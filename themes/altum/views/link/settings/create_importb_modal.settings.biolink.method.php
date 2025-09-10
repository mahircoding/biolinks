<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_importb" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->create_biolink_importb_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <p class="text-muted modal-subheader"><?= $this->language->create_biolink_importb_modal->subheader ?></p>

            <div class="modal-body">
                <form name="create_biolink_importb" method="post" action="link-ajax" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="type" value="biolink" />
                    <input type="hidden" name="subtype" value="import" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-file-import fa-sm mr-1"></i> <?= $this->language->create_biolink_importb_modal->input->description ?></label>
						  <input type="file" class="form-control-file" name="json" id="importBiolink" accept="application/JSON">
					</div>
					
					<div class="custom-control custom-switch mr-3 mb-3">
						<input type="checkbox" class="custom-control-input" id="create_new_links" name="create_new_links" checked>
						<label class="custom-control-label clickable" for="create_new_links"><?= $this->language->create_biolink_importb_modal->input->create_new_links ?></label>
					</div>
					
					<div class="custom-control custom-switch mr-3 mb-3">
						<input type="checkbox" class="custom-control-input" id="overwrite_settings" name="overwrite_settings">
						<label class="custom-control-label clickable" for="overwrite_settings"><?= $this->language->create_biolink_importb_modal->input->overwrite_base_setting ?></label>
					</div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->create_biolink_importb_modal->input->submit ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<?php ob_start() ?>
<script>
    $('form[name="create_biolink_importb"]').on('submit', event => {
		let form = $(event.currentTarget)[0];
        let data = new FormData(form);
        let notification_container = $(event.currentTarget).find('.notification-container');
		
		$.ajax({
            type: 'POST',
			enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            url: 'link-ajax',
            data: data,
            success: (data) => {
                if(data.status == 'error') {

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
