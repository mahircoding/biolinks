<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="product_create_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Create New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="product_create" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
                    <input type="hidden" name="request_type" value="create" />

                    <div class="form-group">
                        <label for="title">Product Title</label>
                        <input type="text" id="title" name="title" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price">Price (IDR)</label>
                                <input type="number" id="price" name="price" class="form-control" min="0" step="1000" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select id="category" name="category" class="form-control">
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
                        <label for="file">Product File Link</label>
                        <input type="url" id="file" name="file" class="form-control" placeholder="https://example.com/your-digital-product" required />
                        <small class="form-text text-muted">Enter the direct download link or access URL for your digital product</small>
                    </div>

                    <div class="form-group">
                        <label for="image">Product Image (Optional)</label>
                        <input type="file" id="image" name="image" class="form-control-file" accept="image/*" />
                        <small class="form-text text-muted">Recommended size: 400x300px</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_enabled" name="is_enabled" checked />
                            <label class="custom-control-label" for="is_enabled">Enable Product</label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary">Create Product</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>