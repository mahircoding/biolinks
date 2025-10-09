<?php defined('ALTUMCODE') || die() ?>

<style>
.restricted-page {
    background: #f8f9fa;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.restricted-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    padding: 40px;
    text-align: center;
    max-width: 500px;
    margin: 20px;
}

.restricted-icon {
    font-size: 64px;
    color: #ffc107;
    margin-bottom: 20px;
}

.restricted-title {
    font-size: 28px;
    font-weight: 600;
    color: #333;
    margin-bottom: 16px;
}

.restricted-subtitle {
    font-size: 16px;
    color: #666;
    margin-bottom: 30px;
    line-height: 1.5;
}

.feature-list {
    text-align: left;
    margin: 30px 0;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.feature-list h4 {
    color: #333;
    margin-bottom: 15px;
    font-size: 18px;
}

.feature-list ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.feature-list li {
    padding: 8px 0;
    color: #666;
    position: relative;
    padding-left: 25px;
}

.feature-list li:before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #28a745;
    font-weight: bold;
}

.action-buttons {
    margin-top: 30px;
}

.btn-upgrade {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    display: inline-block;
    margin: 0 10px;
    transition: transform 0.2s ease;
}

.btn-upgrade:hover {
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    display: inline-block;
    margin: 0 10px;
    transition: transform 0.2s ease;
}

.btn-secondary:hover {
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.contact-info {
    margin-top: 30px;
    padding: 20px;
    background: #e3f2fd;
    border-radius: 8px;
    border-left: 4px solid #2196f3;
}

.contact-info h5 {
    color: #1976d2;
    margin-bottom: 10px;
}

.contact-info p {
    color: #666;
    margin: 0;
    font-size: 14px;
}

@media (max-width: 768px) {
    .restricted-container {
        padding: 30px 20px;
        margin: 10px;
    }
    
    .restricted-title {
        font-size: 24px;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-upgrade,
    .btn-secondary {
        margin: 5px 0;
    }
}
</style>

<div class="restricted-page">
    <div class="restricted-container">
        <div class="restricted-icon">
            <i class="fa fa-lock"></i>
        </div>
        
        <h1 class="restricted-title">Fitur Produk Digital Terkunci</h1>
        <p class="restricted-subtitle">
            Maaf, fitur produk digital belum tersedia untuk akun Anda. 
            Upgrade paket Anda untuk mengakses fitur ini.
        </p>

        <div class="feature-list">
            <h4>Dengan fitur Produk Digital, Anda dapat:</h4>
            <ul>
                <li>Membuat dan mengelola produk digital</li>
                <li>Menerima pembayaran otomatis via Tripay</li>
                <li>Mengirim akses produk otomatis ke pembeli</li>
                <li>Melacak penjualan dan statistik produk</li>
                <li>Menggunakan Facebook Pixel untuk tracking</li>
                <li>Mengatur harga dalam Rupiah (IDR)</li>
                <li>Upload gambar produk dan deskripsi lengkap</li>
            </ul>
        </div>

        <div class="action-buttons">
            <a href="https://api.whatsapp.com/send?phone=6282262235255&text=Upgrade+Paket" class="btn-upgrade">
                <i class="fa fa-arrow-up mr-2"></i>
                Upgrade Paket
            </a>
            <a href="dashboard" class="btn-secondary">
                <i class="fa fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
        </div>

        <div class="contact-info">
            <h5><i class="fa fa-info-circle mr-2"></i>Butuh Bantuan?</h5>
            <p>
                Hubungi admin untuk mengaktifkan fitur Produk Digital di akun Anda, 
                atau upgrade paket Anda melalui halaman <a href="account-package">Account Package</a>.
            </p>
        </div>
    </div>
</div>
