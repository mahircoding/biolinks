<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html lang="<?= $this->language->language_code ?>">
    <head>
        <title><?= \Altum\Title::get() ?></title>
        <base href="<?= SITE_URL.'dashboard'; ?>">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="content-language" content="<?= $this->language->language_code ?>" />
        <?php if($_SERVER['SERVER_NAME'] == whitelabel('url')): ?>
        <link href="<?= SITE_URL . UPLOADS_URL_PATH . 'whitelabel/favicon/' . whitelabel('favicon') ?>" rel="shortcut icon" />
        <?php elseif(!$_SERVER['SERVER_NAME'] == whitelabel('url')): ?>
        <link href="<?= SITE_URL . UPLOADS_URL_PATH . 'favicon/' . $this->settings->favicon ?>" rel="shortcut icon" />
        <?php endif ?>

        <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">

        <?php foreach([\Altum\ThemeStyle::get_file(), 'custom.css', 'link-custom.css', 'animate.min.css'] as $file): ?>
            <link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
        <?php endforeach ?>
		<script src="<?= SITE_URL . ASSETS_URL_PATH ?>js/libraries/jquery.min.js?v=<?= PRODUCT_CODE ?>"></script>

        <?= \Altum\Event::get_content('head') ?>

        <?php if(!empty($this->settings->custom->head_js)): ?>
            <?= $this->settings->custom->head_js ?>
        <?php endif ?>

        <?php if(!empty($this->settings->custom->head_css)): ?>
            <style><?= $this->settings->custom->head_css ?></style>
        <?php endif ?>
    </head>

    <body class="<?= \Altum\Routing\Router::$controller_settings['body_white'] ? 'bg-white' : null ?>" data-theme-style="<?= \Altum\ThemeStyle::get() ?>">
		<?= $this->views['menu'] ?>
		<?php if(isset($this->user) && $this->user && (!(bool)$this->user->esc_package || ((bool)$this->user->esc_package && strtotime($this->user->esc_expired) < strtotime('NOW')))): ?>
		<!--
		<div class="packages-ads">
			<div class="bg"></div>
			<div class="bg bg2"></div>
			<div class="bg bg3"></div>
			<div class="content">
				<div class="slider flex-grow-1">
					<div class="slide text-truncate">
						<span class="text-warning">Special Offers</span> 3 Fitur Unggulan, Smart AI, Custom Domain dan Toko Online 
					</div>
					<div class="slide text-truncate">
						<span class="text-warning">Smart AI</span> Membantu dalam membuat konten halamanmu
					</div>
					<div class="slide text-truncate">
						<span class="text-warning">Custom Domain</span> Ganti dengan domainmu sendiri agar lebih keren
					</div>
					<div class="slide text-truncate">
						<span class="text-warning">Toko Online</span> Buat Tokomu sendiri untuk menjual produkmu
					</div>
				</div>
				<div>
					<a class="order" href="https://cambia.co.id/product/3-fitur-berbayar-biolink/" target="_blank">Klik Disini</a>
				</div>
			</div>
		</div>
		-->
		<?php else:?>
		<!--
		<div class="packages-ads">
			<div class="bg"></div>
			<div class="bg bg2"></div>
			<div class="bg bg3"></div>
			<div class="content">
				<div class="slider flex-grow-1">
					<div class="slide text-truncate">
						<span class="text-warning">Spesial VCard</span> ProfilQu.id telah meluncurkan VCard kini telah hadir
					</div>
					<div class="slide text-truncate">
						<span class="text-warning">Apa itu VCard</span> Kartu bisnis atau pribadi elektronik dengan kemudahan penggunaannya
					</div>
					<div class="slide text-truncate">
						<span class="text-warning">Keuntungan VCard</span> Kemudahan membagikan kontak dan informasi bisnis dengan orang lain
					</div>
					<div class="slide text-truncate">
						<span class="text-warning">VCard-Qu</span> Buat VCard-mu sendiri atau untuk keperluan bisnismu, buruan tunggu apalagi
					</div>
				</div>
				<div>
					<a class="order" href="https://cambia.co.id/product/profilqu/" target="_blank">Klik Disini</a>
				</div>
			</div>
		</div>
		-->
		<?php endif;?>
        <main class="animated fadeIn">

            <?= $this->views['content'] ?>

        </main>
		<style>
		.packages-ads{position:relative;margin:0 auto;max-width:1140px;min-height:60px;background-color:#ccc;border-radius:0 0 .25rem .25rem;overflow:hidden}.packages-ads .bg{animation:3s ease-in-out infinite alternate slide;background-image:linear-gradient(-60deg,#6c3 50%,#09f 50%);bottom:0;left:-50%;opacity:.5;position:absolute;right:-50%;top:0;height:100%}.packages-ads .bg2{animation-direction:alternate-reverse;animation-duration:4s}.bg3{animation-duration:5s}.packages-ads .content{position:absolute;display:flex;align-items:center;top:0;left:0;width:100%;height:100%;padding:1rem;font-weight:700;color:#fff}.packages-ads .text-warning{color:#2b1b56 !important}.packages-ads .order{display:inline-block;text-decoration:none;font-weight:400;text-align:center;vertical-align:middle;user-select:none;padding:.375rem .75rem;font-size:1rem;line-height:1.5;border-radius:50rem;color:#fff;background-color:#b23838;border-color:#b23838;min-width:94.766px}.packages-ads .order:hover{background-color:#741e1e;border:##741e1e}.packages-ads .content .slider{position:relative;display:flex;align-items:center;width:100%;height:60px;overflow:hidden}.packages-ads .content .slider .slide{position:absolute;left:0;width:100%;height:100;opacity:0;padding-right:.5rem;animation:16s infinite round}.packages-ads .content .slider .slide:first-child{animation-delay:0s}.packages-ads .content .slider .slide:nth-child(2){animation-delay:4s}.packages-ads .content .slider .slide:nth-child(3){animation-delay:8s}.packages-ads .content .slider .slide:nth-child(4){animation-delay:12s}.header{padding:1.6rem 0 3rem!important}
		@keyframes slide {
			0% {
				transform:translateX(-25%);
			}
			100% {
				transform:translateX(25%);
			}
		}
		@keyframes round {
			25%{opacity:1;}
			40%{opacity:0;}
		}
		</style>
        <?php if(\Altum\Routing\Router::$controller_key != 'index'): ?>
            <?php 
				if($_SERVER['SERVER_NAME']==BASE_DOMAIN){
				require THEME_PATH . 'views/partials/ads_footer.php'; 
				}
			?>
        <?php endif ?>

        <?= $this->views['footer'] ?>

        <?= \Altum\Event::get_content('modals') ?>

        <input type="hidden" id="url" name="url" value="<?= url() ?>" />
        <input type="hidden" name="global_token" value="<?= \Altum\Middlewares\Csrf::get('global_token') ?>" />
        <input type="hidden" name="number_decimal_point" value="<?= $this->language->global->number->decimal_point ?>" />
        <input type="hidden" name="number_thousands_separator" value="<?= $this->language->global->number->thousands_separator ?>" />

        <?php foreach(['libraries/popper.min.js', 'libraries/bootstrap.min.js', 'main.js', 'functions.js', 'libraries/fontawesome.min.js', 'libraries/clipboard.min.js'] as $file): ?>
            <script src="<?= SITE_URL . ASSETS_URL_PATH ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
        <?php endforeach ?>
		
		<script>
		$(window).on('load',function(e){
			$('[data-send-msg="email"]').off('click').on('click', function(e) {
				if($(this).attr('href')!='javascript:;') {
			        var lnk = window.open($(this).attr('href'), '_blank');
    				lnk.focus();
    				
    				e.preventDefault();
    				return false;
			    }
				
				var email = window.open('mailto:'+$(this).data('contact')+'ReNew%20Order%20Package&body='+message, '_blank');
				email.focus();
				
				e.preventDefault();
			})
			$('[data-send-msg="whatsapp"]').off('click').on('click', function(e) {
				var wa_url = 'https://wa.me/';
				
				if($(this).attr('href')!='javascript:;') {
			        var lnk = window.open($(this).attr('href'), '_blank');
    				lnk.focus();
    				
    				e.preventDefault();
    				return false;
			    }
				
				var wa = window.open(wa_url+$(this).data('contact')+'?text='+message, '_blank');
				wa.focus();
				
				e.preventDefault();
			})
			$('.modal').on('show.bs.modal', function (e) {
				$(this).addClass('fadeInDown');
			}).on('hide.bs.modal', function (e) {
				$(this).removeClass('fadeInDown');
			});
		})
		</script>
		
        <?= \Altum\Event::get_content('javascript') ?>
    </body>
</html>
