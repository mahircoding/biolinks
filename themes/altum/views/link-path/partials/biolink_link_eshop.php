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
				// Use full description in modal, fallback to short description if not available
				document.getElementById('modalProductDesc').textContent = fullDesc || desc || 'No description available';
				document.getElementById('modalProductPrice').textContent = price;
				
				// Populate image gallery with main image + thumbnails
				const images = [image, image1, image2, image3, image4].filter(img => img && img.trim() !== '');
				
				if(images.length > 0) {
					// Set first image as main
					document.getElementById('mainProductImage').src = images[0];
					
					// Create thumbnails
					const thumbnailGallery = document.getElementById('thumbnailGallery');
					thumbnailGallery.innerHTML = '';
					
					images.forEach((imgUrl, index) => {
						const thumbElement = document.createElement('img');
						thumbElement.src = imgUrl;
						thumbElement.alt = title;
						thumbElement.className = 'thumbnail-image' + (index === 0 ? ' active' : '');
						thumbElement.onclick = function() {
							// Update main image
							document.getElementById('mainProductImage').src = imgUrl;
							// Update active state
							document.querySelectorAll('.thumbnail-image').forEach(t => t.classList.remove('active'));
							this.classList.add('active');
						};
						thumbnailGallery.appendChild(thumbElement);
					});
				}
		
		// Populate product variants
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
							// Remove active from siblings
							this.parentElement.querySelectorAll('.variant-btn').forEach(btn => btn.classList.remove('active'));
							this.classList.add('active');
							
							// Update price if variant has price
							if(this.dataset.variantPrice) {
								const currency = price.split(/[0-9]/)[0]; // Extract currency symbol
								document.getElementById('modalProductPrice').textContent = currency + new Intl.NumberFormat('id-ID').format(this.dataset.variantPrice);
							}
							
							// Update main image if variant has image
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
				
				// Set add to cart data and click handler
				const modalCartBtn = document.getElementById('modalAddToCart');
				modalCartBtn.setAttribute('data-index', index);
				modalCartBtn.setAttribute('data-cart', 'add');
				modalCartBtn.setAttribute('data-link-id', linkId);
				
				// Add click event to trigger cart add
				modalCartBtn.onclick = function() {
					// Find the original add to cart button and trigger its click
					const originalBtn = document.querySelector(`a[data-cart="add"][data-link-id="${linkId}"]`);
					if(originalBtn) {
						originalBtn.click();
						// Close modal after adding to cart
						modal.style.display = 'none';
						document.body.style.overflow = 'auto';
					}
				};
				
				modal.style.display = 'flex';
				document.body.style.overflow = 'hidden';
			});
		});
		
		// Close modal
		closeBtn.addEventListener('click', function() {
			modal.style.display = 'none';
			document.body.style.overflow = 'auto';
		});
		
		// Close when clicking outside
		window.addEventListener('click', function(event) {
			if (event.target == modal) {
				modal.style.display = 'none';
				document.body.style.overflow = 'auto';
			}
		});
	})();
	</script>
	<style>
	.bootstrap-select{flex: 1 1 auto !important;}.bootstrap-select .btn{line-height:2.25 !important;border-top-left-radius:0;border-bottom-left-radius:0;}
	
	/* Product Grid Responsive */
	.pricing-table .row {
		margin-left: -8px;
		margin-right: -8px;
	}
	
	.cat-product {
		padding-left: 8px;
		padding-right: 8px;
	}
	
	.cat-product .item {
		height: 100%;
		display: flex;
		flex-direction: column;
	}
	
	.cat-product .item .image {
		width: 100%;
		height: auto;
		max-height: 250px;
		object-fit: cover;
		border-radius: 8px;
	}
	
	.cat-product .product-desc {
		flex: 1;
		display: flex;
		flex-direction: column;
	}
	
	/* Product Button Styles */
	.product-btn {
		display: flex;
		gap: 8px;
		margin-top: auto;
		padding-top: 10px;
	}
	
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
	
	.product-btn .btn-detail:hover {
		transform: translateY(-2px);
		box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
	}
	
	.product-btn .none {
		flex: 1;
	}
	
	/* Modal Styles */
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
		overflow: auto;
		background-color: rgba(0,0,0,0.7);
		animation: fadeIn 0.3s ease;
		padding: 20px;
		-webkit-overflow-scrolling: touch;
	}
	
	@keyframes fadeIn {
		from { opacity: 0; }
		to { opacity: 1; }
	}
	
	.product-modal-content {
		background-color: #fefefe;
		margin: 20px auto;
		padding: 0;
		border-radius: 16px;
		width: 100%;
		max-width: 900px;
		max-height: none;
		display: flex;
		flex-direction: column;
		box-shadow: 0 10px 40px rgba(0,0,0,0.3);
		animation: slideDown 0.3s ease;
		position: relative;
	}
	
	@keyframes slideDown {
		from {
			transform: translateY(-30px);
			opacity: 0;
		}
		to {
			transform: translateY(0);
			opacity: 1;
		}
	}
	
	.product-modal-close {
		color: #aaa;
		position: absolute;
		right: 15px;
		top: 10px;
		font-size: 32px;
		font-weight: bold;
		cursor: pointer;
		z-index: 10;
		transition: color 0.3s ease;
		width: 40px;
		height: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 50%;
		background: rgba(255,255,255,0.9);
	}
	
	.product-modal-close:hover,
	.product-modal-close:focus {
		color: #000;
		background: rgba(255,255,255,1);
	}
	
	.product-modal-body {
		display: flex;
		flex-direction: row;
		padding: 40px 30px 30px;
		gap: 30px;
		overflow: visible;
	}
	
	.product-modal-images {
		flex: 1;
		max-width: 400px;
		min-width: 0;
	}
	
	.main-image-container {
		width: 100%;
		margin-bottom: 12px;
		position: relative;
		overflow: hidden;
		border-radius: 12px;
	}
	
	.main-product-image {
		width: 100%;
		height: auto;
		border-radius: 12px;
		object-fit: contain;
		max-height: 400px;
		display: block;
	}
	
	.thumbnail-gallery {
		display: flex;
		gap: 10px;
		overflow-x: auto;
		padding: 8px 0;
		-webkit-overflow-scrolling: touch;
	}
	
	.thumbnail-image {
		width: 70px;
		height: 70px;
		border-radius: 8px;
		object-fit: cover;
		cursor: pointer;
		border: 2px solid transparent;
		transition: all 0.3s ease;
		flex-shrink: 0;
	}
	
	.thumbnail-image:hover {
		border-color: #667eea;
		transform: scale(1.05);
	}
	
	.thumbnail-image.active {
		border-color: #667eea;
		box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
	}
	
	/* Custom scrollbar for thumbnails */
	.thumbnail-gallery::-webkit-scrollbar {
		height: 6px;
	}
	
	.thumbnail-gallery::-webkit-scrollbar-track {
		background: #f1f1f1;
		border-radius: 10px;
	}
	
	.thumbnail-gallery::-webkit-scrollbar-thumb {
		background: #667eea;
		border-radius: 10px;
	}
	
	.thumbnail-gallery::-webkit-scrollbar-thumb:hover {
		background: #764ba2;
	}
	
	.product-modal-info {
		flex: 1;
		display: flex;
		flex-direction: column;
		min-width: 0;
	}
	
	.product-modal-info h2 {
		margin: 0 0 15px 0;
		font-size: 24px;
		font-weight: 700;
		color: #333;
		text-align: left;
		line-height: 1.3;
		word-wrap: break-word;
	}
	
	.product-modal-price {
		margin-bottom: 20px;
		text-align: left;
	}
	
	.modal-price {
		font-size: 28px;
		font-weight: 700;
		color: #667eea;
		margin-right: 10px;
		display: inline-block;
	}
	
	.modal-price-strike {
		font-size: 18px;
		color: #999;
		text-decoration: line-through;
		display: inline-block;
	}
	
	.product-modal-variants {
		margin-bottom: 20px;
	}
	
	.variant-group {
		margin-bottom: 18px;
	}
	
	.variant-title {
		font-size: 15px;
		font-weight: 600;
		color: #333;
		margin-bottom: 10px;
		text-align: left;
	}
	
	.variant-options {
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
	}
	
	.variant-btn {
		padding: 10px 18px;
		border: 2px solid #e0e0e0;
		border-radius: 8px;
		background: #fff;
		color: #333;
		font-size: 14px;
		font-weight: 500;
		cursor: pointer;
		transition: all 0.3s ease;
		outline: none;
		min-height: 44px;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	
	.variant-btn:hover {
		border-color: #667eea;
		background: #f8f9ff;
	}
	
	.variant-btn.active {
		border-color: #667eea;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: #fff;
		box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
	}
	
	.product-modal-description {
		flex: 1;
		margin-bottom: 25px;
		font-size: 15px;
		line-height: 1.7;
		color: #666;
		white-space: pre-wrap;
		max-height: 250px;
		overflow-y: auto;
		padding-right: 10px;
		text-align: left;
		word-wrap: break-word;
	}
	
	/* Custom scrollbar for description */
	.product-modal-description::-webkit-scrollbar {
		width: 6px;
	}
	
	.product-modal-description::-webkit-scrollbar-track {
		background: #f1f1f1;
		border-radius: 10px;
	}
	
	.product-modal-description::-webkit-scrollbar-thumb {
		background: #667eea;
		border-radius: 10px;
	}
	
	.product-modal-description::-webkit-scrollbar-thumb:hover {
		background: #764ba2;
	}
	
	.product-modal-actions {
		margin-top: auto;
		padding-top: 10px;
	}
	
	.modal-btn-cart {
		display: inline-block;
		width: 100%;
		padding: 16px 30px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		text-align: center;
		border-radius: 10px;
		text-decoration: none;
		font-weight: 700;
		font-size: 18px;
		transition: all 0.3s ease;
		border: none;
		cursor: pointer;
		min-height: 54px;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	
	.modal-btn-cart:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
		color: white;
		text-decoration: none;
	}
	
	/* Responsive Design */
	
	/* Large tablets and small desktops */
	@media (max-width: 992px) {
		.product-modal {
			padding: 15px;
		}
		
		.product-modal-content {
			max-width: 750px;
		}
		
		.product-modal-body {
			padding: 35px 25px 25px;
			gap: 25px;
		}
		
		.product-modal-images {
			max-width: 350px;
		}
		
		.main-product-image {
			max-height: 350px;
		}
	}
	
	/* Tablets */
	@media (max-width: 768px) {
		.cat-product {
			flex: 0 0 50%;
			max-width: 50%;
		}
		
		.product-modal {
			padding: 10px;
		}
		
		.product-modal-content {
			width: 100%;
			max-width: 100%;
			margin: 10px auto;
			border-radius: 12px;
		}
		
		.product-modal-close {
			right: 10px;
			top: 8px;
			font-size: 28px;
			width: 36px;
			height: 36px;
		}
		
		.product-modal-body {
			flex-direction: column;
			padding: 45px 20px 20px;
			gap: 20px;
		}
		
		.product-modal-images {
			max-width: 100%;
		}
		
		.main-product-image {
			max-height: 300px;
		}
		
		.thumbnail-image {
			width: 60px;
			height: 60px;
		}
		
		.product-modal-info h2 {
			font-size: 20px;
		}
		
		.modal-price {
			font-size: 24px;
		}
		
		.modal-price-strike {
			font-size: 16px;
		}
		
		.product-modal-description {
			font-size: 14px;
			max-height: 180px;
		}
		
		.modal-btn-cart {
			font-size: 16px;
			padding: 14px 24px;
		}
		
		.product-btn {
			flex-direction: column;
			gap: 8px;
		}
		
		.product-btn .btn-detail,
		.product-btn .none {
			width: 100%;
		}
	}
	
	/* Mobile phones */
	@media (max-width: 576px) {
		.cat-product {
			flex: 0 0 100%;
			max-width: 100%;
		}
		
		.product-modal {
			padding: 5px;
		}
		
		.product-modal-content {
			margin: 5px auto;
			border-radius: 10px;
		}
		
		.product-modal-close {
			right: 8px;
			top: 6px;
			font-size: 26px;
			width: 34px;
			height: 34px;
		}
		
		.product-modal-body {
			padding: 42px 15px 15px;
			gap: 15px;
		}
		
		.main-product-image {
			max-height: 250px;
		}
		
		.thumbnail-gallery {
			gap: 8px;
			padding: 6px 0;
		}
		
		.thumbnail-image {
			width: 50px;
			height: 50px;
		}
		
		.product-modal-info h2 {
			font-size: 18px;
			margin-bottom: 12px;
		}
		
		.modal-price {
			font-size: 22px;
		}
		
		.modal-price-strike {
			font-size: 15px;
		}
		
		.variant-title {
			font-size: 14px;
			margin-bottom: 8px;
		}
		
		.variant-btn {
			padding: 8px 14px;
			font-size: 13px;
			min-height: 40px;
		}
		
		.product-modal-description {
			font-size: 13px;
			line-height: 1.6;
			max-height: 150px;
			margin-bottom: 20px;
		}
		
		.modal-btn-cart {
			font-size: 15px;
			padding: 12px 20px;
			min-height: 48px;
		}
		
		.product-btn .btn-detail {
			padding: 10px 12px;
			font-size: 14px;
		}
	}
	
	/* Very small phones */
	@media (max-width: 375px) {
		.product-modal-body {
			padding: 40px 12px 12px;
		}
		
		.main-product-image {
			max-height: 200px;
		}
		
		.thumbnail-image {
			width: 45px;
			height: 45px;
		}
		
		.product-modal-info h2 {
			font-size: 16px;
		}
		
		.modal-price {
			font-size: 20px;
		}
		
		.variant-btn {
			padding: 7px 12px;
			font-size: 12px;
		}
		
		.product-modal-description {
			font-size: 12px;
			max-height: 120px;
		}
	}
	</style>
</div>

