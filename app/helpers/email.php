<?php

function get_email_template($email_template_subject_array = [], $email_template_subject, $email_template_body_array = [], $email_template_body) {

    $email_template_subject = str_replace(
        array_keys($email_template_subject_array),
        array_values($email_template_subject_array),
        $email_template_subject
    );

    $email_template_body = str_replace(
        array_keys($email_template_body_array),
        array_values($email_template_body_array),
        $email_template_body
    );

    return (object) [
        'subject' => $email_template_subject,
        'body' => $email_template_body
    ];
}

function send_server_mail($to, $from, $title, $content) {

    $headers = "From: " . strip_tags($from) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    /* Check if receipient is array or not */
    $to_processed = $to;

    if(is_array($to)) {
        $to_processed = '';

        foreach($to as $address) {
            $to_processed .= ',' . $address;
        }

    }

    return mail($to_processed, $title, $content, $headers);
}

function send_mail($settings, $to, $title, $content, $test = false) {

    /* Templating for the title */
    $replacers = [
        '{{WEBSITE_TITLE}}' => $settings->title
    ];

    $title = str_replace(
        array_keys($replacers),
        array_values($replacers),
        $title
    );

    /* Template and content preparing */
    $email_template_raw = file_get_contents(THEME_PATH . 'views/partials/email.html');

    $replacers = [
        '{{CONTENT}}'   => $content,
        '{{URL}}'       => url(),
        '{{WEBSITE_TITLE}}' => (!empty(whitelabel('title')) ? whitelabel('title') : $settings->title),
        '{{HEADER}}'    => '<a href="' . url() . '">' . (!empty(whitelabel('title')) ? '<h2>'.whitelabel('title').'</h2>' : '<h2>' . $settings->title .  '</h2>') . '</a>',
        '{{FOOTER}}'    => 'Copyright © <a href="' . url() . '">' . trim_url(url()) . '</a>'
    ];

    $email_template = str_replace(
        array_keys($replacers),
        array_values($replacers),
        $email_template_raw
    );


    if(!empty($settings->smtp->host)) {

        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer();
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->SMTPDebug = 0;

            if ($settings->smtp->encryption != '0') {
                $mail->SMTPSecure = $settings->smtp->encryption;
            }

            $mail->SMTPAuth = $settings->smtp->auth;
            $mail->isHTML(true);

            $mail->Host = $settings->smtp->host;
            $mail->Port = $settings->smtp->port;
            $mail->Username = $settings->smtp->username;
            $mail->Password = $settings->smtp->password;

            $mail->setFrom($settings->smtp->from, $settings->title);
            $mail->addReplyTo($settings->smtp->from, $settings->title);

            /* Check if receipient is array or not */
            if(is_array($to)) {
                foreach($to as $address) {
                    $mail->addAddress($address);
                }
            } else {
                $mail->addAddress($to);
            }

            $mail->Subject = $title;

            $mail->Body = $email_template;
            $mail->AltBody = strip_tags($email_template);

            $send = $mail->send();

            return $test ? $mail : $send;

        } catch (Exception $e) {

            return $test ? $mail : false;

        }

    } else {
        return send_server_mail($to, $settings->smtp->from, $title, $email_template);
    }

}

function send_mail_mailketing($settings, $to, $title, $content)  {
	$api_token = $settings->smtp_mailketing->api_token; //silahkan copy dari api token mailketing
	$from_name = $settings->smtp_mailketing->from_name; //nama pengirim
	$from_email = $settings->smtp_mailketing->from_email; //email pengirim
	$recipient = $to; //penerima email
	
	/* Templating for the title */
    $replacers = [
        '{{WEBSITE_TITLE}}' => $settings->title
    ];

    $title = str_replace(
        array_keys($replacers),
        array_values($replacers),
        $title
    );

    /* Template and content preparing */
    $email_template_raw = file_get_contents(THEME_PATH . 'views/partials/email.html');

    $replacers = [
        '{{CONTENT}}'   => $content,
        '{{URL}}'       => url(),
        '{{WEBSITE_TITLE}}' => (!empty(whitelabel('title')) ? whitelabel('title') : $settings->title),
        '{{HEADER}}'    => '<a href="' . url() . '">' . (!empty(whitelabel('title')) ? '<h2>'.whitelabel('title').'</h2>' : '<h2>' . $settings->title .  '</h2>') . '</a>',
        '{{FOOTER}}'    => 'Copyright © <a href="' . url() . '">' . trim_url(url()) . '</a>'
    ];

    $email_template = str_replace(
        array_keys($replacers),
        array_values($replacers),
        $email_template_raw
    );
	
	$params = [
		'from_name' => $from_name,
		'from_email' => $from_email,
		'recipient' => $recipient,
		'subject' => $title,
		'content' => $email_template,
		'api_token' => $api_token
	];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$settings->smtp_mailketing->host);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec ($ch);
	curl_close ($ch);
	return $output;
}