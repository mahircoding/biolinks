<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_sliders" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->create_biolink_sliders_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <p class="text-muted modal-subheader"><?= $this->language->create_biolink_sliders_modal->subheader ?></p>

            <div class="modal-body">
                <form name="create_biolink_sliders" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="type" value="biolink" />
                    <input type="hidden" name="subtype" value="sliders" />
					
                    <div class="notification-container"></div>
					
					<div class="d-flex align-items-center justify-content-center mt-2 mb-2">
						<div class="d-flex align-items-center">
							<div class="btn btn-primary" data-add-image="true" data-required="required"><i class="fa fa-image" aria-hidden="true"></i> Add Image</div>
						</div>
					</div>
					
					<div class="form-images-container">
						<div class="form-group">
							<div class="d-flex align-items-stretch">
								<div class="flex-grow-1">
									<label><i class="fas fa-fw fa-image fa-sm mr-1"></i> <?= $this->language->create_biolink_sliders_modal->input->image_url ?></label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" data-image="upload" name="images[]" accept="image/x-png,image/gif,image/jpeg" required>
										<label class="custom-file-label" for="customFile">Choose file</label>
									</div>
								</div>
								<div class="flex-grow-1 w-100 d-flex align-items-stretch mw-preview ml-2">
									<div class="form-image-preview"></div>
								</div>
							</div>
							<small class="text-danger" data-field="images.0"></small>
						</div>
						<div class="form-group">
							<div class="d-flex align-items-stretch">
								<div class="flex-grow-1">
									<label><i class="fas fa-fw fa-image fa-sm mr-1"></i> <?= $this->language->create_biolink_sliders_modal->input->image_url ?></label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" data-image="upload" name="images[]" accept="image/x-png,image/gif,image/jpeg" required>
										<label class="custom-file-label" for="customFile">Choose file</label>
									</div>
								</div>
								<div class="flex-grow-1 w-100 d-flex align-items-stretch mw-preview ml-2">
									<div class="form-image-preview"></div>
								</div>
							</div>
							<small class="text-danger" data-field="images.1"></small>
						</div>
					</div>
					
					<div class="form-group">
						<label><i class="fas fa-fw fa-snowflake fa-sm mr-1"></i> <?= $this->language->create_biolink_sliders_modal->input->animation ?></label>
						<select class="form-control" name="slider_animation">
						  <option value="left"><?= $this->language->create_biolink_sliders_modal->animation->left ?></option>
						  <option value="right"><?= $this->language->create_biolink_sliders_modal->animation->right ?></option>
						  <option value="top"><?= $this->language->create_biolink_sliders_modal->animation->top ?></option>
						  <option value="bottom"><?= $this->language->create_biolink_sliders_modal->animation->bottom ?></option>
						</select>
					</div>
					
					<div class="form-group">
						<label><?= $this->language->create_biolink_sliders_modal->input->timer ?> (Second)</label>
						<div data-range="main" data-gr-iframe-target="[data-link-id]" class="row">
							<div class="col-md-9 d-flex align-items-center">
								<input data-range="range" type="range" name="slider_timer" class="custom-range" min="0.5" step="0.5" max="10" value="3" required>
							</div>
							<div class="col-md-3">
								<input data-range="text" type="number" class="form-control" min="0.5" step="0.5" max="10" value="3">
							</div>
						</div>
					</div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->create_biolink_sliders_modal->input->submit ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<?php ob_start() ?>
<script>
    $('form[name="create_biolink_sliders"]').on('submit', event => {
		var ths = $(event.currentTarget);
		let form = ths[0];
        let data = new FormData(form);
        let notification_container = ths.find('.notification-container');
		
        $.ajax({
            type: 'POST',
            url: 'link-ajax',
            processData: false,
            contentType: false,
            cache: false,
            data: data,
            success: (data) => {
                if(data.status == 'error') {
					if(data.details=='form') {
						$.each(data.message, function(i,j) {	
							var html=''; var frm;
							$.each(j,function(k,l) {
								html += l;
							});
							$('[data-field="'+i+'"]').html(html);
						})
					} else {
						let notification_container = $(event.currentTarget).find('.notification-container');

						notification_container.html('');

						display_notifications(data.message, 'error', notification_container);
					}
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
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
