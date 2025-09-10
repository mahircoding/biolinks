<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;
use Altum\Validation;
use Altum\Routing\Router;

class LinkAjax extends Controller {
    public function index() {

        /* Mail subscriber form submission check check */
        if($_POST['request_type'] !== 'mail') {
            Authentication::guard();
        } else {
            $this->mail();
        }
		
		if(isset($_POST['request_type']))
			$_POST['request_type'] = strip_tags(trim(Database::clean_string($_POST['request_type'])));
		
        if(!empty($_POST) && (Csrf::check('token') || Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Status toggle */
                case 'is_enabled_toggle': $this->is_enabled_toggle(); break;

                /* Duplicate link */
                case 'duplicate': $this->duplicate(); break;

                /* Order links */
                case 'order': $this->order(); break;

                /* Create */
                case 'create': $this->create(); break;

                /* Update */
                case 'update': $this->update(); break;

                /* Delete */
                case 'delete': $this->delete(); break;

            }

        }

        //die($_POST['request_type']);
		die('Invalid Request');
    }

    private function is_enabled_toggle() {
        $_POST['link_id'] = (int) $_POST['link_id'];

        /* Get the current status */
        $link = Database::get(['link_id', 'biolink_id', 'is_enabled'], 'links', ['link_id' => $_POST['link_id']]);

        if($link) {
            $new_is_enabled = (int) !$link->is_enabled;

            Database::$database->query("UPDATE `links` SET `is_enabled` = {$new_is_enabled} WHERE `user_id` = {$this->user->user_id} AND `link_id` = {$link->link_id}");

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

            Response::json('', 'success');
        }
    }

