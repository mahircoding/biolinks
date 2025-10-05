<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-credit-card text-gray-700"></i> <?= 'Payment' ?></h1>
</div>

<?php display_notifications() ?>

<div class="row mt-5">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="h5 mb-0">Payment Method</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fa fa-fw fa-info-circle"></i> 
                    Payment integration with Midtrans will be implemented here.
                </div>
                
                <p>Order ID: <strong>#<?= $data->order->order_id ?></strong></p>
                <p>Amount: <strong><?= $data->order->price . ' ' . $this->settings->payment->currency ?></strong></p>
                
                <div class="mt-4">
                    <a href="<?= url('digital-webhook-midtrans?order_id=' . $data->order->order_id . '&status=paid') ?>" class="btn btn-success">
                        <i class="fa fa-fw fa-check"></i> Simulate Successful Payment
                    </a>
                    <a href="<?= url('digital-webhook-midtrans?order_id=' . $data->order->order_id . '&status=failed') ?>" class="btn btn-danger ml-2">
                        <i class="fa fa-fw fa-times"></i> Simulate Failed Payment
                    </a>
                </div>
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
        
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="h5 mb-0">Customer Information</h3>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong><?= htmlspecialchars($data->order->customer_name) ?></strong></p>
                <p class="mb-1"><?= htmlspecialchars($data->order->customer_email) ?></p>
                <p class="mb-0"><?= htmlspecialchars($data->order->customer_whatsapp) ?></p>
            </div>
        </div>
    </div>
</div>