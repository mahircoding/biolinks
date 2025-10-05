<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-shopping-cart text-gray-700"></i> <?= 'Checkout' ?></h1>
    
    <div class="col-auto p-0">
        <a href="<?= url('digital-product/' . $data->product->product_id) ?>" class="btn btn-outline-secondary rounded-pill"><i class="fa fa-fw fa-arrow-left"></i> <?= 'Back' ?></a>
    </div>
</div>

<?php display_notifications() ?>

<div class="row mt-5">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="h5 mb-0">Customer Information</h3>
            </div>
            <div class="card-body">
                <form action="" method="post" role="form">
                    <div class="form-group">
                        <label for="name"><i class="fa fa-fw fa-sm fa-user text-muted mr-1"></i> <?= 'Full Name' ?></label>
                        <input type="text" id="name" name="name" class="form-control" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required="required" />
                    </div>
                    
                    <div class="form-group">
                        <label for="email"><i class="fa fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= 'Email Address' ?></label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required="required" />
                    </div>
                    
                    <div class="form-group">
                        <label for="whatsapp"><i class="fa fa-fw fa-sm fa-phone text-muted mr-1"></i> <?= 'WhatsApp Number' ?></label>
                        <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="<?= isset($_POST['whatsapp']) ? htmlspecialchars($_POST['whatsapp']) : '' ?>" required="required" />
                        <small class="form-text text-muted">Please include country code (e.g., +628123456789)</small>
                    </div>
                    
                    <button type="submit" name="submit" class="btn btn-primary"><?= 'Continue to Payment' ?></button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-4 mt-4 mt-lg-0">
        <div class="card">
            <div class="card-header">
                <h3 class="h5 mb-0">Order Summary</h3>
            </div>
            <div class="card-body">
                <div class="d-flex mb-3">
                    <?php if($data->product->image): ?>
                        <img src="<?= url(UPLOADS_URL_PATH . 'products/' . $data->product->image) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($data->product->name) ?>" style="width: 80px; height: 80px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 80px;">
                            <i class="fa fa-fw fa-box fa-2x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="ml-3">
                        <h4 class="h6 mb-1"><?= htmlspecialchars($data->product->name) ?></h4>
                        <p class="text-muted mb-0"><?= $data->product->price . ' ' . $this->settings->payment->currency ?></p>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <span><strong>Total:</strong></span>
                    <span class="h4 text-primary mb-0"><?= $data->product->price . ' ' . $this->settings->payment->currency ?></span>
                </div>
            </div>
        </div>
    </div>
</div>