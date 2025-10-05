<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-box text-gray-700"></i> <?= htmlspecialchars($data->product->name) ?></h1>
    
    <div class="col-auto p-0">
        <a href="<?= url('digital-products') ?>" class="btn btn-outline-secondary rounded-pill"><i class="fa fa-fw fa-arrow-left"></i> <?= 'Back to Products' ?></a>
    </div>
</div>

<?php display_notifications() ?>

<div class="row mt-5">
    <div class="col-12 col-lg-6 mb-4 mb-lg-0">
        <?php if($data->product->image): ?>
            <img src="<?= url(UPLOADS_URL_PATH . 'products/' . $data->product->image) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($data->product->name) ?>">
        <?php else: ?>
            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 400px;">
                <i class="fa fa-fw fa-box fa-5x text-muted"></i>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h2 class="h4"><?= htmlspecialchars($data->product->name) ?></h2>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="h3 text-primary mb-0"><?= $data->product->price . ' ' . $this->settings->payment->currency ?></span>
                    <small class="text-muted">Sold by <?= htmlspecialchars($data->seller->name) ?></small>
                </div>
                
                <div class="mb-4">
                    <h3 class="h5">Description</h3>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($data->product->description)) ?></p>
                </div>
                
                <a href="<?= url('digital-checkout/' . $data->product->product_id) ?>" class="btn btn-primary btn-lg btn-block">
                    <i class="fa fa-fw fa-shopping-cart"></i> <?= 'Buy Now' ?>
                </a>
            </div>
        </div>
    </div>
</div>