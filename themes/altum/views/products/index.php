<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h4 mb-0"><?= \Altum\Language::get('products', 'title') ?></h2>
                        <a href="<?= url('products/create') ?>" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i><?= \Altum\Language::get('products', 'create_new') ?>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-white-50"><?= \Altum\Language::get('products', 'stats.total_products') ?></h6>
                                            <h3 class="mb-0"><?= $user_stats['total_products'] ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-box fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-white-50"><?= \Altum\Language::get('products', 'stats.total_sales') ?></h6>
                                            <h3 class="mb-0"><?= $user_stats['total_sales'] ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-shopping-cart fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-white-50"><?= \Altum\Language::get('products', 'stats.total_revenue') ?></h6>
                                            <h3 class="mb-0">Rp <?= number_format($user_stats['total_revenue'], 0, ',', '.') ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-money-bill-wave fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-white-50"><?= \Altum\Language::get('products', 'stats.pending_orders') ?></h6>
                                            <h3 class="mb-0"><?= $order_stats['pending_orders'] ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?= \Altum\Language::get('products', 'table.product_name') ?></th>
                                    <th><?= \Altum\Language::get('products', 'table.price') ?></th>
                                    <th><?= \Altum\Language::get('products', 'table.sales') ?></th>
                                    <th><?= \Altum\Language::get('products', 'table.views') ?></th>
                                    <th><?= \Altum\Language::get('products', 'table.status') ?></th>
                                    <th><?= \Altum\Language::get('products', 'table.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($products)): ?>
                                    <?php foreach($products as $product): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if(!empty($product['image'])): ?>
                                                        <img src="<?= UPLOADS_URL_PATH . $product['image'] ?>" alt="<?= $product['name'] ?>" class="rounded mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="rounded mr-3 bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="font-weight-medium"><?= $product['name'] ?></div>
                                                        <small class="text-muted"><?= string_truncate($product['description'], 50) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                                            <td><span class="badge badge-success"><?= $product['sales'] ?></span></td>
                                            <td><span class="badge badge-info"><?= $product['views'] ?></span></td>
                                            <td>
                                                <?php if($product['status'] == 1): ?>
                                                    <span class="badge badge-success"><?= \Altum\Language::get('products', 'status.active') ?></span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary"><?= \Altum\Language::get('products', 'status.inactive') ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= url('products/view/' . $product['product_id']) ?>" class="btn btn-sm btn-outline-primary" title="<?= \Altum\Language::get('products', 'view') ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= url('products/edit/' . $product['product_id']) ?>" class="btn btn-sm btn-outline-secondary" title="<?= \Altum\Language::get('products', 'edit') ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-product" data-product-id="<?= $product['product_id'] ?>" title="<?= \Altum\Language::get('products', 'delete') ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0"><?= \Altum\Language::get('products', 'no_products') ?></p>
                                            <a href="<?= url('products/create') ?>" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus mr-2"></i><?= \Altum\Language::get('products', 'create_first_product') ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if($total_pages > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php if($current_page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('products?page=' . $previous_page) ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for($i = $start_range; $i <= $end_range; $i++): ?>
                                    <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= url('products?page=' . $i) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if($current_page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('products?page=' . $next_page) ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Product Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= \Altum\Language::get('products', 'delete_modal.title') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?= \Altum\Language::get('products', 'delete_modal.confirmation') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= \Altum\Language::get('global', 'cancel') ?></button>
                <form method="post" action="<?= url('products/delete') ?>" id="deleteProductForm">
                    <input type="hidden" name="selected_products[]" id="selectedProductId">
                    <button type="submit" name="delete" class="btn btn-danger">
                        <i class="fas fa-trash mr-2"></i><?= \Altum\Language::get('products', 'delete') ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete product functionality
    const deleteButtons = document.querySelectorAll('.delete-product');
    const deleteModal = document.getElementById('deleteProductModal');
    const deleteForm = document.getElementById('deleteProductForm');
    const selectedProductId = document.getElementById('selectedProductId');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            selectedProductId.value = productId;
            $(deleteModal).modal('show');
        });
    });

    // Handle form submission
    deleteForm.addEventListener('submit', function(e) {
        e.preventDefault();
        this.submit();
    });
});
</script>