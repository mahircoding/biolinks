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
                <label>Nama Produk</label>
                <input type="text" name="name" class="form-control" required />
            </div>

            <div class="form-group">
                <label>Gambar Produk</label>
                <input type="file" name="image" class="form-control" accept="image/*" />
                <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" id="description" class="form-control" rows="8"></textarea>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Harga (Rupiah)</label>
                        <input type="number" name="price_cents" class="form-control" min="0" step="100" placeholder="50000" required />
                        <small class="text-muted">Masukkan harga dalam rupiah (contoh: 50000 = Rp 50.000)</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Mata Uang</label>
                        <input type="text" name="currency" class="form-control" value="IDR" readonly />
                    </div>
                </div>
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

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#description',
    height: 300,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }'
});
</script>

