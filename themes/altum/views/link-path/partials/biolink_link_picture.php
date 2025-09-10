<?php defined('ALTUMCODE') || die() ?>

<div class="my-3 link-iframe-round">
	<?php if(isset($data->link->settings->link_url)&&!empty($data->link->settings->link_url)) {?>
	<a rel="noopener nofollow" href="<?= $data->link->settings->link_url?>" target="_blank">
	<?php }?>
	<img src="<?= str_replace('http://','https://',$data->link->settings->picture_url) ?>" width="100%">
	<?php if(isset($data->link->settings->link_url)&&!empty($data->link->settings->link_url)) {?>
	</a>
	<?php }?>
</div>

