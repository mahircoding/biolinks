<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Models\Package;
use Altum\Middlewares\Authentication;

class AdminDomainUpdate extends Controller {

    public function index() {

        Authentication::guard('admin');

        $domain_id = (isset($this->params[0])) ? $this->params[0] : false;

        /* Check if user exists */
        //if(!$domain = Database::get('*', 'domains', ['domain_id' => $domain_id])) {
        //    redirect('admin/domains');
        //}
		$domain = Database::$database->query("SELECT domains.*, users.name, users.email FROM domains INNER JOIN users ON users.user_id = domains.user_id WHERE domain_id = ".$domain_id);
		$domain = $domain->num_rows ? $domain->fetch_object() : null;
		if(!$domain) {
			redirect('admin/domains');
		}
		$users = Database::$database->query("SELECT user_id,name,email FROM users WHERE (agency IS NULL OR agency = '') AND (subagency IS NULL OR subagency = '') ORDER BY user_id DESC LIMIT 10");
        if(!empty($_POST)) {
            /* Clean some posted variables */
            $_POST['scheme'] = isset($_POST['scheme']) && in_array($_POST['scheme'], ['http://', 'https://']) ? Database::clean_string($_POST['scheme']) : 'https://';
            $_POST['host'] = Database::clean_string($_POST['host']);

            /* Must have fields */
            $fields = ['scheme', 'host'];

            /* Check for any errors */
            foreach($_POST as $key=>$value) {
                if(empty($value) && in_array($key, $fields) == true) {
                    $_SESSION['error'][] = $this->language->global->error_message->empty_fields;
                    break 1;
                }
            }

            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(empty($_SESSION['error'])) {
				$is_active = (int)$_POST['is_active'];

                /* Update the row of the database */
                $stmt = Database::$database->prepare("UPDATE `domains` SET `scheme` = ?, `host` = ?, `user_id` = ?, `type` = ?, `is_admin` = ?, `is_active` = ? WHERE `domain_id` = ?");
                $stmt->bind_param('sssssss', $_POST['scheme'], $_POST['host'], $_POST['user_id'], $_POST['type_domain'], $_POST['is_admin'], $is_active, $domain->domain_id);
                $stmt->execute();
                $stmt->close();
				
				if($is_active) {
					$res=shell_exec("sudo sh /root/custom-domain.sh ".$_POST['host']." 2>&1");
					usleep(500000);
					$get_zone_cloudflare = cloudflare_get_zone($_POST['host']);
					if($get_zone_cloudflare->success&&$get_zone_cloudflare->result_info->count==0) {
						$domain_name = $_POST['host'];
						$zone = cloudflare_create_zone($domain_name);
						if($zone->success) {
							$stmt = Database::$database->prepare("UPDATE `whitelabel` SET `name_servers` = ? WHERE `id` = ?");
							$stmt->bind_param('ss', json_encode($zone->result->name_servers), $whitelabel_id);
							$stmt->execute();
							$stmt->close();
							$zone_id = $zone->result->id;
							cloudflare_add_dns($zone_id,'CNAME','www',$domain_name);
							cloudflare_add_dns($zone_id,'A',$domain_name,BASE_IP);
							cloudflare_change_ssl($zone_id);
							cloudflare_certificate_pack($zone_id);
							cloudflare_edit_zone($zone_id);
							
							$_SESSION['success'][] = "Domain " . $domain_name . " has been added to cloudflare";
						} else {
							 $_SESSION['success'][] = "Failed add Domain " . $domain_name . " to cloudflare!.";
						}
					}
				} else {
					$res=shell_exec("sudo sh /root/domain-delete ".$_POST['host']." 2>&1");
				}

                $_SESSION['success'][] = $this->language->global->success_message->basic;

                redirect('admin/domain-update/' . $domain->domain_id);
            }

        }

        /* Main View */
        $data = ['domain' => $domain, 'users' => $users];

        $view = new \Altum\Views\View('admin/domain-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
