<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-box text-gray-700"></i> <?= 'Digital Products' ?></h1>
</div>

<?php display_notifications() ?>

<div class="row mt-5">
    <?php if(!empty($data->products)): ?>
        <?php foreach($data->products as $product): ?>
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <?php if($product->image): ?>
                        <img src="<?= url(UPLOADS_URL_PATH . 'products/' . $product->image) ?>" class="card-img-top" alt="<?= htmlspecialchars($product->name) ?>" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fa fa-fw fa-box fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($product->name) ?></h5>
                        <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($product->description, 0, 100)) ?><?= strlen($product->description) > 100 ? '...' : '' ?></p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-primary mb-0"><?= $product->price . ' ' . $this->settings->payment->currency ?></span>
                                <small class="text-muted">by <?= htmlspecialchars($product->seller_name) ?></small>
                            </div>
                            <a href="<?= url('digital-product/' . $product->product_id) ?>" class="btn btn-primary btn-block mt-3">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fa fa-fw fa-info-circle"></i> No digital products available at the moment.
            </div>
        </div>
    <?php endif; ?>
</div>