<?php defined('ALTUMCODE') || die() ?>

<form name="update_biolink_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="type" value="biolink" />
    <input type="hidden" name="subtype" value="cartform" />
    <input type="hidden" name="link_id" value="<?= $row->link_id ?>" />

    <div class="notification-container"></div>
	
	<div class="form-group">
		<label><i class="fa fa-fw fa-heading fa-sm mr-1"></i> <?= $this->language->create_biolink_cartform_modal->input->title ?></label>
		<input type="text" class="form-control" name="title" value="<?= $row->settings->title ?>"" placeholder="<?= $this->language->create_biolink_cartform_modal->input->title_placeholder ?>" required="required" />
	</div>
	
	<div class="form-group">
		<div class="d-flex align-items-stretch">
			<div class="flex-grow-1">
				<label><i class="fas fa-fw fa-image fa-sm mr-1"></i> <?= $this->language->create_biolink_cartform_modal->input->photo ?></label>
				<div class="custom-file">
					<input type="file" class="custom-file-input" data-image="upload" name="image" accept="image/x-png,image/gif,image/jpeg">
					<label class="custom-file-label" for="customFile">Choose file</label>
				</div>
			</div>
			<div class="flex-grow-1 w-100 d-flex align-items-stretch mw-preview ml-2">
				<div class="form-image-preview" style="background-image:url('<?= $row->settings->photo ?>')"></div>
			</div>
		</div>
		<small class="text-danger" data-field="image"></small>
	</div>

	<div class="form-group">
		<label><i class="fa fa-fw fa-paragraph fa-sm mr-1"></i> <?= $this->language->create_biolink_cartform_modal->input->description ?></label>
		<textarea class="form-control" name="description" row="3" placeholder="<?= $this->language->create_biolink_cartform_modal->input->description_placeholder ?>" required="required"><?= $row->settings->description ?>"</textarea>
	</div>
	
	<div class="form-group">
		<label><i class="fa fa-fw fa-tag fa-sm mr-1"></i> <?= $this->language->create_biolink_cartform_modal->input->price ?></label>
		<input type="text" class="form-control" name="price" value="<?= $row->settings->price ?>"" placeholder="<?= $this->language->create_biolink_cartform_modal->input->price_placeholder ?>" required="required" />
	</div>
	
	<div class="form-group">
		<label><i class="fa fa-fw fa-list fa-sm mr-1"></i> <?= $this->language->create_biolink_cartform_modal->input->min_quantity ?></label>
		<input type="number" class="form-control" name="min_qty" min="1" value="<?= $row->settings->min_qty ?>" required="required" />
	</div>
	
	<div class="form-group">
		<label><i class="fa fa-fw fa-list fa-sm mr-1"></i> <?= $this->language->create_biolink_cartform_modal->input->max_quantity ?></label>
		<input type="number" class="form-control" name="max_qty" min="1" value="<?= $row->settings->max_qty ?>"" required="required" />
	</div>
	
	<div class="form-group">
		<label><i class="fab fa-fw fa-whatsapp fa-sm mr-1"></i> <?= $this->language->create_biolink_cartform_modal->input->wa_number ?></label>
		<input type="text" class="form-control" name="wa_number" value="<?= $row->settings->wa_number ?>" placeholder="<?= $this->language->create_biolink_cartform_modal->input->wa_number_placeholder ?>" required="required" />
	</div>

	<div class="form-group">
		<label><i class="fa fa-fw fa-paragraph fa-sm mr-1"></i> <?= $this->language->create_biolink_cartform_modal->input->wa_message ?></label>
		<textarea class="form-control" name="wa_message" row="3" required="required" placeholder="<?= $this->language->create_biolink_cartform_modal->input->wa_message_placeholder ?>"><?= stripcslashes($row->settings->wa_message) ?></textarea>
	</div>
	
	<div class="custom-control custom-switch mb-3">
        <input id="schedule_<?= $row->link_id ?>" name="schedule" type="checkbox" class="custom-control-input" <?= $row->settings->schedule ? 'checked="true"' : null ?>>
        <label class="custom-control-label" for="schedule_<?= $row->link_id ?>"><?= $this->language->create_biolink_cartform_modal->input->schedule ?></label>
        <small class="form-text text-muted"><?= $this->language->create_biolink_cartform_modal->input->schedule_notes ?></small>
    </div>
	
	<div class="row mt-3 schedule_container">
        <div class="col">
            <div class="form-group">
				<label><i class="fa fa-fw fa-clock fa-sm mr-1"></i> <?= $this->language->create_biolink_cartform_modal->input->start_date ?></label>
                <input type="text" class="form-control" name="start_date" value="<?= $row->settings->start_date ?>" placeholder="Start Date" autocomplete="off">
            </div>
        </div>

        <div class="col">
            <div class="form-group">
                <label><i class="fa fa-fw fa-clock fa-sm mr-1"></i> <?= $this->language->create_biolink_cartform_modal->input->end_date ?></label>
                <input type="text" class="form-control" name="end_date" value="<?= $row->settings->end_date ?>" placeholder="End Date" autocomplete="off">
            </div>
        </div>
    </div>
	
    <div class="form-group">
        <label><i class="fa fa-fw fa-paragraph fa-sm mr-1"></i> <?= $this->language->create_biolink_link_modal->input->name ?></label>
        <input type="text" name="name" class="form-control" value="<?= $row->settings->name ?>" />
    </div>

    <div class="form-group">
        <label><i class="fa fa-fw fa-globe fa-sm mr-1"></i> <?= $this->language->create_biolink_link_modal->input->icon ?></label>
        <div class="input-group">
            <span class="input-group-prepend">
                <button class="btn btn-secondary" data-icon="<?= $row->settings->icon ?>" data-iconset="fontawesome5" role="iconpicker"></button>
            </span>

            <input type="text" name="icon" class="form-control" value="<?= $row->settings->icon ?>" placeholder="<?= $this->language->create_biolink_link_modal->input->icon_placeholder ?>" />
        </div>
        <small class="text-muted"><?= $this->language->create_biolink_link_modal->input->icon_help ?></small>
    </div>

    <div class="<?= !$this->user->package_settings->custom_colored_links ? 'container-disabled': null ?>">
        <div class="form-group">
            <label><i class="fa fa-fw fa-paint-brush fa-sm mr-1"></i> <?= $this->language->create_biolink_link_modal->input->text_color ?></label>
            <input type="hidden" name="text_color" class="form-control" value="<?= $row->settings->text_color ?>" required="required" />
            <div class="text_color_pickr"></div>
        </div>

        <div class="form-group">
            <label><i class="fa fa-fw fa-fill fa-sm mr-1"></i> <?= $this->language->create_biolink_link_modal->input->background_color ?></label>
            <input type="hidden" name="background_color" class="form-control" value="<?= $row->settings->background_color ?>" required="required" />
            <div class="background_color_pickr"></div>
        </div>

        <div class="custom-control custom-switch mr-3 mb-3">
            <input
                    type="checkbox"
                    class="custom-control-input"
                    id="outline_<?= $row->link_id ?>"
                    name="outline"
                <?= $row->settings->outline ? 'checked="true"' : null ?>
            >
            <label class="custom-control-label clickable" for="outline_<?= $row->link_id ?>"><?= $this->language->create_biolink_link_modal->input->outline ?></label>
        </div>

        <div class="form-group">
            <label><?= $this->language->create_biolink_link_modal->input->border_radius ?></label>
            <select name="border_radius" class="form-control">
                <option value="straight" <?= $row->settings->border_radius == 'straight' ? 'selected="true"' : null ?>><?= $this->language->create_biolink_link_modal->input->border_radius_straight ?></option>
                <option value="round" <?= $row->settings->border_radius == 'round' ? 'selected="true"' : null ?>><?= $this->language->create_biolink_link_modal->input->border_radius_round ?></option>
                <option value="rounded" <?= $row->settings->border_radius == 'rounded' ? 'selected="true"' : null ?>><?= $this->language->create_biolink_link_modal->input->border_radius_rounded ?></option>
            </select>
        </div>

        <div class="form-group">
            <label><?= $this->language->create_biolink_link_modal->input->animation ?></label>
            <select name="animation" class="form-control">
                <option value="false" <?= !$row->settings->animation ? 'selected="true"' : null ?>>-</option>
                <option value="bounce" <?= $row->settings->animation == 'bounce' ? 'selected="true"' : null ?>>bounce</option>
                <option value="tada" <?= $row->settings->animation == 'tada' ? 'selected="true"' : null ?>>tada</option>
                <option value="wobble" <?= $row->settings->animation == 'wobble' ? 'selected="true"' : null ?>>wobble</option>
                <option value="swing" <?= $row->settings->animation == 'swing' ? 'selected="true"' : null ?>>swing</option>
                <option value="shake" <?= $row->settings->animation == 'shake' ? 'selected="true"' : null ?>>shake</option>
                <option value="rubberBand" <?= $row->settings->animation == 'rubberBand' ? 'selected="true"' : null ?>>rubberBand</option>
                <option value="pulse" <?= $row->settings->animation == 'pulse' ? 'selected="true"' : null ?>>pulse</option>
                <option value="flash" <?= $row->settings->animation == 'flash' ? 'selected="true"' : null ?>>flash</option>
            </select>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= $this->language->global->update ?></button>
    </div>
</form>
