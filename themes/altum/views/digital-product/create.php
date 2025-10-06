<?php defined('ALTUMCODE') || die() ?>

<header class="header pb-0">
    <div class="container">
        <?= $this->views['account_header'] ?>
    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container pt-5">
    <div class="d-flex">
        <h1 class="h3">Tambah Produk Digital</h1>
    </div>

<?php display_notifications() ?>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" required />
            </div>

            <div class="form-group">
                <label>Gambar Produk</label>
                <input type="file" name="image" class="form-control-file" accept="image/*" />
                <small class="text-muted">Upload gambar untuk produk Anda (opsional)</small>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" id="description" class="form-control" rows="5"></textarea>
            </div>

            <div class="form-group">
                <label>Harga (dalam sen, mis. 50000 = 500.00)</label>
                <input type="number" name="price_cents" class="form-control" min="0" step="1" required />
            </div>

            <div class="form-group">
                <label>URL Akses Produk (eksternal)</label>
                <input type="url" name="access_url" class="form-control" placeholder="https://contoh.com/akses-produk" required />
                <small class="text-muted">Pembeli akan diarahkan ke URL ini setelah validasi token.</small>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="digital-product" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
    </div>
</div>

</section>

<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/tinymce/tinymce.min.js' ?>"></script>
<script>
    tinymce.init({
        selector: '#description',
        plugins: 'code preview autolink directionality visualblocks visualchars fullscreen image link media codesample table hr pagebreak nonbreaking toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent | removeformat code',
        height: 300
    });
</script>


