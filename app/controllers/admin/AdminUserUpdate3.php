<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Models\Package;
use Altum\Middlewares\Authentication;
use Altum\Response;

class AdminUserUpdate extends Controller {

    public function index() {

        Authentication::guard('admin');

        $user_id = (isset($this->params[0])) ? $this->params[0] : false;
		$whitelabel_id = 0;

        /* Check if user exists */
        if(!$user = Database::get('*', 'users', ['user_id' => $user_id])) {
            $_SESSION['error'][] = $this->language->admin_user_update->error_message->invalid_account;
            redirect('admin/users');
        }

        /* Get current package proper details */
        $user->package = (new Package(['settings' => $this->settings]))->get_package_by_id($user->package_id);
		
        /* Check if its a custom package */
        if($user->package->package_id == 'custom') {
            $user->package->settings = json_decode($user->package_settings);
        }
		
		$biolinks = Database::$database->query("SELECT link_id,url FROM links WHERE user_id = '".$user_id."' AND type = 'biolink' AND subtype = 'base' ORDER BY link_id DESC LIMIT 10");
		
        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name']		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $_POST['status']	= (int) $_POST['status'];
            $type	    = $_POST['type'];
            if($type == "2" || $type == "3" || $type == "4" || $type == "5"){
				$types = 1;
            }else{
				$types = $type;
            }
            $abc	    = (int) $_POST['ids_insert'];
            $codeagency	    = "Y";
            $nullagency	    = NULL;
            $_POST['package_trial_done'] = (int) $_POST['package_trial_done'];

            switch($_POST['package_id']) {
                case 'free':

                    $package_settings = json_encode($this->settings->package_free->settings);

                    break;

                case 'trial':

                    $package_settings = json_encode($this->settings->package_trial->settings);

                    break;

                case 'custom':

                    $package_settings = json_encode([
                        'no_ads'                => (bool) isset($_POST['no_ads']),
                        'removable_branding'    => (bool) isset($_POST['removable_branding']),
                        'custom_branding'       => (bool) isset($_POST['custom_branding']),
                        'custom_colored_links'  => (bool) isset($_POST['custom_colored_links']),
                        'statistics'            => (bool) isset($_POST['statistics']),
                        'google_analytics'      => (bool) isset($_POST['google_analytics']),
                        'facebook_pixel'        => (bool) isset($_POST['facebook_pixel']),
                        'custom_backgrounds'    => (bool) isset($_POST['custom_backgrounds']),
                        'verified'              => (bool) isset($_POST['verified']),
                        'scheduling'            => (bool) isset($_POST['scheduling']),
                        'seo'                   => (bool) isset($_POST['seo']),
                        'utm'                   => (bool) isset($_POST['utm']),
                        'socials'               => (bool) isset($_POST['socials']),
                        'fonts'                 => (bool) isset($_POST['fonts']),
                        'projects_limit'        => (int) $_POST['projects_limit'],
                        'biolinks_limit'        => (int) $_POST['biolinks_limit'],
                        'links_limit'           => (int) $_POST['links_limit'],
                        'domains_limit'         => (int) $_POST['domains_limit'],
                    ]);

                    break;

                default:

                    $_POST['package_id'] = (int) $_POST['package_id'];

                    /* Make sure this package exists */
                    if(!$package_settings = Database::simple_get('settings', 'packages', ['package_id' => $_POST['package_id']])) {
                        redirect('admin/user-update/' . $user->user_id);
                    }

                    break;
            }

            $_POST['package_expiration_date'] = (new \DateTime($_POST['package_expiration_date']))->format('Y-m-d H:i:s');

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(strlen($_POST['name']) < 3 || strlen($_POST['name']) > 32) {
                $_SESSION['error'][] = $this->language->admin_user_update->error_message->name_length;
            }
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
                $_SESSION['error'][] = $this->language->admin_user_update->error_message->invalid_email;
            }

            if(Database::exists('user_id', 'users', ['email' => $_POST['email']]) && $_POST['email'] !== Database::simple_get('email', 'users', ['user_id' => $user->user_id])) {
                $_SESSION['error'][] = $this->language->admin_user_update->error_message->email_exists;
            }
			
			if($user->whitelabel_id==0)
				$whitelabel_id = $this->user->whitelabel_id;
			
