<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Middlewares\Authentication;
use Altum\Response;

class AdminDomains extends Controller {

    public function index() {

        Authentication::guard('admin');

        /* Main View */
        $view = new \Altum\Views\View('admin/domains/index', (array) $this);
		
        $this->add_view_content('content', $view->run());

    }


    public function read() {

        Authentication::guard('admin');

        $datatable = new \Altum\DataTable();
        $datatable->set_accepted_columns(['domain_id', 'type', 'host', 'is_active', 'date']);
        $datatable->process($_POST);

        $limit = "";
        if($datatable->get_length()>0) $limit = 'LIMIT '.$datatable->get_start().', '.$datatable->get_length();

	//exit($datatable->get_order());
	$order = "ORDER BY domain_id DESC";
	if($datatable->get_order()!="") $order = "ORDER BY ".$datatable->get_order();

	$q = "SELECT
                `domains`.*, `users`.`name`, `users`.`phone`,
                (SELECT COUNT(*) FROM `domains`) AS `total_before_filter`,
                (SELECT COUNT(*) FROM `domains` WHERE `domains`.`host` LIKE '%{$datatable->get_search()}%') AS `total_after_filter`
            FROM
                `domains`
	    RIGHT JOIN 
        	`users` ON `domains`.`user_id` = `users`.`user_id` 
            WHERE 
                `domains`.`host` LIKE '%{$datatable->get_search()}%'
            GROUP BY
                `domain_id`
	    " . $order . " " .$limit. " ";

        $result = Database::$database->query($q);

        $total_before_filter = 0;
        $total_after_filter = 0;

        $data = [];

        while($row = $result->fetch_object()):

            /* Type */
            //$row->type =
            //    $row->type == 1 ?
            //        '<span class="badge badge-pill badge-'.($row->is_active ? 'success' : 'secondary').'"><i class="fa fa-fw fa-globe mr-1"></i> ' . $this->language->admin_domains->display->type_global . '</span>' :
            //        '<span class="badge badge-pill badge-'.($row->is_active ? 'success' : 'secondary').'"><i class="fa fa-fw fa-user mr-1"></i> ' . $this->language->admin_domains->display->type_user . '</span>';

            $row->type =
                $row->type == 1 ?
                    '<span class="badge badge-pill badge-'.($row->is_active ? 'success' : 'secondary').'"><i class="fa fa-fw fa-globe mr-1"></i> ' . $this->language->admin_domains->display->type_global . '</span>' :
                    '<span class="badge badge-pill badge-'.($row->is_active ? 'success' : 'secondary').'"><i class="fa fa-fw fa-user mr-1"></i> ' . ($row->is_active ? 'Domain Aktif' : 'Domain NonAktif') . '</span>';

	    //name
	    $row->name = '<span>'. $row->name . '</span>';

	    //phone
	    $row->phone = '<span>'. $row->phone . '</span>';

            /* host */
            $host_prepend = '<img src="https://www.google.com/s2/favicons?domain=' . $row->host . '" class="img-fluid mr-1" />';
            $row->host = $host_prepend . '<span class="align-middle">' . $row->scheme . $row->host . '</span>';

            $row->date = '<span data-toggle="tooltip" title="' . \Altum\Date::get($row->date, 1) . '">' . \Altum\Date::get($row->date, 2) . '</span>';
            //$row->actions = get_admin_options_button('domain', $row->domain_id);
			$edit = '<a class="dropdown-item" href="admin/domain-update/' . $row->domain_id . '"><i class="fa fa-fw fa-pencil-alt"></i> ' . $this->language->global->edit . '</a>';
			//if($row->is_admin==1)
			//	$edit = '';
			
			$row->actions = '<div class="dropdown">
								<a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
									<i class="fa fa-fw fa-ellipsis-v"></i>
									
									<div class="dropdown-menu dropdown-menu-right">
										'.$edit.'
										<a class="dropdown-item" data-confirm="' . $this->language->global->info_message->confirm_delete . '" href="admin/domains/delete/' . $row->domain_id . \Altum\Middlewares\Csrf::get_url_query() . '"><i class="fa fa-fw fa-times"></i> ' . $this->language->global->delete . '</a>
									</div>
								</a>
							</div>';
			
            $data[] = $row;
            $total_before_filter = $row->total_before_filter;
            $total_after_filter = $row->total_after_filter;

        endwhile;

        Response::simple_json([
            'data' => $data,
            'draw' => $datatable->get_draw(),
            'recordsTotal' => $total_before_filter,
            'recordsFiltered' =>  $total_after_filter
        ]);

    }

