<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="store/<?= $data->store_user->name ?>">
                            <i class="fa fa-store mr-1"></i><?= $data->store_user->name ?>'s Store
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="store/<?= $data->store_user->name ?>/product/<?= $data->product->product_id ?>">
                            <?= $data->product->title ?>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>
            </nav>

            <div class="row">
                <!-- Order Summary -->
                <div class="col-12 col-md-5 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <?php if($data->product->image): ?>
                                    <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'products/' . $data->product->image ?>" alt="<?= $data->product->title ?>" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="fa fa-box fa-2x text-muted"></i>
                                    </div>
                                <?php endif ?>
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="mb-1"><?= $data->product->title ?></h6>
                                    <small class="text-muted"><?= ucfirst($data->product->category) ?></small>
                                    <div class="mt-2">
                                        <span class="font-weight-bold text-primary">Rp <?= number_format($data->product->price, 0, ',', '.') ?></span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>Rp <?= number_format($data->product->price, 0, ',', '.') ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>Rp 0</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between font-weight-bold">
                                <span>Total:</span>
                                <span class="text-primary">Rp <?= number_format($data->product->price, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <i class="fa fa-download fa-2x text-primary mb-2"></i>
                                    <div class="small text-muted">Instant Download</div>
                                </div>
                                <div class="col-4">
                                    <i class="fa fa-shield-alt fa-2x text-success mb-2"></i>
                                    <div class="small text-muted">Secure Payment</div>
                                </div>
                                <div class="col-4">
                                    <i class="fa fa-headset fa-2x text-info mb-2"></i>
                                    <div class="small text-muted">24/7 Support</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div class="col-12 col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Checkout Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="store/<?= $data->store_user->name ?>/checkout/<?= $data->product->product_id ?>">
                                <?= \Altum\Middlewares\Csrf::get() ?>

                                <!-- Customer Information -->
                                <div class="form-group">
                                    <label for="customer_name">Full Name *</label>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="customer_email">Email Address *</label>
                                    <input type="email" id="customer_email" name="customer_email" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="customer_phone">Phone Number</label>
                                    <input type="tel" id="customer_phone" name="customer_phone" class="form-control">
                                </div>

                                <!-- Payment Method -->
                                <div class="form-group">
                                    <label>Payment Method *</label>
                                    <div class="row">
                                        <?php if($this->settings->payment->paypal_is_enabled): ?>
                                            <div class="col-12 col-md-6 mb-2">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="payment_paypal" name="payment_method" value="paypal" class="custom-control-input" required>
                                                    <label class="custom-control-label" for="payment_paypal">
                                                        <i class="fab fa-paypal text-primary mr-2"></i>PayPal
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endif ?>

                                        <?php if($this->settings->payment->stripe_is_enabled): ?>
                                            <div class="col-12 col-md-6 mb-2">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="payment_stripe" name="payment_method" value="stripe" class="custom-control-input" required>
                                                    <label class="custom-control-label" for="payment_stripe">
                                                        <i class="fab fa-stripe text-info mr-2"></i>Stripe
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endif ?>

                                        <?php if($this->settings->payment->duitku_is_enabled): ?>
                                            <div class="col-12 col-md-6 mb-2">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="payment_duitku" name="payment_method" value="duitku" class="custom-control-input" required>
                                                    <label class="custom-control-label" for="payment_duitku">
                                                        <i class="fas fa-credit-card text-success mr-2"></i>Duitku
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="terms_agreement" name="terms_agreement" required>
                                        <label class="custom-control-label" for="terms_agreement">
                                            I agree to the <a href="#" target="_blank">Terms and Conditions</a> and <a href="#" target="_blank">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <i class="fa fa-lock mr-2"></i>Complete Purchase - Rp <?= number_format($data->product->price, 0, ',', '.') ?>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="alert alert-info mt-3">
                        <i class="fa fa-info-circle mr-2"></i>
                        Your payment information is secure and encrypted. We never store your payment details.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.custom-control-label {
    cursor: pointer;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}
</style>