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
                                <div class="card-body">
                                    <h5 class="card-title"><?= $product->name ?></h5>
                                    <p class="card-text"><?= nl2br($product->description) ?></p>
                                    <div class="mb-3">
                                        <strong class="text-primary"><?= number_format($product->price_cents / 100, 2) . ' ' . $product->currency ?></strong>
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
