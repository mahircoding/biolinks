<?php defined('ALTUMCODE') || die() ?>

<div class="my-3 category-product">
    <div class="form-group">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text text-info">Category</span>
			</div>
			<select class="selectpicker">
			<option value="all">All</option>
			<?php foreach($settings_data as $iy => $it) {?>
			<option value="<?= $iy ?>"><?= $it->category ?></option>
			<?php }?>
			</select>
		</div>
	</div>
	<div class="pricing-table">
		<div class="row justify-content-center align-items-stretch">
		<?php 
		// Handle backward compatibility for old e-shop structure
		$settings_data = isset($data->link->settings->data) ? $data->link->settings->data : $data->link->settings;
		$button_text = isset($data->link->settings->button_text) ? $data->link->settings->button_text : 'Add to Cart';
		$num_prd=0; foreach($settings_data as $iy => $it) {?>
		<?php foreach($it->products as $iz => $pr) {
		if(isset($pr->show)&&$pr->show) {
		?>
		<div data-filter-product="<?= $iy ?>" class="col-md-4 col-lg-4 mb-4 cat-product">
			<div class="item">
				<img class="image" src="<?= str_replace('http://','https://',$pr->image_url) ?>" />
				<div class="product-desc">
					<h3 class="title"><?= $pr->title ?></h3>
					<div class="desc"><?= isset($pr->description) ? $pr->description : '' ?></div>
					<div class="price"><?= $data->user->currency.number_format($pr->price,0,"",",") ?></div>
					<?php if(!empty($pr->price_strike)) {?>
					<div class="price-strike"><?= $data->user->currency.number_format($pr->price_strike,0,"",",") ?></div>
					<?php }?>
				</div>
				<div class="product-btn">
					<a class="none" data-index="<?= $num_prd ?>" data-cart="add" data-link-id="<?= $data->link->link_id.':'.$iy.":".$iz ?>" href="javascript:;"><?= $button_text ?></a>
					<a class="btn btn-outline-primary btn-sm ml-2" data-toggle="modal" data-target="#productDetailModal" data-product='<?= json_encode([
						'title' => $pr->title,
						'description' => $pr->description ?? '',
						'detailed_description' => $pr->detailed_description ?? '',
						'price' => $pr->price,
						'price_strike' => $pr->price_strike ?? null,
						'weight' => $pr->weight ?? 100,
						'image_url' => $pr->image_url ?? '',
						'image_1' => $pr->image_1 ?? null,
						'image_2' => $pr->image_2 ?? null,
						'image_3' => $pr->image_3 ?? null,
						'image_4' => $pr->image_4 ?? null,
						'category_index' => $iy,
						'product_index' => $iz
					]) ?>' href="javascript:;">Detail</a>
				</div>
			</div>
		</div>
		<?php } elseif(!isset($pr->show)) {?>
		<div data-filter-product="<?= $iy ?>" class="col-md-4 col-lg-4 mb-4 cat-product">
			<div class="item">
				<img class="image" src="<?= str_replace('http://','https://',$pr->image_url) ?>" />
				<div class="product-desc">
					<h3 class="title"><?= $pr->title ?></h3>
					<div class="desc"><?= isset($pr->description) ? $pr->description : '' ?></div>
					<div class="price"><?= $data->user->currency.number_format($pr->price,0,"",",") ?></div>
					<?php if(!empty($pr->price_strike)) {?>
					<div class="price-strike"><?= $data->user->currency.number_format($pr->price_strike,0,"",",") ?></div>
					<?php }?>
				</div>
				<div class="product-btn">
					<a class="none" data-index="<?= $num_prd ?>" data-cart="add" data-link-id="<?= $data->link->link_id.':'.$iy.":".$iz ?>" href="javascript:;"><?= $button_text ?></a>
					<a class="btn btn-outline-primary btn-sm ml-2" data-toggle="modal" data-target="#productDetailModal" data-product='<?= json_encode([
						'title' => $pr->title,
						'description' => $pr->description ?? '',
						'detailed_description' => $pr->detailed_description ?? '',
						'price' => $pr->price,
						'price_strike' => $pr->price_strike ?? null,
						'weight' => $pr->weight ?? 100,
						'image_url' => $pr->image_url ?? '',
						'image_1' => $pr->image_1 ?? null,
						'image_2' => $pr->image_2 ?? null,
						'image_3' => $pr->image_3 ?? null,
						'image_4' => $pr->image_4 ?? null,
						'category_index' => $iy,
						'product_index' => $iz
					]) ?>' href="javascript:;">Detail</a>
				</div>
			</div>
		</div>
		<?php }
		if(empty($pr->link)) $num_prd++;
		}}
		?>
		</div>
	</div>
	<script>eshop[<?= $data->link->link_id ?>] = <?= json_encode($settings_data) ?></script>
	<style>
	.bootstrap-select{flex: 1 1 auto !important;}.bootstrap-select .btn{line-height:2.25 !important;border-top-left-radius:0;border-bottom-left-radius:0;}
	.product-btn{display:flex;gap:8px;align-items:center;justify-content:center;}
	
	/* Modal improvements */
	#productDetailModal .modal-dialog {
		max-width: 800px;
	}
	#productDetailModal .product-image-gallery {
		position: relative;
	}
	#productDetailModal .thumbnail-img {
		border-radius: 4px;
		transition: all 0.3s ease;
	}
	#productDetailModal .thumbnail-img:hover {
		transform: scale(1.05);
		box-shadow: 0 2px 8px rgba(0,0,0,0.2);
	}
	#productDetailModal .product-description {
		max-height: 200px;
		overflow-y: auto;
		text-align: left;
		line-height: 2;
		font-size: 14px;
	}
	#productDetailModal .product-title {
		color: #333;
		font-weight: 600;
	}
	#productDetailModal .product-price .h4 {
		font-weight: 700;
	}
	
	/* Fix modal backdrop issues - More specific selectors to avoid conflicts */
	#productDetailModal .modal-backdrop,
	.modal-backdrop.show {
		z-index: 1040 !important;
		position: relative !important;
		top: 0 !important;
		left: 0 !important;
		width: 100vw !important;
		height: 100vh !important;
		background-color: rgba(0, 0, 0, 0.5) !important;
		pointer-events: auto !important;
	}
	
	/* Ensure modal dialog is properly positioned */
	#productDetailModal .modal-dialog {
		position: relative !important;
		z-index: 1050 !important;
	}
	
	/* Modal content styling */
	#productDetailModal .modal-content {
		position: relative !important;
		z-index: 1051 !important;
	}
	</style>
