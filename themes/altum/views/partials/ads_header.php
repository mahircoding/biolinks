<?php
use Altum\Middlewares\Authentication;

if(
    !empty($this->settings->ads->header)
    && (
        !Authentication::check() ||
        (Authentication::check() && !$this->user->package_settings->no_ads)
    )
): ?>
    <div class="container my-3"><?= $this->settings->ads->header ?></div>
<?php endif ?>
