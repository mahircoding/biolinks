<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Store Header -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h1 class="h3 mb-2"><?= $data->store_user->name ?>'s Digital Store</h1>
                    <p class="text-muted mb-3">Welcome to my digital products store</p>
                    
                    <div class="row text-center">
                        <div class="col-6 col-md-3">
                            <div class="border-right">
                                <h4 class="mb-0"><?= $data->stats['total_products'] ?></h4>
                                <small class="text-muted">Products</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <h4 class="mb-0"><?= $data->stats['total_sales'] ?></h4>
                            <small class="text-muted">Sales</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <?php if(count($data->products)): ?>
                <div class="row">
                    <?php foreach($data->products as $product): ?>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <?php if($product->image): ?>
                                    <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'products/' . $product->image ?>" class="card-img-top" alt="<?= $product->title ?>" style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fa fa-box fa-3x text-muted"></i>
                                    </div>
                                <?php endif ?>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= $product->title ?></h5>
                                    <p class="card-text text-muted flex-grow-1"><?= string_truncate($product->description, 100) ?></p>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge badge-primary"><?= ucfirst($product->category) ?></span>
                                            <small class="text-muted">
                                                <i class="fa fa-eye mr-1"></i><?= $product->views ?> views
                                            </small>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 text-primary">Rp <?= number_format($product->price, 0, ',', '.') ?></h5>
                                            <a href="store/<?= $data->store_user->name ?>/product/<?= $product->product_id ?>" class="btn btn-primary btn-sm">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fa fa-box fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Products Available</h4>
                        <p class="text-muted">This store doesn't have any products yet.</p>
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

.border-right {
    border-right: 1px solid #dee2e6;
}

@media (max-width: 767.98px) {
    .border-right {
        border-right: none;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
}
</style>