</div>

<!-- Product Detail Modal -->
<div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog" aria-labelledby="productDetailModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="productDetailModalLabel">Detail Produk</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<!-- Product Image Gallery -->
						<div id="productImageGallery" class="product-image-gallery">
							<div id="mainImage" class="main-image mb-3">
								<img id="mainProductImage" src="" class="img-fluid rounded" style="width:100%;height:300px;object-fit:cover;" alt="Product Image">
							</div>
							<div id="thumbnailGallery" class="thumbnail-gallery d-flex flex-wrap gap-2">
								<!-- Thumbnails will be populated by JavaScript -->
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<!-- Product Details -->
						<div class="product-details">
							<h3 id="productTitle" class="product-title mb-3"></h3>
							<div id="productPrice" class="product-price mb-3">
								<!-- Price will be populated by JavaScript -->
							</div>
							<div id="productDescription" class="product-description mb-4">
								<!-- Description will be populated by JavaScript -->
							</div>
							<div id="productWeight" class="product-weight mb-3">
								<small class="text-muted">Berat: <span id="weightValue"></span> gram</small>
							</div>
							<div class="product-actions">
								<button class="btn btn-primary btn-lg" id="addToCartFromDetail">
									<i class="fas fa-shopping-cart mr-2"></i><?= $button_text ?>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	// Debug: Check if Bootstrap is loaded
	if (typeof $.fn.modal === 'undefined') {
		console.error('Bootstrap modal is not loaded!');
		return;
	}
	
	// Handle product detail modal
	$('#productDetailModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var productData = button.data('product');
		
		if (!productData) {
			console.error('No product data found');
			return;
		}
		
		var product = typeof productData === 'string' ? JSON.parse(productData) : productData;
		var modal = $(this);
		
		// Populate product details
		modal.find('#productTitle').text(product.title);
		modal.find('#weightValue').text(product.weight || 100);
		
		// Handle price display
		var currency = '<?= $data->user->currency ?>';
		var priceHtml = '<p class="h4 text-primary">' + currency + product.price.toLocaleString('id-ID') + '</p>';
		if(product.price_strike && product.price_strike > 0) {
			priceHtml += ' <p class="text-muted text-decoration-line-through ml-2">' + currency + product.price_strike.toLocaleString('id-ID') + '</p>';
		}
		modal.find('#productPrice').html(priceHtml);
		
		// Handle description
		var description = product.detailed_description || product.description || 'Tidak ada deskripsi tersedia.';
		// Check if description contains HTML tags
		if(description.includes('<') && description.includes('>')) {
			modal.find('#productDescription').html(description);
		} else {
			modal.find('#productDescription').html('<p>' + description + '</p>');
		}
		
		// Handle images
		var mainImage = modal.find('#mainProductImage');
		var thumbnailGallery = modal.find('#thumbnailGallery');
		
		// Clear previous thumbnails
		thumbnailGallery.empty();
		
		// Handle individual image fields (image_1, image_2, image_3, image_4)
		var allImages = [];
		
		// Collect all non-null images
		if(product.image_url) allImages.push(product.image_url);
		if(product.image_1) allImages.push(product.image_1);
		if(product.image_2) allImages.push(product.image_2);
		if(product.image_3) allImages.push(product.image_3);
		if(product.image_4) allImages.push(product.image_4);
		
		// Backward compatibility: If old format 'images' array exists, use it
		if(product.images && product.images.length > 0) {
			allImages = [product.image_url].concat(product.images).filter(img => img);
		}
		
		if(allImages.length > 0) {
			mainImage.attr('src', allImages[0]);
			allImages.forEach(function(image, index) {
				var thumbnail = $('<img>').attr({
					'src': image,
					'class': 'thumbnail-img',
					'style': 'width:60px;height:60px;object-fit:cover;cursor:pointer;border:2px solid #ddd;',
					'onclick': 'changeMainImage(this.src)'
				});
				if(index === 0) thumbnail.css('border-color', '#007bff');
				thumbnailGallery.append(thumbnail);
			});
		}
		// Fallback if no images
		else {
			mainImage.attr('src', 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==');
		}
		
		// Set up add to cart functionality
		modal.find('#addToCartFromDetail').off('click').on('click', function() {
			// Find the original add to cart button and trigger it
			var originalButton = $('a[data-link-id="<?= $data->link->link_id ?>:' + product.category_index + ':' + product.product_index + '"]');
			if(originalButton.length) {
				originalButton.trigger('click');
				modal.modal('hide');
			}
		});
	});
});

// Function to change main image
function changeMainImage(src) {
	$('#mainProductImage').attr('src', src);
	$('.thumbnail-img').css('border-color', '#ddd');
	$(event.target).css('border-color', '#007bff');
}
</script>

