<?php defined('ALTUMCODE') || die() ?>

<div class="position-relative my-3" style="<?= isset($data->link->settings->background_color)&&$data->link->settings->background_color ? 'background-color:'.$data->link->settings->background_color.';' : null ?><?= isset($data->link->settings->background_radius)&&$data->link->settings->background_radius ? 'border-radius:'.$data->link->settings->background_radius.'px;' : null ?>color:<?= $data->link->settings->description_text_color ?>;text-align:left">
	<marquee scrollamount="<?= $data->link->settings->scrollamount ?>" bgcolor style="font-size:24px;"><?= str_replace("\r\n","",$data->link->settings->description) ?></marquee>
</div>

