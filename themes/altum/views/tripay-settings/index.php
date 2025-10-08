<?php defined('ALTUMCODE') || die() ?>

<header class="header pb-0">
    <div class="container">
        <?= $this->views['account_header'] ?>
    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container pt-5">
    <div class="d-flex">
        <h1 class="h3">Tripay Settings</h1>
    </div>

    <?php display_notifications() ?>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <form action="" method="post">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <div class="form-group">
                    <label>Merchant Code</label>
                    <input type="text" name="tripay_merchant_code" class="form-control" value="<?= htmlspecialchars($data->tripay_settings->tripay_merchant_code ?? '') ?>" placeholder="Masukkan Merchant Code Tripay" />
                    <small class="text-muted">Merchant Code dari dashboard Tripay Anda</small>
                </div>

                <div class="form-group">
                    <label>API Key Public</label>
                    <input type="text" name="tripay_api_key_public" class="form-control" value="<?= htmlspecialchars($data->tripay_settings->tripay_api_key_public ?? '') ?>" placeholder="Masukkan API Key Public" />
                    <small class="text-muted">API Key Public dari dashboard Tripay Anda</small>
                </div>

                <div class="form-group">
                    <label>API Key Secret</label>
                    <input type="text" name="tripay_api_key_secret" class="form-control" value="<?= htmlspecialchars($data->tripay_settings->tripay_api_key_secret ?? '') ?>" placeholder="Masukkan API Key Secret" />
                    <small class="text-muted">API Key Secret dari dashboard Tripay Anda (akan disembunyikan untuk keamanan)</small>
                </div>

                <div class="alert alert-info">
                    <i class="fa fa-info-circle mr-2"></i>
                    <strong>Callback URL:</strong><br>
                    <code><?= url('tripay-callback') ?></code><br>
                    <small>Gunakan URL ini sebagai callback URL di dashboard Tripay Anda.</small>
                </div>

                <div class="alert alert-info">
                    <i class="fa fa-info-circle mr-2"></i>
                    <strong>Informasi:</strong> Data Tripay ini akan digunakan untuk proses pembayaran produk digital Anda. Pastikan data yang dimasukkan sudah benar.
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Settings</button>
                    <a href="dashboard" class="btn btn-light">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>
