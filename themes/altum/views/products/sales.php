<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-body">
                    <h1 class="h4">Sales Dashboard</h1>
                    <p class="text-muted">Monitor your product sales and customer orders</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 h-100">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col">
                            <h3 class="h4 text-primary">Today</h3>
                            <div class="mt-2">
                                <span class="h5 text-success"><?= format_idr($data->sales_stats->today ?? 0) ?></span>
                                <div class="text-muted small"><?= ($data->sales_count->today ?? 0) ?> orders</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 h-100">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col">
                            <h3 class="h4 text-info">This Week</h3>
                            <div class="mt-2">
                                <span class="h5 text-success"><?= format_idr($data->sales_stats->this_week ?? 0) ?></span>
                                <div class="text-muted small"><?= ($data->sales_count->this_week ?? 0) ?> orders</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 h-100">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col">
                            <h3 class="h4 text-warning">This Month</h3>
                            <div class="mt-2">
                                <span class="h5 text-success"><?= format_idr($data->sales_stats->this_month ?? 0) ?></span>
                                <div class="text-muted small"><?= ($data->sales_count->this_month ?? 0) ?> orders</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 h-100">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col">
                            <h3 class="h4 text-success">Total</h3>
                            <div class="mt-2">
                                <span class="h5 text-success"><?= format_idr($data->sales_stats->total ?? 0) ?></span>
                                <div class="text-muted small"><?= ($data->sales_count->total ?? 0) ?> orders</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-body">
                    <h2 class="h5 mb-3">Recent Orders</h2>
                    
                    <?php if(count($data->recent_orders)): ?>
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data->recent_orders as $order): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if($order->product_image): ?>
                                                        <img src="<?= SITE_URL . 'uploads/products/' . $order->product_image ?>" class="img-fluid rounded mr-2" style="width: 40px; height: 40px; object-fit: cover;" alt="<?= $order->product_name ?>">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center mr-2" style="width: 40px; height: 40px;">
                                                            <i class="fa fa-image text-muted"></i>
                                                        </div>
                                                    <?php endif ?>
                                                    <div>
                                                        <div class="font-weight-bold"><?= $order->product_name ?></div>
                                                        <small class="text-muted">ID: <?= $order->order_id ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold"><?= $order->customer_name ?: 'Guest' ?></div>
                                                <?php if($order->customer_phone): ?>
                                                    <small class="text-muted"><?= $order->customer_phone ?></small>
                                                <?php endif ?>
                                            </td>
                                            <td><?= $order->customer_email ?></td>
                                            <td>
                                                <span class="font-weight-bold text-success"><?= format_idr($order->amount) ?></span>
                                            </td>
                                            <td>
                                                <span class="badge <?= $order->status == 'completed' ? 'badge-success' : ($order->status == 'pending' ? 'badge-warning' : 'badge-danger') ?>">
                                                    <?= ucfirst($order->status) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div><?= \Altum\Date::get($order->datetime, 1) ?></div>
                                                <small class="text-muted"><?= \Altum\Date::get($order->datetime, 2) ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <a href="<?= url('orders') ?>" class="btn btn-outline-primary">
                                <i class="fa fa-fw fa-sm fa-eye mr-1"></i> View All Orders
                            </a>
                        </div>
                        
                    <?php else: ?>
                        <div class="d-flex flex-column align-items-center justify-content-center py-3">
                            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_data.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="No Orders" />
                            <h2 class="h4 text-muted">No Orders Yet</h2>
                            <p class="text-muted">You haven't received any orders for your products yet</p>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.product-avatar {
    width: 2.5rem;
    height: 2.5rem;
    object-fit: cover;
}

.icon-container {
    width: 3rem;
    height: 3rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-primary-100 { background-color: rgba(99, 102, 241, 0.1); }
.text-primary-600 { color: #4f46e5; }
.bg-success-100 { background-color: rgba(34, 197, 94, 0.1); }
.text-success-600 { color: #16a34a; }
.bg-warning-100 { background-color: rgba(245, 158, 11, 0.1); }
.text-warning-600 { color: #d97706; }
.bg-info-100 { background-color: rgba(59, 130, 246, 0.1); }
.text-info-600 { color: #2563eb; }

.badge-light-success {
    color: #16a34a;
    background-color: rgba(34, 197, 94, 0.1);
}

.badge-light-warning {
    color: #d97706;
    background-color: rgba(245, 158, 11, 0.1);
}

.badge-light-danger {
    color: #dc2626;
    background-color: rgba(239, 68, 68, 0.1);
}
</style>