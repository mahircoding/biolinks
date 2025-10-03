<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center">
            <a href="products/<?= $data->product->product_id ?>" class="btn btn-light mr-3">
                <i class="fa fa-fw fa-arrow-left"></i>
            </a>
            <h1 class="h4 m-0"><i class="fa fa-fw fa-edit mr-1"></i> Edit Product</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="" method="post" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
                        <input type="hidden" name="request_type" value="update" />

                        <div class="form-group">
                            <label for="title"><i class="fa fa-fw fa-signature fa-sm text-muted mr-1"></i> Product Title</label>
                            <input type="text" id="title" name="title" class="form-control" value="<?= $data->product->title ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="description"><i class="fa fa-fw fa-pen fa-sm text-muted mr-1"></i> Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4"><?= $data->product->description ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price"><i class="fa fa-fw fa-dollar-sign fa-sm text-muted mr-1"></i> Price (Rp)</label>
                                    <input type="number" id="price" name="price" class="form-control" value="<?= $data->product->price ?>" min="0" step="1000" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category"><i class="fa fa-fw fa-tags fa-sm text-muted mr-1"></i> Category</label>
                                    <select id="category" name="category" class="form-control" required>
                                        <option value="">Select Category</option>
                                        <option value="digital" <?= $data->product->category == 'digital' ? 'selected' : '' ?>>Digital</option>
                                        <option value="physical" <?= $data->product->category == 'physical' ? 'selected' : '' ?>>Physical</option>
                                        <option value="service" <?= $data->product->category == 'service' ? 'selected' : '' ?>>Service</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="digital_link"><i class="fa fa-fw fa-link fa-sm text-muted mr-1"></i> Product Link</label>
                            <input type="url" id="digital_link" name="digital_link" class="form-control" value="<?= $data->product->digital_link ?>" placeholder="https://example.com/product-link" />
                            <small class="form-text text-muted">Enter the URL link for this product (optional)</small>
                        </div>

                        <div class="form-group">
                            <label for="image"><i class="fa fa-fw fa-image fa-sm text-muted mr-1"></i> Product Image</label>
                            <?php if($data->product->image): ?>
                                <div class="mb-2">
                                    <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'products/' . $data->product->image ?>" 
                                         class="img-thumbnail" 
                                         style="max-height: 150px;" 
                                         alt="Current product image" />
                                    <div class="mt-2">
                                        <small class="text-muted">Current image: <?= $data->product->image ?></small>
                                    </div>
                                </div>
                            <?php endif ?>
                            <input type="file" id="image" name="image" accept=".gif,.png,.jpg,.jpeg,.svg" class="form-control-file" />
                            <small class="form-text text-muted">Leave empty to keep current image. Accepted formats: .gif, .png, .jpg, .jpeg, .svg</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" id="is_enabled" name="is_enabled" class="custom-control-input" <?= $data->product->is_enabled ? 'checked="checked"' : '' ?> />
                                <label class="custom-control-label" for="is_enabled">Enable Product</label>
                                <small class="form-text text-muted">Disabled products won't be visible to customers</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" name="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-fw fa-save mr-1"></i> Update Product
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
    $('form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $.toast({
                    text: 'Product updated successfully!',
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
                    window.location.href = 'products/<?= $data->product->product_id ?>';
                }, 1500);
            },
            error: function() {
                $.toast({
                    text: 'An error occurred while updating the product.',
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