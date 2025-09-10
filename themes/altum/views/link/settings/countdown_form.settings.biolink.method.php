<?php defined('ALTUMCODE') || die() ?>

<form name="update_biolink_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="type" value="biolink" />
    <input type="hidden" name="subtype" value="countdown" />
    <input type="hidden" name="link_id" value="<?= $row->link_id ?>" />

    <div class="notification-container"></div>
	
	<div class="tabs" data-bs-tabs="main">
		<ul class="nav nav-tabs" data-bs-tabs="tab" role="tablist">
			<a class="nav-tab nav-link active" href="javascript:;">Content</a>
			<a class="nav-tab nav-link" href="javascript:;">Style</a>
		</ul>
		
		<div class="tab-content" data-bs-tabs="content">
			<div class="tab-pane pt-2 pb-2 fade show active">
	
				<div class="form-group">
					<label><i class="fa fa-fw fa-map-marker fa-sm mr-1"></i> <?= $this->language->create_biolink_countdown_modal->input->title ?></label>
					<input type="text" class="form-control" name="title" value="<?= $row->settings->title ?>" placeholder="<?= $this->language->create_biolink_countdown_modal->input->title_placeholder ?>" />
				</div>
				
				<div class="form-group">
					<label><i class="fa fa-fw fa-clock fa-sm mr-1"></i> <?= $this->language->create_biolink_countdown_modal->input->timer ?></label>
					<input type="text" class="form-control" name="end_date" value="<?= $row->settings->end_date ?>" placeholder="<?= $this->language->create_biolink_countdown_modal->input->timer_placeholder ?>" autocomplete="off" required="required">
				</div>
				
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> <?= $this->language->create_biolink_countdown_modal->input->days ?></label>
							<input type="text" class="form-control" name="days" value="<?= (isset($row->settings->days)&&!empty($row->settings->days) ? $row->settings->days : '') ?>" placeholder="<?= $this->language->create_biolink_countdown_modal->input->days_placeholder ?>" />
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> <?= $this->language->create_biolink_countdown_modal->input->hours ?></label>
							<input type="text" class="form-control" name="hours" value="<?= (isset($row->settings->hours)&&!empty($row->settings->hours) ? $row->settings->hours : '') ?>" placeholder="<?= $this->language->create_biolink_countdown_modal->input->hours_placeholder ?>" />
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> <?= $this->language->create_biolink_countdown_modal->input->minutes ?></label>
							<input type="text" class="form-control" name="minutes" value="<?= (isset($row->settings->minutes)&&!empty($row->settings->minutes) ? $row->settings->minutes : '') ?>" placeholder="<?= $this->language->create_biolink_countdown_modal->input->minutes_placeholder ?>" />
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> <?= $this->language->create_biolink_countdown_modal->input->seconds ?></label>
							<input type="text" class="form-control" name="seconds" value="<?= (isset($row->settings->seconds)&&!empty($row->settings->seconds) ? $row->settings->seconds : '') ?>" placeholder="<?= $this->language->create_biolink_countdown_modal->input->seconds_placeholder ?>" />
						</div>
					</div>
				</div>
			</div>
			
			<div class="tab-pane pt-2 pb-2 fade" id="dividerborder" role="tabpanel" aria-labelledby="dividerborder-tab">
				<div data-color-picker="true" class="form-group">
					<label for="style_title_color"><i class="fa fa-fw fa-paint-brush fa-sm mr-1"></i> Title Color</label>
					<input type="hidden" name="title_color" data-picker="val" class="form-control" value="<?= isset($row->settings->title_color)&&$row->settings->title_color ? $row->settings->title_color : '#333333' ?>" required="required" />
					<div id="countdown_title_color_pickr_<?= $row->link_id ?>" data-picker="color"></div>
				</div>
				<div class="form-group">
					<label>Title Size</label>
					<div data-range="main" data-gr-iframe-target="[data-link-id]" class="row">
						<div class="col-md-9 d-flex align-items-center">
							<input data-range="range" type="range" name="title_size" class="custom-range" min="0" step="1" max="100" value="<?= isset($row->settings->title_size)&&$row->settings->title_size ? $row->settings->title_size : 24 ?>" required>
						</div>
						<div class="col-md-3">
							<input data-range="text" type="number" class="form-control" min="14" step="1" max="100" value="<?= isset($row->settings->title_size)&&$row->settings->title_size ? $row->settings->title_size : 24 ?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div data-color-picker="true" class="form-group">
							<label for="style_time_color"><i class="fa fa-fw fa-paint-brush fa-sm mr-1"></i> Time Color</label>
							<input type="hidden" name="time_color" data-picker="val" class="form-control" value="<?= isset($row->settings->time_color)&&$row->settings->time_color ? $row->settings->time_color : '#FFFFFF' ?>" required="required" />
							<div id="countdown_time_color_pickr_<?= $row->link_id ?>" data-picker="color"></div>
						</div>
					</div>
					<div class="col-lg-6">
						<div data-color-picker="true" class="form-group">
							<label for="style_background_color"><i class="fa fa-fw fa-paint-brush fa-sm mr-1"></i> Background Color</label>
							<input type="hidden" name="background_color" data-picker="val" class="form-control" value="<?= isset($row->settings->background_color)&&$row->settings->background_color ? $row->settings->background_color : '#FFFFFF30' ?>" required="required" />
							<div id="countdown_background_color_pickr_<?= $row->link_id ?>" data-picker="color"></div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label>Background Radius</label>
					<div data-range="main" data-gr-iframe-target="[data-link-id]" class="row">
						<div class="col-md-9 d-flex align-items-center">
							<input data-range="range" type="range" name="background_radius" class="custom-range" min="0" step="1" max="100" value="<?= isset($row->settings->background_radius)&&$row->settings->background_radius ? $row->settings->background_radius : 5 ?>" required>
						</div>
						<div class="col-md-3">
							<input data-range="text" type="number" class="form-control" min="0" step="1" max="100" value="<?= isset($row->settings->background_radius)&&$row->settings->background_radius ? $row->settings->background_radius : 5 ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label>Background Opacity</label>
					<div data-range="main" data-gr-iframe-target="[data-link-id]" class="row">
						<div class="col-md-9 d-flex align-items-center">
							<input data-range="range" type="range" name="background_opacity" class="custom-range" min="0.1" step="0.1" max="1" value="<?= isset($row->settings->background_opacity)&&$row->settings->background_opacity ? $row->settings->background_opacity : 0.5 ?>" required>
						</div>
						<div class="col-md-3">
							<input data-range="text" type="number" class="form-control" min="0.1" step="0.1" max="1" value="<?= isset($row->settings->background_opacity)&&$row->settings->background_opacity ? $row->settings->background_opacity : 0.5 ?>">
						</div>
					</div>
				</div>
			</div>
			
		</div>
		
	</div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= $this->language->global->update ?></button>
    </div>
</form>
