<?php defined('ALTUMCODE') || die() ?>
<?php 
// Get settings - handle both old and new format
 $settings_data = is_object($data->link->settings) ? $data->link->settings : json_decode(json_encode($data->link->settings));
 $products_data = isset($settings_data->data) ? $settings_data->data : $settings_data;
 $button_text = isset($settings_data->button_text) ? $settings_data->button_text : 'Add to Cart';
?>

<div class="my-3 category-product">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text text-info">Category</span>
            </div>
            <select class="selectpicker">
            <option value="all">All</option>
            <?php foreach($products_data as $iy => $it) {?>
            <option value="<?= $iy ?>"><?= $it->category ?></option>
            <?php }?>
            </select>
        </div>
    </div>
    <div class="pricing-table">
        <div class="row justify-content-center align-items-stretch">
        <?php $num_prd=0; foreach($products_data as $iy => $it) {?>
        <?php foreach($it->products as $iz => $pr) {
        if(isset($pr->show)&&$pr->show) {
        ?>
        <div data-filter-product="<?= $iy ?>" class="col-md-4 col-lg-4 mb-4 cat-product">
            <div class="item">
                <img class="image" src="<?= str_replace('http://','https://',$pr->image_url) ?>" />
                <div class="product-desc">
                    <h3 class="title"><?= $pr->title ?></h3>
                    <div class="desc"><?= isset($pr->description) ? mb_substr($pr->description, 0, 80) . (mb_strlen($pr->description) > 80 ? '...' : '') : '' ?></div>
                    <div class="price"><?= $data->user->currency.number_format($pr->price,0,"",",") ?></div>
                    <?php if(!empty($pr->price_strike)) {?>
                    <div class="price-strike"><?= $data->user->currency.number_format($pr->price_strike,0,"",",") ?></div>
                    <?php }?>
                </div>
                <div class="product-btn">
                    <a class="btn-detail" 
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
                    <a class="none" data-index="<?= $num_prd ?>" data-cart="add" data-link-id="<?= $data->link->link_id.':'.$iy.":".$iz ?>" href="javascript:;"><?= $button_text ?></a>
                </div>
            </div>
        </div>
        <?php } elseif(!isset($pr->show)) {?>
        <div data-filter-product="<?= $iy ?>" class="col-md-4 col-lg-4 mb-4 cat-product">
            <div class="item">
                <img class="image" src="<?= str_replace('http://','https://',$pr->image_url) ?>" />
                <div class="product-desc">
                    <h3 class="title"><?= $pr->title ?></h3>
                    <div class="desc"><?= isset($pr->description) ? mb_substr($pr->description, 0, 80) . (mb_strlen($pr->description) > 80 ? '...' : '') : '' ?></div>
                    <div class="price"><?= $data->user->currency.number_format($pr->price,0,"",",") ?></div>
                    <?php if(!empty($pr->price_strike)) {?>
                    <div class="price-strike"><?= $data->user->currency.number_format($pr->price_strike,0,"",",") ?></div>
                    <?php }?>
                </div>
                <div class="product-btn">
                    <a class="btn-detail" 
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
                    <a class="none" data-index="<?= $num_prd ?>" data-cart="add" data-link-id="<?= $data->link->link_id.':'.$iy.":".$iz ?>" href="javascript:;"><?= $button_text ?></a>
                </div>
            </div>
        </div>
        <?php }
        if(empty($pr->link)) $num_prd++;
        }}
        ?>
        </div>
    </div>
    
    <!-- Product Detail Modal -->
    <div id="productDetailModal" class="product-modal">
        <div class="product-modal-content">
            <span class="product-modal-close">&times;</span>
            <div class="product-modal-body">
                <div class="product-modal-images">
                    <div class="main-image-container">
                        <img id="mainProductImage" src="" alt="Product Image" class="main-product-image">
                    </div>
                    <div id="thumbnailGallery" class="thumbnail-gallery">
                        <!-- Thumbnails will be populated by JavaScript -->
                    </div>
                </div>
                <div class="product-modal-info">
                    <h2 id="modalProductTitle"></h2>
                    <div class="product-modal-price">
                        <span id="modalProductPrice" class="modal-price"></span>
                        <span id="modalProductPriceStrike" class="modal-price-strike"></span>
                    </div>
                    <div id="modalProductVariants" class="product-modal-variants">
                        <!-- Variants will be populated by JavaScript -->
                    </div>
                    <div id="modalProductDesc" class="product-modal-description"></div>
                    <div class="product-modal-actions">
                        <a id="modalAddToCart" class="modal-btn-cart" href="javascript:;"><?= $button_text ?></a>
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
        
        // Open modal and populate with product data
        detailBtns.forEach(btn => {
            btn.addEventListener('click', function() {
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
                
                document.getElementById('modalProductTitle').textContent = title;
                document.getElementById('modalProductDesc').textContent = fullDesc || desc || 'No description available';
                document.getElementById('modalProductPrice').textContent = price;
                
                const images = [image, image1, image2, image3, image4].filter(img => img && img.trim() !== '');
                
                if(images.length > 0) {
                    document.getElementById('mainProductImage').src = images[0];
                    const thumbnailGallery = document.getElementById('thumbnailGallery');
                    thumbnailGallery.innerHTML = '';
                    
                    images.forEach((imgUrl, idx) => {
                        const thumbElement = document.createElement('img');
                        thumbElement.src = imgUrl;
                        thumbElement.alt = title;
                        thumbElement.className = 'thumbnail-image' + (idx === 0 ? ' active' : '');
                        thumbElement.onclick = function() {
                            document.getElementById('mainProductImage').src = imgUrl;
                            document.querySelectorAll('.thumbnail-image').forEach(t => t.classList.remove('active'));
                            this.classList.add('active');
                        };
                        thumbnailGallery.appendChild(thumbElement);
                    });
                }
        
        const variantsData = this.getAttribute('data-product-variants');
        let variants = [];
        try {
            variants = JSON.parse(variantsData || '[]');
        } catch(e) {
            variants = [];
        }
        
        const variantsContainer = document.getElementById('modalProductVariants');
        variantsContainer.innerHTML = '';
        
        if(variants && variants.length > 0) {
            variants.forEach((variantGroup, groupIndex) => {
                const groupDiv = document.createElement('div');
                groupDiv.className = 'variant-group';
                
                const groupTitle = document.createElement('div');
                groupTitle.className = 'variant-title';
                groupTitle.textContent = variantGroup.title;
                groupDiv.appendChild(groupTitle);
                
                const variantOptions = document.createElement('div');
                variantOptions.className = 'variant-options';
                
                if(variantGroup.variant && variantGroup.variant.length > 0) {
                    variantGroup.variant.forEach((option, optionIndex) => {
                        const optionBtn = document.createElement('button');
                        optionBtn.className = 'variant-btn' + (optionIndex === 0 ? ' active' : '');
                        optionBtn.textContent = option.name;
                        optionBtn.dataset.variantPrice = option.price || '';
                        optionBtn.dataset.variantImage = option.image_url || '';
                        optionBtn.dataset.groupIndex = groupIndex;
                        optionBtn.dataset.optionIndex = optionIndex;
                        
                        optionBtn.onclick = function() {
                            this.parentElement.querySelectorAll('.variant-btn').forEach(btn => btn.classList.remove('active'));
                            this.classList.add('active');
                            
                            if(this.dataset.variantPrice) {
                                const currency = price.split(/[0-9]/)[0];
                                document.getElementById('modalProductPrice').textContent = currency + new Intl.NumberFormat('id-ID').format(this.dataset.variantPrice);
                            }
                            
                            if(this.dataset.variantImage) {
                                document.getElementById('mainProductImage').src = this.dataset.variantImage;
                            }
                        };
                        
                        variantOptions.appendChild(optionBtn);
                    });
                }
                
                groupDiv.appendChild(variantOptions);
                variantsContainer.appendChild(groupDiv);
            });
        }
        
        if(priceStrike) {
                    document.getElementById('modalProductPriceStrike').textContent = priceStrike;
                    document.getElementById('modalProductPriceStrike').style.display = 'inline-block';
                } else {
                    document.getElementById('modalProductPriceStrike').style.display = 'none';
                }
                
                const modalCartBtn = document.getElementById('modalAddToCart');
                modalCartBtn.setAttribute('data-index', index);
                modalCartBtn.setAttribute('data-cart', 'add');
                modalCartBtn.setAttribute('data-link-id', linkId);
                
                modalCartBtn.onclick = function() {
                    const originalBtn = document.querySelector(`a[data-cart="add"][data-link-id="${linkId}"]`);
                    if(originalBtn) {
                        originalBtn.click();
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                };
                
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        });
        
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
        
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    })();
    </script>
    <style>
    /* --- Product Grid Styles --- */
    .bootstrap-select{flex: 1 1 auto !important;}.bootstrap-select .btn{line-height:2.25 !important;border-top-left-radius:0;border-bottom-left-radius:0;}
    
    .pricing-table .row { margin-left: -8px; margin-right: -8px; }
    .cat-product { padding-left: 8px; padding-right: 8px; }
    .cat-product .item { height: 100%; display: flex; flex-direction: column; }
    
    .cat-product .item .image {
        width: 100%;
        height: auto;
        max-height: 250px;
        object-fit: cover;
        border-radius: 8px;
        background: #f0f0f0; /* Placeholder bg */
    }
    
    .cat-product .product-desc { flex: 1; display: flex; flex-direction: column; }
    
    .product-btn { display: flex; gap: 8px; margin-top: auto; padding-top: 10px; }
    
    .product-btn .btn-detail {
        flex: 1;
        padding: 12px 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-align: center;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .product-btn .btn-detail:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
    .product-btn .none { flex: 1; }

    /* --- MODAL RESPONSIVE STYLES --- */
    
    /* Modal Overlay */
    .product-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.7);
        padding: 20px;
        box-sizing: border-box; /* Critical for responsive padding */
        align-items: center; /* Center vertically */
        justify-content: center; /* Center horizontally */
        -webkit-overflow-scrolling: touch;
    }

    /* Modal Content Box */
    .product-modal-content {
        background-color: #fefefe;
        width: 100%;
        max-width: 900px;
        /* RESPONSIVE FIX: Limit height and allow internal scrolling */
        max-height: 90vh; 
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        position: relative;
        overflow: hidden; /* Contains children */
        box-sizing: border-box;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from { transform: translateY(50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    /* Close Button */
    .product-modal-close {
        color: #aaa;
        position: absolute;
        right: 15px;
        top: 15px;
        font-size: 32px;
        font-weight: bold;
        cursor: pointer;
        z-index: 20;
        transition: color 0.3s;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255,255,255,0.8);
        line-height: 1;
    }

    .product-modal-close:hover { color: #000; background: #fff; }

    /* Modal Body (Scrollable Area) */
    .product-modal-body {
        display: flex;
        flex-direction: row;
        padding: 50px 30px 30px; /* Top padding accounts for close button */
        gap: 30px;
        overflow-y: auto; /* Enable scrolling inside the modal */
        box-sizing: border-box;
        flex: 1; /* Take remaining height */
        min-height: 0; /* Fix for nested flex scrolling */
    }

    /* Images Section */
    .product-modal-images {
        flex: 1;
        max-width: 400px;
        min-width: 0; /* Fix flex text overflow */
        display: flex;
        flex-direction: column;
    }

    .main-image-container {
        width: 100%;
        margin-bottom: 12px;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        position: relative;
    }

    .main-product-image {
        width: 100%;
        height: auto;
        display: block;
        object-fit: contain; /* Keep aspect ratio */
        max-height: 40vh;
    }

    .thumbnail-gallery {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 5px;
    }

    .thumbnail-image {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid transparent;
        flex-shrink: 0;
    }

    .thumbnail-image.active { border-color: #667eea; }
    .thumbnail-image:hover { opacity: 0.8; }

    /* Info Section */
    .product-modal-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0; /* Fix flex text overflow */
    }

    .product-modal-info h2 {
        font-size: 24px;
        margin: 0 0 15px;
        color: #333;
        word-wrap: break-word;
    }

    .product-modal-price { margin-bottom: 20px; }
    .modal-price { font-size: 28px; font-weight: 700; color: #667eea; margin-right: 10px; }
    .modal-price-strike { font-size: 18px; color: #999; text-decoration: line-through; }

    .product-modal-variants { margin-bottom: 20px; }
    .variant-group { margin-bottom: 15px; }
    .variant-title { font-weight: 600; margin-bottom: 8px; color: #555; }
    .variant-options { display: flex; flex-wrap: wrap; gap: 8px; }
    
    .variant-btn {
        padding: 8px 16px;
        border: 1px solid #ddd;
        background: #fff;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }
    
    .variant-btn.active {
        background: #667eea;
        color: #fff;
        border-color: #667eea;
    }

    .product-modal-description {
        flex: 1;
        font-size: 15px;
        line-height: 1.6;
        color: #666;
        margin-bottom: 20px;
        word-wrap: break-word;
        white-space: pre-wrap;
        overflow-y: auto;
    }

    .product-modal-actions { margin-top: auto; }

    .modal-btn-cart {
        display: block;
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-align: center;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        font-size: 18px;
        transition: opacity 0.3s;
    }
    
    .modal-btn-cart:hover { opacity: 0.9; }

    /* --- MEDIA QUERIES (RESPONSIVE FIXES) --- */

    /* Tablets and below */
    @media (max-width: 850px) {
        .product-modal { padding: 15px; }
        .product-modal-content { max-height: 95vh; }
        .product-modal-body { flex-direction: column; gap: 20px; padding: 45px 20px 20px; }
        
        .product-modal-images { max-width: 100%; }
        .main-product-image { max-height: 300px; }
        
        .product-modal-info h2 { font-size: 20px; }
        .modal-price { font-size: 24px; }
    }

    /* Mobile Phones */
    @media (max-width: 576px) {
        /* Reduce overlay padding to maximize space */
        .product-modal { padding: 10px; align-items: flex-end; /* Slight offset for better feel */ }
        
        .product-modal-content {
            width: 100%;
            max-height: 100vh; /* Use full height on mobile */
            border-radius: 12px 12px 0 0; /* Rounded only top corners */
            margin-bottom: 0;
        }
        
        .product-modal-body {
            padding: 40px 15px 15px;
            gap: 15px;
        }

        /* Adjust Image for Mobile */
        .main-product-image { max-height: 250px; }
        .thumbnail-image { width: 55px; height: 55px; }

        /* Adjust Typography */
        .product-modal-info h2 { font-size: 18px; margin-bottom: 10px; }
        .modal-price { font-size: 22px; }
        .product-modal-description { font-size: 14px; max-height: 200px; }
        
        /* Adjust Buttons */
        .variant-btn { padding: 8px 12px; font-size: 13px; }
        .modal-btn-cart { font-size: 16px; padding: 14px; }
        .product-modal-close { top: 10px; right: 10px; width: 32px; height: 32px; font-size: 24px; }
        
        /* Grid Layout: 2 columns to 1 column */
        .cat-product { flex: 0 0 100%; max-width: 100%; }
    }
    </style>
</div>