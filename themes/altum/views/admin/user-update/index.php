<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <div class="d-flex align-items-center">
        <h1 class="h3 mr-3"><i class="fa fa-fw fa-xs fa-user text-gray-700"></i> <?= $this->language->admin_user_update->header ?></h1>

        <?= get_admin_options_button('user', $data->user->user_id) ?>
    </div>
</div>

<?php display_notifications() ?>

<div class="card border-0 shadow-sm mt-5">
    <div class="card-body">

        <form action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="form-group">
                <label><?= $this->language->admin_user_update->main->name ?></label>
                <input type="text" name="name" class="form-control" value="<?= $data->user->name ?>" />
            </div>

            <div class="form-group">
                <label><?= $this->language->admin_user_update->main->email ?></label>
                <input type="text" name="email" class="form-control" value="<?= $data->user->email ?>" />
            </div>

            <div class="form-group">
                <label><?= $this->language->admin_user_update->main->status ?></label>

                <select class="form-control" name="status">
                    <option value="1" <?php if($data->user->active == 1) echo 'selected' ?>><?= $this->language->admin_user_update->main->status_active ?></option>
                    <option value="0" <?php if($data->user->active == 0) echo 'selected' ?>><?= $this->language->admin_user_update->main->status_disabled ?></option>
                </select>
            </div>

            <div class="form-group">
                <label><?= $this->language->admin_user_update->main->type ?></label>
			
			<?php if($this->user->whitelabel == 'Y' || $this->user->superagency == 'Y'){?>
				<select class="form-control" name="type">
                    <option value="0" <?php if($data->user->type == 0) echo 'selected' ?>><?= $this->language->admin_user_update->main->type_user ?></option>
					<option value="4" <?php if($data->user->agency == 'Y') echo 'selected' ?>>Agency</option>
					<option value="5" <?php if($data->user->subagency == 'Y') echo 'selected' ?>>Sub Agency</option>
                </select>
                <input type="hidden" name="ids_insert" value="<?= $this->user->user_id ?>">
            <?php } elseif($this->user->agency == 'Y'){?>
                <select class="form-control" name="type">
                    <option value="0" <?php if($data->user->type == 0) echo 'selected' ?>><?= $this->language->admin_user_update->main->type_user ?></option>
					<option value="5" <?php if($data->user->subagency == 'Y') echo 'selected' ?>>Sub Agency</option>
                </select>
                <input type="hidden" name="ids_insert" value="<?= $this->user->user_id ?>">
			<?php } elseif($this->user->subagency == 'Y'){?>
                <select class="form-control" name="type">
                    <option value="0" <?php if($data->user->type == 0) echo 'selected' ?>><?= $this->language->admin_user_update->main->type_user ?></option>
				</select>
                <input type="hidden" name="ids_insert" value="<?= $this->user->user_id ?>">
            <?php } else {?>
                <select class="form-control" name="type">
                    <option value="1" <?php if($data->user->type == 1) echo 'selected' ?>><?= $this->language->admin_user_update->main->type_admin ?></option>
                    <option value="0" <?php if($data->user->type == 0) echo 'selected' ?>><?= $this->language->admin_user_update->main->type_user ?></option>
                    <option value="2" <?php if($data->user->whitelabel == 'Y') echo 'selected' ?>>Whitelabel</option>
					<option value="3" <?php if($data->user->superagency == 'Y') echo 'selected' ?>>Super Agency</option>
					<option value="4" <?php if($data->user->agency == 'Y') echo 'selected' ?>>Agency</option>
					<option value="5" <?php if($data->user->subagency == 'Y') echo 'selected' ?>>Sub Agency</option>
                </select>
				<small class="text-muted"><?= $this->language->admin_user_update->main->type_help ?></small>
                <input type="hidden" name="ids_insert" value="">
            <?php }?>
            </div>
			
			<?php if($this->user->type == 1 && (empty($this->user->whitelabel) && empty($this->user->superagency) && empty($this->user->agency) && empty($this->user->subagency))){?>
			<div class="form-group btype"<?= $data->user->type == 1 && $data->user->whitelabel == 'Y' ? ' style="display:block"' : ' style="display:none"' ?>>
                <label>Domain White Label</label>
				<select class="form-control" name="domain_whitelabel">
					<option value="">Pick Domain</option>
					<?php if($data->domain_wl->num_rows>0) {
					while($dw=$data->domain_wl->fetch_object()) {
					?>
					<option value="<?= $dw->id ?>"<?= $data->user->whitelabel_id==$dw->id ? ' selected' : '' ?>><?= $dw->url ?></option>
					<?php }}?>
				</select>
			</div>
			<?php }?>
			
			<?php if($this->user->type == 1 && (empty($this->user->whitelabel) && empty($this->user->superagency) && empty($this->user->agency) && empty($this->user->subagency))){?>
			<div class="form-group ctype"<?= $data->user->whitelabel == 'Y'||$data->user->superagency == 'Y'||$data->user->agency == 'Y'||$data->user->subagency == 'Y' ? ' style="display:block"' : ' style="display:none"' ?>>
                <label><?= $this->language->admin_user_update->main->available_license ?></label>
                <input type="text" name="license" class="form-control" value="<?= $data->user->ulicense ?>" />
				<div><small class="text-muted">If the license value is empty, the license value will be zero</small></div>
				<div><small class="text-muted">If the license value is -1, the license value will be Unlimited</small></div>
			</div>
			<?php } elseif($this->user->type == 1 && ($this->user->whitelabel == 'Y'||$this->user->superagency == 'Y')){?>
			<div class="form-group dtype"<?= $data->user->whitelabel == 'Y'||$data->user->superagency == 'Y'||$data->user->agency == 'Y'||$data->user->subagency == 'Y' ? ' style="display:block"' : ' style="display:none"' ?>>
                <label><?= $this->language->admin_user_update->main->available_license ?></label>
                <div id="avl" data-license-val="<?= is_null($this->user->ulicense) ? -1 : $this->user->ulicense ?>" class="form-control"><?= is_null($this->user->ulicense)||$this->user->ulicense==-1 ? 'Unlimited' : $this->user->ulicense ?></div>
			</div>
			<div class="form-group dtype"<?= $data->user->whitelabel == 'Y'||$data->user->superagency == 'Y'||$data->user->agency == 'Y'||$data->user->subagency == 'Y' ? ' style="display:block"' : ' style="display:none"' ?>>
                <label><?= $this->language->admin_user_update->main->transfer_license ?></label>
                <input type="text" name="transfer_license" class="form-control" value="0" />
				<div><small class="text-muted">Transfer Licenses using your Available Licenses to Agency/Sub Agency</small></div>
				<div><small class="text-danger">WARNING: Transfer the licenses will permanently use your licenses and cannot be returned, use it carefully</small></div>
			</div>
			<?php } elseif($this->user->type == 1 && $this->user->agency == 'Y'){?>
            <div class="form-group dtype"<?= $data->user->subagency == 'Y' ? ' style="display:block"' : ' style="display:none"' ?>>
                <label><?= $this->language->admin_user_update->main->available_license ?></label>
                <div id="avl" data-license-val="<?= is_null($this->user->ulicense) ? -1 : $this->user->ulicense ?>" class="form-control"><?= is_null($this->user->ulicense)||$this->user->ulicense==-1 ? 'Unlimited' : $this->user->ulicense ?></div>
			</div>
			<div class="form-group dtype"<?= $data->user->subagency == 'Y' ? ' style="display:block"' : ' style="display:none"' ?>>
                <label><?= $this->language->admin_user_update->main->transfer_license ?></label>
                <input type="text" name="transfer_license" class="form-control" value="0" />
				<div><small class="text-muted">Transfer Licenses using your Available Licenses to Sub Agency</small></div>
				<div><small class="text-danger">WARNING: Transfer the licenses will permanently use your licenses and cannot be returned, use it carefully</small></div>
			</div>
			<?php }?>
			
			<div class="form-group dcurrentlic">
				<label>Current User Licenses</label>
				<div class="form-control"><?= $data->user->ulicense ?></div>
			</div>
			
			<?php if($this->user->type == 1 && (empty($this->user->whitelabel) && empty($this->user->superagency) && empty($this->user->agency) && empty($this->user->subagency))){?>
			<h2 class="h4 mt-5">EShop Pro Package</h2>
            <p class="text-muted">Change and update the EShop Pro & RajaOngkir Pro package of the user.</p>
			
			<div class="form-group">
                <label>EShop Pro Status</label>
				<select class="form-control" name="ro_status">
				<option value="0"<?= $data->user->ro_pro_package==0 ? ' selected' : null ?>>Disabled</option>
				<option value="1"<?= $data->user->ro_pro_package==1 ? ' selected' : null ?>>Enabled</option>
				</select>
			</div>
			<!--
			<div id="puser_biolink" class="form-group">
				<label>User Biolinks</label>
				<div class="input-group d-flex align-items-center">
					<select class="form-control selectpicker with-ajax" id="select-biolink" data-live-search="true">
						<?php while($rows = $data->biolinks->fetch_object()) {?>
						<option value="<?= $rows->link_id ?>" data-subtext="<?= url($rows->url) ?>" ><?= $rows->url ?></option>
						<?php }?>
					</select>
					<div class="input-group-append">
						<div class="btn btn-primary btn-add-biolink">Add Biolink</div>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				 <label>Raja Ongkir Pro Biolinks List</label>
				 <div class="table-responsive">
					 <table class="table table-biolinks">
					 <thead>
					 <th>Link URL</th>
					 <th class="text-center">Date Add</th>
					 <th class="text-center">Date Expired</th>
					 <th class="text-center" width="10%">Action</th>
					 </thead>
					 <tbody>
					 <?php if($data->user->ro_pro_package){
					 if($data->url_biolink){ $nro_biolink = json_decode($data->user->ro_pro_biolink); $nro_courier = json_decode($data->user->ro_pro_courier); $nro_expired = json_decode($data->user->ro_pro_expired);
					 foreach($nro_biolink as $rkey => $rval){
					 if(isset($data->url_biolink[$rval])){
					 ?>
					 <tr>
					 <td><p class="mb-0"><?= $data->url_biolink[$rval] ?></p><p class="text-muted mb-0"><?= url($data->url_biolink[$rval]) ?></p><input type="hidden" name="ro_id[]" value="<?= $rval ?>" /></td>
					 <td class="align-middle"><select class="form-control" name="ro_date_add[]"><option value="">None</option><option value="1_year">1 Year</option><option value="6_months">6 Months</option><option value="lifetime">Lifetime</option></select></td>
					 <td class="align-middle"><div class="form-control text-center"><?= $nro_expired[$rkey]!='lifetime' ? date('Y-m-d H:i:s',$nro_expired[$rkey]) : 'Lifetime' ?></div><input type="hidden" name="ro_date_expired[]" value="<?= $nro_expired[$rkey] ?>" /><input type="hidden" name="ro_courier[]" value="<?= $nro_courier[$rkey] ?>" /></td>
					 <td class="text-center align-middle"><a href="javascript:;" class="btn btn-danger btn-del-biolink">Delete</a></td>
					 </tr>
					 <?php }}}}?>
					 </tbody>
					 </table>
				 </div>
			</div>
			-->
			<?php }?>
			
			<?php if($this->user->type == 1 && (empty($this->user->whitelabel) && empty($this->user->superagency) && empty($this->user->agency) && empty($this->user->subagency))){?>
			<h2 class="h4 mt-5">3 in 1 Package</h2>
            <p class="text-muted">EShop Pro, Smart AI and Custom Domain Package.</p>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>ECS Package Status</label>
						<select class="form-control" name="esc_status">
						<option value="0"<?= $data->user->esc_package==0 ? ' selected' : null ?>>Disabled</option>
						<option value="1"<?= $data->user->esc_package==1 ? ' selected' : null ?>>Enabled</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<label>ECS Package Expired / Year <small class="text-danger">Sudah ditambahkan 1 Tahun</small></label>
					<div class="form-group">
						<input type="text" class="form-control" name="esc_expired" value="<?= $this->user->esc_expired ? $this->user->esc_expired : date('Y-m-d H:i:s',strtotime('+1 years',strtotime('now')))?>" />
					</div>
				</div>
			</div>
			<?php }?>
			
			<h2 class="h4 mt-5"><?= $this->language->admin_user_update->package->header ?></h2>
            <p class="text-muted"><?= $this->language->admin_user_update->package->header_help ?></p>

            <div class="form-group">
                <label><?= $this->language->admin_user_update->package->package_id ?></label>

                <select class="form-control" name="package_id">
					<?php if($this->settings->package_free->is_enabled) {?>
                    <option value="free" <?php if($data->user->package->package_id == 'free') echo 'selected' ?>><?= $this->settings->package_free->name ?></option>
                    <?php }?>
					<?php if($this->settings->package_trial->is_enabled) {?>
					<option value="trial" <?php if($data->user->package->package_id == 'trial') echo 'selected' ?>><?= $this->settings->package_trial->name ?></option>
                    <?php }?>
					<option value="custom" <?php if($data->user->package->package_id == 'custom') echo 'selected' ?>><?= $this->settings->package_custom->name ?></option>

                    <?php while($row = $data->packages_result->fetch_object()): ?>
						<?php if($row->is_enabled) {?>
                        <option value="<?= $row->package_id ?>" <?php if($data->user->package->package_id == $row->package_id) echo 'selected' ?>><?= $row->name ?></option>
						<?php }?>
					<?php endwhile ?>
                </select>
            </div>
            
            <?php if(($this->user->type == 1 || $this->user->whitelabel) && (empty($this->user->superagency) && empty($this->user->agency) && empty($this->user->subagency)) && $this->user->tripay_addon){?> 
            <div class="form-group">
                <label>EShop Pro Status</label>
				<select class="form-control" name="ro_status">
				<option value="0"<?= $data->user->ro_pro_package==0 ? ' selected' : null ?>>Disabled</option>
				<option value="1"<?= $data->user->ro_pro_package==1 ? ' selected' : null ?>>Enabled</option>
				</select>
			</div>
            <?php }?>
            
            <div class="form-group">
                <label><?= $this->language->admin_user_update->package->package_trial_done ?></label>

                <select class="form-control" name="package_trial_done">
                    <option value="1" <?= $data->user->package_trial_done ? 'selected="selected"' : null ?>><?= $this->language->global->yes ?></option>
                    <option value="0" <?= !$data->user->package_trial_done ? 'selected="selected"' : null ?>><?= $this->language->global->no ?></option>
                </select>
            </div>

            <div id="package_expiration_date_container" class="form-group">
                <label><?= $this->language->admin_user_update->package->package_expiration_date ?></label>
                <input type="text" class="form-control" name="package_expiration_date" autocomplete="off" value="<?= $data->user->package_expiration_date ?>">
            </div>

            <div id="package_settings" style="display: none">
                <div class="form-group">
                    <label for="projects_limit"><?= $this->language->admin_packages->input->projects_limit ?></label>
                    <input type="number" id="projects_limit" name="projects_limit" min="-1" class="form-control" value="<?= $data->user->package->settings->projects_limit ?>" />
                    <small class="text-muted"><?= $this->language->admin_packages->input->projects_limit_help ?></small>
                </div>

                <div class="form-group">
                    <label for="biolinks_limit"><?= $this->language->admin_packages->input->biolinks_limit ?></label>
                    <input type="number" id="biolinks_limit" name="biolinks_limit" min="-1" class="form-control" value="<?= $data->user->package->settings->biolinks_limit ?>" />
                    <small class="text-muted"><?= $this->language->admin_packages->input->biolinks_limit_help ?></small>
                </div>

                <div class="form-group" <?= !$this->settings->links->shortener_is_enabled ? 'style="display: none"' : null ?>>
                    <label for="links_limit"><?= $this->language->admin_packages->input->links_limit ?></label>
                    <input type="number" id="links_limit" name="links_limit" min="-1" class="form-control" value="<?= $data->user->package->settings->links_limit ?>" />
                    <small class="text-muted"><?= $this->language->admin_packages->input->links_limit_help ?></small>
                </div>

                <div class="form-group" <?= !$this->settings->links->domains_is_enabled ? 'style="display: none"' : null ?>>
                    <label for="biolinks_limit"><?= $this->language->admin_packages->input->domains_limit ?></label>
                    <input type="number" id="domains_limit" name="domains_limit" min="-1" class="form-control" value="<?= $data->user->package->settings->domains_limit ?>" />
                    <small class="text-muted"><?= $this->language->admin_packages->input->domains_limit_help ?></small>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="no_ads" name="no_ads" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->no_ads ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="no_ads"><?= $this->language->admin_packages->input->no_ads ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->no_ads_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="removable_branding" name="removable_branding" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->removable_branding ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="removable_branding"><?= $this->language->admin_packages->input->removable_branding ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->removable_branding_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="custom_branding" name="custom_branding" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->custom_branding ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="custom_branding"><?= $this->language->admin_packages->input->custom_branding ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->custom_branding_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="custom_colored_links" name="custom_colored_links" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->custom_colored_links ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="custom_colored_links"><?= $this->language->admin_packages->input->custom_colored_links ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->custom_colored_links_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="statistics" name="statistics" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->statistics ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="statistics"><?= $this->language->admin_packages->input->statistics ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->statistics_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="google_analytics" name="google_analytics" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->google_analytics ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="google_analytics"><?= $this->language->admin_packages->input->google_analytics ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->google_analytics_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="facebook_pixel" name="facebook_pixel" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->facebook_pixel ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="facebook_pixel"><?= $this->language->admin_packages->input->facebook_pixel ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->facebook_pixel_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="custom_backgrounds" name="custom_backgrounds" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->custom_backgrounds ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="custom_backgrounds"><?= $this->language->admin_packages->input->custom_backgrounds ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->custom_backgrounds_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="verified" name="verified" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->verified ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="verified"><?= $this->language->admin_packages->input->verified ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->verified_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="scheduling" name="scheduling" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->scheduling ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="scheduling"><?= $this->language->admin_packages->input->scheduling ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->scheduling_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="seo" name="seo" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->seo ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="seo"><?= $this->language->admin_packages->input->seo ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->seo_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="utm" name="utm" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->utm ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="utm"><?= $this->language->admin_packages->input->utm ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->utm_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="socials" name="socials" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->socials ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="socials"><?= $this->language->admin_packages->input->socials ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->socials_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="fonts" name="fonts" type="checkbox" class="custom-control-input" <?= $data->user->package->settings->fonts ? 'checked="true"' : null ?>>
                    <label class="custom-control-label" for="fonts"><?= $this->language->admin_packages->input->fonts ?></label>
                    <div><small class="text-muted"><?= $this->language->admin_packages->input->fonts_help ?></small></div>
                </div>
            </div>

            <h2 class="h4 mt-5"><?= $this->language->admin_user_update->change_password->header ?></h2>
            <p class="text-muted"><?= $this->language->admin_user_update->change_password->header_help ?></p>

            <div class="form-group">
                <label><?= $this->language->admin_user_update->change_password->new_password ?></label>
                <input type="password" name="new_password" class="form-control" />
            </div>

            <div class="form-group">
                <label><?= $this->language->admin_user_update->change_password->repeat_password ?></label>
                <input type="password" name="repeat_password" class="form-control" />
            </div>

            <div class="mt-4">
                <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->update ?></button>
            </div>
        </form>
    </div>
