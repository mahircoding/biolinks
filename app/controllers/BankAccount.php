<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Models\User;
use Altum\Response;

class BankAccount extends Controller {

    public function index() {

        Authentication::guard();
		
		$user_id = $this->user->user_id;
		$bank_account = null;
		
        if(!empty($_POST)) {
			$b_acc = array();
            /* Clean some posted variables */
			for($i=0;$i<count($_POST['bank_name']);$i++) {
				$_POST['account_number'][$i]	= filter_var($_POST['account_number'][$i], FILTER_SANITIZE_STRING);
				$_POST['account_name'][$i]		= filter_var($_POST['account_name'][$i], FILTER_SANITIZE_STRING);
				$_POST['bank_name'][$i]			= filter_var($_POST['bank_name'][$i], FILTER_SANITIZE_STRING);
				$_POST['swift_code'][$i]		= filter_var($_POST['swift_code'][$i], FILTER_SANITIZE_STRING);
				
				$b_acc[] = array('account_number' 	=> $_POST['account_number'][$i],
								 'account_name' 	=> $_POST['account_name'][$i],
								 'bank_name' 		=> $_POST['bank_name'][$i],
								 'swift_code' 		=> $_POST['swift_code'][$i]);
			}
			$b_acc = $b_acc ? json_encode($b_acc) : $b_acc;
			/* Prepare the statement and execute query */
			$stmt = Database::$database->prepare("UPDATE `users` SET `bank_account` = ? WHERE `user_id` = ?");
			$stmt->bind_param('ss', $b_acc, $user_id);
			$stmt->execute();
			$stmt->close();
			
        }
		
		if($ba_configs = Database::get(['bank_account'], 'users', ['user_id' => $this->user->user_id])) {
			$bank_account = $ba_configs->bank_account ? json_decode($ba_configs->bank_account) : null;
		}
		
        /* Establish the account header view */
        $menu = new \Altum\Views\View('partials/account_header', (array) $this);
        $this->add_view_content('account_header', $menu->run());
		
        /* Prepare the View */
        $data = [
            'bank_account' => $bank_account
        ];

        $view = new \Altum\Views\View('bank-account/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }
	
}
