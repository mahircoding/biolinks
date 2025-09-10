<?php
function phoneFixer($number, $code='62', $withCode=true) {
	$phone_number = $number;
	if(preg_match('/[^0|\+'.$code.'|'.$code.'](\d{9,16})/', $number, $match)) {
		if(isset($match[0])) {
			if($withCode)
				$phone_number = $code.$match[0];
			else
				$phone_number = $match[0];
		} else
			$phone_number = '';
	}
	return $phone_number;
}

function censored_text($str) {
	$output = $str;
	if(strlen($str)>10) {
		$count = strlen($str) - 10;
		$output = substr_replace($str, str_repeat('*', $count), 4, $count);
	}
	return $output;
}

function get_list_filter_words() {
	$words = null;
	if(file_exists(UPLOADS_PATH . '/filters.db')) {
		$tmp = [];
		$bad_words = explode("\r\n",file_get_contents(UPLOADS_PATH . '/filters.db'));
		foreach($bad_words as $word) {
			if($word) {
				$word = strtolower(trim($word));
				$word = str_replace("'","\'",$word);
				$word = str_replace("+","\+",$word);
				array_push($tmp,$word);
			}
		}
		$words = $tmp;
	}
	$words = $words ? array_unique($words) : [];
	return $words;
}

function get_raw_filter_words() {
	$words = null;
	if(file_exists(UPLOADS_PATH . '/filters.db')) {
		$words = file_get_contents(UPLOADS_PATH . '/filters.db');
	}
	return $words;
}

function is_link_contains_badwords($contents) {
	$words = get_list_filter_words();
	if($words) {
		$contents = strtolower(strip_tags($contents));
		$contents = str_replace("'","",$contents);
		$contents = str_replace(["\\r\\n","\\n","\r\n","\n","\\t","\t"]," ",$contents);
		$contents = preg_replace("/[[:blank:]]+/"," ",$contents);
		
		$words = array_map('preg_quote',$words);
		
		$matches = array();
		if(preg_match_all('/\b('.implode('|',$words).')\b/i',$contents,$matches)) {
			return true;
		}
	}
	return false;
}

function cleanHTMLBody($string){
	$pre_html = preg_replace("/<html\s(.+?)>/is", "", $string);
	$pre_html = preg_replace("/<body\s(.+?)>/is", "", $pre_html);
	$pre_html = str_replace('<html>','',$pre_html);
	$pre_html = str_replace('</html>','',$pre_html);
	$pre_html = str_replace('<head>','',$pre_html);
	$pre_html = str_replace('</head>','',$pre_html);
	$pre_html = str_replace('<body>','',$pre_html);
	$pre_html = str_replace('</body>','',$pre_html);
	return trim($pre_html);
}

/* Generate chart data for based on the date key and each of keys inside */
function get_chart_data(Array $main_array) {

    $results = [];

    foreach($main_array as $date_label => $data) {

        foreach($data as $label_key => $label_value) {

            if(!isset($results[$label_key])) {
                $results[$label_key] = [];
            }

            $results[$label_key][] = $label_value;

        }

    }

    foreach($results as $key => $value) {
        $results[$key] = '["' . implode('", "', $value) . '"]';
    }

    $results['labels'] = '["' . implode('", "', array_keys($main_array)) . '"]';

    return $results;
}

function get_gravatar($email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = []) {
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5(strtolower(trim($email)));
    $url .= "?s=$s&d=$d&r=$r";

    if ($img) {
        $url = '<img src="' . $url . '"';

        foreach ($atts as $key => $val) {
            $url .= ' ' . $key . '="' . $val . '"';
        }

        $url .= ' />';
    }

    return $url;
}

