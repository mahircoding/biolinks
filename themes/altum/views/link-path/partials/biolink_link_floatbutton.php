<?php defined('ALTUMCODE') || die() ?>

<div id="circularMenu" class="circular-menu<?= $data->link->settings->position_class ?>">
	<div class="floating-btn" style="background-color:<?= $data->link->settings->background_color ?>;color:<?= $data->link->settings->text_color ?>"><i class="fa fa-plus"></i></div>
	
	<div class="items-wrapper">
		<?php foreach($data->link->settings->configs as $c) {?>
		<a rel="noopener nofollow" href="<?= $c->link_url ?>" class="menu-item none" style="background-color:<?= $c->background_color ?>" data-toggle="tooltip" data-placement="top" title="<?= $c->link_title ?>" target="_blank"><i class="<?= $c->icon ?>" style="color:<?= $c->text_color ?>"></i></a>
		<?php }?>
	</div>
</div>

