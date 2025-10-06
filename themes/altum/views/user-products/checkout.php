<?php defined('ALTUMCODE') || die() ?>

<style>
.checkout-page {
    background: #ffffff;
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 504px;
    margin: 0 auto;
    position: relative;
}

.header-section {
    background: #f8f9fa;
    padding: 20px;
    text-align: center;
    border-bottom: 1px solid #e9ecef;
}

.back-btn {
    position: absolute;
    left: 20px;
    top: 20px;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: none;
    background: #f5f5f5;
    color: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: #e0e0e0;
    color: #333;
    text-decoration: none;
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
    padding-top: 10px;
}

.checkout-content {
    padding: 20px;
}

.product-info {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    text-align: center;
}

.product-name {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.product-creator {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
}

.product-price {
    font-size: 24px;
    font-weight: 700;
    color: #4caf50;
    margin: 0;
}

.checkout-form {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #4caf50;
}

.submit-btn {
    width: 100%;
    background: #4caf50;
    color: white;
    border: none;
    padding: 16px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background: #45a049;
}

/* Payment Methods Styles */
.payment-methods {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    background: #f8f9fa;
}

.payment-category {
    margin-bottom: 20px;
}

.payment-category:last-child {
    margin-bottom: 0;
}

.payment-category-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e9ecef;
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
    padding: 12px 16px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 12px;
}

.payment-label:hover {
    border-color: #4caf50;
    background: #f8fff8;
}

.payment-radio:checked + .payment-label {
    border-color: #4caf50;
    background: #e8f5e8;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
}

.payment-logo {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 12px;
    color: white;
    flex-shrink: 0;
}

.payment-name {
    font-weight: 500;
    color: #333;
    font-size: 14px;
}

/* Bank Transfer Styles */
.bank-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.bank-name {
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.bank-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.account-name {
    font-size: 12px;
    color: #666;
}

.account-number {
    font-size: 12px;
    color: #888;
    font-family: monospace;
}

/* Payment Logo Colors */
.qris-logo {
    background: #000;
}

.shopeepay-logo {
    background: #ee4d2d;
}

.ovo-logo {
    background: #4c2882;
}

.dana-logo {
    background: #118eea;
}

.bca-logo {
    background: #1e3a8a;
}

.mandiri-logo {
    background: #f59e0b;
}

.bri-logo {
    background: #059669;
}

.bni-logo {
    background: #dc2626;
}

@media (max-width: 768px) {
    .checkout-content {
        padding: 15px;
    }
    
    .header-section {
        padding: 15px;
    }
    
    .page-title {
        font-size: 20px;
    }
    
    .payment-methods {
        max-height: 300px;
    }
    
    .payment-label {
        padding: 10px 12px;
    }
    
    .payment-logo {
        width: 35px;
        height: 35px;
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
        <!-- Product Info -->
        <div class="product-info">
            <h2 class="product-name"><?= htmlspecialchars($data->product->name) ?></h2>
            <div class="product-price"><?= 'Rp ' . number_format($product->price_cents, 0, ',', '.') ?></div>
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
                
                <button type="submit" class="submit-btn">Proses Pembayaran</button>
            </form>
        </div>
    </div>
</div>