function get_admin_options_button($type, $target_id, $urladmin='admin') {

    switch($type) {

        case 'user' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="'.$urladmin.'/user-view/' . $target_id . '"><i class="fa fa-fw fa-eye"></i> ' . \Altum\Language::get()->global->view . '</a>
                            <a class="dropdown-item" href="'.$urladmin.'/user-update/' . $target_id . '"><i class="fa fa-fw fa-pencil-alt"></i> ' . \Altum\Language::get()->global->edit . '</a>
                            <a class="dropdown-item" data-confirm="' . \Altum\Language::get()->global->info_message->confirm_delete . '" href="'.$urladmin.'/users/delete/' . $target_id . \Altum\Middlewares\Csrf::get_url_query() . '"><i class="fa fa-fw fa-times"></i> ' . \Altum\Language::get()->global->delete . '</a>
                            <a href="#" data-toggle="modal" data-target="#user_login" data-user-id="' . $target_id . '" class="dropdown-item"><i class="fa fa-fw fa-sign-in-alt"></i> ' . \Altum\Language::get()->global->login . '</a>
                        </div>
                    </a>
                </div>';

            break;


        case 'link' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" data-confirm="' . \Altum\Language::get()->global->info_message->confirm_delete . '" href="admin/links/delete/' . $target_id . \Altum\Middlewares\Csrf::get_url_query() . '"><i class="fa fa-fw fa-times"></i> ' . \Altum\Language::get()->global->delete . '</a>
                        </div>
                    </a>
                </div>';

            break;

        case 'domain' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="admin/domain-update/' . $target_id . '"><i class="fa fa-fw fa-pencil-alt"></i> ' . \Altum\Language::get()->global->edit . '</a>
                            <a class="dropdown-item" data-confirm="' . \Altum\Language::get()->global->info_message->confirm_delete . '" href="admin/domains/delete/' . $target_id . \Altum\Middlewares\Csrf::get_url_query() . '"><i class="fa fa-fw fa-times"></i> ' . \Altum\Language::get()->global->delete . '</a>
                        </div>
                    </a>
                </div>';

            break;


        case 'pages_category' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="admin/pages-category-update/' . $target_id . '"><i class="fa fa-fw fa-pencil-alt"></i> ' . \Altum\Language::get()->global->edit . '</a>
                            <a href="#" data-toggle="modal" data-target="#pages_category_delete" data-pages-category-id="' . $target_id . '" class="dropdown-item"><i class="fa fa-fw fa-times"></i> ' . \Altum\Language::get()->global->delete . '</a>
                        </div>
                    </a>
                </div>';

            break;

        case 'page' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="admin/page-update/' . $target_id . '"><i class="fa fa-fw fa-pencil-alt"></i> ' . \Altum\Language::get()->global->edit . '</a>
                            <a href="#" data-toggle="modal" data-target="#page_delete" data-page-id="' . $target_id . '" class="dropdown-item"><i class="fa fa-fw fa-times"></i> ' . \Altum\Language::get()->global->delete . '</a>
                        </div>
                    </a>
                </div>';

            break;

        case 'package' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="admin/package-update/' . $target_id . '"><i class="fa fa-fw fa-pencil-alt"></i> ' . \Altum\Language::get()->global->edit . '</a>
                            
                            ' . (is_numeric($target_id) ?
                                '<a class="dropdown-item" data-confirm="' . \Altum\Language::get()->global->info_message->confirm_delete . '" href="admin/packages/delete/' . $target_id . \Altum\Middlewares\Csrf::get_url_query() . '"><i class="fa fa-fw fa-times"></i> ' . \Altum\Language::get()->global->delete . '</a>'
                                : null) . '
                            
                        </div>
                    </a>
                </div>';

            break;

        case 'code' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="admin/code-update/' . $target_id . '"><i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> ' . \Altum\Language::get()->global->edit . '</a>
                            <a href="#" data-toggle="modal" data-target="#code_delete" data-code-id="' . $target_id . '" class="dropdown-item"><i class="fa fa-fw fa-sm fa-times mr-1"></i> ' . \Altum\Language::get()->global->delete . '</a>
                        </div>
                    </a>
                </div>';

            break;

    }
}

