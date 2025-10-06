<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container pt-5">
    <div class="d-flex">
        <h1 class="h3">Digital Orders</h1>
    </div>

<?php display_notifications() ?>

<div class="table-responsive mt-4">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Produk</th>
                <th>Pembeli</th>
                <th>Status</th>
                <th>Channel</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach(($data->orders ?? []) as $order): ?>
            <tr>
                <td><?= (int)$order->order_id ?></td>
                <td><?= $order->product_name ?></td>
                <td><?= $order->buyer_name ?><br><small><?= $order->buyer_email ?></small></td>
                <td>
                    <span class="badge badge-<?= $order->status === 'paid' ? 'success' : ($order->status === 'cancelled' ? 'danger' : ($order->status === 'refunded' ? 'warning' : 'secondary')) ?>">
                        <?= strtoupper($order->status) ?>
                    </span>
                </td>
                <td><?= $order->payment_channel ?: '-' ?></td>
                <td><?= $order->created_at ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#updateStatusModal<?= $order->order_id ?>">
                        <i class="fa fa-edit"></i> Update
                    </button>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    </div>

</section>

<!-- Update Status Modals -->
<?php foreach(($data->orders ?? []) as $order): ?>
<div class="modal fade" id="updateStatusModal<?= $order->order_id ?>" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel<?= $order->order_id ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel<?= $order->order_id ?>">Update Status Order #<?= $order->order_id ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= url('digital-order/update-status') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
                    <input type="hidden" name="order_id" value="<?= $order->order_id ?>" />
                    
                    <div class="form-group">
                        <label for="status<?= $order->order_id ?>">Status</label>
                        <select name="status" id="status<?= $order->order_id ?>" class="form-control" required>
                            <option value="pending" <?= $order->status === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= $order->status === 'paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="cancelled" <?= $order->status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            <option value="refunded" <?= $order->status === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="channel<?= $order->order_id ?>">Payment Channel (Optional)</label>
                        <input type="text" name="channel" id="channel<?= $order->order_id ?>" class="form-control" value="<?= htmlspecialchars($order->payment_channel ?? '') ?>" placeholder="e.g., QRIS, OVO, DANA, Bank Transfer">
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Info:</strong> Jika status diubah ke "Paid", sistem akan otomatis mengirim email akses produk ke pembeli.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach ?>

<script>
$(document).ready(function() {
    // Debug: Check if Bootstrap is loaded
    if (typeof $.fn.modal === 'undefined') {
        console.error('Bootstrap modal is not loaded!');
        alert('Bootstrap tidak dimuat. Modal tidak akan berfungsi.');
        return;
    }
    
    console.log('Bootstrap modal loaded successfully');
    
    // Handle modal show event
    $('[data-target^="#updateStatusModal"]').on('click', function() {
        var modalId = $(this).data('target');
        console.log('Opening modal:', modalId);
        
        // Show modal
        $(modalId).modal('show');
    });
    
    // Handle form submission - Simple form submit for now
    $('form[action*="update-status"]').on('submit', function(e) {
        var form = $(this);
        var orderId = form.find('input[name="order_id"]').val();
        var status = form.find('select[name="status"]').val();
        
        console.log('Submitting form for order:', orderId, 'Status:', status);
        
        // Show loading state
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
        
        // Allow normal form submission
        // Form will submit normally and redirect back to manage page
    });
    
    // Handle modal close
    $('.modal').on('hidden.bs.modal', function() {
        console.log('Modal closed');
    });
});
</script>

