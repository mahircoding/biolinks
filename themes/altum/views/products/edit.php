<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row mb-4">
        <div class="col-12 col-lg-12">
            <div class="card border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg d-flex align-items-center">
                            <h1 class="h4 text-truncate">Edit Product</h1>
                        </div>
                        
                        <div class="col-12 col-lg-auto d-flex">
                            <a href="<?= url('products') ?>" class="btn btn-outline-secondary">
                                <i class="fa fa-fw fa-arrow-left fa-sm"></i> Back to Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card border-0">
                <div class="card-body">
                    <form action="" method="post" role="form" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?= $data->product->name ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea id="description" name="description" class="form-control" rows="5" required><?= $data->product->description ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Price (IDR) *</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" id="price" name="price" class="form-control" step="1000" min="1000" value="<?= $data->product->price ?>" required />
                            </div>
                            <small class="form-text text-muted">Minimum price: Rp 1.000</small>
                        </div>

                        <div class="form-group">
                            <label for="digital_link">Digital Link *</label>
                            <input type="url" id="digital_link" name="digital_link" class="form-control" value="<?= $data->product->digital_link ?>" placeholder="https://drive.google.com/..." required />
                            <small class="form-text text-muted">Link to download or access the digital product</small>
                        </div>

                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*" />
                            <?php if($data->product->image): ?>
                                <div class="mt-2">
                                    <small class="text-muted">Current image:</small><br>
                                    <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'products/' . $data->product->image ?>" alt="Current image" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            <?php endif ?>
                            <small class="form-text text-muted">Optional. Supported formats: JPG, PNG, GIF. Max file size: 2MB</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input id="status" name="status" type="checkbox" class="custom-control-input" <?= $data->product->status ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label" for="status">Active</label>
                            </div>
                            <small class="form-text text-muted">Only active products will be visible in the catalog</small>
                        </div>

                        <button type="submit" name="submit" class="btn btn-block btn-primary">Update Product</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0">
                <div class="card-body">
                    <h5 class="card-title">Product Preview</h5>
                    
                    <?php if($data->product->image): ?>
                        <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'products/' . $data->product->image ?>" alt="<?= $data->product->name ?>" class="card-img-top mb-3" style="max-height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light text-center py-5 mb-3">
                            <i class="fa fa-image fa-3x text-muted"></i>
                            <p class="text-muted mt-2">No image</p>
                        </div>
                    <?php endif ?>
                    
                    <h6><?= $data->product->name ?></h6>
                    <p class="text-muted small"><?= substr($data->product->description, 0, 100) ?>...</p>
                    <p class="h6 text-primary"><?= format_idr($data->product->price) ?></p>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-muted">Views</small>
                            <div class="font-weight-bold"><?= number_format($data->product->views) ?></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Sales</small>
                            <div class="font-weight-bold"><?= number_format($data->product->sales) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>