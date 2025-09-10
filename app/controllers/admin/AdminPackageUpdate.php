<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Middlewares\Authentication;

class AdminPackageUpdate extends Controller {

    public function index() {

        Authentication::guard('admin');
		
		$list_trial_expired = array('1' => '3 days',
									'2' => '1 week',
									'3' => '2 weeks',
									'4' => '3 weeks',
									'5' => '1 month',
									'6' => '3 month');

        $package_id = isset($this->params[0]) ? $this->params[0] : false;

        /* Make sure it is either the trial / free package or normal packages */
        switch($package_id) {

            case 'free':

                /* Get the current settings for the free package */
                $package = $this->settings->package_free;

                break;

            case 'trial':

                /* Get the current settings for the trial package */
                $package = $this->settings->package_trial;

                break;

            default:

                $package_id = (int) $package_id;

                /* Check if package exists */
				if($this->user->superagency == 'Y' || $this->user->agency == 'Y' || $this->user->subagency == 'Y' || $this->user->whitelabel == 'Y'){
					if(!$package = Database::get('*', 'packages', ['package_id' => $package_id, 'uid' => $this->user->user_id])) {
						redirect('admin/packages');
					}
				} else {
					if(!$package = Database::get('*', 'packages', ['package_id' => $package_id])) {
						redirect('admin/packages');
					}
				}

                /* Parse the settings of the package */
                $package->settings = json_decode($package->settings);

                break;

        }

        if(!empty($_POST)) {

            if (!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            switch ($_POST['type']) {

                /* Button to update all users package settings with these ones */
                case 'update_users_package_settings':

                    break;

                /* Update the package settings */
                case 'update':

                    /* Filter variables */
                    $_POST['settings'] = [
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
						'exclude_agency'        => (bool) isset($_POST['exclude_agency']),
                        'projects_limit'        => (int) $_POST['projects_limit'],
                        'biolinks_limit'        => (int) $_POST['biolinks_limit'],
                        'links_limit'           => (int) $_POST['links_limit'],
                        'domains_limit'         => (int) $_POST['domains_limit'],
                    ];

                    switch ($package_id) {

                        case 'free':

                            $_POST['name'] = Database::clean_string($_POST['name']);
                            $_POST['is_enabled'] = (int) $_POST['is_enabled'];

                            /* Make sure to not let the admin disable ALL the packages */
                            if(!$_POST['is_enabled']) {

                                $enabled_packages = (int) $this->settings->payment->is_enabled ? Database::$database->query("SELECT COUNT(*) AS `total` FROM `packages` WHERE `is_enabled` = 1")->fetch_object()->total ?? 0 : 0;

                                if(!$enabled_packages && !$this->settings->package_trial->is_enabled) {
                                    $_SESSION['error'][] = $this->language->admin_package_update->error_message->disabled_packages;
                                }
                            }

                            $setting_key = 'package_free';
                            $setting_value = json_encode([
                                'package_id' => 'free',
                                'name' => $_POST['name'],
                                'days' => null,
                                'is_enabled' => $_POST['is_enabled'],
                                'settings' => $_POST['settings']
                            ]);

                            break;

                        case 'trial':

                            $_POST['name'] = Database::clean_string($_POST['name']);
                            $_POST['days'] = (int)$_POST['days'];
                            $_POST['is_enabled'] = (int)$_POST['is_enabled'];

                            /* Make sure to not let the admin disable ALL the packages */
                            if(!$_POST['is_enabled']) {

                                $enabled_packages = (int) $this->settings->payment->is_enabled ? Database::$database->query("SELECT COUNT(*) AS `total` FROM `packages` WHERE `is_enabled` = 1")->fetch_object()->total ?? 0 : 0;

                                if(!$enabled_packages && !$this->settings->package_free->is_enabled) {
                                    $_SESSION['error'][] = $this->language->admin_package_update->error_message->disabled_packages;
                                }
                            }

                            $setting_key = 'package_trial';
                            $setting_value = json_encode([
                                'package_id' => 'trial',
                                'name' => $_POST['name'],
                                'days' => $_POST['days'],
                                'is_enabled' => $_POST['is_enabled'],
                                'settings' => $_POST['settings']
                            ]);

                            break;

                        default:

                            $_POST['name'] = Database::clean_string($_POST['name']);
							$_POST['is_trial'] = abs((int)$_POST['is_trial'])>1 ? 0 : (int)$_POST['is_trial'];
			
							if($_POST['is_trial']==1) {
								
								$_POST['trial_expired'] = isset($list_trial_expired[$_POST['trial_expired']]) ? $list_trial_expired[$_POST['trial_expired']] : $list_trial_expired['1'];
								$_POST['is_default'] = abs((int)$_POST['is_default'])>1 ? 0 : (int)$_POST['is_default'];
								
							} else {
								
								$_POST['trial_expired'] = null;
								$_POST['is_default'] = 0;
								
							}
							
							if($this->user->superagency || $this->user->agency == 'Y' || $this->user->subagency == 'Y' || $this->user->whitelabel == 'Y'){
								$_POST['monthly_price'] = 0;
								$_POST['annual_price'] = 0;
							}else{
								$_POST['monthly_price'] = (float) $_POST['monthly_price'];
								$_POST['annual_price'] = (float) $_POST['annual_price'];
							}
                            $_POST['monthly_price'] = (float)$_POST['monthly_price'];
                            $_POST['annual_price'] = (float)$_POST['annual_price'];
                            $_POST['is_enabled'] = (int)$_POST['is_enabled'];
                            $_POST['settings'] = json_encode($_POST['settings']);

                            /* Make sure to not let the admin disable ALL the packages */
                            if(!$_POST['is_enabled']) {

                                $enabled_packages = (int) Database::$database->query("SELECT COUNT(*) AS `total` FROM `packages` WHERE `is_enabled` = 1")->fetch_object()->total ?? 0;

                                if(
                                    (
                                        !$enabled_packages ||
                                        ($enabled_packages == 1 && $package->is_enabled))
                                    && !$this->settings->package_free->is_enabled
                                    && !$this->settings->package_trial->is_enabled
                                ) {
                                    $_SESSION['error'][] = $this->language->admin_package_update->error_message->disabled_packages;
                                }
                            }

                            break;

                    }

                    break;
            }


            if (empty($_SESSION['error'])) {

                switch ($_POST['type']) {

                    /* Button to update all users package settings with these ones */
                    case 'update_users_package_settings':

                        $package_settings = json_encode($package->settings);

                        $stmt = Database::$database->prepare("UPDATE `users` SET `package_settings` = ? WHERE `package_id` = ?");
                        $stmt->bind_param('ss', $package_settings, $package_id);
                        $stmt->execute();
                        $stmt->close();

                        break;

                    /* Update the package settings */
                    case 'update':

                        /* Update the database */
                        switch ($package_id) {

                            case 'free':
                            case 'trial':
                                $stmt = Database::$database->prepare("UPDATE `settings` SET `value` = ? WHERE `key` = ?");
                                $stmt->bind_param('ss', $setting_value, $setting_key);
                                $stmt->execute();
                                $stmt->close();

                                /* Clear the cache */
                                \Altum\Cache::$adapter->deleteItem('settings');

                                break;

                            default:
								
                                /* Update the database */
								if($_POST['is_trial']==1&&$_POST['is_default']==1) {
									$stmt = Database::$database->prepare("UPDATE `packages` SET `is_default` = 0 WHERE `uid` = {$this->user->user_id} AND `is_default` = 1");
									$stmt->execute();
									$stmt->close();
								}
								
                                $stmt = Database::$database->prepare("UPDATE `packages` SET `name` = ?, `monthly_price` = ?, `annual_price` = ?, `settings` = ?, `is_enabled` = ?, `is_trial` = ?, `is_default` = ?, `trial_expired` = ? WHERE `package_id` = ?");
                                $stmt->bind_param('sssssssss', $_POST['name'], $_POST['monthly_price'], $_POST['annual_price'], $_POST['settings'], $_POST['is_enabled'], $_POST['is_trial'], $_POST['is_default'], $_POST['trial_expired'], $package_id);
                                $stmt->execute();
                                $stmt->close();

                                break;

                        }

                }

                /* Set a nice success message */
                $_SESSION['success'][] = $this->language->global->success_message->basic;

                /* Refresh the page */
                redirect('admin/package-update/' . $package_id);

            }

        }


        /* Main View */
        $data = [
            'package_id'    => $package_id,
            'package'       => $package,
        ];

        $view = new \Altum\Views\View('admin/package-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
