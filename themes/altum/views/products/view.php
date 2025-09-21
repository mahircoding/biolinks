<?php defined('ALTUMCODE') || die() ?>

<!-- Product Page Custom Styles -->
<style>
/* Product Page Enhancements */
.product-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
    border: none !important;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}

.purchase-card {
    background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 20px;
    transition: all 0.3s ease;
    border: none !important;
}

.purchase-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.btn-purchase {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
    border: none !important;
    border-radius: 50px !important;
    padding: 15px 30px !important;
    font-weight: bold !important;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,123,255,0.3) !important;
    color: white !important;
}

.btn-purchase:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,123,255,0.4) !important;
    color: white !important;
}

.price-highlight {
    background: linear-gradient(45deg, #28a745, #20c997);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 900 !important;
}

.product-stats {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 20px;
    margin: 20px 0;
}

.stat-item {
    text-align: center;
    padding: 15px;
    border-radius: 10px;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.features-list li {
    padding: 8px 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.features-list li:hover {
    background: rgba(0,123,255,0.05);
    padding-left: 10px;
}

.security-badge {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    color: white !important;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(40,167,69,0.2);
}

.guest-info-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 25px;
    margin: 20px 0;
    border: 2px dashed #dee2e6;
    transition: all 0.3s ease;
}

.guest-info-section:hover {
    border-color: #007bff;
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
}

.form-control-lg {
    border-radius: 10px !important;
    border: 2px solid #e9ecef !important;
    transition: all 0.3s ease;
}

.form-control-lg:focus {
    border-color: #007bff !important;
    box-shadow: 0 0 20px rgba(0,123,255,0.2) !important;
    transform: translateY(-2px);
}

.fade-in {
    opacity: 0;
    animation: fadeIn 0.6s ease forwards;
}

.slide-up {
    transform: translateY(30px);
    opacity: 0;
    animation: slideUp 0.6s ease forwards;
}

@keyframes fadeIn {
    to { opacity: 1; }
}

@keyframes slideUp {
    to { transform: translateY(0); opacity: 1; }
}

@media (max-width: 767.98px) {
    .product-stats .row { flex-direction: column; }
    .stat-item { margin-bottom: 10px; }
    .purchase-card { margin-top: 2rem; }
    .guest-info-section { padding: 20px; }
    .security-badge { padding: 15px; margin-bottom: 1rem; }
}

/* Form Validation Styles */
.form-control.is-valid {
    border-color: #28a745 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.7-.7L4.6 4.47l2.6-2.6-.7-.7L4.6 3.07 2.3 5.37l-.7.7z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.form-control.is-invalid {
    border-color: #dc3545 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 2.4 2.4m-2.4 0 2.4-2.4'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.invalid-feedback {
    display: block !important;
    font-size: 0.8rem;
    color: #dc3545;
    margin-top: 0.25rem;
}

/* Ensure clickable elements */
button, input, a {
    pointer-events: auto !important;
}

/* Debug styles for troubleshooting */
#purchase-form {
    position: relative !important;
    z-index: 10 !important;
}

button[type="submit"] {
    cursor: pointer !important;
    pointer-events: auto !important;
    position: relative !important;
    z-index: 9999 !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
}

/* Force button clickability */
#buy-now-btn {
    cursor: pointer !important;
    pointer-events: auto !important;
    z-index: 99999 !important;
    position: relative !important;
    display: block !important;
    width: 100% !important;
    height: auto !important;
    min-height: 50px !important;
    outline: none !important;
    border: none !important;
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
    color: white !important;
}

#buy-now-btn:hover {
    background: linear-gradient(45deg, #0056b3, #003d82) !important;
    color: white !important;
}

#buy-now-btn:focus {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
    color: white !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.25) !important;
}

/* Ensure no overlay blocks the button */
.purchase-card .card-body {
    position: relative !important;
    z-index: 1 !important;
}

.purchase-card .card-body > * {
    position: relative !important;
    z-index: 2 !important;
}
</style>

