<?php defined('ALTUMCODE') || die() ?>

<div class="my-3 link-iframe-round">
	<div id="myCarousel" class="carousel slide <?= $data->link->settings->slider_position ?>" data-interval="<?= $data->link->settings->slider_timer ?>" data-ride="carousel">
		<ol class="carousel-indicators">
			<?php $no=1; foreach($data->link->settings->images as $im) {?>
			<li data-target="#myCarousel" data-slide-to="0" class="<?= $no==1 ? ' active' : '' ?>"></li>
			<?php $no++; }?>
		</ol>
		<div class="carousel-inner">
			<?php $no=1; foreach($data->link->settings->images as $im) {?>
			<div class="carousel-item<?= $no==1 ? ' active' : '' ?>">
				<img class="d-block w-100" src="<?= str_replace('http://','https://',$im->image_url) ?>" alt="First slide">
			</div>
			<?php $no++; }?>
		</div>
		<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
</div>

