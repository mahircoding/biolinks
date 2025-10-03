<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="product_delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Delete Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>Are you sure you want to delete this product? This action cannot be undone.</p>
                
                <form name="product_delete" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
                    <input type="hidden" name="request_type" value="delete" />
                    <input type="hidden" name="product_id" value="" />

                    <div class="text-center">
                        <button type="button" class="btn btn-gray-300 mr-2" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit" class="btn btn-danger">Delete Product</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#product_delete_modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var productId = button.data('product-id');
        
        $(this).find('input[name="product_id"]').val(productId);
    });
});
</script>