<?php defined('ALTUMCODE') || die() ?>

<form name="update_biolink_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="type" value="biolink" />
    <input type="hidden" name="subtype" value="pricingtable" />
    <input type="hidden" name="link_id" value="<?= $row->link_id ?>" />

    <div class="notification-container"></div>
	
	<div class="form-group">
		<label><i class="fab fa-fw fa-whatsapp fa-sm mr-1"></i> <?= $this->language->create_biolink_pricingtable_modal->input->phone_number ?></label>
		<input type="text" class="form-control" name="phone_number" value="<?= $row->settings->phone_number ?>" placeholder="<?= $this->language->create_biolink_pricingtable_modal->input->phone_number_placeholder ?>" required="required" />
	</div>
	
	<div data-color-picker="true" data-index="0" class="form-group mb-4">
				<label for="settings_background_color"><?= $this->language->create_biolink_pricingtable_modal->input->hover_color ?></label>
				<input data-picker="val" type="hidden" name="background" class="form-control" value="<?= isset($row->settings->hover_color) ? $row->settings->hover_color : '#2E64CC' ?>" />
				<div id="settings_background_color_float_pickr_0" data-picker="color"></div>
			</div>
	
    <div class="d-flex align-items-center justify-content-center mt-2 mb-2">
		<div class="d-flex align-items-center">
			<div class="btn btn-primary" data-add-pricing="true" data-required="required"><i class="fa fa-image" aria-hidden="true"></i> Add Section</div>
		</div>
	</div>
	
	<div class="form-pricing-container">
		<?php $num_acc=0; foreach($row->settings->pricings as $ky => $pr) {?>
		<div class="form-pricing-item">
			<?= $num_acc>0 ? '<hr>' : ''?>
			<div class="form-group">
				<div class="d-flex justify-content-between">
					<label><i class="fa fa-fw fa-heading fa-sm mr-1"></i> <?= $this->language->create_biolink_pricingtable_modal->input->name ?></label>
					<?php if($num_acc>0){?>
					<a class="text-primary" data-close-pricing="true" href="javascript:;">Delete</a>
					<?php }else{?>
					<div></div>
					<?php }?>
				</div>
				<input type="text" class="form-control" name="name[]" value="<?= $pr->name ?>" placeholder="<?= $this->language->create_biolink_pricingtable_modal->input->name_placeholder ?>" required/>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> <?= $this->language->create_biolink_pricingtable_modal->input->currency ?></label>
						<input type="text" class="form-control" name="currency[]" value="<?= $pr->currency ?>" placeholder="<?= $this->language->create_biolink_pricingtable_modal->input->currency_placeholder ?>" required/>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<label><i class="fa fa-fw fa-heading fa-sm mr-1"></i> <?= $this->language->create_biolink_pricingtable_modal->input->period ?></label>
						<input type="text" class="form-control" name="period[]" value="<?= $pr->period ?>" placeholder="<?= $this->language->create_biolink_pricingtable_modal->input->period_placeholder ?>" required/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> <?= $this->language->create_biolink_pricingtable_modal->input->price ?></label>
						<input type="text" class="form-control" name="price[]" value="<?= $pr->price ?>" placeholder="<?= $this->language->create_biolink_pricingtable_modal->input->price_placeholder ?>" required/>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> <?= $this->language->create_biolink_pricingtable_modal->input->price_strike ?></label>
						<input type="text" class="form-control" name="price_strike[]" value="<?= isset($pr->price_strike) ? $pr->price_strike : '' ?>" placeholder="<?= $this->language->create_biolink_pricingtable_modal->input->price_strike_placeholder ?>"/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<label><i class="fa fa-fw fa-heading fa-sm mr-1"></i> <?= $this->language->create_biolink_pricingtable_modal->input->button_text ?></label>
						<input type="text" class="form-control" name="button_text[]" value="<?= isset($pr->button) ? $pr->button : '' ?>" placeholder="<?= $this->language->create_biolink_pricingtable_modal->input->button_text_placeholder ?>"/>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<label><i class="fa fa-fw fa-heading fa-sm mr-1"></i> <?= $this->language->create_biolink_pricingtable_modal->input->per ?></label>
						<input type="text" class="form-control" name="per_text[]" value="<?= isset($pr->per) ? $pr->per : 'per' ?>" placeholder="<?= $this->language->create_biolink_pricingtable_modal->input->per_placeholder ?>"/>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="d-flex justify-content-between">
					<label><i class="fa fa-fw fa-heading fa-sm mr-1"></i> <?= $this->language->create_biolink_pricingtable_modal->input->features ?></label>
					<div>
						<div data-add-feature="true" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></div>
						<div data-close-feature="true" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></div>
					</div>
				</div>
			</div>
			<div class="form-feature-container">
				<?php foreach($pr->features as $ft) {?>
				<div class="form-feature-item">
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" name="feat_bold[<?= $ky ?>][]" value="<?= $ft->bold ?>" placeholder="<?= $this->language->create_biolink_pricingtable_modal->input->bold_placeholder ?>" required/>
							<input type="text" class="form-control" name="feat_normal[<?= $ky ?>][]" value="<?= $ft->normal ?>" placeholder="<?= $this->language->create_biolink_pricingtable_modal->input->normal_placeholder ?>"/>
						</div>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
		<?php $num_acc++;}?>
	</div>
	
	<div class="d-flex align-items-center justify-content-center mt-2 mb-2">
		<div class="d-flex align-items-center">
			<div class="btn btn-primary" data-add-pricing="true" data-required="required"><i class="fa fa-image" aria-hidden="true"></i> Add Section</div>
		</div>
	</div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= $this->language->global->update ?></button>
    </div>
</form>
