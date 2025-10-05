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
                <th>Nama</th>
                <th>Slug</th>
                <th>Harga</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($data->products as $product): ?>
            <tr>
                <td><?= $product->name ?></td>
                <td><?= $product->slug ?></td>
                <td><?= number_format($product->price_cents / 100, 2) . ' ' . $product->currency ?></td>
                <td class="text-right">
                    <form action="digital-product/delete" method="post" class="d-inline">
                        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
                        <input type="hidden" name="product_id" value="<?= (int)$product->product_id ?>" />
                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                    <a class="btn btn-sm btn-outline-secondary" target="_blank" href="digital-order/<?= $product->slug ?>">Lihat Halaman</a>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    </div>

</section>


