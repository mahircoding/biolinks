<?php defined('ALTUMCODE') || die() ?>

<div class="position-relative my-3" style="color: <?= $data->link->settings->description_text_color ?>; text-align:left">
	<?= str_replace("\r\n","",$data->link->settings->description) ?>
</div>

