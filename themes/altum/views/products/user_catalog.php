<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg d-flex align-items-center">
                            <div>
                                <h1 class="h4 mb-1">Toko <?= $data->user->name ?></h1>
                                <p class="text-muted mb-0">
                                    <i class="fa fa-fw fa-store"></i> 
                                    Produk digital dari <?= $data->user->name ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-12 col-lg-auto d-flex align-items-center">
                            <div class="text-muted">
                                <small>
                                    <i class="fa fa-fw fa-box"></i> 
                                    <?= count($data->products) ?> produk tersedia
                                </small>
                            </div>
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
                                
                                <div class="row align-items-center mb-2">
                                    <div class="col-auto">
                                        <small class="text-muted">
                                            <i class="fa fa-fw fa-eye"></i> <?= $product->views ?? 0 ?> views
                                        </small>
                                    </div>
                                    <div class="col-auto">
                                        <small class="text-muted">
                                            <i class="fa fa-fw fa-calendar"></i> <?= date('d M Y', strtotime($product->datetime)) ?>
                                        </small>
                                    </div>
                                </div>
                                
                                <a href="<?= url('product/' . $product->product_id) ?>" class="btn btn-primary btn-block">
                                    <i class="fa fa-fw fa-shopping-cart"></i> Beli Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        
        <!-- Back to catalog link -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="<?= url('catalog') ?>" class="btn btn-outline-secondary">
                    <i class="fa fa-fw fa-arrow-left"></i> Lihat Semua Produk
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center py-5">
                    <i class="fa fa-store fa-3x text-muted mb-3"></i>
                    <h2 class="h4">Belum Ada Produk</h2>
                    <p class="text-muted"><?= $data->user->name ?> belum memiliki produk yang dijual.</p>
                    
                    <a href="<?= url('catalog') ?>" class="btn btn-outline-primary">
                        <i class="fa fa-fw fa-arrow-left"></i> Lihat Produk Lainnya
                    </a>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>

<style>
.product-card {
    transition: transform 0.2s;
    cursor: pointer;
    border: 1px solid #e9ecef;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-color: #007bff;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}

.text-primary {
    color: #007bff !important;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    font-weight: 500;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}
</style>