<?php defined('ALTUMCODE') || die() ?>

<header class="header pb-0">
    <div class="container">
        <?= $this->views['account_header'] ?>
    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container pt-5">

    <?php display_notifications() ?>

    <div class="d-flex justify-content-between">
        <h2 class="h4"><?= $this->language->domains->header ?></h2>
		
		<?php /*?>
        <div class="col-auto p-0">
            <?php if($this->user->package_settings->domains_limit != -1 && $data->domains_result->num_rows >= $this->user->package_settings->domains_limit): ?>
                <button type="button" data-confirm="<?= $this->language->domains->error_message->domains_limit ?>"  class="btn btn-primary rounded-pill"><i class="fa fa-plus-circle"></i> <?= $this->language->global->create ?></button>
            <?php else: ?>
                <button type="button" data-toggle="modal" data-target="#domain_create" class="btn btn-primary rounded-pill"><i class="fa fa-plus-circle"></i> <?= $this->language->global->create ?></button>
            <?php endif ?>
        </div>
		<?php */?>
    </div>
	
	
	<div class="d-flex custom-row flex-column align-items-center my-4" data-domain-id="<?= $row->domain_id ?>">
		Jika anda ingin menggunakan domain sendiri, silahkan hubungi Whatsapp admin di <br><a href="https://wa.me/6283823723342" class="text-success" target="_blank">https://wa.me/6283823723342</a> <br>dan infokan nama domain yang ingin dipakai.
	</div>
	
    <?php if($data->domains_result->num_rows): ?>
        <p class="text-muted"><?= $this->language->domains->subheader ?></p>

        <?php while($row = $data->domains_result->fetch_object()): ?>
            <?php

            /* Get some stats about the domain */
            $row->statistics = $this->database->query("SELECT COUNT(*) AS `total`, SUM(`clicks`) AS `clicks` FROM `links` WHERE `domain_id` = {$row->domain_id}")->fetch_object();

            ?>
            <div class="d-flex custom-row align-items-center my-4" data-domain-id="<?= $row->domain_id ?>">
                <div class="col-6">
                    <div class="font-weight-bold text-truncate h6">
                        <img src="https://www.google.com/s2/favicons?domain=<?= $row->host ?>" class="img-fluid mr-1" />
                        <span class="align-middle"><?= $row->host ?></span>
						<?php if($row->is_active):?>
						<div class="badge badge-success" data-toggle="tooltip" title="This domain is online">Online</div>
						<?php endif;?>
                    </div>

                    <div class="text-muted d-flex align-items-center"><i class="fa fa-fw fa-calendar-alt fa-sm mr-1"></i> <?= \Altum\Date::get($row->date, 2) ?></div>
                </div>

                <div class="col-4 d-flex flex-column flex-lg-row justify-content-lg-around">
                    <div>
                        <span data-toggle="tooltip" title="<?= $this->language->domains->domains->total ?>" class="badge badge-info">
                            <i class="fa fa-fw fa-link mr-1"></i> <?= nr($row->statistics->total) ?>
                        </span>
                    </div>

                    <div>
                        <span data-toggle="tooltip" title="<?= $this->language->domains->domains->clicks ?>"class="badge badge-primary">
                            <i class="fa fa-fw fa-chart-bar mr-1"></i> <?= nr($row->statistics->clicks) ?>
                        </span>
                    </div>
                </div>

                <div class="col-2 d-flex justify-content-end">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                            <i class="fa fa-ellipsis-v"></i>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" data-toggle="modal" data-target="#domain_update" data-domain-id="<?= $row->domain_id ?>" data-scheme="<?= $row->scheme ?>" data-host="<?= $row->host ?>"  data-index_url="<?= $row->index_url ?>"class="dropdown-item"><i class="fa fa-fw fa-pencil-alt"></i> <?= $this->language->global->edit ?></a>
                                <a href="#" data-toggle="modal" data-target="#domain_delete" data-domain-id="<?= $row->domain_id ?>" class="dropdown-item"><i class="fa fa-fw fa-times"></i> <?= $this->language->global->delete ?></a>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile ?>

    <?php else: ?>
        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_data.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->domains->domains->no_data ?>" />
            <h2 class="h4 text-muted"><?= $this->language->domains->domains->no_data ?></h2>
            <p><a href="#" data-toggle="modal" data-target="#create_domain"><?= $this->language->domains->domains->no_data_help ?></a></p>
        </div>
    <?php endif ?>
</section>



