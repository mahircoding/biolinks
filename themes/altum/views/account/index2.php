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
                    <label for="email"><?= $this->language->account->settings->phone ?></label>
                    <input type="text" id="phone" name="phone" class="form-control" value="<?= $this->user->phone ?>" placeholder="<?= $this->language->account->settings->phone_placeholder ?>" />
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
						<input type="checkbox" class="custom-control-input" id="rajaongkir_enabled" name="rajaongkir_enabled"<?= $data->configs['enabled'] ? ' checked' : '' ?>>
						<label class="custom-control-label clickable" for="rajaongkir_enabled"><b><?= $this->language->account->shipping->rajaongkir_config ?></b></label>
					</div>
					
					<div class="rajaongkir-container"<?= !$data->configs['enabled'] ? ' style="display:none"' : '' ?>>
						
						<hr class="mt-1">
						
						<div class="alert alert-success">
							Fitur Baru! Eshop Pro. Anda bisa menggunakan layanan hitung ongkir semua ekspedisi kurir. Untuk Menikmati fitur ini Anda hanya harus membayar biaya tambahan Rp 50rb/toko online.
							<br><br>
							Jangan Sampai Anda Gagal Kebanjiran Orderan Hanya Karena Takut Bayar 50rb!.
							<br><br>
							<div class="d-flex justify-content-center">
								<a href="https://wa.me/6289521009988?text=Halo%20Kang%20Ridwan%2C%20Saya%20Mau%20Order%20Layanan%20EShop%20Pro" class="btn btn-primary" target="_blank">Order EShop Pro</a>
							</div>
						</div>
						
						<hr class="mt-1">
						
						<?php if($data->ro_package && $data->url_biolink){?>
						<div class="form-group">
						<label><b>EShop Pro Biolink</b></label>
						<div id="accordion">
						<?php foreach($data->ro_biolink as $rkey => $rval){
						if($data->ro_expired>strtotime('NOW')){
						?>
							<div class="card">
								<div class="card-header" data-toggle="collapse" data-target="#collapse<?= $rkey ?>" aria-expanded="true" aria-controls="collapse<?= $rkey ?>" id="heading<?= $rkey ?>">
									<div class="d-flex justify-content-between align-items-center">
										<div>
											<p class="mb-0"><?= $data->url_biolink[$rval] ?></p>
											<p class="text-muted mb-0"><?= url($data->url_biolink[$rval]) ?></p>
										</div>
										<div>
											<p class="text-center mb-0">Expired</p>
											<p class="text-center text-primary mb-0"><?= date('j F Y H:i A',$data->ro_expired[$rkey]) ?></p>
										</div>
									</div>
								</div>
								<div id="collapse<?= $rkey ?>" class="collapse<?= $rkey==0 ? ' show' : null ?>" aria-labelledby="heading<?= $rkey ?>" data-parent="#accordion">
									<div class="card-body">
										<div class="row">
											<?php foreach($data->pro_couriers as $ck => $cv){?>
											<div class="col-md-4">
												<div class="form-check">
												  <input class="form-check-input" type="checkbox" name="ro_courier[<?= $rkey ?>][]" value="<?= $ck ?>" id="ro_courier_<?= $rkey.'_'.$ck ?>"<?= in_array($ck,explode(':',$data->ro_courier[$rkey])) ? ' checked' : null ?>>
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
						<?php }}?>
						</div>
						</div>
						
						<hr class="mt-1">
						<?php }?>
						<div class="form-group<?= $data->ro_package && $data->url_biolink ? ' mt-3' : null ?>">
							<label for="name"><?= $this->language->account->shipping->rajaongkir_apikey ?></label>
							<input type="text" id="name" name="rajaongkir_apikey" class="form-control" value="<?= censored_text($data->configs['apikey']) ?>" autocomplete="off" />
						</div>

						<div class="form-group">
							<label for="name"><?= $this->language->account->shipping->rajaongkir_packages ?></label>
							<select class="form-control" name="rajaongkir_package">
								<option value="0"<?= $data->configs['package']==0 ? ' selected' : '' ?>><?= $this->language->account->shipping->rajaongkir_package_starter ?></option>
								<option value="1"<?= $data->configs['package']==1 ? ' selected' : '' ?>><?= $this->language->account->shipping->rajaongkir_package_basic ?></option>
								<option value="2"<?= $data->configs['package']==2 ? ' selected' : '' ?>><?= $this->language->account->shipping->rajaongkir_package_pro ?></option>
							</select>
						</div>
						<div class="form-ro-packages">
							<div class="form-group mb-0">
								<label for="name"><?= $this->language->account->shipping->rajaongkir_couriers ?></label>
							</div>
							<div class="ro-package-item<?= $data->configs['package']==0 ? ' show' : '' ?>">
								<div class="row">
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="radio" name="courier_1" id="courier_11" value="jne"<?= $data->configs['package']==0 ? ($data->configs['couriers']&&in_array('jne',$data->configs['couriers']) ? ' checked' : '') : ' checked' ?>>
										  <label class="form-check-label" for="courier_11">
											JNE
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="radio" name="courier_1" id="courier_12" value="tiki"<?= $data->configs['package']==0 ? ($data->configs['couriers']&&in_array('tiki',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_12">
											TIKI
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="radio" name="courier_1" id="courier_13" value="pos"<?= $data->configs['package']==0 ? ($data->configs['couriers']&&in_array('pos',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_13">
											POS Indonesia
										  </label>
										</div>
									</div>
								</div>
							</div>
							<div class="ro-package-item<?= $data->configs['package']==1 ? ' show' : '' ?>">
								<div class="row">
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_2[]" value="jne" id="courier_21"<?= $data->configs['package']==1 ? ($data->configs['couriers']&&in_array('jne',$data->configs['couriers']) ? ' checked' : '') : ' checked' ?>>
										  <label class="form-check-label" for="courier_21">
											JNE
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_2[]" value="tiki" id="courier_22"<?= $data->configs['package']==1 ? ($data->configs['couriers']&&in_array('tiki',$data->configs['couriers']) ? ' checked' : '') : ' checked' ?>>
										  <label class="form-check-label" for="courier_22">
											TIKI
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_2[]" value="pos" id="courier_23"<?= $data->configs['package']==1 ? ($data->configs['couriers']&&in_array('pos',$data->configs['couriers']) ? ' checked' : '') : ' checked' ?>>
										  <label class="form-check-label" for="courier_23">
											POS Indonesia
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_2[]" value="pcp" id="courier_24"<?= $data->configs['package']==1 ? ($data->configs['couriers']&&in_array('pcp',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_24">
											PCP Express
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_2[]" value="esl" id="courier_25"<?= $data->configs['package']==1 ? ($data->configs['couriers']&&in_array('esl',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_25">
											ESL Express
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_2[]" value="rpx" id="courier_26"<?= $data->configs['package']==1 ? ($data->configs['couriers']&&in_array('rpx',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_26">
											RPX Holding
										  </label>
										</div>
									</div>
								</div>
							</div>
							<div class="ro-package-item<?= $data->configs['package']==2 ? ' show' : '' ?>">
								<div class="row">
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="jne" id="courier_31"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('jne',$data->configs['couriers']) ? ' checked' : '') : ' checked' ?>>
										  <label class="form-check-label" for="courier_31">
											JNE
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="jnt" id="courier_32"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('jnt',$data->configs['couriers']) ? ' checked' : '') : ' checked' ?>>
										  <label class="form-check-label" for="courier_32">
											J&T Express
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="sicepat" id="courier_33"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('sicepat',$data->configs['couriers']) ? ' checked' : '') : ' checked' ?>>
										  <label class="form-check-label" for="courier_33">
											SiCepat Express
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="pos" id="courier_34"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('pos',$data->configs['couriers']) ? ' checked' : '') : ' checked' ?>>
										  <label class="form-check-label" for="courier_34">
											POS Indonesia
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="anteraja" id="courier_35"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('anteraja',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_35">
											AnterAja
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="tiki" id="courier_36"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('tiki',$data->configs['couriers']) ? ' checked' : '') : ' checked' ?>>
										  <label class="form-check-label" for="courier_36">
											TIKI
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="wahana" id="courier_37"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('wahana',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_37">
											Wahana
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="jet" id="courier_38"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('jet',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_38">
											JET Express
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="pandu" id="courier_39"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('pandu',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_39">
											Pandu Logistics
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="first" id="courier_40"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('first',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_40">
											First Logistics
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="lion" id="courier_41"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('lion',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_41">
											Lion Parcel
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="ninja" id="courier_42"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('ninja',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_42">
											Ninja Xpress
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="rex" id="courier_43"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('rex',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_43">
											Royal Express
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="ide" id="courier_44"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('ide',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_44">
											ID Express
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="ncs" id="courier_45"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('ncs',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_45">
											NCS
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="rpx" id="courier_46"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('rpx',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_46">
											RPX Holding
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="pcp" id="courier_47"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('pcp',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_47">
											PCP Express
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="esl" id="courier_48"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('esl',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_48">
											ESL Express
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="pahala" id="courier_49"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('pahala',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_49">
											Pahala Kencana
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="dse" id="courier_50"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('dse',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_50">
											21 Express
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="slis" id="courier_51"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('slis',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_51">
											Solusi Ekspres
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="star" id="courier_52"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('star',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_52">
											Star Cargo
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="idl" id="courier_53"<?= $data->configs['package']==2 ? ($data->configs['couriers']&&in_array('idl',$data->configs['couriers']) ? ' checked' : '') : '' ?>>
										  <label class="form-check-label" for="courier_53">
											IDL Cargo
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="expedito" id="courier_54">
										  <label class="form-check-label" for="courier_54">
											Expedito*
										  </label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" name="courier_3[]" value="sentral" id="courier_55">
										  <label class="form-check-label" for="courier_55">
											Sentral Cargo
										  </label>
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
<style>.ro-package-item{display:none}.ro-package-item.show{display:block}.sales-type-item{display:none}.sales-type-item.show{display:block}</style>
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
			$('.rajaongkir-container').removeAttr('style')
		else
			$('.rajaongkir-container').hide();
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
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>
