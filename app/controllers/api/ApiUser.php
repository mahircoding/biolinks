<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Language;
use Altum\Logger;
use Altum\Response;

class ApiUser extends Controller {

    /**
     * Authenticate API request using API key
     * @return bool
     */
    private function authenticate() {
        // Check for API key in header first
        $api_key = null;
        
        // Try to get from X-API-Key header
        if(isset($_SERVER['HTTP_X_API_KEY'])) {
            $api_key = $_SERVER['HTTP_X_API_KEY'];
        }
        // Fallback to Authorization header
        else if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $api_key = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        }
        // Fallback to POST data
        else if(isset($_POST['api_key'])) {
            $api_key = $_POST['api_key'];
        }

        if(empty($api_key)) {
            Response::json('API key is required', 'error', [], 401);
            return false;
        }

        // Get API key from settings
        $stored_api_key = $this->settings->api_key ?? null;

        if(empty($stored_api_key)) {
            Response::json('API not configured', 'error', [], 500);
            return false;
        }

        if($api_key !== $stored_api_key) {
            Response::json('Invalid API key', 'error', [], 401);
            return false;
        }

        return true;
    }

    /**
     * Create new user via API
     * POST /api/user-create
     */
    public function create() {
        // Authenticate
        if(!$this->authenticate()) {
            return;
        }

        // Check if registration is enabled
        if(!$this->settings->register_is_enabled) {
            Response::json('Registration is disabled', 'error', [], 403);
            return;
        }

        // Get JSON input
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        // Fallback to POST if JSON is empty
        if(empty($data)) {
            $data = $_POST;
        }

        // Validate required fields
        $required_fields = ['name', 'email', 'phone', 'password'];
        foreach($required_fields as $field) {
            if(empty($data[$field])) {
                Response::json("Field '{$field}' is required", 'error', ['code' => 'MISSING_FIELD'], 400);
                return;
            }
        }

        // Clean input data
        $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($data['phone'], FILTER_SANITIZE_NUMBER_INT);
        $phone = phoneFixer($phone);
        $password = $data['password'];
        $package_id = $data['package_id'] ?? 'trial';
        $active = isset($data['active']) ? (int)$data['active'] : 1;

        // New fields
        $addon_digital_products = isset($data['addon_digital_products']) ? (int)$data['addon_digital_products'] : 0;
        $addon_tripay = isset($data['addon_tripay']) ? (int)$data['addon_tripay'] : 0;
        $esc_package = isset($data['esc_package']) ? (int)$data['esc_package'] : 0;
        $esc_expired = isset($data['esc_expired']) ? $data['esc_expired'] : null;
        $ro_pro_package = isset($data['ro_pro_package']) ? (int)$data['ro_pro_package'] : 0;
        
        if($esc_expired) {
            $esc_expired = date("Y-m-d H:i:s", strtotime($esc_expired));
        }

        // Validate name length
        if(strlen($name) < 3 || strlen($name) > 32) {
            Response::json('Name must be between 3 and 32 characters', 'error', ['code' => 'INVALID_NAME_LENGTH'], 400);
            return;
        }

        // Validate phone length
        if(strlen($phone) < 10 || strlen($phone) > 14) {
            Response::json('Phone number must be between 10 and 14 digits', 'error', ['code' => 'INVALID_PHONE_LENGTH'], 400);
            return;
        }

        // Check if phone already exists
        if(Database::exists('user_id', 'users', ['phone' => $phone])) {
            Response::json('Phone number already exists', 'error', ['code' => 'PHONE_EXISTS'], 400);
            return;
        }

        // Check if email already exists
        if(Database::exists('user_id', 'users', ['email' => $email])) {
            Response::json('Email already exists', 'error', ['code' => 'EMAIL_EXISTS'], 400);
            return;
        }

        // Validate email format
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::json('Invalid email format', 'error', ['code' => 'INVALID_EMAIL'], 400);
            return;
        }

        // Validate password length
        if(strlen(trim($password)) < 6) {
            Response::json('Password must be at least 6 characters', 'error', ['code' => 'SHORT_PASSWORD'], 400);
            return;
        }

        // Check for whitelabel domain
        $main_server_name = null;
        $whitelabel = null;
        $whitelabel_id = 0;
        $licenses_user = 0;

        if(trim($_SERVER['SERVER_NAME']) != BASE_DOMAIN) {
            $main_server_name = str_replace('www.', '', trim($_SERVER['SERVER_NAME']));
        }

        if($main_server_name) {
            $rs_wl = Database::$database->query("SELECT a.`name`, a.`email`, a.`phone`, a.`ulicense`, a.`ids_insert`, a.`whitelabel`, a.`whitelabel_id`, a.`user_id`, b.`id`, b.`url` FROM `users` a LEFT JOIN `whitelabel` b ON a.`whitelabel_id` = b.`id` WHERE b.`url` = '{$main_server_name}' AND a.`type` = 1");
            $whitelabel = $rs_wl && $rs_wl->num_rows ? $rs_wl->fetch_object() : false;
            
            if(!$whitelabel) {
                Response::json('Failed to register user - invalid whitelabel', 'error', ['code' => 'INVALID_WHITELABEL'], 400);
                return;
            }

            $licenses_user = $whitelabel->ulicense;
            $whitelabel_id = $whitelabel->whitelabel_id;

            if($whitelabel->whitelabel != 'Y') {
                Response::json('Failed to register user - whitelabel not active', 'error', ['code' => 'WHITELABEL_INACTIVE'], 400);
                return;
            }

            if(!is_null($licenses_user) && $licenses_user != -1 && $licenses_user == 0) {
                Response::json('Failed to register user - no license available', 'error', ['code' => 'NO_LICENSE'], 400);
                return;
            }
        }

        // Prepare user data
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $email_code = $active == 1 ? '' : md5($email . microtime());
        $last_user_agent = Database::clean_string($_SERVER['HTTP_USER_AGENT'] ?? 'API');
        $total_logins = $active == 1 ? 1 : 0;
        $ip = get_ip();

        // Get package settings
        $package_settings = '';
        $package_expiration_date = date("Y-m-d H:i:s", strtotime('+' . $this->settings->package_trial->days . ' days'));

        /* Get the package settings */
        if($package_id == 'free') {
            $package_settings = json_encode($this->settings->package_free->settings);
            $package_expiration_date = date("Y-m-d H:i:s", strtotime('+100 years')); // Free plan practically never expires
        } elseif($package_id == 'trial') {
            $package_settings = json_encode($this->settings->package_trial->settings);
            $package_expiration_date = date("Y-m-d H:i:s", strtotime('+' . $this->settings->package_trial->days . ' days'));
        } else {
            /* Try to get the package from the database */
            $package = Database::get('*', 'packages', ['package_id' => $package_id]);

            if($package) {
                $package_settings = $package->settings;
                
                // Default to 30 days for paid packages if not specified
                $package_expiration_date = date("Y-m-d H:i:s", strtotime('+30 days'));
            } else {
                // Fallback to free if package invalid
                $package_id = 'free';
                $package_settings = json_encode($this->settings->package_free->settings);
                $package_expiration_date = date("Y-m-d H:i:s", strtotime('+100 years')); 
            }
        }

        // Override package expiration date if provided
        if(isset($data['package_expiration_date'])) {
            $package_expiration_date = date("Y-m-d H:i:s", strtotime($data['package_expiration_date']));
        }

        if($main_server_name && $whitelabel) {
            if($pkgs = Database::get('*', 'packages', ['uid' => $whitelabel->user_id, 'is_trial' => 1, 'is_default' => 1])) {
                // Only use trial expiration if not manually provided
                if(!isset($data['package_expiration_date'])) {
                    $package_expiration_date = date("Y-m-d H:i:s", strtotime('+' . $pkgs->trial_expired));
                }
                $package_settings = $pkgs->settings;
                $package_id = $pkgs->package_id;
            }

            // Decrease license for the owner user
            if(!is_null($licenses_user) && $licenses_user != -1) {
                if($licenses_user < -1) $licenses_user = 1;
                if(intval($licenses_user) > 0) {
                    $licenses_user -= 1;
                    $stmt = Database::$database->prepare("UPDATE `users` SET `ulicense` = ? WHERE `user_id` = ?");
                    $stmt->bind_param('ss', $licenses_user, $whitelabel->user_id);
                    $stmt->execute();
                    $stmt->close();
                }
            }

            // Insert user with whitelabel
            $stmt = Database::$database->prepare("INSERT INTO `users` (`password`, `email`, `phone`, `email_activation_code`, `name`, `package_id`, `package_expiration_date`, `package_settings`, `language`, `active`, `date`, `ip`, `last_user_agent`, `total_logins`, `ids_insert`, `whitelabel_id`, `addon_digital_products`, `addon_tripay`, `esc_package`, `esc_expired`, `ro_pro_package`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssssssssssssssssss', $hashed_password, $email, $phone, $email_code, $name, $package_id, $package_expiration_date, $package_settings, Language::$language, $active, \Altum\Date::$date, $ip, $last_user_agent, $total_logins, $whitelabel->user_id, $whitelabel->id, $addon_digital_products, $addon_tripay, $esc_package, $esc_expired, $ro_pro_package);
            $stmt->execute();
            $registered_user_id = $stmt->insert_id;
            $stmt->close();
        } else {
            // Insert user without whitelabel
            $stmt = Database::$database->prepare("INSERT INTO `users` (`password`, `email`, `phone`, `email_activation_code`, `name`, `package_id`, `package_expiration_date`, `package_settings`, `language`, `active`, `date`, `ip`, `last_user_agent`, `total_logins`, `addon_digital_products`, `addon_tripay`, `esc_package`, `esc_expired`, `ro_pro_package`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssssssssssssssss', $hashed_password, $email, $phone, $email_code, $name, $package_id, $package_expiration_date, $package_settings, Language::$language, $active, \Altum\Date::$date, $ip, $last_user_agent, $total_logins, $addon_digital_products, $addon_tripay, $esc_package, $esc_expired, $ro_pro_package);
            $stmt->execute();
            $registered_user_id = $stmt->insert_id;
            $stmt->close();
        }

        // Log the action
        Logger::users($registered_user_id, 'api.user_created');

        // Send WhatsApp notification if enabled
        if($this->settings->whatsapp_notifications->api_key) {
            $dashboard = SITE_URL . 'login';
            $wa_message = $this->settings->whatsapp_notifications->whatsapps;
            $wa_message = str_replace("{%USER%}", ucwords($name), $wa_message);
            $wa_message = str_replace("{%EMAIL%}", $email, $wa_message);
            $wa_message = str_replace("{%PASSWORD%}", $password, $wa_message);
            $wa_message = str_replace("{%PHONE%}", $phone, $wa_message);
            $wa_message = str_replace("{%DASHBOARD%}", $dashboard, $wa_message);

            woowa_notifications($phone, $wa_message, $this->settings->whatsapp_notifications->api_key);
        }

        // Return success response
        Response::json('User created successfully', 'success', [
            'user_id' => $registered_user_id,
            'email' => $email,
            'phone' => $phone,
            'package_id' => $package_id,
            'package_expiration_date' => $package_expiration_date,
            'active' => $active,
            'addon_digital_products' => $addon_digital_products,
            'addon_tripay' => $addon_tripay,
            'esc_package' => $esc_package,
            'esc_expired' => $esc_expired,
            'ro_pro_package' => $ro_pro_package
        ], 201);
    }

    /**
     * Update user package via API
     * POST /api/user-package
     */
    public function update_package() {
        // Authenticate
        if(!$this->authenticate()) {
            return;
        }

        // Get JSON input
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        // Fallback to POST if JSON is empty
        if(empty($data)) {
            $data = $_POST;
        }

        // Validate required fields
        if(empty($data['user_id'])) {
            Response::json("Field 'user_id' is required", 'error', ['code' => 'MISSING_FIELD'], 400);
            return;
        }

        if(empty($data['package_id'])) {
            Response::json("Field 'package_id' is required", 'error', ['code' => 'MISSING_FIELD'], 400);
            return;
        }

        $user_id = (int)$data['user_id'];
        $package_id = Database::clean_string($data['package_id']);
        $days = isset($data['days']) ? (int)$data['days'] : 30;

        // Check if user exists
        $user = Database::get('user_id', 'users', ['user_id' => $user_id]);
        if(!$user) {
            Response::json('User not found', 'error', ['code' => 'USER_NOT_FOUND'], 404);
            return;
        }

        // Get package settings
        $package_settings = null;
        
        // Check if it's a trial package
        if($package_id === 'trial') {
            $package_expiration_date = date("Y-m-d H:i:s", strtotime('+' . $this->settings->package_trial->days . ' days'));
            $package_settings = json_encode($this->settings->package_trial->settings);
        } else {
            // Check if package exists in custom packages
            $package = Database::get('*', 'packages', ['package_id' => $package_id]);
            
            if($package) {
                $package_expiration_date = date("Y-m-d H:i:s", strtotime('+' . $days . ' days'));
                $package_settings = $package->settings;
            } else {
                // Use default trial settings if package not found
                $package_expiration_date = date("Y-m-d H:i:s", strtotime('+' . $days . ' days'));
                $package_settings = json_encode($this->settings->package_trial->settings);
            }
        }

        // Update user package
        $stmt = Database::$database->prepare("UPDATE `users` SET `package_id` = ?, `package_expiration_date` = ?, `package_settings` = ? WHERE `user_id` = ?");
        $stmt->bind_param('ssss', $package_id, $package_expiration_date, $package_settings, $user_id);
        $stmt->execute();
        $stmt->close();

        // Log the action
        Logger::users($user_id, 'api.package_updated=' . $package_id);

        // Return success response
        Response::json('Package updated successfully', 'success', [
            'user_id' => $user_id,
            'package_id' => $package_id,
            'package_expiration_date' => $package_expiration_date
        ], 200);
    }

    /**
     * Get all available packages
     * GET /api/packages
     */
    public function get_packages() {
        // Authenticate
        if(!$this->authenticate()) {
            return;
        }

        $packages = [];

        // Add built-in packages
        // Trial package
        $trial_settings = $this->settings->package_trial ?? null;
        $packages[] = [
            'package_id' => 'trial',
            'name' => 'Trial',
            'description' => 'Trial package',
            'days' => $trial_settings->days ?? 7,
            'is_enabled' => 1,
            'is_trial' => 1,
            'settings' => $trial_settings->settings ?? null
        ];

        // Free package
        if(isset($this->settings->package_free)) {
            $free_settings = $this->settings->package_free;
            $packages[] = [
                'package_id' => 'free',
                'name' => 'Free',
                'description' => 'Free package',
                'days' => null,
                'is_enabled' => 1,
                'is_trial' => 0,
                'settings' => $free_settings->settings ?? null
            ];
        }

        // Custom package
        if(isset($this->settings->package_custom)) {
            $custom_settings = $this->settings->package_custom;
            $packages[] = [
                'package_id' => 'custom',
                'name' => 'Custom',
                'description' => 'Custom package',
                'days' => null,
                'is_enabled' => 1,
                'is_trial' => 0,
                'settings' => $custom_settings->settings ?? null
            ];
        }

        try {
            // Get custom packages from database
            $packages_result = Database::$database->query("SELECT * FROM `packages` WHERE `is_enabled` = 1 ORDER BY `package_id` ASC");

            if($packages_result) {
                while($row = $packages_result->fetch_object()) {
                    $packages[] = [
                        'package_id' => $row->package_id,
                        'name' => $row->name,
                        'description' => $row->description ?? '',
                        'monthly_price' => $row->monthly_price ?? 0,
                        'annual_price' => $row->annual_price ?? 0,
                        'lifetime_price' => $row->lifetime_price ?? 0,
                        'days' => $row->trial_days ?? ($row->trial_expired ?? 30),
                        'is_enabled' => $row->is_enabled,
                        'status' => $row->status ?? 'active',
                        'color' => $row->color ?? null,
                        'settings' => $row->settings ?? null
                    ];
                }
            }
        } catch (\Exception $e) {
            // Log error but continue with built-in packages
            Logger::log('API get_packages error: ' . $e->getMessage());
        }

        // Return success response
        Response::json('Packages retrieved successfully', 'success', [
            'total' => count($packages),
            'packages' => $packages
        ], 200);
    }
}
