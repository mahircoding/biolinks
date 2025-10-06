<?php defined('ALTUMCODE') || die() ?>

<style>
.product-detail-page {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    position: sticky;
    top: 0;
    z-index: 100;
}

.action-btn {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.action-btn:hover {
    background: #e0e0e0;
    color: #333;
    text-decoration: none;
}

.cart-btn {
    position: relative;
}

.cart-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #4caf50;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-title {
    font-size: 24px;
    font-weight: 700;
    color: #1a1a1a;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 20px 0;
    padding: 0 20px;
    line-height: 1.2;
}

.product-banner {
    position: relative;
    margin: 0 20px 30px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.banner-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
    background: linear-gradient(135deg, #2196f3 0%, #ff9800 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.banner-content {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.9) 0%, rgba(255, 152, 0, 0.9) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    color: white;
    text-align: center;
    padding: 20px;
}

.banner-title {
    font-size: 28px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.banner-subtitle {
    font-size: 18px;
    font-weight: 600;
    opacity: 0.9;
}

.instructor-section {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    text-align: center;
    color: white;
}

.instructor-title {
    font-size: 14px;
    font-style: italic;
    margin-bottom: 5px;
}

.instructor-name {
    background: #ffeb3b;
    color: #333;
    padding: 5px 10px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    margin-bottom: 5px;
    display: inline-block;
}

.instructor-role {
    font-size: 11px;
    opacity: 0.9;
}

.materials-section {
    position: absolute;
    left: 20px;
    top: 20px;
    background: rgba(255, 235, 59, 0.95);
    padding: 15px;
    border-radius: 12px;
    max-width: 250px;
}

.materials-title {
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
    font-size: 14px;
}

.materials-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.materials-list li {
    color: white;
    font-size: 12px;
    margin-bottom: 5px;
    padding-left: 15px;
    position: relative;
}

.materials-list li:before {
    content: "•";
    position: absolute;
    left: 0;
    color: white;
    font-weight: bold;
}

.zoom-access {
    background: rgba(255, 235, 59, 0.95);
    color: #333;
    padding: 10px 15px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 10px;
    text-align: center;
}

.description-section {
    padding: 0 20px 30px;
    color: #333;
    line-height: 1.6;
}

.description-text {
    font-size: 16px;
    margin-bottom: 20px;
}

.learn-about {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}

.cta-section {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    padding: 15px 20px;
    box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
    display: flex;
    gap: 10px;
    align-items: center;
}

.whatsapp-btn, .add-cart-btn {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    border: 2px solid #4caf50;
    background: white;
    color: #4caf50;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.whatsapp-btn:hover, .add-cart-btn:hover {
    background: #4caf50;
    color: white;
    text-decoration: none;
}

.buy-now-btn {
    flex: 1;
    height: 50px;
    background: #4caf50;
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.buy-now-btn:hover {
    background: #45a049;
    color: white;
    text-decoration: none;
}

@media (max-width: 768px) {
    .product-title {
        font-size: 20px;
        padding: 0 15px;
    }
    
    .product-banner {
        margin: 0 15px 20px;
    }
    
    .banner-image {
        height: 250px;
    }
    
    .banner-title {
        font-size: 22px;
    }
    
    .materials-section {
        left: 15px;
        top: 15px;
        max-width: 200px;
        padding: 12px;
    }
    
    .instructor-section {
        right: 15px;
    }
    
    .description-section {
        padding: 0 15px 100px;
    }
    
    .cta-section {
        padding: 15px;
    }
}
</style>

<div class="product-detail-page">
    <!-- Header Actions -->
    <div class="header-actions">
        <a href="javascript:history.back()" class="action-btn">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div>
            <a href="#" class="action-btn">
                <i class="fa fa-share"></i>
            </a>
            <a href="#" class="action-btn cart-btn">
                <i class="fa fa-shopping-cart"></i>
                <span class="cart-badge">0</span>
            </a>
        </div>
    </div>

    <!-- Product Title -->
    <h1 class="product-title"><?= htmlspecialchars($data->product->name) ?></h1>

    <!-- Product Banner -->
    <div class="product-banner">
        <?php if(!empty($data->product->image_path)): ?>
            <img src="<?= url($data->product->image_path) ?>" alt="<?= htmlspecialchars($data->product->name) ?>" class="banner-image">
        <?php else: ?>
            <div class="banner-image">
                <div class="banner-content">
                    <div class="banner-title"><?= htmlspecialchars($data->product->name) ?></div>
                    <div class="banner-subtitle">Digital Product</div>
                </div>
            </div>
        <?php endif ?>
        
        <!-- Materials Section -->
        <div class="materials-section">
            <div class="materials-title">Materi:</div>
            <ul class="materials-list">
                <li>Konten berkualitas tinggi</li>
                <li>Akses seumur hidup</li>
                <li>Update gratis</li>
                <li>Support 24/7</li>
                <li>Garansi uang kembali</li>
                <li>Komunitas eksklusif</li>
            </ul>
            <div class="zoom-access">
                Akses video rekaman zoom
            </div>
        </div>

        <!-- Instructor Section -->
        <div class="instructor-section">
            <div class="instructor-title">Mentor</div>
            <div class="instructor-name"><?= htmlspecialchars($data->user->name) ?></div>
            <div class="instructor-role">Digital Creator</div>
        </div>
    </div>

    <!-- Description Section -->
    <div class="description-section">
        <div class="description-text">
            Produk digital berkualitas tinggi yang akan membantu Anda mencapai tujuan. 
            Dapatkan akses seumur hidup dan update gratis untuk konten terbaru.
        </div>
        <div class="description-text">
            Bergabunglah dengan ribuan pelanggan yang telah merasakan manfaat dari produk ini. 
            Dapatkan hasil maksimal dengan panduan step-by-step yang mudah diikuti.
        </div>
        
        <div class="learn-about">Anda akan diajak belajar tentang:</div>
        
        <?php if(!empty($data->product->description)): ?>
            <div class="description-text">
                <?= $data->product->description ?>
            </div>
        <?php endif ?>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        <a href="https://wa.me/6281234567890" class="whatsapp-btn">
            <i class="fa fa-whatsapp"></i>
        </a>
        <a href="<?= url($data->user->user_id . '/' . $data->product->slug . '/checkout') ?>" class="buy-now-btn">
            DAPATKAN SEKARANG!
        </a>
    </div>
</div>
