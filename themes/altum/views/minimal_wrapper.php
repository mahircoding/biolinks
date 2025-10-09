<?php defined('ALTUMCODE') || die() ?>

<!DOCTYPE html>
<html lang="<?= $this->language->language_code ?>" dir="<?= $this->language->direction ?>">
<head>
    <title><?= $this->page_title ?></title>
    <base href="<?= SITE_URL ?>">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex, nofollow">
    
    <?php if(!empty($this->settings->favicon)): ?>
        <link href="<?= SITE_URL . UPLOADS_URL_PATH . 'favicon/' . $this->settings->favicon ?>" rel="shortcut icon" />
    <?php endif ?>

    <!-- CSS -->
    <link href="<?= SITE_URL ?>themes/altum/assets/css/fontawesome.min.css?v=<?= PRODUCT_CODE ?>" rel="stylesheet" media="screen">
    <link href="<?= SITE_URL ?>themes/altum/assets/css/bootstrap.min.css?v=<?= PRODUCT_CODE ?>" rel="stylesheet" media="screen">
    <link href="<?= SITE_URL ?>themes/altum/assets/css/custom.css?v=<?= PRODUCT_CODE ?>" rel="stylesheet" media="screen">
    
    <?php if($this->settings->custom_css): ?>
        <style><?= $this->settings->custom_css ?></style>
    <?php endif ?>
    
    <!-- Facebook Pixel Code (if available) -->
    <?php if(isset($this->views['facebook_pixel_head'])): ?>
        <?= $this->views['facebook_pixel_head'] ?>
    <?php endif ?>

    <!-- Custom CSS -->
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        
        .minimal-content {
            min-height: 100vh;
            width: 100%;
            background-color: #72cfca4f;
        }
    </style>
</head>

<body class="<?= $this->settings->theme ?>">
    <div class="minimal-content">
        <?= $this->views['content'] ?>
    </div>

    <!-- JS -->
    <script src="<?= SITE_URL ?>themes/altum/assets/js/jquery.min.js?v=<?= PRODUCT_CODE ?>"></script>
    <script src="<?= SITE_URL ?>themes/altum/assets/js/bootstrap.bundle.min.js?v=<?= PRODUCT_CODE ?>"></script>
    <script src="<?= SITE_URL ?>themes/altum/assets/js/fontawesome.min.js?v=<?= PRODUCT_CODE ?>"></script>
    <script src="<?= SITE_URL ?>themes/altum/assets/js/main.js?v=<?= PRODUCT_CODE ?>"></script>
    
    <?php if($this->settings->custom_js): ?>
        <script><?= $this->settings->custom_js ?></script>
    <?php endif ?>
</body>
</html>
