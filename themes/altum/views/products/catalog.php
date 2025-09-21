<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg d-flex align-items-center">
                            <h1 class="h4">Digital Products Catalog</h1>
                        </div>
                        
                        <div class="col-12 col-lg-auto d-flex">
                            <form method="get" role="form">
                                <div class="input-group">
                                    <input type="search" name="search" class="form-control" placeholder="Search products..." value="<?= $_GET['search'] ?? '' ?>" />
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="fa fa-fw fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(count($data->products)): ?>
        <div class="row">
            <?php foreach($data->products as $product): ?>
                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                    <div class="card h-100 product-card">
                        <?php if($product->image): ?>
                            <img src="<?= SITE_URL . 'uploads/products/' . $product->image ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= $product->name ?>">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fa fa-image fa-3x text-muted"></i>
                            </div>
                        <?php endif ?>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= $product->name ?></h5>
                            <p class="card-text text-muted small"><?= string_truncate($product->description, 100) ?></p>
                            
                            <?php if($product->seller_name): ?>
                                <small class="text-muted mb-2">
                                    <i class="fa fa-fw fa-user"></i> by <?= $product->seller_name ?>
                                </small>
                            <?php endif ?>
                            
                            <div class="mt-auto">
                                <div class="row align-items-center mb-3">
                                    <div class="col">
                                        <span class="h5 text-primary mb-0"><?= format_idr($product->price) ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <small class="text-muted">
                                            <i class="fa fa-fw fa-shopping-cart"></i> <?= $product->sales ?? 0 ?>
                                        </small>
                                    </div>
                                </div>
                                
                                <a href="<?= url('products/view/' . $product->product_id) ?>" class="btn btn-primary btn-block">
                                    <i class="fa fa-fw fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center py-5">
                    <i class="fa fa-search fa-3x text-muted mb-3"></i>
                    <h2 class="h4">No products found</h2>
                    <p class="text-muted">Try adjusting your search terms or browse all products.</p>
                    
                    <?php if(isset($_GET['search'])): ?>
                        <a href="<?= url('products/catalog') ?>" class="btn btn-outline-primary">
                            <i class="fa fa-fw fa-arrow-left"></i> View All Products
                        </a>
                    <?php endif ?>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>

<style>
.product-card {
    transition: transform 0.2s;
    cursor: pointer;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>