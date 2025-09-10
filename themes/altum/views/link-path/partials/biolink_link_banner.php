<?php defined('ALTUMCODE') || die() ?>

<?php 
$json = json_decode($data->link->settings,true);
$picture_url = $json['picture_url'];
$target_url = $json['target_url'];
?>
<div class="my-3 link-iframe-round">
	<a rel="noreferrer noopener" href="<?= $target_url ?>" target="_blank">
		<img src="<?= $picture_url ?>" width="100%">
	</a>
</div>

