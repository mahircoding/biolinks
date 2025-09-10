<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_picture" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->create_biolink_picture_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <p class="text-muted modal-subheader"><?= $this->language->create_biolink_picture_modal->subheader ?></p>

            <div class="modal-body">
                <form name="create_biolink_picture" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="type" value="biolink" />
                    <input type="hidden" name="subtype" value="picture" />

                    <div class="notification-container"></div>

                    <div class="form-group">
						<div class="d-flex align-items-stretch">
							<div class="flex-grow-1">
								<label><i class="fas fa-fw fa-image fa-sm mr-1"></i> <?= $this->language->create_biolink_picture_modal->input->picture_url ?></label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" data-image="upload" name="image" accept="image/x-png,image/gif,image/jpeg">
									<label class="custom-file-label" for="customFile">Choose file</label>
								</div>
							</div>
							<div class="flex-grow-1 w-100 d-flex align-items-stretch mw-preview ml-2">
								<div class="form-image-preview"></div>
							</div>
						</div>
						<small class="text-danger" data-field="image"></small>
					</div>
					
					<div class="form-group">
						<label><i class="fa fa-fw fa-signature fa-sm mr-1"></i> <?= $this->language->create_biolink_picture_modal->input->link_url ?></label>
						<input type="text" class="form-control" name="link_url" placeholder="<?= $this->language->create_biolink_picture_modal->input->link_url_placeholder ?>" />
					</div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->create_biolink_picture_modal->input->submit ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<?php ob_start() ?>
<script>
    $('form[name="create_biolink_picture"]').on('submit', event => {
		let form = $(event.currentTarget)[0];
        let data = new FormData(form);
        let notification_container = $(event.currentTarget).find('.notification-container');
		
        $.ajax({
            type: 'POST',
            url: 'link-ajax',
			processData: false,
            contentType: false,
            cache: false,
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
