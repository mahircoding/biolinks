<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0">
            <h1 class="h4 m-0 text-truncate"><i class="fa fa-fw fa-shopping-cart mr-1"></i> Order Management</h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="Manage your product orders and sales">
                    <i class="fa fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span class="h6 text-muted text-uppercase">Total Orders</span>
                            <span class="h3 d-block"><?= nr($data->stats['total_orders']) ?></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span class="h6 text-muted text-uppercase">Total Revenue</span>
                            <span class="h3 d-block">Rp <?= number_format($data->stats['total_revenue'], 0, ',', '.') ?></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fa fa-money-bill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span class="h6 text-muted text-uppercase">Completed Orders</span>
                            <span class="h3 d-block"><?= nr($data->stats['completed_orders']) ?></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fa fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span class="h6 text-muted text-uppercase">Pending Orders</span>
                            <span class="h3 d-block"><?= nr($data->stats['pending_orders']) ?></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fa fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <?php if(count($data->orders)): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data->orders as $row): ?>
                                <tr>
                                    <td>
                                        <span class="font-weight-bold">#<?= $row->order_id ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="font-weight-bold"><?= $row->product_title ?></div>
                                                <small class="text-muted">ID: <?= $row->product_id ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="font-weight-bold"><?= $row->customer_name ?></div>
                                            <small class="text-muted"><?= $row->customer_email ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold">Rp <?= number_format($row->total_amount, 0, ',', '.') ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $status_class = '';
                                        switch($row->status) {
                                            case 'completed':
                                                $status_class = 'success';
                                                break;
                                            case 'pending':
                                                $status_class = 'warning';
                                                break;
                                            case 'failed':
                                                $status_class = 'danger';
                                                break;
                                            default:
                                                $status_class = 'secondary';
                                        }
                                        ?>
                                        <span class="badge badge-<?= $status_class ?>"><?= ucfirst($row->status) ?></span>
                                    </td>
                                    <td>
                                        <span data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime) ?>">
                                            <?= \Altum\Date::get_timeago($row->datetime) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                                <i class="fa fa-fw fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#" data-toggle="modal" data-target="#order_view_modal" data-order-id="<?= $row->order_id ?>" class="dropdown-item">
                                                    <i class="fa fa-fw fa-sm fa-eye mr-2"></i>
                                                    View Details
                                                </a>
                                                <?php if($row->status == 'completed'): ?>
                                                    <a href="mailto:<?= $row->customer_email ?>" class="dropdown-item">
                                                        <i class="fa fa-fw fa-sm fa-envelope mr-2"></i>
                                                        Contact Customer
                                                    </a>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center py-3">
                    <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-4 mb-3" alt="No orders" />
                    <h2 class="h4 text-muted">No Orders Yet</h2>
                    <p class="text-muted">Orders will appear here once customers start purchasing your products.</p>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>