    public function read_wl() {

        Authentication::guard('admin');

        $datatable = new \Altum\DataTable();
        $datatable->set_accepted_columns(['id', 'url', 'name', 'title']);
        $datatable->process($_POST);

        $result = Database::$database->query("
        SELECT 
            `whitelabel`.`url`, `whitelabel`.`id`, `users`.`name`, `users`.`email`, `whitelabel`.`title`,
            (SELECT COUNT(*) FROM `whitelabel`) AS `total_before_filter`,
            (SELECT COUNT(*) FROM `whitelabel` WHERE `whitelabel`.`url` LIKE '%{$datatable->get_search()}%') AS `total_after_filter` 
        FROM 
            `whitelabel` 
        RIGHT JOIN 
            `users` ON `whitelabel`.`user_id` = `users`.`user_id` 
        WHERE 
            `whitelabel`.`url` !='' AND `whitelabel`.`url` LIKE '%{$datatable->get_search()}%'
        ORDER BY
            `whitelabel`.`id` DESC
        LIMIT
            {$datatable->get_start()}, {$datatable->get_length()}
        ");

        $total_before_filter = 0;
        $total_after_filter = 0;

        $data = [];

        while($row = $result->fetch_object()):

            
            $host_prepend = '<img src="https://www.google.com/s2/favicons?domain=' . $row->url . '" class="img-fluid mr-1" />';
            $row->url = $host_prepend . '<span class="align-middle">https://'. $row->url . '/</span>';
  
            // Links 
            $row->title = '<span>'. $row->title . '</span>';

            $edit = '<a class="dropdown-item" href="admin/domains-whitelabel-u/' . $row->id . '"><i class="fa fa-fw fa-pencil-alt"></i> ' . $this->language->global->edit . '</a>';
            $row->actions = '<div class="dropdown">
                <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                    <i class="fa fa-fw fa-ellipsis-v"></i>
                        <div class="dropdown-menu dropdown-menu-right">
                            '.$edit.'
                            <!-- <a class="dropdown-item" data-confirm="' . $this->language->global->info_message->confirm_delete . '" href="admin/domains-whitelabel/delete/' . $row->id . \Altum\Middlewares\Csrf::get_url_query() . '"><i class="fa fa-fw fa-times"></i> ' . $this->language->global->delete . '</a>-->
                        </div>
                    </a>
                </div>';
            $data[] = $row;
            $total_before_filter = $row->total_before_filter;
            $total_after_filter = $row->total_after_filter;
        endwhile;

        Response::simple_json([
            'data' => $data,
            'draw' => $datatable->get_draw(),
            'recordsTotal' => $total_before_filter,
            'recordsFiltered' =>  $total_after_filter
        ]);

    }

    public function delete() {

        Authentication::guard();

        $domain_id = (isset($this->params[0])) ? (int) $this->params[0] : false;
		$type = null;
		if($doms = Database::get('*', 'domains', ['domain_id' => $domain_id])) {
			$domain_id = $doms->domain_id;
		} else
			$_SESSION['error'][] = "Invalid domain id";
		
        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
        }

        if(empty($_SESSION['error'])) {
			
			$zone_id_domain = cloudflare_get_zone($doms->host);
			if($zone_id_domain&&$zone_id_domain->success) {
				$zone_id_domain = $zone_id_domain->result[0]->id;
				$del_status = cloudflare_delete_zone($zone_id_domain);
				$this->database->query("DELETE FROM `domains` WHERE `domain_id` = {$domain_id}");
			
				$res=shell_exec("sudo sh /root/domain-delete.sh ".$doms->host." 2>&1");
				
				/* Update all the links using that domain to default*/
				$this->database->query("UPDATE `links` set domain_id = 0 WHERE `domain_id` = {$domain_id}");
			} else {
				$this->database->query("DELETE FROM `domains` WHERE `domain_id` = {$domain_id}");
				/* Update all the links using that domain to default*/
				$this->database->query("UPDATE `links` set domain_id = 0 WHERE `domain_id` = {$domain_id}");
			}

			redirect('admin/domains');

        }

        die();
    }
	
	public function ajaxsearch() {
		$q = trim($_POST['q']);
		if($q) {
			$arr_users = null;
			$users = Database::$database->query("SELECT user_id,name,email FROM users WHERE (name LIKE '%".$q."%' OR email LIKE '%".$q."%') AND user_id <> ".$this->user->user_id." ORDER BY user_id DESC LIMIT 10");
			while ($rows = $users->fetch_object()) {
				$arr_users[] = array("id" => $rows->user_id, "name" => $rows->name, "email" => $rows->email);
			}
			
			Response::simple_json($arr_users);
		}
	}
}
