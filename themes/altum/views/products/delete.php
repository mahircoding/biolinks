<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center">
            <a href="products/<?= $data->product->product_id ?>" class="btn btn-light mr-3">
                <i class="fa fa-fw fa-arrow-left"></i>
            </a>
            <h1 class="h4 m-0"><i class="fa fa-fw fa-trash mr-1"></i> Delete Product</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fa fa-fw fa-exclamation-triangle mr-1"></i> Confirm Deletion</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <?php if($data->product->image): ?>
                                <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'products/' . $data->product->image ?>" 
                                     class="img-fluid rounded mb-3" 
                                     style="max-height: 200px; object-fit: cover;" 
                                     alt="<?= $data->product->title ?>" />
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 150px;">
                                    <i class="fa fa-box fa-3x text-muted"></i>
                                </div>
                            <?php endif ?>
                        </div>
                        <div class="col-md-8">
                            <h3 class="h4 mb-3"><?= $data->product->title ?></h3>
                            <p class="text-muted mb-3"><?= nl2br($data->product->description) ?></p>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Price:</strong><br>
                                    <span class="text-primary h5">Rp <?= number_format($data->product->price, 0, ',', '.') ?></span>
                                </div>
                                <div class="col-6">
                                    <strong>Category:</strong><br>
                                    <span class="badge badge-primary"><?= ucfirst($data->product->category) ?></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <strong>Views:</strong> <?= $data->product->views ?>
                                </div>
                                <div class="col-6">
                                    <strong>Sales:</strong> <?= $data->product->sales ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-danger">
                        <h6><i class="fa fa-fw fa-exclamation-triangle mr-1"></i> Warning!</h6>
                        <p class="mb-0">
                            You are about to permanently delete this product. This action cannot be undone.
                            All associated data including sales history and statistics will be lost.
                        </p>
                    </div>

                    <form action="" method="post" role="form">
                        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
                        <input type="hidden" name="request_type" value="delete" />

                        <div class="form-group">
                            <label for="confirmation">
                                <strong>Type "DELETE" to confirm:</strong>
                            </label>
                            <input type="text" id="confirmation" name="confirmation" class="form-control" placeholder="Type DELETE here" required />
                            <small class="form-text text-muted">This confirmation is required to prevent accidental deletions.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" name="submit" class="btn btn-danger btn-block" id="delete-btn" disabled>
                                    <i class="fa fa-fw fa-trash mr-1"></i> Delete Product
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="products/<?= $data->product->product_id ?>" class="btn btn-secondary btn-block">
                                    <i class="fa fa-fw fa-times mr-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Enable delete button only when "DELETE" is typed
    $('#confirmation').on('input', function() {
        var deleteBtn = $('#delete-btn');
        if ($(this).val().toUpperCase() === 'DELETE') {
            deleteBtn.prop('disabled', false);
        } else {
            deleteBtn.prop('disabled', true);
        }
    });

    $('form').on('submit', function(e) {
        e.preventDefault();
        
        if ($('#confirmation').val().toUpperCase() !== 'DELETE') {
            $.toast({
                text: 'Please type "DELETE" to confirm.',
                heading: 'Error',
                icon: 'error',
                showHideTransition: 'slide',
                allowToastClose: true,
                hideAfter: 3000,
                stack: 5,
                position: 'top-right',
                textAlign: 'left',
                loader: true,
                loaderBg: '#f56565'
            });
            return;
        }
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            success: function(response) {
                $.toast({
                    text: 'Product deleted successfully!',
                    heading: 'Success',
                    icon: 'success',
                    showHideTransition: 'slide',
                    allowToastClose: true,
                    hideAfter: 3000,
                    stack: 5,
                    position: 'top-right',
                    textAlign: 'left',
                    loader: true,
                    loaderBg: '#9EC600'
                });
                
                setTimeout(function() {
                    window.location.href = 'products';
                }, 1500);
            },
            error: function() {
                $.toast({
                    text: 'An error occurred while deleting the product.',
                    heading: 'Error',
                    icon: 'error',
                    showHideTransition: 'slide',
                    allowToastClose: true,
                    hideAfter: 5000,
                    stack: 5,
                    position: 'top-right',
                    textAlign: 'left',
                    loader: true,
                    loaderBg: '#f56565'
                });
            }
        });
    });
});
</script>