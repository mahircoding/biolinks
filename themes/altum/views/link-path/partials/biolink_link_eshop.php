<?php defined('ALTUMCODE') || die() ?>
<?php 
// Get settings
 $settings_data = is_object($data->link->settings) ? $data->link->settings : json_decode(json_encode($data->link->settings));
 $products_data = isset($settings_data->data) ? $settings_data->data : $settings_data;
 $button_text = isset($settings_data->button_text) ? $settings_data->button_text : 'Add to Cart';
?>

<div class="my-3 category-product">
    <!-- Category Filter -->
    <div class="form-group mb-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text text-info">Category</span>
            </div>
            <select class="selectpicker form-control">
                <option value="all">All</option>
                <?php foreach($products_data as $iy => $it) {?>
                <option value="<?= $iy ?>"><?= $it->category ?></option>
                <?php }?>
            </select>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="pricing-table">
        <div class="row justify-content-center">
            <?php $num_prd=0; foreach($products_data as $iy => $it) {?>
            <?php foreach($it->products as $iz => $pr) {
            if(isset($pr->show)&&$pr->show) {
            ?>
            <div data-filter-product="<?= $iy ?>" class="col-6 col-md-4 col-lg-3 mb-4 cat-product">
                <div class="card h-100 border-0 shadow-sm">
                    <img class="card-img-top" src="<?= str_replace('http://','https://',$pr->image_url) ?>" alt="<?= $pr->title ?>" style="object-fit: cover; height: 200px;" />
                    <div class="card-body d-flex flex-column p-3">
                        <h3 class="h6 card-title font-weight-bold mb-2 text-truncate"><?= $pr->title ?></h3>
                        <div class="card-text text-muted small mb-3" style="flex:1;">
                            <?= isset($pr->description) ? mb_substr($pr->description, 0, 60) . (mb_strlen($pr->description) > 60 ? '...' : '') : '' ?>
                        </div>
                        <div class="mb-2">
                            <span class="h5 mb-0 d-block text-primary"><?= $data->user->currency.number_format($pr->price,0,"",",") ?></span>
                            <?php if(!empty($pr->price_strike)) {?>
                            <small class="text-muted text-decoration-line-through"><?= $data->user->currency.number_format($pr->price_strike,0,"",",") ?></small>
                            <?php }?>
                        </div>
                        <div class="product-btn mt-auto d-flex flex-column gap-2">
                            <a class="btn btn-sm btn-primary btn-detail" 
                               data-product-title="<?= htmlspecialchars($pr->title) ?>" 
                               data-product-desc="<?= htmlspecialchars($pr->description ?? '') ?>" 
                               data-product-full-desc="<?= htmlspecialchars($pr->full_description ?? $pr->description ?? '') ?>" 
                               data-product-price="<?= $data->user->currency.number_format($pr->price,0,"",",") ?>" 
                               data-product-price-strike="<?= !empty($pr->price_strike) ? $data->user->currency.number_format($pr->price_strike,0,"",",") : '' ?>" 
                               data-product-image="<?= str_replace('http://','https://',$pr->image_url) ?>"
                               data-product-image1="<?= !empty($pr->image_1) ? str_replace('http://','https://',$pr->image_1) : '' ?>"
                               data-product-image2="<?= !empty($pr->image_2) ? str_replace('http://','https://',$pr->image_2) : '' ?>"
                               data-product-image3="<?= !empty($pr->image_3) ? str_replace('http://','https://',$pr->image_3) : '' ?>"
                               data-product-image4="<?= !empty($pr->image_4) ? str_replace('http://','https://',$pr->image_4) : '' ?>"
                               data-product-variants='<?= isset($pr->variants) ? json_encode($pr->variants) : '[]' ?>'
                               data-product-index="<?= $num_prd ?>"
                               data-product-link-id="<?= $data->link->link_id.':'.$iy.":".$iz ?>"
                               href="javascript:;">Detail</a>
                            <a class="btn btn-sm btn-outline-secondary none" data-index="<?= $num_prd ?>" data-cart="add" data-link-id="<?= $data->link->link_id.':'.$iy.":".$iz ?>" href="javascript:;"><?= $button_text ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } elseif(!isset($pr->show)) {?>
            <div data-filter-product="<?= $iy ?>" class="col-6 col-md-4 col-lg-3 mb-4 cat-product">
                <div class="card h-100 border-0 shadow-sm">
                    <img class="card-img-top" src="<?= str_replace('http://','https://',$pr->image_url) ?>" alt="<?= $pr->title ?>" style="object-fit: cover; height: 200px;" />
                    <div class="card-body d-flex flex-column p-3">
                        <h3 class="h6 card-title font-weight-bold mb-2 text-truncate"><?= $pr->title ?></h3>
                        <div class="card-text text-muted small mb-3" style="flex:1;">
                            <?= isset($pr->description) ? mb_substr($pr->description, 0, 60) . (mb_strlen($pr->description) > 60 ? '...' : '') : '' ?>
                        </div>
                        <div class="mb-2">
                            <span class="h5 mb-0 d-block text-primary"><?= $data->user->currency.number_format($pr->price,0,"",",") ?></span>
                            <?php if(!empty($pr->price_strike)) {?>
                            <small class="text-muted text-decoration-line-through"><?= $data->user->currency.number_format($pr->price_strike,0,"",",") ?></small>
                            <?php }?>
                        </div>
                        <div class="product-btn mt-auto d-flex flex-column gap-2">
                            <a class="btn btn-sm btn-primary btn-detail" 
                               data-product-title="<?= htmlspecialchars($pr->title) ?>" 
                               data-product-desc="<?= htmlspecialchars($pr->description ?? '') ?>" 
                               data-product-full-desc="<?= htmlspecialchars($pr->full_description ?? $pr->description ?? '') ?>" 
                               data-product-price="<?= $data->user->currency.number_format($pr->price,0,"",",") ?>" 
                               data-product-price-strike="<?= !empty($pr->price_strike) ? $data->user->currency.number_format($pr->price_strike,0,"",",") : '' ?>" 
                               data-product-image="<?= str_replace('http://','https://',$pr->image_url) ?>"
                               data-product-image1="<?= !empty($pr->image_1) ? str_replace('http://','https://',$pr->image_1) : '' ?>"
                               data-product-image2="<?= !empty($pr->image_2) ? str_replace('http://','https://',$pr->image_2) : '' ?>"
                               data-product-image3="<?= !empty($pr->image_3) ? str_replace('http://','https://',$pr->image_3) : '' ?>"
                               data-product-image4="<?= !empty($pr->image_4) ? str_replace('http://','https://',$pr->image_4) : '' ?>"
                               data-product-variants='<?= isset($pr->variants) ? json_encode($pr->variants) : '[]' ?>'
                               data-product-index="<?= $num_prd ?>"
                               data-product-link-id="<?= $data->link->link_id.':'.$iy.":".$iz ?>"
                               href="javascript:;">Detail</a>
                            <a class="btn btn-sm btn-outline-secondary none" data-index="<?= $num_prd ?>" data-cart="add" data-link-id="<?= $data->link->link_id.':'.$iy.":".$iz ?>" href="javascript:;"><?= $button_text ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php }
            if(empty($pr->link)) $num_prd++;
            }}
            ?>
        </div>
    </div>
    
    <!-- Product Detail Modal (Bootstrap Structure) -->
    <div id="productDetailModal" class="position-fixed top-0 left-0 w-100 h-100 d-flex justify-content-center align-items-center" style="z-index: 9999; background-color: rgba(0,0,0,0.7); display: none;">
        <!-- Modal Content Container -->
        <div class="bg-white w-100 mx-3 rounded shadow overflow-hidden" style="max-width: 900px; max-height: 90vh;">
            
            <!-- Close Button -->
            <span class="product-modal-close position-absolute text-secondary rounded-circle bg-white p-2 m-2 shadow-sm" style="top:0; right:0; z-index: 10; cursor:pointer; font-size: 24px; line-height: 1; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">&times;</span>
            
            <!-- Modal Body: Use Flex Row for Desktop, Flex Column for Mobile -->
            <div class="d-flex flex-column flex-md-row overflow-auto" style="height: 100%; max-height: 90vh;">
                
                <!-- Left Column: Images (Col-12 Mobile, Col-6 Desktop) -->
                <div class="col-12 col-md-6 p-3 d-flex flex-column align-items-center border-bottom border-md-bottom-0 border-md-right bg-light">
                    <div class="w-100 mb-3 text-center">
                        <img id="mainProductImage" src="" alt="Product Image" class="img-fluid rounded bg-white" style="max-height: 400px; object-fit: contain;" />
                    </div>
                    <div id="thumbnailGallery" class="d-flex flex-wrap justify-content-center gap-2 w-100">
                        <!-- Thumbnails JS -->
                    </div>
                </div>
                
                <!-- Right Column: Info (Col-12 Mobile, Col-6 Desktop) -->
                <div class="col-12 col-md-6 p-4 d-flex flex-column">
                    <h2 id="modalProductTitle" class="h4 font-weight-bold mb-3"></h2>
                    
                    <div class="mb-3">
                        <span id="modalProductPrice" class="h3 font-weight-bold text-primary d-block"></span>
                        <span id="modalProductPriceStrike" class="text-muted text-decoration-line-through d-none"></span>
                    </div>
                    
                    <div id="modalProductVariants" class="mb-3">
                        <!-- Variants JS -->
                    </div>
                    
                    <div id="modalProductDesc" class="text-secondary small mb-4" style="white-space: pre-wrap; overflow-y: auto; flex: 1;"></div>
                    
                    <div class="mt-auto">
                        <a id="modalAddToCart" class="btn btn-primary btn-lg w-100 font-weight-bold" href="javascript:;"><?= $button_text ?></a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <script>eshop[<?= $data->link->link_id ?>] = <?= json_encode($products_data) ?></script>
    <script>
    // Product Detail Modal Handler
    (function() {
        const modal = document.getElementById('productDetailModal');
        const closeBtn = document.querySelector('.product-modal-close');
        const detailBtns = document.querySelectorAll('.btn-detail');
        
        detailBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Extract data
                const title = this.getAttribute('data-product-title');
                const desc = this.getAttribute('data-product-desc');
                const fullDesc = this.getAttribute('data-product-full-desc');
                const price = this.getAttribute('data-product-price');
                const priceStrike = this.getAttribute('data-product-price-strike');
                const image = this.getAttribute('data-product-image');
                const image1 = this.getAttribute('data-product-image1');
                const image2 = this.getAttribute('data-product-image2');
                const image3 = this.getAttribute('data-product-image3');
                const image4 = this.getAttribute('data-product-image4');
                const index = this.getAttribute('data-product-index');
                const linkId = this.getAttribute('data-product-link-id');
                
                // Populate Text
                document.getElementById('modalProductTitle').textContent = title;
                document.getElementById('modalProductDesc').textContent = fullDesc || desc || '';
                document.getElementById('modalProductPrice').textContent = price;
                
                // Handle Price Strike
                const strikeEl = document.getElementById('modalProductPriceStrike');
                if(priceStrike) {
                    strikeEl.textContent = priceStrike;
                    strikeEl.classList.remove('d-none');
                } else {
                    strikeEl.classList.add('d-none');
                }
                
                // Populate Images
                const images = [image, image1, image2, image3, image4].filter(img => img && img.trim() !== '');
                if(images.length > 0) {
                    document.getElementById('mainProductImage').src = images[0];
                    const thumbnailGallery = document.getElementById('thumbnailGallery');
                    thumbnailGallery.innerHTML = '';
                    
                    images.forEach((imgUrl, idx) => {
                        const thumb = document.createElement('img');
                        thumb.src = imgUrl;
                        thumb.className = 'border rounded cursor-pointer' + (idx === 0 ? ' border-primary' : '');
                        thumb.style.width = '60px';
                        thumb.style.height = '60px';
                        thumb.style.objectFit = 'cover';
                        thumb.style.cursor = 'pointer';
                        thumb.onclick = function() {
                            document.getElementById('mainProductImage').src = imgUrl;
                            // Reset borders
                            Array.from(thumbnailGallery.children).forEach(c => c.classList.remove('border-primary'));
                            this.classList.add('border-primary');
                        };
                        thumbnailGallery.appendChild(thumb);
                    });
                }
                
                // Populate Variants
                const variantsData = this.getAttribute('data-product-variants');
                let variants = [];
                try { variants = JSON.parse(variantsData || '[]'); } catch(e) {}
                
                const variantsContainer = document.getElementById('modalProductVariants');
                variantsContainer.innerHTML = '';
                
                if(variants && variants.length > 0) {
                    variants.forEach((variantGroup, gIndex) => {
                        const groupDiv = document.createElement('div');
                        groupDiv.className = 'mb-3';
                        
                        const groupTitle = document.createElement('div');
                        groupTitle.className = 'font-weight-bold small mb-2';
                        groupTitle.textContent = variantGroup.title;
                        groupDiv.appendChild(groupTitle);
                        
                        const optionsDiv = document.createElement('div');
                        optionsDiv.className = 'd-flex flex-wrap gap-2';
                        
                        if(variantGroup.variant && variantGroup.variant.length > 0) {
                            variantGroup.variant.forEach((opt, oIndex) => {
                                const btn = document.createElement('button');
                                btn.type = 'button';
                                btn.className = 'btn btn-sm btn-outline-secondary' + (oIndex === 0 ? ' active' : '');
                                btn.textContent = opt.name;
                                btn.onclick = function() {
                                    // Active state logic
                                    this.parentElement.querySelectorAll('.btn').forEach(b => {
                                        b.classList.remove('active');
                                        b.classList.add('btn-outline-secondary');
                                    });
                                    this.classList.add('active');
                                    this.classList.remove('btn-outline-secondary');
                                    
                                    if(opt.price) {
                                        const currency = price.split(/[0-9]/)[0];
                                        document.getElementById('modalProductPrice').textContent = currency + new Intl.NumberFormat('id-ID').format(opt.price);
                                    }
                                    if(opt.image_url) {
                                        document.getElementById('mainProductImage').src = opt.image_url;
                                    }
                                };
                                optionsDiv.appendChild(btn);
                            });
                        }
                        groupDiv.appendChild(optionsDiv);
                        variantsContainer.appendChild(groupDiv);
                    });
                }
                
                // Cart Button Logic
                const modalCartBtn = document.getElementById('modalAddToCart');
                modalCartBtn.setAttribute('data-index', index);
                modalCartBtn.setAttribute('data-cart', 'add');
                modalCartBtn.setAttribute('data-link-id', linkId);
                
                // Clone click handler to original button logic
                modalCartBtn.onclick = function() {
                    const originalBtn = document.querySelector(`a[data-cart="add"][data-link-id="${linkId}"]`);
                    if(originalBtn) {
                        originalBtn.click();
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                };
                
                // Show Modal
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        });
        
        // Close Logic
        const closeModal = () => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        };
        
        closeBtn.addEventListener('click', closeModal);
        window.addEventListener('click', (e) => { if(e.target == modal) closeModal(); });
    })();
    </script>
    
    <!-- Minimal Custom CSS Overrides for Bootstrap -->
    <style>
        /* Helper classes if not available in your BS version */
        .border-md-right { border-right: 1px solid #dee2e6 !important; }
        .border-md-bottom-0 { border-bottom: 0 !important; }
        
        @media (max-width: 767.98px) {
            .border-md-right { border-right: 0 !important; }
        }
        
        /* Force Bootstrap Select to fit nicely */
        .bootstrap-select{flex: 1 1 auto !important;} 
        .bootstrap-select .btn{line-height:2.25 !important;border-top-left-radius:0;border-bottom-left-radius:0;}
        
        /* Scrollbar styling for modal content */
        .overflow-auto::-webkit-scrollbar { width: 8px; }
        .overflow-auto::-webkit-scrollbar-track { background: #f1f1f1; }
        .overflow-auto::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        .overflow-auto::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
</div>