			if($type == "2"){
				if($this->user->type == 1 && ($this->user->whitelabel=='Y' && empty($this->user->superagency) && empty($this->user->agency) && empty($this->user->subagency))) {
					if(!is_null($this->user->ulicense) && (int)$this->user->ulicense > -1) {
						$sub_license_user = !is_null($this->user->ulicense) && $this->user->ulicense > 0 ? (int)$this->user->ulicense : 0;
						$sub_license_user = $sub_license_user - (int)$_POST['transfer_license'];
						if($sub_license_user<=0) {
							$_SESSION['error'][] = 'Licenses has exceeded the limit!.';
						}
					}
				}
			} elseif($type == "3"){
				if($this->user->type == 1 && ($this->user->superagency=='Y' && empty($this->user->whitelabel) && empty($this->user->agency) && empty($this->user->subagency))) {
					if(!is_null($this->user->ulicense) && (int)$this->user->ulicense > -1) {
						$sub_license_user = !is_null($this->user->ulicense) && $this->user->ulicense > 0 ? (int)$this->user->ulicense : 0;
						$sub_license_user = $sub_license_user - (int)$_POST['transfer_license'];
						if($sub_license_user<=0) {
							$_SESSION['error'][] = 'Licenses has exceeded the limit!.';
						}
					}
				}
			} elseif($type == "4"){
				if($this->user->type == 1 && ($this->user->agency=='Y' && empty($this->user->whitelabel) && empty($this->user->superagency) && empty($this->user->subagency))) {
					if(!is_null($this->user->ulicense) && (int)$this->user->ulicense > -1) {
						$sub_license_user = !is_null($this->user->ulicense) && $this->user->ulicense > 0 ? (int)$this->user->ulicense : 0;
						$sub_license_user = $sub_license_user - (int)$_POST['transfer_license'];
						if($sub_license_user<=0) {
							$_SESSION['error'][] = 'Licenses has exceeded the limit!.';
						}
					}
				}
			}

            if(!empty($_POST['new_password']) && !empty($_POST['repeat_password'])) {
                if(strlen(trim($_POST['new_password'])) < 6) {
                    $_SESSION['error'][] = $this->language->admin_user_update->error_message->short_password;
                }
                if($_POST['new_password'] !== $_POST['repeat_password']) {
                    $_SESSION['error'][] = $this->language->admin_user_update->error_message->passwords_not_matching;
                }
            }


