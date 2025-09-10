<?php defined('ALTUMCODE') || die() ?>

<header class="header pb-0">
    <div class="container">
        <?= $this->views['account_header'] ?>
    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container pt-5">

    <?php display_notifications() ?>
	
    <form action="" method="post" role="form">
        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

        <div class="row">
            <div class="col-12 col-md-4">
                <h2 class="h3"><?= $this->language->account->settings->header ?></h2>
                <p class="text-muted"><?= $this->language->account->settings->subheader ?></p>
            </div>

            <div class="col">
                <div class="form-group">
                    <label for="name"><?= $this->language->account->settings->name ?></label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= $this->user->name ?>" />
                </div>

                <div class="form-group">
                    <label for="email"><?= $this->language->account->settings->email ?></label>
                    <input type="text" id="email" name="email" class="form-control" value="<?= $this->user->email ?>" />
                </div>
				
				<div class="form-group">
                    <label for="email">Nomor Whatsapp</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="<?= $this->user->phone ?>" placeholder="Nomor Whatsapp" />
                </div>

                <div class="form-group">
                    <label for="timezone"><?= $this->language->account->settings->timezone ?></label>
                    <select id="timezone" name="timezone" class="form-control">
                        <?php foreach(DateTimeZone::listIdentifiers() as $timezone) echo '<option value="' . $timezone . '" ' . ($this->user->timezone == $timezone ? 'selected' : null) . '>' . $timezone . '</option>' ?>
                    </select>
                    <small class="text-muted"><?= $this->language->account->settings->timezone_help ?></small>
                </div>
				
				<div class="form-group">
                    <label for="country"><?= $this->language->account->settings->country ?></label>
                    <select id="country" name="country" class="form-control">
                        <?php while($cty = $data->country->fetch_object()) { ?>
						<option value="<?= $cty->cty_id ?>"<?= $cty->cty_id==$this->user->country ? ' selected' : '' ?>><?= $cty->cty_name ?></option>
						<?php }?>
                    </select>
                </div>
            </div>
        </div>
		
		<div class="margin-top-3"></div>
		
		<div class="row">
            <div class="col-12 col-md-4">
                <h2 class="h3"><?= $this->language->account->shipping->header ?></h2>
                <p class="text-muted"><?= $this->language->account->shipping->subheader ?></p>
            </div>

            <div class="col">
				<div><b>Seller Location</b></div>
				<hr class="mt-1">
				<div id="id-city" class="city-container"<?= $this->user->country ? ($this->user->country=='ID' ? '' : ' style="display:none"') : '' ?>>
					
					<div class="form-group">
						<label for="name"><?= $this->language->account->shipping->province ?></label>
						<select class="form-control vw_location" data-type="pv" name="province">
							<?php while($row = $data->province->fetch_object()): ?>
							<option value="<?= $row->pv_id ?>"<?= $row->pv_id==$data->configs['pv'] ? ' selected' : '' ?>><?= $row->pv_name ?></option>
							<?php endwhile ?>
						</select>
					</div>
					<div class="form-group">
						<label for="name"><?= $this->language->account->shipping->city ?></label>
						<select class="form-control vw_location" data-type="ct" name="city">
							<?php while($row = $data->city->fetch_object()): ?>
							<option value="<?= $row->kt_id ?>"<?= $row->kt_id==$data->configs['kt'] ? ' selected' : '' ?>><?= ($row->kt_type==1 ? '' : 'Kab. ').$row->kt_name ?></option>
							<?php endwhile ?>
						</select>
					</div>
					<div class="form-group">
						<label for="name"><?= $this->language->account->shipping->district ?></label>
						<select class="form-control vw_location" data-type="sd" name="subdistrict">
							<?php while($row = $data->subdistrict->fetch_object()): ?>
							<option value="<?= $row->kc_id ?>"<?= $row->kc_id==$data->configs['kc'] ? ' selected' : '' ?>><?= $row->kc_name ?></option>
							<?php endwhile ?>
						</select>
					</div>
					
					<div class="custom-control custom-switch mt-5 mr-3">
						<input type="checkbox" class="custom-control-input" id="rajaongkir_enabled" name="rajaongkir_enabled" value="<?= $data->configs['enabled'] ?>"<?= $data->configs['enabled'] ? ' checked' : '' ?>>
						<label class="custom-control-label clickable" for="rajaongkir_enabled"><b>Aktifkan Pengiriman EShop</b></label>
					</div>
					
					<div class="rajaongkir-container<?= !$data->configs['enabled'] ? ' ro-disabled' : '' ?>">
						
						<hr class="mt-1">
						<?php //$data->ro_package && $data->url_biolink ?>
						<div class="form-group">
							<label><b>EShop Pro Biolink</b></label>
							<div id="accordion">
							<?php
							$is_eshop_pro=true; $rkey = 0;
							?>
								<div class="card">
									<div id="collapse<?= $rkey ?>" class="collapse<?= $rkey==0 ? ' show' : null ?>" aria-labelledby="heading<?= $rkey ?>" data-parent="#accordion">
										<div class="card-body">
											<div class="d-flex align-items-center mb-3">
											  <div class="flex-grow-1">Daftar Jasa Ekspedisi Pengiriman</div>
											  <div class="p-2"><a id="unselectall" class="btn btn-danger btn-sm" href="javascript:;">UnSelect All</a></div>
											  <div class="p-2"><a id="selectall" class="btn btn-primary btn-sm" href="javascript:;">Select All</a></div>
											</div>
											
											<div class="row">
												<?php foreach($data->pro_couriers as $ck => $cv){?>
												<div class="col-md-4">
													<div class="form-check">
													  <input class="form-check-input" type="checkbox" name="ro_courier[<?= $rkey ?>][]" value="<?= $ck ?>" id="ro_courier_<?= $rkey.'_'.$ck ?>"<?= in_array($ck,$data->ro_courier) ? ' checked' : null ?>>
													  <label class="form-check-label" for="ro_courier_<?= $rkey.'_'.$ck ?>">
														<?= $cv ?>
													  </label>
													</div>
												</div>
												<?php }?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				
				</div>
				
				<div id="my-city" class="city-container"<?= $this->user->country ? ($this->user->country=='MY' ? '' : ' style="display:none"') : ' style="display:none"' ?>>
					
					<div class="form-group">
						<label for="name"><?= $this->language->account->shipping->gb_state ?></label>
						<select class="form-control" name="my_city">
							<?php while($row = $data->my_city->fetch_object()): ?>
							<option value="<?= $row->myc_id ?>"><?= $row->myc_name ?></option>
							<?php endwhile ?>
						</select>
					</div>
					
					<div class="form-group">
						<label for="my_postcode"><?= $this->language->account->shipping->postcode ?></label>
						<input type="text" name="my_postcode" class="form-control" value="<?= $data->configs['pc'] ?>" placeholder="<?= ucfirst($this->language->account->shipping->postcode) ?>..." />
					</div>
					
					<div class="custom-control custom-switch mt-5 mr-3">
						<input type="checkbox" class="custom-control-input" id="parcelasia_enabled" name="parcelasia_enabled"<?= $data->configs['enabled'] ? ' checked' : '' ?>>
						<label class="custom-control-label clickable" for="parcelasia_enabled"><b><?= $this->language->account->shipping->parcelasia_config ?></b></label>
					</div>
					
					<div class="parcelasia-container"<?= !$data->configs['enabled'] ? ' style="display:none"' : '' ?>>
						
						<hr class="mt-1">
						
						<div class="form-group">
							<label for="name"><?= $this->language->account->shipping->parcelasia_apikey ?></label>
							<input type="text" id="name" name="parcelasia_apikey" class="form-control" value="<?= censored_text($data->configs['apikey']) ?>" />
						</div>
						
						<div class="form-group">
							<label for="name"><?= $this->language->account->shipping->parcelasia_secret ?></label>
							<input type="text" id="name" name="parcelasia_secret" class="form-control" value="<?= censored_text($data->configs['secret']) ?>" />
						</div>
						
					</div>
					
				</div>
				
            </div>
        </div>

        <div class="margin-top-3"></div>
		
		<?php if($this->user->type==1) {?>
		<div class="row">
            <div class="col-12 col-md-4">
                <h2 class="h3"><?= $this->language->account->sales_page->header ?></h2>
                <p class="text-muted"><?= $this->language->account->sales_page->subheader ?></p>
            </div>

            <div class="col">
                <div class="form-group">
                    <label for="title"><?= $this->language->account->sales_page->title ?></label>
                    <input type="text" id="title" name="title" class="form-control" value="<?= $data->sales_settings ? $data->sales_settings->title : '' ?>" placeholder="Default: <?= $this->language->package->package_expired ?>" />
					<small class="text-muted"><?= $this->language->account->sales_page->default_help ?></small>
				</div>

                <div class="form-group">
                    <label for="description"><?= $this->language->account->sales_page->description ?></label>
                    <textarea type="text" id="description" name="description" class="form-control" placeholder="Default: <?= $this->language->package->package_order ?>" /><?= $data->sales_settings ? $data->sales_settings->description : '' ?></textarea>
					<small class="text-muted"><?= $this->language->account->sales_page->default_help ?></small>
				</div>
				
				<div class="form-group">
                    <label for="text_button"><?= $this->language->account->sales_page->text_button ?></label>
                    <input type="text" id="text_button" name="text_button" class="form-control" value="<?= $data->sales_settings ? $data->sales_settings->text_button : '' ?>" placeholder="Default: <?= $this->language->package->buy_package ?>" />
					<small class="text-muted"><?= $this->language->account->sales_page->default_help ?></small>
				</div>
				
				<div class="form-group">
					<label for="sales_type"><?= $this->language->account->sales_page->sales_type ?></label>
					<select class="form-control" name="sales_type">
						<option value="0"<?= $data->sales_settings&&$data->sales_settings->sales_type==0 ? ' selected' : '' ?>>Using Custom Link</option>
						<option value="1"<?= $data->sales_settings&&$data->sales_settings->sales_type==1 ? ' selected' : '' ?>>Send a WhatsApp Message</option>
					</select>
				</div>
				
				<div class="form-sales-type">
					
					<div class="sales-type-item<?= $data->sales_settings&&$data->sales_settings->sales_type==0 ? ' show' : (is_null($data->sales_settings) ? ' show' : '') ?>">
						<div class="form-group">
							<label for="sales_link"><?= $this->language->account->sales_page->sales_link ?></label>
							<input class="form-control" name="sales_link_0" value="<?= $data->sales_settings&&$data->sales_settings->sales_type==0 ? $data->sales_settings->sales_link : '' ?>" placeholder="<?= $this->language->account->sales_page->sales_type_placeholder ?>" />
							<small class="text-muted"><?= $this->language->account->sales_page->sales_link_help ?></small>
						</div>
					</div>
					
					<div class="sales-type-item<?= $data->sales_settings&&$data->sales_settings->sales_type==1 ? ' show' : '' ?>">
						<div class="form-group">
							<label for="sales_link"><?= $this->language->account->sales_page->st_wa_message ?></label>
							<textarea class="form-control" name="sales_link_1" rows="1" placeholder="<?= $this->language->account->sales_page->st_wa_message_placeholder ?>"><?= $data->sales_settings&&$data->sales_settings->sales_type==1 ? $data->sales_settings->sales_link : '' ?></textarea>
						</div>
					</div>
				
				</div>
				
            </div>
        </div>

        <div class="margin-top-3"></div>
		<?php }?>
		
        <div class="row">
            <div class="col-12 col-md-4">
                <h2 class="h3"><?= $this->language->account->twofa->header ?></h2>
                <p class="text-muted"><?= $this->language->account->twofa->subheader ?></p>
            </div>

            <div class="col">
                <div class="form-group">
                    <label><?= $this->language->account->twofa->is_enabled ?></label>

                    <select name="twofa_is_enabled" class="form-control">
                        <option value="1" <?= $this->user->twofa_secret ? 'selected' : null ?>><?= $this->language->global->yes ?></option>
                        <option value="0" <?= !$this->user->twofa_secret ? 'selected' : null ?>><?= $this->language->global->no ?></option>
                    </select>
                </div>

                <div id="twofa_container">
                    <?php if(!$this->user->twofa_secret): ?>
                    <div class="form-group">
                        <label><?= $this->language->account->twofa->qr ?></label>
                        <p class="text-muted"><?= $this->language->account->twofa->qr_help ?></p>

                        <div class="d-flex flex-column flex-md-row align-items-center">
                            <div class="mb-3 mb-md-0 mr-md-5">
                                <img src="<?= $data->twofa_image ?>" alt="<?= $this->language->account->twofa->qr ?>" />
                            </div>

                            <div>
                                <label><?= $this->language->account->twofa->secret ?></label>
                                <p class="text-muted"><?= $this->language->account->twofa->secret_help ?></p>

                                <p class="h5"><?= $data->twofa_secret ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="twofa_token"><?= $this->language->account->twofa->verify ?></label>
                        <p class="text-muted"><?= $this->language->account->twofa->verify_help ?></p>
                        <input type="text" id="twofa_token" name="twofa_token" class="form-control" value="" autocomplete="off" />
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="margin-top-3"></div>

        <div class="row">
            <div class="col-12 col-md-4">
                <h2 class="h3"><?= $this->language->account->change_password->header ?></h2>
                <p class="text-muted"><?= $this->language->account->change_password->subheader ?></p>
            </div>

            <div class="col">
                <div class="form-group">
                    <label for="old_password"><?= $this->language->account->change_password->current_password ?></label>
                    <input type="password" id="old_password" name="old_password" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="new_password"><?= $this->language->account->change_password->new_password ?></label>
                    <input type="password" id="new_password" name="new_password" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="repeat_password"><?= $this->language->account->change_password->repeat_password ?></label>
                    <input type="password" id="repeat_password" name="repeat_password" class="form-control" />
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 col-md-4"></div>

            <div class="col">
                <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->update ?></button>
            </div>
        </div>
    </form>

    <div class="margin-top-6 d-flex justify-content-between align-items-center" style="display:none !important">
        <div>
            <h2 class="h3"><?= $this->language->account->delete->header ?></h2>
            <p class="text-muted"><?= $this->language->account->delete->subheader ?></p>
        </div>

        <a href="<?= url('account/delete' . \Altum\Middlewares\Csrf::get_url_query()) ?>" class="btn btn-secondary" style="display:none" data-confirm="<?= $this->language->account->delete->confirm_message ?>"><?= $this->language->global->delete ?></a>
    </div>

