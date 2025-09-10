<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-file-alt text-gray-700"></i> <?= $this->language->admin_page_create->header ?></h1>
</div>

<div class="card border-0 shadow-sm my-5">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label><?= $this->language->admin_pages->input->type ?></label>
                        <select class="form-control form-control-lg" name="type">
                            <option value="internal"><?= $this->language->admin_pages->input->type_internal ?></option>
                            <option value="external"><?= $this->language->admin_pages->input->type_external ?></option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label id="url_label"><?= $this->language->admin_pages->input->url_internal ?></label>
                        <div class="input-group">
                            <div id="url_prepend" class="input-group-prepend">
                                <span class="input-group-text"><?= SITE_URL . 'page/' ?></span>
                            </div>

                            <input type="text" name="url" class="form-control form-control-lg" placeholder="<?= $this->language->admin_pages->input->url_internal_placeholder ?>" value="" required="required" />
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label><?= $this->language->admin_pages->input->title ?></label>
                        <input type="text" name="title" class="form-control form-control-lg" value="" />
                    </div>
                </div>

                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label><?= $this->language->admin_pages->input->description ?></label>
                        <input type="text" name="description" class="form-control form-control-lg" value="" />
                    </div>
                </div>

                <div class="col-sm-12 col-md-4">
                    <div class="form-group">
                        <label><?= $this->language->admin_pages->input->position ?></label>
                        <select class="form-control form-control-lg" name="position">
                            <option value="bottom"><?= $this->language->admin_pages->input->position_bottom ?></option>
                            <option value="top"><?= $this->language->admin_pages->input->position_top ?></option>
                            <option value="hidden"><?= $this->language->admin_pages->input->position_hidden ?></option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4">
                    <div class="form-group">
                        <label><?= $this->language->admin_pages->input->pages_category_id ?></label>
                        <select name="pages_category_id" class="form-control form-control-lg">
                            <?php while($row = $data->pages_categories_result->fetch_object()): ?>
                                <option value="<?= $row->pages_category_id ?>"><?= $row->title ?></option>
                            <?php endwhile ?>

                            <option value=""><?= $this->language->admin_pages->input->pages_category_id_null ?></option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4">
                    <div class="form-group">
                        <label><?= $this->language->admin_pages->input->order ?></label>
                        <input type="number" name="order" class="form-control form-control-lg" value="0" />
                        <small class="text-muted"><?= $this->language->admin_pages->input->order_help ?></small>
                    </div>
                </div>

                <div id="description_container" class="col-12">
                    <div class="form-group">
                        <label><?= $this->language->admin_pages->input->content ?></label>
                        <textarea id="content" name="content" class="form-control form-control-lg"></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->create ?></button>
            </div>
        </form>

    </div>
</div>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/tinymce/tinymce.min.js' ?>"></script>
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'code preview fullpage autolink directionality visualblocks visualchars fullscreen image link media codesample table hr pagebreak nonbreaking toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent | removeformat code',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html(<?= json_encode($this->language->admin_pages->input->url_internal) ?>);
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', <?= json_encode($this->language->admin_pages->input->url_internal_placeholder) ?>);
                $('#description_container').show();

                break;

            case 'external':

                $('#url_label').html(<?= json_encode($this->language->admin_pages->input->url_external) ?>);
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', <?= json_encode($this->language->admin_pages->input->url_external_placeholder) ?>);
                $('#description_container').hide();

                break;
        }

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
