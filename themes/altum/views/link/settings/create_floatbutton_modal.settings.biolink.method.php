<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_floatbutton" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->create_biolink_floatbutton_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <p class="text-muted modal-subheader"><?= $this->language->create_biolink_floatbutton_modal->subheader ?></p>

            <div class="modal-body">
                <form name="create_biolink_floatbutton" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="type" value="biolink" />
                    <input type="hidden" name="subtype" value="floatbutton" />

                    <div class="notification-container"></div>
					
					<div class="d-flex align-items-center justify-content-center mt-2 mb-2">
						<div class="d-flex align-items-center">
							<div class="btn btn-primary" data-add-floating="true" data-required="required"><i class="fa fa-image" aria-hidden="true"></i> Add Button</div>
						</div>
					</div>
					
					<div class="form-floating-button-container">
						<div class="form-floating-button-item">
							<div class="form-group">
								<div class="d-flex justify-content-between">
									<label><i class="fa fa-fw fa-globe fa-sm mr-1"></i> <?= $this->language->create_biolink_floatbutton_modal->input->icon_button ?></label>
									<div></div>
								</div>
								<div class="input-group">
									<span class="input-group-prepend">
										<button class="btn btn-secondary" data-icon="" data-iconset="fontawesome5" role="iconpicker"></button>
									</span>

									<input type="text" name="icon[]" class="form-control" value="" placeholder="<?= $this->language->create_biolink_floatbutton_modal->input->icon_button_placeholder ?>" />
								</div>
								<small class="text-muted"><?= $this->language->create_biolink_link_modal->input->icon_help ?></small>
							</div>
							
							<div data-color-picker="true" data-index="0" class="form-group">
								<label for="settings_background_color"><?= $this->language->create_biolink_floatbutton_modal->input->background_color ?></label>
								<input data-picker="val" type="hidden" name="background[]" class="form-control" value="#fd4235" />
								<div id="settings_background_color_float_pickr_0" data-picker="color"></div>
							</div>
							
							<div data-color-picker="true" data-index="0" class="form-group">
								<label for="settings_text_color"><?= $this->language->create_biolink_floatbutton_modal->input->text_color ?></label>
								<input data-picker="val" type="hidden" name="text[]" class="form-control" value="#ffffff" />
								<div id="settings_text_color_float_pickr_0" data-picker="color"></div>
							</div>
							
							<div class="form-group">
								<label><i class="fa fa-fw fa-signature fa-sm mr-1"></i> <?= $this->language->create_biolink_floatbutton_modal->input->link_url ?></label>
								<input type="text" class="form-control" name="link_url[]" required="required" placeholder="<?= $this->language->create_biolink_floatbutton_modal->input->link_url_placeholder ?>" />
							</div>
							
							<div class="form-group">
								<label><i class="fa fa-fw fa-signature fa-sm mr-1"></i> <?= $this->language->create_biolink_floatbutton_modal->input->link_title ?></label>
								<input type="text" class="form-control" name="link_title[]" required="required" placeholder="<?= $this->language->create_biolink_floatbutton_modal->input->link_title_placeholder ?>" />
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label><i class="fas fa-fw fa-snowflake fa-sm mr-1"></i> <?= $this->language->create_biolink_floatbutton_modal->input->position ?></label>
						<select class="form-control" name="position">
						  <option value="left"><?= $this->language->create_biolink_floatbutton_modal->position->left ?></option>
						  <option value="center"><?= $this->language->create_biolink_floatbutton_modal->position->center ?></option>
						  <option value="right"><?= $this->language->create_biolink_floatbutton_modal->position->right ?></option>
						</select>
					</div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->create_biolink_floatbutton_modal->input->submit ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>.form-floating-button-item{padding:.5rem .75rem .75rem;background-color:#f8f8f8;border-radius:5px;margin-bottom:1rem;}.form-floating-button-item .form-group:last-child{margin-bottom:.5rem}</style>

<?php ob_start() ?>
<script>
    $('form[name="create_biolink_floatbutton"]').on('submit', event => {

        $.ajax({
            type: 'POST',
            url: 'link-ajax',
            data: $(event.currentTarget).serialize(),
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
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
