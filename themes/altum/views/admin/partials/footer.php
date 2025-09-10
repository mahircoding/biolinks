<?php defined('ALTUMCODE') || die() ?>

<footer class="margin-top-3">
    <?php if($_SERVER['SERVER_NAME'] == whitelabel('url')): ?>
    <span class="text-muted"><?= 'Copyright &copy; ' . date('Y') . '. All rights reserved. Product by 🔥 <a href="https://'.whitelabel('url').'/" target="_blank">'. whitelabel('title') .'</a>' ?></span>
    <?php else: ?>
    <span class="text-muted"><?= 'Copyright &copy; ' . date('Y') . ' ' . $this->settings->title . '. All rights reserved. Product by 🔥 <a href="https://' . BASE_DOMAIN . '/" target="_blank">Biolink</a>' ?></span>
    <?php endif ?>
</footer>
