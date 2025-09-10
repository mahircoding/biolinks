<?php

use Altum\Database\Database;

function string_truncate($string, $maxchar) {
    $length = strlen($string);
    if($length > $maxchar) {
        $cutsize = -($length-$maxchar);
        $string  = substr($string, 0, $cutsize);
        $string  = $string . '..';
    }
    return $string;
}

function string_filter_alphanumeric($string) {

    $string = preg_replace('/[^a-zA-Z0-9\s]+/', '', $string);

    $string = preg_replace('/\s+/', ' ', $string);

    return $string;
}

function string_generate($length) {
    $characters = str_split('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
    $content = '';

    for($i = 1; $i <= $length; $i++) {
        $content .= $characters[array_rand($characters, 1)];
    }

    return $content;
}

function string_ends_with($needle, $haystack) {
    return substr($haystack, -strlen($needle)) === $needle;
}

function string_estimate_reading_time($string) {
    $total_words = str_word_count(strip_tags($string));

    /* 200 is the total amount of words read per minute */
    $minutes = floor($total_words / 200);
    $seconds = floor($total_words / 200 / (200 / 60));

    return (object) [
        'minutes' => $minutes,
        'seconds' => $seconds
    ];
}

function string_encode($string, $key = '') {
	$enc = xor_encode($string, $key);
	return base64_encode($enc);
}

function string_decode($string, $key = '') {
	if (preg_match('/[^a-zA-Z0-9\/\+=]/', $string)) {
		return FALSE;
	}
	$dec = base64_decode($string);
	$dec = xor_decode($dec, $key);
	return $dec;
}

function xor_encode($string, $key) {
	$rand = '';
	while (strlen($rand) < 32) {
		$rand .= mt_rand(0, mt_getrandmax());
	}
	$rand = hash_str($rand);
	$enc = '';
	for ($i = 0; $i < strlen($string); $i++) {
		$enc .= substr($rand, ($i % strlen($rand)), 1).(substr($rand, ($i % strlen($rand)), 1) ^ substr($string, $i, 1));
	}
	return xor_merge($enc, $key);
}

function xor_decode($string, $key) {
	$string = xor_merge($string, $key);
	$dec = '';
	for ($i = 0; $i < strlen($string); $i++) {
		$dec .= (substr($string, $i++, 1) ^ substr($string, $i, 1));
	}
	return $dec;
}

function xor_merge($string, $key) {
	$hash = hash_str($key);
	$str = '';
	for ($i = 0; $i < strlen($string); $i++) {
		$str .= substr($string, $i, 1) ^ substr($hash, ($i % strlen($hash)), 1);
	}
	return $str;
}

function remove_cipher_noise($data, $key) {
	$keyhash = hash_str($key);
	$keylen = strlen($keyhash);
	$str = '';
	for ($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j) {
		if ($j >= $keylen){
			$j = 0;
		}
		$temp = ord($data[$i]) - ord($keyhash[$j]);
		if ($temp < 0) {
			$temp = $temp + 256;
		}
		$str .= chr($temp);
	}
	return $str;
}
	
function hash_str($str,$hash_type='sha1'){
	return ($hash_type == 'sha1') ? sha1($str) : md5($str);
}

function gen_chat_token($user_id,$origin,$key='') {
	$tmp_key = rand(100000,999999);
	$user_id = (int)$user_id + (int)$tmp_key;
	$expired = strtotime('+6 hours', strtotime("now"));
	return string_encode($user_id.':'.$tmp_key.':'.$origin.':'.$expired,$key);
}

function valid_3in1_packages($pkg_3in1,$exp_3in1) {
	
}

function trim_url($str){
    $str = trim($str, '/');

    if (!preg_match('#^http(s)?://#', $str)) {
        $str = 'http://' . $str;
    }

    $urlParts = parse_url($str);

    $domain_name = preg_replace('/^www\./', '', $urlParts['host']);

    return $domain_name;
}

class UniqueValues{
    #The data Array
    private $dataArray;
    /*
        The index you want to get unique values.
        It can be the named index or the integer index.
        In our case it is "member_name"
    */
    private $indexToFilter;

    public function __construct($dataArray, $indexToFilter){
        $this->dataArray = $dataArray;
        $this->indexToFilter = $indexToFilter;
    }
    private function getUnique(){
        foreach($this->dataArray as $key =>$value){
            $id[$value[$this->indexToFilter]]=$key;
        }
        return array_keys(array_flip(array_unique($id,SORT_REGULAR)));
    }
    public function getFiltered(){
        $array = $this->getUnique();
        $i=0;
        foreach($array as $key =>$value){
            $newAr[$i]=$this->dataArray[$value];
            $i++;
        }
        return $newAr;
    }
}

function fetch_all_assoc(& $result,$index_keys) {

  // Args :    $result = mysqli result variable (passed as reference to allow a free() at the end
  //           $indexkeys = array of columns to index on
  // Returns : associative array indexed by the keys array

  $assoc = array();             // The array we're going to be returning

  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

        $pointer = & $assoc;            // Start the pointer off at the base of the array

        for ($i=0; $i<count($index_keys); $i++) {
       
                $key_name = $index_keys[$i];
                if (!isset($row[$key_name])) {
                        print "Error: Key $key_name is not present in the results output.\n";
                        return(false);
                }

                $key_val= isset($row[$key_name]) ? $row[$key_name]  : "";
       
                if (!isset($pointer[$key_val])) {              

                        $pointer[$key_val] = "";                // Start a new node
                        $pointer = & $pointer[$key_val];                // Move the pointer on to the new node
                }
                else {
                        $pointer = & $pointer[$key_val];            // Already exists, move the pointer on to the new node
                }

        } // for $i

        // At this point, $pointer should be at the furthest point on the tree of keys
        // Now we can go through all the columns and place their values on the tree
        // For ease of use, include the index keys and their values at this point too

        foreach ($row as $key => $val) {
                        $pointer[$key] = $val;
        }

  } // $row

  /* free result set */
  $result->close();

  return($assoc);              
}

