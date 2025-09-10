<?php defined('ALTUMCODE') || die() ?>

<div id="countdown_<?= $data->link->link_id ?>" class="my-4">
    <h2 class="h4"><?= $data->link->settings->title ?></h2>
    <div class="countdown-container container" data-countdown="<?= date('m d, Y H:i:s', strtotime($data->link->settings->end_date)) ?>">
	   <div class="clock row">
		  <!-- days --> 
		  <div class="clock-item clock-days countdown-time-value col-3">
			 <div class="wrap">
				<div class="inner">
				   <div id="canvas_days" class="clock-canvas"></div>
				   <div class="text">
					  <p class="val">00</p>
					  <p class="type-days type-time"><?= $data->link->settings->days ?></p>
				   </div>
				</div>
			 </div>
		  </div>
		  <!-- hours --> 
		  <div class="clock-item clock-hours countdown-time-value col-3">
			 <div class="wrap">
				<div class="inner">
				   <div id="canvas_hours" class="clock-canvas"></div>
				   <div class="text">
					  <p class="val">00</p>
					  <p class="type-hours type-time"><?= $data->link->settings->hours ?></p>
				   </div>
				</div>
			 </div>
		  </div>
		  <!-- minutes --> 
		  <div class="clock-item clock-minutes countdown-time-value col-3">
			 <div class="wrap">
				<div class="inner">
				   <div id="canvas_minutes" class="clock-canvas"></div>
				   <div class="text">
					  <p class="val">00</p>
					  <p class="type-minutes type-time"><?= $data->link->settings->minutes ?></p>
				   </div>
				</div>
			 </div>
		  </div>
		  <!-- seconds --> 
		  <div class="clock-item clock-seconds countdown-time-value col-3">
			 <div class="wrap">
				<div class="inner">
				   <div id="canvas_seconds" class="clock-canvas canvas_seconds"></div>
				   <div class="text">
					  <p class="val">00</p>
					  <p class="type-seconds type-time"><?= $data->link->settings->seconds ?></p>
				   </div>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
</div>
<?php ob_start();?><style><?= isset($data->link->settings->title_color)&&$data->link->settings->title_color ? '#countdown_'.$data->link->link_id.' .h4{color:'.$data->link->settings->title_color.';'.(isset($data->link->settings->title_size)&&$data->link->settings->title_size ? 'font-size:'.$data->link->settings->title_size.'px;' : null).'}' : null ?><?= isset($data->link->settings->time_color)&&$data->link->settings->time_color ? '#countdown_'.$data->link->link_id.' .clock-item .text{color:'.$data->link->settings->time_color.'}' : null ?><?= isset($data->link->settings->background_color)&&$data->link->settings->background_color ? '#countdown_'.$data->link->link_id.' .clock-canvas{background-color:'.$data->link->settings->background_color.';'.(isset($data->link->settings->background_radius)&&$data->link->settings->background_radius ? 'border-radius:'.$data->link->settings->background_radius.'%;' : null).(isset($data->link->settings->background_opacity)&&$data->link->settings->background_opacity ? 'opacity:'.$data->link->settings->background_opacity.';' : null).'}' : null ?></style>
<?php \Altum\Event::add_content(ob_get_clean(), 'head'); ?>