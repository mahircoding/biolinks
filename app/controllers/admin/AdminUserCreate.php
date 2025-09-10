<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Logger;
use Altum\Middlewares\Csrf;
use Altum\Models\Package;
use Altum\Middlewares\Authentication;

class AdminUserCreate extends Controller {

    public function index() {

        Authentication::guard('admin');

        /* Default variables */
        $values = [
            'name' => '',
            'email' => '',
            'password' => '',
			'phone' => ''
        ];
		
		if(!empty($_POST)) {

            /* Clean some posted variables */
            $_POST['name']		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $_POST['email']		= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			$_POST['phone']		= filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
			
			$_POST['phone'] 	= phoneFixer($_POST['phone']);

            /* Default variables */
            $values['name'] = $_POST['name'];
            $values['email'] = $_POST['email'];
            $values['password'] = $_POST['password'];
			$values['phone'] = $_POST['phone'];
			
			/* Format Message Whatsapp */
			$dashboard = SITE_URL.'login';
			$wa_message = $this->settings->whatsapp_notifications->whatsapps;
			$wa_message = str_replace("{%USER%}",ucwords($_POST['name']),$wa_message);
			$wa_message = str_replace("{%EMAIL%}",$_POST['email'],$wa_message);
			$wa_message = str_replace("{%PASSWORD%}",$_POST['password'],$wa_message);
			$wa_message = str_replace("{%PHONE%}",$_POST['phone'],$wa_message);
			$wa_message = str_replace("{ÚSHBOARD%}",$dashboard,$wa_message);

            /* Define some variables */
            $fields = ['name', 'email' ,'password' ,'phone'];

            /* Check for any errors */
            foreach($_POST as $key=>$value) {
                if(empty($value) && in_array($key, $fields) == true) {
                    $_SESSION['error'][] = $this->language->global->error_message->empty_fields;
                    break 1;
                }
            }

            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(strlen($_POST['name']) < 3 || strlen($_POST['name']) > 32) {
                $_SESSION['error'][] = $this->language->admin_user_create->error_message->name_length;
            }
            if(Database::exists('user_id', 'users', ['email' => $_POST['email']])) {
                $_SESSION['error'][] = $this->language->admin_user_create->error_message->email_exists;
            }
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'][] = $this->language->admin_user_create->error_message->invalid_email;
            }
            if(strlen(trim($_POST['password'])) < 6) {
                $_SESSION['error'][] = $this->language->admin_user_create->error_message->short_password;
            }
			//if(strlen($_POST['phone']) < 10 || strlen($_POST['phone']) > 14) {
            //    $_SESSION['error'][] = $this->language->admin_user_create->error_message->whatsapp_number;
            //}
			
			/* Checking User Minimum Licenses to Create Another user */
			$licenses_user = $this->user->ulicense;
			if(is_null($licenses_user) || $licenses_user == -1) {
			} else {
				if($licenses_user==0)
					$_SESSION['error'][] = $this->language->admin_user_create->error_message->licenses_limit;
			}
			
			
			$whitelabel_id = $this->user->whitelabel_id;

            /* If there are no errors continue the registering process */
            if(empty($_SESSION['error'])) {
                /* Define some needed variables */
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $active = 1;
                $email_code = '';
                $last_user_agent = Database::clean_string($_SERVER['HTTP_USER_AGENT']);
                $total_logins = 0;
                $package_id = 'trial';
                $package_expiration_date = date("Y-m-d H:i:s", strtotime('+' . $this->settings->package_trial->days . ' days'));
                $ip = get_ip();
                $package_settings = json_encode($this->settings->package_trial->settings);
				
				if($pkgs=Database::get('*', 'packages', ['uid' => $this->user->user_id, 'is_trial' => 1, 'is_default' => 1])) {
					$package_expiration_date = date("Y-m-d H:i:s", strtotime('+' . $pkgs->trial_expired));
					$package_settings = $pkgs->settings;
					$package_id = $pkgs->package_id;
				}
				
				/* Decrease License for the user */
				if(is_null($licenses_user) || $licenses_user == -1) {
				} else {
					if($licenses_user<-1) $licenses_user = 1;
					if($licenses_user>300) $licenses_user = 300;
					if(intval($licenses_user) > 0) {
						$licenses_user -= 1;
						$stmt = Database::$database->prepare("UPDATE `users` SET `ulicense` = ? WHERE `user_id` = ?");
						$stmt->bind_param('ss', $licenses_user, $this->user->user_id);
						$stmt->execute();
						$stmt->close();
					}
				}

                /* Add the user to the database */
                $stmt = Database::$database->prepare("INSERT INTO `users` (`password`, `email`, `email_activation_code`, `name`, `phone`, `package_id`, `package_expiration_date`, `package_settings`, `active`, `date`, `ip`, `last_user_agent`, `total_logins`, `ids_insert`, `whitelabel_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sssssssssssssss', $password, $_POST['email'], $email_code, $_POST['name'], $_POST['phone'], $package_id, $package_expiration_date, $package_settings, $active, \Altum\Date::$date, $ip, $last_user_agent, $total_logins, $this->user->user_id, $whitelabel_id);
                $stmt->execute();
                $registered_user_id = $stmt->insert_id;
                $stmt->close();
				
				if($this->settings->whatsapp_notifications->api_key)
					woowa_notifications($_POST['phone'],$wa_message,$this->settings->whatsapp_notifications->api_key);
				
                /* Log the action */
                Logger::users($registered_user_id, 'register.admin_register');

                /* Success message */
                $_SESSION['success'][] = $this->language->admin_user_create->success_message->created;

                /* Redirect */
                redirect('admin/user-update/' . $registered_user_id);
            }

        }

        /* Main View */
        $data = [
            'values' => $values
        ];

        $view = new \Altum\Views\View('admin/user-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