function array_search_multi($array, $key, $value)
{
    $results = array();
    if (is_array($array))
    {
        if (isset($array[$key]) && $array[$key] == $value)
            $results[] = $array; 
        foreach ($array as $subarray)
            $results = array_merge($results, array_search_multi($subarray, $key, $value));
    }
    return $results;
}

function whitelabel($str) {
    
    $url = $_SERVER['SERVER_NAME']; 
    $result = Database::$database->query("SELECT * FROM `whitelabel` WHERE `url` = '{$url}'");
    $row = mysqli_fetch_array($result);
    if (!$result) {
        exit;
    }
    if (isset($row[$str])){
        return $row[$str];
     }
}

function global_whitelabel($str) {
    
    $url = $_SERVER['SERVER_NAME']; 
    $result = Database::$database->query("SELECT `url` FROM `whitelabel` LEFT JOIN `domains` ON `whitelabel`.`user_id` = `domains`.`user_id` where `whitelabel`.`url` = '{$url}' AND `domains`.`is_admin` = 1;");
    $row = mysqli_fetch_array($result);
    if (!$result) {
        exit;
    }
    if (isset($row[$str])){
        return $row[$str];
     }
}

function link_whitelabel($str) {
    
    $url = $_SERVER['SERVER_NAME']; 
    $result = Database::$database->query("SELECT * FROM `links` LEFT JOIN `domains` ON `links`.`domain_id` = `domains`.`domain_id` WHERE `links`.`user_id` = (SELECT `user_id` FROM `whitelabel` WHERE `url` = '{$url}') AND `links`.`domain_id` = 0  limit 1;");
    $row = mysqli_fetch_array($result);
    if (!$result) {
        exit;
    }
    if (isset($row[$str])){
        return $row[$str];
     }
}

function link_global_whitelabel($str) {
    
    $url = $_SERVER['SERVER_NAME']; 
    $result = Database::$database->query("SELECT * FROM `links` LEFT JOIN `domains` ON `links`.`domain_id` = `domains`.`domain_id` WHERE `links`.`user_id` = (SELECT `user_id` FROM `whitelabel` WHERE `url` = '{$url}') AND `domains`.`is_admin` = 1 limit 1;");
    $row = mysqli_fetch_array($result);
    if (!$result) {
        exit;
    }
    if (isset($row[$str])){
        return $row[$str];
     }
}

function custom_domain($str) {
    
    $url = $_SERVER['SERVER_NAME']; 
    $result = Database::$database->query("SELECT * FROM `domains` WHERE `host` = '{$url}' AND `is_active` = 1");
    $row = mysqli_fetch_array($result);
    return $row['host'];
}

function custom_index_url($str) {
    
    $url = $_SERVER['SERVER_NAME']; 
    $result = Database::$database->query("SELECT * FROM `domains` WHERE `host` = '{$url}' AND `index_url` != ''");
    $row = mysqli_fetch_array($result);
    return $row[$str];
}

function api_mailketing($first_name, $email) {
    $api_token='f30a3f31c16c89c83c00d95d6c47e756'; //silahkan copy dari api token mailketing
    $list_id='17290';
    $params = [
    'first_name' => $first_name,
    'email' => $email,
    'api_token' => $api_token,
    'list_id' => $list_id
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://app.mailketing.co.id/api/v1/addsubtolist");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec ($ch);
    //print_r($output);
    curl_close ($ch);
}