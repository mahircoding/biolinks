<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg d-flex align-items-center">
                            <h1 class="h4">Create New Product</h1>
                        </div>
                        
                        <div class="col-12 col-lg-auto d-flex">
                            <a href="<?= url('products') ?>" class="btn btn-outline-secondary">
                                <i class="fa fa-fw fa-arrow-left"></i> Back to Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <form action="" method="post" role="form" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" id="name" name="name" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea id="description" name="description" class="form-control" rows="5" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Price (IDR) *</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" id="price" name="price" class="form-control" step="1000" min="1000" placeholder="10000" required />
                            </div>
                            <small class="form-text text-muted">Minimum price: Rp 1.000</small>
                        </div>

                        <div class="form-group">
                            <label for="digital_link">Digital Access Link</label>
                            <input type="url" id="digital_link" name="digital_link" class="form-control" placeholder="https://example.com/download-link" />
                            <small class="form-text text-muted">External link where customers can access the digital product after purchase</small>
                        </div>

                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.gif" class="form-control-file" />
                            <small class="form-text text-muted">Upload a product image (JPG, PNG, GIF - max 2MB)</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input id="status" name="status" type="checkbox" class="custom-control-input" checked>
                                <label class="custom-control-label" for="status">Active</label>
                                <div><small class="form-text text-muted">Only active products will be visible in the catalog</small></div>
                            </div>
                        </div>

                        <button type="submit" name="submit" class="btn btn-block btn-primary">Create Product</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <h5 class="card-title">Product Guidelines</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fa fa-fw fa-check text-success"></i>
                            Use high-quality product images
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-fw fa-check text-success"></i>
                            Write clear, detailed descriptions
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-fw fa-check text-success"></i>
                            Set competitive pricing
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-fw fa-check text-success"></i>
                            Provide working access links
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-fw fa-check text-success"></i>
                            Test the purchase flow
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>