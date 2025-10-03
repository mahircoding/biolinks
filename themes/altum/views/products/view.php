<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center">
            <a href="products" class="btn btn-light mr-3">
                <i class="fa fa-fw fa-arrow-left"></i>
            </a>
            <h1 class="h4 m-0"><i class="fa fa-fw fa-eye mr-1"></i> Product Details</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <?php if($data->product->image): ?>
                        <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'products/' . $data->product->image ?>" 
                             class="img-fluid rounded mb-3" 
                             style="max-height: 300px; object-fit: cover;" 
                             alt="<?= $data->product->title ?>" />
                    <?php else: ?>
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 200px;">
                            <i class="fa fa-box fa-3x text-muted"></i>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="h3 mb-3"><?= $data->product->title ?></h2>
                    
                    <div class="mb-3">
                        <span class="badge badge-<?= $data->product->is_enabled ? 'success' : 'secondary' ?> mr-2">
                            <?= $data->product->is_enabled ? 'Active' : 'Disabled' ?>
                        </span>
                        <span class="badge badge-primary"><?= ucfirst($data->product->category) ?></span>
                    </div>

                    <p class="text-muted mb-4"><?= nl2br($data->product->description) ?></p>

                    <div class="mb-4">
                        <h3 class="h4 text-primary mb-0">Rp <?= number_format($data->product->price, 0, ',', '.') ?></h3>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h5 mb-1"><?= $data->product->views ?></div>
                                <small class="text-muted">Views</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h5 mb-1"><?= $data->product->sales ?></div>
                                <small class="text-muted">Sales</small>
                            </div>
                        </div>
                    </div>

                    <?php if($data->product->digital_link): ?>
                        <div class="mb-4">
                            <label class="font-weight-bold">Product Link:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="<?= $data->product->digital_link ?>" readonly />
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('<?= $data->product->digital_link ?>')">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <a href="products/<?= $data->product->product_id ?>/update" class="btn btn-primary btn-block">
                                <i class="fa fa-fw fa-edit mr-1"></i> Edit Product
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <a href="products/<?= $data->product->product_id ?>/delete" class="btn btn-danger btn-block">
                                <i class="fa fa-fw fa-trash mr-1"></i> Delete Product
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted">Product ID:</td>
                                    <td><?= $data->product->product_id ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Created:</td>
                                    <td><?= date('d M Y H:i', strtotime($data->product->datetime)) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Last Updated:</td>
                                    <td><?= date('d M Y H:i', strtotime($data->product->last_datetime)) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted">Category:</td>
                                    <td><?= ucfirst($data->product->category) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        <span class="badge badge-<?= $data->product->is_enabled ? 'success' : 'secondary' ?>">
                                            <?= $data->product->is_enabled ? 'Active' : 'Disabled' ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total Revenue:</td>
                                    <td class="font-weight-bold text-success">
                                        Rp <?= number_format($data->product->sales * $data->product->price, 0, ',', '.') ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        $.toast({
            text: 'Link copied to clipboard!',
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
    });
}
</script>