<div class="container">
    <!-- Product Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url() ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('products/catalog') ?>">Produk</a></li>
                    <li class="breadcrumb-item active"><?= $data->product->name ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Product Info Section -->
        <div class="col-12 col-lg-8">
            <div class="card product-card shadow-sm border-0 fade-in">
                <div class="card-body p-4">
                    <!-- Product Image -->
                    <?php if($data->product->image): ?>
                        <div class="text-center mb-4">
                            <img src="<?= SITE_URL . 'uploads/products/' . $data->product->image ?>" 
                                 class="img-fluid rounded shadow slide-up" 
                                 alt="<?= $data->product->name ?>"
                                 style="max-height: 400px; object-fit: cover; border-radius: 20px;">
                        </div>
                    <?php endif ?>
                    
                    <!-- Product Title & Seller -->
                    <div class="mb-4 slide-up">
                        <h1 class="h2 font-weight-bold text-dark mb-2"><?= $data->product->name ?></h1>
                        
                        <?php if($data->product_owner): ?>
                            <div class="d-flex align-items-center text-muted mb-3">
                                <i class="fa fa-user-circle fa-fw mr-2 text-primary"></i>
                                <span>Dijual oleh <strong class="text-primary"><?= $data->product_owner->name ?></strong></span>
                            </div>
                        <?php endif ?>
                        
                        <!-- Price & Stats -->
                        <div class="product-stats">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <span class="h3 price-highlight mb-0"><?= format_idr($data->product->price) ?></span>
                                    <small class="text-muted d-block">Pembayaran satu kali</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="stat-item">
                                                <i class="fa fa-eye text-info mb-1"></i>
                                                <div class="font-weight-bold text-dark"><?= $data->product->views ?></div>
                                                <small class="text-muted">Dilihat</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="stat-item">
                                                <i class="fa fa-shopping-cart text-success mb-1"></i>
                                                <div class="font-weight-bold text-dark"><?= $data->product->sales ?? 0 ?></div>
                                                <small class="text-muted">Terjual</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Product Description -->
                    <div class="mb-4 slide-up">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fa fa-info-circle fa-fw text-primary"></i> Deskripsi Produk
                        </h5>
                        <div class="bg-white p-4 rounded-lg border shadow-sm">
                            <p class="mb-0 text-justify" style="line-height: 1.8; font-size: 1.05rem;"><?= nl2br($data->product->description) ?></p>
                        </div>
                    </div>
                    
                    <!-- Already Purchased Alert -->
                    <?php if($data->has_purchased): ?>
                        <div class="alert alert-success border-0 shadow-sm slide-up" style="border-radius: 15px;">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-check-circle fa-3x text-success mr-3"></i>
                                <div>
                                    <h5 class="alert-heading mb-2 font-weight-bold">Anda sudah membeli produk ini!</h5>
                                    <p class="mb-0">Terima kasih atas pembelian Anda. Akses produk Anda di bawah ini.</p>
                                </div>
                            </div>
                            
                            <?php if($data->product->digital_link): ?>
                                <div class="mt-4 text-center">
                                    <a href="<?= $data->product->digital_link ?>" class="btn btn-success btn-lg shadow" target="_blank" style="border-radius: 50px; padding: 15px 40px;">
                                        <i class="fa fa-fw fa-download"></i> Akses Produk Sekarang
                                    </a>
                                </div>
                            <?php endif ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
        
        <!-- Purchase Section -->
        <div class="col-12 col-lg-4">
            <div class="sticky-top slide-up" style="top: 20px;">
                <!-- Purchase Card -->
                <div class="card purchase-card shadow border-0 mb-4">
                    <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border-radius: 20px 20px 0 0;">
                        <h5 class="mb-0 font-weight-bold">
                            <i class="fa fa-shopping-cart fa-fw"></i> Beli Produk Ini
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Price Display -->
                        <div class="text-center mb-4">
                            <div class="h2 price-highlight mb-2"><?= format_idr($data->product->price) ?></div>
                            <div class="badge badge-info px-3 py-2" style="font-size: 0.9rem; border-radius: 20px;">
                                <i class="fa fa-infinity fa-fw"></i> Pembayaran sekali seumur hidup
                            </div>
                        </div>
                        
                        <?php if($data->has_purchased): ?>
                            <!-- Already Purchased -->
                            <div class="text-center">
                                <div class="alert border-0 mb-3" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-radius: 15px;">
                                    <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                                    <div class="font-weight-bold h6 text-success">Sudah Dibeli</div>
                                    <small class="text-muted">Anda sudah memiliki produk ini</small>
                                </div>
                                
                                <?php if($data->product->digital_link): ?>
                                    <a href="<?= $data->product->digital_link ?>" class="btn btn-success btn-lg btn-block shadow-sm" target="_blank" style="border-radius: 50px; padding: 15px;">
                                        <i class="fa fa-fw fa-download"></i> Akses Produk
                                    </a>
                                <?php endif ?>
                            </div>
                        <?php else: ?>
                            <!-- Purchase Form -->
                            <form action="<?= url('orders/create/' . $data->product->product_id) ?>" 
                                  method="post" 
                                  id="purchase-form" 
                                  style="position: relative; z-index: 10;"
                                  onsubmit="console.log('Form submitted!'); return validateForm();">
                                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
                                
                                <?php if(!$this->user): ?>
                                    <!-- Guest Checkout Fields -->
                                    <div class="guest-info-section">
                                        <h6 class="text-dark mb-4 text-center">
                                            <i class="fa fa-user-plus fa-fw text-primary"></i> Informasi Pembeli
                                        </h6>
                                        
                                        <div class="mb-3">
                                            <label for="customer_name" class="form-label font-weight-bold text-dark">
                                                <i class="fa fa-user fa-fw text-muted"></i> Nama Lengkap <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control form-control-lg" 
                                                   id="customer_name" name="customer_name" 
                                                   required maxlength="128" 
                                                   placeholder="Masukkan nama lengkap Anda"
                                                   style="border-radius: 15px; z-index: 5; position: relative;">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="customer_email" class="form-label font-weight-bold text-dark">
                                                <i class="fa fa-envelope fa-fw text-muted"></i> Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control form-control-lg" 
                                                   id="customer_email" name="customer_email" 
                                                   required maxlength="320" 
                                                   placeholder="Masukkan email Anda"
                                                   style="border-radius: 15px; z-index: 5; position: relative;">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="customer_phone" class="form-label font-weight-bold text-dark">
                                                <i class="fa fa-phone fa-fw text-muted"></i> No. Handphone <span class="text-danger">*</span>
                                            </label>
                                            <input type="tel" class="form-control form-control-lg" 
                                                   id="customer_phone" name="customer_phone" 
                                                   required maxlength="20" 
                                                   placeholder="Contoh: 081234567890"
                                                   style="border-radius: 15px; z-index: 5; position: relative;">
                                        </div>
                                        
                                        <div class="alert alert-info border-0 mb-0" style="border-radius: 15px; background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);">
                                            <small class="text-info">
                                                <i class="fa fa-info-circle"></i> 
                                                Detail produk akan dikirim ke email Anda setelah pembayaran berhasil.
                                            </small>
                                        </div>
                                    </div>
                                <?php endif ?>
                                
                                <!-- Purchase Button -->
                                <button type="submit" 
                                        class="btn btn-primary btn-lg btn-block btn-purchase shadow" 
                                        id="buy-now-btn"
                                        style="background: linear-gradient(45deg, #007bff, #0056b3) !important; 
                                               border: none !important; 
                                               border-radius: 50px !important; 
                                               padding: 18px !important; 
                                               font-size: 1.1rem !important; 
                                               color: white !important; 
                                               cursor: pointer !important; 
                                               pointer-events: auto !important; 
                                               z-index: 9999 !important; 
                                               position: relative !important;
                                               display: block !important;
                                               width: 100% !important;
                                               outline: none !important;">
                                    <i class="fa fa-fw fa-credit-card"></i> 
                                    Beli Sekarang - <?= format_idr($data->product->price) ?>
                                </button>
                                
                                <!-- Debug Info -->
                                <div class="mt-2 text-center">
                                    <small class="text-muted">
                                        Form Action: <?= url('orders/create/' . $data->product->product_id) ?>
                                    </small>
                                </div>
                            </form>
                            
                            <?php if(!$this->user): ?>
                                <div class="text-center mt-4">
                                    <div class="card bg-light border-0" style="border-radius: 15px;">
                                        <div class="card-body py-3">
                                            <small class="text-muted">
                                                <i class="fa fa-sign-in-alt fa-fw"></i>
                                                Sudah punya akun? <a href="<?= url('login') ?>" class="text-primary font-weight-bold">Login disini</a>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
                
                <!-- Features Card -->
                <div class="card shadow-sm border-0 mb-4 fade-in" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-dark mb-4 text-center">
                            <i class="fa fa-star text-warning fa-fw"></i> Yang Anda Dapatkan
                        </h6>
                        <ul class="list-unstyled features-list mb-0">
                            <li class="mb-3 d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 mr-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-bolt text-white"></i>
                                </div>
                                <span class="text-dark font-weight-500">Akses instan setelah pembayaran</span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <div class="bg-primary rounded-circle p-2 mr-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-infinity text-white"></i>
                                </div>
                                <span class="text-dark font-weight-500">Akses seumur hidup</span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <div class="bg-info rounded-circle p-2 mr-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-envelope text-white"></i>
                                </div>
                                <span class="text-dark font-weight-500">Konfirmasi email dengan detail</span>
                            </li>
                            <li class="mb-0 d-flex align-items-center">
                                <div class="bg-warning rounded-circle p-2 mr-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-headset text-white"></i>
                                </div>
                                <span class="text-dark font-weight-500">Support customer 24/7</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Security Badge -->
                <div class="security-badge fade-in">
                    <div class="mb-3">
                        <i class="fa fa-shield-alt fa-3x"></i>
                    </div>
                    <h6 class="font-weight-bold mb-2">Pembayaran 100% Aman</h6>
                    <small>Dilindungi oleh Midtrans Payment Gateway</small>
                    <div class="mt-2">
                        <i class="fa fa-lock fa-fw"></i>
                        <i class="fa fa-credit-card fa-fw ml-1"></i>
                        <i class="fa fa-mobile-alt fa-fw ml-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Details Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold text-dark mb-4">
                        <i class="fa fa-info-circle text-primary fa-fw"></i> Detail Produk
                    </h5>
                    <div class="row">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-center">
                                <i class="fa fa-hashtag fa-2x text-muted mb-2"></i>
                                <div class="font-weight-bold text-dark">ID Produk</div>
                                <small class="text-muted"><?= $data->product->product_id ?></small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-center">
                                <i class="fa fa-calendar fa-2x text-muted mb-2"></i>
                                <div class="font-weight-bold text-dark">Dibuat</div>
                                <small class="text-muted"><?= \Altum\Date::get($data->product->datetime, 1) ?></small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-center">
                                <i class="fa fa-eye fa-2x text-muted mb-2"></i>
                                <div class="font-weight-bold text-dark">Dilihat</div>
                                <small class="text-muted"><?= $data->product->views ?> kali</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-center">
                                <i class="fa fa-shopping-cart fa-2x text-muted mb-2"></i>
                                <div class="font-weight-bold text-dark">Terjual</div>
                                <small class="text-muted"><?= $data->product->sales ?? 0 ?> produk</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom JavaScript for Product Page -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Product page loaded'); // Debug log
    
    // Fix any potential overlay issues
    const purchaseForm = document.getElementById('purchase-form');
    const purchaseBtn = document.querySelector('button[type="submit"]');
    const buyNowBtn = document.getElementById('buy-now-btn');
    
    if (purchaseBtn) {
        console.log('Purchase button found:', purchaseBtn);
        
        // Ensure button is clickable
        purchaseBtn.style.pointerEvents = 'auto';
        purchaseBtn.style.position = 'relative';
        purchaseBtn.style.zIndex = '99999';
        purchaseBtn.style.cursor = 'pointer';
        
        // Remove any conflicting event handlers
        purchaseBtn.onclick = null;
        
        // Add click event listener
        purchaseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Purchase button clicked!');
            
            // Get the form
            const form = document.getElementById('purchase-form');
            if (form) {
                // Validate form if user is guest
                const userLoggedIn = <?= $this->user ? 'true' : 'false' ?>;
                if (!userLoggedIn) {
                    const nameInput = document.getElementById('customer_name');
                    const emailInput = document.getElementById('customer_email');
                    const phoneInput = document.getElementById('customer_phone');
                    
                    if (!nameInput || !emailInput || !phoneInput) {
                        alert('Error: Required fields not found. Please refresh the page.');
                        return false;
                    }
                    
                    const name = nameInput.value.trim();
                    const email = emailInput.value.trim();
                    const phone = phoneInput.value.trim();
                    
                    if (!name || !email || !phone) {
                        alert('Mohon isi semua informasi yang diperlukan untuk melanjutkan pembelian.');
                        return false;
                    }
                    
                    if (!validateEmail(email)) {
                        alert('Format email tidak valid.');
                        emailInput.focus();
                        return false;
                    }
                    
                    if (!validatePhone(phone)) {
                        alert('Format nomor handphone tidak valid.');
                        phoneInput.focus();
                        return false;
                    }
                }
                
                // Show loading state
                this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memproses...';
                this.disabled = true;
                
                // Submit form
                console.log('Submitting form...');
                form.submit();
            }
        });
        
        // Ensure button is clickable
        purchaseBtn.style.pointerEvents = 'auto';
        purchaseBtn.style.cursor = 'pointer';
    } else {
        console.log('Purchase button not found');
    }
    
    // Additional handler specifically for buy-now-btn ID
    if (buyNowBtn && buyNowBtn !== purchaseBtn) {
        buyNowBtn.addEventListener('click', function(e) {
            console.log('Buy now button clicked!');
            handleButtonClick(e, this);
        });
    }
    
    function handleButtonClick(e, button) {
        console.log('Button click handler triggered'); // Debug log
        
        // Prevent multiple rapid clicks
        if (button.disabled) {
            e.preventDefault();
            return false;
        }
        
        // Basic validation for guest users
        const tokenInput = document.querySelector('input[name="token"]');
        if (!tokenInput || !tokenInput.value) {
            alert('Error: Missing security token. Please refresh the page.');
            e.preventDefault();
            return false;
        }
        
        // Check guest fields if user is not logged in
        const userLoggedIn = <?= $this->user ? 'true' : 'false' ?>;
        if (!userLoggedIn) {
            const nameInput = document.getElementById('customer_name');
            const emailInput = document.getElementById('customer_email');
            const phoneInput = document.getElementById('customer_phone');
            
            if (!nameInput || !emailInput || !phoneInput) {
                alert('Error: Required fields not found. Please refresh the page.');
                e.preventDefault();
                return false;
            }
            
            const name = nameInput.value.trim();
            const email = emailInput.value.trim();
            const phone = phoneInput.value.trim();
            
            if (!name || !email || !phone) {
                alert('Harap lengkapi semua informasi yang diperlukan.');
                e.preventDefault();
                return false;
            }
            
            if (!validateEmail(email)) {
                alert('Format email tidak valid.');
                emailInput.focus();
                e.preventDefault();
                return false;
            }
            
            if (!validatePhone(phone)) {
                alert('Format nomor handphone tidak valid.');
                phoneInput.focus();
                e.preventDefault();
                return false;
            }
        }
        
        // Show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memproses...';
        
        // Submit the form
        setTimeout(() => {
            if (purchaseForm) {
                console.log('Submitting form...');
                purchaseForm.submit();
            }
        }, 100);
        
        // Allow form submission
        return true;
    }
    
    // Fallback: Direct form submission if button doesn't work
    if (purchaseForm) {
        purchaseForm.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
        });
    }
    
    // Debug: Add visual feedback on button hover
    if (purchaseBtn) {
        purchaseBtn.addEventListener('mouseenter', function() {
            console.log('Button hover detected');
            this.style.transform = 'scale(1.02)';
        });
        
        purchaseBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    }
    
    // Ensure form can be submitted manually if needed
    window.submitPurchaseForm = function() {
        console.log('Manual form submission triggered');
        if (purchaseForm) {
            purchaseForm.submit();
        }
    };
    
    // Add animation classes with delay
    const animateElements = document.querySelectorAll('.fade-in, .slide-up');
    animateElements.forEach((el, index) => {
        el.style.animationDelay = (index * 0.1) + 's';
    });
    
    // Enhanced form validation for inputs
    const inputs = document.querySelectorAll('input[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
    
    // Add hover effects to feature list
    const featureItems = document.querySelectorAll('.features-list li');
    featureItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(10px)';
            this.style.backgroundColor = 'rgba(0,123,255,0.05)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
            this.style.backgroundColor = 'transparent';
        });
    });
});