/* Helper to output proper and nice numbers */
function nr($number, $decimals = 0, $extra = false) {

    if($extra) {
        $formatted_number = $number;
        $touched = false;

        if(!$touched && (!is_array($extra) || (is_array($extra) && in_array('B', $extra)))) {

            if($number > 999999999) {
                $formatted_number = number_format($number / 1000000000, $decimals, \Altum\Language::get()->global->number->decimal_point, \Altum\Language::get()->global->number->thousands_separator) . 'B';

                $touched = true;
            }

        }

        if(!$touched && (!is_array($extra) || (is_array($extra) && in_array('M', $extra)))) {

            if($number > 999999) {
                $formatted_number = number_format($number / 1000000, $decimals, \Altum\Language::get()->global->number->decimal_point, \Altum\Language::get()->global->number->thousands_separator) . 'M';

                $touched = true;
            }

        }

        if(!$touched && (!is_array($extra) || (is_array($extra) && in_array('K', $extra)))) {

            if($number > 999) {
                $formatted_number = number_format($number / 1000, $decimals, \Altum\Language::get()->global->number->decimal_point, \Altum\Language::get()->global->number->thousands_separator) . 'K';

                $touched = true;
            }

        }

        if($decimals > 0) {
            $dotzero = '.' . str_repeat('0', $decimals);
            $formatted_number = str_replace($dotzero, '', $formatted_number);
        }

        return $formatted_number;
    }

    if($number == 0) {
        return 0;
    }

    return number_format($number, $decimals, \Altum\Language::get()->global->number->decimal_point, \Altum\Language::get()->global->number->thousands_separator);
}

function get_domain($url) {

    $host = parse_url($url, PHP_URL_HOST);

    $host = explode('.', $host);

    /* Return only the last 2 array values combined */
    return implode('.', array_slice($host, -2, 2));
}