</div>
<?php if($this->user->type == 1 && (empty($this->user->whitelabel) && empty($this->user->superagency) && empty($this->user->agency) && empty($this->user->subagency))){?>
<style>
.bootstrap-select .dropdown-menu li small{
	display:block;
	width:100%;
	padding:0;
}
.bootstrap-select .dropdown-menu li a.opt {
	padding-left:1.25rem;
}
.dropdown-item {
	white-space: normal; !important;
}
.bootstrap-select .status{display:none !important}
.bootstrap-select .btn-light{
	color: #858585;
	background-color: #fff;
	background-clip: padding-box;
	border: 1px solid #e8e8e8;
	border-radius: .25rem;
}
</style>
<?php }?>
<?php ob_start() ?>
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/datepicker.min.css' ?>" rel="stylesheet" media="screen">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/datepicker.min.js' ?>"></script>
<script>
    let check_package_id = () => {
        let selected_package_id = $('[name="package_id"]').find(':selected').attr('value');
		
		if(selected_package_id == 'free') {
			$('#package_expiration_date_container').show();
		} else if(selected_package_id == 'custom') {
			$('#package_settings').show();
			$('#package_expiration_date_container').show();
		} else {
			$('#package_expiration_date_container').show();
			$('#package_settings').hide();
		}
    };

    /* Initial check */
    check_package_id();

    $.fn.datepicker.language['altum'] = <?= json_encode(require APP_PATH . 'includes/datepicker_translations.php') ?>;
    $('[name="esc_expired"]').datepicker({
        classes: 'datepicker-modal',
        language: 'altum',
        dateFormat: 'yyyy-mm-dd',
        autoClose: true,
        timepicker: false,
        toggleSelected: false,
        minDate: new Date('<?= date('Y-m-d',strtotime('+1 years',strtotime('now')))?>')
    });
	
	$('[name="package_expiration_date"]').datepicker({
        classes: 'datepicker-modal',
        language: 'altum',
        dateFormat: 'yyyy-mm-dd',
        autoClose: true,
        timepicker: false,
        toggleSelected: false,
        minDate: new Date()
    });
	
	var max_lcs = <?= $this->user->type == 1 && $this->user->agency == 'Y' ? 25 : 10000 ?>;

    /* Dont show expiration date when the chosen package is the free one */
    $('[name="package_id"]').on('change', check_package_id);
	$('select[name="type"]').on('change',function(e){
        if($(this).val()==2||$(this).val()==3||$(this).val()==4||$(this).val()==5) {
            $('.ctype').show(),
            $('.dtype').show();
			$('.dcurrentlic').show();
        } else {
            $('.ctype').hide(),
            $('.dtype').hide();
			$('.dcurrentlic').hide();
		}
		if($(this).val()==2)
			$('.btype').show()
		else
			$('.btype').hide()
		
		if($(this).val()==4) {
			max_lcs = 300;
		} else if($(this).val()==5) {
			max_lcs = 25;
		} else {
			max_lcs = 300;
		}
    }).trigger('change')
	
	
	
	if($('#avl').length>0) {
		var lcs_limit = $('#avl').data('license-val')
		$('[name="transfer_license"]').on('keyup', function(e) {
			var tv = $(this).val()
			tv = tv.replace(/\D/g,'') ? tv.replace(/\D/g,'') : 0;
			$(this).val(tv)
			if($(this).val()>max_lcs) 
				$(this).val(max_lcs) 
			else 
				$(this).val(parseInt($(this).val()))
			if(lcs_limit>0) {
				if($(this).val()>lcs_limit) $(this).val(lcs_limit)
				$('#avl').text(lcs_limit-$(this).val())
			}
		}).on('blur',function(e) { parseInt($(this).val($(this).val().replace(/\D/g,''))); if($(this).val()=='') $(this).val(0) });
	}
