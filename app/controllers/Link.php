<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Models\Domain;
use Altum\Title;
use Altum\Response;

class Link extends Controller {
    public $link;

    public function index() {

        Authentication::guard();
		//\Altum\Cache::$adapter->clear();
		//print_r("TESSSSSSSSSTTTT");
		
		if(strtotime($this->user->package_expiration_date) < strtotime('NOW')) {
			redirect('package/new');
		}
		
        $link_id = isset($this->params[0]) ? (int) $this->params[0] : false;
        $method = isset($this->params[1]) && in_array($this->params[1], ['settings', 'statistics']) ? $this->params[1] : 'settings';

        /* Make sure the link exists and is accessible to the user */
        if(!$this->link = Database::get('*', 'links', ['link_id' => $link_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }
		
		$this->link->main_domain = BASE_DOMAIN;
		if(trim($_SERVER['SERVER_NAME'])!=BASE_DOMAIN)
			$this->link->main_domain = trim($_SERVER['SERVER_NAME']);
		
		$this->link->main_domain = 'https://' . $this->link->main_domain . '/';
		
        $this->link->settings = json_decode($this->link->settings);
		
		$this->link->settings->seo->meta_image = $this->link->settings->seo->meta_image ? SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $this->link->link_id . '/' . $this->link->settings->seo->meta_image : null;
		
        /* Get the current domain if needed */
        $this->link->domain = $this->link->domain_id ? (new Domain())->get_domain($this->link->domain_id) : null;
		
        /* Determine the actual full url */
        $this->link->full_url = $this->link->domain ? $this->link->domain->url . $this->link->url : url($this->link->url);
		
		$this->blocks = new \stdClass();
		
		/* Set Array Default for Form/Block Title */
		$this->blocks->block_title = ['spotify', 'youtube', 'vimeo', 'tiktok', 'twitch', 'soundcloud', 'html', 'text', 'mail', 'picture', 'banner', 'sliders', 'waform', 'cartform', 'googlemap', 'countdown', 'domain', 'floatbutton', 'pricingtable', 'eshop', 'runningtext'];
		
		/* Set Array Default for Form/Block Statistics, Note: Statistics will not show if the word put in array */
		$this->blocks->block_statistic = ['runningtext', 'eshop', 'pricingtable', 'floatbutton', 'domain', 'countdown', 'googlemap', 'cartform', 'waform', 'sliders', 'banner', 'picture', 'html', 'mail', 'text', 'youtube', 'vimeo', 'tiktok', 'twitch', 'spotify', 'soundcloud'];
		
        /* Handle code for different parts of the page */
        switch($method) {
            case 'settings':

                if($this->link->type == 'biolink') {
                    /* Get the links available for the biolink */
                    $link_links_result = $this->database->query("SELECT * FROM `links` WHERE `biolink_id` = {$this->link->link_id} ORDER BY `order` ASC");

                    $biolink_link_types = require APP_PATH . 'includes/biolink_link_types.php';

                    /* Add the modals for creating the links inside the biolink */
                    foreach($biolink_link_types as $key) {
                        $data = ['link' => $this->link];
                        $view = new \Altum\Views\View('link/settings/create_' . $key . '_modal.settings.biolink.method', (array) $this);
                        \Altum\Event::add_content($view->run($data), 'modals');
                    }
					
					$data = ['link' => $this->link];
					$view = new \Altum\Views\View('link/settings/create_exportb_modal.settings.biolink.method', (array) $this);
					\Altum\Event::add_content($view->run($data), 'modals');

                    $data = ['link' => $this->link];
					$view = new \Altum\Views\View('link/settings/create_importb_modal.settings.biolink.method', (array) $this);
					\Altum\Event::add_content($view->run($data), 'modals');
					
					$data = ['link' => $this->link];
					$view = new \Altum\Views\View('link/settings/duplicate_biolink_modal.settings.biolink.method', (array) $this);
					\Altum\Event::add_content($view->run($data), 'modals');

                    if($this->link->subtype != 'base') {
                        redirect('link/' . $this->link->biolink_id);
                    }
                }

                /* Get the available domains to use */
                $domains = (new Domain())->get_biolink_domains($this->user->user_id);

                /* Prepare variables for the view */
                $data = [
                    'link'              => $this->link,
                    'method'            => $method,
                    'link_links_result' => $link_links_result ?? null,
                    'domains'           => $domains,
					'user'				=> $this->user
                ];

                break;


            case 'statistics':

                $type = isset($this->params[2]) && in_array($this->params[2], ['lastactivity', 'referrers', 'countries', 'operatingsystems', 'browsers', 'devices', 'browserlanguages']) ? Database::clean_string($this->params[2]) : 'lastactivity';
                $start_date = isset($_GET['start_date']) ? Database::clean_string($_GET['start_date']) : null;
                $end_date = isset($_GET['end_date']) ? Database::clean_string($_GET['end_date']) : null;

                $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

                /* Get data needed for statistics from the database */
                $logs = [];
                $logs_chart = [];

                $logs_result = Database::$database->query("
                    SELECT
                        COUNT(`count`) AS `uniques`,
						SUM(`count`) AS `impressions`,
                        DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
                    FROM
                         `track_links`
                    WHERE
                        `link_id` = {$this->link->link_id}
                        AND (`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                    GROUP BY
                        `formatted_date`
                    ORDER BY
                        `formatted_date`
                ");

                /* Generate the raw chart data and save logs for later usage */
                while($row = $logs_result->fetch_object()) {
                    $logs[] = $row;

                    $logs_chart[$row->formatted_date] = [
                        'impressions'        => $row->impressions,
                        'uniques'            => $row->uniques,
                    ];
                }

                $logs_chart = get_chart_data($logs_chart);

                /* Get data based on what statistics are needed */
                switch($type) {
                    case 'lastactivity':

                        $result = Database::$database->query("
                            SELECT
                                `dynamic_id`,
                                `referrer`,
                                `country_code`,
                                `os_name`,
                                `browser_name`,
                                `browser_language`,
                                `device_type`,
                                `last_date`
                            FROM
                                `track_links`
                            WHERE
                                `link_id` = {$this->link->link_id}
                                AND (`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                            ORDER BY
                                `last_date` DESC
                            LIMIT 25
                        ");

                    break;

                    case 'referrers':
                    case 'countries':
                    case 'operatingsystems':
                    case 'browsers':
                    case 'devices':
                    case 'browserlanguages':

                        $columns = [
                            'referrers' => 'referrer',
                            'countries' => 'country_code',
                            'operatingsystems' => 'os_name',
                            'browsers' => 'browser_name',
                            'devices' => 'device_type',
                            'browserlanguages' => 'browser_language'
                        ];

                        $result = Database::$database->query("
                            SELECT
                                `{$columns[$type]}`,
                                COUNT(DISTINCT {$columns[$type]}) AS `total`
                            FROM
                                 `track_links`
                            WHERE
                                `link_id` = {$this->link->link_id}
                                AND (`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                            GROUP BY
                                `{$columns[$type]}`
                            ORDER BY
                                `total` DESC
                            LIMIT 250
                        ");

                        break;
                }

                $statistics_rows = [];

                while($row = $result->fetch_object()) {
                    $statistics_rows[] = $row;
                }

                /* Prepare the statistics method View */
                $data = [
                    'rows' => $statistics_rows
                ];

                $view = new \Altum\Views\View('link/statistics/' . $type . '.statistics.method', (array) $this);
                $this->add_view_content('statistics.method', $view->run($data));

                /* Prepare variables for the view */
                $data = [
                    'link'              => $this->link,
                    'method'            => $method,
                    'type'              => $type,
                    'date'              => $date,
                    'logs'              => $logs,
                    'logs_chart'        => $logs_chart
                ];

                break;
        }

        /* Prepare the method View */
        $view = new \Altum\Views\View('link/' . $method . '.method', (array) $this);
        $this->add_view_content('method', $view->run($data));

        /* Prepare the View */
        $data = [
            'link'      => $this->link,
            'method'    => $method
        ];

        $view = new \Altum\Views\View('link/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->link->title, $this->link->url));

    }
	
	public function locationajax() {
		
		if($_POST['tp']&&$_POST['vl']) {
			$ct = $sd = array();
			if($_POST['tp']=='pv') {
				$num_kt = $num_ix = 0;
				
				$r_ct = Database::$database->query("SELECT * FROM `kota_kabupaten` WHERE kt_pv_id = ".(int)$_POST['vl']);
				while($ct_row = $r_ct->fetch_object()) {
					$ct[] = array('id' => $ct_row->kt_id, 'name' => ($ct_row->kt_type==1 ? '' : 'Kab. ').$ct_row->kt_name);
					if($num_ix==0) $num_kt = $ct_row->kt_id;
					$num_ix++;
				}
				
				$r_sd = Database::$database->query("SELECT * FROM `kecamatan` WHERE kc_kt_id = ".$num_kt);
				while($sd_row = $r_sd->fetch_object()) {
					$sd[] = array('id' => $sd_row->kc_id, 'name' => $sd_row->kc_name);
				}
				
				Response::simple_json([
					'ct' => $ct,
					'sd' => $sd,
				]);
				
			} elseif($_POST['tp']=='ct') {
				
				$r_sd = Database::$database->query("SELECT * FROM `kecamatan` WHERE kc_kt_id = ".(int)$_POST['vl']);
				while($sd_row = $r_sd->fetch_object()) {
					$sd[] = array('id' => $sd_row->kc_id, 'name' => $sd_row->kc_name);
				}
				
				Response::simple_json([
					'sd' => $sd,
				]);
				
			} else {
				Response::simple_json([
					'status' => 'error'
				]);
			}
			
		} else {
			Response::simple_json([
				'status' => 'error'
			]);
		}
	}
	
	public function spcajax() {
		
		$user_id = string_decode($_POST['eu_id'],'12345678');
		$link_id = string_decode($_POST['el_id'],'12345678');
		
		if($user_id) {
			
			if($user = Database::get(['country','shipping','ro_pro_courier'], 'users', ['user_id' => $user_id])) {
				
				$shipping = $user->shipping ? json_decode($user->shipping) : null;
				$pro_couriers = $user->ro_pro_courier ? json_decode($user->ro_pro_courier) : null;
				if($shipping) {
					$config['apikey'] = $shipping->apikey;
					$config['package'] = 'pro';
					//$packages = array('starter','basic','pro');
					
					if($user->country=='ID') {
						$config['apikey'] = ro_api_rotator();
						$config['couriers'] = $pro_couriers ? $pro_couriers : explode(':',$shipping->couriers);
						
						$config['weight'] = isset($_POST['weight']) ? (int)$_POST['weight'] : 100;
						$cost = $this->getRajaOngkirCost($config,$shipping->kt,$_POST['city']);
						
						if(isset($cost['status'])&&$cost['status']=='error'){
							Response::simple_json([
								'status' => 'success',
								'shp' => 'ro',
								'data' => '',
								'message' => 'Shipping not available yet for this shop!.'
							]);
						}
					} elseif($user->country=='MY') {
						$config['apikey'] = $shipping->apikey;
						$config['secret'] = $shipping->secret;
						$cost = $this->getParcelAsiaCost($config);
					}
					Response::simple_json([
						'status' => 'success',
						'shp' => 'ro',
						'data' => $cost ? $cost : '',
						'message' => !$cost ? 'Shipping not available yet for this shop!.' : ''
					]);
				} else {
					Response::simple_json([
						'status' => 'success',
						'shp' => 'ro',
						'data' => '',
						'message' => 'Shipping not available yet for this shop!.'
					]);
				}
			
			} else {
				
				Response::simple_json([
					'status' => 'error'
				]);
				
			}
			
		} else {
			
			Response::simple_json([
				'status' => 'error'
			]);
			
		}
		
	}
	
	public function bkcajax() {
		
		$user_id = string_decode($_POST['eu_id'],'12345678');
		
		if($user_id) {
			
			if($user = Database::get(['bank_account'], 'users', ['user_id' => $user_id])) {
				
				$bank_account = json_decode($user->bank_account);
				Response::simple_json([
					'status' => 'success',
					'data' => $bank_account ? $bank_account : '',
					'message' => !$bank_account ? 'Bank Account not available yet for this shop!.' : null
				]);
			
			} else {
				
				Response::simple_json([
					'status' => 'error'
				]);
				
			}
			
		} else {
			
			Response::simple_json([
				'status' => 'error'
			]);
			
		}
		
	}
	
	private function getRajaOngkirCost($config, $origin,$destination) {
		$apikey 	= $config['apikey'];
		$package 	= $config['package'];
		$weight		= (int)$config['weight'];
		
		$work_couriers = explode(':',"jne:jnt:sicepat:pos:anteraja:tiki:wahana:pandu:first:lion:ninja:rex:ide:ncs:rpx:pcp:esl:pahala:dse:slis:star:idl");
		$current_couriers = $config['couriers'];
		$config['couriers'] = implode(':',array_intersect($current_couriers,$work_couriers));
		
		if(intval($weight)<=0) $weight = 1000;
		
		$url 		= 'https://api.rajaongkir.com/starter/cost';
		
		//Package Basic dan Pro courier bisa lebih dari satu, Contoh: jne:pos:tiki
		$courier	= $config['couriers'] ? $config['couriers'] : 'jne';
		$postdata 	= "origin=".$origin."&destination=".$destination."&weight=".$weight."&courier=".$courier;
		
		$courier	= $config['couriers'] ? $config['couriers'] : 'jne:jnt:sicepat:pos:tiki';
		$url 		= 'https://pro.rajaongkir.com/api/cost';
		$postdata 	= "origin=".$origin."&originType=city&destination=".$destination."&destinationType=city&weight=".$weight."&courier=".$courier;
	
		
		$response = null;
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $postdata,
		  CURLOPT_HTTPHEADER => array(
			"content-type: application/x-www-form-urlencoded",
			"key: ".$apikey
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		$result = null;
		$ongkir = json_decode($response,true);
		$num = 0;
		if($ongkir['rajaongkir']['status']['code']==400) {
			$result['status'] = 'error';
			$result['message'] = 'Invalid API KEY';
		} else {
		
			foreach($ongkir['rajaongkir']['results'] as $cr) {
				$costs = null;
				
				foreach($cr['costs'] as $ok) {
					$etd = $ok['cost'][0]['etd'];
					if($etd) {
						$etd = str_replace("hari","",strtolower($ok['cost'][0]['etd']));
					}
					if($ok['cost'][0]['value'])
						$costs[] = array("name" => ucwords($ok['service']), "name_long" => strtoupper($cr['code']).' '.$ok['service'], 'desc' => $ok['description'], "cost" => $ok['cost'][0]['value'], "costtext" => number_format($ok['cost'][0]['value'], 0, "", ","), "etd" => $etd);
				}
				$result[] = array('cd' => $cr['code'],
								'nm' => $cr['name'],
								'ct' => $costs);
			}
		
		}

		if ($err) {
			$result['status'] = 'error';
			$result['message'] = $err;
		}
		
		return $result;
	}
	
	private function getParcelAsiaCost($config) {
		$domain = "https://demo.connect.easyparcel.my/?ac=";

		$action = "EPRateCheckingBulk";
		$postparam = array(
		'api'	=> 'EP-jxS8zfRAt',
		'bulk'	=> array(
			array(
				'pick_code'	=> '10050',
				'pick_state'	=> 'png',
				'pick_country'	=> 'MY',
				'send_code'	=> '11950',
				'send_state'	=> 'png',
				'send_country'	=> 'MY',
				'weight'	=> '5',
				'width'	=> '0',
				'length'	=> '0',
				'height'	=> '0',
			),
		),
		'exclude_fields'	=> array(
				'rates.*.pickup_point', 'rates.*.dropoff_point'
			)
		);

		$url = $domain.$action;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postparam));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$return = curl_exec($ch);
		curl_close($ch);

		$json = json_decode($return);
		return $return;
		
	}

}
