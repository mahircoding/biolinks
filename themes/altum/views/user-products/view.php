<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h1 class="h3 mb-3"><?= $data->product->name ?></h1>
                    <p class="text-muted mb-3">Oleh: <?= $data->user->name ?></p>
                    
                    <?php if(!empty($data->product->description)): ?>
                        <div class="mb-4">
                            <?= nl2br($data->product->description) ?>
                        </div>
                    <?php endif ?>
                    
                    <div class="mb-4">
                        <strong class="h4 text-primary"><?= number_format($data->product->price_cents / 100, 2) . ' ' . $data->product->currency ?></strong>
                    </div>
                    
                    <a href="<?= url($data->user->user_id . '/' . $data->product->slug . '/checkout') ?>" class="btn btn-primary btn-lg">Beli Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</div>
