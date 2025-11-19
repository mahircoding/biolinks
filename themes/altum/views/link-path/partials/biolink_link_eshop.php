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
					   data-product-price="<?= $data->user->currency.number_format($pr->price,0,"",",") ?>" 
					   data-product-price-strike="<?= !empty($pr->price_strike) ? $data->user->currency.number_format($pr->price_strike,0,"",",") : '' ?>" 
					   data-product-image="<?= str_replace('http://','https://',$pr->image_url) ?>"
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
					   data-product-price="<?= $data->user->currency.number_format($pr->price,0,"",",") ?>" 
					   data-product-price-strike="<?= !empty($pr->price_strike) ? $data->user->currency.number_format($pr->price_strike,0,"",",") : '' ?>" 
					   data-product-image="<?= str_replace('http://','https://',$pr->image_url) ?>"
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
				<div class="product-modal-image">
					<img id="modalProductImage" src="" alt="Product Image">
				</div>
				<div class="product-modal-info">
					<h2 id="modalProductTitle"></h2>
					<div class="product-modal-price">
						<span id="modalProductPrice" class="modal-price"></span>
						<span id="modalProductPriceStrike" class="modal-price-strike"></span>
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
				const price = this.getAttribute('data-product-price');
				const priceStrike = this.getAttribute('data-product-price-strike');
				const image = this.getAttribute('data-product-image');
				const index = this.getAttribute('data-product-index');
				const linkId = this.getAttribute('data-product-link-id');
				
				document.getElementById('modalProductTitle').textContent = title;
				document.getElementById('modalProductDesc').textContent = desc || 'No description available';
				document.getElementById('modalProductPrice').textContent = price;
				document.getElementById('modalProductImage').src = image;
				
				if(priceStrike) {
					document.getElementById('modalProductPriceStrike').textContent = priceStrike;
					document.getElementById('modalProductPriceStrike').style.display = 'inline-block';
				} else {
					document.getElementById('modalProductPriceStrike').style.display = 'none';
				}
				
				// Set add to cart data
				const modalCartBtn = document.getElementById('modalAddToCart');
				modalCartBtn.setAttribute('data-index', index);
				modalCartBtn.setAttribute('data-cart', 'add');
				modalCartBtn.setAttribute('data-link-id', linkId);
				
				modal.style.display = 'block';
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
	
	/* Product Button Styles */
	.product-btn {
		display: flex;
		gap: 8px;
		margin-top: 10px;
	}
	
	.product-btn .btn-detail {
		flex: 1;
		padding: 10px 15px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		text-align: center;
		border-radius: 8px;
		text-decoration: none;
		font-weight: 600;
		transition: all 0.3s ease;
		border: none;
		cursor: pointer;
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
		width: 100%;
		height: 100%;
		overflow: auto;
		background-color: rgba(0,0,0,0.7);
		animation: fadeIn 0.3s ease;
	}
	
	@keyframes fadeIn {
		from { opacity: 0; }
		to { opacity: 1; }
	}
	
	.product-modal-content {
		background-color: #fefefe;
		margin: 5% auto;
		padding: 0;
		border-radius: 16px;
		width: 90%;
		max-width: 800px;
		box-shadow: 0 10px 40px rgba(0,0,0,0.3);
		animation: slideDown 0.3s ease;
		position: relative;
	}
	
	@keyframes slideDown {
		from {
			transform: translateY(-50px);
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
		right: 20px;
		top: 15px;
		font-size: 32px;
		font-weight: bold;
		cursor: pointer;
		z-index: 1;
		transition: color 0.3s ease;
	}
	
	.product-modal-close:hover,
	.product-modal-close:focus {
		color: #000;
	}
	
	.product-modal-body {
		display: flex;
		flex-direction: row;
		padding: 30px;
		gap: 30px;
	}
	
	.product-modal-image {
		flex: 1;
		max-width: 350px;
	}
	
	.product-modal-image img {
		width: 100%;
		height: auto;
		border-radius: 12px;
		object-fit: cover;
	}
	
	.product-modal-info {
		flex: 1;
		display: flex;
		flex-direction: column;
	}
	
	.product-modal-info h2 {
		margin: 0 0 15px 0;
		font-size: 28px;
		font-weight: 700;
		color: #333;
	}
	
	.product-modal-price {
		margin-bottom: 20px;
	}
	
	.modal-price {
		font-size: 32px;
		font-weight: 700;
		color: #667eea;
		margin-right: 10px;
	}
	
	.modal-price-strike {
		font-size: 20px;
		color: #999;
		text-decoration: line-through;
	}
	
	.product-modal-description {
		flex: 1;
		margin-bottom: 25px;
		font-size: 16px;
		line-height: 1.6;
		color: #666;
		white-space: pre-wrap;
	}
	
	.product-modal-actions {
		margin-top: auto;
	}
	
	.modal-btn-cart {
		display: inline-block;
		width: 100%;
		padding: 15px 30px;
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
	}
	
	.modal-btn-cart:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
		color: white;
		text-decoration: none;
	}
	
	/* Responsive Design */
	@media (max-width: 768px) {
		.product-modal-content {
			width: 95%;
			margin: 10% auto;
		}
		
		.product-modal-body {
			flex-direction: column;
			padding: 20px;
		}
		
		.product-modal-image {
			max-width: 100%;
		}
		
		.product-modal-info h2 {
			font-size: 22px;
		}
		
		.modal-price {
			font-size: 24px;
		}
		
		.product-btn {
			flex-direction: column;
		}
		
		.product-btn .btn-detail,
		.product-btn .none {
			width: 100%;
		}
	}
	</style>
</div>

