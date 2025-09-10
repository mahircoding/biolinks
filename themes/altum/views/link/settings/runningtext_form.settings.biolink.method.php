<?php defined('ALTUMCODE') || die() ?>

<form name="update_biolink_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="type" value="biolink" />
    <input type="hidden" name="subtype" value="runningtext" />
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
					<label><i class="fa fa-fw fa-paragraph fa-sm mr-1"></i> <?= $this->language->create_biolink_runningtext_modal->input->description ?></label>
					<textarea class="form-control" name="description" rows="5" required> <?= $row->settings->description ?></textarea>
				</div>

				<div class="form-group">
					<label>Speed</label>
					<div data-range="main" data-gr-iframe-target="[data-link-id]" class="row">
						<div class="col-md-9 d-flex align-items-center">
							<input data-range="range" type="range" name="scrollamount" class="custom-range" min="1" step="1" max="20" value="<?= $row->settings->scrollamount ?>">
						</div>
						<div class="col-md-3">
							<input data-range="text" type="number" class="form-control" min="1" step="1" max="20" value="<?= $row->settings->scrollamount ?>">
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane pt-2 pb-2 fade" id="dividerborder" role="tabpanel" aria-labelledby="dividerborder-tab">
				<div data-color-picker="true" class="form-group">
					<label for="style_text_color"><i class="fa fa-fw fa-paint-brush fa-sm mr-1"></i> <?= $this->language->create_biolink_text_modal->input->description_text_color ?></label>
					<input type="hidden" name="description_text_color" data-picker="val" class="form-control" value="<?= $row->settings->description_text_color ?>" required="required" />
					<div id="runningtext_desc_text_color_pickr_<?= $row->link_id ?>" data-picker="color"></div>
				</div>
				
				<div data-color-picker="true" class="form-group">
					<label for="style_background_color"><i class="fa fa-fw fa-paint-brush fa-sm mr-1"></i> Background Color</label>
					<input type="hidden" name="background_color" data-picker="val" class="form-control" value="<?= isset($row->settings->background_color)&&$row->settings->background_color ? $row->settings->background_color : '#FFFFFF00' ?>" required="required" />
					<div id="runningtext_background_color_pickr_<?= $row->link_id ?>" data-picker="color"></div>
				</div>
				
				<div class="form-group">
					<label>Background Radius</label>
					<div data-range="main" data-gr-iframe-target="[data-link-id]" class="row">
						<div class="col-md-9 d-flex align-items-center">
							<input data-range="range" type="range" name="background_radius" class="custom-range" min="0" step="1" max="100" value="<?= isset($row->settings->background_radius)&&$row->settings->background_radius ? $row->settings->background_radius : 0 ?>">
						</div>
						<div class="col-md-3">
							<input data-range="text" type="number" class="form-control" min="0" step="1" max="100" value="<?= isset($row->settings->background_radius)&&$row->settings->background_radius ? $row->settings->background_radius : 0 ?>">
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
