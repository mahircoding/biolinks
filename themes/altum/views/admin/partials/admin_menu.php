<?php defined('ALTUMCODE') || die() ?>

<nav class="navbar navbar-expand-lg navbar-light admin-top-navbar">
    <a class="navbar-brand" href="<?= url().'dashboard' ?>">
        <?php if($_SERVER['SERVER_NAME'] == whitelabel('url')): ?>
            <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'whitelabel/logo/' . whitelabel('logo') ?>" class="img-fluid navbar-logo" alt="<?= $this->language->global->accessibility->logo_alt ?>" />
        <?php elseif($this->settings->logo != ''): ?>
            <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'logo/' . $this->settings->logo ?>" class="img-fluid navbar-logo" alt="<?= $this->language->global->accessibility->logo_alt ?>" />
        <?php else: ?>
            <?= $this->settings->title ?>
        <?php endif ?>
    </a>

    <ul class="navbar-nav ml-auto">
        <button class="btn navbar-custom-toggler" type="button" id="admin_menu_toggler" aria-controls="main_navbar" aria-expanded="false" aria-label="<?= $this->language->global->accessibility->toggle_navigation ?>">
            <i class="fa fa-fw fa-bars"></i>
        </button>
    </ul>
</nav>
