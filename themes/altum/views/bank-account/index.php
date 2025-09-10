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
                <h2 class="h3"><?= $this->language->bank_account->header ?></h2>
                <p class="text-muted"><?= $this->language->bank_account->subheader ?></p>
            </div>
			<style>.cursor-pointer{cursor:pointer}</style>
            <div class="col form-account-container">
				<div class="d-flex justify-content-between mb-2">
				<div></div>
				<div data-eshop="psi" class="badge badge-primary btn-account badge-pill cursor-pointer p-2 pl-4 pr-4">Add Bank Account</div>
				</div>
				<div class="form-account">
					<?php if($data->bank_account):?>
					<?php foreach($data->bank_account as $ba):?>
					<div class="account-item">
						<div class="account-close">×</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Account Number</label>
									<input type="text" class="form-control" name="account_number[]" value="<?= $ba->account_number ?>" placeholder="Account Number..." required/>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Account Name</label>
									<input type="text" class="form-control" name="account_name[]" value="<?= $ba->account_name ?>" placeholder="Account Name..." required/>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Bank Name</label>
									<input type="text" class="form-control" name="bank_name[]" value="<?= $ba->bank_name ?>" placeholder="Bank Name..." required/>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Swift Code <small>Optional</small></label>
									<input type="text" class="form-control" name="swift_code[]" value="<?= $ba->swift_code ?>" placeholder="Swift Code..." />
								</div>
							</div>
						</div>
					</div>
					<?php endforeach?>
					<?php else:?>
					<div class="account-item">
						<div class="account-close">×</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Account Number</label>
									<input type="text" class="form-control" name="account_number[]" placeholder="Account Number..." required/>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Account Name</label>
									<input type="text" class="form-control" name="account_name[]" placeholder="Account Name..." required/>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Bank Name</label>
									<input type="text" class="form-control" name="bank_name[]" placeholder="Bank Name..." required/>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Swift Code <small>Optional</small></label>
									<input type="text" class="form-control" name="swift_code[]" placeholder="Swift Code..." />
								</div>
							</div>
						</div>
					</div>
					<?php endif?>
				</div>
            </div>
        </div>
		<style>
		.form-account{margin-bottom:1rem}
		.account-item{
			position:relative;
			padding:1rem;background-color:#fff;box-shadow:0 5px 5px 0 rgba(0,0,0,.1);
			margin-bottom:1rem
		}
		.account-close{
			position: absolute;
			top: 4px;
			right: 4px;
			width: 24px;
			height: 24px;
			background-color: #f76a6a;
			border-radius: 50%;
			padding: .6rem .45rem;
			line-height: 4px;
			color:#fff;
			font-weight:bold;
			cursor:pointer;
			z-index:999
		}
		</style>
        <div class="margin-top-3"></div>

        <div class="row mt-4">
            <div class="col-12 col-md-4">
			
			</div>

            <div class="col">
                <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->update ?></button>
            </div>
        </div>
    </form>

	<script>
	$('.btn-account').off('click').on('click',function(e){
		var frm_acc = '<div class="account-item"><div class="account-close">×</div><div class="row"><div class="col-md-6"><div class="form-group"><label>Account Number</label><input type="text" class="form-control" name="account_number[]" placeholder="Account Number..." required/></div></div><div class="col-md-6"><div class="form-group"><label>Account Name</label><input type="text" class="form-control" name="account_name[]" placeholder="Account Name..." required/></div></div></div><div class="row"><div class="col-md-6"><div class="form-group"><label>Bank Name</label><input type="text" class="form-control" name="bank_name[]" placeholder="Bank Name..." required/></div></div><div class="col-md-6"><div class="form-group"><label>Swift Code <small>Optional</small></label><input type="text" class="form-control" name="swift_code[]" placeholder="Swift Code..." /></div></div></div></div>';
		$(this).parents('.form-account-container').find('.form-account').append(frm_acc)
		account_delete()
	})
	account_delete()
	function account_delete() {
		$('.account-close').off('click').on('click',function(e){
			var ths = $(this)
			if(ths.parents('.form-account').find('.account-item').length>1) {
				ths.parents('.account-item').remove();
			}
		})
	}
	</script>
</section>
