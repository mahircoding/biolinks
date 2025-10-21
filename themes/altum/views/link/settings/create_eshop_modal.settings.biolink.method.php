<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_eshop" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->create_biolink_eshop_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <p class="text-muted modal-subheader"><?= $this->language->create_biolink_eshop_modal->subheader ?></p>

            <div class="modal-body">
                <form name="create_biolink_eshop" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="type" value="biolink" />
                    <input type="hidden" name="subtype" value="eshop" />

                    <div class="notification-container"></div>
					
					<div class="form-product-container">
					
						<div class="d-flex justify-content-between mb-3">
							<div class="flex-grow-1">
								<i class="fas fa-list-alt"></i> <b class="ml-1">Categories</b>
							</div>
							<div>
								<div data-eshop="aci" class="btn btn-primary"><i class="fa fa-plus"></i> Add</div>
							</div>
						</div>
						
						<div class="form-product-main">
						
							<div class="form-product-item open">
							
								<div class="form-product-ctg">
									<div data-eshop-btn="true" class="form-ctg-view hide">
										<div class="d-flex justify-content-between align-items-center">
											<div class="form-ctg-text flex--1"><div class="form-ctg-name"></div></div>
											<div class="d-flex">
												<div data-eshop="api" class="btn btn-primary cursor-pointer mr-1"><i class="fa fa-plus"></i></div>
												<div class="dropdown">
													<div data-toggle="dropdown" class="btn btn-light cursor-pointer dropdown-toggle dropdown-toggle-simple"><i class="fa fa-ellipsis-v"></i></div>
													<div class="dropdown-menu dropdown-menu-right">
														<a data-eshop="sci" class="dropdown-item none" href="javascript:;"><i class="fas fa-exchange-alt mr-1"></i> <span class="fbv-switch">Hide</span></a>
														<a data-eshop="eci" class="dropdown-item none" href="javascript:;"><i class="fa fa-pencil-alt fa-sm mr-1"></i> Edit</a>
														<a data-eshop="dci" class="dropdown-item none" href="javascript:;"><i class="fa fa-times mr-1"></i> Delete</a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-ctg-edit show">
										<div class="form-group mt-1">
											<input class="form-control" name="category[]" value="" placeholder="Insert a category name" required />
										</div>
										<div class="d-flex justify-content-center mb-1" style="display:none !important">
											<div data-eshop="csp" class="badge badge-primary badge-pill cursor-pointer p-2 pl-4 pr-4">Save</div>
										</div>
									</div>
								</div>
								
								<div class="form-product-bag">
									<div class="form-bag-item">
										<div class="form-bag-view hide">
											<div class="form-group">
												<div class="d-flex align-items-stretch">
													<div class="flex-grow-1 w-100 d-flex align-items-stretch mw-preview mr-2">
														<div class="fbv-image form-image-preview wh-70"></div>
													</div>
													
													<div class="overflow-hidden w-100 d-flex flex-direction-row flex-wrap">
														<div class="flex--1">
															<div class="fbv-title mb-1"><span></span></div>
															<div class="fbv-description mb-1"></div>
															<div class="fbv-mprice">
																<div class="text-nowrap">Rp<span class="fbv-price"></span></div>
																<div class="fbv-pstrike text-nowrap">Rp<span class="fbv-price-strike"></span></div>
															</div>
														</div>
													</div>
													
													<div class="dropdown">
														<div data-toggle="dropdown" class="btn btn-light  cursor-pointer dropdown-toggle dropdown-toggle-simple"><i class="fa fa-ellipsis-v"></i></div>
														<div class="dropdown-menu dropdown-menu-right">
															<a data-eshop="epi" class="dropdown-item none" href="javascript:;"><i class="fa fa-pencil-alt fa-sm mr-1"></i> Edit</a>
															<a data-eshop="dpi" class="dropdown-item none" href="javascript:;"><i class="fa fa-times mr-1"></i> Delete</a>
														</div>
													</div>
													
												</div>
											</div>
											<hr class="mb-1">
											<div class="form-vrn-main">
												<div class="form-vrn-vrm mb-2">
													<div class="d-flex justify-content-between align-items-center">
														<div class="form-mvrn-text flex--1"><div class="form-vrn-name">Variants</div></div>
														<div class="d-flex">
															<div data-eshop="vra" class="btn btn-primary cursor-pointer mr-1"><i class="fa fa-plus"></i></div>
														</div>
													</div>
												</div>
											</div>
											
										</div>
										<div class="form-bag-edit show">
										
											<div class="form-group">
												<label><i class="fas fa-fw fa-image fa-sm mr-1"></i> Product Images</label>
												<div class="multiple-image-upload">
													<div class="image-upload-container">
														<div class="custom-file mb-2">
															<input type="file" class="custom-file-input" data-image="upload" name="image_main[0][]" accept="image/x-png,image/gif,image/jpeg" required id="image_main_0">
															<label class="custom-file-label" for="image_main_0">Choose main image</label>
														</div>
														<div class="custom-file mb-2">
															<input type="file" class="custom-file-input" data-image="upload-single" name="image_1[0][]" accept="image/x-png,image/gif,image/jpeg" id="image_1_0">
															<label class="custom-file-label" for="image_1_0">Choose image 1 (optional)</label>
														</div>
														<div class="custom-file mb-2">
															<input type="file" class="custom-file-input" data-image="upload-single" name="image_2[0][]" accept="image/x-png,image/gif,image/jpeg" id="image_2_0">
															<label class="custom-file-label" for="image_2_0">Choose image 2 (optional)</label>
														</div>
														<div class="custom-file mb-2">
															<input type="file" class="custom-file-input" data-image="upload-single" name="image_3[0][]" accept="image/x-png,image/gif,image/jpeg" id="image_3_0">
															<label class="custom-file-label" for="image_3_0">Choose image 3 (optional)</label>
														</div>
														<div class="custom-file">
															<input type="file" class="custom-file-input" data-image="upload-single" name="image_4[0][]" accept="image/x-png,image/gif,image/jpeg" id="image_4_0">
															<label class="custom-file-label" for="image_4_0">Choose image 4 (optional)</label>
														</div>
													</div>
													<div class="image-preview-container d-flex flex-wrap gap-2 mt-2">
														<div role="image" class="form-image-preview wh-70 main-image"></div>
														<div id="additional-images-preview" class="d-flex flex-wrap gap-2"></div>
													</div>
												</div>
												<small class="text-muted">Upload 1 gambar utama + maksimal 4 gambar tambahan</small>
												<small class="text-danger" data-field="image"></small>
											</div>
											
											<div class="form-group">
												<label><i class="fa fa-fw fa-heading fa-sm mr-1"></i> Title</label>
												<input type="text" role="title" class="form-control" name="title[0][]" placeholder="Insert product title" required="required" />
											</div>
											
											<div class="form-group">
												<label><i class="fa fa-fw fa-paragraph fa-sm mr-1"></i> Short Description <small>(Opsional)</small></label>
												<textarea class="form-control" role="description" name="description[0][]" rows="2" placeholder="Short description for product card"></textarea>
											</div>
											
											<div class="form-group">
												<label><i class="fa fa-fw fa-align-left fa-sm mr-1"></i> Detailed Description <small>(Opsional)</small></label>
												<textarea class="form-control" role="detailed_description" name="detailed_description[0][]" rows="4" placeholder="Detailed description for product popup"></textarea>
												<small class="text-muted">Deskripsi panjang yang akan ditampilkan di popup detail produk</small>
											</div>
											
											<div class="row">
												<div class="col-lg-6">
													<div class="form-group">
														<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> Price</label>
														<input type="number" min="1" role="price" class="form-control" name="price[0][]" placeholder="Price..." required/>
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> Price Strike <small>(Opsional)</small></label>
														<input type="number" min="1" role="price_strike" class="form-control" name="price_strike[0][]" placeholder="Price Strike..." />
													</div>
												</div>
											</div>
											
											<div class="form-group">
												<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> Weight <small>(Gram)</small></label>
												<input type="number" min="1" role="price" class="form-control" name="weight[0][]" placeholder="Ex: 100" required/>
											</div>
											
											<div class="form-group">
												<label><i class="fa fa-fw fa-eye fa-sm mr-1"></i> Show Product</label>
												<select class="form-control" role="show" name="show[0][]">
												<option value="1">Show</option>
												<option value="0">Hide</option>
												</select>
											</div>
											
											<div class="form-group">
												<label><i class="fa fa-fw fa-shopping-cart fa-sm mr-1"></i> Button Text</label>
												<input type="text" class="form-control" name="button_text" value="Add to Cart" placeholder="Ex: Add to Cart, Beli Sekarang, etc." />
												<small class="text-muted">Custom text untuk tombol Add to Cart</small>
											</div>
											
											<div class="d-flex justify-content-center mb-2">
												<div data-eshop="psi" class="badge badge-primary badge-pill cursor-pointer p-2 pl-4 pr-4">Save</div>
											</div>
										
										</div>
									</div>
									
								</div>
								
							</div>
						
						</div>
					
					</div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-primary btn-spinner"><?= $this->language->create_biolink_text_modal->input->submit ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
	window.loadpage = false;
    $('form[name="create_biolink_eshop"]').on('submit', event => {
		if(!window.loadpage)
			window.loadpage = true;
		else
			event.preventDefault();
		
		
		
		$(event.currentTarget).find('.form-product-item .form-ctg-edit.show').find('[data-eshop="csp"]').click();
		$(event.currentTarget).find('.form-product-item .form-bag-edit.show').find('[data-eshop="psi"]').click();
        
		let form = $(event.currentTarget)[0];
        let data = new FormData(form);
        let notification_container = $(event.currentTarget).find('.notification-container');
		
		var txt_spin = $('.btn.btn-spinner').text()
		
		$('.btn.btn-spinner').text('Please wait, uploading...');
		
		event.preventDefault();
		
		$.ajax({
            type: 'POST',
            url: 'link-ajax',
			processData: false,
            contentType: false,
            cache: false,
            data: data,
            success: (data) => {
                if(data.status == 'error') {

                    let notification_container = $(event.currentTarget).find('.notification-container');

                    notification_container.html('');

                    display_notifications(data.message, 'error', notification_container);

                }

                else if(data.status == 'success') {

                    /* Fade out refresh */
                    fade_out_redirect({ url: data.details.url, full: true });

                }
            },
            dataType: 'json'
        });

        event.preventDefault();
    })
	
	// Handle main image upload preview
	$(document).on('change', 'input[data-image="upload"]', function() {
		var file = this.files[0];
		var container = $(this).closest('.multiple-image-upload').find('.main-image');
		var $this = $(this);
		var label = $this.siblings('.custom-file-label');
		
		if(file && file.type.startsWith('image/')) {
			// Update label text
			label.text(file.name);
			
			var reader = new FileReader();
			reader.onload = function(e) {
				container.css('background-image', 'url(' + e.target.result + ')');
			};
			reader.readAsDataURL(file);
		}
	});
	
	// Handle individual image upload preview
	$(document).on('change', 'input[data-image="upload-single"]', function() {
		var file = this.files[0];
		var container = $(this).closest('.multiple-image-upload').find('#additional-images-preview');
		var inputName = $(this).attr('name');
		var imageIndex = inputName.match(/image_(\d+)/);
		var $this = $(this);
		var label = $this.siblings('.custom-file-label');
		
		if(file && file.type.startsWith('image/')) {
			// Update only this specific label
			label.text(file.name);
			
			var reader = new FileReader();
			reader.onload = function(e) {
				// Remove existing preview for this image slot
				container.find('[data-slot="' + imageIndex[1] + '"]').remove();
				
				// Add new preview
				var img = $('<img>').attr({
					'src': e.target.result,
					'class': 'additional-image-preview',
					'data-slot': imageIndex[1],
					'style': 'width:60px;height:60px;object-fit:cover;border-radius:4px;border:1px solid #ddd;'
				});
				container.append(img);
			};
			reader.readAsDataURL(file);
		}
	});
	
	// Prevent Bootstrap from updating all labels
	$(document).on('change', '.custom-file-input', function() {
		var $this = $(this);
		var file = this.files[0];
		var label = $this.siblings('.custom-file-label');
		
		// Only update the label for this specific input
		if(file) {
			label.text(file.name);
		} else {
			// Reset to default text based on input name
			var inputName = $this.attr('name');
			if(inputName.includes('image_main')) {
				label.text('Choose main image');
			} else if(inputName.includes('image_1')) {
				label.text('Choose image 1 (optional)');
			} else if(inputName.includes('image_2')) {
				label.text('Choose image 2 (optional)');
			} else if(inputName.includes('image_3')) {
				label.text('Choose image 3 (optional)');
			} else if(inputName.includes('image_4')) {
				label.text('Choose image 4 (optional)');
			}
		}
	});
	
	// Handle multiple image upload preview (for backward compatibility)
	$(document).on('change', 'input[data-image="upload-multiple"]', function() {
		var files = this.files;
		var container = $(this).closest('.multiple-image-upload').find('#additional-images-preview');
		container.empty();
		
		if(files.length > 4) {
			alert('Maksimal 4 gambar tambahan');
			return;
		}
		
		for(var i = 0; i < files.length; i++) {
			var file = files[i];
			if(file.type.startsWith('image/')) {
				var reader = new FileReader();
				reader.onload = function(e) {
					var img = $('<img>').attr({
						'src': e.target.result,
						'class': 'additional-image-preview',
						'style': 'width:60px;height:60px;object-fit:cover;border-radius:4px;border:1px solid #ddd;'
					});
					container.append(img);
				};
				reader.readAsDataURL(file);
			}
		}
	});
	
	// Add custom CSS to prevent Bootstrap interference
	$('<style>')
		.prop('type', 'text/css')
		.html(`
			.custom-file-input:focus ~ .custom-file-label {
				border-color: #80bdff;
				box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
			}
			.custom-file-input:lang(en) ~ .custom-file-label::after {
				content: "Browse";
			}
			.custom-file-label {
				position: absolute;
				top: 0;
				right: 0;
				left: 0;
				z-index: 1;
				height: calc(1.5em + 0.75rem + 2px);
				padding: 0.375rem 0.75rem;
				line-height: 1.5;
				color: #495057;
				background-color: #fff;
				border: 1px solid #ced4da;
				border-radius: 0.25rem;
			}
			.custom-file-label::after {
				position: absolute;
				top: 0;
				right: 0;
				bottom: 0;
				z-index: 3;
				display: block;
				height: calc(1.5em + 0.75rem);
				padding: 0.375rem 0.75rem;
				line-height: 1.5;
				color: #495057;
				content: "Browse";
				background-color: #e9ecef;
				border-left: inherit;
				border-radius: 0 0.25rem 0.25rem 0;
			}
		`)
		.appendTo('head');
	
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
