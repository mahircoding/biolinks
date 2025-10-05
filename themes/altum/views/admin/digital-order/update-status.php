<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-edit text-gray-700"></i> <?= 'Update Order Status #' . $data->order->order_id ?></h1>
    
    <div class="col-auto p-0">
        <a href="<?= url('admin/digital-orders') ?>" class="btn btn-outline-secondary rounded-pill"><i class="fa fa-fw fa-arrow-left"></i> <?= 'Back' ?></a>
    </div>
</div>

<?php display_notifications() ?>

<div class="card border-0 col-12 col-xl-12 mt-5 p-4">
    <form action="" method="post" role="form">
        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
        
        <div class="form-group">
            <label for="payment_status"><i class="fa fa-fw fa-sm fa-toggle-on text-muted mr-1"></i> <?= 'Payment Status' ?></label>
            <select id="payment_status" name="payment_status" class="form-control">
                <option value="pending" <?= ($data->order->payment_status == 'pending') ? 'selected="selected"' : '' ?>>Pending</option>
                <option value="paid" <?= ($data->order->payment_status == 'paid') ? 'selected="selected"' : '' ?>>Paid</option>
                <option value="failed" <?= ($data->order->payment_status == 'failed') ? 'selected="selected"' : '' ?>>Failed</option>
                <option value="refunded" <?= ($data->order->payment_status == 'refunded') ? 'selected="selected"' : '' ?>>Refunded</option>
            </select>
        </div>
        
        <div class="mt-4">
            <button type="submit" name="submit" class="btn btn-primary"><?= 'Update Status' ?></button>
        </div>
    </form>
</div>

<div class="card border-0 col-12 col-xl-12 mt-5 p-4">
    <h3 class="h5">Order Information</h3>
    
    <table class="table">
        <tr>
            <td><strong>Product:</strong></td>
            <td><?= $data->order->product_name ?></td>
        </tr>
        <tr>
            <td><strong>Customer:</strong></td>
            <td><?= $data->order->customer_name ?></td>
        </tr>
        <tr>
            <td><strong>Price:</strong></td>
            <td><?= $data->order->price . ' ' . $this->settings->payment->currency ?></td>
        </tr>
        <tr>
            <td><strong>Current Status:</strong></td>
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