function validateField(field) {
    const feedback = field.parentElement.querySelector('.invalid-feedback');
    if (feedback) feedback.remove();
    
    field.classList.remove('is-invalid', 'is-valid');
    
    if (!field.value.trim()) {
        if (field.hasAttribute('required')) {
            field.classList.add('is-invalid');
            const errorMsg = document.createElement('div');
            errorMsg.className = 'invalid-feedback';
            errorMsg.textContent = 'Field ini wajib diisi';
            field.parentElement.appendChild(errorMsg);
        }
    } else if (field.type === 'email' && !isValidEmail(field.value)) {
        field.classList.add('is-invalid');
        const errorMsg = document.createElement('div');
        errorMsg.className = 'invalid-feedback';
        errorMsg.textContent = 'Format email tidak valid';
        field.parentElement.appendChild(errorMsg);
    } else if (field.type === 'tel' && !isValidPhone(field.value)) {
        field.classList.add('is-invalid');
        const errorMsg = document.createElement('div');
        errorMsg.className = 'invalid-feedback';
        errorMsg.textContent = 'Format nomor handphone tidak valid';
        field.parentElement.appendChild(errorMsg);
    } else {
        field.classList.add('is-valid');
    }
}

// Email validation function
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Phone validation function
function validatePhone(phone) {
    const phoneRegex = /^[\+]?[0-9\-\s\(\)]{10,}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[0-9\-\s\(\)]{10,}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

// Fallback validation function for form onsubmit
function validateForm() {
    console.log('Validating form...');
    
    const userLoggedIn = <?= $this->user ? 'true' : 'false' ?>;
    
    if (!userLoggedIn) {
        const nameInput = document.getElementById('customer_name');
        const emailInput = document.getElementById('customer_email');
        const phoneInput = document.getElementById('customer_phone');
        
        if (!nameInput || !emailInput || !phoneInput) {
            console.log('Required fields not found');
            return true; // Let browser validation handle it
        }
        
        const name = nameInput.value.trim();
        const email = emailInput.value.trim();
        const phone = phoneInput.value.trim();
        
        if (!name || !email || !phone) {
            alert('Harap lengkapi semua informasi yang diperlukan.');
            return false;
        }
        
        if (!isValidEmail(email)) {
            alert('Format email tidak valid.');
            return false;
        }
        
        if (!isValidPhone(phone)) {
            alert('Format nomor handphone tidak valid.');
            return false;
        }
    }
    
    // Show loading state
    const submitBtn = document.getElementById('buy-now-btn');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memproses...';
    }
    
    return true;
}
</script>

<!-- Custom Styles for Better Mobile Experience -->
<style>
@media (max-width: 767.98px) {
    .product-stats .row {
        flex-direction: column;
    }
    
    .stat-item {
        margin-bottom: 10px;
    }
    
    .purchase-card {
        margin-top: 2rem;
    }
    
    .guest-info-section {
        padding: 20px;
    }
    
    .security-badge {
        padding: 15px;
        margin-bottom: 1rem;
    }
}

.form-control.is-valid {
    border-color: #28a745;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.7-.7L4.6 4.47l2.6-2.6-.7-.7L4.6 3.07 2.3 5.37l-.7.7z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.form-control.is-invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 2.4 2.4m-2.4 0 2.4-2.4'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.invalid-feedback {
    display: block;
    font-size: 0.8rem;
    color: #dc3545;
    margin-top: 0.25rem;
}
</style>