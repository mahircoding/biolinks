<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php display_notifications() ?>

    <!-- Store Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center">
                <div class="mr-3">
                    <?php if($user->image): ?>
                        <img src="<?= UPLOADS_URL_PATH . 'users/' . $user->image ?>" class="rounded-circle" width="80" height="80" alt="<?= $user->name ?>">
                    <?php else: ?>
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fa fa-user fa-2x"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex-grow-1">
                    <h1 class="h3 mb-1"><?= $user->name ?></h1>
                    <p class="text-muted mb-0"><?= $user->email ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Store Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <div class="card border-0 bg-primary-200 text-primary-700">
                                <div class="p-3 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-fw fa-shopping-bag fa-lg"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card-title h5 m-0"><?= nr($user_stats['total_products']) ?></div>
                            <small class="text-muted">Total Products</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <div class="card border-0 bg-success-200 text-success-700">
                                <div class="p-3 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-fw fa-chart-line fa-lg"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card-title h5 m-0"><?= nr($user_stats['total_sales']) ?></div>
                            <small class="text-muted">Total Sales</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <div class="card border-0 bg-info-200 text-info-700">
                                <div class="p-3 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-fw fa-money-bill-wave fa-lg"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card-title h5 m-0">Rp <?= number_format($user_stats['total_revenue'], 0, ',', '.') ?></div>
                            <small class="text-muted">Total Revenue</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        <div class="col-12">
            <h2 class="h4 mb-4">Digital Products</h2>
            
            <?php if(count($products)): ?>
                <div class="row">
                    <?php foreach($products as $product): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100">
                                <?php if($product['image']): ?>
                                    <img src="<?= UPLOADS_URL_PATH . 'products/' . $product['image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
                                <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fa fa-file fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h5 class="card-title h6"><?= $product['name'] ?></h5>
                                    <p class="card-text text-muted small"><?= mb_substr($product['description'], 0, 100) . (mb_strlen($product['description']) > 100 ? '...' : '') ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary font-weight-bold">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                                        <span class="badge badge-secondary"><?= nr($product['sales']) ?> sales</span>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="<?= url('store/' . $user->username . '/product/' . $product['product_id']) ?>" class="btn btn-primary btn-sm w-100">
                                        <i class="fa fa-eye mr-1"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if($total_products > 12): ?>
                    <div class="row">
                        <div class="col-12">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <?php if($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= url('store/' . $user->username . '?page=' . ($page - 1)) ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for($i = $start_range; $i <= $end_range; $i++): ?>
                                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                            <a class="page-link" href="<?= url('store/' . $user->username . '?page=' . $i) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= url('store/' . $user->username . '?page=' . ($page + 1)) ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fa fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <h3 class="h5 text-muted">No products available</h3>
                    <p class="text-muted">Check back later for new digital products!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>