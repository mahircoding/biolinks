<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h2 class="h4 mb-0 text-center">
                        <i class="fas fa-shopping-cart mr-2"></i><?= \Altum\Language::get('orders', 'payment.title') ?>
                    </h2>
                </div>
                <div class="card-body">
                    <!-- Progress Steps -->
                    <div class="d-flex justify-content-center mb-4">
                        <div class="step active">
                            <div class="step-number">1</div>
                            <div class="step-label"><?= \Altum\Language::get('orders', 'payment.steps.cart') ?></div>
                        </div>
                        <div class="step active">
                            <div class="step-number">2</div>
                            <div class="step-label"><?= \Altum\Language::get('orders', 'payment.steps.checkout') ?></div>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-label"><?= \Altum\Language::get('orders', 'payment.steps.payment') ?></div>
                        </div>
                        <div class="step">
                            <div class="step-number">4</div>
                            <div class="step-label"><?= \Altum\Language::get('orders', 'payment.steps.complete') ?></div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-shopping-bag mr-2"></i><?= \Altum\Language::get('orders', 'payment.order_summary') ?>
                                    </h5>
                                    <div class="order-summary">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?= \Altum\Language::get('orders', 'payment.product') ?>:</span>
                                            <strong><?= $product['name'] ?></strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?= \Altum\Language::get('orders', 'payment.product_id') ?>:</span>
                                            <code><?= $product['product_id'] ?></code>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?= \Altum\Language::get('orders', 'payment.price') ?>:</span>
                                            <strong>Rp <?= number_format($product['price'], 0, ',', '.') ?></strong>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span class="font-weight-bold"><?= \Altum\Language::get('orders', 'payment.total') ?>:</span>
                                            <strong class="text-primary">Rp <?= number_format($product['price'], 0, ',', '.') ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-user mr-2"></i><?= \Altum\Language::get('orders', 'payment.customer_info') ?>
                                    </h5>
                                    <div class="customer-info">
                                        <div class="form-group">
                                            <label for="customer_name" class="form-label"><?= \Altum\Language::get('orders', 'payment.name') ?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="customer_email" class="form-label"><?= \Altum\Language::get('orders', 'payment.email') ?> <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="customer_phone" class="form-label"><?= \Altum\Language::get('orders', 'payment.phone') ?> <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-credit-card mr-2"></i><?= \Altum\Language::get('orders', 'payment.select_payment') ?>
                            </h5>
                            <div class="payment-methods">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="payment-method-card active" data-method="duitku">
                                            <div class="payment-method-header">
                                                <input type="radio" name="payment_method" value="duitku" id="payment_duitku" checked>
                                                <label for="payment_duitku" class="payment-method-label">
                                                    <i class="fas fa-university mr-2"></i><?= \Altum\Language::get('orders', 'payment.methods.bank_transfer') ?>
                                                </label>
                                            </div>
                                            <div class="payment-method-body">
                                                <p class="text-muted small"><?= \Altum\Language::get('orders', 'payment.methods.bank_transfer_desc') ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="payment-method-card" data-method="midtrans">
                                            <div class="payment-method-header">
                                                <input type="radio" name="payment_method" value="midtrans" id="payment_midtrans">
                                                <label for="payment_midtrans" class="payment-method-label">
                                                    <i class="fas fa-mobile-alt mr-2"></i><?= \Altum\Language::get('orders', 'payment.methods.e_wallet') ?>
                                                </label>
                                            </div>
                                            <div class="payment-method-body">
                                                <p class="text-muted small"><?= \Altum\Language::get('orders', 'payment.methods.e_wallet_desc') ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-group mb-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="terms" name="terms" required>
                            <label class="custom-control-label" for="terms">
                                <?= \Altum\Language::get('orders', 'payment.terms_agree') ?>
                                <a href="#" data-toggle="modal" data-target="#termsModal"><?= \Altum\Language::get('orders', 'payment.terms_link') ?></a>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg" id="processPayment">
                            <i class="fas fa-lock mr-2"></i><?= \Altum\Language::get('orders', 'payment.process_payment') ?>
                        </button>
                        <a href="<?= url('products/view/' . $product['product_id']) ?>" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left mr-2"></i><?= \Altum\Language::get('orders', 'payment.cancel') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= \Altum\Language::get('orders', 'payment.terms_title') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6><?= \Altum\Language::get('orders', 'payment.terms_section1') ?></h6>
                <p><?= \Altum\Language::get('orders', 'payment.terms_content1') ?></p>
                
                <h6><?= \Altum\Language::get('orders', 'payment.terms_section2') ?></h6>
                <p><?= \Altum\Language::get('orders', 'payment.terms_content2') ?></p>
                
                <h6><?= \Altum\Language::get('orders', 'payment.terms_section3') ?></h6>
                <p><?= \Altum\Language::get('orders', 'payment.terms_content3') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= \Altum\Language::get('global', 'close') ?></button>
            </div>
        </div>
    </div>
