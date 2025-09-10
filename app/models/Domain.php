<?php

namespace Altum\Models;

use Altum\Database\Database;

class Domain extends Model {

    public function get_domains($user_id = false) {

        //$where = $user_id ? "user_id = {$user_id} OR `type` = 1 AND `is_admin` = 1" : "`type` = 1 AND `is_admin` = 1";
        $where = "user_id = {$user_id}";

        $result = Database::$database->query("SELECT * FROM `domains` WHERE {$where}");
        $data = [];

        while($row = $result->fetch_object()) {

            /* Build the url */
            $row->url = $row->scheme . $row->host . '/';

            $data[] = $row;
        }

        return $data;
    }
	
	public function get_biolink_domains($user_id = false) {

        $where = "user_id = {$user_id}";

        $result = Database::$database->query("SELECT * FROM `domains` WHERE ({$where} AND `is_active` = 1) OR (`type` = 1 AND `is_admin` = 1 AND `is_active` = 1)");
        $data = [];

        while($row = $result->fetch_object()) {

            /* Build the url */
            $row->url = $row->scheme . $row->host . '/';

            $data[] = $row;
        }

        return $data;
    }

    public function get_domain($domain_id) {

        $domain_id = (int) Database::clean_string($domain_id);

        $result = Database::$database->query("SELECT * FROM `domains` WHERE `domain_id` = {$domain_id} AND `is_active` = 1");

        if(!$result->num_rows) return false;

        $row = $result->fetch_object();

        /* Build the url */
        $row->url = $row->scheme . $row->host . '/';

        return $row;
    }
	
	public function get_biolink_domain($domain_id) {

        $domain_id = (int) Database::clean_string($domain_id);

        $result = Database::$database->query("SELECT * FROM `domains` WHERE (`domain_id` = {$domain_id} AND `is_active` = 1) OR (`type` = 1 AND `is_admin` = 1 AND `is_active` = 1)");

        if(!$result->num_rows) return false;

        $row = $result->fetch_object();

        /* Build the url */
        $row->url = $row->scheme . $row->host . '/';

        return $row;
    }

    public function get_domains_custom($user_id = false) {

        $where = "user_id = {$user_id}";

        $result = Database::$database->query("SELECT * FROM `domains` WHERE {$where}");
        $data = [];

        while($row = $result->fetch_object()) {

            /* Build the url */
            $row->url = $row->scheme . $row->host . '/';

            $data[] = $row;
        }

        return $data;
    }
	
	public function get_biolink_domains_custom($user_id = false) {

        $where = "user_id = {$user_id}";

        $result = Database::$database->query("SELECT * FROM `domains` WHERE ({$where} AND `is_active` = 1) OR (`type` = 1 AND `is_admin` = 1 AND `is_active` = 1)");
        $data = [];

        while($row = $result->fetch_object()) {

            /* Build the url */
            $row->url = $row->scheme . $row->host . '/';

            $data[] = $row;
        }

        return $data;
    }
	
	public function get_biolink_domains_paid_user($user) {
		
		if($user->esc_package) {
		
			$where = "user_id = {$user->user_id}";

			$result = Database::$database->query("SELECT * FROM `domains` WHERE ({$where} AND `is_active` = 1) OR (`type` = 1 AND `is_admin` = 1 AND `is_active` = 1)");
			$data = [];

			while($row = $result->fetch_object()) {

				/* Build the url */
				$row->url = $row->scheme . $row->host . '/';

				$data[] = $row;
			}
		
		}

        return $data;
    }

}
