<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Models\Package;
use Altum\Middlewares\Authentication;

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

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name']		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $_POST['status']	= (int) $_POST['status'];
            $type	    = $_POST['type'];
            if($type == "2" || $type == "3" || $type == "4"){
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
			
			if($this->user->type == 1 && (empty($this->user->whitelabel) && empty($this->user->agency) && empty($this->user->subagency))) {
				if(isset($_POST['domain_whitelabel'])) {
					if($user->whitelabel_id!=$_POST['domain_whitelabel']&&$user->whitelabel_id!=0) {
						if(Database::exists('id', 'users', ['whitelabel' => 'Y', 'whitelabel_id' => $_POST['domain_whitelabel']])) {
							$_SESSION['error'][] = "This Domain for Whitelabel has beed used!.";
						}
					}
				}
				
				if($type == "2"){
					$whitelabel_id = $_POST['domain_whitelabel'];
					if(empty($_POST['domain_whitelabel'])||!is_numeric($_POST['domain_whitelabel'])) {
						$whitelabel_id = 0;
					}
				}
			} elseif($this->user->type == 1 && ($this->user->whitelabel=='Y' || $this->user->agency=='Y' && $this->user->subagency=='Y')) {
				$whitelabel_id = $this->user->whitelabel_id;
			}
			
			if($type == "2"){
				if($this->user->type == 1 && ($this->user->whitelabel=='Y' && empty($this->user->agency) && empty($this->user->subagency))) {
					if(!is_null($this->user->ulicense) && (int)$this->user->ulicense > -1) {
						$sub_license_user = !is_null($this->user->ulicense) && $this->user->ulicense > 0 ? (int)$this->user->ulicense : 0;
						$sub_license_user = $sub_license_user - (int)$_POST['transfer_license'];
						if($sub_license_user<=0) {
							$_SESSION['error'][] = 'Licenses has exceeded the limit!.';
						}
					}
				}
			} elseif($type == "3"){
				if($this->user->type == 1 && ($this->user->agency=='Y' && empty($this->user->whitelabel) && empty($this->user->subagency))) {
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
				if($this->user->type == 1 && (empty($this->user->whitelabel) && empty($this->user->agency) && empty($this->user->subagency))) {
					if(trim($_POST['license'])=='')
						$_POST['license'] = 0;
					else
						$_POST['license'] = (int)$_POST['license'];
						
					if($_POST['license']<-1) $_POST['license'] = 0;
				} elseif($this->user->type == 1 && (($this->user->whitelabel == 'Y' || $this->user->agency == 'Y') && empty($this->user->subagency))) {
					if(trim($_POST['transfer_license'])==''||intval($_POST['transfer_license'])<0)
						$_POST['transfer_license'] = 0;
					else
						$_POST['transfer_license'] = (int)$_POST['transfer_license'];
					
					if((int)$_POST['transfer_license']<0) $_POST['transfer_license'] = 0;
				} elseif($this->user->type == 1 && ($this->user->subagency == 'Y' && empty($this->user->agency))) {
					if(trim($_POST['transfer_license'])==''||intval($_POST['transfer_license'])<0)
						$_POST['transfer_license'] = 0;
					else
						$_POST['transfer_license'] = (int)$_POST['transfer_license'];
				}
				
				$max_transfer = $this->user->type == 1 && $this->user->agency == 'Y' ? 25 : 10000;
				
				if(isset($_POST['transfer_license'])) if((int)$_POST['transfer_license']<0) $_POST['transfer_license'] = 0;
				if(isset($_POST['transfer_license'])) if((int)$_POST['transfer_license']>$max_transfer) $_POST['transfer_license'] = $max_transfer;
				
				//Admin
				if($this->user->type == 1 && (empty($this->user->whitelabel) && empty($this->user->agency) && empty($this->user->subagency))) {
					if($type == "1"){
						$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
						$stmt->bind_param('ssssss', $types, $_POST['license'], $whitelabel_id, $codeagency, $nullagency, $nullagency);
						$stmt->execute();
						$stmt->close();
						if($user->whitelabel_id!=$whitelabel_id)	
							update_wl_id($whitelabel_id,$user,true);
					} elseif($type == "2"){
						$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
						$stmt->bind_param('ssssss', $types, $_POST['license'], $whitelabel_id, $codeagency, $nullagency, $nullagency);
						$stmt->execute();
						$stmt->close();
						if($user->whitelabel_id!=$whitelabel_id)
							update_wl_id($whitelabel_id,$user);
					} elseif($type == "3") {
						$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
						$stmt->bind_param('ssssss', $types, $_POST['license'], $whitelabel_id, $nullagency, $codeagency, $nullagency);
						$stmt->execute();
						$stmt->close();
						if($user->whitelabel_id!=$whitelabel_id)	
							update_wl_id($whitelabel_id,$user,true);
					} elseif($type == "4") {
						$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
						$stmt->bind_param('ssssss', $types, $_POST['license'], $whitelabel_id, $nullagency, $nullagency, $codeagency);
						$stmt->execute();
						$stmt->close();
						if($user->whitelabel_id!=$whitelabel_id)
							update_wl_id($whitelabel_id,$user,true);
					} else {
						$types = 0;
						$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
						$stmt->bind_param('ssssss', $types, $_POST['license'], $whitelabel_id, $nullagency, $nullagency, $nullagency);
						$stmt->execute();
						$stmt->close();
						if($user->whitelabel_id!=$whitelabel_id)
							update_wl_id($whitelabel_id,$user,true);
					}
				//Whitelabel
				} elseif($this->user->type == 1 && ($this->user->whitelabel=='Y' && empty($this->user->agency) && empty($this->user->subagency))) {
					if(isset($_POST['transfer_license'])) {
						if(!is_null($this->user->ulicense) && $this->user->ulicense > 0) {
							$stmt = Database::$database->prepare("UPDATE `users` SET `ulicense` = `ulicense` - ? WHERE `user_id` = {$this->user->user_id}");
							$stmt->bind_param('s', $_POST['transfer_license']);
							$stmt->execute();
							$stmt->close();
						}
						
						if($type == "3") {
							$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = CASE WHEN(`ulicense` IS NULL OR `ulicense` = -1) THEN ? ELSE `ulicense` + ? END, `whitelabel_id` = ?, `whitelabel` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
							$stmt->bind_param('sssssss', $types, $_POST['transfer_license'], $_POST['transfer_license'], $whitelabel_id, $nullagency, $codeagency, $nullagency);
							$stmt->execute();
							$stmt->close();
						} elseif($type == "4") {
							$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = CASE WHEN(`ulicense` IS NULL OR `ulicense` = -1) THEN ? ELSE `ulicense` + ? END, `whitelabel_id` = ?, `whitelabel` = ?, `agency` = ?, `subagency` = ? WHERE `user_id` = {$user->user_id}");
							$stmt->bind_param('sssssss', $types, $_POST['transfer_license'], $_POST['transfer_license'], $whitelabel_id, $nullagency, $nullagency, $codeagency);
							$stmt->execute();
							$stmt->close();
						} else {
							$types = 0;
							$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
							$stmt->bind_param('ssssss', $types, $whitelabel_id, $nullagency, $nullagency, $nullagency);
							$stmt->execute();
							$stmt->close();
						}
					}
				//Agency
				} elseif($this->user->type == 1 && ($this->user->agency=='Y' && empty($this->user->whitelabel) && empty($this->user->subagency))) {
					if(isset($_POST['transfer_license'])) {
						if(!is_null($this->user->ulicense) && $this->user->ulicense > 0) {
							$stmt = Database::$database->prepare("UPDATE `users` SET `ulicense` = `ulicense` - ? WHERE `user_id` = {$this->user->user_id}");
							$stmt->bind_param('s', $_POST['transfer_license']);
							$stmt->execute();
							$stmt->close();
						}
						
						if($type == "4") {
							$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = CASE WHEN(`ulicense` IS NULL OR `ulicense` = -1) THEN ? ELSE `ulicense` + ? END, `whitelabel_id` = ?, `whitelabel` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
							$stmt->bind_param('sssssss', $types, $_POST['transfer_license'], $_POST['transfer_license'], $whitelabel_id, $nullagency, $nullagency, $codeagency);
							$stmt->execute();
							$stmt->close();
						} else {
							$types = 0;
							$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
							$stmt->bind_param('ssssss', $types, $whitelabel_id, $nullagency, $nullagency, $nullagency);
							$stmt->execute();
							$stmt->close();
						}
					}
				//Sub Agency
				} elseif($this->user->type == 1 && ($this->user->subagency=='Y' && empty($this->user->whitelabel) && empty($this->user->agency))) {
					$types = 0;
					$stmt = Database::$database->prepare("UPDATE `users` SET `type` = ?, `ulicense` = ?, `whitelabel_id` = ?, `whitelabel` = ?, `agency` = ?, `subagency` = ?  WHERE `user_id` = {$user->user_id}");
					$stmt->bind_param('ssssss', $types, $_POST['license'], $whitelabel_id, $nullagency, $nullagency, $nullagency);
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
		if($this->user->agency == 'Y' || $this->user->subagency == 'Y' || $this->user->whitelabel == 'Y'){
			$packages_result = Database::$database->query("SELECT * FROM `packages` WHERE `is_enabled` = 1 AND (`uid` = {$this->user->user_id} OR `uid` = 0) AND (JSON_UNQUOTE(JSON_EXTRACT(`settings`, '$.exclude_agency')) = 'false' OR JSON_UNQUOTE(JSON_EXTRACT(`settings`, '$.exclude_agency')) IS NULL)");
			$domain_wl = null;
		} else {
			$packages_result = Database::$database->query("SELECT * FROM `packages` WHERE `is_enabled` = 1 AND `uid` = 0");
			$domain_wl = Database::$database->query("SELECT `id`,`url` FROM `whitelabel` ORDER BY `url`,`id` DESC");
		}
		
        /* Main View */
        $data = [
            'user'              => $user,
            'packages_result'   => $packages_result,
			'domain_wl'			=> $domain_wl
        ];

        $view = new \Altum\Views\View('admin/user-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
