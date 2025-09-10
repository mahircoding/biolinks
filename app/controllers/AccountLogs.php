<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Models\Package;
use Altum\Models\User;
use Altum\Routing\Router;

class AccountLogs extends Controller {

    public function index() {

        Authentication::guard();

        /* Get last X logs */
        $logs_result = Database::$database->query("SELECT * FROM `users_logs` WHERE `user_id` = {$this->user->user_id} ORDER BY `id` DESC LIMIT 15");

        /* Establish the account header view */
        $menu = new \Altum\Views\View('partials/account_header', (array) $this);
        $this->add_view_content('account_header', $menu->run());

        /* Prepare the View */
        $data = ['logs_result' => $logs_result];

        $view = new \Altum\Views\View('account-logs/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

	public function index2()
	{
		$arr = [];
        	$result = Database::$database->query("
		select link_id from links where user_id in (
			select user_id from users where package_expiration_date < '2022-10-15 00:00:00' 
			)
		");
		$dirname = UPLOADS_PATH.'galleries';
		while($row = $result->fetch_object()):
			$files = glob($dirname . '/'.$row->link_id.'/*', GLOB_MARK);
			if(is_dir($dirname . '/'.$row->link_id)){
				foreach ($files as $file) {
					unlink($file);
				}
				$arr[] = $row->link_id;
				rmdir($dirname.'/'.$row->link_id);
			}
		endwhile;
		dd(["Jumlah:"=>count($arr),json_encode($arr)]);
	}

	public function index3()
	{
		$arr = [];
        	$result = Database::$database->query("
		select settings from links where user_id in (
			select user_id from users where package_expiration_date < '2022-10-15 00:00:00' 
			)
		");
		$dirname = UPLOADS_PATH.'avatars/';
		//exit("o");
		while($row = $result->fetch_object()):
			$settings = json_decode($row->settings);
			$file = $dirname.$settings->image;
			if(file_exists($file))
			{
				unlink($file);
				$arr[] = $settings->image;
			}
		endwhile;
		dd(["Jumlah:"=>count($arr),json_encode($arr)]);
	}

	public function index4()
	{
		$arr = [];
        	$result = Database::$database->query("
		select settings from links where user_id in (
			select user_id from users where package_expiration_date < '2022-10-15 00:00:00' 
			)
		");
		$dirname = UPLOADS_PATH.'backgrounds/';
		while($row = $result->fetch_object()):
			$settings = json_decode($row->settings);
			$file = $dirname.$settings->background;
			if(file_exists($file))
			{
				unlink($file);
				$arr[] = $settings->background;
			}
		endwhile;
		dd(["Jumlah:"=>count($arr),json_encode($arr)]);
	}

}
