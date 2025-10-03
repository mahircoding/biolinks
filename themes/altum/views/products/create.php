<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center">
            <a href="products" class="btn btn-light mr-3">
                <i class="fa fa-fw fa-arrow-left"></i>
            </a>
            <h1 class="h4 m-0"><i class="fa fa-fw fa-plus mr-1"></i> Create New Product</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form name="product_create" method="post" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
                        <input type="hidden" name="request_type" value="create" />

                        <div class="form-group">
                            <label for="title">Product Title</label>
                            <input type="text" id="title" name="title" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4"></textarea>
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

                        <div class="row">
                            <div class="col-md-6">
                                <a href="products" class="btn btn-light btn-block">Cancel</a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" name="submit" class="btn btn-primary btn-block">Create Product</button>
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
    // Check for success message in session and show toast
    <?php if(isset($_SESSION['success'])): ?>
        <?php 
            $success_messages = is_array($_SESSION['success']) ? $_SESSION['success'] : [$_SESSION['success']];
            foreach($success_messages as $message): 
        ?>
            $.toast({
                text: '<?= addslashes($message) ?>',
                heading: 'Berhasil!',
                icon: 'success',
                showHideTransition: 'slide',
                allowToastClose: true,
                hideAfter: 5000,
                stack: 5,
                position: 'top-right',
                textAlign: 'left',
                loader: true,
                loaderBg: '#9EC600'
            });
        <?php endforeach; ?>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
});
</script>