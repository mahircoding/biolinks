<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-box text-gray-700"></i> <?= 'Digital Products' ?></h1>

    <div class="col-auto p-0">
        <a href="<?= url('admin/digital-product-create') ?>" class="btn btn-primary rounded-pill"><i class="fa fa-fw fa-plus-circle"></i> <?= 'Create Product' ?></a>
    </div>
</div>

<?php display_notifications() ?>

<div class="mt-5 table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Status</th>
            <th>Created</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($data->products)): ?>
            <?php foreach($data->products as $product): ?>
                <tr>
                    <td><?= $product->name ?></td>
                    <td><?= $product->price . ' ' . $this->settings->payment->currency ?></td>
                    <td>
                        <?php if($product->status == 'active'): ?>
                            <span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> Active</span>
                        <?php else: ?>
                            <span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td><?= (new \DateTime($product->date))->format('Y-m-d') ?></td>
                    <td>
                        <div class="dropdown">
                            <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                                <i class="fa fa-ellipsis-v"></i>
                                
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="<?= url('admin/digital-product-update/' . $product->product_id) ?>"><i class="fa fa-fw fa-pencil-alt"></i> Edit</a>
                                    <a class="dropdown-item" href="<?= url('admin/digital-product-delete/' . $product->product_id) ?>" data-confirm="<?= 'Are you sure you want to delete this product?' ?>"><i class="fa fa-fw fa-times"></i> Delete</a>
                                </div>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">No products found</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>