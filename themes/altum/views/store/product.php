<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php display_notifications() ?>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('store/' . $user->username) ?>"><?= $user->name ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $product['name'] ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 h-100">
                <?php if($product['image']): ?>
                    <img src="<?= UPLOADS_URL_PATH . 'products/' . $product['image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>" style="max-height: 400px; object-fit: contain;">
                <?php else: ?>
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                        <i class="fa fa-file fa-5x text-muted"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <h1 class="h3 mb-3"><?= $product['name'] ?></h1>
                    
                    <div class="d-flex align-items-center mb-3">
                        <span class="text-primary h4 mb-0 font-weight-bold">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                        <span class="badge badge-secondary ml-3"><?= nr($product['sales']) ?> sales</span>
                    </div>

                    <div class="mb-4">
                        <h5 class="h6 mb-2">Description</h5>
                        <p class="text-muted"><?= nl2br($product['description']) ?></p>
                    </div>

                    <div class="mb-4">
                        <h5 class="h6 mb-2">Product Details</h5>
                        <ul class="list-unstyled text-muted small">
                            <li><i class="fa fa-check text-success mr-2"></i>Instant Digital Delivery</li>
                            <li><i class="fa fa-check text-success mr-2"></i>Access Link via Email</li>
                            <li><i class="fa fa-check text-success mr-2"></i>Secure Download</li>
                        </ul>
                    </div>

                    <?php if($user->id): ?>
                        <div class="mb-4">
                            <h5 class="h6 mb-2">Seller Information</h5>
                            <div class="d-flex align-items-center">
                                <?php if($user->image): ?>
                                    <img src="<?= UPLOADS_URL_PATH . 'users/' . $user->image ?>" class="rounded-circle mr-3" width="40" height="40" alt="<?= $user->name ?>">
                                <?php else: ?>
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                        <i class="fa fa-user fa-sm"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="font-weight-bold"><?= $user->name ?></div>
                                    <small class="text-muted"><?= $user->email ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid">
                        <?php if($user->id): ?>
                            <a href="<?= url('orders/create/' . $product['product_id']) ?>" class="btn btn-primary btn-lg">
                                <i class="fa fa-shopping-cart mr-2"></i>Buy Now
                            </a>
                        <?php else: ?>
                            <a href="<?= url('login') ?>?redirect=<?= urlencode(url('store/' . $user->username . '/product/' . $product['product_id'])) ?>" class="btn btn-primary btn-lg">
                                <i class="fa fa-sign-in-alt mr-2"></i>Login to Purchase
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="h6 mb-3">About This Product</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted small">Delivery Method</h6>
                            <p class="mb-0">Digital delivery via secure download link sent to your email after purchase completion.</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">Refund Policy</h6>
                            <p class="mb-0">Due to the digital nature of this product, all sales are final. No refunds will be provided after purchase.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>