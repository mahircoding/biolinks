<?php defined('ALTUMCODE') || die() ?>

<form name="update_biolink_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="type" value="biolink" />
    <input type="hidden" name="subtype" value="domain" />
    <input type="hidden" name="link_id" value="<?= $row->link_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label><i class="fa fa-fw fa-signature fa-sm mr-1"></i> <?= $this->language->create_biolink_domain_modal->input->location_url ?></label>
        <input type="text" class="form-control" name="location_url" value="<?= $row->settings->location_url ?>" placeholder="<?= $this->language->create_biolink_domain_modal->input->location_url_placeholder ?>" required="required" />
		<small class="text-muted"><?= str_replace("[This Domain]",url(),$this->language->create_biolink_domain_modal->input->notes) ?></small>
	</div>
	
	<div class="form-group">
		<label><i class="fas fa-fw fa-align-center fa-sm mr-1"></i> <?= $this->language->create_biolink_domain_modal->input->type_height ?></label>
		<select class="form-control" name="type_height" onchange="getshown(this)">
		<option value="0"<?= $row->settings->type_height==0 ? ' selected' : '' ?>>Responsive</option>
		<option value="1"<?= $row->settings->type_height==1 ? ' selected' : '' ?>>Manual</option>
		<option value="2"<?= $row->settings->type_height==2 ? ' selected' : '' ?>>Fullscreen</option>
		</select>
	</div>
	
	<div id="ptypeheight_<?= $row->link_id ?>" class="form-group"<?= $row->settings->type_height==1 ? ' style="display:block"' : ' style="display:none"' ?>>
		<label><i class="fa fa-fw fa-heading fa-sm mr-1"></i> <?= $this->language->create_biolink_domain_modal->input->height ?></label>
		<div class="input-group">
			<input type="text" class="form-control" name="height" value="<?= $row->settings->height=='auto' ? 300 : $row->settings->height ?>" />
			<div class="input-group-append">
				<span class="input-group-text">PX</span>
			</div>
		</div>
	</div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= $this->language->global->update ?></button>
    </div>
</form>

<script>
	function getshown(ele) {
		var el = document.getElementById('ptypeheight_<?= $row->link_id ?>');
		if(ele.value==1)
			el.style.display = 'block';
		else
			el.style.display = 'none';
	}
	
</script>