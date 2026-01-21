<?php defined('ALTUMCODE') || die() ?>
<?php 
 $settings_data = is_object($data->link->settings) ? $data->link->settings : json_encode($data->link->settings); 
if(is_string($settings_data)) $settings_data = json_decode($settings_data);

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
                            <a class="btn btn-sm btn-success mt-2" data-index="<?= $num_prd ?>" data-cart="add" data-link-id="<?= $data->link->link_id.':'.$iy.":".$iz ?>" href="javascript:;"><?= $button_text ?></a>
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
                            <a class="btn btn-sm btn-success mt-2" data-index="<?= $num_prd ?>" data-cart="add" data-link-id="<?= $data->link->link_id.':'.$iy.":".$iz ?>" href="javascript:;"><?= $button_text ?></a>
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
    
    <!-- Product Detail Modal: Bootstrap Modal (Same as #addtocart) -->
    <div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="modalProductTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="row no-gutters">
                        <!-- Image Section -->
                        <div class="col-12 col-md-5 bg-light">
                            <div class="p-3">
                                <div class="text-center mb-3" style="min-height: 200px;">
                                    <img id="mainProductImage" src="" alt="Product" class="img-fluid rounded" style="max-height: 280px; object-fit: contain;" />
                                </div>
                                <div id="thumbnailGallery" class="d-flex flex-wrap justify-content-center gap-2"></div>
                            </div>
                        </div>
                        
                        <!-- Info Section -->
                        <div class="col-12 col-md-7">
                            <div class="p-3">
                                <!-- Price -->
                                <div class="mb-3 pb-2 border-bottom">
                                    <span id="modalProductPrice" class="h4 font-weight-bold text-primary d-block mb-1"></span>
                                    <span id="modalProductPriceStrike" class="text-muted text-decoration-line-through d-none small"></span>
                                </div>
                                
                                <!-- Variants -->
                                <div id="modalProductVariants" class="mb-3"></div>
                                
                                <!-- Description -->
                                <div id="modalProductDesc" class="text-secondary small mb-3" style="white-space: pre-wrap; line-height: 1.6; max-height: 150px; overflow-y: auto;"></div>
                                
                                <!-- Add to Cart Button -->
                                <a id="modalAddToCart" class="btn btn-primary btn-block font-weight-bold py-2" href="javascript:;"><?= $button_text ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    window.eshop = window.eshop || {};
    window.eshop[<?= $data->link->link_id ?>] = <?= json_encode($products_data) ?>;
    </script>

    <script>
    (function() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initProductDetailModal);
        } else {
            initProductDetailModal();
        }

        function initProductDetailModal() {
            // PENTING: Pindahkan modal ke body untuk fix z-index stacking context
            const modal = document.getElementById('productDetailModal');
            if(modal && modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            
            const detailBtns = document.querySelectorAll('.btn-detail');
            
            detailBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Data extraction
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
                    
                    // Populate Content
                    document.getElementById('modalProductTitle').textContent = title || '';
                    document.getElementById('modalProductDesc').textContent = fullDesc || desc || '';
                    document.getElementById('modalProductPrice').textContent = price || '';
                    
                    const strikeEl = document.getElementById('modalProductPriceStrike');
                    if(priceStrike) {
                        strikeEl.textContent = priceStrike;
                        strikeEl.classList.remove('d-none');
                    } else {
                        strikeEl.classList.add('d-none');
                    }
                    
                    // Images
                    const images = [image, image1, image2, image3, image4].filter(img => img && img.trim() !== '');
                    const mainImg = document.getElementById('mainProductImage');
                    const thumbnailGallery = document.getElementById('thumbnailGallery');
                    thumbnailGallery.innerHTML = '';
                    
                    if(images.length > 0) {
                        mainImg.src = images[0];
                        images.forEach((imgUrl, idx) => {
                            const thumb = document.createElement('img');
                            thumb.src = imgUrl;
                            thumb.className = 'rounded border cursor-pointer' + (idx === 0 ? ' border-primary' : '');
                            thumb.style.cssText = 'width:50px;height:50px;object-fit:cover;cursor:pointer;';
                            
                            thumb.onclick = function() {
                                mainImg.src = imgUrl;
                                Array.from(thumbnailGallery.children).forEach(c => c.classList.remove('border-primary'));
                                this.classList.add('border-primary');
                            };
                            thumbnailGallery.appendChild(thumb);
                        });
                    }
                    
                    // Variants
                    const variantsData = this.getAttribute('data-product-variants');
                    let variants = [];
                    try { variants = JSON.parse(variantsData || '[]'); } catch(e) {}
                    
                    const variantsContainer = document.getElementById('modalProductVariants');
                    variantsContainer.innerHTML = '';
                    
                    if(variants && variants.length > 0) {
                        variants.forEach((variantGroup, gIndex) => {
                            const groupDiv = document.createElement('div');
                            groupDiv.className = 'mb-2';
                            
                            const groupTitle = document.createElement('div');
                            groupTitle.className = 'font-weight-bold small mb-1 text-dark';
                            groupTitle.textContent = variantGroup.title || '';
                            groupDiv.appendChild(groupTitle);
                            
                            const optionsDiv = document.createElement('div');
                            optionsDiv.className = 'd-flex flex-wrap';
                            optionsDiv.style.gap = '5px';
                            
                            if(variantGroup.variant && variantGroup.variant.length > 0) {
                                variantGroup.variant.forEach((opt, oIndex) => {
                                    const btn = document.createElement('button');
                                    btn.type = 'button';
                                    btn.className = 'btn btn-sm' + (oIndex === 0 ? ' btn-primary' : ' btn-outline-secondary');
                                    btn.textContent = opt.name || '';
                                    
                                    btn.onclick = function() {
                                        this.parentElement.querySelectorAll('.btn').forEach(b => {
                                            b.classList.remove('btn-primary');
                                            b.classList.add('btn-outline-secondary');
                                        });
                                        this.classList.remove('btn-outline-secondary');
                                        this.classList.add('btn-primary');
                                        
                                        if(opt.price) {
                                            const currency = (price || '').split(/[0-9]/)[0];
                                            document.getElementById('modalProductPrice').textContent = currency + new Intl.NumberFormat('id-ID').format(opt.price);
                                        }
                                        if(opt.image_url) {
                                            mainImg.src = opt.image_url;
                                        }
                                    };
                                    optionsDiv.appendChild(btn);
                                });
                            }
                            groupDiv.appendChild(optionsDiv);
                            variantsContainer.appendChild(groupDiv);
                        });
                    }
                    
                    // Cart Logic
                    const modalCartBtn = document.getElementById('modalAddToCart');
                    if(modalCartBtn) {
                        modalCartBtn.setAttribute('data-index', index);
                        modalCartBtn.setAttribute('data-cart', 'add');
                        modalCartBtn.setAttribute('data-link-id', linkId);
                        
                        modalCartBtn.onclick = function(e) {
                            e.preventDefault();
                            const originalBtn = document.querySelector(`a[data-cart="add"][data-link-id="${linkId}"]`);
                            if(originalBtn) {
                                originalBtn.click();
                                // Close modal using Bootstrap
                                $('#productDetailModal').modal('hide');
                            }
                        };
                    }
                    
                    // Show modal using Bootstrap
                    $('#productDetailModal').modal('show');
                });
            });
        }
    })();
    </script>
    
    <style>
        /* Bootstrap Select Fix */
        .bootstrap-select{flex: 1 1 auto !important;} 
        .bootstrap-select .btn{line-height:2.25 !important;border-top-left-radius:0;border-bottom-left-radius:0;}
        
        /* Harga Coret (Strikethrough) */
        .text-decoration-line-through,
        .price-strike {
            text-decoration: line-through !important;
        }
        
        /* Product Detail Modal - HIGH Z-INDEX */
        #productDetailModal {
            z-index: 99999999 !important;
        }
        #productDetailModal + .modal-backdrop,
        .modal-backdrop.show {
            z-index: 99999998 !important;
        }
        
        /* Product Detail Modal Styles */
        #productDetailModal .modal-content {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }
        
        #productDetailModal .modal-header {
            border-bottom: 1px solid #eee;
        }
        
        #productDetailModal .modal-title {
            font-size: 16px;
            font-weight: 700;
        }
        
        /* Thumbnail Gallery */
        #thumbnailGallery img {
            transition: all 0.2s;
        }
        #thumbnailGallery img:hover {
            opacity: 0.8;
        }
        #thumbnailGallery img.border-primary {
            border-width: 2px !important;
        }
        
        /* Scrollbar for description */
        #modalProductDesc::-webkit-scrollbar { width: 4px; }
        #modalProductDesc::-webkit-scrollbar-track { background: #f9f9f9; }
        #modalProductDesc::-webkit-scrollbar-thumb { background: #bbb; border-radius: 2px; }
        
        /* Mobile Responsive */
        @media (max-width: 767.98px) {
            #productDetailModal .modal-dialog {
                margin: 10px;
                max-width: calc(100% - 20px);
            }
            
            #productDetailModal .modal-title {
                font-size: 14px;
            }
            
            #productDetailModal #modalProductPrice {
                font-size: 18px !important;
            }
            
            #productDetailModal #modalProductDesc {
                font-size: 13px;
                max-height: 120px;
            }
            
            #productDetailModal #mainProductImage {
                max-height: 200px !important;
            }
            
            #thumbnailGallery img {
                width: 45px !important;
                height: 45px !important;
            }
            
            #modalProductVariants .btn {
                font-size: 12px;
                padding: 6px 10px;
            }
            
            #modalAddToCart {
                font-size: 14px;
            }
        }
    </style>
</div>