<?php defined('ALTUMCODE') || die() ?>

<style>
.bank-instructions-page {
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

.instructions-content {
    padding: 20px;
}

.success-message {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
}

.bank-details-card {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.bank-details-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    text-align: center;
}

.bank-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.bank-detail-row:last-child {
    border-bottom: none;
}

.bank-detail-label {
    font-weight: 600;
    color: #666;
    font-size: 14px;
}

.bank-detail-value {
    font-weight: 500;
    color: #333;
    font-size: 14px;
    text-align: right;
}

.account-number {
    font-family: monospace;
    font-size: 16px;
    font-weight: 700;
    color: #4caf50;
}

.instructions-text {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.instructions-text h6 {
    margin: 0 0 10px 0;
    font-size: 16px;
    font-weight: 600;
}

.instructions-text ul {
    margin: 0;
    padding-left: 20px;
}

.instructions-text li {
    margin-bottom: 5px;
}

.product-summary {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}

.product-name {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.product-price {
    font-size: 18px;
    font-weight: 700;
    color: #4caf50;
}

@media (max-width: 768px) {
    .instructions-content {
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

<div class="bank-instructions-page">
    <!-- Header Section -->
    <div class="header-section">
        <a href="<?= url($data->user->user_id . '/' . $data->product->slug) ?>" class="back-btn">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h1 class="page-title">Instruksi Pembayaran</h1>
    </div>

    <!-- Instructions Content -->
    <div class="instructions-content">
        <!-- Success Message -->
        <div class="success-message">
            <i class="fa fa-check-circle mr-2"></i>
            <strong>Pesanan berhasil dibuat!</strong><br>
            Silakan lakukan pembayaran sesuai instruksi di bawah ini.
        </div>

        <!-- Product Summary -->
        <div class="product-summary">
            <div class="product-name"><?= htmlspecialchars($data->product->name) ?></div>
            <div class="product-price">Rp <?= number_format($data->product->price_cents / 100, 0, ',', '.') ?></div>
        </div>

        <!-- Bank Details -->
        <div class="bank-details-card">
            <div class="bank-details-title">
                <i class="fa fa-university mr-2"></i>
                Transfer ke Rekening Berikut
            </div>
            
            <div class="bank-detail-row">
                <span class="bank-detail-label">Bank</span>
                <span class="bank-detail-value"><?= htmlspecialchars($data->bank->bank_name) ?></span>
            </div>
            
            <div class="bank-detail-row">
                <span class="bank-detail-label">Nama Rekening</span>
                <span class="bank-detail-value"><?= htmlspecialchars($data->bank->account_name) ?></span>
            </div>
            
            <div class="bank-detail-row">
                <span class="bank-detail-label">Nomor Rekening</span>
                <span class="bank-detail-value account-number"><?= htmlspecialchars($data->bank->account_number) ?></span>
            </div>
        </div>

        <!-- Instructions -->
        <div class="instructions-text">
            <h6><i class="fa fa-info-circle mr-2"></i>Instruksi Pembayaran:</h6>
            <ul>
                <li>Transfer sesuai dengan jumlah yang tertera di atas</li>
                <li>Gunakan nomor rekening yang benar-benar sesuai</li>
                <li>Setelah transfer, produk akan dikirim ke email: <strong><?= htmlspecialchars($data->email) ?></strong></li>
                <li>Proses verifikasi membutuhkan waktu 1x24 jam</li>
                <li>Jika ada pertanyaan, hubungi penjual melalui WhatsApp</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div style="text-align: center; margin-top: 30px;">
            <a href="<?= url($data->user->user_id . '/' . $data->product->slug) ?>" class="btn btn-outline-primary">
                <i class="fa fa-arrow-left mr-2"></i>Kembali ke Produk
            </a>
        </div>
    </div>
</div>
