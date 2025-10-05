<?php defined('ALTUMCODE') || die() ?>

<header class="header">
    <div class="container">

        <div class="row justify-content-between">
            <div class="col-12 col-md-6 col-xl-3 mb-3 mb-xl-0">
                <div class="card border-0 h-100">
                    <div class="card-body d-flex">

                        <div>
                            <div class="card border-0 bg-primary-200 text-primary-700 mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fa fa-fw fa-box-open fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="card-title h4 m-0"><?= $this->user->package->name ?></div>
                            <small class="text-muted"><?= $this->language->dashboard->header->package ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($this->user->package_id != 'free'): ?>
            <div class="col-12 col-md-6 col-xl-3 mb-3 mb-xl-0">
                <div class="card border-0 h-100">
                    <div class="card-body d-flex">

                        <div>
                            <div class="card border-0 bg-primary-200 text-primary-700 mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fa fa-fw fa-calendar fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="card-title h4 m-0"><?= \Altum\Date::get_time_until($this->user->package_expiration_date) ?></div>
                            <small class="text-muted"><?= $this->language->dashboard->header->package_expiration_date ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif ?>

            <?php if($this->user->type==1&&(is_null($data->license) ? -1 : $data->license)): ?>
            <div class="col-12 col-md-6 col-xl-3 mb-3 mb-xl-0">
                <div class="card border-0 h-100">
                    <div class="card-body d-flex">

                        <div>
                            <div class="card border-0 bg-primary-200 text-primary-700 mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fa fa-fw fa-certificate fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="card-title h4 m-0"><?= is_null($data->license)||$data->license==-1 ? 'Unlimited' : $data->license ?></div>
                            <small class="text-muted"><?= $this->language->admin_users->table->license ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif ?>

            <div class="col-12 col-md-6 col-xl-3 mb-3 mb-xl-0">
                <div class="card border-0 h-100">
                    <div class="card-body d-flex">

                        <div>
                            <div class="card border-0 bg-primary-200 text-primary-700 mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fa fa-fw fa-chart-bar fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="card-title h4 m-0"><?= nr($data->links_clicks_total) ?></div>
                            <small class="text-muted"><?= $this->language->dashboard->header->clicks ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3 mb-3 mb-xl-0">
                <div class="card border-0 h-100">
                    <div class="card-body d-flex">

                        <div>
                            <div class="card border-0 bg-primary-200 text-primary-700 mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fa fa-fw fa-link fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="card-title h4 m-0"><?= nr($data->links_total) ?></div>
                            <small class="text-muted"><?= $this->language->dashboard->header->links ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container">

    <?php display_notifications() ?>
	<!--
					<div class="alert alert-danger animated fadeInDown text-center">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						WAJIB klik tombol pengumuman dibawah ini. Agar Anda tidak ketinggalan info terbaru
					</div>
	<div class="d-flex justify-content-center">
		<a class="btn btn-warning rounded-pill animate-flicker mr-2" href="https://t.me/+UW44JWw4NjZj7-tF" target="_blank"><i class="far fa-address-book mr-1"></i> Pengumuman</a>
	</div>
	-->
    <div class="mt-4 d-flex justify-content-between">
        <h2 class="h4 flex-grow-1"><?= $this->language->dashboard->projects->header ?></h2>

        <div class="d-flex flex-wrap flex-row-reverse p-0">
			<?php if($this->user->package_settings->projects_limit != -1 && $data->projects_result->num_rows >= $this->user->package_settings->projects_limit): ?>
                <button type="button" data-confirm="<?= $this->language->project->error_message->projects_limit ?>"  class="btn btn-primary flex-end mb-1 ml-2 rounded-pill"><i class="fa fa-plus-circle"></i> <?= $this->language->dashboard->projects->create ?></button>
            <?php else: ?>
                <button type="button" data-toggle="modal" data-target="#create_project" class="btn btn-primary flex-end mb-1 ml-2 rounded-pill"><i class="fa fa-plus-circle"></i> <?= $this->language->dashboard->projects->create ?></button>
            <?php endif ?>
        </div>
    </div>
	<style>@keyframes fadeIn { from { opacity: 0; } }.animate-flicker { animation: fadeIn .75s infinite alternate; }</style>
    <?php if($data->projects_result->num_rows): ?>
        <p class="text-muted"><?= $this->language->dashboard->projects->subheader ?></p>

        <?php while($row = $data->projects_result->fetch_object()): ?>
            <?php

            /* Get some stats about the project */
            $row->statistics = $this->database->query("SELECT COUNT(*) AS `total`, SUM(`clicks`) AS `clicks` FROM `links` WHERE `project_id` = {$row->project_id}")->fetch_object();

            ?>
            <div class="d-flex custom-row align-items-center my-4" data-project-id="<?= $row->project_id ?>">
                <div class="col-6">
                    <div class="font-weight-bold text-truncate h6">
                        <a href="<?= url('project/' . $row->project_id) ?>"><?= $row->name ?></a>
                    </div>

                    <div class="text-muted d-flex align-items-center"><i class="fa fa-fw fa-calendar-alt fa-sm mr-1"></i> <?= \Altum\Date::get($row->date, 2) ?></div>
                </div>

                <div class="col-4 d-flex flex-column flex-lg-row justify-content-lg-around">
                    <div>
                        <span data-toggle="tooltip" title="<?= $this->language->project->links->total ?>" class="badge badge-info">
                            <i class="fa fa-fw fa-link mr-1"></i> <?= nr($row->statistics->total) ?>
                        </span>
                    </div>

                    <div>
                        <span data-toggle="tooltip" title="<?= $this->language->project->links->clicks ?>"class="badge badge-primary">
                            <i class="fa fa-fw fa-chart-bar mr-1"></i> <?= nr($row->statistics->clicks) ?>
                        </span>
                    </div>
                </div>

                <div class="col-2 d-flex justify-content-end">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                            <i class="fa fa-ellipsis-v"></i>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" data-toggle="modal" data-target="#project_update" data-project-id="<?= $row->project_id ?>" data-name="<?= $row->name ?>" class="dropdown-item"><i class="fa fa-fw fa-pencil-alt"></i> <?= $this->language->global->edit ?></a>
                                <a href="#" data-toggle="modal" data-target="#project_delete" data-project-id="<?= $row->project_id ?>" class="dropdown-item"><i class="fa fa-fw fa-times"></i> <?= $this->language->global->delete ?></a>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile ?>

    <?php else: ?>
        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_data.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->dashboard->projects->no_data ?>" />
            <h2 class="h4 text-muted"><?= $this->language->dashboard->projects->no_data ?></h2>
            <p><a href="#" data-toggle="modal" data-target="#create_project"><?= $this->language->dashboard->projects->no_data_help ?></a></p>
        </div>
    <?php endif ?>
</section>
<script>$(document).ready(function() {$('3132123').append('<div id="tutorial" class="modal fade justify-content-center"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Tutorial Biolink</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div><div class="modal-body text-center"><p>Klik tombol dibawah ini untuk melihat panduannya</p><a class="btn btn-success flex-end mt-2 mb-1 animate-flicker" href="https://linktutorial.my.id" target="_blank"><i class="far fa-address-book"></i> Tutorial Biolink</a></div></div></div></div>');setTimeout(function() {$("#tutorial").modal();},1000);});</script>
