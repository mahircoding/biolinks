<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-book text-gray-700"></i> <?= $this->language->admin_pages_category_update->header ?></h1>
</div>

<?php display_notifications() ?>

<div class="card border-0 shadow-sm my-5">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label><?= $this->language->admin_pages_categories->input->url ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?= SITE_URL . 'pages/' ?></span>
                            </div>

                            <input type="text" name="url" class="form-control form-control-lg" placeholder="<?= $this->language->admin_pages_categories->input->url_placeholder ?>" value="<?= $data->pages_category->url ?>" required="required" />
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label><?= $this->language->admin_pages_categories->input->title ?></label>
                        <input type="text" name="title" class="form-control form-control-lg" value="<?= $data->pages_category->title ?>" required="required" />
                    </div>
                </div>

                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label><?= $this->language->admin_pages_categories->input->description ?></label>
                        <input type="text" name="description" class="form-control form-control-lg" value="<?= $data->pages_category->description ?>" />
                    </div>
                </div>

                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label><?= $this->language->admin_pages_categories->input->icon ?></label>
                        <input type="text" name="icon" class="form-control form-control-lg" placeholder="<?= $this->language->admin_pages_categories->input->icon_placeholder ?>" value="<?= $data->pages_category->icon ?>" />
                        <small class="text-muted"><?= $this->language->admin_pages_categories->input->icon_help ?></small>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label><?= $this->language->admin_pages_categories->input->order ?></label>
                        <input type="number" name="order" class="form-control form-control-lg" value="<?= $data->pages_category->order ?>" />
                        <small class="text-muted"><?= $this->language->admin_pages_categories->input->order_help ?></small>
                    </div>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->update ?></button>
            </div>
        </form>

    </div>
</div>
