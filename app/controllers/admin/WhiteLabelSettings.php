<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class WhiteLabelSettings extends Controller {

    public function index() {

        /* Check if Registration is enabled first */

        Authentication::guard('admin');
		
		$whitelabel = null;

        /* Default variables */
        $values = [
            'url' => '',
            'index_url' => '',
            'title' => '',
            'logo' => '',
            'favicon' => ''
        ];
		
		if(!$whitelabel = Database::get('*', 'whitelabel', ['id' => $this->user->whitelabel_id])) {
            $whitelabel = null;
        }

        if(!empty($_POST)) {
            /* Clean some posted variables */
            $image_allowed_extensions = ['jpg', 'jpeg', 'png', 'svg', 'ico'];

            $_POST['url'] = trim_url(filter_var($_POST['index_url'], FILTER_SANITIZE_STRING));
            $_POST['title'] = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
            $_POST['index_url'] = filter_var($_POST['index_url'], FILTER_SANITIZE_STRING);
            $_POST['terms_url'] = filter_var($_POST['terms_and_conditions_url'], FILTER_SANITIZE_STRING);
            $_POST['privacy_url'] = filter_var($_POST['privacy_policy_url'], FILTER_SANITIZE_STRING);
            $_POST['logo'] = (!empty($_FILES['logo']['name']));
            $_POST['favicon'] = (!empty($_FILES['favicon']['name']));

            /* Default variables */
            $values['url'] = $_POST['index_url'];
            $values['title'] = $_POST['title'];
            $values['index_url'] = $_POST['index_url'];
            $values['terms_url'] = $_POST['terms_and_conditions_url'];
            $values['privacy_url'] = $_POST['privacy_policy_url'];
            $values['logo'] = $_POST['logo'];
            $values['favicon'] = $_POST['favicon'];

            /* Define some variables */
            $fields = ['url', 'index_url', 'terms_url', 'privacy_url', 'title'];

            /* Check for any errors */
            foreach($_POST as $key => $value) {
                if(empty($value) && in_array($key, $fields) == true) {
                    $_SESSION['error'][] = $this->language->global->error_message->empty_fields;
                    break 1;
                }
            }

            /* If there are no errors continue the registering process */
            if($_POST['logo']) {
                $logo_file_name = $_FILES['logo']['name'];
                $logo_file_extension = explode('.', $logo_file_name);
                $logo_file_extension = strtolower(end($logo_file_extension));
                $logo_file_temp = $_FILES['logo']['tmp_name'];
                $logo_name = $_POST['logo'] ? '' : whitelabel('logo');
                $logo_file_size = $_FILES['logo']['size'];
                list($logo_width, $logo_height) = getimagesize($logo_file_temp);
                $logo_new_name = md5(time() . rand()) . '.' . $logo_file_extension;
                $_POST['logo'] = $logo_new_name;

                if(!in_array($logo_file_extension, $image_allowed_extensions)) {
                    $_SESSION['error'][] = $this->language->global->error_message->invalid_file_type;
                }

                if(!is_writable(UPLOADS_PATH . 'whitelabel/logo/')) {
                    $_SESSION['error'][] = sprintf($this->language->global->error_message->directory_not_writable, UPLOADS_PATH . 'whitelabel/logo/');
                }
                move_uploaded_file($logo_file_temp, UPLOADS_PATH . 'whitelabel/logo/' . $logo_new_name);
                $stmt = Database::$database->prepare("UPDATE `whitelabel` SET `logo` = ? WHERE `url` = '{$_SERVER['SERVER_NAME']}'");
                $stmt->bind_param('s', $_POST['logo']);
                $stmt->execute();
                $stmt->close();
            }
            if($_POST['favicon']) {
                $favicon_file_name = $_FILES['favicon']['name'];
                $favicon_file_extension = explode('.', $favicon_file_name);
                $favicon_file_extension = strtolower(end($favicon_file_extension));
                $favicon_file_temp = $_FILES['favicon']['tmp_name'];
                $favicon_name = $_POST['favicon'] ? '' : whitelabel('favicon');
                $favicon_file_size = $_FILES['favicon']['size'];
                list($favicon_width, $favicon_height) = getimagesize($favicon_file_temp);
                $favicon_new_name = md5(time() . rand()) . '.' . $favicon_file_extension;
                $_POST['favicon'] = $favicon_new_name;

                if(!in_array($favicon_file_extension, $image_allowed_extensions)) {
                    $_SESSION['error'][] = $this->language->global->error_message->invalid_file_type;
                }

                if(!is_writable(UPLOADS_PATH . 'whitelabel/favicon/')) {
                    $_SESSION['error'][] = sprintf($this->language->global->error_message->directory_not_writable, UPLOADS_PATH . 'whitelabel/favicon/');
                }
                move_uploaded_file($favicon_file_temp, UPLOADS_PATH . 'whitelabel/favicon/' . $favicon_new_name);
                $stmt = Database::$database->prepare("UPDATE `whitelabel` SET `favicon` = ? WHERE `url` = '{$_SERVER['SERVER_NAME']}'");
                $stmt->bind_param('s', $_POST['favicon']);
                $stmt->execute();
                $stmt->close();
            }
            
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            /* Add the whitelabel to the database */
			/*
            if(empty($_SESSION['error'])) {
                if(whitelabel('url') === ''|| Database::exists('url', 'whitelabel', ['url' => $_POST['url']])) {
                    $stmt = Database::$database->prepare("UPDATE `whitelabel` SET  `url` = ?, `index_url` = ?, `terms_url` = ?, `privacy_url` = ?, `title` = ? WHERE `url` = '{$_SERVER['SERVER_NAME']}'");
                    $stmt->bind_param('sssss', $_POST['url'], $_POST['index_url'], $_POST['terms_url'], $_POST['privacy_url'], $_POST['title']);
                    $stmt->execute();
                    $stmt->close();
                } elseif(!Database::exists('url', 'whitelabel', ['url' => $_POST['url']])) {
                    $stmt = Database::$database->prepare("INSERT INTO `whitelabel` (`user_id`, `url`, `index_url`, `terms_url`, `privacy_url`, `title`, `logo`, `favicon`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param('ssssssss', $_POST['ids_insert'], $_POST['url'], $_POST['index_url'], $_POST['terms_url'], $_POST['privacy_url'], $_POST['title'], $_POST['logo'], $_POST['favicon']);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    $_SESSION['error'][] = "The url has already been registered";
                }
            }
			*/
			
			if(empty($_SESSION['error'])) {
				
				if($whitelabel) {
					
					$stmt = Database::$database->prepare("UPDATE `whitelabel` SET `index_url` = ?, `terms_url` = ?, `privacy_url` = ?, `title` = ? WHERE `id` = '{$whitelabel->id}'");
                    $stmt->bind_param('ssss', $_POST['index_url'], $_POST['terms_url'], $_POST['privacy_url'], $_POST['title']);
                    $stmt->execute();
                    $stmt->close();
					
				}
				
			}
			
            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItem('settings');

            /* Set message */
            $_SESSION['success'][] = $this->language->admin_settings->success_message->saved;
        }
        
		/* Main View */
        $data = [
            'whitelabel'              => $whitelabel,
        ];
		
        $view = new \Altum\Views\View('admin/whitelabel-settings/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function removelogo() {

        Authentication::guard('admin');

        if(!Csrf::check()) {
            redirect('whitelabel/whitelabel-settings');
        }

        /* Delete the current logo */
        if(file_exists(UPLOADS_PATH . 'whitelabel/logo/' . whitelabel('logo'))) {
            unlink(UPLOADS_PATH . 'whitelabel/logo/' . whitelabel('logo'));
        }

        /* Remove it from db */
        Database::$database->query("UPDATE `whitelabel` SET `logo` = '' WHERE `url` = '{$_SERVER['SERVER_NAME']}'");
        
        /* Set message & Redirect */
        $_SESSION['success'][] = $this->language->global->success_message->basic;

        redirect('whitelabel/whitelabel-settings');

    }

    public function removefavicon() {

        Authentication::guard('admin');

        if(!Csrf::check()) {
            redirect('whitelabel/whitelabel-settings');
        }

        /* Delete the current logo */
        if(file_exists(UPLOADS_PATH . 'whitelabel/favicon/' . whitelabel('favicon'))) {
            unlink(UPLOADS_PATH . 'whitelabel/favicon/' . whitelabel('favicon'));
        }

        /* Remove it from db */
        Database::$database->query("UPDATE `whitelabel` SET `logo` = '' WHERE `url` = '{$_SERVER['SERVER_NAME']}'");

        /* Set message & Redirect */
        $_SESSION['success'][] = $this->language->global->success_message->basic;
        redirect('whitelabel/whitelabel-settings');

    }

    public function testemail() {

        Authentication::guard('admin');

        if(!Csrf::check()) {
            redirect('whitelabel/whitelabel-settings');
        }

        $result = send_mail($this->settings, $this->settings->smtp->from, $this->settings->title . ' - Test Email', 'This is just a test email to confirm the smtp email settings!', true);

        if($result->ErrorInfo == '') {
            $_SESSION['success'][] = $this->language->admin_settings->success_message->email;
        } else {
            $_SESSION['error'][] = sprintf($this->language->admin_settings->error_message->email, $result->ErrorInfo);
        }

        redirect('whitelabel/whitelabel-settings');
    }

    public function testttt() {

        Authentication::guard('admin');

        if(!Csrf::check()) {
            redirect('whitelabel/whitelabel-settings');
        }

        $result = send_mail($this->settings, $this->settings->smtp->from, $this->settings->title . ' - Test Email', 'This is just a test email to confirm the smtp email settings!', true);

        if($result->ErrorInfo == '') {
            $_SESSION['success'][] = $this->language->admin_settings->success_message->email;
        } else {
            $_SESSION['error'][] = sprintf($this->language->admin_settings->error_message->email, $result->ErrorInfo);
        }

        redirect('whitelabel/whitelabel-settings');
    }
}

