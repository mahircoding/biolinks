<?php

namespace Altum\Models;

use Altum\Database\Database;

class Whitelabel extends Model {

    public function get($domain_name) {
		
        $result = Database::$database->query("SELECT a.`user_id`, a.`type`, a.`whitelabel_id`, a.`whitelabel`, a.`agency`, a.`subagency`, b.`index_url` FROM `users` a JOIN `whitelabel` b ON b.`id` = a.`whitelabel_id` WHERE a.`whitelabel` = 'y' AND b.`url` = '{$domain_name}'");
        $data = $result&&$result->num_rows ? $result->fetch_object() : null;

        return $data;
    }
}
