<?php
use Altum\Middlewares\Authentication;

if(
    !empty($this->settings->ads->footer_biolink)
    && (
        !Authentication::check() ||
        (Authentication::check() && !$this->user->package_settings->no_ads)
    )
): ?>
    <div class="container my-3"><?= $this->settings->ads->footer_biolink ?></div>
<?php endif ?>