function get_ip() {
    if(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {

        if(strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            return trim(reset($ips));
        } else {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

    } else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
        return $_SERVER['REMOTE_ADDR'];
    } else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    return '';
}

function get_device_type($user_agent) {
    $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
    $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';

    if(preg_match_all($mobile_regex, $user_agent)) {
        return 'mobile';
    } else {

        if(preg_match_all($tablet_regex, $user_agent)) {
            return 'tablet';
        } else {
            return 'desktop';
        }

    }
}

function get_country_from_country_code($code) {
    $code = strtoupper($code);

    $country_list = [
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BQ' => 'Bonaire, Saint Eustatius and Saba',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CW' => 'Curacao',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'CD' => 'Democratic Republic of the Congo',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'TL' => 'East Timor',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'CI' => 'Ivory Coast',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'XK' => 'Kosovo',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Laos',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'KP' => 'North Korea',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'CG' => 'Republic of the Congo',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russia',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SX' => 'Sint Maarten',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'KR' => 'South Korea',
        'SS' => 'South Sudan',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syria',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'VI' => 'U.S. Virgin Islands',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Minor Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VA' => 'Vatican',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    ];

    if(!isset($country_list[$code])) {
        return $code;
    } else {
        return $country_list[$code];
    }
}

/* Dump & die */
function dd($string = null) {
    var_dump($string);
    die();
}

function get_wl_parent($user_id){
	$link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
	
	$whitelabel_id = 0;
	$ids_insert = 0;
	$stopped = false;
	for($i=0;$i<3;$i++){
		if($i==0) {
			$rs_wl = mysqli_query($link, "SELECT `user_id`, `whitelabel`, `whitelabel_id`, `ids_insert` FROM `users` where `user_id` = {$user_id}");
			while($rw_wl = mysqli_fetch_assoc($rs_wl)){
				if($rw_wl['whitelabel']=='Y') {
					$whitelabel_id = $rw_wl['whitelabel_id'];
					$stopped = true;
					break;
				} else
					$ids_insert = $rw_wl['ids_insert'];
			}			
		} else {
			$rs_wl = mysqli_query($link, "SELECT `user_id`, `whitelabel`, `whitelabel_id`, `ids_insert` FROM `users` where `user_id` = {$ids_insert}");
			while($rw_wl = mysqli_fetch_assoc($rs_wl)){
				if($rw_wl['whitelabel']=='Y') {
					$whitelabel_id = $rw_wl['whitelabel_id'];
					$stopped = true;
					break;
				} else
					$ids_insert = $rw_wl['ids_insert'];
			}
		}
		
		if($stopped)
			break;
	}
	
	return $whitelabel_id;
}

function update_wl_id($wl_id,$user,$is_zero=false) {
	$link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
	
	if($is_zero)
		$wl_id = $user->whitelabel_id;
	
	$wl = null;
	$rs_wl = mysqli_query($link, "SELECT * FROM `whitelabel` where `id` = {$wl_id}");
	while($rw_wl = mysqli_fetch_assoc($rs_wl)){
		$wl[$rw_wl['user_id']] = $rw_wl['id'];
	}
	$us = null;
	foreach($wl as $a => $w) {
		$rs_us = mysqli_query($link, "SELECT `user_id`,`ids_insert` FROM `users` WHERE `user_id` = {$user->user_id}");
		while($rw_us = mysqli_fetch_assoc($rs_us)){
			$us[$rw_us['user_id']] = array("id" => $w, "ids" => $rw_us['ids_insert']);
		}
	}
	
	foreach($us as $k => $w) {
		if($is_zero) $w['id'] = 0;
		mysqli_query($link, "UPDATE `users` SET `whitelabel_id` = {$w['id']} WHERE `user_id` = {$k}");
		mysqli_query($link, "UPDATE `links` SET `whitelabel_id` = {$w['id']} WHERE `user_id` = {$k} AND (type = 'link' OR subtype = 'base' OR subtype = 'link')");
	}
	
	$ss = null;
	foreach($us as $b => $s) {
		$rs_ss = mysqli_query($link, "SELECT `user_id`,`ids_insert` FROM `users` WHERE `ids_insert` = {$user_id} AND `whitelabel` <> 'Y'");
		while($rw_ss = mysqli_fetch_assoc($rs_ss)){
			$ss[$rw_ss['user_id']] = array("id" => $s['id'], "ids" => $rw_ss['ids_insert']);
		}
	}
	foreach($ss as $k => $w) {
		if($is_zero) $w['id'] = 0;
		mysqli_query($link, "UPDATE `users` SET `whitelabel_id` = {$w['id']} WHERE `user_id` = {$k}");
		mysqli_query($link, "UPDATE `links` SET `whitelabel_id` = {$w['id']} WHERE `user_id` = {$k} AND (type = 'link' OR subtype = 'base' OR subtype = 'link')");
	}
	
	$cs = null;
	foreach($ss as $b => $s) {
		$rs_cs = mysqli_query($link, "SELECT `user_id`,`ids_insert` FROM `users` WHERE `ids_insert` = {$b} AND `whitelabel` <> 'Y'");
		while($rw_cs = mysqli_fetch_assoc($rs_cs)){
			$cs[$rw_cs['user_id']] = array("id" => $s['id'], "ids" => $rw_ss['ids_insert']);
		}
	}
	foreach($cs as $k => $w) {
		if($is_zero) $w['id'] = 0;
		mysqli_query($link, "UPDATE `users` SET `whitelabel_id` = {$w['id']} WHERE `user_id` = {$k}");
		mysqli_query($link, "UPDATE `links` SET `whitelabel_id` = {$w['id']} WHERE `user_id` = {$k} AND (type = 'link' OR subtype = 'base' OR subtype = 'link')");
	}
	
	mysqli_close($link); // finally, close the connection
}

function cloudflare_get_zone($domain_name,$whitelabel=false) {
	
	if($whitelabel) {
		$acc_id = 'd950e6c0fc5f8e7a197661b42ea208cf';
	} else {
		$acc_id = 'd950e6c0fc5f8e7a197661b42ea208cf';
	}
	
	// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/zones?name='.$domain_name.'&status=active&account.id='.$acc_id.'&page=1&per_page=50&order=status&direction=desc&match=all');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


	$headers = [
		"X-Auth-Email: akhmada845@gmail.com",
		"X-Auth-Key: 55c60e307d82c6470b4396a890f32e678fd47",
		"Content-Type: application/json"
	];
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	$result = json_decode($result);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return $result;
}

function cloudflare_create_zone($domain_name,$whitelabel=false) {
	
	if($whitelabel) {
		$acc_id = 'd950e6c0fc5f8e7a197661b42ea208cf';
	} else {
		$acc_id = 'd950e6c0fc5f8e7a197661b42ea208cf';
	}
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/zones');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"name\":\"".$domain_name."\",\"account\":{\"id\":\"".$acc_id."\"},\"jump_start\":true,\"type\":\"full\"}");
	
	$headers = [
		"X-Auth-Email: akhmada845@gmail.com",
		"X-Auth-Key: 55c60e307d82c6470b4396a890f32e678fd47",
		"Content-Type: application/json"
	];
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	$result = json_decode($result);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return $result;
}

function cloudflare_edit_zone($zone_id) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/zones/'.$zone_id);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');

	curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"paused\":false,\"plan\":{\"id\":\"0feeeeeeeeeeeeeeeeeeeeeeeeeeeeee\"},\"type\":\"full\"}");

	$headers = [
		"X-Auth-Email: akhmada845@gmail.com",
		"X-Auth-Key: 55c60e307d82c6470b4396a890f32e678fd47",
		"Content-Type: application/json"
	];
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	$result = json_decode($result);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return $result;
}

