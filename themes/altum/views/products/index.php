<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0">
            <h1 class="h4 m-0 text-truncate"><i class="fa fa-fw fa-box mr-1"></i> <?= l('products.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('products.subheader') ?>">
                    <i class="fa fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <div class="col-12 col-lg-auto d-flex">
            <div>
                <button
                    id="bulk_enable"
                    type="button"
                    class="btn btn-gray-300 mr-2"
                    data-toggle="tooltip"
                    title="<?= l('global.bulk_actions') ?>"
                ><i class="fa fa-fw fa-list"></i></button>
            </div>

            <div>
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" title="<?= l('global.create') ?>">
                        <i class="fa fa-fw fa-plus-circle"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="products/create" class="dropdown-item">
                            <i class="fa fa-fw fa-sm fa-plus mr-2"></i>
                            <?= l('product.create') ?>
                        </a>
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

    <?php if(count($data->products)): ?>
        <div class="row">
            <?php foreach($data->products as $row): ?>
                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title text-truncate">
                                    <a href="products/<?= $row->product_id ?>" class="text-decoration-none">
                                        <?= $row->title ?>
                                    </a>
                                </h5>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                        <i class="fa fa-fw fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="products/<?= $row->product_id ?>" class="dropdown-item">
                                            <i class="fa fa-fw fa-sm fa-eye mr-2"></i>
                                            <?= l('global.view') ?>
                                        </a>
                                        <a href="products/<?= $row->product_id ?>/update" class="dropdown-item">
                                            <i class="fa fa-fw fa-sm fa-pencil-alt mr-2"></i>
                                            <?= l('global.edit') ?>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a href="products/<?= $row->product_id ?>/delete" class="dropdown-item">
                                            <i class="fa fa-fw fa-sm fa-trash mr-2"></i>
                                            <?= l('global.delete') ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <p class="card-text text-muted small mb-3"><?= string_truncate($row->description, 100) ?></p>

                            <div class="mt-auto">
                                <div class="row small text-muted mb-2">
                                    <div class="col-6">
                                        <i class="fa fa-fw fa-eye mr-1"></i>
                                        <?= nr($row->views) ?> <?= l('global.views') ?>
                                    </div>
                                    <div class="col-6">
                                        <i class="fa fa-fw fa-shopping-cart mr-1"></i>
                                        <?= nr($row->sales) ?> <?= l('global.sales') ?>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-<?= $row->is_enabled ? 'success' : 'secondary' ?>">
                                        <?= $row->is_enabled ? l('global.active') : l('global.disabled') ?>
                                    </span>
                                    <strong class="text-primary">Rp <?= number_format($row->price, 0, ',', '.') ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center py-3">
                    <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_data.svg' ?>" class="col-10 col-md-7 col-lg-4 mb-3" alt="No products" />
                <h2 class="h4 text-muted">No Products Yet</h2>
                <p class="text-muted">Create your first product to start selling digital items.</p>
            </div>
        </div>
    </div>
<?php endif ?>