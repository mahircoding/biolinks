<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fa fa-check-circle fa-4x text-success mb-3"></i>
                        <h1 class="h3 text-success">Payment Successful!</h1>
                        <p class="text-muted">Thank you for your purchase. Your order has been completed.</p>
                    </div>
                    
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5>Order Details</h5>
                            
                            <div class="row mb-2">
                                <div class="col-4"><strong>Order ID:</strong></div>
                                <div class="col-8"><?= $data->order->order_id ?></div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-4"><strong>Product:</strong></div>
                                <div class="col-8"><?= $data->product->name ?></div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-4"><strong>Amount:</strong></div>
                                <div class="col-8">$<?= number_format($data->order->amount, 2) ?></div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-4"><strong>Purchase Date:</strong></div>
                                <div class="col-8"><?= \Altum\Date::get($data->order->completed_datetime, 1) ?></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-4"><strong>Status:</strong></div>
                                <div class="col-8">
                                    <span class="badge badge-success">Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($data->product->digital_link): ?>
                        <div class="alert alert-success mt-4">
                            <h6><i class="fa fa-fw fa-download"></i> Access Your Product</h6>
                            <p class="mb-2">Your digital product is ready for access:</p>
                            <a href="<?= $data->product->digital_link ?>" class="btn btn-success" target="_blank">
                                <i class="fa fa-fw fa-external-link-alt"></i> Access Product
                            </a>
                        </div>
                    <?php endif ?>
                    
                    <div class="alert alert-info">
                        <h6><i class="fa fa-fw fa-envelope"></i> Email Confirmation</h6>
                        <p class="mb-0">
                            A confirmation email with your order details and access information has been sent to your registered email address.
                        </p>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="<?= url('orders') ?>" class="btn btn-primary">
                            <i class="fa fa-fw fa-list"></i> View All Orders
                        </a>
                        
                        <a href="<?= url('products/catalog') ?>" class="btn btn-outline-primary ml-2">
                            <i class="fa fa-fw fa-shopping-cart"></i> Browse More Products
                        </a>
                    </div>
                    
                    <hr class="mt-4">
                    
                    <div class="text-center">
                        <small class="text-muted">
                            Need help? Contact our support team at support@<?= parse_url(SITE_URL, PHP_URL_HOST) ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>