            if(empty($_SESSION['error'])) {

                /* Update the basic user settings */
                $stmt = Database::$database->prepare("
                    UPDATE
                        `users`
                    SET
                        `name` = ?,
                        `email` = ?,
                        `active` = ?,
                        `type` = ?,
                        `package_id` = ?,
                        `package_expiration_date` = ?,
                        `package_settings` = ?,
                        `package_trial_done` = ?
                    WHERE
                        `user_id` = ?
                ");
                $stmt->bind_param(
                    'sssssssss',
                    $_POST['name'],
                    $_POST['email'],
                    $_POST['status'],
                    $types,
                    $_POST['package_id'],
                    $_POST['package_expiration_date'],
                    $package_settings,
                    $_POST['package_trial_done'],
                    $user->user_id
                );
                $stmt->execute();
                $stmt->close();
				
				$package_json = json_decode($package_settings);
				
				/* Set Automatic License if Empty for Agency */
				if($this->user->type == 1 && (empty($this->user->whitelabel) && empty($this->user->superagency) && empty($this->user->agency) && empty($this->user->subagency))) {
					if(trim($_POST['license'])=='')
						$_POST['license'] = 0;
					else
						$_POST['license'] = (int)$_POST['license'];
						
					if($_POST['license']<-1) $_POST['license'] = 0;
				} elseif($this->user->type == 1 && (($this->user->whitelabel == 'Y' || $this->user->superagency == 'Y' || $this->user->agency == 'Y') && empty($this->user->subagency))) {
					if(trim($_POST['transfer_license'])==''||intval($_POST['transfer_license'])<0)
						$_POST['transfer_license'] = 0;
					else
						$_POST['transfer_license'] = (int)$_POST['transfer_license'];
					
					if((int)$_POST['transfer_license']<0) $_POST['transfer_license'] = 0;
				} elseif($this->user->type == 1 && ($this->user->subagency == 'Y' && empty($this->user->superagency) && empty($this->user->agency))) {
					if(trim($_POST['transfer_license'])==''||intval($_POST['transfer_license'])<0)
						$_POST['transfer_license'] = 0;
					else
						$_POST['transfer_license'] = (int)$_POST['transfer_license'];
				}
				
				$max_transfer = $this->user->type == 1 && $this->user->agency == 'Y' ? 25 : 300;
				
				if($this->user->type == 1 && $this->user->superagency == 'Y') {
					if($type == "3") {
						$max_transfer = 300;
					} elseif($type == "4") {
						$max_transfer = 25;
					}
				}
				
				if(isset($_POST['transfer_license'])) if((int)$_POST['transfer_license']<0) $_POST['transfer_license'] = 0;
				if(isset($_POST['transfer_license'])) if((int)$_POST['transfer_license']>$max_transfer) $_POST['transfer_license'] = $max_transfer;
				
				//Admin
				if($this->user->type == 1 && (empty($this->user->whitelabel) && empty($this->user->superagency) && empty($this->user->agency) && empty($this->user->subagency))) {
					$whitelabel_id = $user->whitelabel_id;
					if($type == "1"){
						$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `superagency` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
						$stmt->bind_param('sssssss', $types, $_POST['license'], $whitelabel_id, $nullagency, $nullagency, $nullagency, $nullagency);
						$stmt->execute();
						$stmt->close();
					} elseif($type == "2"){
						$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `superagency` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
						$stmt->bind_param('sssssss', $types, $_POST['license'], $whitelabel_id, $codeagency, $nullagency, $nullagency, $nullagency);
						$stmt->execute();
						$stmt->close();
					} elseif($type == "3") {
						$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `superagency` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
						$stmt->bind_param('sssssss', $types, $_POST['license'], $whitelabel_id, $nullagency, $codeagency, $nullagency, $nullagency);
						$stmt->execute();
						$stmt->close();
					} elseif($type == "4") {
						$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `superagency` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
						$stmt->bind_param('sssssss', $types, $_POST['license'], $whitelabel_id, $nullagency, $nullagency, $codeagency, $nullagency);
						$stmt->execute();
						$stmt->close();
					} elseif($type == "5") {
						$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `superagency` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
						$stmt->bind_param('sssssss', $types, $_POST['license'], $whitelabel_id, $nullagency, $nullagency, $nullagency, $codeagency);
						$stmt->execute();
						$stmt->close();
					} else {
						$types = 0;
						$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `superagency` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
						$stmt->bind_param('sssssss', $types, $_POST['license'], $whitelabel_id, $nullagency, $nullagency, $nullagency, $nullagency);
						$stmt->execute();
						$stmt->close();
					}
					
				//Whitelabel
				} elseif($this->user->type == 1 && ($this->user->whitelabel=='Y' && empty($this->user->superagency) && empty($this->user->agency) && empty($this->user->subagency))) {
					if(isset($_POST['transfer_license'])) {
						if(!is_null($this->user->ulicense) && $this->user->ulicense > 0) {
							$stmt = Database::$database->prepare("UPDATE `users` SET `ulicense` = `ulicense` - ? WHERE `user_id` = {$this->user->user_id}");
							$stmt->bind_param('s', $_POST['transfer_license']);
							$stmt->execute();
							$stmt->close();
						}
						
						if($type == "4") {
							$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = CASE WHEN(`ulicense` IS NULL OR `ulicense` = -1) THEN ? ELSE `ulicense` + ? END, `whitelabel_id` = ?, `whitelabel` = ?, `superagency` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
							$stmt->bind_param('ssssssss', $types, $_POST['transfer_license'], $_POST['transfer_license'], $whitelabel_id, $nullagency, $nullagency, $codeagency, $nullagency);
							$stmt->execute();
							$stmt->close();
						} elseif($type == "5") {
							$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = CASE WHEN(`ulicense` IS NULL OR `ulicense` = -1) THEN ? ELSE `ulicense` + ? END, `whitelabel_id` = ?, `whitelabel` = ?, `superagency` = ?, `agency` = ?, `subagency` = ? WHERE `user_id` = {$user->user_id}");
							$stmt->bind_param('ssssssss', $types, $_POST['transfer_license'], $_POST['transfer_license'], $whitelabel_id, $nullagency, $nullagency, $nullagency, $codeagency);
							$stmt->execute();
							$stmt->close();
						} else {
							$types = 0;
							$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `superagency` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
							$stmt->bind_param('ssss', $types, $nullagency, $nullagency, $nullagency);
							$stmt->execute();
							$stmt->close();
						}
					}
					
				//Super Agency
				} elseif($this->user->type == 1 && ($this->user->superagency=='Y' && empty($this->user->whitelabel) && empty($this->user->agency) && empty($this->user->subagency))) {
					if(isset($_POST['transfer_license'])) {
						if(!is_null($this->user->ulicense) && $this->user->ulicense > 0) {
							$stmt = Database::$database->prepare("UPDATE `users` SET `ulicense` = `ulicense` - ? WHERE `user_id` = {$this->user->user_id}");
							$stmt->bind_param('s', $_POST['transfer_license']);
							$stmt->execute();
							$stmt->close();
						}
						
						if($type == "4") {
							$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = CASE WHEN(`ulicense` IS NULL OR `ulicense` = -1) THEN ? ELSE `ulicense` + ? END, `whitelabel_id` = ?, `whitelabel` = ?, `superagency` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
							$stmt->bind_param('ssssssss', $types, $_POST['transfer_license'], $_POST['transfer_license'], $whitelabel_id, $nullagency, $nullagency, $codeagency, $nullagency);
							$stmt->execute();
							$stmt->close();
						} elseif($type == "5") {
							$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = CASE WHEN(`ulicense` IS NULL OR `ulicense` = -1) THEN ? ELSE `ulicense` + ? END, `whitelabel_id` = ?, `whitelabel` = ?, `superagency` = ?, `agency` = ?, `subagency` = ? WHERE `user_id` = {$user->user_id}");
							$stmt->bind_param('ssssssss', $types, $_POST['transfer_license'], $_POST['transfer_license'], $whitelabel_id, $nullagency, $nullagency, $nullagency, $codeagency);
							$stmt->execute();
							$stmt->close();
						} else {
							$types = 0;
							$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `superagency` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
							$stmt->bind_param('ssss', $types, $nullagency, $nullagency, $nullagency);
							$stmt->execute();
							$stmt->close();
						}
					}
				
				//Agency
				} elseif($this->user->type == 1 && ($this->user->agency=='Y' && empty($this->user->whitelabel) && empty($this->user->superagency) && empty($this->user->subagency))) {
					if(isset($_POST['transfer_license'])) {
						if(!is_null($this->user->ulicense) && $this->user->ulicense > 0) {
							$stmt = Database::$database->prepare("UPDATE `users` SET `ulicense` = `ulicense` - ? WHERE `user_id` = {$this->user->user_id}");
							$stmt->bind_param('s', $_POST['transfer_license']);
							$stmt->execute();
							$stmt->close();
						}
						
						if($type == "5") {
							$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = CASE WHEN(`ulicense` IS NULL OR `ulicense` = -1) THEN ? ELSE `ulicense` + ? END, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
							$stmt->bind_param('sssss', $types, $_POST['transfer_license'], $_POST['transfer_license'], $nullagency, $codeagency);
							$stmt->execute();
							$stmt->close();
						} else {
							$types = 0;
							$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
							$stmt->bind_param('sss', $types, $nullagency, $nullagency);
							$stmt->execute();
							$stmt->close();
						}
					}
					
				//Sub Agency
				} elseif($this->user->type == 1 && ($this->user->subagency=='Y' && empty($this->user->whitelabel) && empty($this->user->superagency) && empty($this->user->agency))) {
					$types = 0;
					$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
					$stmt->bind_param('ssss', $types, $_POST['license'], $nullagency, $nullagency);
					$stmt->execute();
					$stmt->close();
				}
				
				/* Update rajaongkir pro if enabled */
				if((int)$_POST['ro_status']==0 || (int)$_POST['ro_status']==1) {
					if(isset($_POST['ro_id'])) {
						$ro_pro_biolink = $ro_pro_expired = $ro_pro_courier = [];
						foreach($_POST['ro_id'] as $rkey => $rval) {
							if(isset($_POST['ro_date_add']) && $_POST['ro_date_add'][$rkey]) {
								if($_POST['ro_date_add'][$rkey]=='1_year')
									$ro_pro_expired[] = strtotime('NOW + 1 year');
								elseif($_POST['ro_date_add'][$rkey]=='6_months')
									$ro_pro_expired[] = strtotime('NOW + 6 months');
								elseif($_POST['ro_date_add'][$rkey]=='lifetime')
									$ro_pro_expired[] = "lifetime";
							} else {
								$ro_pro_expired[] = $_POST['ro_date_expired'][$rkey];
							}
							$ro_pro_biolink[] = $rval;
							$ro_pro_courier[] = $_POST['ro_courier'][$rkey];
						}
						
						$ro_pro_biolink = json_encode($ro_pro_biolink);
						$ro_pro_courier = json_encode($ro_pro_courier);
						$ro_pro_expired = json_encode($ro_pro_expired);
						
						$stmt = Database::$database->prepare("UPDATE `users` SET `ro_pro_package` = ?, `ro_pro_courier` = ?, `ro_pro_biolink` = ?, `ro_pro_expired` = ?  WHERE `user_id` = {$user->user_id}");
						$stmt->bind_param('ssss', $_POST['ro_status'], $ro_pro_courier, $ro_pro_biolink, $ro_pro_expired);
						$stmt->execute();
						$stmt->close();
					}
				} elseif((int)$_POST['ro_status']==-1) {
					$stmt = Database::$database->prepare("UPDATE `users` SET `ro_pro_package` = ?, `ro_pro_courier` = ?, `ro_pro_biolink` = ?, `ro_pro_expired` = ?  WHERE `user_id` = {$user->user_id}");
					$stmt->bind_param('ssss', 0, null, null, null);
					$stmt->execute();
					$stmt->close();
				}

                /* Update the password if set */
                if(!empty($_POST['new_password']) && !empty($_POST['repeat_password'])) {
                    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                    $stmt = Database::$database->prepare("UPDATE `users` SET `password` = ?  WHERE `user_id` = {$user->user_id}");
                    $stmt->bind_param('s', $new_password);
                    $stmt->execute();
                    $stmt->close();
                }

                $_SESSION['success'][] = $this->language->global->success_message->basic;

                redirect('admin/user-update/' . $user->user_id);
            }

        }