<?php if($this->user->type == 1 && (empty($this->user->whitelabel) && empty($this->user->superagency) && empty($this->user->agency) && empty($this->user->subagency))){?>
$(window).on('load',function(e){
	var options = {
	  values: "a, b, c",
	  ajax: {
		url: "<?= url('admin/user-update/biolinksearch') ?>",
		type: "POST",
		dataType: "json",
		// Use "{{{q}}}" as a placeholder and Ajax Bootstrap Select will
		// automatically replace it with the value of the search query.
		data: {
		  q: "{{{q}}}",
		  u: "<?= $data->user->user_id ?>"
		}
	  },
	  locale: {
		emptyTitle: "Select and Begin Typing"
	  },
	  preprocessData: function(data) {
		var i,
		  l = data.length,
		  array = [];
		if (l) {
		  for (i = 0; i < l; i++) {
			array.push(
			  $.extend(true, data[i], {
				text: data[i].name,
				value: data[i].id,
				data: {
				  subtext: data[i].url
				}
			  })
			);
		  }
		}
		// You must always return a valid array when processing data. The
		// data argument passed is a clone and cannot be modified directly.
		return array;
	  }
	};
	
	$(".selectpicker")
	  .selectpicker()
	  .filter(".with-ajax")
	  .ajaxSelectPicker(options);
	$("select").trigger("change");

	function chooseSelectpicker(index, selectpicker) {
	  $(selectpicker).val(index);
	  $(selectpicker).selectpicker('refresh');
	}
	
	function delBiolink() {
		$('.btn-del-biolink').off('click').on('click',function(e){
			$(this).parents('tr:eq(0)').remove();
		})
	}
	delBiolink()
	$('.btn-add-biolink').on('click',function(e){
		var bvl = $('#select-biolink').val()
		var btx = $('#select-biolink').find(':selected').text()
		var btl = $('#select-biolink').find(':selected').data('subtext')
		if(bvl) {
			var html = '<tr><td><p class="mb-0">'+btx+'</p><p class="text-muted mb-0">'+btl+'</p><input type="hidden" name="ro_id[]" value="'+bvl+'" /></td><td class="align-middle"><select class="form-control" name="ro_date_add[]"><option value="1_year">1 Year</option><option value="6_months">6 Months</option><option value="lifetime">Lifetime</option></select></td><td class="align-middle"><div class="form-control"></div><input type="hidden" name="ro_date_expired[]" value="" /><input type="hidden" name="ro_courier[]" value="jne:pos:tiki:anteraja:jnt:sicepat" /></td><td class="text-center align-middle"><a href="javascript:;" class="btn btn-danger btn-del-biolink">Delete</a></td></tr>';
			$('.table-biolinks').append(html);
			delBiolink()
		}
	})
})
<?php }?>
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>