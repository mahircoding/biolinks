<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="product_update_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Update Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="product_update" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
                    <input type="hidden" name="request_type" value="update" />
                    <input type="hidden" name="product_id" value="" />

                    <div class="form-group">
                        <label for="update_title">Product Title</label>
                        <input type="text" id="update_title" name="title" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="update_description">Description</label>
                        <textarea id="update_description" name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="update_price">Price (IDR)</label>
                                <input type="number" id="update_price" name="price" class="form-control" min="0" step="1000" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="update_category">Category</label>
                                <select id="update_category" name="category" class="form-control">
                                    <option value="ebook">E-book</option>
                                    <option value="course">Online Course</option>
                                    <option value="template">Template</option>
                                    <option value="software">Software</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="update_file">Replace Product File (Optional)</label>
                        <input type="file" id="update_file" name="file" class="form-control-file" />
                        <small class="form-text text-muted">Leave empty to keep current file. Maximum size: 50MB</small>
                    </div>

                    <div class="form-group">
                        <label for="update_image">Replace Product Image (Optional)</label>
                        <input type="file" id="update_image" name="image" class="form-control-file" accept="image/*" />
                        <small class="form-text text-muted">Leave empty to keep current image. Recommended size: 400x300px</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="update_is_enabled" name="is_enabled" />
                            <label class="custom-control-label" for="update_is_enabled">Enable Product</label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#product_update_modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var productId = button.data('product-id');
        
        // You would typically load product data via AJAX here
        // For now, just set the product ID
        $(this).find('input[name="product_id"]').val(productId);
    });
});
</script>