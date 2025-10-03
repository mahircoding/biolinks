<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="jumbotron bg-primary text-white mb-4">
                <div class="container">
                    <h1 class="display-4"><?= \Altum\Language::get('products', 'catalog.title') ?></h1>
                    <p class="lead"><?= \Altum\Language::get('products', 'catalog.subtitle') ?></p>
                    <hr class="my-4">
                    <p><?= \Altum\Language::get('products', 'catalog.description') ?></p>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="get" action="<?= url('products/catalog') ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" placeholder="<?= \Altum\Language::get('products', 'catalog.search_placeholder') ?>" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="form-control" name="category" onchange="this.form.submit()">
                                    <option value=""><?= \Altum\Language::get('products', 'catalog.all_categories') ?></option>
                                    <option value="ebook" <?= isset($_GET['category']) && $_GET['category'] == 'ebook' ? 'selected' : '' ?>><?= \Altum\Language::get('products', 'categories.ebook') ?></option>
                                    <option value="course" <?= isset($_GET['category']) && $_GET['category'] == 'course' ? 'selected' : '' ?>><?= \Altum\Language::get('products', 'categories.course') ?></option>
                                    <option value="software" <?= isset($_GET['category']) && $_GET['category'] == 'software' ? 'selected' : '' ?>><?= \Altum\Language::get('products', 'categories.software') ?></option>
                                    <option value="template" <?= isset($_GET['category']) && $_GET['category'] == 'template' ? 'selected' : '' ?>><?= \Altum\Language::get('products', 'categories.template') ?></option>
                                    <option value="audio" <?= isset($_GET['category']) && $_GET['category'] == 'audio' ? 'selected' : '' ?>><?= \Altum\Language::get('products', 'categories.audio') ?></option>
                                    <option value="video" <?= isset($_GET['category']) && $_GET['category'] == 'video' ? 'selected' : '' ?>><?= \Altum\Language::get('products', 'categories.video') ?></option>
                                    <option value="other" <?= isset($_GET['category']) && $_GET['category'] == 'other' ? 'selected' : '' ?>><?= \Altum\Language::get('products', 'categories.other') ?></option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="form-control" name="sort" onchange="this.form.submit()">
                                    <option value="latest" <?= isset($_GET['sort']) && $_GET['sort'] == 'latest' ? 'selected' : '' ?>><?= \Altum\Language::get('products', 'catalog.sort_latest') ?></option>
                                    <option value="price_low" <?= isset($_GET['sort']) && $_GET['sort'] == 'price_low' ? 'selected' : '' ?>><?= \Altum\Language::get('products', 'catalog.sort_price_low') ?></option>
                                    <option value="price_high" <?= isset($_GET['sort']) && $_GET['sort'] == 'price_high' ? 'selected' : '' ?>><?= \Altum\Language::get('products', 'catalog.sort_price_high') ?></option>
                                    <option value="popular" <?= isset($_GET['sort']) && $_GET['sort'] == 'popular' ? 'selected' : '' ?>><?= \Altum\Language::get('products', 'catalog.sort_popular') ?></option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row">
                <?php if(!empty($products)): ?>
                    <?php foreach($products as $product): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 product-card">
                                <?php if(!empty($product['image'])): ?>
                                    <img src="<?= UPLOADS_URL_PATH . $product['image'] ?>" class="card-img-top product-image" alt="<?= $product['name'] ?>">
                                <?php else: ?>
                                    <div class="card-img-top product-image-placeholder">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h5 class="card-title"><?= $product['name'] ?></h5>
                                    <p class="card-text text-muted small"><?= string_truncate($product['description'], 100) ?></p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge badge-primary price-badge">
                                            <i class="fas fa-tag mr-1"></i>Rp <?= number_format($product['price'], 0, ',', '.') ?>
                                        </span>
                                        <span class="badge badge-success sales-badge">
                                            <i class="fas fa-shopping-cart mr-1"></i><?= $product['sales'] ?> <?= \Altum\Language::get('products', 'catalog.sales') ?>
                                        </span>
                                    </div>

                                    <div class="seller-info mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-user mr-1"></i><?= $product['user_name'] ?? 'Anonymous' ?>
                                        </small>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="<?= url('products/view/' . $product['product_id']) ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye mr-1"></i><?= \Altum\Language::get('products', 'catalog.view_details') ?>
                                        </a>
                                        <?php if($this->user): ?>
                                            <a href="<?= url('orders/create/' . $product['product_id']) ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-shopping-cart mr-1"></i><?= \Altum\Language::get('products', 'catalog.buy_now') ?>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= url('login?redirect=' . urlencode(url('products/view/' . $product['product_id']))) ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-sign-in-alt mr-1"></i><?= \Altum\Language::get('products', 'catalog.login_to_buy') ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted mb-2"><?= \Altum\Language::get('products', 'catalog.no_products_found') ?></h4>
                            <p class="text-muted mb-4"><?= \Altum\Language::get('products', 'catalog.try_different_search') ?></p>
                            <a href="<?= url('products/catalog') ?>" class="btn btn-primary">
                                <i class="fas fa-refresh mr-2"></i><?= \Altum\Language::get('products', 'catalog.reset_search') ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if($current_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= url('products/catalog?page=' . $previous_page . $search_query . $category_query . $sort_query) ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for($i = $start_range; $i <= $end_range; $i++): ?>
                            <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                <a class="page-link" href="<?= url('products/catalog?page=' . $i . $search_query . $category_query . $sort_query) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if($current_page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= url('products/catalog?page=' . $next_page . $search_query . $category_query . $sort_query) ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.product-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.product-image {
    height: 200px;
    object-fit: cover;
}

.product-image-placeholder {
    height: 200px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.price-badge {
    font-size: 0.9rem;
    font-weight: 600;
}

.sales-badge {
    font-size: 0.8rem;
}

.seller-info {
    border-top: 1px solid #e9ecef;
    padding-top: 0.5rem;
}

@media (max-width: 768px) {
    .product-image, .product-image-placeholder {
        height: 150px;
    }
}
</style>