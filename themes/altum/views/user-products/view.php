<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?php if(!empty($data->product->image_path)): ?>
                                <img src="<?= url($data->product->image_path) ?>" alt="<?= htmlspecialchars($data->product->name) ?>" class="img-fluid rounded mb-3">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" style="height: 200px;">
                                    <i class="fa fa-image text-muted fa-3x"></i>
                                </div>
                            <?php endif ?>
                        </div>
                        <div class="col-md-8">
                            <h1 class="h3 mb-3"><?= $data->product->name ?></h1>
                            <p class="text-muted mb-3">Oleh: <?= $data->user->name ?></p>
                            
                            <div class="mb-4">
                                <strong class="h4 text-primary">Rp <?= number_format($data->product->price_cents / 100, 0, ',', '.') ?></strong>
                            </div>
                            
                            <a href="<?= url($data->user->user_id . '/' . $data->product->slug . '/checkout') ?>" class="btn btn-primary btn-lg">Beli Sekarang</a>
                        </div>
                    </div>
                    
                    <?php if(!empty($data->product->description)): ?>
                        <hr class="my-4">
                        <div class="mb-4">
                            <h5>Deskripsi Produk</h5>
                            <div class="text-muted">
                                <?= $data->product->description ?>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>
