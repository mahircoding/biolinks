<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Models\Package;
use Altum\Middlewares\Authentication;

class AdminDomainsWLUpdate extends Controller {

    public function index() {

        Authentication::guard('admin');

        $id = (isset($this->params[0])) ? $this->params[0] : false;

        /* Check if user exists */
        if(!$domain = Database::get('*', 'whitelabel', ['id' => $id])) {
            redirect('admin/domains-whitelabel');
        }
		
		$users = Database::$database->query("SELECT user_id,name,email FROM users WHERE user_id = " . $domain->user_id);

        if(!empty($_POST)) {
            /* Clean some posted variables */
            $_POST['url'] = $_POST['url'];

            /* Must have fields */
            $fields = ['url'];

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

                /* Update the row of the database */
                $stmt = Database::$database->prepare("UPDATE `whitelabel` SET `url` = ? WHERE `id` = ?");
                $stmt->bind_param('ss', $_POST['url'], $domain->id);
                $stmt->execute();
                $stmt->close();
				
				$res=shell_exec("sudo sh /root/custom-domain.sh ".$_POST['url']." 2>&1");
				usleep(500000);
				$get_zone_cloudflare = cloudflare_get_zone($_POST['url'],true);
				if($get_zone_cloudflare->success&&$get_zone_cloudflare->result_info->count==0) {
					$domain_name = $_POST['url'];
					$zone = cloudflare_create_zone($domain_name,true);
					if($zone->success) {
						$stmt = Database::$database->prepare("UPDATE `whitelabel` SET `name_servers` = ? WHERE `id` = ?");
						$stmt->bind_param('ss', json_encode($zone->result->name_servers), $domain->id);
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

                $_SESSION['success'][] = $this->language->global->success_message->basic;

                redirect('admin/domains-whitelabel-u/' . $domain->id);
            }

        }

        /* Main View */
		$domain->name_servers = $domain->name_servers ? json_decode($domain->name_servers) : null;
        $data = ['domain' => $domain, 'users' => $users];

        $view = new \Altum\Views\View('admin/domains-whitelabel-u/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
