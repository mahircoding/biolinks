<?php defined('ALTUMCODE') || die() ?>

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
                <label>Slug (untuk URL)</label>
                <input type="text" name="slug" class="form-control" required />
                <small class="text-muted">Contoh: ebook-laris</small>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control" rows="5"></textarea>
            </div>

            <div class="form-group">
                <label>Harga (dalam sen, mis. 50000 = 500.00)</label>
                <input type="number" name="price_cents" class="form-control" min="0" step="1" required />
            </div>

            <div class="form-group">
                <label>Mata Uang</label>
                <input type="text" name="currency" class="form-control" value="IDR" />
            </div>

            <div class="form-group">
                <label>Berkas Produk (ZIP/PDF/dll.)</label>
                <input type="file" name="file" class="form-control" required />
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="digital-product" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>


