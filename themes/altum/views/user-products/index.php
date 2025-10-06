<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">Produk Digital dari <?= $data->user->name ?></h1>
            
            <?php if(empty($data->products)): ?>
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada produk yang tersedia.</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach($data->products as $product): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <?php if(!empty($product->image_path)): ?>
                                    <img src="<?= url($product->image_path) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fa fa-image text-muted fa-3x"></i>
                                    </div>
                                <?php endif ?>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= $product->name ?></h5>
                                    <p class="card-text text-muted small"><?= substr(strip_tags($product->description), 0, 100) ?><?= strlen(strip_tags($product->description)) > 100 ? '...' : '' ?></p>
                                    <div class="mb-3 mt-auto">
                                        <strong class="text-primary h5">Rp <?= number_format($product->price_cents / 100, 0, ',', '.') ?></strong>
                                    </div>
                                    <a href="<?= url($data->user->user_id . '/' . $product->slug) ?>" class="btn btn-primary">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