</section>
<style>.rajaongkir-container{transition: filter 0.5s ease-in-out}.rajaongkir-container.ro-disabled{filter: gray;-webkit-filter: grayscale(1);filter: grayscale(1)}.ro-package-item{display:none}.ro-package-item.show{display:block}.sales-type-item{display:none}.sales-type-item.show{display:block}</style>
<script>$(document).ready(function(e){$('#unselectall').on('click',function(e){$(this).parents('.card-body').find('input[type="checkbox"]').prop('checked',false)});$('#selectall').on('click',function(e){$(this).parents('.card-body').find('input[type="checkbox"]').prop('checked',true)})})</script>
<?php if(!$this->user->twofa_secret): ?>
<?php ob_start() ?>
<script>
    let twofa = () => {
        let is_enabled = parseInt($('select[name="twofa_is_enabled"]').find(':selected').val());

        if(is_enabled) {
            $('#twofa_container').show();
        } else {
            $('#twofa_container').hide();

        }
    };

    twofa();
	$('select[name="twofa_is_enabled"]').on('change', twofa);
	$('.vw_location').on('change',function(e){
		if($(this).data('type')!=='sd') {
			if($(this).data('type')=='pv') {
				$('[data-type="ct"]').prop('disabled',true)
				$('[data-type="sd"]').prop('disabled',true)
			} else if($(this).data('type')=='ct') {
				$('[data-type="sd"]').prop('disabled',true)
			}
			$.ajax({
				type: 'POST',
				url: 'account/locationajax',
				data: {tp:$(this).data('type'),vl:$(this).val()},
				dataType: 'json',
				success: (d) => {
					if(typeof(d.ct)!='undefined') {
						var html = '';
						$.each(d.ct,function(i,j) {
							html += '<option value="'+j.id+'">'+j.name+'</option>';
						})
						$('[data-type="ct"]').html(html)
					}
					var html = '';
					$.each(d.sd,function(i,j) {
						html += '<option value="'+j.id+'">'+j.name+'</option>';
					})
					$('[data-type="sd"]').html(html)
					if($(this).data('type')=='pv') {
						$('[data-type="ct"]').prop('disabled',false)
						$('[data-type="sd"]').prop('disabled',false)
					} else if($(this).data('type')=='ct') {
						$('[data-type="sd"]').prop('disabled',false)
					}
				}
			});
		}
	})
	$('#country').on('change',function(e){
		$('.city-container').hide();
		$('#'+$(this).val().toLowerCase()+'-city').removeAttr('style')
	}).trigger('change')
	$('#rajaongkir_enabled').on('change',function(e){
		if($(this).prop('checked'))
			$('.rajaongkir-container').removeClass('ro-disabled')
		else
			$('.rajaongkir-container').addClass('ro-disabled')
	}).trigger('change')
	$('#parcelasia_enabled').on('change',function(e){
		if($(this).prop('checked'))
			$('.parcelasia-container').removeAttr('style')
		else
			$('.parcelasia-container').hide();
	}).trigger('change')
	
	$('[name="rajaongkir_package"]').on('change',function(e){
		$('.ro-package-item').removeClass('show')
		$('.ro-package-item').eq($(this).val()).removeClass('show').addClass('show')
	}).trigger('change')
	
	$('[name="sales_type"]').on('change',function(e){
		$('.sales-type-item').removeClass('show')
		$('.sales-type-item').eq($(this).val()).removeClass('show').addClass('show')
	}).trigger('change')
	<?php if(!$this->user->shipping): ?>
	setTimeout(function(){$('#selectall').click()},100)
	<?php endif ?>
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>
