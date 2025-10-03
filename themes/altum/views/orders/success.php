<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body text-center py-5">
                    <!-- Success Icon -->
                    <div class="success-icon mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>

                    <!-- Success Message -->
                    <h1 class="display-4 mb-3"><?= \Altum\Language::get('orders', 'success.title') ?></h1>
                    <p class="lead mb-4"><?= \Altum\Language::get('orders', 'success.subtitle') ?></p>

                    <!-- Order Details -->
                    <div class="order-details mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-receipt mr-2"></i><?= \Altum\Language::get('orders', 'success.order_details') ?>
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <strong><?= \Altum\Language::get('orders', 'success.order_id') ?>:</strong>
                                            <code><?= $order['order_id'] ?></code>
                                        </div>
                                        <div class="detail-item">
                                            <strong><?= \Altum\Language::get('orders', 'success.product') ?>:</strong>
                                            <?= $product['name'] ?>
                                        </div>
                                        <div class="detail-item">
                                            <strong><?= \Altum\Language::get('orders', 'success.amount') ?>:</strong>
                                            Rp <?= number_format($order['amount'], 0, ',', '.') ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <strong><?= \Altum\Language::get('orders', 'success.status') ?>:</strong>
                                            <span class="badge badge-success"><?= \Altum\Language::get('orders', 'status.completed') ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <strong><?= \Altum\Language::get('orders', 'success.payment_method') ?>:</strong>
                                            <?= \Altum\Language::get('orders', 'success.' . $order['payment_method']) ?>
                                        </div>
                                        <div class="detail-item">
                                            <strong><?= \Altum\Language::get('orders', 'success.date') ?>:</strong>
                                            <?= \Altum\Date::get($order['completed_datetime'], 1) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="<?= url('orders/download/' . $order['order_id']) ?>" class="btn btn-success btn-lg">
                                    <i class="fas fa-download mr-2"></i><?= \Altum\Language::get('orders', 'success.download_product') ?>
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="<?= url('orders') ?>" class="btn btn-primary btn-lg">
                                    <i class="fas fa-list mr-2"></i><?= \Altum\Language::get('orders', 'success.view_orders') ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Email Confirmation -->
                    <div class="alert alert-info">
                        <i class="fas fa-envelope mr-2"></i>
                        <?= \Altum\Language::get('orders', 'success.email_sent') ?>
                        <strong><?= $order['customer_email'] ?></strong>
                    </div>

                    <!-- Additional Information -->
                    <div class="additional-info">
                        <h6 class="mb-3"><?= \Altum\Language::get('orders', 'success.additional_info') ?></h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-info-circle mr-2 text-info"></i>
                                <?= \Altum\Language::get('orders', 'success.info1') ?>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-info-circle mr-2 text-info"></i>
                                <?= \Altum\Language::get('orders', 'success.info2') ?>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-info-circle mr-2 text-info"></i>
                                <?= \Altum\Language::get('orders', 'success.info3') ?>
                            </li>
                        </ul>
                    </div>

                    <!-- Continue Shopping -->
                    <div class="continue-shopping mt-4">
                        <a href="<?= url('products/catalog') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-shopping-bag mr-2"></i><?= \Altum\Language::get('orders', 'success.continue_shopping') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    animation: successPulse 1s ease-in-out;
}

@keyframes successPulse {
    0% {
        transform: scale(0.5);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.order-details {
    animation: slideUp 0.5s ease-out 0.3s both;
}

@keyframes slideUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.action-buttons {
    animation: slideUp 0.5s ease-out 0.6s both;
}

.additional-info {
    animation: slideUp 0.5s ease-out 0.9s both;
}

.continue-shopping {
    animation: slideUp 0.5s ease-out 1.2s both;
}

.detail-item {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.detail-item strong {
    color: #495057;
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .action-buttons .btn-lg {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Track successful order completion
    if('<?= $order['order_id'] ?>') {
        fetch('<?= url('api/orders/completed/' . $order['order_id']) ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                order_id: '<?= $order['order_id'] ?>'
            })
        }).catch(error => console.error('Error tracking completion:', error));
    }

    // Auto-redirect after 30 seconds if user doesn't interact
    let redirectTimeout = setTimeout(function() {
        window.location.href = '<?= url('orders') ?>';
    }, 30000);

    // Clear timeout if user interacts with the page
    document.addEventListener('click', function() {
        clearTimeout(redirectTimeout);
    });

    document.addEventListener('keypress', function() {
        clearTimeout(redirectTimeout);
    });
});
</script>