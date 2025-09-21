<?php defined('ALTUMCODE') || die() ?>

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
                            <form action="<?= url('orders/create/' . $data->product->product_id) ?>" method="post" id="purchase-form">
                                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
                                
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
                                                   style="border-radius: 15px;">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="customer_email" class="form-label font-weight-bold text-dark">
                                                <i class="fa fa-envelope fa-fw text-muted"></i> Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control form-control-lg" 
                                                   id="customer_email" name="customer_email" 
                                                   required maxlength="320" 
                                                   placeholder="Masukkan email Anda"
                                                   style="border-radius: 15px;">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="customer_phone" class="form-label font-weight-bold text-dark">
                                                <i class="fa fa-phone fa-fw text-muted"></i> No. Handphone <span class="text-danger">*</span>
                                            </label>
                                            <input type="tel" class="form-control form-control-lg" 
                                                   id="customer_phone" name="customer_phone" 
                                                   required maxlength="20" 
                                                   placeholder="Contoh: 081234567890"
                                                   style="border-radius: 15px;">
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
                                <button type="submit" class="btn btn-purchase btn-lg btn-block shadow" style="border-radius: 50px; padding: 18px; font-size: 1.1rem;">
                                    <i class="fa fa-fw fa-credit-card"></i> 
                                    Beli Sekarang - <?= format_idr($data->product->price) ?>
                                </button>
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
    // Add animation classes
    const animateElements = document.querySelectorAll('.fade-in, .slide-up');
    animateElements.forEach((el, index) => {
        el.style.animationDelay = (index * 0.1) + 's';
    });
    
    // Enhanced form validation
    const purchaseForm = document.getElementById('purchase-form');
    if (purchaseForm) {
        purchaseForm.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = purchaseForm.querySelectorAll('input[required]');
            
            requiredFields.forEach(field => {
                const feedback = field.parentElement.querySelector('.invalid-feedback');
                if (feedback) feedback.remove();
                
                field.classList.remove('is-invalid', 'is-valid');
                
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'invalid-feedback';
                    errorMsg.textContent = 'Field ini wajib diisi';
                    field.parentElement.appendChild(errorMsg);
                    isValid = false;
                } else if (field.type === 'email' && !isValidEmail(field.value)) {
                    field.classList.add('is-invalid');
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'invalid-feedback';
                    errorMsg.textContent = 'Format email tidak valid';
                    field.parentElement.appendChild(errorMsg);
                    isValid = false;
                } else if (field.type === 'tel' && !isValidPhone(field.value)) {
                    field.classList.add('is-invalid');
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'invalid-feedback';
                    errorMsg.textContent = 'Format nomor handphone tidak valid';
                    field.parentElement.appendChild(errorMsg);
                    isValid = false;
                } else {
                    field.classList.add('is-valid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = purchaseForm.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            } else {
                // Show loading state
                const submitBtn = purchaseForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memproses...';
                }
            }
        });
        
        // Real-time validation
        const inputs = purchaseForm.querySelectorAll('input');
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
    }
    
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
    
    // Smooth scroll for better UX
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
    smoothScrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
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

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[0-9\-\s\(\)]{10,}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

// Add copy to clipboard functionality for product ID
function copyProductId() {
    const productId = '<?= $data->product->product_id ?>';
    navigator.clipboard.writeText(productId).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'alert alert-success position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; border-radius: 10px;';
        toast.innerHTML = '<i class="fa fa-check"></i> ID Produk disalin!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
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