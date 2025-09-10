<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">
    <div class="d-flex flex-column justify-content-center">

        <?php //display_notifications() ?>

        <?php if($this->user->package_is_expired && $this->user->package_id != 'free'): ?>
            <div class="alert alert-info" role="alert">
                <?= $this->language->global->info_message->user_package_is_expired ?>
            </div>
        <?php endif ?>

        <?php if($data->type == 'new'): ?>
			<?php if($data->sales_settings&&$data->sales_settings->sales_link) {
			$st_type = isset($data->sales_settings->sales_type) ? (int)$data->sales_settings->sales_type : 0;
			$st_link = $data->sales_settings->sales_link;
			$sd_type = 'email';
			$sd_contact = $data->admin_email['email'];
			$sd_message = $data->message;
			$sd_text = '';
			
			if($st_type==1)
				$sd_message = urlencode(str_replace('Order ReNew Package',ucfirst(trim($data->sales_settings->sales_link)),urldecode($sd_message)));
			
			if($data->admin_phone) {
				$sd_text = 'whatsapp';
				$sd_name = $data->admin_phone['name'];
				$sd_contact = $data->admin_phone['phone'];
			}
			?>
            <h1 class="h3 text-center"><?= $data->sales_settings->title ? $data->sales_settings->title : $this->language->package->package_expired ?></h1>
            <span class="text-muted text-center"><?= $data->sales_settings->description ? $data->sales_settings->description : $this->language->package->package_order ?></span>
			
			<div class="mt-4">
			
			<div class="text-center mt-4 mb-4"><a rel="noopener nofollow" href="https://wa.me/<?= $sd_contact ?>?text=Halo%20Admin%20Biolink" class="btn btn-primary btn-buy-package" target="_blank"><?= $data->sales_settings->text_button ? $data->sales_settings->text_button : $this->language->package->buy_package ?> (<?= $sd_name ?>)</a></div>
			<?php } else { ?>
			<h1 class="h3 text-center"><?= $this->language->package->package_expired ?></h1>
            <span class="text-muted text-center"><?= $this->language->package->package_order ?></span>
			
			<div class="mt-4">
			<?php if($data->admin_phone) {?>
			<div class="text-center"><h4>WhatsApp ( <span class="text-success"><?= $data->admin_phone['name'] ?></span> ) </h4></div>
			<div class="text-center mb-4"><a href="https://wa.me/<?= $data->admin_phone['phone'] ?>?text=Halo%20Admin%20Biolink" class="btn btn-primary" target="_blank"><?= $this->language->package->send_whatsapp_msg ?> <?= $ap['name'] ?></a></div>
			<?php } 
			if($data->admin_email) {?>
			<div class="text-center"><h4>Email ( <span class="text-success"><?= $data->admin_email['name'] ?></span> ) </h4></div>
			<div class="text-center mb-4"><a href="mailto:<?= $data->admin_email['email'] ?>?subject=Halo%20Admin%20Biolink&body=<?= $data->message ?>" class="btn btn-primary" target="_blank"><?= $this->language->package->send_email_msg ?> <?= $ae['name'] ?></a></div>
			<?php }}?>
			</div>
			<script>var message = "<?= $data->message ?>"</script>

        <?php elseif($data->type == 'upgrade'): ?>

            <h1 class="h3"><?= $this->language->package->header_upgrade ?></h1>
            <span class="text-muted"><?= $this->language->package->subheader_upgrade ?></span>

        <?php elseif($data->type == 'renew'): ?>

            <h1 class="h3"><?= $this->language->package->header_renew ?></h1>
            <span class="text-muted"><?= $this->language->package->subheader_renew ?></span>

        <?php endif ?>


        <div class="margin-top-3 col-12">
           <!-- <?= $this->views['packages'] ?> -->
        </div>

    </div>
	<style>.btn-buy-package{padding:1rem 5rem;font-size:1.25rem;border-radius:50px;text-transform:uppercase}</style>
</div>
