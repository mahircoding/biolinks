<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-shopping-cart text-gray-700"></i> <?= 'Digital Orders' ?></h1>
</div>

<?php display_notifications() ?>

<div class="mt-5 table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
        <tr>
            <th>Order ID</th>
            <th>Product</th>
            <th>Customer</th>
            <th>Price</th>
            <th>Status</th>
            <th>Date</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($data->orders)): ?>
            <?php foreach($data->orders as $order): ?>
                <tr>
                    <td><?= $order->order_id ?></td>
                    <td><?= $order->product_name ?></td>
                    <td><?= $order->customer_name ?></td>
                    <td><?= $order->price . ' ' . $this->settings->payment->currency ?></td>
                    <td>
                        <?php 
                        $status_badge = '';
                        switch($order->payment_status) {
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
                    <td><?= (new \DateTime($order->date))->format('Y-m-d H:i') ?></td>
                    <td>
                        <div class="dropdown">
                            <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                                <i class="fa fa-ellipsis-v"></i>
                                
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="<?= url('admin/digital-order-view/' . $order->order_id) ?>"><i class="fa fa-fw fa-eye"></i> View</a>
                                    <a class="dropdown-item" href="<?= url('admin/digital-order-update-status/' . $order->order_id) ?>"><i class="fa fa-fw fa-edit"></i> Update Status</a>
                                </div>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">No orders found</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>