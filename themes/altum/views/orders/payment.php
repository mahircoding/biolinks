<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fa fa-credit-card fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Complete Your Payment</h1>
                        <p class="text-muted">Review your order and proceed with payment</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <?php if($data->product->image): ?>
                                <img src="<?= SITE_URL . 'uploads/products/' . $data->product->image ?>" class="img-fluid rounded mb-3" alt="<?= $data->product->name ?>">
                            <?php endif ?>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <h4><?= $data->product->name ?></h4>
                            <p class="text-muted"><?= $data->product->description ?></p>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-6">
                                    <strong>Order ID:</strong>
                                </div>
                                <div class="col-6">
                                    <?= $data->order->order_id ?>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <strong>Amount:</strong>
                                </div>
                                <div class="col-6">
                                    <span class="h5 text-primary">$<?= number_format($data->order->amount, 2) ?></span>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-6">
                                    <span class="badge badge-warning"><?= ucfirst($data->order->status) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="alert alert-info">
                        <h6><i class="fa fa-fw fa-info-circle"></i> Payment Information</h6>
                        <ul class="mb-0">
                            <li>You will receive instant access after successful payment</li>
                            <li>A confirmation email will be sent to your registered email</li>
                            <li>You can access your purchase anytime from the Orders page</li>
                        </ul>
                    </div>
                    
                    <form action="" method="post" class="text-center">
                        <button type="submit" name="pay_now" class="btn btn-primary btn-lg">
                            <i class="fa fa-fw fa-credit-card"></i> Pay $<?= number_format($data->order->amount, 2) ?>
                        </button>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                Secured by Midtrans Payment Gateway
                            </small>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="<?= url('orders') ?>" class="btn btn-outline-secondary">
                            <i class="fa fa-fw fa-arrow-left"></i> Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>