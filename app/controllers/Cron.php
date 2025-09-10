<?php

namespace Altum\Controllers;

use Altum\Database\Database;

class Cron extends Controller {

    public function index() {

        /* Initiation */
        set_time_limit(0);

        /* Make sure the key is correct */
        if(!isset($_GET['key']) || (isset($_GET['key']) && $_GET['key'] != $this->settings->cron->key)) {
            die();
        }

        die();
    }
	
	public function set_inactive_domains() {
		set_time_limit(0);
		
		$valid_nameservers = [
								'arnold.ns.cloudflare.com',
								'elma.ns.cloudflare.com',
								'sara.ns.cloudflare.com',
								'cleo.ns.cloudflare.com',
								'pete.ns.cloudflare.com',
								'leah.ns.cloudflare.com',
								'gigi.ns.cloudflare.com',
								'jerome.ns.cloudflare.com'
							  ];
		$domains_id = [];
		
		
		$result = Database::$database->query("SELECT * FROM `domains` WHERE `is_active` = 1");
		while($row = $result->fetch_object()) {
			$ip = trim(shell_exec('dig +short ns ' . $row->host));
			if($ip) {
				$ns = explode("\n",trim($ip));
				$ns = array_map(function($item){
					return trim(rtrim($item,'.'));
				},$ns);
				//echo '--- (' . $row->domain_id . ') ' . $row->host . ' ---' . "\n";
				//print_r($ns);
				//echo '--------------------------' . "\n";
				
				$diff = array_diff($ns,$valid_nameservers);
				
				if(count($diff)>0) {
					//print_r(implode(', ',$diff) . "\n\n");
					$domains_id[] = $row->domain_id;
					Database::$database->query("UPDATE `domains` SET `is_active` = 0 WHERE `domain_id` = {$row->domain_id}");
				}
			} else {
				//echo "--- (" . $row->domain_id . ') ' . $row->host . " ---\n Kosong \n ----------------------\n";
				$domains_id[] = $row->domain_id;
				Database::$database->query("UPDATE `domains` SET `is_active` = 0 WHERE `domain_id` = {$row->domain_id}");
			}
		}
		
		if(count($domains_id)>0) {
			/* Update the row of the database */
			$ids = '(' . implode(',',$domains_id) . ')';
		} else {
			echo 'No Action';
		}
		
		die();
	}

}
