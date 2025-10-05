<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-shopping-cart text-gray-700"></i> <?= 'View Order #' . $data->order->order_id ?></h1>
    
    <div class="col-auto p-0">
        <a href="<?= url('admin/digital-orders') ?>" class="btn btn-outline-secondary rounded-pill"><i class="fa fa-fw fa-arrow-left"></i> <?= 'Back' ?></a>
    </div>
</div>

<?php display_notifications() ?>

<div class="row mt-5">
    <div class="col-12 col-lg-6">
        <div class="card border-0">
            <div class="card-header">
                <h3 class="h5">Order Details</h3>
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
                        <td><strong>Payment Status:</strong></td>
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
                    <tr>
                        <td><strong>Payment Method:</strong></td>
                        <td><?= $data->order->payment_method ?? 'N/A' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Payment Reference:</strong></td>
                        <td><?= $data->order->payment_reference ?? 'N/A' ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-6">
        <div class="card border-0">
            <div class="card-header">
                <h3 class="h5">Customer Details</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td><?= $data->order->customer_name ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td><?= $data->order->customer_email ?></td>
                    </tr>
                    <tr>
                        <td><strong>WhatsApp:</strong></td>
                        <td><?= $data->order->customer_whatsapp ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card border-0 mt-4">
            <div class="card-header">
                <h3 class="h5">Product Details</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td><strong>Product Name:</strong></td>
                        <td><?= $data->product->name ?></td>
                    </tr>
                    <tr>
                        <td><strong>Price:</strong></td>
                        <td><?= $data->order->price . ' ' . $this->settings->payment->currency ?></td>
                    </tr>
                    <tr>
                        <td><strong>Access URL:</strong></td>
                        <td><a href="<?= $data->product->access_url ?>" target="_blank"><?= $data->product->access_url ?></a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="<?= url('admin/digital-order-update-status/' . $data->order->order_id) ?>" class="btn btn-primary">Update Status</a>
</div>