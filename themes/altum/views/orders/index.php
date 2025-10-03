<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h4 mb-0"><?= \Altum\Language::get('orders', 'title') ?></h2>
                        <div class="btn-group">
                            <a href="<?= url('orders') ?>" class="btn btn-outline-secondary <?= $active_tab == 'all' ? 'active' : '' ?>">
                                <?= \Altum\Language::get('orders', 'tabs.all') ?>
                            </a>
                            <a href="<?= url('orders?status=pending') ?>" class="btn btn-outline-secondary <?= $active_tab == 'pending' ? 'active' : '' ?>">
                                <?= \Altum\Language::get('orders', 'tabs.pending') ?>
                            </a>
                            <a href="<?= url('orders?status=completed') ?>" class="btn btn-outline-secondary <?= $active_tab == 'completed' ? 'active' : '' ?>">
                                <?= \Altum\Language::get('orders', 'tabs.completed') ?>
                            </a>
                            <a href="<?= url('orders?status=failed') ?>" class="btn btn-outline-secondary <?= $active_tab == 'failed' ? 'active' : '' ?>">
                                <?= \Altum\Language::get('orders', 'tabs.failed') ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-white-50"><?= \Altum\Language::get('orders', 'stats.total_orders') ?></h6>
                                            <h3 class="mb-0"><?= $order_stats['total_orders'] ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-shopping-cart fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-white-50"><?= \Altum\Language::get('orders', 'stats.completed_orders') ?></h6>
                                            <h3 class="mb-0"><?= $order_stats['completed_orders'] ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-white-50"><?= \Altum\Language::get('orders', 'stats.pending_orders') ?></h6>
                                            <h3 class="mb-0"><?= $order_stats['pending_orders'] ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-white-50"><?= \Altum\Language::get('orders', 'stats.failed_orders') ?></h6>
                                            <h3 class="mb-0"><?= $order_stats['failed_orders'] ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-times-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?= \Altum\Language::get('orders', 'table.order_id') ?></th>
                                    <th><?= \Altum\Language::get('orders', 'table.product') ?></th>
                                    <th><?= \Altum\Language::get('orders', 'table.customer') ?></th>
                                    <th><?= \Altum\Language::get('orders', 'table.amount') ?></th>
                                    <th><?= \Altum\Language::get('orders', 'table.status') ?></th>
                                    <th><?= \Altum\Language::get('orders', 'table.date') ?></th>
                                    <th><?= \Altum\Language::get('orders', 'table.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($orders)): ?>
                                    <?php foreach($orders as $order): ?>
                                        <tr>
                                            <td>
                                                <code><?= $order['order_id'] ?></code>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="font-weight-medium"><?= $order['product_name'] ?></div>
                                                    <small class="text-muted"><?= $order['product_id'] ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="font-weight-medium"><?= $order['customer_name'] ?? '-' ?></div>
                                                    <small class="text-muted"><?= $order['customer_email'] ?? '-' ?></small>
                                                </div>
                                            </td>
                                            <td>Rp <?= number_format($order['amount'], 0, ',', '.') ?></td>
                                            <td>
                                                <?php if($order['status'] == 'completed'): ?>
                                                    <span class="badge badge-success"><?= \Altum\Language::get('orders', 'status.completed') ?></span>
                                                <?php elseif($order['status'] == 'pending'): ?>
                                                    <span class="badge badge-warning"><?= \Altum\Language::get('orders', 'status.pending') ?></span>
                                                <?php elseif($order['status'] == 'failed'): ?>
                                                    <span class="badge badge-danger"><?= \Altum\Language::get('orders', 'status.failed') ?></span>
                                                <?php elseif($order['status'] == 'cancelled'): ?>
                                                    <span class="badge badge-secondary"><?= \Altum\Language::get('orders', 'status.cancelled') ?></span>
                                                <?php else: ?>
                                                    <span class="badge badge-info"><?= \Altum\Language::get('orders', 'status.processing') ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= \Altum\Date::get($order['datetime'], 1) ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= url('orders/view/' . $order['order_id']) ?>" class="btn btn-sm btn-outline-primary" title="<?= \Altum\Language::get('orders', 'view') ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if($order['status'] == 'pending'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-success mark-completed" data-order-id="<?= $order['order_id'] ?>" title="<?= \Altum\Language::get('orders', 'mark_completed') ?>">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <?php if($order['status'] == 'completed'): ?>
                                                        <a href="<?= url('orders/download/' . $order['order_id']) ?>" class="btn btn-sm btn-outline-success" title="<?= \Altum\Language::get('orders', 'download') ?>">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0"><?= \Altum\Language::get('orders', 'no_orders') ?></p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if($total_pages > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php if($current_page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('orders?page=' . $previous_page . $status_query) ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for($i = $start_range; $i <= $end_range; $i++): ?>
                                    <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= url('orders?page=' . $i . $status_query) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if($current_page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('orders?page=' . $next_page . $status_query) ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mark Completed Modal -->
<div class="modal fade" id="markCompletedModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= \Altum\Language::get('orders', 'mark_completed_modal.title') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?= \Altum\Language::get('orders', 'mark_completed_modal.confirmation') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= \Altum\Language::get('global', 'cancel') ?></button>
                <form method="post" action="<?= url('orders/mark-completed') ?>" id="markCompletedForm">
                    <input type="hidden" name="order_id" id="markCompletedOrderId">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-2"></i><?= \Altum\Language::get('orders', 'mark_completed') ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark completed functionality
    const markCompletedButtons = document.querySelectorAll('.mark-completed');
    const markCompletedModal = document.getElementById('markCompletedModal');
    const markCompletedForm = document.getElementById('markCompletedForm');
    const markCompletedOrderId = document.getElementById('markCompletedOrderId');

    markCompletedButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            markCompletedOrderId.value = orderId;
            $(markCompletedModal).modal('show');
        });
    });

    // Handle form submission
    markCompletedForm.addEventListener('submit', function(e) {
        e.preventDefault();
        this.submit();
    });
});
</script>