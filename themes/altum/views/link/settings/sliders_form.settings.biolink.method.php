<?php defined('ALTUMCODE') || die() ?>

<form name="update_biolink_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="type" value="biolink" />
    <input type="hidden" name="subtype" value="sliders" />
    <input type="hidden" name="link_id" value="<?= $row->link_id ?>" />

    <div class="notification-container"></div>
	
    <div class="d-flex align-items-center justify-content-center mt-2 mb-2">
		<div class="d-flex align-items-center">
			<div class="btn btn-primary" data-add-image="true" data-required="required"><i class="fa fa-image" aria-hidden="true"></i> Add Image</div>
		</div>
	</div>
	
	<div class="form-images-container">
		<?php $num_img=0; foreach($row->settings->images as $im) {?>
		<div class="form-group">
			<div class="d-flex align-items-stretch">
				<div class="flex-grow-1">
					<label><i class="fas fa-fw fa-image fa-sm mr-1"></i> <?= $this->language->create_biolink_sliders_modal->input->image_url ?></label>
					<div class="custom-file">
						<input type="file" class="custom-file-input" data-image="upload" name="images[]" accept="image/x-png,image/gif,image/jpeg">
						<label class="custom-file-label" for="customFile">Choose file</label>
					</div>
				</div>
				<div class="flex-grow-1 w-100 d-flex align-items-stretch mw-preview ml-2">
					<div class="form-image-preview" style="background-image:url('<?= $im->image_url ?>')"></div>
					<?php if($num_img>1) {?>
					<div class="form-image-close" data-close-image="true">&times;</div>
					<?php }?>
				</div>
			</div>
			<small class="text-danger" data-field="images.<?= $num_img ?>"></small>
		</div>
		<?php $num_img++; }?>
	</div>
	
	<div class="form-group">
		<label><i class="fas fa-fw fa-snowflake fa-sm mr-1"></i> <?= $this->language->create_biolink_sliders_modal->input->animation ?></label>
		<select class="form-control" name="slider_animation">
		  <option value="left"<?= $row->settings->slider_animation=='left' ? ' selected' : '' ?>><?= $this->language->create_biolink_sliders_modal->animation->left ?></option>
		  <option value="right"<?= $row->settings->slider_animation=='right' ? ' selected' : '' ?>><?= $this->language->create_biolink_sliders_modal->animation->right ?></option>
		  <option value="top"<?= $row->settings->slider_animation=='top' ? ' selected' : '' ?>><?= $this->language->create_biolink_sliders_modal->animation->top ?></option>
		  <option value="bottom"<?= $row->settings->slider_animation=='bottom' ? ' selected' : '' ?>><?= $this->language->create_biolink_sliders_modal->animation->bottom ?></option>
		</select>
	</div>
	
	<div class="form-group">
		<label><?= $this->language->create_biolink_sliders_modal->input->timer ?> (Second)</label>
		<div data-range="main" data-gr-iframe-target="[data-link-id]" class="row">
			<div class="col-md-9 d-flex align-items-center">
				<input data-range="range" type="range" name="slider_timer" class="custom-range" min="0.5" step="0.5" max="10" value="<?= $row->settings->slider_timer ?>" required>
			</div>
			<div class="col-md-3">
				<input data-range="text" type="number" class="form-control" min="0.5" step="0.5" max="10" value="<?= $row->settings->slider_timer ?>">
			</div>
		</div>
	</div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= $this->language->global->update ?></button>
    </div>
</form>
