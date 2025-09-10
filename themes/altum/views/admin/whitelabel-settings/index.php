<?php use Altum\Database\Database;;defined('ALTUMCODE') || die() ?>
<div class="d-flex">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-wrench text-gray-700"></i> <?= $this->language->admin_settings->header ?></h1>
</div>
<?php display_notifications() ?>
<div class="row mt-5">
    <div class="mb-5 mb-lg-0 col-12 col-lg-3">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" href="#main" data-toggle="pill" role="tab"><i class="fa fa-fw fa-home fa-sm mr-1"></i> <?= $this->language->admin_settings->tab->main ?></a>
        </div>
    </div>

    <div class="col order-1 order-lg-0">
        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <form action="" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="main">
                            <div class="form-group">
                                <label><i class="fa fa-fw fa-heading fa-sm mr-1 text-muted"></i> <?= $this->language->admin_settings->main->title ?></label>
                                <input type="text" name="title" class="form-control" value="<?= $data->whitelabel ? $data->whitelabel->title : '' ?>" />
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-fw fa-language fa-sm mr-1 text-muted"></i> <?= $this->language->admin_settings->main->default_language ?></label>
                                <select name="default_language" class="form-control">
                                    <?php foreach(\Altum\Language::$languages as $value) echo '<option value="' . $value . '" ' . (($this->settings->default_language == $value) ? 'selected' : null) . '>' . $value . '</option>' ?>
                                </select>
                                <small class="text-muted"><?= $this->language->admin_settings->main->default_language_help ?></small>
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-fw fa-eye fa-sm mr-1 text-muted"></i> <?= $this->language->admin_settings->main->logo ?></label>
                                <?php if($data->whitelabel&&$data->whitelabel->logo): ?>
                                    <div class="m-1">
                                        <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'whitelabel/logo/' . $data->whitelabel->logo; ?>" class="img-fluid navbar-logo" />
                                    </div>
                                <?php endif ?>
                                <input id="logo-file-input" type="file" name="logo" class="form-control" />
                                <small class="text-muted"><?= $this->language->admin_settings->main->logo_help ?></small>
                                <small class="text-muted"><a href="whitelabel/whitelabel-settings/removelogo<?= \Altum\Middlewares\Csrf::get_url_query() ?>"><?= $this->language->admin_settings->main->logo_remove ?></a></small>
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-fw fa-icons fa-sm mr-1 text-muted"></i> <?= $this->language->admin_settings->main->favicon ?></label>
                                <?php if($data->whitelabel&&$data->whitelabel->favicon): ?>
                                    <div class="m-1">
                                        <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'whitelabel/favicon/' . $data->whitelabel->favicon; ?>" class="img-fluid" />
                                    </div>
                                <?php endif ?>
                                <input id="favicon-file-input" type="file" name="favicon" class="form-control" />
                                <small class="text-muted"><?= $this->language->admin_settings->main->favicon_help ?></small>
                                <small class="text-muted"><a href="whitelabel/whitelabel-settings/removefavicon<?= \Altum\Middlewares\Csrf::get_url_query() ?>"><?= $this->language->admin_settings->main->favicon_remove ?></a></small>
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-fw fa-atlas fa-sm mr-1 text-muted"></i> <?= $this->language->admin_settings->main->default_timezone ?></label>
                                <select name="default_timezone" class="form-control">
                                    <?php foreach(DateTimeZone::listIdentifiers() as $timezone) echo '<option value="' . $timezone . '" ' . (($this->settings->default_timezone == $timezone) ? 'selected' : null) . '>' . $timezone . '</option>' ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-fw fa-sitemap fa-sm mr-1 text-muted"></i> <?= $this->language->admin_settings->main->index_url ?></label>
                                <input type="text" name="index_url" class="form-control" value="<?= $data->whitelabel ? $data->whitelabel->index_url : '' ?>" />
                                <small class="text-muted"><?= $this->language->admin_settings->main->index_url_help ?></small>
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-fw fa-file-word fa-sm mr-1 text-muted"></i> <?= $this->language->admin_settings->main->terms_and_conditions_url ?></label>
                                <input type="text" name="terms_and_conditions_url" class="form-control" value="<?= $data->whitelabel ? $data->whitelabel->terms_url : '' ?>" />
                                <small class="text-muted"><?= $this->language->admin_settings->main->terms_and_conditions_url_help ?></small>
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-fw fa-file-word fa-sm mr-1 text-muted"></i> <?= $this->language->admin_settings->main->privacy_policy_url ?></label>
                                <input type="text" name="privacy_policy_url" class="form-control" value="<?= $data->whitelabel ? $data->whitelabel->privacy_url : '' ?>" />
                                <small class="text-muted"><?= $this->language->admin_settings->main->privacy_policy_url_help ?></small>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->update ?></button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


<?php ob_start() ?>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
