<?php
use Altum\Middlewares\Authentication;

if(
    !empty($this->settings->ads->footer)
    && (
        !Authentication::check() ||
        (Authentication::check() && !$this->user->package_settings->no_ads)
    )
): ?>
    <div class="container my-3"><?= $this->settings->ads->footer ?></div>
<?php endif ?>
