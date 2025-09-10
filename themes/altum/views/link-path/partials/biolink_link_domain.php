<?php defined('ALTUMCODE') || die() ?>

<div class="<?= $data->type_height!=2 ? 'my-3 ' : ''?>embed-responsive embed-responsive-16by9 link-iframe-round" style="<?= $data->type_height==2 ? 'position: absolute;z-index:9999; height: 100%' : 'height:'.$data->height ?>">
    <iframe class="embed-responsive-item" style="height: <?= $data->height ?>" scrolling="yes" frameborder="no" src="<?= $data->embed ?>"></iframe>
</div>