        /* Login Modal */
        $view = new \Altum\Views\View('admin/users/user_login_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');
		
        /* Get all the packages available */
		if($this->user->superagency == 'Y' || $this->user->agency == 'Y' || $this->user->subagency == 'Y' || $this->user->whitelabel == 'Y'){
			$packages_result = Database::$database->query("SELECT * FROM `packages` WHERE `is_enabled` = 1 AND (`uid` = {$this->user->user_id} OR `uid` = 0) AND (JSON_UNQUOTE(JSON_EXTRACT(`settings`, '$.exclude_agency')) = 'false' OR JSON_UNQUOTE(JSON_EXTRACT(`settings`, '$.exclude_agency')) IS NULL)");
			$domain_wl = null;
		} else {
			$packages_result = Database::$database->query("SELECT * FROM `packages` WHERE `is_enabled` = 1 AND `uid` = 0");
			$domain_wl = Database::$database->query("SELECT `id`,`url` FROM `whitelabel` ORDER BY `url`,`id` DESC");
		}
		
		$url_biolink = null;
		if($user->ro_pro_biolink) {
			$tmp_ro_pro_biolink = '('.implode(',',json_decode($user->ro_pro_biolink)).')';
			$dt_ro_pro_biolink = Database::$database->query("SELECT link_id,url FROM links WHERE user_id = '".$user->user_id."' AND link_id IN ".$tmp_ro_pro_biolink." AND type = 'biolink' AND subtype = 'base'");
			if($dt_ro_pro_biolink->num_rows) {
				$url_biolink = [];
				while($ro_rows = $dt_ro_pro_biolink->fetch_object()) {
					$url_biolink[$ro_rows->link_id] = $ro_rows->url;
				}
			}
		}
		
        /* Main View */
        $data = [
            'user'              => $user,
			'biolinks'			=> $biolinks,
			'url_biolink'		=> $url_biolink,
            'packages_result'   => $packages_result,
			'domain_wl'			=> $domain_wl
        ];

        $view = new \Altum\Views\View('admin/user-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }
	
	public function biolinksearch() {
		$q = trim($_POST['q']);
		$u = trim($_POST['u']);
		if($q && $u) {
			$arr_users = null;
			$users = Database::$database->query("SELECT link_id,url FROM links WHERE url LIKE '%".$q."%' AND user_id = '".$u."' AND type = 'biolink' AND subtype = 'base' ORDER BY user_id DESC LIMIT 10");
			while ($rows = $users->fetch_object()) {
				$arr_users[] = array("id" => $rows->link_id, "name" => $rows->url, "url" => url($rows->url));
			}
			
			Response::simple_json($arr_users);
		}
	}

}
