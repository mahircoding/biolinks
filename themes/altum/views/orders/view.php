<?php defined('ALTUMCODE') || die() ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <h1 class="h3 mb-0 mr-1">Order #<?= $data->order->order_id ?></h1>
            <span class="badge badge-<?= $data->order->status == 'completed' ? 'success' : ($data->order->status == 'pending' ? 'warning' : 'secondary') ?> ml-2">
                <?= ucfirst($data->order->status) ?>
            </span>
        </div>
        <div>
            <a href="orders" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left mr-1"></i> Back to Orders
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Details -->
        <div class="col-12 col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h6>Product Information</h6>
                            <div class="d-flex mb-3">
                                <?php if($data->product->image): ?>
                                    <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'products/' . $data->product->image ?>" alt="<?= $data->product->title ?>" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="fa fa-box fa-2x text-muted"></i>
                                    </div>
                                <?php endif ?>
                                <div class="ml-3">
                                    <h6 class="mb-1"><?= $data->product->title ?></h6>
                                    <small class="text-muted"><?= ucfirst($data->product->category) ?></small>
                                    <div class="mt-1">
                                        <span class="font-weight-bold">Rp <?= number_format($data->order->amount, 0, ',', '.') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <h6>Order Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted">Order ID:</td>
                                    <td><?= $data->order->order_id ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Date:</td>
                                    <td><?= \Altum\Date::get($data->order->datetime, 2) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Payment Method:</td>
                                    <td><?= ucfirst($data->order->payment_method) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Transaction ID:</td>
                                    <td><?= $data->order->transaction_id ?: '-' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Actions -->
        <div class="col-12 col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted">Name:</td>
                            <td><?= $data->order->customer_name ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email:</td>
                            <td>
                                <a href="mailto:<?= $data->order->customer_email ?>"><?= $data->order->customer_email ?></a>
                            </td>
                        </tr>
                        <?php if($data->order->customer_phone): ?>
                        <tr>
                            <td class="text-muted">Phone:</td>
                            <td>
                                <a href="tel:<?= $data->order->customer_phone ?>"><?= $data->order->customer_phone ?></a>
                            </td>
                        </tr>
                        <?php endif ?>
                    </table>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="mailto:<?= $data->order->customer_email ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-envelope mr-1"></i> Contact Customer
                        </a>
                        
                        <?php if($data->order->status == 'pending'): ?>
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#mark_completed_modal">
                                <i class="fa fa-check mr-1"></i> Mark as Completed
                            </button>
                        <?php endif ?>
                        
                        <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#delete_order_modal">
                            <i class="fa fa-trash mr-1"></i> Delete Order
                        </button>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Payment Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>Rp <?= number_format($data->order->amount, 0, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>Rp 0</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between font-weight-bold">
                        <span>Total:</span>
                        <span class="text-primary">Rp <?= number_format($data->order->amount, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Timeline -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Order Created</h6>
                                <small class="text-muted"><?= \Altum\Date::get($data->order->datetime, 2) ?></small>
                            </div>
                        </div>
                        
                        <?php if($data->order->status == 'completed'): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Payment Completed</h6>
                                <small class="text-muted"><?= \Altum\Date::get($data->order->last_datetime, 2) ?></small>
                            </div>
                        </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mark Completed Modal -->
<?php if($data->order->status == 'pending'): ?>
<div class="modal fade" id="mark_completed_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Order as Completed</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to mark this order as completed?</p>
                <p class="text-muted">This action will notify the customer and cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form method="post" action="orders/update-status/<?= $data->order->order_id ?>" style="display: inline;">
                    <?= \Altum\Middlewares\Csrf::get() ?>
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn btn-success">Mark as Completed</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<!-- Delete Order Modal -->
<div class="modal fade" id="delete_order_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this order?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form method="post" action="orders/delete/<?= $data->order->order_id ?>" style="display: inline;">
                    <?= \Altum\Middlewares\Csrf::get() ?>
                    <button type="submit" class="btn btn-danger">Delete Order</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.d-grid {
    display: grid;
}

.gap-2 {
    gap: 0.5rem;
}
</style>