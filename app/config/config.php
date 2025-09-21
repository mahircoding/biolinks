<?php
/* Configuration of the site */
define('DATABASE_SERVER',   '127.0.0.1:3307');
define('DATABASE_USERNAME', 'root');
define('DATABASE_PASSWORD', '');
define('DATABASE_NAME',     'kiblatbio');

define('BASE_DOMAIN', 'demo.sekolahotakkananindonesia.sch.id');
define('BASE_IP', '103.163.139.126');
define('WA_NUMBER', '6285784989876');

header("Access-Control-Allow-Origin: https://chatgpt." . BASE_DOMAIN);
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");

$whitelabel = strtolower(WL('url'));
$CustomDomain = CustomDomain('host');

$base = BASE_DOMAIN;
$ctdomain = $base;

$url = trim($_SERVER['SERVER_NAME'], '/'); 
if ($_SERVER['SERVER_NAME'] == $whitelabel) {
	$ctdomain = $whitelabel.'/';
} elseif ($_SERVER['SERVER_NAME'] == $CustomDomain) {
	$ctdomain = $CustomDomain.'/';
} else {
	$ctdomain = $base.'/';
}

if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $protocol = 'https://';
} else {
  $protocol = 'http://';
}

define('SITE_URL',$protocol.$ctdomain);
/*if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == $whitelabel) {
        define('SITE_URL','https://'.$whitelabel.'/');
} else if($_SERVER['SERVER_NAME'] == $whitelabel) {
        define('SITE_URL','https://'.$whitelabel.'/');
} else if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == $CustomDomain) {
        define('SITE_URL','https://'.$CustomDomain.'/');
} else if($_SERVER['SERVER_NAME'] == $CustomDomain) {
        define('SITE_URL','https://'.$CustomDomain.'/');
} else define('SITE_URL','https://'.$base.'/');
*/

function WL($str) {
    $whitelabel = trim($_SERVER['SERVER_NAME'], '/'); 
    $sql = "SELECT `url` FROM `whitelabel` WHERE `url` = '{$whitelabel}'";
    $conn = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
    $result = $conn->query($sql);
    $row = mysqli_fetch_array($result);
    if (!$result) {
        exit;
    }
    if (isset($row[$str])){
        return $row[$str];
     }
}

function CustomDomain($str) {
    $customURL = trim($_SERVER['SERVER_NAME'], '/');  
    $sql = "SELECT * FROM `domains` WHERE `host` = '{$customURL}' AND `is_active` = 1";
//echo $sql; exit;
    $conn = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);$result = $conn->query($sql);
    $result = $conn->query($sql);
    $row = mysqli_fetch_array($result);
    if (!$result) {
        exit;
        die();
    }
    if (isset($row[$str])){
        return $row[$str];
        die();
     }
}

/* Midtrans Payment Gateway Configuration for IDR */
define('MIDTRANS_SERVER_KEY', 'SB-Mid-server-YOUR_SERVER_KEY'); // Replace with your server key
define('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-YOUR_CLIENT_KEY'); // Replace with your client key  
define('MIDTRANS_IS_PRODUCTION', false); // Set to true for production
define('SITE_CURRENCY', 'IDR');
define('SITE_CURRENCY_SYMBOL', 'Rp');