</div>

<style>
.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
}

.step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 20px;
    left: 50%;
    width: 100%;
    height: 2px;
    background: #e9ecef;
    z-index: 0;
}

.step.active:not(:last-child)::after {
    background: #007bff;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 8px;
    position: relative;
    z-index: 1;
}

.step.active .step-number {
    background: #007bff;
    color: white;
}

.step-label {
    font-size: 0.875rem;
    color: #6c757d;
    text-align: center;
}

.step.active .step-label {
    color: #007bff;
    font-weight: 600;
}

.payment-method-card {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-method-card:hover {
    border-color: #007bff;
}

.payment-method-card.active {
    border-color: #007bff;
    background: #f8f9fa;
}

.payment-method-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.payment-method-label {
    margin: 0;
    cursor: pointer;
    flex: 1;
}

.payment-method-label input[type="radio"] {
    margin-right: 10px;
}

@media (max-width: 768px) {
    .step:not(:last-child)::after {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentForm');
    const processPaymentBtn = document.getElementById('processPayment');
    const paymentMethodCards = document.querySelectorAll('.payment-method-card');

    // Payment method selection
    paymentMethodCards.forEach(card => {
        card.addEventListener('click', function() {
            const method = this.getAttribute('data-method');
            const radio = this.querySelector('input[type="radio"]');
            
            // Remove active class from all cards
            paymentMethodCards.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked card
            this.classList.add('active');
            
            // Check the radio button
            radio.checked = true;
        });
    });

    // Form validation and submission
    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const customerName = document.getElementById('customer_name').value.trim();
        const customerEmail = document.getElementById('customer_email').value.trim();
        const customerPhone = document.getElementById('customer_phone').value.trim();
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const terms = document.getElementById('terms').checked;

        // Validation
        if (!customerName) {
            alert('<?= \Altum\Language::get('orders', 'error.name_required') ?>');
            document.getElementById('customer_name').focus();
            return;
        }

        if (!customerEmail) {
            alert('<?= \Altum\Language::get('orders', 'error.email_required') ?>');
            document.getElementById('customer_email').focus();
            return;
        }

        if (!customerPhone) {
            alert('<?= \Altum\Language::get('orders', 'error.phone_required') ?>');
            document.getElementById('customer_phone').focus();
            return;
        }

        if (!terms) {
            alert('<?= \Altum\Language::get('orders', 'error.terms_required') ?>');
            return;
        }

        // Validate email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(customerEmail)) {
            alert('<?= \Altum\Language::get('orders', 'error.email_invalid') ?>');
            document.getElementById('customer_email').focus();
            return;
        }

        // Validate phone format (Indonesian phone number)
        const phoneRegex = /^(\+62|62|0)8[1-9][0-9]{6,9}$/;
        if (!phoneRegex.test(customerPhone)) {
            alert('<?= \Altum\Language::get('orders', 'error.phone_invalid') ?>');
            document.getElementById('customer_phone').focus();
            return;
        }

        // Show loading state
        processPaymentBtn.disabled = true;
        processPaymentBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><?= \Altum\Language::get('orders', 'payment.processing') ?>...';

        // Submit form
        this.submit();
    });
});
</script>