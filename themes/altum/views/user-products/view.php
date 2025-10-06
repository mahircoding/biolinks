<?php defined('ALTUMCODE') || die() ?>

<style>
.product-detail-page {
    background: #ffff;
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 504px;
    margin: 0 auto;
    position: relative;
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

.product-price {
    font-size: 16px;
    font-weight: 500;
    color: #38b2ac;
    margin: 20px 0;
    padding: 0 20px;
    line-height: 1.2;
}

.product-title {
    font-size: 20px;
    font-weight: 500;
    color: #1a1a1a;
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
    max-width: 504px;
    margin: 0 auto;
    justify-content: center;
}

.whatsapp-btn, .add-cart-btn {
    width: 50px;
    height: 37px;
    border-radius: 12px;
    border: 2px solid #38b2ac;
    background: white;
    color: #38b2ac;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.whatsapp-btn:hover, .add-cart-btn:hover {
    background: #38b2ac;
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
        <a href="<?= url($data->user->user_id) ?>" class="action-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
        </svg>
        </a>
        <div>
        </div>
    </div>

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
    </div>

    <!-- Product Title -->
    <h1 class="product-title"><?= htmlspecialchars($data->product->name) ?></h1>
    <h4 class="product-price">Rp <?= number_format($data->product->price_cents, 0, ',', '.') ?></h4>

    <!-- Description Section -->
    <div class="description-section">
        <?php if(!empty($data->product->description)): ?>
            <div class="description-text">
                <?= $data->product->description ?>
            </div>
        <?php endif ?>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        <?php 
        $whatsapp_number = $data->user->phone ?? '';
        $whatsapp_url = !empty($whatsapp_number) ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp_number) : '#';
        ?>
        <a href="<?= $whatsapp_url ?>" class="whatsapp-btn" <?= empty($whatsapp_number) ? 'onclick="alert(\'Nomor WhatsApp tidak tersedia\')"' : '' ?>>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
            </svg>
        </a>
        <a href="<?= url($data->user->user_id . '/' . $data->product->slug . '/checkout') ?>" class="btn btn-primary">
            ORDER SEKARANG!
        </a>
    </div>
</div>
