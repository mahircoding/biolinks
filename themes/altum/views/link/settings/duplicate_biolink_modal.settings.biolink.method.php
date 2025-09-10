<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="duplicate_biolink" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->duplicate_biolink_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <p class="text-muted modal-subheader"><?= $this->language->duplicate_biolink_modal->subheader ?></p>
			
            <div class="modal-body">
                <form name="duplicate_biolink" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="type" value="biolink" />
					<input type="hidden" name="subtype" value="duplicate" />

                    <div class="notification-container"></div>
					
					<div class="form-group">
                        <label><i class="fa fa-fw fa-link"></i> <?= $this->language->duplicate_biolink_modal->input->url ?></label>
                        <span class="input-group-text"><?= url() ?></span>
						<input type="text" class="form-control text-center mt-2" name="url" placeholder="<?= $this->language->duplicate_biolink_modal->input->url_placeholder ?>" />
                        <small class="text-muted"><?= $this->language->duplicate_biolink_modal->input->url_help ?></small>
                    </div>
					<style>.input-group-text{justify-content:center}</style>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->duplicate_biolink_modal->input->submit ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    $('form[name="duplicate_biolink"]').on('submit', event => {

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
