<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fa fa-shopping-cart"></i> Konfirmasi Pembelian
                    </h4>
                </div>
                
                <div class="card-body">
                    <!-- Product Info -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <?php if($data->product->image): ?>
                                <img src="<?= SITE_URL . 'uploads/products/' . $data->product->image ?>" 
                                     alt="<?= $data->product->name ?>" 
                                     class="img-fluid rounded shadow-sm">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fa fa-image fa-3x text-muted"></i>
                                </div>
                            <?php endif ?>
                        </div>
                        
                        <div class="col-md-8">
                            <h3 class="h4 font-weight-bold"><?= $data->product->name ?></h3>
                            <p class="text-muted mb-3"><?= string_truncate($data->product->description, 150) ?></p>
                            
                            <div class="d-flex align-items-center mb-3">
                                <span class="h3 font-weight-bold text-primary mb-0">
                                    <?= format_idr($data->product->price) ?>
                                </span>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-right">
                                        <div class="h5 font-weight-bold text-primary"><?= $data->product->views ?></div>
                                        <small class="text-muted">Views</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-right">
                                        <div class="h5 font-weight-bold text-success"><?= $data->product->sales ?></div>
                                        <small class="text-muted">Sales</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="h5 font-weight-bold text-info">
                                        <?= number_format(($data->product->sales / max($data->product->views, 1)) * 100, 1) ?>%
                                    </div>
                                    <small class="text-muted">Conversion</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Order Form -->
                    <form method="post" action="<?= url('orders/create/' . $data->product->product_id) ?>">
                        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
                        
                        <?php if(!$this->user): ?>
                            <!-- Guest Customer Information -->
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> 
                                <strong>Informasi Pembeli</strong><br>
                                Silakan isi informasi Anda untuk melanjutkan pembelian.
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_name">Nama Lengkap *</label>
                                        <input type="text" 
                                               id="customer_name" 
                                               name="customer_name" 
                                               class="form-control" 
                                               placeholder="Masukkan nama lengkap Anda"
                                               required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_phone">Nomor Handphone *</label>
                                        <input type="tel" 
                                               id="customer_phone" 
                                               name="customer_phone" 
                                               class="form-control" 
                                               placeholder="08xxxxxxxxxx"
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="customer_email">Email *</label>
                                <input type="email" 
                                       id="customer_email" 
                                       name="customer_email" 
                                       class="form-control" 
                                       placeholder="email@example.com"
                                       required>
                                <small class="text-muted">Email akan digunakan untuk mengirim detail pembelian dan akses produk.</small>
                            </div>
                        <?php else: ?>
                            <!-- Logged in user info -->
                            <div class="alert alert-success">
                                <i class="fa fa-user-check"></i> 
                                <strong>Pembeli:</strong> <?= $this->user->name ?> (<?= $this->user->email ?>)
                            </div>
                        <?php endif ?>
                        
                        <!-- Order Summary -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Ringkasan Pesanan</h5>
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span><?= $data->product->name ?></span>
                                    <span class="font-weight-bold"><?= format_idr($data->product->price) ?></span>
                                </div>
                                
                                <hr>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0">Total</span>
                                    <span class="h4 font-weight-bold text-primary mb-0"><?= format_idr($data->product->price) ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="agree_terms" required>
                            <label class="form-check-label" for="agree_terms">
                                Saya setuju dengan <a href="#" target="_blank">syarat dan ketentuan</a> yang berlaku.
                            </label>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?= url('products/product/' . $data->product->product_id) ?>" 
                                   class="btn btn-outline-secondary btn-block">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <i class="fa fa-credit-card"></i> Lanjut ke Pembayaran
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.form-control {
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    padding: 12px 15px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    border-radius: 10px;
    padding: 12px 20px;
    font-weight: 500;
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
    transform: translateY(-1px);
}

.alert {
    border-radius: 10px;
    border: none;
}
</style>