<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Logger;
use Altum\Middlewares\Csrf;
use Altum\Middlewares\Authentication;

class AdminDomainsWLCreate extends Controller {

    public function index() {

        Authentication::guard('admin');

        /* Default variables */
        $values = [
            'url' => ''
        ];
		
		$users = Database::$database->query("SELECT user_id,name,email FROM users LIMIT 10");
		
        if(!empty($_POST)) {
						
			require_once APP_PATH . 'includes/DomainParser.php';
            /* Clean some posted variables */
            $_POST['url'] = trim($_POST['url']);
			$_POST['user_id'] = (int)$_POST['user_id'];
			
            /* Default variables */
            $values['url'] = $_POST['url'];
            

            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }
			
			if($doms = Database::get(['url'], 'whitelabel', ['url' => $_POST['url']])) {
				$_SESSION['error'][] = "Domain name already exists";
			}

            /* If there are no errors continue the registering process */
            if(empty($_SESSION['error'])) {
                /* Define some needed variables */
				$user_id = $_POST['user_id']==0 ? $_POST['user_id'] : $this->user->user_id;
				$index_url = 'https://'.$_POST['url'].'/login';
                $term_priv_url = 'https://'.$_POST['url'];
				/* Add the row to the database */
                $stmt = Database::$database->prepare("INSERT INTO `whitelabel` (`user_id`, `url`, `index_url`, `terms_url`, `privacy_url`, `title`, `logo`, `favicon`) VALUES (?, ?, ?, ?, ?, 'Default Page', 'default.png', 'default.png')");
                $stmt->bind_param('sssss', $_POST['user_id'], $_POST['url'], $index_url, $term_priv_url, $term_priv_url);
                $stmt->execute();
				$whitelabel_id = $stmt->insert_id;
                $stmt->close();
				
				$wl_on = 'Y';
				$stmt = Database::$database->prepare("UPDATE `users` SET `whitelabel_id` = ?, whitelabel = ? WHERE `user_id` = ?");
                $stmt->bind_param('sss', $whitelabel_id, $wl_on, $_POST['user_id']);
                $stmt->execute();
                $stmt->close();
				
				//file_put_contents('domain_create.dat',1);
				$siteName = BASE_DOMAIN;
				$res=shell_exec("sudo sh /root/custom-domain.sh ".$_POST['url']." 2>&1");
				usleep(500000);
				$get_zone_cloudflare = cloudflare_get_zone($_POST['url'],true);
				if($get_zone_cloudflare->success&&$get_zone_cloudflare->result_info->count==0) {
					$domain_name = $_POST['url'];
					$zone = cloudflare_create_zone($domain_name,true);
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
				
                /* Success message */
                $_SESSION['success'][] = $this->language->admin_domain_create->success_message->created;

                /* Redirect */
                redirect('admin/domains-whitelabel-u/' . $whitelabel_id);
            }

        }

        /* Main View */
        $data = ['values' => $values, 'users' => $users];

        $view = new \Altum\Views\View('admin/domains-whitelabel-c/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
