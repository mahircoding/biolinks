<?php defined('ALTUMCODE') || die() ?>

<header class="header pb-0">
    <div class="container">
        <?= $this->views['account_header'] ?>
    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container pt-5">
    <div class="d-flex">
        <h1 class="h3">Edit Produk Digital</h1>
    </div>

<?php display_notifications() ?>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data->product->name) ?>" required />
            </div>

            <div class="form-group">
                <label>Gambar Produk</label>
                <?php if(!empty($data->product->image_path)): ?>
                    <div class="mb-2">
                        <img src="<?= url($data->product->image_path) ?>" alt="Current image" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                        <br><small class="text-muted">Gambar saat ini</small>
                    </div>
                <?php endif ?>
                <input type="file" name="image" class="form-control" accept="image/*" />
                <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" id="description" class="form-control" rows="8"><?= $data->product->description ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Harga (Rupiah)</label>
                        <input type="number" name="price_cents" class="form-control" min="0" step="100" value="<?= (int)$data->product->price_cents ?>" required />
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
                <input type="url" name="access_url" class="form-control" value="<?= htmlspecialchars($data->product->access_url) ?>" placeholder="https://contoh.com/akses-produk" required />
                <small class="text-muted">Pembeli akan diarahkan ke URL ini setelah validasi token.</small>
            </div>

            <div class="form-group">
                <label>Slug (untuk URL)</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data->product->slug) ?>" readonly />
                <small class="text-muted">Slug tidak dapat diubah setelah produk dibuat.</small>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update Produk</button>
                <a href="digital-product" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
    </div>
</div>

</section>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
ClassicEditor
    .create( document.querySelector( '#description' ), {
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'link', '|',
                'undo', 'redo'
            ]
        },
        language: 'id'
    } )
    .catch( error => {
        console.error( error );
    } );
</script>
