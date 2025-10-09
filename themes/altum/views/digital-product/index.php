<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container pt-5">
    <div class="d-flex">
        <h1 class="h3">Digital Products</h1>
    </div>

<?php display_notifications() ?>

<div class="mt-4">
    <a href="digital-product/create" class="btn btn-primary">Tambah Produk</a>
</div>

<div class="table-responsive mt-4">
    <table class="table">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Harga</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if(empty($data->products)): ?>
            <tr>
                <td colspan="4" class="text-center py-5">
                    <div class="text-muted">
                        <i class="fa fa-box-open fa-3x mb-3"></i>
                        <h5>Belum ada produk digital</h5>
                        <p>Mulai buat produk digital pertama Anda untuk menjual konten digital secara online.</p>
                        <a href="digital-product/create" class="btn btn-primary">
                            <i class="fa fa-plus mr-2"></i>
                            Buat Produk Pertama
                        </a>
                    </div>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach($data->products as $product): ?>
                <tr>
                    <td>
                        <?php if(!empty($product->image_path)): ?>
                            <img src="<?= url($product->image_path) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fa fa-image text-muted"></i>
                            </div>
                        <?php endif ?>
                    </td>
                    <td>
                        <strong><?= $product->name ?></strong><br>
                        <small class="text-muted">Slug: <?= $product->slug ?></small>
                    </td>
                    <td>Rp <?= number_format($product->price_cents, 0, ',', '.') ?></td>
                    <td class="text-right">
                        <a href="digital-product/edit/<?= (int)$product->product_id ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="digital-product/delete" method="post" class="d-inline">
                            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
                            <input type="hidden" name="product_id" value="<?= (int)$product->product_id ?>" />
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                        <a class="btn btn-sm btn-outline-secondary" target="_blank" href="<?= url($this->user->user_id . '/' . $product->slug) ?>">Lihat Halaman</a>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php endif ?>
        </tbody>
    </table>
    </div>

</section>


