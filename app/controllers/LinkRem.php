<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Models\Domain;
use Altum\Title;
use Altum\Response;

class LinkRem extends Controller {
    public $link;

    public function index() {
	return "OK";
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
			
			if($user = Database::get(['country','shipping','ro_pro_package','ro_pro_courier','ro_pro_biolink','ro_pro_expired'], 'users', ['user_id' => $user_id])) {
				
				$shipping = $user->shipping ? json_decode($user->shipping) : null;
				if($shipping) {
					$config['apikey'] = $shipping->apikey;
					$config['package'] = 'pro';
					$packages = array('starter','basic','pro');
					
					if($user->country=='ID') {
						if($user->ro_pro_package) {
							$user->ro_pro_biolink = json_decode($user->ro_pro_biolink);
							$user->ro_pro_courier = json_decode($user->ro_pro_courier);
							$user->ro_pro_expired = json_decode($user->ro_pro_expired);
							
							$bl_key = array_search($link_id,$user->ro_pro_biolink);
							if($user->ro_pro_expired[$bl_key]=='lifetime') {
								$config['apikey'] = ro_api_rotator();
								$config['package'] = $packages[2];
								$config['couriers'] = $user->ro_pro_courier[$bl_key];
							} elseif($user->ro_pro_expired[$bl_key]>strtotime('NOW')) {
								$config['apikey'] = ro_api_rotator();
								$config['package'] = $packages[2];
								$config['couriers'] = $user->ro_pro_courier[$bl_key];
							} else {
								if($shipping->package==2)  {
									$config['apikey'] = $shipping->apikey;
									$config['package'] = $packages[2];
								} else {
									$config['apikey'] = ro_starter_api_rotator();
									$config['package'] = $packages[0];
								}
								$config['couriers'] = isset($shipping->couriers) ? $shipping->couriers : null;
							}
						} else {
							if($shipping->package==2)  {
								$config['apikey'] = $shipping->apikey;
								$config['package'] = $packages[2];
							} else {
								$config['apikey'] = ro_starter_api_rotator();
								$config['package'] = $packages[0];
							}
							$config['couriers'] = isset($shipping->couriers) ? $shipping->couriers : null;
						}
						$config['weight'] = isset($_POST['weight']) ? (int)$_POST['weight'] : 1000;
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
		$current_couriers = explode(':',$config['couriers']);
		$config['couriers'] = implode(':',array_intersect($current_couriers,$work_couriers));
		
		if(intval($weight)<=0) $weight = 1000;
		
		$url 		= 'https://api.rajaongkir.com/starter/cost';
		
		//Package Basic dan Pro courier bisa lebih dari satu, Contoh: jne:pos:tiki
		$courier	= $config['couriers'] ? $config['couriers'] : 'jne';
		$postdata 	= "origin=".$origin."&destination=".$destination."&weight=".$weight."&courier=".$courier;
		
		if($package=="basic") {
			$courier	= $config['couriers'] ? $config['couriers'] : 'jne:tiki:pos';
			$url = str_replace('/starter/','/basic/',$url);
			$postdata 	= "origin=".$origin."&destination=".$destination."&weight=".$weight."&courier=".$courier;
		} else if($package=="pro") {
			$courier	= $config['couriers'] ? $config['couriers'] : 'jne:jnt:sicepat:pos:tiki';
			$url 		= 'https://pro.rajaongkir.com/api/cost';
			$postdata 	= "origin=".$origin."&originType=city&destination=".$destination."&destinationType=city&weight=".$weight."&courier=".$courier;
		}
		
		
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
