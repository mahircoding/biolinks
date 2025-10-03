<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="store/<?= $data->store_user->name ?>">
                            <i class="fa fa-store mr-1"></i><?= $data->store_user->name ?>'s Store
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $data->product->title ?></li>
                </ol>
            </nav>

            <div class="row">
                <!-- Product Image -->
                <div class="col-12 col-md-6 mb-4">
                    <div class="card">
                        <?php if($data->product->image): ?>
                            <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'products/' . $data->product->image ?>" class="card-img-top" alt="<?= $data->product->title ?>" style="height: 400px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                                <i class="fa fa-box fa-5x text-muted"></i>
                            </div>
                        <?php endif ?>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-12 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h1 class="h3 mb-3"><?= $data->product->title ?></h1>
                            
                            <div class="mb-3">
                                <span class="badge badge-primary mr-2"><?= ucfirst($data->product->category) ?></span>
                                <small class="text-muted">
                                    <i class="fa fa-eye mr-1"></i><?= $data->product->views ?> views
                                    <i class="fa fa-shopping-cart ml-2 mr-1"></i><?= $data->product->sales ?> sales
                                </small>
                            </div>

                            <p class="text-muted mb-4"><?= nl2br($data->product->description) ?></p>

                            <div class="mb-4">
                                <h2 class="h4 text-primary mb-0">Rp <?= number_format($data->product->price, 0, ',', '.') ?></h2>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="store/<?= $data->store_user->name ?>/checkout/<?= $data->product->product_id ?>" class="btn btn-primary btn-lg">
                                    <i class="fa fa-shopping-cart mr-2"></i>Buy Now
                                </a>
                            </div>

                            <hr class="my-4">

                            <div class="row text-center">
                                <div class="col-4">
                                    <i class="fa fa-download fa-2x text-primary mb-2"></i>
                                    <div class="small text-muted">Instant Download</div>
                                </div>
                                <div class="col-4">
                                    <i class="fa fa-shield-alt fa-2x text-success mb-2"></i>
                                    <div class="small text-muted">Secure Payment</div>
                                </div>
                                <div class="col-4">
                                    <i class="fa fa-headset fa-2x text-info mb-2"></i>
                                    <div class="small text-muted">24/7 Support</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <?php if(count($data->related_products)): ?>
                <div class="row mt-5">
                    <div class="col-12">
                        <h3 class="mb-4">More Products from <?= $data->store_user->name ?></h3>
                        <div class="row">
                            <?php foreach($data->related_products as $product): ?>
                                <div class="col-12 col-md-6 col-lg-3 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <?php if($product->image): ?>
                                            <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'products/' . $product->image ?>" class="card-img-top" alt="<?= $product->title ?>" style="height: 150px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <i class="fa fa-box fa-2x text-muted"></i>
                                            </div>
                                        <?php endif ?>
                                        
                                        <div class="card-body d-flex flex-column">
                                            <h6 class="card-title"><?= string_truncate($product->title, 50) ?></h6>
                                            <div class="mt-auto">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-primary font-weight-bold">Rp <?= number_format($product->price, 0, ',', '.') ?></small>
                                                    <a href="store/<?= $data->store_user->name ?>/product/<?= $product->product_id ?>" class="btn btn-outline-primary btn-sm">
                                                        View
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.d-grid {
    display: grid;
}

.gap-2 {
    gap: 0.5rem;
}
</style>