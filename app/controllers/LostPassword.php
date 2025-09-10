<?php

namespace Altum\Controllers;

use Altum\Captcha;
use Altum\Database\Database;
use Altum\Language;
use Altum\Middlewares\Authentication;

class LostPassword extends Controller {

    public function index() {

        Authentication::guard('guest');

        /* Default values */
        $values = [
            'email' => ''
        ];

        /* Initiate captcha */
        $captcha = new Captcha([
            'recaptcha' => $this->settings->captcha->recaptcha_is_enabled,
            'recaptcha_public_key' => $this->settings->captcha->recaptcha_public_key,
            'recaptcha_private_key' => $this->settings->captcha->recaptcha_private_key
        ]);

        if(!empty($_POST)) {
			$main_server_name = null;
			if(trim($_SERVER['SERVER_NAME'])!=BASE_DOMAIN)
				$main_server_name = str_replace('www.','',trim($_SERVER['SERVER_NAME']));
			
			$whitelabel = null;
			if($main_server_name) {
				if(!$whitelabel = Database::get('*', 'whitelabel', ['url' => $main_server_name])) {
					$_SESSION['error'][] = "Invalid domain!.";
				} else {
					$this->settings->title = $whitelabel->title;
					$this->settings->logo = $whitelabel->logo;
				}
			}
			
            /* Clean the posted variable */
            $_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $values['email'] = $_POST['email'];

            /* Check for any errors */
            if(!$captcha->is_valid()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_captcha;
            }

            /* If there are no errors, resend the activation link */
            if(empty($_SESSION['error'])) {

                if($this_account = Database::get(['user_id', 'email', 'name', 'language'], 'users', ['email' => $_POST['email']])) {
                    /* Define some variables */
                    $lost_password_code = md5($_POST['email'] . microtime());

                    /* Update the current activation email */
                    Database::$database->query("UPDATE `users` SET `lost_password_code` = '".$lost_password_code."' WHERE `user_id` = {$this_account->user_id}");

                    /* Get the language for the user */
                    $language = Language::get($this_account->language);
					
					$url_reset = url('reset-password/' . $_POST['email'] . '/' . $lost_password_code);
					if($whitelabel) {
						$url_reset = str_replace(BASE_DOMAIN,$whitelabel->url,$url_reset);
					}

                    /* Prepare the email */
                    $email_template = get_email_template(
                        [
                            '{{NAME}}' => $this_account->name,
                        ],
                        $language->global->emails->user_lost_password->subject,
                        [
                            '{{LOST_PASSWORD_LINK}}' => url('reset-password/' . $_POST['email'] . '/' . $lost_password_code),
                            '{{NAME}}' => $this_account->name,
                        ],
                        $language->global->emails->user_lost_password->body
                    );
					//print_r($lost_password_code);
                    /* Send the email */
                    //send_mail($this->settings, $this_account->email, $email_template->subject, $email_template->body);
					send_mail_mailketing($this->settings, $this_account->email, $email_template->subject, $email_template->body);
				}

                /* Set success message */
                $_SESSION['success'][] = $this->language->lost_password->notice_message->success;
            }
        }

        /* Prepare the View */
        $data = [
            'values'    => $values,
            'captcha'   => $captcha
        ];

        $view = new \Altum\Views\View('lost-password/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
