<?php defined('ALTUMCODE') || die() ?>

		<div class="aqy-container">
			<div class="aqy-wrapper w-sm">
				<div class="agy-form-container">
					<div class="agy-form-content">
						<div class="form-info">
							<div class="f-header">
								<h2><i class="fas fa-users"></i> Buruan Gabung</h2>
							</div>
							<div class="f-body">
								<div class="content">
								Dengan Aplikasi ini Anda bisa membuat website, landing page, toko online, kartu nama digital dll hanya dalam hitungan menit dan bisa lewat HP (Tidak Perlu Buka Laptop).<br>
								<br>
								Isi formulir dibawah ini!.<br>
								</div>
							</div>
						</div>
						<div class="form-detail">
							<div class="f-header">
								<h2><i class="fab fa-wpforms"></i> Daftar Sekarang</h2>
							</div>
							
							<div class="f-body">
								<form id="join_trial" action="" method="post" class="mt-4" role="form">
								<div class="form-group">
									<label>Nama Panggilan</label>
									<input class="form-control" type="text" name="name" value="<?= $data->values['name'] ?>" placeholder="Masukkan nama panggilan..." />
									<div class="form-warn" data-field="name"></div>
								</div>
								
								<div class="form-group">
									<label>Email</label>
									<input class="form-control" type="text" name="email" value="<?= $data->values['email'] ?>" placeholder="Masukkan email..." />
									<div class="form-warn" data-field="email"></div>
								</div>
								
								<div class="form-group">
									<label>Nomor WhatsApp</label>
									<input class="form-control" type="text" name="phone" value="<?= $data->values['phone'] ?>" placeholder="Masukkan nomor telpon whatsapp..." />
									<div class="form-warn" data-field="phone"></div>
								</div>
								
								<div class="form-group">
									<label>Password</label>
									<input class="form-control" type="password" name="password" value="<?= $data->values['password'] ?>" placeholder="Masukkan password..." />
									<div class="form-warn" data-field="password"></div>
								</div>
								
								<div class="form-captcha">
									<?php $data->captcha->display() ?>
								</div>
								
								<div class="d-flex justify-content-center mt-8">
									<button class="btn btn-primary fs-4">Daftar</button>
								</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php ob_start() ?>
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

