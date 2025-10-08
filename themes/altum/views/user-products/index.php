<?php defined('ALTUMCODE') || die() ?>

<?php
/* Facebook Pixel tracking for product listing */
$pixel_id = $data->user->facebook_pixel_id ?? null;
if ($pixel_id) {
    echo \Altum\Helpers\FacebookPixel::get_base_code($pixel_id);
}
?>

<style>
.products-page {
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

.creator-name {
    font-size: 16px;
    color: #666;
    margin-top: 5px;
}

.products-grid {
    padding: 20px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    color: inherit;
}

.product-image {
    width: 100%;
    height: 120px;
    object-fit: cover;
    background: #f8f9fa;
}

.product-image-placeholder {
    width: 100%;
    height: 120px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
}

.product-info {
    padding: 12px;
}

.product-name {
    font-size: 14px;
    font-weight: 500;
    color: #333;
    margin: 0 0 8px 0;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-price {
    font-size: 14px;
    font-weight: 500;
    color: #38b2ac;
    margin: 0;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.empty-state i {
    font-size: 48px;
    color: #ddd;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
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

<div class="products-page">
    <!-- Header Section -->
    <div class="header-section">
        <a href="javascript:history.back()" class="back-btn">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h1 class="page-title">Produk Digital</h1>
        <div class="creator-name">dari <?= htmlspecialchars($data->user->name) ?></div>
    </div>

    <!-- Products Grid -->
    <?php if(empty($data->products)): ?>
        <div class="empty-state">
            <i class="fa fa-box-open"></i>
            <p>Belum ada produk yang tersedia.</p>
        </div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach($data->products as $product): ?>
                <a href="<?= url($data->user->user_id . '/' . $product->slug) ?>" class="product-card">
                    <?php if(!empty($product->image_path)): ?>
                        <img src="<?= url($product->image_path) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="product-image">
                    <?php else: ?>
                        <div class="product-image-placeholder">
                            <i class="fa fa-image"></i>
                        </div>
                    <?php endif ?>
                    <div class="product-info">
                        <h3 class="product-name"><?= htmlspecialchars($product->name) ?></h3>
                        <p class="product-price">Rp <?= number_format($product->price_cents, 0, ',', '.') ?></p>
                    </div>
                </a>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</div>
