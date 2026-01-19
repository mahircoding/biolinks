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
    
    <!-- Product Detail Modal -->
    <div id="productDetailModal" class="position-fixed" style="inset: 0; width: 100%; z-index: 100000; background-color: rgba(0,0,0,0.75); display: none !important;">
        <div class="bg-white rounded shadow overflow-hidden position-relative mx-2" style="max-width: 900px; max-height: 90vh; margin: auto; overflow-y: auto; width: calc(100% - 1rem);">
            
            <span class="product-modal-close position-absolute text-dark bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="top: 10px; right: 10px; z-index: 1000; cursor: pointer; font-size: 24px; width: 36px; height: 36px; opacity: 0.8;">&times;</span>
            
            <div class="d-flex flex-column flex-md-row">
                
                <div class="col-12 col-md-6 p-0 bg-light border-bottom border-md-bottom-0 border-md-right">
                    <div class="p-3 d-flex flex-column align-items-center">
                        <div class="w-100 bg-white rounded mb-3 p-2 text-center d-flex align-items-center justify-content-center" style="min-height: 250px; max-height: 50vh;">
                            <img id="mainProductImage" src="" alt="Product" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;" />
                        </div>
                        <div id="thumbnailGallery" class="d-flex flex-wrap justify-content-center gap-2 w-100 pb-2"></div>
                    </div>
                </div>
                
                <div class="col-12 col-md-6 p-4 d-flex flex-column bg-white">
                    <h2 id="modalProductTitle" class="h4 font-weight-bold mb-3 text-dark"></h2>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <span id="modalProductPrice" class="h3 font-weight-bold text-primary d-block mb-1"></span>
                        <span id="modalProductPriceStrike" class="text-muted text-decoration-line-through d-none small"></span>
                    </div>
                    
                    <div id="modalProductVariants" class="mb-4"></div>
                    
                    <!-- PERBAIKAN: Deskripsi dibatasi tingginya dan bisa di-scroll -->
                    <div id="modalProductDesc" class="text-secondary small mb-4" style="white-space: pre-wrap; line-height: 1.6;"></div>
                    
                    <div class="mt-auto">
                        <a id="modalAddToCart" class="btn btn-primary btn-block font-weight-bold py-3 rounded-lg" href="javascript:;"><?= $button_text ?></a>
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
            document.addEventListener('DOMContentLoaded', initModal);
        } else {
            initModal();
        }

        function initModal() {
            const modal = document.getElementById('productDetailModal');
            const closeBtn = document.querySelector('.product-modal-close');
            
            if(!modal) {
                console.error('Modal not found');
                return;
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
                            thumb.className = 'cursor-pointer rounded p-1 border' + (idx === 0 ? ' border-primary' : ' border-white');
                            thumb.style.width = '60px';
                            thumb.style.height = '60px';
                            thumb.style.objectFit = 'cover';
                            thumb.style.backgroundColor = '#fff';
                            thumb.style.transition = 'all 0.2s';
                            
                            thumb.onclick = function() {
                                mainImg.src = imgUrl;
                                Array.from(thumbnailGallery.children).forEach(c => {
                                    c.classList.remove('border-primary');
                                    c.classList.add('border-white');
                                });
                                this.classList.remove('border-white');
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
                            groupDiv.className = 'mb-3';
                            const groupTitle = document.createElement('div');
                            groupTitle.className = 'font-weight-bold small mb-2 text-dark';
                            groupTitle.textContent = variantGroup.title || '';
                            groupDiv.appendChild(groupTitle);
                            
                            const optionsDiv = document.createElement('div');
                            optionsDiv.className = 'd-flex flex-wrap gap-2';
                            
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
                                closeModal();
                            }
                        };
                    }
                    
                    // Show Modal
                    modal.style.display = 'flex';
                    modal.style.flexDirection = 'row';
                    modal.style.flexWrap = 'wrap';
                    modal.style.alignContent = 'center';
                    modal.style.justifyContent = 'center';
                    
                    document.body.style.overflow = 'hidden';
                });
            });
            
            const closeModal = () => {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            };
            
            if(closeBtn) {
                closeBtn.addEventListener('click', closeModal);
            }
            
            window.addEventListener('click', (e) => { 
                if(e.target == modal) closeModal(); 
            });
        }
    })();
    </script>
    
    <style>
        .rounded-lg { border-radius: 0.5rem !important; }
        .btn-block { display: block; width: 100%; }
        .py-3 { padding-top: 0.75rem !important; padding-bottom: 0.75rem !important; }
        .border-md-right { border-right: 1px solid #dee2e6 !important; }
        .border-md-bottom-0 { border-bottom: 0 !important; }
        @media (max-width: 767.98px) {
            .border-md-right { border-right: 0 !important; }
        }
        
        /* Custom Scrollbar for Modal Content Box */
        #productDetailModal > div::-webkit-scrollbar { width: 6px; }
        #productDetailModal > div::-webkit-scrollbar-track { background: #f1f1f1; }
        #productDetailModal > div::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
        
        /* SCROLLABLE DESCRIPTION CSS */
        #modalProductDesc {
            /* Hapus flex-grow agar tidak memaksa tinggi modal */
            flex: unset; 
            /* Batasi tinggi maksimal (misal 200px) */
            max-height: 200px;
            /* Aktifkan scroll vertikal jika teks melebihi batas */
            overflow-y: auto;
            /* Tambahkan padding kanan agar scrollbar tidak menutupi teks */
            padding-right: 8px; 
            /* Styling scrollbar khusus untuk deskripsi */
        }
        
        #modalProductDesc::-webkit-scrollbar { width: 4px; }
        #modalProductDesc::-webkit-scrollbar-track { background: #f9f9f9; }
        #modalProductDesc::-webkit-scrollbar-thumb { background: #bbb; border-radius: 2px; }

        .bootstrap-select{flex: 1 1 auto !important;} 
        .bootstrap-select .btn{line-height:2.25 !important;border-top-left-radius:0;border-bottom-left-radius:0;}
    </style>
</div>