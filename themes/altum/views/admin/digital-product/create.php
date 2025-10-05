<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-box text-gray-700"></i> <?= 'Create Digital Product' ?></h1>
    
    <div class="col-auto p-0">
        <a href="<?= url('admin/digital-products') ?>" class="btn btn-outline-secondary rounded-pill"><i class="fa fa-fw fa-arrow-left"></i> <?= 'Back' ?></a>
    </div>
</div>

<?php display_notifications() ?>

<div class="card border-0 col-12 col-xl-12 mt-5 p-4">
    <form action="" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
        
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="form-group">
                    <label for="name"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= 'Product Name' ?></label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required="required" />
                </div>
                
                <div class="form-group">
                    <label for="description"><i class="fa fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= 'Description' ?></label>
                    <textarea id="description" name="description" class="form-control" rows="5" required="required"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="price"><i class="fa fa-fw fa-sm fa-dollar-sign text-muted mr-1"></i> <?= 'Price' ?></label>
                    <input type="number" id="price" name="price" class="form-control" min="0" step="0.01" value="<?= isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '' ?>" required="required" />
                </div>
                
                <div class="form-group">
                    <label for="access_url"><i class="fa fa-fw fa-sm fa-link text-muted mr-1"></i> <?= 'Access URL' ?></label>
                    <input type="url" id="access_url" name="access_url" class="form-control" value="<?= isset($_POST['access_url']) ? htmlspecialchars($_POST['access_url']) : '' ?>" placeholder="https://example.com/product-access" required="required" />
                </div>
            </div>
            
            <div class="col-12 col-lg-6">
                <div class="form-group">
                    <label for="image"><i class="fa fa-fw fa-sm fa-image text-muted mr-1"></i> <?= 'Product Image' ?></label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*" />
                    <small class="form-text text-muted"><?= 'Upload an image for your product (optional)' ?></small>
                </div>
                
                <div class="form-group">
                    <label for="status"><i class="fa fa-fw fa-sm fa-toggle-on text-muted mr-1"></i> <?= 'Status' ?></label>
                    <select id="status" name="status" class="form-control">
                        <option value="active" <?= (isset($_POST['status']) && $_POST['status'] == 'active') ? 'selected="selected"' : '' ?>>Active</option>
                        <option value="inactive" <?= (isset($_POST['status']) && $_POST['status'] == 'inactive') ? 'selected="selected"' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" name="submit" class="btn btn-primary"><?= 'Create Product' ?></button>
        </div>
    </form>
</div>