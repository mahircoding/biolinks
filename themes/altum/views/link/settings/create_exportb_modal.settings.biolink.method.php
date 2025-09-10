<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_exportb" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->create_biolink_exportb_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <p class="text-muted modal-subheader"><?= $this->language->create_biolink_exportb_modal->subheader ?></p>

            <div class="modal-body">
                <form name="create_biolink_exportb" method="post" action="link-ajax" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="type" value="biolink" />
                    <input type="hidden" name="subtype" value="export" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-file-export fa-sm mr-1"></i> <?= $this->language->create_biolink_exportb_modal->input->description ?></label>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->create_biolink_exportb_modal->input->submit ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<?php ob_start() ?>
<script>
    $('form[name="create_biolink_exportb"]').on('submit', event => {

    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