function cloudflare_delete_zone($zone_id) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/zones/'.$zone_id);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');


	$headers = [
		"X-Auth-Email: akhmada845@gmail.com",
		"X-Auth-Key: 55c60e307d82c6470b4396a890f32e678fd47",
		"Content-Type: application/json"
	];
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	$result = json_decode($result);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return $result;

}

function cloudflare_add_dns($zone_id,$type,$name,$value) {
	// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/zones/'.$zone_id.'/dns_records');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"type\":\"".$type."\",\"name\":\"".$name."\",\"content\":\"".$value."\",\"ttl\":3600,\"priority\":10,\"proxied\":true}");

	$headers = [
		"X-Auth-Email: akhmada845@gmail.com",
		"X-Auth-Key: 55c60e307d82c6470b4396a890f32e678fd47",
		"Content-Type: application/json"
	];
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	$result = json_decode($result);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return $result;
}

function cloudflare_change_ssl($zone_id,$value="full") {
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/zones/'.$zone_id.'/settings/ssl');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');

	curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"value\":\"".$value."\"}");

	$headers = [
		"X-Auth-Email: akhmada845@gmail.com",
		"X-Auth-Key: 55c60e307d82c6470b4396a890f32e678fd47",
		"Content-Type: application/json"
	];
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	$result = json_decode($result);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return $result;
}

function cloudflare_certificate_pack($zone_id) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/zones/'.$zone_id.'/ssl/certificate_packs?status=all');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


	$headers = [
		"X-Auth-Email: akhmada845@gmail.com",
		"X-Auth-Key: 55c60e307d82c6470b4396a890f32e678fd47",
		"Content-Type: application/json"
	];
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	$result = json_decode($result);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return $result;
}

function get_final_url_shortened($url) {
	$curl = curl_init();

	curl_setopt_array($curl, [
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 1,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => [
		  'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
		  'Accept-Language: en-US,en;q=0.5',
		  'Upgrade-Insecure-Requests: 1',
		  'Sec-Fetch-Dest: document',
		  'Sec-Fetch-Mode: navigate',
		  'Sec-Fetch-Site: none',
		  'Sec-Fetch-User: ?1',
		  'Connection: keep-alive',
		  'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:122.0) Gecko/20100101 Firefox/122.0',
		],
	]);

	$response = curl_exec($curl);
	curl_close($curl);
	  
	$tiktok_url = null;
	preg_match_all('/"seo.abtest":\{"canonical":"(.*?)","/',$response,$matches);
	if(isset($matches[1])&&$matches[1]) {
		$tiktok_url = json_decode('{"url":"'.$matches[1][0].'"}',true);
		$tiktok_url = $tiktok_url['url'];
	}
	
	return $tiktok_url;
}

