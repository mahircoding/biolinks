<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card border-0">
                <div class="card-body">
                    <?php if($data->product->image): ?>
                        <img src="<?= SITE_URL . 'uploads/products/' . $data->product->image ?>" class="img-fluid rounded mb-4" alt="<?= $data->product->name ?>">
                    <?php endif ?>
                    
                    <h1 class="h3"><?= $data->product->name ?></h1>
                    
                    <?php if($data->product_owner): ?>
                        <p class="text-muted mb-3">
                            <i class="fa fa-fw fa-user"></i> by <?= $data->product_owner->name ?>
                        </p>
                    <?php endif ?>
                    
                    <div class="mb-4">
                        <span class="h4 text-primary"><?= format_idr($data->product->price) ?></span>
                        <span class="text-muted ml-2">
                            <i class="fa fa-fw fa-eye"></i> <?= $data->product->views ?> views
                            <i class="fa fa-fw fa-shopping-cart ml-2"></i> <?= $data->product->sales ?? 0 ?> sales
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Description</h5>
                        <p><?= nl2br($data->product->description) ?></p>
                    </div>
                    
                    <?php if($data->has_purchased): ?>
                        <div class="alert alert-success">
                            <i class="fa fa-fw fa-check-circle"></i>
                            You have already purchased this product!
                            
                            <?php if($data->product->digital_link): ?>
                                <div class="mt-2">
                                    <a href="<?= $data->product->digital_link ?>" class="btn btn-success" target="_blank">
                                        <i class="fa fa-fw fa-download"></i> Access Product
                                    </a>
                                </div>
                            <?php endif ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <div class="card border-0">
                <div class="card-body">
                    <h5 class="card-title">Purchase this product</h5>
                    
                    <div class="mb-3">
                        <div class="h4 text-primary"><?= format_idr($data->product->price) ?></div>
                        <small class="text-muted">One-time payment</small>
                    </div>
                    
                    <?php if($data->has_purchased): ?>
                        <p class="text-success">
                            <i class="fa fa-fw fa-check-circle"></i>
                            Already purchased
                        </p>
                        
                        <?php if($data->product->digital_link): ?>
                            <a href="<?= $data->product->digital_link ?>" class="btn btn-success btn-block" target="_blank">
                                <i class="fa fa-fw fa-download"></i> Access Product
                            </a>
                        <?php endif ?>
                    <?php else: ?>
                        <form action="<?= url('orders/create/' . $data->product->product_id) ?>" method="post" id="purchase-form">
                            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
                            
                            <?php if(!$this->user): ?>
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" required maxlength="128" placeholder="Masukkan nama lengkap Anda">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="customer_email" name="customer_email" required maxlength="320" placeholder="Masukkan email Anda">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">No. Handphone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required maxlength="20" placeholder="Contoh: 081234567890">
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fa fa-info-circle"></i> 
                                        Detail produk akan dikirim ke email Anda setelah pembayaran berhasil.
                                    </small>
                                </div>
                            <?php endif ?>
                            
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fa fa-fw fa-shopping-cart"></i> Beli Sekarang
                            </button>
                        </form>
                        
                        <?php if(!$this->user): ?>
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    Sudah punya akun? <a href="<?= url('login') ?>">Login disini</a>
                                </small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                    
                    <hr>
                    
                    <div class="small text-muted">
                        <h6>What you'll get:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fa fa-fw fa-check text-success"></i> Instant access after payment</li>
                            <li><i class="fa fa-fw fa-check text-success"></i> Lifetime access to the product</li>
                            <li><i class="fa fa-fw fa-check text-success"></i> Email confirmation with details</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="card-title">Product Details</h6>
                    <small class="text-muted">
                        <div class="mb-2">
                            <strong>Product ID:</strong> <?= $data->product->product_id ?>
                        </div>
                        <div class="mb-2">
                            <strong>Created:</strong> <?= \Altum\Date::get($data->product->datetime, 1) ?>
                        </div>
                        <div class="mb-2">
                            <strong>Views:</strong> <?= $data->product->views ?>
                        </div>
                        <div>
                            <strong>Sales:</strong> <?= $data->product->sales ?? 0 ?>
                        </div>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>