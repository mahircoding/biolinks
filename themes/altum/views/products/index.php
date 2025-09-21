<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row mb-4">
        <div class="col-12 col-lg-12">
            <div class="card border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg d-flex align-items-center">
                            <h1 class="h4 text-truncate">My Products</h1>
                        </div>
                        
                        <div class="col-12 col-lg-auto d-flex">
                            <a href="<?= url('products/create') ?>" class="btn btn-primary">
                                <i class="fa fa-fw fa-plus-circle fa-sm"></i> Add Product
                            </a>
                        </div>
                    </div>
                    
                    <!-- Product Display Link -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fa fa-store fa-fw mr-2"></i>
                                <div class="flex-grow-1">
                                    <strong>Link Toko Produk Anda:</strong>
                                    <br>
                                    <a href="<?= url($this->user->user_id) ?>" target="_blank" class="text-decoration-none">
                                        <?= SITE_URL . $this->user->user_id ?>
                                    </a>
                                    <small class="text-muted d-block">
                                        Bagikan link ini untuk menampilkan semua produk Anda (User ID: <?= $this->user->user_id ?>)
                                    </small>
                                </div>
                                <div class="ml-2">
                                    <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('<?= SITE_URL . $this->user->user_id ?>')">
                                        <i class="fa fa-copy"></i> Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(count($data->products)): ?>
        <div class="row">
            <?php foreach($data->products as $product): ?>
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <?php if($product->image): ?>
                            <img src="<?= SITE_URL . 'uploads/products/' . $product->image ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= $product->name ?>">
                        <?php endif ?>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= $product->name ?></h5>
                            <p class="card-text text-muted small"><?= string_truncate($product->description, 100) ?></p>
                            
                            <div class="mt-auto">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <span class="h5 text-primary"><?= format_idr($product->price) ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <span class="badge <?= $product->status ? 'badge-success' : 'badge-secondary' ?>">
                                            <?= $product->status ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col">
                                        <small class="text-muted">
                                            <i class="fa fa-fw fa-eye"></i> <?= $product->views ?> views
                                            <i class="fa fa-fw fa-shopping-cart ml-2"></i> <?= $product->sales ?> sales
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="btn-group btn-group-sm mt-3 w-100" role="group">
                                    <a href="<?= url('products/view/' . $product->product_id) ?>" class="btn btn-outline-primary" target="_blank">
                                        <i class="fa fa-fw fa-eye"></i> View
                                    </a>
                                    <a href="<?= url('products/update/' . $product->product_id) ?>" class="btn btn-outline-secondary">
                                        <i class="fa fa-fw fa-pencil-alt"></i> Edit
                                    </a>
                                    <a href="<?= url('products/delete/' . $product->product_id) ?>" class="btn btn-outline-danger">
                                        <i class="fa fa-fw fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center py-3">
                    <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-4 mb-3" alt="No products" />
                    <h2 class="h4">No products yet</h2>
                    <p class="text-muted">You haven't created any products yet. Start selling your digital products!</p>
                    
                    <a href="<?= url('products/create') ?>" class="btn btn-primary">
                        <i class="fa fa-fw fa-plus-circle fa-sm"></i> Create your first product
                    </a>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-check"></i> Copied!';
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-success');
        
        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-primary');
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Failed to copy link');
    });
}
</script>