function ro_api_rotator() {
	$base_rotator = $temp_rotator = $base_count = [];
	$base_key = null;
	$base_api = [
		'f8c9777c807e12be084a770f23c1a573',
		'42d9164584a209caad6f635480f01b35',
		'f6f979838be300e30956d6e818f92b50',
		'be782cf2051371a12f172b335f2f5570',
		'279b99bc488886ed1b5d6230ea523180',
		'74111c700017b82130bc9fb355da20a5',
		'97b0911394a92d1649567b5886b75116',
		'69b4cf50e64a0bf1d36ad1a56cfdb50b',
		'8fd98cce0d52c4b132bf9bdc3b87baac',
		'2c49dd86faae48761112a3d5c819fea5',
		'acf3850aed66d4265f2bd32355f9c887'
	];
	if(file_exists('ro_count.db')) {
		$base_counts = json_decode(file_get_contents('ro_count.db'),true);
		foreach($base_api as $key => $api) {
			$base_rotator[] = ['api' => $api, 'count' => $base_counts[$key]];
			$temp_rotator[] = ['api' => $api, 'count' => $base_counts[$key]];
			$base_count[] = $base_counts[$key];
		}
	} else {
		foreach($base_api as $api) {
			$base_rotator[] = ['api' => $api, 'count' => 0];
			$temp_rotator[] = ['api' => $api, 'count' => 0];
			$base_count[] = 0;
		}
	}
	
	shuffle($temp_rotator);
	
	usort($temp_rotator,function($first,$second){
		return $first['count'] > $second['count'];
	});
	
	$base_key = $temp_rotator[0];
	
	foreach($base_rotator as $key => $rotate) {
		if($rotate['api']==$base_key['api']) {
			$base_count[$key]++;
		}
	}
	
	file_put_contents('ro_count.db',json_encode($base_count));
	
	return $base_key['api'];
	
}

function ro_starter_api_rotator() {
	$base_rotator = $temp_rotator = $base_count = [];
	$base_key = null;
	$rand_num = mt_rand(1,10);
	$base_api = explode(",",string_decode(file_get_contents(UPLOADS_PATH . 'zrokey/starter_ro_api_'.str_pad($rand_num,2,"0",STR_PAD_LEFT).'.ro'), BASE_DOMAIN . '.bQ2tFb5H'));

	if(file_exists(UPLOADS_PATH . 'zrokey/starter_ro_count_'.str_pad($rand_num,2,"0",STR_PAD_LEFT).'.db')) {
		$base_counts = json_decode(file_get_contents(UPLOADS_PATH . 'zrokey/starter_ro_count_'.str_pad($rand_num,2,"0",STR_PAD_LEFT).'.db'),true);
		foreach($base_api as $key => $api) {
			$base_rotator[] = ['api' => $api, 'count' => $base_counts[$key]];
			$temp_rotator[] = ['api' => $api, 'count' => $base_counts[$key]];
			$base_count[] = $base_counts[$key];
		}
	} else {
		foreach($base_api as $api) {
			$base_rotator[] = ['api' => $api, 'count' => 0];
			$temp_rotator[] = ['api' => $api, 'count' => 0];
			$base_count[] = 0;
		}
	}
	
	shuffle($temp_rotator);
	
	usort($temp_rotator,function($first,$second){
		return $first['count'] > $second['count'];
	});
	
	$base_key = $temp_rotator[0];
	
	foreach($base_rotator as $key => $rotate) {
		if($rotate['api']==$base_key['api']) {
			$base_count[$key]++;
		}
	}
	
	file_put_contents(UPLOADS_PATH . 'zrokey/starter_ro_count_'.str_pad($rand_num,2,"0",STR_PAD_LEFT).'.db',json_encode($base_count));
	
	return $base_key['api'];
}