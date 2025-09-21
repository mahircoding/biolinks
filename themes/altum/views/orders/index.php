<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-body">
                    <h1 class="h4">My Orders</h1>
                    <p class="text-muted">View your purchase history and access your digital products</p>
                </div>
            </div>
        </div>
    </div>

    <?php if(count($data->orders)): ?>
        <div class="row">
            <?php foreach($data->orders as $order): ?>
                <div class="col-12 mb-3">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-2">
                                    <?php if($order->product_image): ?>
                                        <img src="<?= SITE_URL . 'uploads/products/' . $order->product_image ?>" class="img-fluid rounded" alt="<?= $order->product_name ?>">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                            <i class="fa fa-image fa-2x text-muted"></i>
                                        </div>
                                    <?php endif ?>
                                </div>
                                
                                <div class="col-12 col-md-4">
                                    <h5 class="mb-1"><?= $order->product_name ?></h5>
                                    <p class="text-muted mb-1 small"><?= string_truncate($order->product_description, 100) ?></p>
                                    <small class="text-muted">Order #<?= $order->order_id ?></small>
                                </div>
                                
                                <div class="col-12 col-md-2 text-center">
                                    <span class="h5 text-primary"><?= format_idr($order->amount) ?></span>
                                </div>
                                
                                <div class="col-12 col-md-2 text-center">
                                    <span class="badge <?= $order->status == 'completed' ? 'badge-success' : ($order->status == 'pending' ? 'badge-warning' : 'badge-danger') ?>">
                                        <?= ucfirst($order->status) ?>
                                    </span>
                                    <br>
                                    <small class="text-muted"><?= \Altum\Date::get($order->datetime, 1) ?></small>
                                </div>
                                
                                <div class="col-12 col-md-2 text-right">
                                    <?php if($order->status == 'completed'): ?>
                                        <a href="<?= url('orders/success/' . $order->order_id) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-fw fa-eye"></i> View
                                        </a>
                                    <?php elseif($order->status == 'pending'): ?>
                                        <a href="<?= url('orders/payment/' . $order->order_id) ?>" class="btn btn-sm btn-warning">
                                            <i class="fa fa-fw fa-credit-card"></i> Pay
                                        </a>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center py-5">
                    <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h2 class="h4">No orders yet</h2>
                    <p class="text-muted">You haven't made any purchases yet. Browse our products to get started!</p>
                    
                    <a href="<?= url('products/catalog') ?>" class="btn btn-primary">
                        <i class="fa fa-fw fa-shopping-cart"></i> Browse Products
                    </a>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>