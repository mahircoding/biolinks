<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Logger;
use Altum\Middlewares\Csrf;
use Altum\Middlewares\Authentication;

class AdminDomainCreate extends Controller {

    public function index() {

        Authentication::guard('admin');

        /* Default variables */
        $values = [
            'scheme' => '',
            'host' => '',
        ];
		
		$users = Database::$database->query("SELECT user_id,name,email FROM users WHERE (agency IS NULL OR agency = '') AND (subagency IS NULL OR subagency = '') ORDER BY user_id DESC LIMIT 10");
		
        if(!empty($_POST)) {

			require_once APP_PATH . 'includes/DomainParser.php';
			$manageSLD = new \ManageSLD();
			$rules = $manageSLD->parseFile();
			list( $sld, $label, $rest, $registerable, $pattern, $flags ) = $manageSLD->applyRules( trim($_POST['host']), $rules );
			
            /* Clean some posted variables */
            $_POST['type_domain'] = (int)$_POST['type_domain'];
			$_POST['type_dns'] = (int)$_POST['type_dns'];
			$_POST['scheme'] = isset($_POST['scheme']) && in_array($_POST['scheme'], ['http://', 'https://']) ? Database::clean_string($_POST['scheme']) : 'https://';
		//$_POST_host = $_POST["host"]; //editon 07022023
            $_POST['host'] = $registerable;
			$_POST['user_id'] = (int)$_POST['user_id'];
			$_POST['is_active'] = (int)$_POST['is_active'];
			
			if($_POST['type_domain']<0) $_POST['type_domain']=0;
			if($_POST['type_domain']>1) $_POST['type_domain']=1;
			
			if($_POST['type_dns']<0) $_POST['type_dns']=0;
			if($_POST['type_dns']>1) $_POST['type_dns']=1;
//dd($rules);
//dd($registerable);
            /* Default variables */
            $values['scheme'] = $_POST['scheme'];
            $values['host'] = $_POST['host'];
			if($_POST['type_domain']==0)
				$values['user_id'] = $_POST['user_id'];
			else
				$values['user_id'] = '';
				
            /* Must have fields */
			if($_POST['type_domain']==0)
				$fields = ['scheme', 'host', 'email'];
			else
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
			
			if($doms = Database::get(['host'], 'domains', ['host' => $_POST['host']])) {
			//if($doms = Database::get(['host'], 'domains', ['host' => $_POST_host])) { //editon 07022023
				$_SESSION['error'][] = "Domain name already exists";
			}

            /* If there are no errors continue the registering process */
            if(empty($_SESSION['error'])) {
                /* Define some needed variables */
                $type = $_POST['type_domain']==0 ? 0 : 1;
				$is_admin = 1;
				$processed = 0;
				$index_url = '';
				$user_id = $_POST['type_domain']==0 ? $_POST['user_id'] : $this->user->user_id;
				
				/* Add the row to the database */
                $stmt = Database::$database->prepare("INSERT INTO `domains` (`user_id`, `scheme`, `host`, `index_url`, `type`, `is_admin`, `is_active`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('ssssssss', $user_id, $_POST['scheme'], $_POST['host'], $index_url, $type, $is_admin, $_POST['is_active'], \Altum\Date::$date);
                //$stmt->bind_param('ssssssss', $user_id, $_POST['scheme'], $_POST_host, $index_url, $type, $is_admin, $_POST['is_active'], \Altum\Date::$date); //editon 07012023
                $stmt->execute();
				$domain_id = $stmt->insert_id;
                $stmt->close();
				
				$res=shell_exec("sudo sh /root/custom-domain.sh ".$_POST['host']." 2>&1");
				// $res=shell_exec("sudo sh /var/www/scripts/vhost.sh ".$_POST_host." 2>&1"); //editon 07012023
				usleep(500000);
				$get_zone_cloudflare = cloudflare_get_zone($_POST['host']);
				//$get_zone_cloudflare = cloudflare_get_zone($_POST_host); //editon 07012023
				if($get_zone_cloudflare->success&&$get_zone_cloudflare->result_info->count==0) {
					$domain_name = $_POST['host'];
					//$domain_name = $_POST_host; //editon 07012023
					$zone = cloudflare_create_zone($domain_name);
					if($zone->success) {
						$stmt = Database::$database->prepare("UPDATE `domains` SET `name_servers` = ? WHERE `domain_id` = ?");
						$stmt->bind_param('ss', json_encode($zone->result->name_servers), $domain_id);
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
                redirect('admin/domain-update/' . $domain_id);
            }

        }

        /* Main View */
        $data = ['values' => $values, 'users' => $users];

        $view = new \Altum\Views\View('admin/domain-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