    private function duplicate() {
        $_POST['link_id'] = (int) $_POST['link_id'];

        /* Get the link data */
        $link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id, 'type' => 'biolink', 'subtype' => 'link']);
        $html = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id, 'type' => 'biolink', 'subtype' => 'html']);

        if($link) {
            $link->settings = json_decode($link->settings);

            $url = string_generate(10);
            $settings = json_encode([
                'name' => $link->settings->name,
                'text_color' => $link->settings->text_color,
                'background_color' => $link->settings->background_color,
                'outline' => $link->settings->outline,
                'border_radius' => $link->settings->border_radius,
                'animation' => $link->settings->animation,
                'icon' => $link->settings->icon
            ]);

            /* Generate random url if not specified */
            while(Database::exists('link_id', 'links', ['url' => $url])) {
                $url = string_generate(10);
            }

            $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `start_date`, `end_date`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssssssss', $link->project_id, $link->biolink_id, $this->user->user_id, $link->type, $link->subtype, $url, $link->location_url, $settings, $link->start_date, $link->end_date, \Altum\Date::$date);
            $stmt->execute();
            $stmt->close();

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

            Response::json('', 'success', ['url' => url('link/' . $link->biolink_id . '?tab=links')]);

        }

        if($html) {
            $html->settings = json_decode($html->settings);

            $order = 99;
            $url = string_generate(10);
            $settings = json_encode([
                'description' => $html->settings->description,
                'description_text_color' => $html->settings->description_text_color
            ]);

            $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `settings`, `start_date`, `end_date`, `date`, `order`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssssssss', $html->project_id, $html->biolink_id, $this->user->user_id, $html->type, $html->subtype, $settings, $html->start_date, $html->end_date, \Altum\Date::$date, $order);
            $stmt->execute();
            $stmt->close();

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

            Response::json('', 'success', ['url' => url('link/' . $html->biolink_id . '?tab=links')]);

        }
    }

    private function order() {

        if(isset($_POST['links']) && is_array($_POST['links'])) {
            foreach($_POST['links'] as $link) {
                $link['link_id'] = (int) $link['link_id'];
                $link['order'] = (int) $link['order'];

                /* Update the link order */
                $stmt = $this->database->prepare("UPDATE `links` SET `order` = ? WHERE `link_id` = ? AND `user_id` = ?");
                $stmt->bind_param('sss', $link['order'], $link['link_id'], $this->user->user_id);
                $stmt->execute();
                $stmt->close();

            }
        }

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success');
    }

    private function create() {
        $_POST['type'] = trim(Database::clean_string($_POST['type']));

        /* Check for possible errors */
        if(!in_array($_POST['type'], ['link', 'biolink'])) {
            die();
        }

        switch($_POST['type']) {
            case 'link':

                $this->create_link();

                break;

            case 'biolink':

                $biolink_link_types = require APP_PATH . 'includes/biolink_link_types.php';

                /* Check for subtype */
                if(isset($_POST['subtype']) && in_array($_POST['subtype'], $biolink_link_types)) {
                    $_POST['subtype'] = trim(Database::clean_string($_POST['subtype']));
		
                    if($_POST['subtype'] == 'link') {
                        $this->create_biolink_link();
                    } else if($_POST['subtype'] == 'mail') {
                        $this->create_biolink_mail();
                    } else if($_POST['subtype'] == 'text') {
                        $this->create_biolink_text();
                    } else if($_POST['subtype'] == 'runningtext') {
                        $this->create_biolink_runningtext();
                    } else if($_POST['subtype'] == 'html') {
                        $this->create_biolink_html();
                    } else if($_POST['subtype'] == 'picture') {
                        $this->create_biolink_picture();
                    } else if($_POST['subtype'] == 'banner') {
                        $this->create_biolink_picture('banner');
                    } else if($_POST['subtype'] == 'sliders') {
                        $this->create_biolink_picture('sliders');
                    } else if($_POST['subtype'] == 'waform') {
                        $this->create_biolink_waform();
                    } else if($_POST['subtype'] == 'cartform') {
                        $this->create_biolink_cartform();
                    } else if($_POST['subtype'] == 'domain') {
                        $this->create_biolink_domain();
                    } else if($_POST['subtype'] == 'googlemap') {
                        $this->create_biolink_googlemap();
                    } else if($_POST['subtype'] == 'countdown') {
                        $this->create_biolink_countdown();
                    } else if($_POST['subtype'] == 'floatbutton') {
                        $this->create_biolink_floatbutton();
                    } else if($_POST['subtype'] == 'pricingtable') {
                        $this->create_biolink_pricingtable();
                    } else if($_POST['subtype'] == 'eshop') {
                        $this->create_biolink_eshop();
                    }
                    else {
                        $this->create_biolink_other($_POST['subtype']);
                    }


                } else if(isset($_POST['subtype'])) {
					if($_POST['subtype'] == 'export') {
						$this->create_biolink_export();
					} else if($_POST['subtype'] == 'import') {
                        $this->create_biolink_import();
                    } else if($_POST['subtype'] == 'duplicate') {
                        $this->create_duplicate_biolink();
                    }
                } else {
                    /* Base biolink */
                    $this->create_biolink();
                }

                break;
        }

        die();
    }

    private function create_link() {
        $_POST['project_id'] = (int) $_POST['project_id'];
        $_POST['location_url'] = trim(Database::clean_string($_POST['location_url']));
        $_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;

        /* Check if custom domain is set */
        $domain_id = $this->get_domain_id($_POST['domain_id'] ?? false);

        if(!Database::exists('project_id', 'projects', ['user_id' => $this->user->user_id, 'project_id' => $_POST['project_id']])) {
            die();
        }

        if(empty($_POST['location_url'])) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        $this->check_url($_POST['url']);

        $this->check_location_url($_POST['location_url']);

        /* Make sure that the user didn't exceed the limit */
        $user_total_links = Database::$database->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id} AND `type` = 'link'")->fetch_object()->total;
        if($this->user->package_settings->links_limit != -1 && $user_total_links >= $this->user->package_settings->links_limit) {
            Response::json($this->language->create_link_modal->error_message->links_limit, 'error');
        }

        /* Check for duplicate url if needed */
        if($_POST['url']) {

            if(Database::exists('link_id', 'links', ['url' => $_POST['url'], 'domain_id' => $domain_id])) {
                Response::json($this->language->create_link_modal->error_message->url_exists, 'error');
            }

        }

        if(empty($errors)) {
            $url = $_POST['url'] ? $_POST['url'] : string_generate(10);
            $type = 'link';
            $subtype = '';
            $settings = '';

            /* Generate random url if not specified */
            while(Database::exists('link_id', 'links', ['url' => $url, 'domain_id' => $domain_id])) {
                $url = string_generate(10);
            }

            /* Insert to database */
            $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `user_id`, `domain_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssssss', $_POST['project_id'], $this->user->user_id, $domain_id, $type, $subtype, $url, $_POST['location_url'], $settings, \Altum\Date::$date);
            $stmt->execute();
            $link_id = $stmt->insert_id;
            $stmt->close();

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

            Response::json('', 'success', ['url' => url('link/' . $link_id)]);
        }
    }

    private function create_biolink() {
        $_POST['project_id'] = (int) $_POST['project_id'];
        $_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;

        /* Check if custom domain is set */
        $domain_id = $this->get_domain_id($_POST['domain_id'] ?? false);

        if(!Database::exists('project_id', 'projects', ['user_id' => $this->user->user_id, 'project_id' => $_POST['project_id']])) {
            die();
        }

        /* Make sure that the user didn't exceed the limit */
        $user_total_biolinks = Database::$database->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id} AND `type` = 'biolink' AND `subtype` = 'base'")->fetch_object()->total;
		if($this->user->package_settings->biolinks_limit != -1 && $user_total_biolinks >= $this->user->package_settings->biolinks_limit) {
            Response::json($this->language->create_link_modal->error_message->biolinks_limit, 'error');
        }

        /* Check for duplicate url if needed */
        if($_POST['url']) {
            if(Database::exists('link_id', 'links', ['url' => $_POST['url'], 'domain_id' => $domain_id])) {
                Response::json($this->language->create_biolink_modal->error_message->url_exists, 'error');
            }
        }

        /* Start the creation process */
        $url = $_POST['url'] ? $_POST['url'] : string_generate(10);
        $type = 'biolink';
        $subtype = 'base';
        $settings = json_encode([
            //'title' => $this->language->link->biolink->title_default,
			'title' => '',
            'description' => $this->language->link->biolink->description_default,
            'display_verified' => false,
			'password_protector' => false,
			'password_lock' => '',
            'image' => '',
			'page_transition_type' => '0',
            'background_type' => 'preset',
            'background' => 'one',
            'text_color' => 'white',
            'socials_color' => 'white',
            'google_analytics' => '',
            'facebook_pixel' => '',
            'display_branding' => true,
            'branding' => [
                'url' => '',
                'name' => ''
            ],
            'seo' => [
                'title' => '',
                'meta_description' => ''
            ],
            'utm' => [
                'medium' => '',
                'source' => '',
            ],
            'socials' => [],
            'font' => null
        ]);

        /* Generate random url if not specified */
        while(Database::exists('link_id', 'links', ['url' => $url, 'domain_id' => $domain_id])) {
            $url = string_generate(10);
        }

        $this->check_url($_POST['url']);

        /* Insert to database */
        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `user_id`, `domain_id`, `type`, `subtype`, `url`, `settings`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssss', $_POST['project_id'], $this->user->user_id, $domain_id, $type, $subtype, $url,  $settings, \Altum\Date::$date);
        $stmt->execute();
        $link_id = $stmt->insert_id;
        $stmt->close();

        /* Insert a first biolink link */
        $url = string_generate(10);
        $location_url = url();
        $type = 'biolink';
        $subtype = 'link';
        $settings = json_encode([
            'name' => $this->language->link->biolink->link->name_default,
            'text_color' => 'black',
            'background_color' => 'white',
            'outline' => false,
            'border_radius' => 'rounded',
            'animation' => false,
            'icon' => ''
        ]);

        /* Generate random url if not specified */
        while(Database::exists('link_id', 'links', ['url' => $url])) {
            $url = string_generate(10);
        }

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssssss', $_POST['project_id'], $link_id, $this->user->user_id, $type, $subtype, $url, $location_url, $settings, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $link_id)]);
    }
	
	private function create_duplicate_biolink() {
		$_POST['link_id'] = (int) $_POST['link_id'];
		$_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;
		
		/* Make sure that the user didn't exceed the limit */
        $user_total_biolinks = Database::$database->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id} AND `type` = 'biolink' AND `subtype` = 'base'")->fetch_object()->total;
		if($this->user->package_settings->biolinks_limit != -1 && $user_total_biolinks >= $this->user->package_settings->biolinks_limit) {
            Response::json($this->language->create_link_modal->error_message->biolinks_limit, 'error');
        }
		
		$link_base = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id]);
		
		$domain_id = $link_base->domain_id;
		$project_id = $link_base->project_id;
		if(!Database::exists('project_id', 'projects', ['project_id' => $project_id, 'user_id' => $this->user->user_id])) {
			die();
		}
		
		/* Check for duplicate url if needed */
        if($_POST['url']) {
            if(Database::exists('link_id', 'links', ['url' => $_POST['url'], 'domain_id' => $domain_id])) {
                Response::json($this->language->create_biolink_modal->error_message->url_exists, 'error');
            }
        }
		
		$url = $_POST['url'];
		$type = $link_base->type;
        $subtype = $link_base->subtype;
		$settings = $link_base->settings;
		$order = $link_base->order;
		$is_enabled = $link_base->is_enabled;
		
		/* Insert to database */
		$stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `user_id`, `domain_id`, `type`, `subtype`, `url`, `settings`, `order`, `is_enabled`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param('ssssssssss', $project_id, $this->user->user_id, $domain_id, $type, $subtype, $url,  $settings,  $order,  $is_enabled, \Altum\Date::$date);
		$stmt->execute();
		$biolink_id = $stmt->insert_id;
		$stmt->close();

        $this->check_url($_POST['url']);
		
		$result = Database::$database->query("SELECT * FROM links WHERE biolink_id = {$_POST['link_id']} and user_id = {$this->user->user_id}");
		while($links = $result->fetch_object()) {
			$url = $links->url;
			$location_url = $links->location_url;
			$type = $links->type;
			$subtype = $links->subtype;
			$settings = $links->settings;
			$domain_id = $links->domain_id;
			$order = $links->order;
			$is_enabled = $links->is_enabled;
			
			/* Generate random url if not specified */
			if(!empty($location_url)) {
				$url = string_generate(10);
				while(Database::exists('link_id', 'links', ['url' => $url, 'domain_id' => $domain_id])) {
					$url = string_generate(10);
				}
			}
			
			if (!file_exists(UPLOADS_PATH . 'galleries/' . $biolink_id)) {
				mkdir(UPLOADS_PATH . 'galleries/' . $biolink_id, 0755, true);
			}
			
			if($subtype == 'picture') {
				$jso_settings = json_decode($settings,true);
				$jso_settings['picture_url'] = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $biolink_id . '/' . $jso_settings['picture_name'];
				$settings = json_encode($jso_settings);
				copy(UPLOADS_PATH . 'galleries/' . $_POST['link_id'] . '/' . $jso_settings['picture_name'], UPLOADS_PATH . 'galleries/' . $biolink_id . '/' . $jso_settings['picture_name']);
			} elseif($subtype == 'sliders') {
				$jso_settings = json_decode($settings,true);
				for($i=0;$i<count($jso_settings['images']);$i++) {
					$jso_settings['images'][$i]['image_url'] = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $biolink_id . '/' . $jso_settings['images'][$i]['image_name'];
					copy(UPLOADS_PATH . 'galleries/' . $_POST['link_id'] . '/' . $jso_settings['images'][$i]['image_name'], UPLOADS_PATH . 'galleries/' . $biolink_id . '/' . $jso_settings['images'][$i]['image_name']);
				}
				$settings = json_encode($jso_settings);
			} elseif($subtype == 'cartform') {
				$jso_settings = json_decode($settings,true);
				$jso_settings['photo'] = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $biolink_id . '/' . $jso_settings['photo_name'];
				$settings = json_encode($jso_settings);
				copy(UPLOADS_PATH . 'galleries/' . $_POST['link_id'] . '/' . $jso_settings['photo_name'], UPLOADS_PATH . 'galleries/' . $biolink_id . '/' . $jso_settings['photo_name']);
			}
			
			/* Insert to database */
			$stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `user_id`, `biolink_id`, `domain_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `is_enabled`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param('ssssssssssss', $project_id, $this->user->user_id, $biolink_id, $domain_id, $type, $subtype, $url, $location_url,  $settings,  $order,  $is_enabled, \Altum\Date::$date);
			$stmt->execute();
			$stmt->close();
		}
		
		Response::json('', 'success', ['url' => url('link/' . $biolink_id)]);
	}

    private function create_biolink_domain_old() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = trim(Database::clean_string($_POST['location_url']));
        $_POST['type_height'] = (int) $_POST['type_height']; 
        $_POST['height'] = (int) $_POST['height'];

        $this->check_location_url($_POST['location_url']);

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }
        
        if($_POST['type_height'] == 0) {
            $_POST['height'] = 'auto';
        } 

        $url = string_generate(10);
        $type = 'biolink';
        $subtype = 'domain';
		$order = 99;
        $settings = json_encode([
            'height' => $_POST['height']
        ]);

        /* Generate random url if not specified */
        while(Database::exists('link_id', 'links', ['url' => $url])) {
            $url = string_generate(10);
        }

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $_POST['location_url'], $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }

    private function create_biolink_link() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = trim(Database::clean_string($_POST['location_url']));

        $this->check_location_url($_POST['location_url']);

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }

        $url = string_generate(10);
        $type = 'biolink';
        $subtype = 'link';
		$order = 99;
        $settings = json_encode([
            'name' => $this->language->link->biolink->link->name_default,
            'text_color' => 'black',
            'background_color' => 'white',
            'outline' => false,
            'border_radius' => 'rounded',
            'animation' => false,
            'icon' => ''
        ]);

        /* Generate random url if not specified */
        while(Database::exists('link_id', 'links', ['url' => $url])) {
            $url = string_generate(10);
        }

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $_POST['location_url'], $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }

    private function create_biolink_other($subtype) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = trim(Database::clean_string($_POST['location_url']));

        $this->check_location_url($_POST['location_url']);
			
        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }

        $url = string_generate(10);
        $type = 'biolink';
		if($subtype=='soundcloud')
			$settings = json_encode(['autoplay' => isset($_POST['autoplay']) ? true : false]);
		else
			$settings = null;
		$order = 99;

        /* Generate random url if not specified */
        while(Database::exists('link_id', 'links', ['url' => $url])) {
            $url = string_generate(10);
        }

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $_POST['location_url'], $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }

    private function create_biolink_mail() {
        $_POST['link_id'] = (int) $_POST['link_id'];

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }

        $url = $location_url = '';
        $type = 'biolink';
        $subtype = 'mail';
		$order = 99;
        $settings = json_encode([
            'name' => $this->language->link->biolink->mail->name_default,
            'text_color' => 'black',
            'background_color' => 'white',
            'outline' => false,
            'border_radius' => 'rounded',
            'animation' => false,
            'icon' => '',

            'email_placeholder' => $this->language->link->biolink->mail->email_placeholder_default,
            'button_text' => $this->language->link->biolink->mail->button_text_default,
            'success_text' => $this->language->link->biolink->mail->success_text_default,
            'show_agreement' => false,
            'agreement_url' => '',
            'agreement_text' => '',
            'mailchimp_api' => '',
            'mailchimp_api_list' => '',
            'webhook_url' => ''
        ]);

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $location_url, $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }

    private function create_biolink_text() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['title'] = trim(Database::clean_string($_POST['title']));

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }

        $url = $location_url = '';
        $type = 'biolink';
        $subtype = 'text';
		$order = 99;
        $settings = json_encode([
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'title_text_color' => 'white',
            'description_text_color' => 'white',
        ]);

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $location_url, $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }

    private function create_biolink_runningtext() {
        $_POST['link_id'] = (int) $_POST['link_id'];

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }

        $url = $location_url = '';
        $type = 'biolink';
        $subtype = 'runningtext';
		$order = 99;
        $settings = json_encode([
            'description' => $_POST['description'],
            'description_runningtext_color' => 'white',
            'scrollamount' => 6,
        ]);

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $location_url, $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }
	
	private function create_biolink_html() {
        $_POST['link_id'] = (int) $_POST['link_id'];
		$_POST['description'] = str_replace("\r\n","",$_POST['description']);
		
		//require APP_PATH . 'helpers/HTMLPurifier/HTMLPurifier.standalone.php';
		//$config = \HTMLPurifier_Config::createDefault();
		//$purifier = new \HTMLPurifier($config);
		//$_POST['description'] = $purifier->purify(trim($_POST['description']));
		$_POST['description'] = trim($_POST['description']);

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }

        $url = $location_url = '';
        $type = 'biolink';
        $subtype = 'html';
		$order = 99;
        $settings = json_encode([
            'description' => $_POST['description'],
            'description_text_color' => '#fff',
        ]);

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $location_url, $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }
	
	private function create_biolink_picture($subtype='picture') {
        $_POST['link_id'] = (int) $_POST['link_id'];
		$image_allowed = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
		$folder_id = $_POST['link_id'];
		
		if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
			die();
		}
		
		if($subtype=='picture') {
			require APP_PATH . 'includes/ResizeImage.php';
			$mime_type = getimagesize($_FILES['image']['tmp_name']);
			$img_ext = 'jpg';
			
			if($_FILES['image']['error']) {
				Response::json($this->language->global->error_message->file_upload, 'error');
            }
			if($_FILES['image']['size']>716800) {
				Response::json($this->language->global->error_message->file_upload, 'error');
            }
			if(!in_array($mime_type['mime'],$image_allowed)) {
				Response::json($this->language->global->error_message->invalid_file_type, 'error');
			}
			
			if($mime_type['mime']!='image/gif') {
				$resize = new \ResizeImage($_FILES['image']['tmp_name']);
				$resize->resizeTo(800, 800, 'maxWidth');
				
				if (!file_exists(UPLOADS_PATH . 'galleries/' . $folder_id)) {
					mkdir(UPLOADS_PATH . 'galleries/' . $folder_id, 0755, true);
				}
				
				/* Generate new name for logo */
				if($mime_type['mime']=='image/png')
					$img_ext = 'png';
				
				$picture_name = md5(time() . rand()) . '.' . $img_ext;

				/* Upload the original */
				$resize->saveImage(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $picture_name, '90', $img_ext);
				
				$picture_url = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $folder_id . '/' . $picture_name;
			} else {
				$img_ext = 'gif';
				$picture_name = md5(time() . rand()) . '.' . $img_ext;
				
				if (!file_exists(UPLOADS_PATH . 'galleries/' . $folder_id)) {
					mkdir(UPLOADS_PATH . 'galleries/' . $folder_id, 0755, true);
				}
					
				move_uploaded_file($_FILES['image']['tmp_name'], UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $picture_name);
				
				$picture_url = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $folder_id . '/' . $picture_name;
			}
		} elseif($subtype=='banner') {
			$_POST['picture_url'] = trim(Database::clean_string($_POST['picture_url']));
			$_POST['target_url'] = trim(Database::clean_string($_POST['target_url']));
		} elseif($subtype=='sliders') {
			$errors = null;
			for($i=0;$i<count($_FILES['images']['tmp_name']);$i++) {
				$item_errors = null;
				if($_FILES['images']['size'][$i]>0) {
					$mime_type = getimagesize($_FILES['images']['tmp_name'][$i]);
					if($_FILES['images']['error'][$i]) {
						$item_errors[] = $this->language->global->error_message->file_upload_empty;
					} elseif($_FILES['images']['size'][$i]>716800) {
						$item_errors[] = $this->language->global->error_message->file_upload_max_size;
					} elseif(!in_array($mime_type['mime'],$image_allowed)) {
						$item_errors[] = $this->language->global->error_message->invalid_file_type;
					}
				} else {
					$item_errors[] = $this->language->global->error_message->file_upload_empty;
				}
				if(!empty($item_errors)) {
					$errors['images.'.$i] = $item_errors;
				}
			}
			
			if(!empty($errors)) {
				Response::json($errors, 'error','form');
				die();
			}
			
			require APP_PATH . 'includes/ResizeImage.php';
			$item_settings = null;
			
			if (!file_exists(UPLOADS_PATH . 'galleries/' . $folder_id)) {
				mkdir(UPLOADS_PATH . 'galleries/' . $folder_id, 0755, true);
			}
			
			for($i=0;$i<count($_FILES['images']['tmp_name']);$i++) {
				$mime_type = getimagesize($_FILES['images']['tmp_name'][$i]);
				$img_ext = 'jpg';
				
				$resize = new \ResizeImage($_FILES['images']['tmp_name'][$i]);
				$resize->resizeTo(800, 800, 'maxWidth');
				
				/* Generate new name for logo */
				if($mime_type['mime']=='image/png')
					$img_ext = 'png';
					
				$image_name = md5(time() . rand()) . '.' . $img_ext;

				/* Upload the original */
				$resize->saveImage(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $image_name, '90', $img_ext);
				
				$image_url = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $folder_id . '/' . $image_name;
				$item_settings[] = array("image_name" => $image_name,
										 "image_url" => $image_url);
			}
			$_POST['slider_animation'] = trim(Database::clean_string($_POST['slider_animation']));
			$_POST['slider_timer'] = trim(Database::clean_string($_POST['slider_timer']));
		} else {
			$_POST['picture_url'] = trim(Database::clean_string($_POST['picture_url']));
		}
		
        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }

        $url = $location_url = '';
        $type = 'biolink';
		$order = 99;
        
		if($subtype=='picture') {
			$subtype = 'picture';
			$settings = json_encode([
				'picture_name' => $picture_name,
				'picture_url' => $picture_url,
				'link_url' => filter_var($_POST['link_url'], FILTER_VALIDATE_URL) ? trim($_POST['link_url']) : '',
			]);
		} elseif($subtype=='banner') {
			$subtype = 'banner';
			$settings = json_encode([
				'picture_url' => $_POST['picture_url'],
				'target_url' => $_POST['target_url'],
			]);
		} elseif($subtype=='sliders') {
			$subtype = 'sliders';
			$settings = json_encode([
				'images' => $item_settings,
				'slider_animation' => $_POST['slider_animation'],
				'slider_timer' => $_POST['slider_timer'],
			]);
		} else {
			$subtype = 'picture';
			$settings = json_encode([
				'picture_url' => $_POST['picture_url'],
			]);
		}
		
        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $location_url, $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }
	
	private function create_biolink_waform() {
        $_POST['link_id'] = (int) $_POST['link_id'];

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }
		
        $url = $location_url = '';
        $type = 'biolink';
        $subtype = 'waform';
		$order = 99;
		if(!isset($_POST['name'])) $_POST['name'] = 'WA Form';
        $settings = json_encode([
            'wa_number' => $_POST['wa_number'],
			'wa_message' => $_POST['wa_message'],
			'name' => $_POST['name'],
            'text_color' => '#000',
            'background_color' => '#fff',
            'outline' => false,
            'border_radius' => 'rounded',
            'animation' => false,
            'icon' => '',
        ]);

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $location_url, $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }
	
	private function create_biolink_cartform() {
        $_POST['link_id'] = (int) $_POST['link_id'];

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }
		
		$image_allowed = ['image/png', 'image/jpeg', 'image/gif'];
		require APP_PATH . 'includes/ResizeImage.php';
		$mime_type = getimagesize($_FILES['image']['tmp_name']);
		$img_ext = 'jpg';
		
		$folder_id = $_POST['link_id'];
		
		if($_FILES['image']['error']) {
			Response::json($this->language->global->error_message->file_upload, 'error');
		}
		if(!in_array($mime_type['mime'],$image_allowed)) {
			Response::json($this->language->global->error_message->invalid_file_type, 'error');
		}
		
		$resize = new \ResizeImage($_FILES['image']['tmp_name']);
		$resize->resizeTo(800, 800, 'maxWidth');
		
		if (!file_exists(UPLOADS_PATH . 'galleries/' . $folder_id)) {
			mkdir(UPLOADS_PATH . 'galleries/' . $folder_id, 0755, true);
		}
		
		/* Generate new name for logo */
		if($mime_type['mime']=='image/png')
			$img_ext = 'png';
		
		$photo_name = md5(time() . rand()) . '.' . $img_ext;

		/* Upload the original */
		$resize->saveImage(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $photo_name, '90', $img_ext);
		
		$photo_url = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $folder_id . '/' . $photo_name;
		
        $url = $location_url = '';
        $type = 'biolink';
        $subtype = 'cartform';
		$order = 99;
		if(!isset($_POST['name'])) $_POST['name'] = 'Add to Cart';
        $settings = json_encode([
			'title' => $_POST['title'],
			'photo' => $photo_url,
			'photo_name' => $photo_name,
			'description' => $_POST['description'],
			'price' => $_POST['price'],
			'min_qty' => $_POST['min_qty'],
			'max_qty' => $_POST['max_qty'],
            'wa_number' => $_POST['wa_number'],
			'wa_message' => $_POST['wa_message'],
			'schedule' => false,
			'start_date' => '',
			'end_date' => '',
			'name' => $_POST['name'],
            'text_color' => '#000',
            'background_color' => '#fff',
            'outline' => false,
            'border_radius' => 'rounded',
            'animation' => false,
            'icon' => 'fa fa-shopping-cart',
        ]);

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $location_url, $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }
	
	private function create_biolink_googlemap() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['coordinates'] = trim(Database::clean_string($_POST['coordinates']));

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }

        $url = $location_url = '';
        $type = 'biolink';
        $subtype = 'googlemap';
		$order = 99;
        $settings = json_encode([
            'coordinates' => $_POST['coordinates'],
        ]);

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $location_url, $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }
	
	private function create_biolink_countdown() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['title'] = trim(Database::clean_string($_POST['title']));
		$_POST['end_date'] = trim(Database::clean_string($_POST['end_date']));

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }

        $url = $location_url = '';
        $type = 'biolink';
        $subtype = 'countdown';
		$order = 99;
        $settings = json_encode([
            'title' => $_POST['title'],
			'end_date' => $_POST['end_date'],
			'days' => '',
			'hours' => '',
			'minutes' => '',
			'seconds' => ''
        ]);

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $location_url, $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }
    
    private function create_biolink_domain() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = trim(Database::clean_string($_POST['location_url']));
		$_POST['type_height'] = (int) $_POST['type_height'];
		$_POST['height'] = (int) $_POST['height'];
		
		if(empty($_POST['height'])) $_POST['height'] = 300;
		
		if($_POST['type_height']<0 || $_POST['type_height']>2) $_POST['type_height'] = 0;
		if($_POST['type_height']==0 || $_POST['type_height']==2)
			$_POST['height'] = 'auto';
		
        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }

        $url = $location_url = '';
        $type = 'biolink';
        $subtype = 'domain';
		$order = 99;
        $settings = json_encode([
            'location_url' => $_POST['location_url'],
			'type_height' => $_POST['type_height'],
			'height' => $_POST['height'],
        ]);

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $location_url, $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }
	
	private function create_biolink_floatbutton() {
        $_POST['link_id'] = (int) $_POST['link_id'];
		$new_settings = array();
		
		for($i=0;$i<count($_POST['icon']);$i++) {
			$_POST['icon'][$i] = trim(Database::clean_string($_POST['icon'][$i]));
			$_POST['link_url'][$i] = trim(Database::clean_string($_POST['link_url'][$i]));
			$_POST['link_title'][$i] = trim(Database::clean_string($_POST['link_title'][$i]));
			$new_settings[] = array('icon' => $_POST['icon'][$i],
									'background_color' => '#fd4235',
									'text_color' => '#ffffff',
									'link_url' => $_POST['link_url'][$i],
									'link_title' => trim(Database::clean_string($_POST['link_title'][$i])));
		}

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }

        $url = $location_url = '';
        $type = 'biolink';
        $subtype = 'floatbutton';
		$order = 99;
        $settings = json_encode([
								'background_color' => '#fd4235',
								'text_color' => '#fff',
								'position' => trim(Database::clean_string($_POST['position'])),
								'configs' => $new_settings
								]);

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $location_url, $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }
	
	private function create_biolink_pricingtable() {
        $_POST['link_id'] = (int) $_POST['link_id'];
		$_POST['phone_number'] = (int) $_POST['phone_number'];

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }
		
		$new_items = array();
		
		for($i=0;$i<count($_POST['name']);$i++) {
			$features = array();
			
			for($j=0;$j<count($_POST['feat_bold'][$i]);$j++) {
				$features[] = array("bold" => trim(Database::clean_string($_POST['feat_bold'][$i][$j])),
									"normal" => trim(Database::clean_string($_POST['feat_normal'][$i][$j])));
 			}
			
			$new_items[] = array("name" => trim(Database::clean_string($_POST['name'][$i])),
								 "currency" => $_POST['currency'][$i],
								 "price" => floatval($_POST['price'][$i]),
								 "price_strike" => floatval($_POST['price_strike'][$i]),
								 "per" => ucwords(trim(Database::clean_string($_POST['per_text'][$i]))),
								 "period" => ucwords(trim(Database::clean_string($_POST['period'][$i]))),
								 "button" => ucwords(trim(Database::clean_string($_POST['button_text'][$i]))),
								 "features" => $features);
		}

        $url = $location_url = '';
        $type = 'biolink';
        $subtype = 'pricingtable';
		$order = 99;
        $settings = json_encode([
			'phone_number' => $_POST['phone_number'],
			'hover_color' => $_POST['background'],
			'pricings' => $new_items
        ]);

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $location_url, $settings, $order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }
	
	private function create_biolink_eshop() {
        $_POST['link_id'] = (int) $_POST['link_id'];
		$new_settings = array();
		$image_allowed = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
		$folder_id = $_POST['link_id'];
		
		if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }
		
		$errors = $sub_variants = null;
		if($_POST) {
			for($i=0;$i<count($_POST['category']);$i++) {
				$item_errors = null;
				for($j=0;$j<count($_POST['title'][$i]);$j++) {
					if(isset($_FILES['image']['size'][$i][$j])) {
						$mime_type = getimagesize($_FILES['image']['tmp_name'][$i][$j]);
						if($_FILES['image']['error'][$i][$j]) {
							$item_errors[] = $this->language->global->error_message->file_upload_empty;
						} elseif($_FILES['image']['size'][$i][$j]>716800) {
							$item_errors[] = $this->language->global->error_message->file_upload_max_size;
						} elseif(!in_array($mime_type['mime'],$image_allowed)) {
							$item_errors[] = $this->language->global->error_message->invalid_file_type;
						}
					} else {
						$item_errors[] = $this->language->global->error_message->file_upload_empty;
					}
					if(isset($_POST['title_variant'][$i][$j])) {
						for($k=0;$k<count($_POST['title_variant'][$i][$j]);$k++) {
							for($l=0;$l<count($_POST['name_variant'][$i][$j][$k]);$l++) {
								if($_FILES['image_variant']['size'][$i][$j][$k][$l]>0) {
									$mime_type = getimagesize($_FILES['image_variant']['tmp_name'][$i][$j][$k][$l]);
									if($_FILES['image_variant']['error'][$i][$j][$k][$l]) {
										$item_errors[] = $this->language->global->error_message->file_upload_empty;
									} elseif($_FILES['image_variant']['size'][$i][$j][$k][$l]>716800) {
										$item_errors[] = $this->language->global->error_message->file_upload_max_size;
									} elseif(!in_array($mime_type['mime'],$image_allowed)) {
										$item_errors[] = $this->language->global->error_message->invalid_file_type;
									}
								}
							}
						}
					}
				}
				if(!empty($item_errors)) {
					$errors['image.'.$i] = $item_errors;
				}
			}
			
			require APP_PATH . 'includes/ResizeImage.php';
			$item_settings = null;
			
			if (!file_exists(UPLOADS_PATH . 'galleries/' . $folder_id)) {
				mkdir(UPLOADS_PATH . 'galleries/' . $folder_id, 0755, true);
			}
			
			for($i=0;$i<count($_POST['category']);$i++) {
				$sub_settings = null;
				for($j=0;$j<count($_POST['title'][$i]);$j++) {
					if($_FILES['image']['size'][$i][$j]>0) {
						$mime_type = getimagesize($_FILES['image']['tmp_name'][$i][$j]);
						$img_ext = 'jpg';
						
						$resize = new \ResizeImage($_FILES['image']['tmp_name'][$i][$j]);
						$resize->resizeTo(800, 800,'maxWidth');
						
						/* Generate new name for logo */
						if($mime_type['mime']=='image/png')
							$img_ext = 'png';
							
						$image_name = md5(time() . rand()) . '.' . $img_ext;

						/* Upload the original */
						$resize->saveImage(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $image_name, '90', $img_ext);
						
						$image_url = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $folder_id . '/' . $image_name;
						
						$variants = null;
						if(isset($_POST['title_variant'][$i][$j])) {
							for($k=0;$k<count($_POST['title_variant'][$i][$j]);$k++) {
								$sub_variants = null;
								$image_name = null;
								$image_url = null;
								for($l=0;$l<count($_POST['name_variant'][$i][$j][$k]);$l++) {
									if(isset($_POST['name_variant'][$i][$j][$k][$l])) {
										$image_name = null;
										$image_url = null;
										if($_FILES['image_variant']['size'][$i][$j][$k][$l]>0) {
											$mime_type = getimagesize($_FILES['image_variant']['tmp_name'][$i][$j][$k][$l]);
											$img_ext = 'jpg';
											
											$resize = new \ResizeImage($_FILES['image_variant']['tmp_name'][$i][$j][$k][$l]);
											$resize->resizeTo(800, 800,'maxWidth');
											
											/* Generate new name for logo */
											if($mime_type['mime']=='image/png')
												$img_ext = 'png';
												
											$image_name = md5(time() . rand()) . '.' . $img_ext;

											/* Upload the original */
											$resize->saveImage(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $image_name, '90', $img_ext);
											
											$image_url = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $folder_id . '/' . $image_name;
											
											if(isset($images[$i]['products'][$j]['variants'][$k][$l]['image_name'])) {
												if(file_exists(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images[$i]['products'][$j]['variants'][$k][$l]['image_name'])) {
													unlink(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images[$i]['products'][$j]['variants'][$k][$l]['image_name']);
												}
											}
										}
										
										$sub_variants[] = array('name' => $_POST['name_variant'][$i][$j][$k][$l],
																'image_name' => $image_name,
																'image_url' => $image_url,
																'price' => $_POST['price_variant'][$i][$j][$k][$l],
																'weight' => $_POST['weight_variant'][$i][$j][$k][$l]);
									}
								}
								if(isset($_POST['title_variant'][$i][$j][$k])) {
									$variants[] = array('title' => $_POST['title_variant'][$i][$j][$k],
														'select' => isset($_POST['select_variant'][$i][$j][$k]) ? 1 : 0,
														'variant' => $sub_variants);
								}
							}
						}
						$sub_settings[] = array("image_name" => $image_name,
												"image_url" => $image_url,
												"title" => ucwords($_POST['title'][$i][$j]),
												"description" => ucfirst($_POST['description'][$i][$j]),
												"price" => (int)$_POST['price'][$i][$j],
												"price_strike" => $_POST['price_strike'][$i][$j] ? (int)$_POST['price_strike'][$i][$j] : null,
												"weight" => $_POST['weight'][$i][$j] ? (int)$_POST['weight'][$i][$j] : 100,
												'variants' => $variants);
					} else {
						$variants = null;
						if(isset($_POST['title_variant'][$i][$j])) {
							for($k=0;$k<count($_POST['title_variant'][$i][$j]);$k++) {
								$sub_variants = null;
								$image_name = null;
								$image_url = null;
								for($l=0;$l<count($_POST['name_variant'][$i][$j][$k]);$l++) {
									if(isset($_POST['name_variant'][$i][$j][$k][$l])) {
										$image_name = null;
										$image_url = null;
										if($_FILES['image_variant']['size'][$i][$j][$k][$l]>0) {
											$mime_type = getimagesize($_FILES['image_variant']['tmp_name'][$i][$j][$k][$l]);
											$img_ext = 'jpg';
											
											$resize = new \ResizeImage($_FILES['image_variant']['tmp_name'][$i][$j][$k][$l]);
											$resize->resizeTo(800, 800,'maxWidth');
											
											/* Generate new name for logo */
											if($mime_type['mime']=='image/png')
												$img_ext = 'png';
												
											$image_name = md5(time() . rand()) . '.' . $img_ext;

											/* Upload the original */
											$resize->saveImage(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $image_name, '90', $img_ext);
											
											$image_url = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $folder_id . '/' . $image_name;
											
											if(isset($images[$i]['products'][$j]['variants'][$k][$l]['image_name'])) {
												if(file_exists(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images[$i]['products'][$j]['variants'][$k][$l]['image_name'])) {
													unlink(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images[$i]['products'][$j]['variants'][$k][$l]['image_name']);
												}
											}
										}
										
										$sub_variants[] = array('name' => ucwords($_POST['name_variant'][$i][$j][$k][$l]),
																'image_name' => $image_name,
																'image_url' => $image_url,
																'price' => $_POST['price_variant'][$i][$j][$k][$l],
																'weight' => $_POST['weight_variant'][$i][$j][$k][$l]);
									}
								}
								if(isset($_POST['title_variant'][$i][$j][$k])) {
									$variants[] = array('title' => ucwords($_POST['title_variant'][$i][$j][$k]),
														'select' => isset($_POST['select_variant'][$i][$j][$k])&&(int)$_POST['select_variant'][$i][$j][$k]==1 ? 1 : 0,
														'variant' => $sub_variants);
								}
							}
						}
						$sub_settings[] = array("image_name" => $images[$i]['products'][$j]['image_name'],
												"image_url" => $images[$i]['products'][$j]['image_url'],
												"title" => ucwords($_POST['title'][$i][$j]),
												"description" => ucfirst($_POST['description'][$i][$j]),
												"price" => (int)$_POST['price'][$i][$j],
												"price_strike" => $_POST['price_strike'][$i][$j] ? (int)$_POST['price_strike'][$i][$j] : null,
												"weight" => $_POST['weight'][$i][$j] ? (int)$_POST['weight'][$i][$j] : 100,
												'variants' => $variants);
					}
				}
				$item_settings[] = array("category" => ucwords($_POST['category'][$i]),
										 "products" => $sub_settings);
			}
			
			$url = $location_url = '';
			$type = 'biolink';
			$subtype = 'eshop';
			$order = 99;
			$settings = json_encode($item_settings);
			
			$stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $this->user->user_id, $type, $subtype, $url, $location_url, $settings, $order, \Altum\Date::$date);
			$stmt->execute();
			$stmt->close();

			/* Clear the cache */
			\Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

			Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
		}
    }
	
	private function create_biolink_export() {
		$_POST['link_id'] = (int) $_POST['link_id'];
		
		if($base_link = Database::get(['url','settings'], 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
			$result = Database::$database->query("SELECT * FROM links WHERE (biolink_id = {$_POST['link_id']} or link_id = {$_POST['link_id']}) and user_id = {$this->user->user_id}");
			$exports = array();
			while($links = $result->fetch_object()) {
				if(!empty($links->location_url))
					$url = string_generate(10);
				else
					$url = $links->url;
				
				$exports[] = array("url" => $url,
								   "location_url" => $links->location_url,
								   "type" => $links->type,
								   "subtype" => $links->subtype,
								   "settings" => $links->settings,
								   "order" => $links->order,
								   "is_enabled" => $links->is_enabled);
			}
			
			header('Content-disposition: attachment; filename='.$base_link->url.'.json');
			header('Content-type: application/json');
			
			echo string_encode(json_encode($exports, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),PRODUCT_ENS_KEY);
		}
	}
	
	private function create_biolink_import() {
		$_POST['link_id'] = (int) $_POST['link_id'];
		
		$json_file_temp = $_FILES['json']['tmp_name'];
		$json_new_name = md5(time() . rand()) . '.json';
		
		move_uploaded_file($json_file_temp, UPLOADS_PATH . $json_new_name);
		
		$string_to_decode = file_get_contents(UPLOADS_PATH . $json_new_name);
		
		if(file_exists(UPLOADS_PATH . $json_new_name))
			unlink(UPLOADS_PATH . $json_new_name);
		
		$string_decoded = string_decode($string_to_decode,PRODUCT_ENS_KEY);
		$json_decoded = json_decode($string_decoded,true);
		if ($json_decoded === null && json_last_error() !== JSON_ERROR_NONE) {
			Response::json($this->language->global->error_message->invalid_json_file, 'error');
		}
		
		if(!$link_base = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) 
			Response::json($this->language->global->error_message->invalid_json_file, 'error');
		
		$domain_id = $link_base->domain_id;
		$project_id = $link_base->project_id;
		$biolink_id = $link_base->link_id;
		
		foreach($json_decoded as $jd) {
			$url = $jd['url'];
			$location_url = $jd['location_url'];
			$type = $jd['type'];
			$subtype = $jd['subtype'];
			$settings = $jd['settings'];
			$order = $jd['order'];
			$is_enabled = $jd['is_enabled'];
			
			if($subtype=='base') {
				if(isset($_POST['overwrite_settings'])) {
					/* Insert to database */
					$stmt = Database::$database->prepare("UPDATE links SET `settings` = ?, `is_enabled` = ? WHERE `link_id` = ?");
					$stmt->bind_param('sss', $settings, $is_enabled, $biolink_id);
					$stmt->execute();
					$stmt->close();
				}
			} else {
				if(isset($_POST['create_new_links'])) {
					/* Generate random url if not specified */
					if(!empty($location_url)) {
						$url = string_generate(10);
						while(Database::exists('link_id', 'links', ['url' => $url, 'domain_id' => $domain_id])) {
							$url = string_generate(10);
						}
					}
					
					/* Insert to database */
					$stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `user_id`, `biolink_id`, `domain_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `is_enabled`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
					$stmt->bind_param('ssssssssssss', $project_id, $this->user->user_id, $biolink_id, $domain_id, $type, $subtype, $url, $location_url,  $settings,  $order,  $is_enabled, \Altum\Date::$date);
					$stmt->execute();
					$stmt->close();
				}
			}
		}
		
		/* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
	}
	
    private function update() {

        if(!empty($_POST)) {
            $_POST['type'] = trim(Database::clean_string($_POST['type']));

            /* Check for possible errors */
            if(!in_array($_POST['type'], ['link', 'biolink'])) {
                die();
            }
            if(!Csrf::check()) {
                Response::json($this->language->global->error_message->invalid_csrf_token, 'error');
            }

            switch($_POST['type']) {
                case 'link':

                    $this->update_link();

                    break;

                case 'biolink':

                    $biolink_link_types = require APP_PATH . 'includes/biolink_link_types.php';

                    /* Check for subtype */
                    if(isset($_POST['subtype']) && in_array($_POST['subtype'], $biolink_link_types)) {
                        $_POST['subtype'] = trim(Database::clean_string($_POST['subtype']));

                        if($_POST['subtype'] == 'link') {
                            $this->update_biolink_link();
                        } else if($_POST['subtype'] == 'mail') {
                            $this->update_biolink_mail();
                        } else if($_POST['subtype'] == 'text') {
                            $this->update_biolink_text();
                        } else if($_POST['subtype'] == 'runningtext') {
                            $this->update_biolink_runningtext();
                        } else if($_POST['subtype'] == 'multitext') {
                            $this->update_biolink_multitext();
                        } else if($_POST['subtype'] == 'html') {
                            $this->update_biolink_html();
                        } else if($_POST['subtype'] == 'picture') {
                            $this->update_biolink_picture();
                        } else if($_POST['subtype'] == 'banner') {
                            $this->update_biolink_picture('banner');
                        } else if($_POST['subtype'] == 'sliders') {
                            $this->update_biolink_picture('sliders');
                        } else if($_POST['subtype'] == 'waform') {
                            $this->update_biolink_waform();
                        } else if($_POST['subtype'] == 'cartform') {
                            $this->update_biolink_cartform();
                        } else if($_POST['subtype'] == 'domain') {
                            $this->update_biolink_domain();
                        } else if($_POST['subtype'] == 'googlemap') {
                            $this->update_biolink_googlemap();
                        } else if($_POST['subtype'] == 'countdown') {
                            $this->update_biolink_countdown();
                        } else if($_POST['subtype'] == 'floatbutton') {
                            $this->update_biolink_floatbutton();
                        } else if($_POST['subtype'] == 'pricingtable') {
                            $this->update_biolink_pricingtable();
                        } else if($_POST['subtype'] == 'eshop') {
                            $this->update_biolink_eshop();
                        } else {
                            $this->update_biolink_other($_POST['subtype']);
                        }


                    } else {
                        /* Base biolink */
                        $this->update_biolink();
                    }

                    break;
            }

        }

        die();
    }

    private function update_biolink() {
        $image_allowed_extensions = ['jpg', 'jpeg', 'png', 'svg', 'ico'];
        $image = (bool) !empty($_FILES['image']['name']);
        $image_delete = isset($_POST['image_delete']) && $_POST['image_delete'] == 'true';
        $_POST['title'] = Database::clean_string($_POST['title']);
        $_POST['description'] = Database::clean_string($_POST['description']);
        $_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;

        /* Check if custom domain is set */
        $domain_id = $this->get_domain_id($_POST['domain_id'] ?? false);

        /* Check for any errors */
        if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }

        $link->settings = json_decode($link->settings);

        /* Check for any errors on the logo image */
        if($image) {
            $image_file_extension = explode('.', $_FILES['image']['name']);
            $image_file_extension = strtolower(end($image_file_extension));
            $image_file_temp = $_FILES['image']['tmp_name'];

            if($_FILES['image']['error']) {
                Response::json($this->language->global->error_message->file_upload, 'error');
            }

            if(!in_array($image_file_extension, $image_allowed_extensions)) {
                Response::json($this->language->global->error_message->invalid_file_type, 'error');
            }
        }

        if($_POST['url'] == $link->url) {
            $url = $link->url;

            if($link->domain_id != $domain_id) {
                if(Database::exists('link_id', 'links', ['url' => $_POST['url'], 'domain_id' => $domain_id])) {
                    Response::json($this->language->create_biolink_modal->error_message->url_exists, 'error');
                }
            }

        } else {
            $url = $_POST['url'] ? $_POST['url'] : string_generate(10);

            if(Database::exists('link_id', 'links', ['url' => $_POST['url'], 'domain_id' => $domain_id])) {
                Response::json($this->language->create_biolink_modal->error_message->url_exists, 'error');
            }

            /* Generate random url if not specified */
            while(Database::exists('link_id', 'links', ['url' => $url, 'domain_id' => $domain_id])) {
                $url = string_generate(10);
            }

            $this->check_url($_POST['url']);
        }

        /* Update the avatar of the profile if needed */
        if($image && !$image_delete) {

            /* Delete current image */
            if(!empty($link->settings->image) && file_exists(UPLOADS_PATH . 'avatars/' . $link->settings->image)) {
                unlink(UPLOADS_PATH . 'avatars/' . $link->settings->image);
            }

            /* Generate new name for logo */
            $image_new_name = md5(time() . rand()) . '.' . $image_file_extension;
			$img_type = getimagesize($image_file_temp);
			$resize = '125x';
			if($img_type[1]<$img_type[0]) $resize = 'x125';
			
			//if(function_exists('shell_exec')) {
			//	shell_exec('F:\xampp\htdocs\biosmart\magick\convert ' . $image_file_temp . ' -layers Coalesce -resize ' . $resize . ' -gravity Center -crop 125x125+0+0 ' . UPLOADS_PATH . 'avatars/' . $image_new_name);
			//} else {
			/* Upload the original */
			move_uploaded_file($image_file_temp, UPLOADS_PATH . 'avatars/' . $image_new_name);
			//}

        }

        /* Delete avatar */
        if($image_delete) {
            /* Delete current image */
            if(!empty($link->settings->image) && file_exists(UPLOADS_PATH . 'avatars/' . $link->settings->image)) {
                unlink(UPLOADS_PATH . 'avatars/' . $link->settings->image);
            }
        }

        $_POST['text_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['text_color']) ? '#fff' : $_POST['text_color'];
        $_POST['socials_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['socials_color']) ? '#fff' : $_POST['socials_color'];
        $biolink_backgrounds = require APP_PATH . 'includes/biolink_backgrounds.php';
        $_POST['page_transition_type'] = (int)$_POST['page_transition_type'];
		if($_POST['page_transition_type']<0 || $_POST['page_transition_type']>67) $_POST['page_transition_type'] = 0;
		$_POST['background_type'] = array_key_exists($_POST['background_type'], $biolink_backgrounds) ? $_POST['background_type'] : 'preset';
        $background = 'one';

        switch($_POST['background_type']) {
            case 'preset':
                $background = in_array($_POST['background'], $biolink_backgrounds['preset']) ? $_POST['background'] : 'one';
                break;
				
			case 'anigradient':

                $color_one = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background'][0]) ? '#000' : $_POST['background'][0];
                $color_two = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background'][1]) ? '#000' : $_POST['background'][1];
				$color_three = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background'][2]) ? '#000' : $_POST['background'][2];
				$color_four = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background'][3]) ? '#000' : $_POST['background'][3];

                $background = [
                    'color_one' => $color_one,
                    'color_two' => $color_two,
					'color_three' => $color_three,
					'color_four' => $color_four
                ];

                break;
				
            case 'color':

                $background = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background']) ? '#000' : $_POST['background'];

                break;

            case 'gradient':

                $color_one = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background'][0]) ? '#000' : $_POST['background'][0];
                $color_two = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background'][1]) ? '#000' : $_POST['background'][1];

                $background = [
                    'color_one' => $color_one,
                    'color_two' => $color_two
                ];

                break;

            case 'image':

                $background = (bool) !empty($_FILES['background']['name']);
				$image_bg_allowed_extensions = ['jpg', 'gif', 'jpeg', 'png', 'svg'];
				
                /* Check for any errors on the logo image */
                if($background) {
                    $background_file_extension = explode('.', $_FILES['background']['name']);
                    $background_file_extension = strtolower(end($background_file_extension));
                    $background_file_temp = $_FILES['background']['tmp_name'];

                    if($_FILES['background']['error']) {
                        Response::json($this->language->global->error_message->file_upload, 'error');
                    }

                    if(!in_array($background_file_extension, $image_bg_allowed_extensions)) {
                        Response::json($this->language->global->error_message->invalid_file_type, 'error');
                    }

                    /* Delete current image */
                    if(!is_object($link->settings->background) && file_exists(UPLOADS_PATH . 'backgrounds/' . $link->settings->background)) {
                        unlink(UPLOADS_PATH . 'backgrounds/' . $link->settings->background);
                    }

                    /* Generate new name for logo */
                    $background_new_name = md5(time() . rand()) . '.' . $background_file_extension;

                    /* Upload the original */
                    move_uploaded_file($background_file_temp, UPLOADS_PATH . 'backgrounds/' . $background_new_name);

                    $background = $background_new_name;
                }

                break;
        }

        $_POST['display_branding'] = (bool) isset($_POST['display_branding']);
        $_POST['display_verified'] = (bool) isset($_POST['display_verified']);
		$_POST['password_protector'] = (bool) isset($_POST['password_protector']);
		$_POST['password_lock'] = Database::clean_string($_POST['password_lock']);
        $_POST['branding_name'] = Database::clean_string($_POST['branding_name']);
        $_POST['branding_url'] = Database::clean_string($_POST['branding_url']);
        $_POST['google_analytics'] = Database::clean_string($_POST['google_analytics']);
        $_POST['facebook_pixel'] = Database::clean_string($_POST['facebook_pixel']);
        $_POST['seo_title'] = Database::clean_string(mb_substr($_POST['seo_title'], 0, 70));
        $_POST['seo_meta_description'] = Database::clean_string(mb_substr($_POST['seo_meta_description'], 0, 160));
        $_POST['utm_medium'] = Database::clean_string($_POST['utm_medium']);
        $_POST['utm_source'] = Database::clean_string($_POST['utm_source']);

        /* Make sure the socials sent are proper */
        $biolink_socials = require APP_PATH . 'includes/biolink_socials.php';

        foreach($_POST['socials'] as $key => $value) {

            if(!array_key_exists($key, $biolink_socials)) {
                unset($_POST['socials'][$key]);
            } else {
                $_POST['socials'][$key] = Database::clean_string($_POST['socials'][$key]);
            }

        }

        /* Make sure the font is ok */
        $biolink_fonts = require APP_PATH . 'includes/biolink_fonts.php';
        $_POST['font'] = !array_key_exists($_POST['font'], $biolink_fonts) ? false : Database::clean_string($_POST['font']);

        /* Set the new settings variable */
        $settings = json_encode([
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'display_verified' => $_POST['display_verified'],
			'display_verified' => $_POST['display_verified'],
			'password_protector' => !empty($_POST['password_lock']) ? $_POST['password_protector'] : false,
			'password_lock' => $_POST['password_protector'] ? $_POST['password_lock'] : '',
            'image' => $image_delete ? '' : ($image ? $image_new_name : $link->settings->image),
            'page_transition_type' => $_POST['page_transition_type'],
			'background_type' => $_POST['background_type'],
            'background' => $background ? $background : $link->settings->background,
            'text_color' => $_POST['text_color'],
            'socials_color' => $_POST['socials_color'],
            'google_analytics' => $_POST['google_analytics'],
            'facebook_pixel' => $_POST['facebook_pixel'],
            'display_branding' => $_POST['display_branding'],
            'branding' => [
                'name' => $_POST['branding_name'],
                'url' => $_POST['branding_url'],
            ],
            'seo' => [
                'title' => $_POST['seo_title'],
                'meta_description' => $_POST['seo_meta_description'],
            ],
            'utm' => [
                'medium' => $_POST['utm_medium'],
                'source' => $_POST['utm_source'],
            ],
            'socials' => $_POST['socials'],
            'font' => $_POST['font']
        ]);

        /* Update the record */
        $stmt = Database::$database->prepare("UPDATE `links` SET `domain_id` = ?, `url` = ?, `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ssss', $domain_id, $url, $settings, $link->link_id);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');

    }

    private function update_biolink_link() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = trim(Database::clean_string($_POST['location_url']));
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;
        $_POST['outline'] = (bool) isset($_POST['outline']);
        $_POST['border_radius'] = in_array($_POST['border_radius'], ['straight', 'round', 'rounded']) ? Database::clean_string($_POST['border_radius']) : 'rounded';
        $_POST['animation'] = in_array($_POST['animation'], ['false', 'bounce', 'tada', 'wobble', 'swing', 'shake', 'rubberBand', 'pulse', 'flash']) ? Database::clean_string($_POST['animation']) : false;
        $_POST['icon'] = trim(Database::clean_string($_POST['icon']));
        $_POST['text_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['text_color']) ? '#000' : $_POST['text_color'];
        $_POST['background_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background_color']) ? '#fff' : $_POST['background_color'];
        if(isset($_POST['schedule']) && !empty($_POST['start_date']) && !empty($_POST['end_date']) && Date::validate($_POST['start_date'], 'Y-m-d H:i:s') && Date::validate($_POST['end_date'], 'Y-m-d H:i:s')) {
            $_POST['start_date'] = (new \DateTime($_POST['start_date'], new \DateTimeZone($this->user->timezone)))->setTimezone(new \DateTimeZone(\Altum\Date::$default_timezone))->format('Y-m-d H:i:s');
            $_POST['end_date'] = (new \DateTime($_POST['end_date'], new \DateTimeZone($this->user->timezone)))->setTimezone(new \DateTimeZone(\Altum\Date::$default_timezone))->format('Y-m-d H:i:s');
        } else {
            $_POST['start_date'] = $_POST['end_date'] = null;
        }

        /* Check for any errors */
        $fields = ['location_url', 'name'];

        /* Check for any errors */
        foreach($_POST as $key => $value) {
            if(empty($value) && in_array($key, $fields) == true) {
                Response::json($this->language->global->error_message->empty_fields, 'error');
                break 1;
            }
        }

        $this->check_url($_POST['url']);

        $this->check_location_url($_POST['location_url']);

        if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }

        /* Check for duplicate url if needed */
        if($_POST['url'] && $_POST['url'] != $link->url) {
            if(Database::exists('link_id', 'links', ['url' => $_POST['url']])) {
                Response::json($this->language->create_biolink_link_modal->error_message->url_exists, 'error');
            }
        } else if(!$_POST['url']) {
            $_POST['url'] = string_generate(10);

            /* Generate random url if not specified */
            while(Database::exists('link_id', 'links', ['url' => $_POST['url']])) {
                $_POST['url'] = string_generate(10);
            }

            $this->check_url($_POST['url']);
        }

        $settings = json_encode([
            'name' => $_POST['name'],
            'text_color' => $_POST['text_color'],
            'background_color' => $_POST['background_color'],
            'outline' => $_POST['outline'],
            'border_radius' => $_POST['border_radius'],
            'animation' => $_POST['animation'],
            'icon' => $_POST['icon']
        ]);

        $stmt = Database::$database->prepare("UPDATE `links` SET `url` = ?, `location_url` = ?, `settings` = ?, `start_date` = ?, `end_date` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ssssss', $_POST['url'], $_POST['location_url'], $settings, $_POST['start_date'], $_POST['end_date'], $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }

    private function update_biolink_domain_old() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = trim(Database::clean_string($_POST['location_url']));
        $_POST['type_height'] = (int) $_POST['type_height']; 
        $_POST['height'] = (int) $_POST['height'].'px';
        $_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;
        
        if($_POST['type_height'] == 0) {
            $_POST['height'] = 'auto';
        } 

        /* Check for any errors */
        $fields = ['location_url','height'];

        /* Check for any errors */
        foreach($_POST as $key => $value) {
            if(empty($value) && in_array($key, $fields) == true) {
                Response::json($this->language->global->error_message->empty_fields, 'error');
                break 1;
            }
        }

        $this->check_url($_POST['url']);

        $this->check_location_url($_POST['location_url']);

        if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }

        /* Check for duplicate url if needed */
        if($_POST['url'] && $_POST['url'] != $link->url) {
            if(Database::exists('link_id', 'links', ['url' => $_POST['url']])) {
                Response::json($this->language->create_biolink_link_modal->error_message->url_exists, 'error');
            }
        } else if(!$_POST['url']) {
            $_POST['url'] = string_generate(10);

            /* Generate random url if not specified */
            while(Database::exists('link_id', 'links', ['url' => $_POST['url']])) {
                $_POST['url'] = string_generate(10);
            }

            $this->check_url($_POST['url']);
        }

        $settings = json_encode([
            'height' => $_POST['height']
        ]);

        $stmt = Database::$database->prepare("UPDATE `links` SET `url` = ?, `location_url` = ?, `settings` = ?, `start_date` = ?, `end_date` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ssssss', $_POST['url'], $_POST['location_url'], $settings, $_POST['start_date'], $_POST['end_date'], $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }

    private function update_biolink_other($subtype) {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = trim(Database::clean_string($_POST['location_url']));

        $this->check_location_url($_POST['location_url']);

        if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }
		
		if($subtype=='soundcloud')
			$settings = json_encode(['autoplay' => isset($_POST['autoplay']) ? true : false]);
		else
			$settings = null;

        $stmt = Database::$database->prepare("UPDATE `links` SET `location_url` = ?, `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('sss', $_POST['location_url'], $settings, $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }

    private function update_biolink_mail() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;
        $_POST['outline'] = (bool) isset($_POST['outline']);
        $_POST['border_radius'] = in_array($_POST['border_radius'], ['straight', 'round', 'rounded']) ? Database::clean_string($_POST['border_radius']) : 'rounded';
        $_POST['animation'] = in_array($_POST['animation'], ['false', 'bounce', 'tada', 'wobble', 'swing', 'shake', 'rubberBand', 'pulse', 'flash']) ? Database::clean_string($_POST['animation']) : false;
        $_POST['icon'] = trim(Database::clean_string($_POST['icon']));
        $_POST['text_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['text_color']) ? '#000' : $_POST['text_color'];
        $_POST['background_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background_color']) ? '#fff' : $_POST['background_color'];

        $_POST['email_placeholder'] = trim(Database::clean_string($_POST['email_placeholder']));
        $_POST['button_text'] = trim(Database::clean_string($_POST['button_text']));
        $_POST['success_text'] = trim(Database::clean_string($_POST['success_text']));
        $_POST['show_agreement'] = (bool) isset($_POST['show_agreement']);
        $_POST['agreement_url'] = trim(Database::clean_string($_POST['agreement_url']));
        $_POST['agreement_text'] = trim(Database::clean_string($_POST['agreement_text']));
        $_POST['mailchimp_api'] = trim(Database::clean_string($_POST['mailchimp_api']));
        $_POST['mailchimp_api_list'] = trim(Database::clean_string($_POST['mailchimp_api_list']));
        $_POST['webhook_url'] = trim(Database::clean_string($_POST['webhook_url']));

        if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }

        $settings = json_encode([
            'name' => $_POST['name'],
            'text_color' => $_POST['text_color'],
            'background_color' => $_POST['background_color'],
            'outline' => $_POST['outline'],
            'border_radius' => $_POST['border_radius'],
            'animation' => $_POST['animation'],
            'icon' => $_POST['icon'],

            'email_placeholder' => $_POST['email_placeholder'],
            'button_text' => $_POST['button_text'],
            'success_text' => $_POST['success_text'],
            'show_agreement' => $_POST['show_agreement'],
            'agreement_url' => $_POST['agreement_url'],
            'agreement_text' => $_POST['agreement_text'],
            'mailchimp_api' => $_POST['mailchimp_api'],
            'mailchimp_api_list' => $_POST['mailchimp_api_list'],
            'webhook_url' => $_POST['webhook_url']
        ]);

        $stmt = Database::$database->prepare("UPDATE `links` SET `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ss', $settings, $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }

    private function update_biolink_text() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['title'] = trim(Database::clean_string($_POST['title']));
        $_POST['title_text_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['title_text_color']) ? '#fff' : $_POST['title_text_color'];
        $_POST['description_text_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['description_text_color']) ? '#fff' : $_POST['description_text_color'];

        if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }

        $settings = json_encode([
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'title_text_color' => $_POST['title_text_color'],
            'description_text_color' => $_POST['description_text_color'],
        ]);

        $stmt = Database::$database->prepare("UPDATE `links` SET `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ss', $settings, $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }
	
	private function update_biolink_html() {
        $_POST['link_id'] = (int) $_POST['link_id'];
		$_POST['description'] = trim($_POST['description']);
		//$_POST['description'] = str_replace("\r\n","",$_POST['description']);
		
		//require APP_PATH . 'helpers/HTMLPurifier/HTMLPurifier.standalone.php';
		//$config = \HTMLPurifier_Config::createDefault();
		// Allow Text without tag e.g P or DIV (plain text, obviously necessary for markdown)
		//$config->set('Core.Encoding', 'UTF-8');
		//$config->set('HTML.TidyLevel', 'light' );
		
		//$config->set('HTML.Trusted', true);
        //$config->set('HTML.SafeObject', true);
        //$config->set('Output.FlashCompat', true);
		//$config->set('HTML.SafeIframe', true);
		//$config->set('URI.SafeIframeRegexp', '%^https://(www.youtube.com/embed/|player.vimeo.com/video/)%');

		// Define manually which elements can be rendered
		// In this example, we allow (almost) all the basic elements that are converted with markdown
		//$config->set('HTML.Allowed', 'script,iframe,h1,h2,h3,h4,h5,h6,br,b,i,strong,em,a,pre,code,img,tt,div,ins,del,sup,sub,p,ol,ul,table,thead,tbody,tfoot,blockquote,dl,dt,dd,kbd,q,samp,var,hr,li,tr,td,th,s,strike,br');
		//$config->set('Attr.AllowedFrameTargets', array('_blank'));
		// The attributes are up to you
		//$config->set('HTML.AllowedAttributes', 'iframe.width,iframe.height,iframe.frameborder,iframe.allowfullscreen,*.id,*.class,form.action,*.src,*.style,*.class, code.class,a.href,*.target');
		
		//$def = $config->getHTMLDefinition(true);
		//$def->addAttribute('iframe', 'allowfullscreen', 'Bool');
		//$def->addAttribute('script', 'async', 'Bool#async');
		
		// Create an instance of the purifier with the configuration
		//$purifier = new \HTMLPurifier($config);
		//$_POST['description'] = $purifier->purify(trim($_POST['description']));
        
        if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }

        $settings = json_encode([
            'description' => trim($_POST['description']),
            'description_text_color' => '#fff',
        ]);
		
        $stmt = Database::$database->prepare("UPDATE `links` SET `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ss', $settings, $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }

    private function update_biolink_runningtext() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['description_runningtext_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['description_text_color']) ? '#fff' : $_POST['description_text_color'];
        $_POST['scrollamount'] = (int) $_POST['scrollamount'];
        
        if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }

        $settings = json_encode([
            'description' => $_POST['description'],
            'description_text_color' => $_POST['description_runningtext_color'],
            'scrollamount' => $_POST['scrollamount'],
        ]);

        $stmt = Database::$database->prepare("UPDATE `links` SET `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ss', $settings, $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }
	
	 private function update_biolink_picture($subtype='picture') {
        $_POST['link_id'] = (int) $_POST['link_id'];
		$image_allowed = ['image/png', 'image/jpeg', 'image/gif'];
		
		if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
			die();
		}
		
		$images = json_decode($link->settings);
		$folder_id = $link->biolink_id;
		
		if($subtype=='picture') {
			$picture_name = $images->picture_name;
			
			if($_FILES['image']['size']>0) {
				$mime_type = getimagesize($_FILES['image']['tmp_name']);
				$img_ext = 'jpg';
				
				if($_FILES['image']['error']) {
					Response::json($this->language->global->error_message->file_upload, 'error');
				}
				if(!in_array($mime_type['mime'],$image_allowed)) {
					Response::json($this->language->global->error_message->invalid_file_type, 'error');
				}
				
				if (file_exists(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $picture_name)) {
					unlink(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $picture_name);
				}
				
				if($mime_type['mime']!='image/gif') {
					require APP_PATH . 'includes/ResizeImage.php';
					$resize = new \ResizeImage($_FILES['image']['tmp_name']);
					$resize->resizeTo(800, 800, 'maxWidth');
					
					if (!file_exists(UPLOADS_PATH . 'galleries/' . $folder_id)) {
						mkdir(UPLOADS_PATH . 'galleries/' . $folder_id, 0755, true);
					}
					
					/* Generate new name for the image */
					if($mime_type['mime']=='image/png')
						$img_ext = 'png';
					
					$picture_name = md5(time() . rand()) . '.' . $img_ext;
					
					/* Upload the image */
					$resize->saveImage(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $picture_name, '90', $img_ext);
				} else {
					$img_ext = 'gif';
					$picture_name = md5(time() . rand()) . '.' . $img_ext;
					
					if (!file_exists(UPLOADS_PATH . 'galleries/' . $folder_id)) {
						mkdir(UPLOADS_PATH . 'galleries/' . $folder_id, 0755, true);
					}
					
					move_uploaded_file($_FILES['image']['tmp_name'], UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $picture_name);
				}
			}
			$picture_url = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $folder_id . '/' . $picture_name;
		} elseif($subtype=='banner') {
			$_POST['picture_url'] = trim(Database::clean_string($_POST['picture_url']));
			$_POST['target_url'] = trim(Database::clean_string($_POST['target_url']));
		} elseif($subtype=='sliders') {
			$images_list = null;
			$num_list = $num_item = 0;
			foreach($images->images as $im) {
				$images_list[] = $num_list;
				$num_list++;
			}
			$errors = null;
			for($i=0;$i<count($_FILES['images']['tmp_name']);$i++) {
				$item_errors = null;
				if($_FILES['images']['size'][$i]>0) {
					if(in_array($i,$images_list)) {
						if(file_exists(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images->images[$i]->image_name)) {
							unlink(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images->images[$i]->image_name);
						}
					}
					$mime_type = getimagesize($_FILES['images']['tmp_name'][$i]);
					if($_FILES['images']['error'][$i]) {
						$item_errors[] = $this->language->global->error_message->file_upload_empty;
					} elseif($_FILES['images']['size'][$i]>716800) {
						$item_errors[] = $this->language->global->error_message->file_upload_max_size;
					} elseif(!in_array($mime_type['mime'],$image_allowed)) {
						$item_errors[] = $this->language->global->error_message->invalid_file_type;
					}
				} elseif(in_array($i,$images_list)) {
				} else {
					$item_errors[] = $this->language->global->error_message->file_upload_empty;
				}
				if(!empty($item_errors)) {
					$errors['images.'.$i] = $item_errors;
				}
				$num_item++;
			}
			if($num_item<count($images_list)) {
				for($j=$num_item;$j<count($images_list);$j++) {
					if(file_exists(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images->images[$j]->image_name)) {
						unlink(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images->images[$j]->image_name);
					}
				}
			}
			
			if(!empty($errors)) {
				Response::json($errors, 'error','form');
				die();
			}
			
			require APP_PATH . 'includes/ResizeImage.php';
			$item_settings = null;
			
			for($i=0;$i<count($_FILES['images']['tmp_name']);$i++) {
				$image_name = isset($images->images[$i]->image_name) ? $images->images[$i]->image_name : null;
				$image_url = isset($images->images[$i]->image_url) ? $images->images[$i]->image_url : null;
				if($_FILES['images']['size'][$i]>0) {
					$mime_type = getimagesize($_FILES['images']['tmp_name'][$i]);
					$img_ext = 'jpg';
					
					$resize = new \ResizeImage($_FILES['images']['tmp_name'][$i]);
					$resize->resizeTo(800, 800, 'maxWidth');
					
					/* Generate new name for logo */
					if($mime_type['mime']=='image/png')
						$img_ext = 'png';
					
					$image_name = md5(time() . rand()) . '.' . $img_ext;

					/* Upload the original */
					$resize->saveImage(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $image_name, '90', $img_ext);
					
					$image_url = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $folder_id . '/' . $image_name;
				}
				$item_settings[] = array("image_name" => $image_name,
										 "image_url" => $image_url);
			}
			$_POST['slider_animation'] = trim(Database::clean_string($_POST['slider_animation']));
			$_POST['slider_timer'] = trim(Database::clean_string($_POST['slider_timer']));
		} else {
			$_POST['picture_url'] = trim(Database::clean_string($_POST['picture_url']));
		}
		
		if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }
		
		if($subtype=='picture') {
			$settings = json_encode([
				'picture_name' => $picture_name,
				'picture_url' => $picture_url,
				'link_url' => filter_var($_POST['link_url'], FILTER_VALIDATE_URL) ? trim($_POST['link_url']) : '',
			]);
		} elseif($subtype=='banner') {
			$settings = json_encode([
				'picture_url' => $_POST['picture_url'],
				'target_url' => $_POST['target_url'],
			]);
		} elseif($subtype=='sliders') {
			$settings = json_encode([
				'images' => $item_settings,
				'slider_animation' => $_POST['slider_animation'],
				'slider_timer' => $_POST['slider_timer'],
			]);
		} else {
			$settings = json_encode([
				'picture_url' => $_POST['picture_url'],
			]);
		}

        $stmt = Database::$database->prepare("UPDATE `links` SET `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ss', $settings, $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }
	
	private function update_biolink_waform() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['wa_number'] = trim(Database::clean_string($_POST['wa_number']));
		$_POST['wa_message'] = trim(Database::clean_string($_POST['wa_message']));
        $_POST['text_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['text_color']) ? '#000' : $_POST['text_color'];
        
        if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }
		
		if(!isset($_POST['name'])) $_POST['name'] = 'WA Form';
		if(!isset($_POST['outline'])) $_POST['outline'] = false;
        $settings = json_encode([
            'wa_number' => $_POST['wa_number'],
			'wa_message' => $_POST['wa_message'],
			'name' => $_POST['name'],
            'text_color' => $_POST['text_color'],
            'background_color' => $_POST['background_color'],
            'outline' => $_POST['outline'],
            'border_radius' => $_POST['border_radius'],
            'animation' => $_POST['animation'],
            'icon' => $_POST['icon'],
        ]);

        $stmt = Database::$database->prepare("UPDATE `links` SET `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ss', $settings, $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }
	
	private function update_biolink_cartform() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['wa_number'] = trim(Database::clean_string($_POST['wa_number']));
		$_POST['wa_message'] = trim(Database::clean_string($_POST['wa_message']));
        $_POST['text_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['text_color']) ? '#000' : $_POST['text_color'];
        
		$image_allowed = ['image/png', 'image/jpeg', 'image/gif'];
		
        if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }
		
		$images = json_decode($link->settings);
		$photo_name = $images->photo_name;
		$folder_id = $link->biolink_id;
		
		if($_FILES['image']['size']>0) {
			$mime_type = getimagesize($_FILES['image']['tmp_name']);
			$img_ext = 'jpg';
			
			if($_FILES['image']['error']) {
				Response::json($this->language->global->error_message->file_upload, 'error');
			}
			if(!in_array($mime_type['mime'],$image_allowed)) {
				Response::json($this->language->global->error_message->invalid_file_type, 'error');
			}
			
			if (file_exists(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $photo_name)) {
				unlink(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $photo_name);
			}
			
			require APP_PATH . 'includes/ResizeImage.php';
			$resize = new \ResizeImage($_FILES['image']['tmp_name']);
			$resize->resizeTo(800, 800, 'maxWidth');
			
			if (!file_exists(UPLOADS_PATH . 'galleries/' . $folder_id)) {
				mkdir(UPLOADS_PATH . 'galleries/' . $folder_id, 0755, true);
			}
			
			/* Generate new name for the image */
			if($mime_type['mime']=='image/png')
				$img_ext = 'png';
			
			$photo_name = md5(time() . rand()) . '.' . $img_ext;

			/* Upload the image */
			$resize->saveImage(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $photo_name, '90' , $img_ext);
		}
		$photo_url = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $folder_id . '/' . $photo_name;
		
		if(!isset($_POST['name'])) $_POST['name'] = 'Add to Cart';
		if(!isset($_POST['outline'])) $_POST['outline'] = false;
		if(!isset($_POST['schedule'])) $_POST['schedule'] = false;
        $settings = json_encode([
			'title' => $_POST['title'],
			'photo' => $photo_url,
			'photo_name' => $photo_name,
			'description' => $_POST['description'],
			'price' => $_POST['price'],
			'min_qty' => $_POST['min_qty'],
			'max_qty' => $_POST['max_qty'],
            'wa_number' => $_POST['wa_number'],
			'wa_message' => $_POST['wa_message'],
			'schedule' => $_POST['schedule'],
			'start_date' => $_POST['start_date'],
			'end_date' => $_POST['end_date'],
			'name' => $_POST['name'],
            'text_color' => $_POST['text_color'],
            'background_color' => $_POST['background_color'],
            'outline' => $_POST['outline'],
            'border_radius' => $_POST['border_radius'],
            'animation' => $_POST['animation'],
            'icon' => $_POST['icon'],
        ]);

        $stmt = Database::$database->prepare("UPDATE `links` SET `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ss', $settings, $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }
	
	private function update_biolink_googlemap() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['coordinates'] = trim(Database::clean_string($_POST['coordinates']));
        
		if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }

        $settings = json_encode([
            'coordinates' => $_POST['coordinates'],
        ]);

        $stmt = Database::$database->prepare("UPDATE `links` SET `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ss', $settings, $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }
	
	private function update_biolink_countdown() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['title'] = trim(Database::clean_string($_POST['title']));
		$_POST['end_date'] = trim(Database::clean_string($_POST['end_date']));
        
		if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }

        $settings = json_encode([
            'title' => $_POST['title'],
			'end_date' => $_POST['end_date'],
			'days' => ucwords($_POST['days']),
			'hours' => ucwords($_POST['hours']),
			'minutes' => ucwords($_POST['minutes']),
			'seconds' => ucwords($_POST['seconds'])
        ]);

        $stmt = Database::$database->prepare("UPDATE `links` SET `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ss', $settings, $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }
    
    private function update_biolink_domain() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = trim(Database::clean_string($_POST['location_url']));
		$_POST['type_height'] = (int) $_POST['type_height'];
		$_POST['height'] = (int) $_POST['height'];
		
		if(empty($_POST['height'])) $_POST['height'] = 300;
		
		if($_POST['type_height']<0 || $_POST['type_height']>2) $_POST['type_height'] = 0;
		if($_POST['type_height']==0 || $_POST['type_height']==2)
			$_POST['height'] = 'auto';
        
		if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }

        $settings = json_encode([
            'location_url' => $_POST['location_url'],
			'type_height' => $_POST['type_height'],
			'height' => $_POST['height'],
        ]);

        $stmt = Database::$database->prepare("UPDATE `links` SET `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ss', $settings, $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }
	
	private function update_biolink_floatbutton() {
        $_POST['link_id'] = (int) $_POST['link_id'];
		$new_settings = array();
		
		for($i=0;$i<count($_POST['icon']);$i++) {
			$_POST['icon'][$i] = trim(Database::clean_string($_POST['icon'][$i]));
			$_POST['link_url'][$i] = trim(Database::clean_string($_POST['link_url'][$i]));
			$_POST['link_title'][$i] = trim(Database::clean_string($_POST['link_title'][$i]));
			$new_settings[] = array('icon' => $_POST['icon'][$i],
									'background_color' => !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background'][$i]) ? '#fd4235' : $_POST['background'][$i],
									'text_color' => !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['text'][$i]) ? '#ffffff' : $_POST['text'][$i],
									'link_url' => $_POST['link_url'][$i],
									'link_title' => $_POST['link_title'][$i]);
		}
        
		if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }

        $settings = json_encode([
								'background_color' => '#fd4235',
								'text_color' => '#fff',
								'position' => trim(Database::clean_string($_POST['position'])),
								'configs' => $new_settings
								]);

        $stmt = Database::$database->prepare("UPDATE `links` SET `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ss', $settings, $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }
	
	private function update_biolink_pricingtable() {
        $_POST['link_id'] = (int) $_POST['link_id'];
		$_POST['phone_number'] = (int) $_POST['phone_number'];
        
		if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }
		
		$new_items = array();
		
		for($i=0;$i<count($_POST['name']);$i++) {
			$features = array();
			
			for($j=0;$j<count($_POST['feat_bold'][$i]);$j++) {
				$features[] = array("bold" => ucwords(trim(Database::clean_string($_POST['feat_bold'][$i][$j]))),
									"normal" => ucwords(trim(Database::clean_string($_POST['feat_normal'][$i][$j]))));
 			}
			
			$new_items[] = array("name" => ucwords(trim(Database::clean_string($_POST['name'][$i]))),
								 "currency" => ucwords($_POST['currency'][$i]),
								 "price" => floatval($_POST['price'][$i]),
								 "price_strike" => floatval($_POST['price_strike'][$i]),
								 "per" => ucwords(trim(Database::clean_string($_POST['per_text'][$i]))),
								 "period" => ucwords(trim(Database::clean_string($_POST['period'][$i]))),
								 "button" => ucwords(trim(Database::clean_string($_POST['button_text'][$i]))),
								 "features" => $features);
		}

        $settings = json_encode([
			'phone_number' => $_POST['phone_number'],
			'hover_color' => $_POST['background'],
			'pricings' => $new_items
        ]);

        $stmt = Database::$database->prepare("UPDATE `links` SET `settings` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ss', $settings, $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }
	
	private function update_biolink_eshop() {
        $_POST['link_id'] = (int) $_POST['link_id'];
		$new_settings = array();
		$image_allowed = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
		$folder_id = $_POST['link_id'];
		
		if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }
		
		$errors = null;
		if($_POST) {
			for($i=0;$i<count($_POST['category']);$i++) {
				$item_errors = null;
				for($j=0;$j<count($_POST['title'][$i]);$j++) {
					if($_FILES['image']['size'][$i][$j]>0) {
						$mime_type = getimagesize($_FILES['image']['tmp_name'][$i][$j]);
						if($_FILES['image']['error'][$i][$j]) {
							$item_errors[] = $this->language->global->error_message->file_upload_empty;
						} elseif($_FILES['image']['size'][$i][$j]>716800) {
							$item_errors[] = $this->language->global->error_message->file_upload_max_size;
						} elseif(!in_array($mime_type['mime'],$image_allowed)) {
							$item_errors[] = $this->language->global->error_message->invalid_file_type;
						}
					} else {
						//$item_errors[] = $this->language->global->error_message->file_upload_empty;
					}
					if(isset($_POST['title_variant'][$i][$j])) {
						for($k=0;$k<count($_POST['title_variant'][$i][$j]);$k++) {
							for($l=0;$l<count($_POST['name_variant'][$i][$j][$k]);$l++) {
								if($_FILES['image_variant']['size'][$i][$j][$k][$l]>0) {
									$mime_type = getimagesize($_FILES['image_variant']['tmp_name'][$i][$j][$k][$l]);
									if($_FILES['image_variant']['error'][$i][$j][$k][$l]) {
										$item_errors[] = $this->language->global->error_message->file_upload_empty;
									} elseif($_FILES['image_variant']['size'][$i][$j][$k][$l]>716800) {
										$item_errors[] = $this->language->global->error_message->file_upload_max_size;
									} elseif(!in_array($mime_type['mime'],$image_allowed)) {
										$item_errors[] = $this->language->global->error_message->invalid_file_type;
									}
								}
							}
						}
					}
				}
				if(!empty($item_errors)) {
					$errors['image.'.$i] = $item_errors;
				}
			}
			
			require APP_PATH . 'includes/ResizeImage.php';
			$item_settings = null;
			
			if (!file_exists(UPLOADS_PATH . 'galleries/' . $folder_id)) {
				mkdir(UPLOADS_PATH . 'galleries/' . $folder_id, 0755, true);
			}
			
			$images = json_decode($link->settings,true);
			
			for($i=0;$i<count($_POST['category']);$i++) {
				$sub_settings = null;
				for($j=0;$j<count($_POST['title'][$i]);$j++) {
					if($_FILES['image']['size'][$i][$j]>0) {
						$mime_type = getimagesize($_FILES['image']['tmp_name'][$i][$j]);
						$img_ext = 'jpg';
						
						$resize = new \ResizeImage($_FILES['image']['tmp_name'][$i][$j]);
						$resize->resizeTo(800, 800,'maxWidth');
						
						/* Generate new name for logo */
						if($mime_type['mime']=='image/png')
							$img_ext = 'png';
							
						$image_name = md5(time() . rand()) . '.' . $img_ext;

						/* Upload the original */
						$resize->saveImage(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $image_name, '90', $img_ext);
						
						$image_url = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $folder_id . '/' . $image_name;
						
						if(isset($images[$i]['products'][$j]['image_name'])) {
							if(file_exists(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images[$i]['products'][$j]['image_name'])) {
								unlink(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images[$i]['products'][$j]['image_name']);
							}
						}
						$variants = null;
						if(isset($_POST['title_variant'][$i][$j])) {
							for($k=0;$k<count($_POST['title_variant'][$i][$j]);$k++) {
								$sub_variants = null;
								for($l=0;$l<count($_POST['name_variant'][$i][$j][$k]);$l++) {
									if(isset($_POST['name_variant'][$i][$j][$k][$l])) {
										if($_FILES['image_variant']['size'][$i][$j][$k][$l]>0) {
											$mime_type = getimagesize($_FILES['image_variant']['tmp_name'][$i][$j][$k][$l]);
											$img_ext = 'jpg';
											
											$resize = new \ResizeImage($_FILES['image_variant']['tmp_name'][$i][$j][$k][$l]);
											$resize->resizeTo(800, 800,'maxWidth');
											
											/* Generate new name for logo */
											if($mime_type['mime']=='image/png')
												$img_ext = 'png';
												
											$image_name = md5(time() . rand()) . '.' . $img_ext;

											/* Upload the original */
											$resize->saveImage(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $image_name, '90', $img_ext);
											
											$image_url = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $folder_id . '/' . $image_name;
											
											if(isset($images[$i]['products'][$j]['variants'][$k][$l]['image_name'])) {
												if(file_exists(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images[$i]['products'][$j]['variants'][$k][$l]['image_name'])) {
													unlink(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images[$i]['products'][$j]['variants'][$k][$l]['image_name']);
												}
											}
										}
										
										$sub_variants[] = array('name' => $_POST['name_variant'][$i][$j][$k][$l],
																'image_name' => $image_name,
																'image_url' => $image_url,
																'price' => $_POST['price_variant'][$i][$j][$k][$l],
																'weight' => $_POST['weight_variant'][$i][$j][$k][$l]);
									}
								}
								if(isset($_POST['title_variant'][$i][$j][$k])) {
									$variants[] = array('title' => $_POST['title_variant'][$i][$j][$k],
														'select' => isset($_POST['select_variant'][$i][$j][$k]) ? 1 : 0,
														'variant' => $sub_variants);
								}
							}
						}
						$sub_settings[] = array("image_name" => $image_name,
												"image_url" => $image_url,
												"title" => ucwords($_POST['title'][$i][$j]),
												"description" => ucfirst($_POST['description'][$i][$j]),
												"price" => (int)$_POST['price'][$i][$j],
												"price_strike" => $_POST['price_strike'][$i][$j] ? (int)$_POST['price_strike'][$i][$j] : null,
												"weight" => $_POST['weight'][$i][$j] ? (int)$_POST['weight'][$i][$j] : 100,
												'variants' => $variants);
					} else {
						$variants = null;
						if(isset($_POST['title_variant'][$i][$j])) {
							for($k=0;$k<count($_POST['title_variant'][$i][$j]);$k++) {
								$sub_variants = null;
								for($l=0;$l<count($_POST['name_variant'][$i][$j][$k]);$l++) {
									if(isset($_POST['name_variant'][$i][$j][$k][$l])) {
										$image_name = isset($images[$i]['products'][$j]['variants'][$k][$l]['image_name']) ? $images[$i]['products'][$j]['variants'][$k][$l]['image_name'] : null;
										$image_url = isset($images[$i]['products'][$j]['variants'][$k][$l]['image_url']) ? $images[$i]['products'][$j]['variants'][$k][$l]['image_url'] : null;
										if($_FILES['image_variant']['size'][$i][$j][$k][$l]>0) {
											$mime_type = getimagesize($_FILES['image_variant']['tmp_name'][$i][$j][$k][$l]);
											$img_ext = 'jpg';
											
											$resize = new \ResizeImage($_FILES['image_variant']['tmp_name'][$i][$j][$k][$l]);
											$resize->resizeTo(800, 800,'maxWidth');
											
											/* Generate new name for logo */
											if($mime_type['mime']=='image/png')
												$img_ext = 'png';
												
											$image_name = md5(time() . rand()) . '.' . $img_ext;

											/* Upload the original */
											$resize->saveImage(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $image_name, '90', $img_ext);
											
											$image_url = SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $folder_id . '/' . $image_name;
											
											if(isset($images[$i]['products'][$j]['variants'][$k][$l]['image_name'])) {
												if(file_exists(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images[$i]['products'][$j]['variants'][$k][$l]['image_name'])) {
													unlink(UPLOADS_PATH . 'galleries/' . $folder_id . '/' . $images[$i]['products'][$j]['variants'][$k][$l]['image_name']);
												}
											}
										}
										
										$sub_variants[] = array('name' => ucwords($_POST['name_variant'][$i][$j][$k][$l]),
																'image_name' => $image_name,
																'image_url' => $image_url,
																'price' => $_POST['price_variant'][$i][$j][$k][$l],
																'weight' => $_POST['weight_variant'][$i][$j][$k][$l]);
									}
								}
								if(isset($_POST['title_variant'][$i][$j][$k])) {
									$variants[] = array('title' => ucwords($_POST['title_variant'][$i][$j][$k]),
														'select' => isset($_POST['select_variant'][$i][$j][$k])&&(int)$_POST['select_variant'][$i][$j][$k]==1 ? 1 : 0,
														'variant' => $sub_variants);
								}
							}
						}
						$sub_settings[] = array("image_name" => $images[$i]['products'][$j]['image_name'],
												"image_url" => $images[$i]['products'][$j]['image_url'],
												"title" => ucwords($_POST['title'][$i][$j]),
												"description" => ucfirst($_POST['description'][$i][$j]),
												"price" => (int)$_POST['price'][$i][$j],
												"price_strike" => $_POST['price_strike'][$i][$j] ? (int)$_POST['price_strike'][$i][$j] : null,
												"weight" => $_POST['weight'][$i][$j] ? (int)$_POST['weight'][$i][$j] : 100,
												'variants' => $variants);
					}
				}
				$item_settings[] = array("category" => ucwords($_POST['category'][$i]),
										 "products" => $sub_settings);
			}
			
			$url = $location_url = '';
			$type = 'biolink';
			$subtype = 'eshop';
			$order = 99;
			$settings = json_encode($item_settings);
			
			$stmt = Database::$database->prepare("UPDATE `links` SET `settings` = ? WHERE `link_id` = ?");
			$stmt->bind_param('ss', $settings, $_POST['link_id']);
			$stmt->execute();
			$stmt->close();

			/* Clear the cache */
			\Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

			Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
		}
    }

    private function update_link() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;
        $_POST['location_url'] = trim(Database::clean_string($_POST['location_url']));
        if(isset($_POST['schedule']) && !empty($_POST['start_date']) && !empty($_POST['end_date']) && Date::validate($_POST['start_date'], 'Y-m-d H:i:s') && Date::validate($_POST['end_date'], 'Y-m-d H:i:s')) {
            $_POST['start_date'] = (new \DateTime($_POST['start_date'], new \DateTimeZone($this->user->timezone)))->setTimezone(new \DateTimeZone(\Altum\Date::$default_timezone))->format('Y-m-d H:i:s');
            $_POST['end_date'] = (new \DateTime($_POST['end_date'], new \DateTimeZone($this->user->timezone)))->setTimezone(new \DateTimeZone(\Altum\Date::$default_timezone))->format('Y-m-d H:i:s');        } else {
            $_POST['start_date'] = $_POST['end_date'] = null;
        }

        /* Check if custom domain is set */
        $domain_id = $this->get_domain_id($_POST['domain_id']);

        /* Check for any errors */
        $fields = ['location_url'];

        /* Check for any errors */
        foreach($_POST as $key => $value) {
            if(empty($value) && in_array($key, $fields) == true) {
                Response::json($this->language->global->error_message->empty_fields, 'error');
                break 1;
            }
        }

        $this->check_url($_POST['url']);

        $this->check_location_url($_POST['location_url']);

        if(!$link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'user_id' => $this->user->user_id])) {
            die();
        }

        if($_POST['url'] == $link->url) {
            $url = $link->url;
        } else {
            $url = $_POST['url'] ? $_POST['url'] : string_generate(10);

            /* Generate random url if not specified */
            while(Database::exists('link_id', 'links', ['url' => $url, 'domain_id' => $domain_id])) {
                $url = string_generate(10);
            }
        }

        $stmt = Database::$database->prepare("UPDATE `links` SET `domain_id` = ?, `url` = ?, `location_url` = ?, `start_date` = ?, `end_date` = ? WHERE `link_id` = ?");
        $stmt->bind_param('ssssss', $domain_id, $url, $_POST['location_url'], $_POST['start_date'], $_POST['end_date'], $_POST['link_id']);
        $stmt->execute();
        $stmt->close();

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

        Response::json($this->language->link->success_message->settings_updated, 'success');
    }

    private function delete() {
        $_POST['link_id'] = (int) $_POST['link_id'];

        /* Check for possible errors */
        if(!$link = Database::get(['link_id', 'project_id', 'biolink_id', 'type', 'subtype', 'settings'], 'links', ['user_id' => $this->user->user_id, 'link_id' => $_POST['link_id']])) {
            die();
        }

        if(empty($errors)) {
			if($link->type == 'biolink' && $link->subtype != 'base') {
				$images = json_decode($link->settings);
				if($link->subtype == 'picture') {
					if(file_exists(UPLOADS_PATH . 'galleries/' . $link->biolink_id . '/' . $images->picture_name)) {
						unlink(UPLOADS_PATH . 'galleries/' . $link->biolink_id . '/' . $images->picture_name);
					}
				} elseif($link->subtype == 'cartform') {
					if(file_exists(UPLOADS_PATH . 'galleries/' . $link->biolink_id . '/' . $images->photo_name)) {
						unlink(UPLOADS_PATH . 'galleries/' . $link->biolink_id . '/' . $images->photo_name);
					}
				} elseif($link->subtype == 'sliders') {
					foreach($images->images as $im) {
						if(file_exists(UPLOADS_PATH . 'galleries/' . $link->biolink_id . '/' . $im->image_name)) {
							unlink(UPLOADS_PATH . 'galleries/' . $link->biolink_id . '/' . $im->image_name);
						}
					}
				}
			} elseif($link->type == 'biolink' && $link->subtype == 'base') {
				if(is_dir(UPLOADS_PATH . 'galleries/' . $link->link_id)) {
					array_map('unlink', glob(UPLOADS_PATH . 'galleries/' . $link->link_id . "/*.*"));
					rmdir(UPLOADS_PATH . 'galleries/' . $link->link_id);
				}
			}
			
            /* Delete from database */
            $stmt = Database::$database->prepare("DELETE FROM `links` WHERE `link_id` = ? OR `biolink_id` = ? AND `user_id` = ?");
            $stmt->bind_param('sss', $_POST['link_id'], $_POST['link_id'], $this->user->user_id);
            $stmt->execute();
            $stmt->close();

            /* Determine where to redirect the user */
            if($link->type == 'biolink' && $link->subtype != 'base') {
                $redirect_url = url('link/' . $link->biolink_id . '?tab=links');
            } else {
                $redirect_url = url('project/' . $link->project_id);
            }

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('biolinks_links_user_' . $this->user->user_id);

            Response::json('', 'success', ['url' => $redirect_url]);
        }
    }

    private function mail() {
        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['email'] = mb_substr(trim(Database::clean_string($_POST['email'])), 0, 320);

        /* Get the link data */
        $link = Database::get('*', 'links', ['link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'mail']);

        if($link) {
            $link->settings = json_decode($link->settings);

            /* Send the webhook */
            if($link->settings->webhook_url) {

                $body = \Unirest\Request\Body::form(['email' => $_POST['email']]);

                $response = \Unirest\Request::post($link->settings->webhook_url, [], $body);

            }

            /* Send the email to mailchimp */
            if($link->settings->mailchimp_api && $link->settings->mailchimp_api_list) {

                /* Check the mailchimp api list and get data */
                $explode = explode('-', $link->settings->mailchimp_api);

                if(count($explode) < 2) {
                    die();
                }

                $dc = $explode[1];
                $url = 'https://' . $dc . '.api.mailchimp.com/3.0/lists/' . $link->settings->mailchimp_api_list . '/members';

                /* Try to subscribe the user to mailchimp list */
                \Unirest\Request::auth('altum', $link->settings->mailchimp_api);

                $body = \Unirest\Request\Body::json([
                    'email_address' => $_POST['email'],
                    'status' => 'subscribed',
                ]);

                \Unirest\Request::post(
                    $url,
                    [],
                    $body
                );

            }

            Response::json($link->settings->success_text, 'success');
        }
    }

    /* Function to bundle together all the checks of a custom url */
    private function check_url($url) {

        if($url) {
            /* Make sure the url alias is not blocked by a route of the product */
            if(array_key_exists($url, Router::$routes[''])) {
                Response::json($this->language->link->error_message->blacklisted_url, 'error');
            }

            /* Make sure the custom url is not blacklisted */
            if(in_array($url, $this->settings->links->blacklisted_keywords)) {
                Response::json($this->language->link->error_message->blacklisted_keyword, 'error');
            }

        }

    }

    /* Function to bundle together all the checks of an url */
    private function check_location_url($url) {

        if(empty(trim($url))) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        $url_details = parse_url($url);

        if(!isset($url_details['scheme']) || (isset($url_details['scheme']) && !in_array($url_details['scheme'], ['http', 'https']))) {
            Response::json($this->language->link->error_message->invalid_location_url, 'error');
        }

        /* Make sure the domain is not blacklisted */
        if(in_array(get_domain($url), $this->settings->links->blacklisted_domains)) {
            Response::json($this->language->link->error_message->blacklisted_domain, 'error');
        }

        /* Check the url with phishtank to make sure its not a phishing site */
        if($this->settings->links->phishtank_is_enabled) {
            if(phishtank_check($url, $this->settings->links->phishtank_api_key)) {
                Response::json($this->language->link->error_message->blacklisted_location_url, 'error');
            }
        }

        /* Check the url with google safe browsing to make sure it is a safe website */
        if($this->settings->links->google_safe_browsing_is_enabled) {
            if(google_safe_browsing_check($url, $this->settings->links->google_safe_browsing_api_key)) {
                Response::json($this->language->link->error_message->blacklisted_location_url, 'error');
            }
        }
    }

    /* Check if custom domain is set and return the proper value */
    private function get_domain_id($posted_domain_id) {

        $domain_id = 0;

        if(isset($posted_domain_id)) {
            $domain_id = (int) Database::clean_string($posted_domain_id);
            if(parse_url(url())['host'] == global_whitelabel('url')){
                $domain_id = link_global_whitelabel('domain_id');
            }
            else {
            $domain_id = $this->database->query("SELECT `domain_id` FROM `domains` WHERE `domain_id` = {$domain_id} AND (`user_id` = {$this->user->user_id} OR `type` = 1)")->fetch_object()->domain_id ?? 0;
            }
        }

        return $domain_id;
    }
	
	private function product_forms() {
		if($_POST) {
			
		}
	}
}
