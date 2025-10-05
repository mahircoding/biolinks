<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-shopping-cart text-gray-700"></i> <?= 'Order #' . $data->order->order_id ?></h1>
</div>

<?php display_notifications() ?>

<div class="row mt-5">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="h5 mb-0">Order Status</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="h6 mb-1"><?= htmlspecialchars($data->product->name) ?></h4>
                        <p class="text-muted mb-0"><?= $data->order->price . ' ' . $this->settings->payment->currency ?></p>
                    </div>
                    <?php 
                    $status_badge = '';
                    switch($data->order->payment_status) {
                        case 'pending':
                            $status_badge = '<span class="badge badge-pill badge-warning">Pending</span>';
                            break;
                        case 'paid':
                            $status_badge = '<span class="badge badge-pill badge-success">Paid</span>';
                            break;
                        case 'failed':
                            $status_badge = '<span class="badge badge-pill badge-danger">Failed</span>';
                            break;
                        case 'refunded':
                            $status_badge = '<span class="badge badge-pill badge-secondary">Refunded</span>';
                            break;
                    }
                    echo $status_badge;
                    ?>
                </div>
                
                <?php if($data->order->payment_status == 'paid'): ?>
                    <div class="mt-4">
                        <h4 class="h6">Access Your Product</h4>
                        <p>You can access your product using the link below:</p>
                        <a href="<?= htmlspecialchars($data->product->access_url) ?>" target="_blank" class="btn btn-primary">Access Product</a>
                    </div>
                <?php elseif($data->order->payment_status == 'pending'): ?>
                    <div class="mt-4">
                        <h4 class="h6">Complete Your Payment</h4>
                        <p>Your payment is still pending. Please complete your payment to access your product.</p>
                        <a href="<?= url('digital-payment/' . $data->order->order_id) ?>" class="btn btn-primary">Proceed to Payment</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-4 mt-4 mt-lg-0">
        <div class="card">
            <div class="card-header">
                <h3 class="h5 mb-0">Order Details</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td><strong>Order ID:</strong></td>
                        <td><?= $data->order->order_id ?></td>
                    </tr>
                    <tr>
                        <td><strong>Order Date:</strong></td>
                        <td><?= (new \DateTime($data->order->date))->format('Y-m-d H:i:s') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Product:</strong></td>
                        <td><?= htmlspecialchars($data->product->name) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Price:</strong></td>
                        <td><?= $data->order->price . ' ' . $this->settings->payment->currency ?></td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <?php 
                            $status_badge = '';
                            switch($data->order->payment_status) {
                                case 'pending':
                                    $status_badge = '<span class="badge badge-pill badge-warning">Pending</span>';
                                    break;
                                case 'paid':
                                    $status_badge = '<span class="badge badge-pill badge-success">Paid</span>';
                                    break;
                                case 'failed':
                                    $status_badge = '<span class="badge badge-pill badge-danger">Failed</span>';
                                    break;
                                case 'refunded':
                                    $status_badge = '<span class="badge badge-pill badge-secondary">Refunded</span>';
                                    break;
                            }
                            echo $status_badge;
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="h5 mb-0">Customer Information</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td><?= htmlspecialchars($data->order->customer_name) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td><?= htmlspecialchars($data->order->customer_email) ?></td>
                    </tr>
                    <tr>
                        <td><strong>WhatsApp:</strong></td>
                        <td><?= htmlspecialchars($data->order->customer_whatsapp) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>