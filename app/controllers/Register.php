<?php

namespace Altum\Controllers;

use Altum\Captcha;
use Altum\Database\Database;
use Altum\Language;
use Altum\Logger;
use Altum\Middlewares\Authentication;

class Register extends Controller {

    public function index() {

        /* Check if Registration is enabled first */
        if(!$this->settings->register_is_enabled) {
            redirect();
        }

        Authentication::guard('guest');

        $redirect = 'dashboard';
        if(isset($_GET['redirect']) && $redirect = $_GET['redirect']) {
            $redirect = Database::clean_string($redirect);
        }

        /* Default variables */
        $values = [
            'name' => '',
            'email' => '',
			'phone' => '',
            'password' => ''
        ];

        /* Initiate captcha */
        $captcha = new Captcha([
            'recaptcha' => $this->settings->captcha->recaptcha_is_enabled,
            'recaptcha_public_key' => $this->settings->captcha->recaptcha_public_key,
            'recaptcha_private_key' => $this->settings->captcha->recaptcha_private_key
        ]);
		
		$main_server_name = null;
		if(trim($_SERVER['SERVER_NAME'])!=BASE_DOMAIN)
			$main_server_name = str_replace('www.','',trim($_SERVER['SERVER_NAME']));

        if(!empty($_POST)) {
            /* Clean some posted variables */
            $_POST['name']		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
			$_POST['phone']		= filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['email']		= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			
			$_POST['phone'] 	= phoneFixer($_POST['phone']);
			$licenses_user = 0;
			$whitelabel_id = 0;

            /* Default variables */
            $values['name'] = $_POST['name'];
            $values['email'] = $_POST['email'];
			$values['phone'] = $_POST['phone'];
            $values['password'] = $_POST['password'];

            /* Define some variables */
            $fields = ['name', 'email', 'phone' ,'password'];

            /* Check for any errors */
            foreach($_POST as $key => $value) {
                if(empty($value) && in_array($key, $fields) == true) {
                    $_SESSION['error'][] = $this->language->global->error_message->empty_fields;
                    break 1;
                }
            }
            if(!$captcha->is_valid()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_captcha;
            }
            if(strlen($_POST['name']) < 3 || strlen($_POST['name']) > 32) {
                $_SESSION['error'][] = $this->language->register->error_message->name_length;
            }
			
			if (strlen($_POST['phone']) < 10 || strlen($_POST['phone']) > 14) {
				$_SESSION['error'][] = $this->language->register->error_message->phone_length;
			}
			
			if(Database::exists('user_id', 'users', ['phone' => $_POST['phone']])) {
                $_SESSION['error'][] = $this->language->register->error_message->phone_exists;
            }
			
            if(Database::exists('user_id', 'users', ['email' => $_POST['email']])) {
                $_SESSION['error'][] = $this->language->register->error_message->email_exists;
            }
			
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'][] = $this->language->register->error_message->invalid_email;
            }
            if(strlen(trim($_POST['password'])) < 6) {
                $_SESSION['error'][] = $this->language->register->error_message->short_password;
            }
			
			if($main_server_name) {
				if(!$whitelabel = Database::get('*', 'whitelabel', ['url' => $main_server_name])) {
					$_SESSION['error'][] = "Failed to register user";
				}
			}
			
			if($main_server_name) {
				$rs_wl = Database::$database->query("SELECT a.`name`, a.`email`, a.`phone`, a.`ulicense`, a.`ids_insert`, a.`whitelabel`, a.`whitelabel_id`, b.`id`, b.`url` FROM `users` a LEFT JOIN `whitelabel` b ON a.`whitelabel_id` = b.`id` WHERE b.`url` = '{$main_server_name}' AND a.`type` = 1");
				$whitelabel = $rs_wl&&$rs_wl->num_rows ? $rs_wl->fetch_object() : false;
				if(!$whitelabel) {
					$_SESSION['error'][] = "Failed to register user";
				} else {
					$licenses_user = $whitelabel->ulicense;
					$whitelabel_id = $whitelabel->whitelabel_id;
					if($whitelabel->whitelabel != 'Y') {
						$_SESSION['error'][] = "Failed to register user";
					}
					if(is_null($licenses_user) || $licenses_user == -1) {
					} else {
						if($licenses_user==0)
							$_SESSION['error'][] = "Failed to register user, no license!";
					}
				}
			}

            /* If there are no errors continue the registering process */
            if(empty($_SESSION['error'])) {
                /* Define some needed variables */
                $password                   = password_hash($_POST['password'], PASSWORD_DEFAULT);
                //$active 	                = (int) !$this->settings->email_confirmation;
				$active 	                = 0;
                $email_code                 = md5($_POST['email'] . microtime());
                $last_user_agent            = Database::clean_string($_SERVER['HTTP_USER_AGENT']);
                $total_logins               = $active == '1' ? 1 : 0;
                $package_id                 = 'trial';
                $package_expiration_date    = date("Y-m-d H:i:s", strtotime('+' . $this->settings->package_trial->days . ' days'));
                $ip                         = get_ip();
                $package_settings           = json_encode($this->settings->package_trial->settings);

                if($main_server_name) {
					if($pkgs=Database::get('*', 'packages', ['uid' => $whitelabel->user_id, 'is_trial' => 1, 'is_default' => 1])) {
						$package_expiration_date = date("Y-m-d H:i:s", strtotime('+' . $pkgs->trial_expired));
						$package_settings = $pkgs->settings;
						$package_id = $pkgs->package_id;
					}
					
					/* Decrease License for the owner user */
					if(is_null($licenses_user) || $licenses_user == -1) {
					} else {
						if($licenses_user<-1) $licenses_user = 1;
						if(intval($licenses_user) > 0) {
							$licenses_user -= 1;
							$stmt = Database::$database->prepare("UPDATE `users` SET `ulicense` = ? WHERE `user_id` = ?");
							$stmt->bind_param('ss', $licenses_user, $whitelabel->user_id);
							$stmt->execute();
							$stmt->close();
						}
					}
					
					/* Add the user to the database to whitelabel */
					$stmt = Database::$database->prepare("INSERT INTO `users` (`password`, `email`, `phone`, `email_activation_code`, `name`, `package_id`, `package_expiration_date`, `package_settings`, `language`, `active`, `date`, `ip`, `last_user_agent`, `total_logins`, `ids_insert`, `whitelabel_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
					$stmt->bind_param('ssssssssssssssss', $password, $_POST['email'], $_POST['phone'], $email_code, $_POST['name'], $package_id, $package_expiration_date, $package_settings, Language::$language, $active, \Altum\Date::$date, $ip, $last_user_agent, $total_logins, $whitelabel->user_id, $whitelabel->id);
					$stmt->execute();
					$registered_user_id = $stmt->insert_id;
					$stmt->close();
				} else {
					/* Add the user to the database to smartbio */
					$stmt = Database::$database->prepare("INSERT INTO `users` (`password`, `email`, `phone`, `email_activation_code`, `name`, `package_id`, `package_expiration_date`, `package_settings`, `language`, `active`, `date`, `ip`, `last_user_agent`, `total_logins`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
					$stmt->bind_param('ssssssssssssss', $password, $_POST['email'], $_POST['phone'], $email_code, $_POST['name'], $package_id, $package_expiration_date, $package_settings, Language::$language, $active, \Altum\Date::$date, $ip, $last_user_agent, $total_logins);
					$stmt->execute();
					$registered_user_id = $stmt->insert_id;
					$stmt->close();
				}

                /* Log the action */
                Logger::users($registered_user_id, 'register.register');

                /* Send notification to admin if needed */
                if($this->settings->email_notifications->new_user && !empty($this->settings->email_notifications->emails)) {

                    send_mail(
                        $this->settings,
                        explode(',', $this->settings->email_notifications->emails),
                        $this->language->global->emails->admin_new_user_notification->subject,
                        sprintf($this->language->global->emails->admin_new_user_notification->body, $_POST['name'], $_POST['email'])
                    );


                }

                /* If active = 1 then login the user, else send the user an activation email */
                if($active == '1') {
                    $_SESSION['user_id'] = $registered_user_id;
                    $_SESSION['success'] = $this->language->register->success_message->login;

                    Logger::users($registered_user_id, 'login.success');

                    redirect($redirect);
                } else {

                    /* Prepare the email */
                    $email_template = get_email_template(
                        [
                            '{{NAME}}' => $_POST['name'],
                        ],
                        $this->language->global->emails->user_activation->subject,
                        [
                            '{{ACTIVATION_LINK}}' => url('activate-user/' . md5($_POST['email']) . '/' . $email_code . '?redirect=' . $redirect),
                            '{{NAME}}' => $_POST['name'],
                        ],
                        $this->language->global->emails->user_activation->body
                    );

                    //send_mail($this->settings, $_POST['email'], $email_template->subject, $email_template->body);
					send_mail_mailketing($this->settings, $_POST['email'], $email_template->subject, $email_template->body);
					
                    $_SESSION['success'][] = $this->language->register->success_message->registration;
                }

            }
        }

        /* Main View */
        $data = [
            'values' => $values,
            'captcha' => $captcha
        ];

        $view = new \Altum\Views\View('register/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
