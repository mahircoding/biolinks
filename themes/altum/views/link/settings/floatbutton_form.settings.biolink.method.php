<?php defined('ALTUMCODE') || die() ?>

<form name="update_biolink_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="type" value="biolink" />
    <input type="hidden" name="subtype" value="floatbutton" />
    <input type="hidden" name="link_id" value="<?= $row->link_id ?>" />

    <div class="notification-container"></div>
	
	<div class="d-flex align-items-center justify-content-center mt-2 mb-2">
		<div class="d-flex align-items-center">
			<div class="btn btn-primary" data-add-floating="true" data-required="required"><i class="fa fa-image" aria-hidden="true"></i> Add Button</div>
		</div>
	</div>
	
	<div class="form-floating-button-container">
		<?php $num_ico=1; foreach($row->settings->configs as $ic) {?>
		<div class="form-floating-button-item">
			<div class="form-group">
				<div class="d-flex justify-content-between">
					<label><i class="fa fa-fw fa-globe fa-sm mr-1"></i> <?= $this->language->create_biolink_floatbutton_modal->input->icon_button ?></label>
					<div></div>
				</div>
				<div class="input-group">
					<span class="input-group-prepend">
						<button class="btn btn-secondary" data-icon="" data-iconset="fontawesome5" role="iconpicker"></button>
					</span>

					<input type="text" name="icon[]" class="form-control" value="<?= $ic->icon ?>" placeholder="<?= $this->language->create_biolink_floatbutton_modal->input->icon_button_placeholder ?>" />
				</div>
				<small class="text-muted"><?= $this->language->create_biolink_link_modal->input->icon_help ?></small>
			</div>
			
			<div data-color-picker="true" data-index="0" class="form-group">
				<label for="settings_background_color"><?= $this->language->create_biolink_floatbutton_modal->input->background_color ?></label>
				<input data-picker="val" type="hidden" name="background[]" class="form-control" value="<?= isset($ic->background_color) ? $ic->background_color : '#fd4235' ?>" />
				<div id="settings_background_color_float_pickr_0" data-picker="color"></div>
			</div>
			
			<div data-color-picker="true" data-index="0" class="form-group">
				<label for="settings_text_color"><?= $this->language->create_biolink_floatbutton_modal->input->text_color ?></label>
				<input data-picker="val" type="hidden" name="text[]" class="form-control" value="<?= isset($ic->text_color) ? $ic->text_color : '#ffffff' ?>" />
				<div id="settings_text_color_float_pickr_0" data-picker="color"></div>
			</div>
			
			<div class="form-group">
				<label><i class="fa fa-fw fa-signature fa-sm mr-1"></i> <?= $this->language->create_biolink_floatbutton_modal->input->link_url ?></label>
				<input type="text" class="form-control" name="link_url[]" value="<?= $ic->link_url ?>" required="required" placeholder="<?= $this->language->create_biolink_floatbutton_modal->input->link_url_placeholder ?>" />
			</div>
			
			<div class="form-group">
				<label><i class="fa fa-fw fa-signature fa-sm mr-1"></i> <?= $this->language->create_biolink_floatbutton_modal->input->link_title ?></label>
				<input type="text" class="form-control" name="link_title[]" value="<?= $ic->link_title ?>" required="required" placeholder="<?= $this->language->create_biolink_floatbutton_modal->input->link_title_placeholder ?>" />
			</div>
		</div>
		<?php $num_ico++;}?>
	</div>
	
	<div class="form-group">
		<label><i class="fas fa-fw fa-snowflake fa-sm mr-1"></i> <?= $this->language->create_biolink_floatbutton_modal->input->position ?></label>
		<select class="form-control" name="position">
		  <option value="left"<?= $row->settings->position=='left' ? ' selected' : '' ?>><?= $this->language->create_biolink_floatbutton_modal->position->left ?></option>
		  <option value="center"<?= $row->settings->position=='center' ? ' selected' : '' ?>><?= $this->language->create_biolink_floatbutton_modal->position->center ?></option>
		  <option value="right"<?= $row->settings->position=='right' ? ' selected' : '' ?>><?= $this->language->create_biolink_floatbutton_modal->position->right ?></option>
		</select>
	</div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= $this->language->global->update ?></button>
    </div>
</form>
