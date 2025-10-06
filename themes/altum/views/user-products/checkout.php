<?php defined('ALTUMCODE') || die() ?>

<style>
.checkout-page {
    background: #ffff;
    min-height: 100vh;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    max-width: 480px;
    margin: 0 auto;
    position: relative;
}

.header-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 20px;
    text-align: center;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

.back-btn {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: rgba(0, 0, 0, 0.1);
    color: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: rgba(0, 0, 0, 0.2);
    color: #333;
    text-decoration: none;
}

.page-title {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
}

.checkout-content {
    padding: 0;
}

.product-card {
    background: white;
    margin: 20px;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid rgb(188 188 188 / 20%);
}

.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 16px;
}

.product-name {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
    line-height: 1.3;
}

.product-creator {
    font-size: 14px;
    color: #666;
    margin-bottom: 16px;
}

.product-price {
    font-size: 28px;
    font-weight: 800;
    color: #4caf50;
    margin: 0;
    text-align: center;
    background: linear-gradient(135deg, #4caf50, #45a049);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.checkout-form {
    background: white;
    margin: 0 20px 20px;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid rgb(188 188 188 / 20%);
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 16px;
    border: 2px solid #f0f0f0;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
    box-sizing: border-box;
    background: #fafafa;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.submit-btn {
    width: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 18px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.submit-btn:active {
    transform: translateY(0);
}

/* Payment Methods Styles */
.payment-methods {
    max-height: 400px;
    overflow-y: auto;
    border: 2px solid #f0f0f0;
    border-radius: 12px;
    padding: 16px;
    background: #fafafa;
}

.payment-category {
    margin-bottom: 20px;
}

.payment-category:last-child {
    margin-bottom: 0;
}

.payment-category-title {
    font-size: 16px;
    font-weight: 700;
    color: #333;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.payment-category-title::after {
    content: '▼';
    font-size: 12px;
    color: #666;
}

.payment-option {
    margin-bottom: 8px;
}

.payment-radio {
    display: none;
}

.payment-label {
    display: block;
    padding: 16px;
    background: white;
    border: 2px solid #f0f0f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.payment-label:hover {
    border-color: #667eea;
    background: #f8f9ff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
}

.payment-radio:checked + .payment-label {
    border-color: #667eea;
    background: linear-gradient(135deg, #f8f9ff, #e8f0ff);
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.2);
    transform: translateY(-1px);
}

.payment-logo {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 12px;
    color: white;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.payment-name {
    font-weight: 600;
    color: #333;
    font-size: 15px;
}

/* Bank Transfer Styles */
.bank-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.bank-name {
    font-weight: 700;
    color: #333;
    font-size: 15px;
}

.bank-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.account-name {
    font-size: 13px;
    color: #666;
}

.account-number {
    font-size: 13px;
    color: #888;
    font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
    font-weight: 600;
}

/* Payment Logo Colors */
.qris-logo {
    background: linear-gradient(135deg, #000, #333);
}

.shopeepay-logo {
    background: linear-gradient(135deg, #ee4d2d, #ff6b35);
}

.ovo-logo {
    background: linear-gradient(135deg, #4c2882, #6a4c93);
}

.dana-logo {
    background: linear-gradient(135deg, #118eea, #36a2eb);
}

.bca-logo {
    background: linear-gradient(135deg, #1e3a8a, #3b82f6);
}

.mandiri-logo {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
}

.bri-logo {
    background: linear-gradient(135deg, #059669, #10b981);
}

.bni-logo {
    background: linear-gradient(135deg, #dc2626, #ef4444);
}

/* Loading Animation */
.loading {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .checkout-content {
        padding: 0;
    }
    
    .header-section {
        padding: 16px;
    }
    
    .page-title {
        font-size: 18px;
    }
    
    .product-card {
        margin: 16px;
        padding: 20px;
    }
    
    .checkout-form {
        margin: 0 16px 16px;
        padding: 20px;
    }
    
    .payment-methods {
        max-height: 300px;
    }
    
    .payment-label {
        padding: 14px;
    }
    
    .payment-logo {
        width: 40px;
        height: 40px;
        font-size: 11px;
    }
}
</style>

<div class="checkout-page">
    <!-- Header Section -->
    <div class="header-section">
        <a href="<?= url($data->user->user_id . '/' . $data->product->slug) ?>" class="back-btn">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h1 class="page-title">Checkout</h1>
    </div>

    <!-- Checkout Content -->
    <div class="checkout-content">
        <!-- Product Card -->
        <div class="product-card">
            <?php if(!empty($data->product->image_path)): ?>
                <img src="<?= url($data->product->image_path) ?>" alt="<?= htmlspecialchars($data->product->name) ?>" class="product-image">
            <?php else: ?>
                <div class="product-image" style="background: linear-gradient(135deg, #f0f0f0, #e0e0e0); display: flex; align-items: center; justify-content: center; color: #999; font-size: 48px;">
                    <i class="fa fa-image"></i>
                </div>
            <?php endif ?>
            
            <h2 class="product-name"><?= htmlspecialchars($data->product->name) ?></h2>
            <div class="product-creator">Oleh: <?= htmlspecialchars($data->user->name) ?></div>
            <div class="product-price">Rp <?= number_format($data->product->price_cents, 0, ',', '.') ?></div>
        </div>

        <!-- Checkout Form -->
        <div class="checkout-form">
            <form action="" method="post">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required />
                </div>
                
                <div class="form-group">
                    <label class="form-label">No WhatsApp</label>
                    <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx" />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Metode Pembayaran</label>
                    <div class="payment-methods">
                        <!-- Bank Transfer Section -->
                        <?php if(!empty($data->user->bank_account)): ?>
                            <?php $bank_accounts = json_decode($data->user->bank_account); ?>
                            <?php if($bank_accounts && count($bank_accounts) > 0): ?>
                                <div class="payment-category">
                                    <h6 class="payment-category-title">Bank Transfer</h6>
                                    <?php foreach($bank_accounts as $bank): ?>
                                        <div class="payment-option">
                                            <input type="radio" name="payment_method" value="bank_transfer_<?= $bank->bank_name ?>" id="bank_<?= $bank->bank_name ?>" class="payment-radio">
                                            <label for="bank_<?= $bank->bank_name ?>" class="payment-label">
                                                <div class="bank-info">
                                                    <div class="bank-name"><?= htmlspecialchars($bank->bank_name) ?></div>
                                                    <div class="bank-details">
                                                        <div class="account-name"><?= htmlspecialchars($bank->account_name) ?></div>
                                                        <div class="account-number"><?= htmlspecialchars($bank->account_number) ?></div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            <?php endif ?>
                        <?php endif ?>

                        <!-- Tripay Payment Methods -->
                        <?php if(!empty($data->user->tripay_merchant_code) && !empty($data->user->tripay_api_key_public) && !empty($data->user->tripay_api_key_secret)): ?>
                            <div class="payment-category">
                                <h6 class="payment-category-title">Instant Payment</h6>
                                
                                <div class="payment-option">
                                    <input type="radio" name="payment_method" value="QRIS" id="qris" class="payment-radio">
                                    <label for="qris" class="payment-label">
                                        <div class="payment-logo qris-logo">QRIS</div>
                                        <div class="payment-name">QRIS</div>
                                    </label>
                                </div>

                                <div class="payment-option">
                                    <input type="radio" name="payment_method" value="SHOPEEPAY" id="shopeepay" class="payment-radio">
                                    <label for="shopeepay" class="payment-label">
                                        <div class="payment-logo shopeepay-logo">ShopeePay</div>
                                        <div class="payment-name">ShopeePay</div>
                                    </label>
                                </div>

                                <div class="payment-option">
                                    <input type="radio" name="payment_method" value="OVO" id="ovo" class="payment-radio">
                                    <label for="ovo" class="payment-label">
                                        <div class="payment-logo ovo-logo">OVO</div>
                                        <div class="payment-name">OVO</div>
                                    </label>
                                </div>

                                <div class="payment-option">
                                    <input type="radio" name="payment_method" value="DANA" id="dana" class="payment-radio">
                                    <label for="dana" class="payment-label">
                                        <div class="payment-logo dana-logo">DANA</div>
                                        <div class="payment-name">DANA</div>
                                    </label>
                                </div>
                            </div>

                            <div class="payment-category">
                                <h6 class="payment-category-title">Virtual Account</h6>
                                
                                <div class="payment-option">
                                    <input type="radio" name="payment_method" value="BCAVA" id="bcava" class="payment-radio">
                                    <label for="bcava" class="payment-label">
                                        <div class="payment-logo bca-logo">BCA</div>
                                        <div class="payment-name">BCA Virtual Account</div>
                                    </label>
                                </div>

                                <div class="payment-option">
                                    <input type="radio" name="payment_method" value="MANDIRIVA" id="mandiriva" class="payment-radio">
                                    <label for="mandiriva" class="payment-label">
                                        <div class="payment-logo mandiri-logo">Mandiri</div>
                                        <div class="payment-name">Mandiri Virtual Account</div>
                                    </label>
                                </div>

                                <div class="payment-option">
                                    <input type="radio" name="payment_method" value="BRIVA" id="briva" class="payment-radio">
                                    <label for="briva" class="payment-label">
                                        <div class="payment-logo bri-logo">BRIVA</div>
                                        <div class="payment-name">BRI Virtual Account</div>
                                    </label>
                                </div>

                                <div class="payment-option">
                                    <input type="radio" name="payment_method" value="BNIVA" id="bniva" class="payment-radio">
                                    <label for="bniva" class="payment-label">
                                        <div class="payment-logo bni-logo">BNI</div>
                                        <div class="payment-name">BNI Virtual Account</div>
                                    </label>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class="fa fa-credit-card mr-2"></i>
                    <span class="btn-text">Proses Pembayaran</span>
                    <span class="loading" style="display: none;"></span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const loading = submitBtn.querySelector('.loading');
    
    // Payment method selection animation
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove active class from all labels
            document.querySelectorAll('.payment-label').forEach(label => {
                label.classList.remove('active');
            });
            
            // Add active class to selected label
            if (this.checked) {
                this.nextElementSibling.classList.add('active');
            }
        });
    });
    
    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        // Check if payment method is selected
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedPayment) {
            e.preventDefault();
            alert('Silakan pilih metode pembayaran terlebih dahulu.');
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        loading.style.display = 'inline-block';
        
        // Add loading class for animation
        submitBtn.classList.add('loading-state');
    });
    
    // Smooth scroll for payment methods
    const paymentMethods = document.querySelector('.payment-methods');
    if (paymentMethods) {
        paymentMethods.style.scrollBehavior = 'smooth';
    }
    
    // Add ripple effect to payment options
    document.querySelectorAll('.payment-label').forEach(label => {
        label.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(102, 126, 234, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// Add ripple animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .payment-label.active {
        border-color: #667eea !important;
        background: linear-gradient(135deg, #f8f9ff, #e8f0ff) !important;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.2) !important;
        transform: translateY(-1px) !important;
    }
    
    .submit-btn.loading-state {
        opacity: 0.8;
        cursor: not-allowed;
    }
`;
document.head.appendChild(style);
</script>
