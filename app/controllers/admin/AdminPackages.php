<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Middlewares\Authentication;
use Altum\Models\User;

class AdminPackages extends Controller {

    public function index() {

        Authentication::guard('admin');
		
		if($this->user->superagency == 'Y' || $this->user->agency == 'Y' || $this->user->subagency == 'Y' || $this->user->whitelabel == 'Y'){
			$packages_result = Database::$database->query("SELECT * FROM `packages` WHERE uid = '{$this->user->user_id}' ORDER BY `package_id` ASC");
		} else {
			$packages_result = Database::$database->query("SELECT * FROM `packages` ORDER BY `package_id` ASC");
		}
		
        /* Main View */
        $data = [
            'packages_result' => $packages_result
        ];

        $view = new \Altum\Views\View('admin/packages/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        Authentication::guard();

        $package_id = (isset($this->params[0])) ? $this->params[0] : false;

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
        }

        if(empty($_SESSION['error'])) {

            /* Get all the users with this package that have subscriptions and cancel them */
            $result = $this->database->query("SELECT `user_id`, `payment_subscription_id` FROM `users` WHERE `package_id` = {$package_id} AND `payment_subscription_id` <> ''");

            while($row = $result->fetch_object()) {
                try {
                    (new User(['settings' => $this->settings, 'user' => $row]))->cancel_subscription();
                } catch (\Exception $exception) {

                    /* Output errors properly */
                    if(DEBUG) {
                        echo $exception->getCode() . '-' . $exception->getMessage();

                        die();
                    }

                }

                /* Change the user package to custom and leave their current features they paid for on */
                //$this->database->query("UPDATE `users` SET `package_id` = 'custom' WHERE `user_id` = {$row->user_id}");

            }
			
			if($this->user->superagency == 'Y' || $this->user->agency == 'Y' || $this->user->subagency == 'Y' || $this->user->whitelabel == 'Y'){
				if(!$package = Database::get('*', 'packages', ['package_id' => $package_id, 'uid' => $this->user->user_id])) {
					redirect('admin/packages');
				}
			} else {
				if(!$package = Database::get('*', 'packages', ['package_id' => $package_id, 'uid' => 0])) {
					redirect('admin/packages');
				}
			}
			
			/* Change the user package to custom and leave their current features they paid for on */
            $this->database->query("UPDATE `users` SET `package_id` = 'custom' WHERE `package_id` = {$package_id}");

            /* Delete the package */
			Database::$database->query("DELETE FROM `packages` WHERE `package_id` = {$package_id}");

            redirect('admin/packages');

        }

        die();
    }

}
