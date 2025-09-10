<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html lang="<?= $this->language->language_code ?>">
    <head>
		<?php if($_SERVER['SERVER_NAME'] == whitelabel('url')): ?>
        <title>Join & Trial Our New Features - <?= whitelabel('title')?></title>
        <?php elseif(!$_SERVER['SERVER_NAME'] == whitelabel('url')): ?>
        <title><?= \Altum\Title::get() ?></title>
        <?php endif ?>
        <base href="<?= SITE_URL.'dashboard'; ?>">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="content-language" content="<?= $this->language->language_code ?>" />
		<?php if($_SERVER['SERVER_NAME'] == whitelabel('url')): ?>
        <link href="<?= SITE_URL . UPLOADS_URL_PATH . 'whitelabel/favicon/' . whitelabel('favicon') ?>" rel="shortcut icon" />
        <?php elseif(!$_SERVER['SERVER_NAME'] == whitelabel('url')): ?>
        <link href="<?= SITE_URL . UPLOADS_URL_PATH . 'favicon/' . $this->settings->favicon ?>" rel="shortcut icon" />
        <?php endif ?>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,700"/> 
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
		<link rel="stylesheet" href="<?= SITE_URL . ASSETS_URL_PATH . 'templates/templates.base.css?v=' . PRODUCT_CODE ?>">
        <script src="<?= SITE_URL . ASSETS_URL_PATH ?>js/libraries/jquery.min.js?v=<?= PRODUCT_CODE ?>"></script>
	</head>

    <body>
		<?= $this->views['content'] ?>
		<input type="hidden" id="url" name="url" value="<?= url() ?>" />
        <input type="hidden" name="global_token" value="<?= \Altum\Middlewares\Csrf::get('global_token') ?>" />
        <input type="hidden" name="number_decimal_point" value="<?= $this->language->global->number->decimal_point ?>" />
        <input type="hidden" name="number_thousands_separator" value="<?= $this->language->global->number->thousands_separator ?>" />
    
		<script src="<?= SITE_URL . ASSETS_URL_PATH ?>js/libraries/jquery.validate.min.js?v=<?= PRODUCT_CODE ?>"></script>
		<script>
		$(window).on('load', function(e){
			var errors = JSON.parse(<?= json_encode(error_object_notif()); ?>)
			if(errors.length>0) {
				$.each(errors,function(i,j){
					var mg = j.split(':')
					$('[data-field="'+mg[0]+'"]').append('<div class="msg">'+mg[1]+'</div>')
				})
			}
			
			$("form#join_trial").validate({
				// Specify validation rules
				rules: {
				  name: "required",
				  email: "required",
				  phone: "required",
				  password: "required",
				  captcha: "required",
				  accept: "required",
				  name: {
					required: true,
					minlength:3
				  },
				  email: {
					required: true,
					email: true
				  },
				  phone: {
					required: true,
					number: true,
					minlength: 9,
					maxlength: 14
				  },
				  password: {
					required: true,
					minlength: 6
				  },
				  captcha: {
					required: true,
					minlength: 6
				  },
				  accept: {
					required: true,
					minlength: 1
				  }
				},
				// Specify validation error messages
				messages: {
				  name: {
					required: "Masukkan nama panggilan!.",
					minlength: "Nama panggilan minimal 3 karakter"
				  },
				  email: {
					required: "Masukkan email!.",
					email: "Format email salah!."
				  },
				  phone: {
					required: "Masukkan nomor whatsapp!.",
					number: "Nomor whatsapp harus berupa angka!.",
					minlength: "Nomor whatsapp minimal 9 digit!.",
					maxlength: "Nomor whatsapp maksimal 14 digit!."
				  },
				  password: {
					required: "Masukkan password!.",
					minlength: "Password minimal 6 karakter!."
				  },
				  captcha: {
					required: "Masukkan captcha!.",
					minlength: "Captcha minimal 6 karakter!."
				  },
				  accept: {
					required: "Centang untuk menyetujui Syarat & Ketentuan!."
				  }
				},
				errorPlacement: function(error, element) {
					$('[data-field="'+element.attr('name')+'"]').html('').append('<div class="msg">'+error.text()+'</div>')
				},
				success: function(label) {
					$('[data-field="'+label.attr('for')+'"]').html('')
				}
			});
		})
		</script>
	</body>
</html>
