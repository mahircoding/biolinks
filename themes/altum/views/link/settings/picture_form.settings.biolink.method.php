<?php defined('ALTUMCODE') || die() ?>

<form name="update_biolink_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="type" value="biolink" />
    <input type="hidden" name="subtype" value="picture" />
    <input type="hidden" name="link_id" value="<?= $row->link_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
		<div class="d-flex align-items-stretch">
			<div class="flex-grow-1">
				<label><i class="fas fa-fw fa-image fa-sm mr-1"></i> <?= $this->language->create_biolink_picture_modal->input->picture_url ?></label>
				<div class="custom-file">
					<input type="file" class="custom-file-input" data-image="upload" name="image" accept="image/x-png,image/gif,image/jpeg">
					<label class="custom-file-label" for="customFile">Choose file</label>
				</div>
			</div>
			<div class="flex-grow-1 w-100 d-flex align-items-stretch mw-preview ml-2">
				<div class="form-image-preview" style="background-image:url('<?= $row->settings->picture_url ?>')"></div>
			</div>
		</div>
		<small class="text-danger" data-field="image"></small>
	</div>
	
	<div class="form-group">
		<label><i class="fa fa-fw fa-signature fa-sm mr-1"></i> <?= $this->language->create_biolink_picture_modal->input->link_url ?></label>
		<input type="text" class="form-control" name="link_url" value="<?= isset($row->settings->link_url)&&$row->settings->link_url ? $row->settings->link_url : '' ?>" placeholder="<?= $this->language->create_biolink_picture_modal->input->link_url_placeholder ?>" />
	</div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= $this->language->global->update ?></button>
    </div>
</form>
