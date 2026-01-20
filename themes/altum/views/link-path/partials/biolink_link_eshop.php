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
    
    <!-- PRODUCT DETAIL MODAL (Responsive & Robust) -->
    <!-- Default Hidden, Toggled via JS -->
    <div id="productDetailModal">
        <!-- Overlay -->
        <div class="modal-overlay-bg" onclick="document.getElementById('productDetailModal').classList.remove('show'); document.body.style.overflow='auto';"></div>
        
        <!-- Modal Content Box -->
        <div class="modal-container">
            <!-- Close Button -->
            <span class="modal-close-btn" onclick="document.getElementById('productDetailModal').classList.remove('show'); document.body.style.overflow='auto';">&times;</span>
            
            <!-- Scrollable Area -->
            <div class="modal-scroll-area">
                
                <!-- Image Section -->
                <div class="modal-section-image">
                    <div class="img-container">
                        <img id="mainProductImage" src="" class="main-img" />
                    </div>
                    <div id="thumbnailGallery" class="thumb-gallery"></div>
                </div>
                
                <!-- Info Section -->
                <div class="modal-section-info">
                    <h2 id="modalProductTitle" class="prod-title"></h2>
                    
                    <div class="price-box">
                        <span id="modalProductPrice" class="prod-price"></span>
                        <span id="modalProductPriceStrike" class="prod-strike d-none"></span>
                    </div>
                    
                    <div id="modalProductVariants" class="variants-box"></div>
                    
                    <div id="modalProductDesc" class="prod-desc"></div>
                    
                    <div class="action-box">
                        <a id="modalAddToCart" class="btn-cart" href="javascript:;"><?= $button_text ?></a>
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
        // Wait for DOM
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('productDetailModal');
            if(!modal) {
                console.error('Modal container not found');
                return;
            }

            const detailBtns = document.querySelectorAll('.btn-detail');
            console.log('Found detail buttons:', detailBtns.length);
            
            detailBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    try {
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
                        
                        // Populate
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
                                thumb.className = 'thumb' + (idx === 0 ? ' active' : '');
                                thumb.onclick = function() {
                                    mainImg.src = imgUrl;
                                    Array.from(thumbnailGallery.children).forEach(c => c.classList.remove('active'));
                                    this.classList.add('active');
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
                                groupDiv.className = 'v-group';
                                const groupTitle = document.createElement('div');
                                groupTitle.className = 'v-title';
                                groupTitle.textContent = variantGroup.title || '';
                                groupDiv.appendChild(groupTitle);
                                
                                const optionsDiv = document.createElement('div');
                                optionsDiv.className = 'v-options';
                                
                                if(variantGroup.variant && variantGroup.variant.length > 0) {
                                    variantGroup.variant.forEach((opt, oIndex) => {
                                        const btn = document.createElement('button');
                                        btn.className = 'v-btn' + (oIndex === 0 ? ' active' : '');
                                        btn.textContent = opt.name || '';
                                        
                                        btn.onclick = function() {
                                            this.parentElement.querySelectorAll('.v-btn').forEach(b => b.classList.remove('active'));
                                            this.classList.add('active');
                                            
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
                                    modal.classList.remove('show');
                                    document.body.style.overflow = 'auto';
                                }
                            };
                        }
                        
                        // SHOW MODAL
                        modal.classList.add('show');
                        document.body.style.overflow = 'hidden';
                        
                    } catch(err) {
                        console.error('Error opening modal:', err);
                    }
                });
            });
        });
    })();
    </script>
    
    <style>
        /* ========================
           ROBUST MODAL CSS
           ======================== */
        
        /* 1. Modal Container (Overlay) */
        #productDetailModal {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999999; /* Super high Z-Index */
            width: 100%;
            height: 100%;
            align-items: center; /* Center vertically default */
            justify-content: center; /* Center horizontally default */
            background-color: transparent;
        }
        
        /* Visible State */
        #productDetailModal.show {
            display: flex;
        }

        /* 2. Background Overlay */
        .modal-overlay-bg {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0,0,0,0.75);
            cursor: pointer;
        }

        /* 3. Modal Box */
        .modal-container {
            position: relative;
            background: #fff;
            width: 95%; /* Default Mobile Width */
            max-width: 600px; /* Desktop Max Width */
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.4);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            /* Height Constraints: Max 80% of screen height */
            max-height: 80vh; 
        }

        /* Close Button */
        .modal-close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #fff;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            line-height: 1;
            color: #333;
            cursor: pointer;
            z-index: 20;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: 0.2s;
        }
        .modal-close-btn:hover { background: #f0f0f0; }

        /* 4. Scrollable Area */
        .modal-scroll-area {
            overflow-y: auto;
            max-height: 80vh;
            display: flex;
            flex-direction: row; /* Side by side Desktop */
        }
        
        /* Custom Scrollbar */
        .modal-scroll-area::-webkit-scrollbar { width: 6px; }
        .modal-scroll-area::-webkit-scrollbar-track { background: #f1f1f1; }
        .modal-scroll-area::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }

        /* 5. Image Section */
        .modal-section-image {
            flex: 1;
            min-width: 0;
            background: #f9f9f9;
            padding: 15px;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #eee;
        }

        .img-container {
            background: #fff;
            border-radius: 8px;
            width: 100%;
            min-height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            overflow: hidden;
        }

        .main-img {
            max-width: 100%;
            max-height: 40vh;
            object-fit: contain;
        }

        .thumb-gallery { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 5px; }
        .thumb-gallery::-webkit-scrollbar { height: 4px; }
        .thumb-gallery::-webkit-scrollbar-thumb { background: #ccc; border-radius: 2px; }
        
        .thumb {
            width: 60px; height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid transparent;
            cursor: pointer;
            flex-shrink: 0;
            background: #fff;
        }
        .thumb.active { border-color: #007bff; }

        /* 6. Info Section */
        .modal-section-info {
            flex: 1;
            min-width: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .prod-title { font-size: 22px; font-weight: 700; color: #333; margin: 0 0 10px; word-wrap: break-word; }
        
        .price-box { margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .prod-price { font-size: 24px; font-weight: 700; color: #007bff; display: block; }
        .prod-strike { font-size: 16px; color: #999; text-decoration: line-through; }

        .variants-box { margin-bottom: 15px; }
        .v-group { margin-bottom: 10px; }
        .v-title { font-size: 14px; font-weight: 600; color: #555; margin-bottom: 8px; }
        .v-options { display: flex; flex-wrap: wrap; gap: 8px; }
        .v-btn {
            padding: 6px 14px;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
        }
        .v-btn.active { background: #007bff; color: #fff; border-color: #007bff; }

        .prod-desc {
            font-size: 14px; line-height: 1.6; color: #666;
            white-space: pre-wrap; word-wrap: break-word;
            flex: 1;
            overflow-y: auto;
            max-height: 200px; /* Limit desc scroll */
            margin-bottom: 20px;
        }

        .action-box { margin-top: auto; }
        .btn-cart {
            display: block; width: 100%;
            background: #007bff; color: #fff;
            text-align: center;
            padding: 14px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            font-size: 16px;
        }

        /* =========================================
           MOBILE RESPONSIVE (< 768px)
           ========================================= */
        @media (max-width: 767px) {
            #productDetailModal {
                align-items: flex-end; /* Stick to bottom */
                padding-bottom: 0;
            }

            .modal-container {
                width: 100%;
                max-width: 100%;
                border-radius: 16px 16px 0 0; /* Rounded only top */
                margin-bottom: 0;
                /* Slightly taller on mobile */
                max-height: 85vh; 
            }

            .modal-scroll-area {
                flex-direction: column; /* Stack vertically */
                height: 100%;
                overflow-y: auto;
            }

            .modal-section-image {
                border-right: 0;
                border-bottom: 1px solid #eee;
                padding: 10px;
            }

            .img-container { min-height: 180px; }
            .main-img { max-height: 30vh; }

            .modal-section-info { padding: 15px; }
            
            /* Force 14px fonts for Mobile */
            .prod-title { font-size: 14px !important; margin-bottom: 8px; }
            .prod-price { font-size: 18px !important; }
            .prod-strike { font-size: 14px !important; }
            .v-title { font-size: 14px !important; }
            .v-btn { font-size: 13px !important; padding: 6px 12px; }
            .prod-desc { font-size: 13px !important; max-height: 150px; }
            .btn-cart { font-size: 14px !important; padding: 12px; }
        }

        /* Helper Classes */
        .d-none { display: none; }
        .bootstrap-select{flex: 1 1 auto !important;} 
        .bootstrap-select .btn{line-height:2.25 !important;border-top-left-radius:0;border-bottom-left-radius:0;}
    </style>
</div>