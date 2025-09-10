<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html lang="<?= $this->language->language_code ?>" class="link-html pt-h100">
    <head>
        <?php $title = $this->link->settings->seo->title ?? \Altum\Title::get(); ?>
        <title><?= $title ?></title>
        <base href="<?= SITE_URL; ?>">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta property="og:title" content="<?= $title ?>">
		<meta name="twitter:title" content="<?= $title ?>">
        <?php if($this->link->settings->seo->meta_description): ?>
        <meta name="description" content="<?= $this->link->settings->seo->meta_description ?>">
		<meta property="og:description" content="<?= $this->link->settings->seo->meta_description ?>">
		<meta name="twitter:description" content="<?= $this->link->settings->seo->meta_description ?>">
        <?php endif ?>
		
		<?php if(isset($this->link->settings->seo->meta_image)&&$this->link->settings->seo->meta_image): ?>
        <meta name="og:image" content="<?= SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $this->link->link_id . '/' . $this->link->settings->seo->meta_image ?>">
		<meta name="twitter:image" content="<?= SITE_URL . UPLOADS_URL_PATH . 'galleries/' . $this->link->link_id . '/' . $this->link->settings->seo->meta_image ?>">
		<meta name="twitter:card" content="summary_large_image">
        <?php endif ?>
        <meta property="og:url" content="<?= $this->link->full_url ?>">
        
        <?php if($_SERVER['SERVER_NAME'] == whitelabel('url')): ?>
            <link href="<?= SITE_URL . UPLOADS_URL_PATH . 'whitelabel/favicon/' . whitelabel('favicon') ?>" rel="shortcut icon" />
        <?php elseif(!empty($this->settings->favicon)): ?>
            <link href="<?= SITE_URL . UPLOADS_URL_PATH . 'favicon/' . $this->settings->favicon ?>" rel="shortcut icon" />
        <?php endif ?>

        <?php if(!$this->link->settings->font): ?>
            <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
        <?php endif ?>

        <?php foreach(['bootstrap.min.css', 'custom.css', 'animate.min.css'] as $file): ?>
            <link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen">
        <?php endforeach ?>
		<link rel="stylesheet" href="<?= SITE_URL . ASSETS_URL_PATH . 'css/link-custom.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="<?= SITE_URL . ASSETS_URL_PATH . 'css/page-transition.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="<?= SITE_URL . ASSETS_URL_PATH . 'css/floating-button.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css " rel="stylesheet" media="screen">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" />
		<script src="<?= SITE_URL . ASSETS_URL_PATH ?>js/libraries/jquery.min.js?v=<?= PRODUCT_CODE ?>"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" type="text/javascript"></script>
		
		<script>function listen(t,e,n){if(e.addEventListener)e.addEventListener(t,n,!1);else{if(e.attachEvent)return e.attachEvent("on"+t,n);window.alert("Oops")}}</script>
		
        <?php if($this->link->settings->font): ?>
		<?php $biolink_fonts = require APP_PATH . 'includes/biolink_fonts.php'; ?>
		<link href="https://fonts.googleapis.com/css?family=<?= $biolink_fonts[$this->link->settings->font]['font-family'] ?>&display=swap" rel="stylesheet">
		<style>body{font-family: '<?= $biolink_fonts[$this->link->settings->font]['name'] ?>',sans-serif !important;}</style>
        <?php endif ?>

        <?= \Altum\Event::get_content('head') ?>

        <?php if(!empty($this->settings->head_js)): ?>
            <?= $this->settings->head_js ?>
        <?php endif ?>

        <link rel="canonical" href="<?= $this->link->full_url ?>" />
    </head>

    <?= $this->views['content'] ?>

    <input type="hidden" id="url" name="url" value="<?= url() ?>" />
    <input type="hidden" name="global_token" value="<?= \Altum\Middlewares\Csrf::get('global_token') ?>" />
    <input type="hidden" name="number_decimal_point" value="<?= $this->language->global->number->decimal_point ?>" />
    <input type="hidden" name="number_thousands_separator" value="<?= $this->language->global->number->thousands_separator ?>" />
	
    <?php foreach(['libraries/popper.min.js', 'libraries/bootstrap.min.js', 'libraries/jquery.validate.min.js', 'main.js', 'functions.js', 'libraries/fontawesome.min.js'] as $file): ?>
        <script src="<?= SITE_URL . ASSETS_URL_PATH ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
    <?php endforeach ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
	<script src="<?= SITE_URL . ASSETS_URL_PATH ?>js/libraries/page-transition.js?v=<?= PRODUCT_CODE ?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.1/js.cookie.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
	
	<script>
	$(window).on('load',function(e){
		$('.modal').on('show.bs.modal', function (e) {
			$(this).addClass('fadeInDown');
		}).on('hide.bs.modal', function (e) {
			$(this).removeClass('fadeInDown');
		});
	})
	</script>
	
	<?= \Altum\Event::get_content('javascript') ?>
</html>
