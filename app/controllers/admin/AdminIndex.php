<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class AdminIndex extends Controller {

    public function index() {
	
        Authentication::guard('admin');
        $ids =  $this->user->user_id;
		$license = $this->user->ulicense;
		
		$total_users = Database::$database->query("
            SELECT
              (SELECT COUNT(*) FROM `users` WHERE ids_insert = $ids) AS `total_users`
        ")->fetch_object();
        if($this->user->whitelabel == "Y" || $this->user->superagency == "Y" || $this->user->agency == "Y" || $this->user->subagency == "Y"){
        $users = Database::$database->query("
            SELECT
              (SELECT COUNT(*) FROM `users` WHERE ids_insert = $ids and  MONTH(`last_activity`) = MONTH(CURRENT_DATE()) AND YEAR(`last_activity`) = YEAR(CURRENT_DATE())) AS `active_users_month`,
              (SELECT COUNT(*) FROM `users` WHERE ids_insert = $ids) AS `active_users`
        ")->fetch_object();

        $links = Database::$database->query("
        SELECT
        (SELECT COUNT(*) FROM `track_links` join links on track_links.link_id = links.link_id join users on links.user_id = users.user_id WHERE ids_insert = $ids and MONTH(track_links.date) = MONTH(CURRENT_DATE()) AND YEAR(track_links.date) = YEAR(CURRENT_DATE())) AS `clicks_month`,
        (SELECT SUM(`clicks`) FROM `links` join users on links.user_id = users.user_id where ids_insert = $ids) AS `clicks`
        ")->fetch_object();
        }
        else{
            $users = Database::$database->query("
            SELECT
              (SELECT COUNT(*) FROM `users` WHERE MONTH(`last_activity`) = MONTH(CURRENT_DATE()) AND YEAR(`last_activity`) = YEAR(CURRENT_DATE())) AS `active_users_month`,
              (SELECT COUNT(*) FROM `users`) AS `active_users`
        ")->fetch_object();

        $links = Database::$database->query("
            SELECT
              (SELECT COUNT(*) FROM `track_links` WHERE MONTH(`date`) = MONTH(CURRENT_DATE()) AND YEAR(`date`) = YEAR(CURRENT_DATE())) AS `clicks_month`,
              (SELECT SUM(`clicks`) FROM `links`) AS `clicks`
        ")->fetch_object();
        }

        if($this->settings->payment->is_enabled) {
            $payments = Database::$database->query("SELECT COUNT(*) AS `payments`, IFNULL(TRUNCATE(SUM(`amount`), 2), 0) AS `earnings` FROM `payments` WHERE `currency` = '{$this->settings->payment->currency}'")->fetch_object();

            /* Data for the months transactions and earnings */
            $payments_month = Database::$database->query("SELECT COUNT(*) AS `payments`, IFNULL(TRUNCATE(SUM(`amount`), 2), 0) AS `earnings` FROM `payments` WHERE `currency` = '{$this->settings->payment->currency}' AND MONTH(`date`) = MONTH(CURRENT_DATE()) AND YEAR(`date`) = YEAR(CURRENT_DATE())")->fetch_object();

        } else {
            $payments = $payments_month = null;
        }

        /* Login Modal */
        $view = new \Altum\Views\View('admin/users/user_login_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
			'id_user' => $ids,
			'total_users' => $total_users,
			'license' => $license,
            'links' => $links,
            'users' => $users,
			'settings' => $this->settings,
            'payments' => $payments,
            'payments_month' => $payments_month
        ];

        $view = new \Altum\Views\View('admin/index/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
