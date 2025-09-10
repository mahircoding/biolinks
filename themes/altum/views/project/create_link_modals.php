<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_link" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->create_link_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <p class="text-muted modal-subheader"><?= $this->language->create_link_modal->subheader ?></p>

            <div class="modal-body">
                <form name="create_link" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="project_id" value="<?= $data->project->project_id ?>" />
                    <input type="hidden" name="type" value="link" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-signature fa-sm mr-1"></i> <?= $this->language->create_link_modal->input->location_url ?></label>
                        <input type="text" class="form-control" name="location_url" required="required" placeholder="<?= $this->language->create_link_modal->input->location_url_placeholder ?>" />
                    </div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-link"></i> <?= $this->language->create_link_modal->input->url ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <?php if(count($data->domains)): ?>
                                    <select name="domain_id" class="appearance-none select-custom-altum form-control input-group-text">
                                        <option value=""><?= url() ?></option>
                                        <?php foreach($data->domains as $row): ?>
                                        <option value="<?= $row->domain_id ?>"><?= $row->url ?></option>
                                        <?php endforeach ?>
                                    </select>
                                <?php elseif(parse_url(url())['host'] == global_whitelabel('url')): ?>
                                <select name="domain_id" class="appearance-none select-custom-altum form-control input-group-text">
                                    <option value="<?= link_global_whitelabel('domain_id') ?>"><?= url() ?></option>
                                </select>
                                <?php else: ?>
                                    <span class="input-group-text"><?= url() ?></span>
                                <?php endif ?>
                            </div>
                            <input type="text" class="form-control" name="url" placeholder="<?= $this->language->create_link_modal->input->url_placeholder ?>" />
                        </div>
                        <small class="text-muted"><?= $this->language->create_link_modal->input->url_help ?></small>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->create_link_modal->input->submit ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="create_biolink" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->create_biolink_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <p class="text-muted modal-subheader"><?= $this->language->create_biolink_modal->subheader ?></p>

            <div class="modal-body">
                <form name="create_biolink" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="project_id" value="<?= $data->project->project_id ?>" />
                    <input type="hidden" name="type" value="biolink" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-link"></i> <?= $this->language->create_biolink_modal->input->url ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <?php if(count($data->domains)): ?>
                                    <select name="domain_id" class="appearance-none select-custom-altum form-control input-group-text">
                                        <option value=""><?= url() ?></option>
                                        <?php foreach($data->domains as $row): ?>
                                            <option value="<?= $row->domain_id ?>"><?= $row->url ?></option>
                                        <?php endforeach ?>
                                    </select>
                                <?php elseif(parse_url(url())['host'] == global_whitelabel('url')): ?>
                                    <select name="domain_id" class="appearance-none select-custom-altum form-control input-group-text">
                                        <option value="<?= link_global_whitelabel('domain_id') ?>"><?= url() ?></option>
                                    </select>
                                <?php else: ?>
                                    <span class="input-group-text"><?= url() ?></span>
                                <?php endif ?>
                            </div>
                            <input type="text" class="form-control" name="url" placeholder="<?= $this->language->create_biolink_modal->input->url_placeholder ?>" />
                        </div>
                        <small class="text-muted"><?= $this->language->create_biolink_modal->input->url_help ?></small>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->create_biolink_modal->input->submit ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

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
                    <input type="hidden" name="link_id" value="" />
                    <input type="hidden" name="type" value="biolink" />
					<input type="hidden" name="subtype" value="duplicate" />

                    <div class="notification-container"></div>
					
					<div class="form-group">
                        <label><i class="fa fa-fw fa-link"></i> <?= $this->language->duplicate_biolink_modal->input->url ?></label>
                        <?php if(count($data->domains)): ?>
							<select name="domain_id" class="appearance-none select-custom-altum form-control input-group-text">
								<option value=""><?= url() ?></option>
								<?php foreach($data->domains as $row): ?>
									<option value="<?= $row->domain_id ?>"><?= $row->url ?></option>
								<?php endforeach ?>
							</select>
                        <?php elseif(parse_url(url())['host'] == global_whitelabel('url')): ?>
                            <select name="domain_id" class="appearance-none select-custom-altum form-control input-group-text">
                                <option value="<?= link_global_whitelabel('domain_id') ?>"><?= url() ?></option>
                            </select>
						<?php else: ?>
							<span class="input-group-text"><?= url() ?></span>
						<?php endif ?>
						<input type="text" class="form-control text-center mt-2" name="url" placeholder="<?= $this->language->duplicate_biolink_modal->input->url_placeholder ?>" />
                        <small class="text-muted"><?= $this->language->duplicate_biolink_modal->input->url_help ?></small>
                    </div>
					<style>.input-group-text{justify-content:center}</style>
					
					<?php if($data->projects_all): ?>
					<div class="form-group">
						<label><i class="fas fa-fw fa-align-center fa-sm mr-1"></i> <?= $this->language->duplicate_biolink_modal->input->duplicate_to ?></label>
						<select id="type_duplicate" class="form-control" name="type_duplicate">
						<?php foreach($data->projects_all as $pa): ?>
						<option value="<?= $pa->project_id ?>"<?= $pa->project_id==$data->project->project_id ? ' selected' : '' ?>><?= $pa->name ?></option>
						<?php endforeach?>
						</select>
					</div>
					<?php endif?>

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
    $('form[name="create_link"],form[name="create_biolink"],form[name="duplicate_biolink"]').on('submit', event => {

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
