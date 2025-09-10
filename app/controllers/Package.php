<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class Package extends Controller {

    public function index() {

        //if(!$this->settings->payment->is_enabled) {
        //    redirect();
        //}

        $type = isset($this->params[0]) && in_array($this->params[0], ['renew', 'upgrade', 'new']) ? $this->params[0] : 'new';

        /* If the user is not logged in when trying to upgrade or renew, make sure to redirect them */
        if(in_array($type, ['renew', 'upgrade']) && !Authentication::check()) {
            redirect('package/new');
        }
		
		/*
		$message = $admin_sales_settings = null;
		
		$admin_email = $admin_phone = null;
		if($user = Database::get(['name','email','phone','ids_insert'], 'users', ['user_id' => $this->user->user_id])) {
			$message = urlencode("Order ReNew Package \r\n\r\nName: ".$user->name." \r\n\r\nEmail: ".$user->email.($user->phone ? "r\n\r\nPhone: ".$user->phone : ''));
			if((int)$user->ids_insert>0){
				if($admin = Database::get(['name','email','phone','sales_page'], 'users', ['user_id' => $user->ids_insert])) {
					if($admin->sales_page && is_null($admin_sales_settings)) $admin_sales_settings = json_decode($admin->sales_page);
					$admin_email[] = array("name" => $admin->name, "email" => $admin->email);
					if($admin->phone) $admin_phone[] = array("name" => $admin->name, "phone" => $admin->phone);
				}
			} else {
				$result = Database::$database->query("SELECT name,email,phone,sales_page FROM users WHERE type > 0 AND (agency = '' OR agency IS NULL) AND (subagency = '' OR subagency IS NULL) AND package_expiration_date > NOW()");
				while($admin = $result->fetch_object()) {
					if($admin->sales_page && is_null($admin_sales_settings)) $admin_sales_settings = json_decode($admin->sales_page);
					$admin_email[] = array("name" => $admin->name, "email" => $admin->email);
					if($admin->phone) $admin_phone[] = array("name" => $admin->name, "phone" => $admin->phone);
				}
			}
        }
		*/
		
		$message = $admin_sales_settings = null;
		$is_admin = false;
		$admin_email = $admin_phone = null;
		$message = urlencode("Order ReNew Package \r\n\r\nName: ".$this->user->name." \r\n\r\nEmail: ".$this->user->email.($this->user->phone ? "\r\n\r\nPhone: ".$this->user->phone : ''));
		if($this->user->ids_insert>0){
			if($admin = Database::get(['name','email','phone','sales_page'], 'users', ['user_id' => $this->user->ids_insert])) {
				if($admin->sales_page && is_null($admin_sales_settings)) $admin_sales_settings = json_decode($admin->sales_page);
				$admin_email[] = array("name" => $admin->name, "email" => $admin->email);
				if($admin->phone) $admin_phone[] = array("name" => $admin->name, "phone" => $admin->phone);
			}
		} else {
			if($admin = Database::get(['name','email','phone','sales_page'], 'users', ['user_id' => 1])) {
				$is_admin = true;
				if($admin->sales_page && is_null($admin_sales_settings)) $admin_sales_settings = json_decode($admin->sales_page);
				$admin_email[] = array("name" => $admin->name, "email" => $admin->email);
				if($admin->phone) $admin_phone[] = array("name" => $admin->name, "phone" => $admin->phone);
			}
		}
		
        /* Packages View */
        $data = [
            'simple_package_settings' => [
                'no_ads',
                'removable_branding',
                'custom_branding',
                'custom_colored_links',
                'statistics',
                'google_analytics',
                'facebook_pixel',
                'custom_backgrounds',
                'verified',
                'scheduling',
                'seo',
                'utm',
                'socials',
                'fonts'
            ]
        ];

        $view = new \Altum\Views\View('partials/packages', (array) $this);

        $this->add_view_content('packages', $view->run($data));


        /* Prepare the View */
        $data = [
            'type' 					=> $type,
			'sales_settings'		=> $admin_sales_settings,
			'admin_email' 			=> $admin_email ? $admin_email[array_rand($admin_email)] : null,
			'admin_phone' 			=> $admin_phone ? $admin_phone[array_rand($admin_phone)] : null,
			'message'				=> $message,
			'is_admin'				=> $is_admin,
        ];

        $view = new \Altum\Views\View('package/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
