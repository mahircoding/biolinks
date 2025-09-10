<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="domain_create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->domain_create_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php $url = parse_url(SITE_URL); $host = $url['host'] . (strlen($url['path']) > 1 ? $url['path'] : null); ?>

            <!-- <p class="text-muted modal-subheader">Sebelum menggunakan fitur ini, <strong>WAJIB</strong> baca dulu panduannya. Silahkan <a href="https://id.suizen.id/tutorial-biolink" target="_blank"><strong>KLIK DISINI</strong></a> utk Baca Panduannya</p> -->

            <div class="modal-body">
                <form name="domain_create" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-network-wired mr-1"></i> <?= $this->language->domains->input->host ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select name="scheme" class="appearance-none select-custom-altum form-control form-control-lg input-group-text">
                                    <option value="https://">https://</option>
                                    <option value="http://">http://</option>
                                </select>
                            </div>

                            <input type="text" class="form-control form-control-lg" name="host" placeholder="<?= $this->language->domains->input->host_placeholder ?>" required="required" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-network-wired mr-1"></i> Index Domain</label>
                        <input type="text" class="form-control form-control-lg" name="index_url" placeholder="Destination URL ex: facebook.com/fulan" />
                    </div>
                    <small class="text-muted"><i class="fa fa-fw fa-info-circle"></i> <?= $this->language->domains->input->host_help ?></small>
                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->create ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    $('form[name="domain_create"]').on('submit', event => {

        $.ajax({
            type: 'POST',
            url: 'domains/create',
            data: $(event.currentTarget).serialize(),
            success: (data) => {
                if (data.status == 'error') {
                    let notification_container = $(event.currentTarget).find('.notification-container');

                    notification_container.html('');

                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    /* Hide modal */
                    $('#domain_create').modal('hide');

                    /* Clear input values */
                    $('form[name="domain_create"] input').val('');

                    /* Fade out refresh */
                    redirect(`domains`);

                }
            },
            dataType: 'json'
        });

        event.preventDefault();
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
