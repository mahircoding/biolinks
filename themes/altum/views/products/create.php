<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="h4 mb-0"><?= \Altum\Language::get('products', 'create_new') ?></h2>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" id="productCreateForm">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name" class="form-label"><?= \Altum\Language::get('products', 'form.name') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <small class="form-text text-muted"><?= \Altum\Language::get('products', 'form.name_help') ?></small>
                                </div>

                                <div class="form-group">
                                    <label for="description" class="form-label"><?= \Altum\Language::get('products', 'form.description') ?></label>
                                    <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                                    <small class="form-text text-muted"><?= \Altum\Language::get('products', 'form.description_help') ?></small>
                                </div>

                                <div class="form-group">
                                    <label for="price" class="form-label"><?= \Altum\Language::get('products', 'form.price') ?> <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control" id="price" name="price" min="1000" step="1000" required>
                                    </div>
                                    <small class="form-text text-muted"><?= \Altum\Language::get('products', 'form.price_help') ?></small>
                                </div>

                                <div class="form-group">
                                    <label for="digital_link" class="form-label"><?= \Altum\Language::get('products', 'form.digital_link') ?> <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control" id="digital_link" name="digital_link" required>
                                    <small class="form-text text-muted"><?= \Altum\Language::get('products', 'form.digital_link_help') ?></small>
                                </div>

                                <div class="form-group">
                                    <label for="status" class="form-label"><?= \Altum\Language::get('products', 'form.status') ?></label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" checked>
                                        <label class="custom-control-label" for="status"><?= \Altum\Language::get('products', 'form.status_help') ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="image" class="form-label"><?= \Altum\Language::get('products', 'form.image') ?></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                                        <label class="custom-file-label" for="image"><?= \Altum\Language::get('products', 'form.choose_image') ?></label>
                                    </div>
                                    <small class="form-text text-muted"><?= \Altum\Language::get('products', 'form.image_help') ?></small>
                                </div>

                                <div class="form-group">
                                    <label><?= \Altum\Language::get('products', 'form.preview') ?></label>
                                    <div class="product-preview">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <div class="product-image-placeholder">
                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                </div>
                                                <h5 class="product-name-placeholder mt-3">Product Name</h5>
                                                <p class="product-description-placeholder text-muted">Product description will appear here...</p>
                                                <div class="product-price-placeholder">
                                                    <span class="badge badge-primary">Rp 0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i><?= \Altum\Language::get('products', 'create_product') ?>
                            </button>
                            <a href="<?= url('products') ?>" class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i><?= \Altum\Language::get('global', 'cancel') ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productCreateForm');
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const priceInput = document.getElementById('price');
    const digitalLinkInput = document.getElementById('digital_link');
    const imageInput = document.getElementById('image');
    const customFileLabel = document.querySelector('.custom-file-label');

    // Update preview in real-time
    function updatePreview() {
        const name = nameInput.value || 'Product Name';
        const description = descriptionInput.value || 'Product description will appear here...';
        const price = priceInput.value ? 'Rp ' + parseInt(priceInput.value).toLocaleString('id-ID') : 'Rp 0';
        const digitalLink = digitalLinkInput.value;

        document.querySelector('.product-name-placeholder').textContent = name;
        document.querySelector('.product-description-placeholder').textContent = description;
        document.querySelector('.product-price-placeholder span').textContent = price;

        // Update image preview if file is selected
        if (imageInput.files && imageInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePlaceholder = document.querySelector('.product-image-placeholder');
                imagePlaceholder.innerHTML = `<img src="${e.target.result}" alt="Product preview" class="img-fluid rounded">`;
            };
            reader.readAsDataURL(imageInput.files[0]);
        }
    }

    // Add event listeners for real-time preview
    nameInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
    priceInput.addEventListener('input', updatePreview);
    digitalLinkInput.addEventListener('input', updatePreview);

    // Update custom file label
    imageInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            customFileLabel.textContent = this.files[0].name;
        } else {
            customFileLabel.textContent = '<?= \Altum\Language::get('products', 'form.choose_image') ?>';
        }
        updatePreview();
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Basic validation
        if (!nameInput.value.trim()) {
            alert('<?= \Altum\Language::get('products', 'error_message.name_required') ?>');
            nameInput.focus();
            return;
        }

        if (!descriptionInput.value.trim()) {
            alert('<?= \Altum\Language::get('products', 'error_message.description_required') ?>');
            descriptionInput.focus();
            return;
        }

        if (!priceInput.value || priceInput.value < 1000) {
            alert('<?= \Altum\Language::get('products', 'error_message.price_required') ?>');
            priceInput.focus();
            return;
        }

        if (!digitalLinkInput.value.trim()) {
            alert('<?= \Altum\Language::get('products', 'error_message.digital_link_required') ?>');
            digitalLinkInput.focus();
            return;
        }

        // Validate URL
        try {
            new URL(digitalLinkInput.value);
        } catch (_) {
            alert('<?= \Altum\Language::get('products', 'error_message.digital_link_invalid') ?>');
            digitalLinkInput.focus();
            return;
        }

        // Submit form
        form.submit();
    });

    // Initialize preview
    updatePreview();
});
</script>