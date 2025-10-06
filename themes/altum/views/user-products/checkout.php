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
            <p class="product-creator">Oleh: <?= htmlspecialchars($data->user->name) ?></p>
            <div class="product-price">Rp <?= number_format($data->product->price_cents / 100, 0, ',', '.') ?></div>
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
                    <select name="method" class="form-control">
                        <option value="QRIS">QRIS</option>
                        <option value="BCAVA">BCA Virtual Account</option>
                        <option value="BNIVA">BNI Virtual Account</option>
                        <option value="BRIVA">BRI Virtual Account</option>
                        <option value="MANDIRIVA">Mandiri Virtual Account</option>
                    </select>
                </div>
                
                <button type="submit" class="submit-btn">Proses Pembayaran</button>
            </form>
        </div>
    </div>
</div>
