<?php

function display_notifications() {
    $types = ['error', 'success', 'info'];

    foreach($types as $type) {
        if(isset($_SESSION[$type]) && !empty($_SESSION[$type])) {
            if(!is_array($_SESSION[$type])) $_SESSION[$type] = [$_SESSION[$type]];

            foreach($_SESSION[$type] as $message) {
                $csstype = ($type == 'error') ? 'danger' : $type;

                echo '
					<div class="alert alert-' . $csstype . ' animated fadeInDown">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
					    ' . $message . '
					</div>
				';
            }
            unset($_SESSION[$type]);
        }
    }

}

function error_object_notif() {
    $types = ['error', 'success', 'info'];
	$errors = array();
	
    foreach($types as $type) {
        if(isset($_SESSION[$type]) && !empty($_SESSION[$type])) {
            if(!is_array($_SESSION[$type])) $_SESSION[$type] = [$_SESSION[$type]];
            foreach($_SESSION[$type] as $ky => $msg) {
				if($type == 'error') {
					$errors[] = $msg;
				}
            }
            unset($_SESSION[$type]);
        }
    }
	return json_encode($errors, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function woowa_notifications($phone, $message, $api_key=null, $token=null) {
    //$api_key='b52e780b1a7b679e949bd8f45247cb6c6b12f151f5a6bdff'; //this is demo key please change with your own key
	$url='http://116.203.191.58/api/send_message';
	$data = array(
	  "phone_no"=> $phone,
	  "key"		=> $api_key,
	  "message"	=> $message
	);
	$data_string = json_encode($data);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  'Content-Type: application/json',
	  'Content-Length: ' . strlen($data_string))
	);
	$res=curl_exec($ch);
	curl_close($ch);
	
	return $res;
}
