<?php defined('ALTUMCODE') || die();
if($this->user->superagency == 'Y')
    $urladmin = 'superagency';
elseif($this->user->agency == 'Y')
    $urladmin = 'agency';
elseif($this->user->subagency == 'Y')
    $urladmin = 'subagency';
elseif($this->user->whitelabel == 'Y')
    $urladmin = 'whitelabel';
else
    $urladmin = 'admin';
?>

<div class="mb-5 row justify-content-between">
    <div class="col-6 col-md-3 mb-4">
        <div class="card d-flex flex-row shadow-sm border-0 h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><?= $this->language->admin_index->display->id_user ?></small>

                <div class="mt-3"><i class="fa fa-fw fa-chart-line"></i> <span class="h4"><?= nr($data->id_user) ?></span></div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-md-3 mb-4">
        <div class="card d-flex flex-row shadow-sm border-0 h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><?= $this->language->admin_index->display->license ?></small>
                
                <div class="mt-3"><i class="fa fa-fw fa-chart-line"></i> <span class="h4"><?= is_null($data->license)||$data->license==-1 ? ' <span class="badge badge-success">Unlimited</span>' : ' <span class="badge badge-success">'.$data->license.'</span>' ?></span></div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 mb-4">
        <div class="card d-flex flex-row shadow-sm border-0 h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><?= $this->language->admin_index->display->clicks_month ?></small>

                <div class="mt-3"><i class="fa fa-fw fa-chart-line"></i> <span class="h4"><?= nr($data->links->clicks_month) ?></span></div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 mb-4">
        <div class="card d-flex flex-row shadow-sm border-0 h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><?= $this->language->admin_index->display->active_users_month ?></small>

                <div class="mt-3"><i class="fa fa-fw fa-users"></i> <span class="h4"><?= nr($data->users->active_users_month) ?></span></div>
            </div>
        </div>
    </div>

    <?php if($this->settings->payment->is_enabled): ?>
        <div class="col-6 col-md-3 mb-4">
            <div class="card d-flex flex-row shadow-sm border-0 h-100 overflow-hidden">
                <div class="card-body">
                    <small class="text-muted"><?= $this->language->admin_index->display->payments_month ?></small>

                    <div class="mt-3"><i class="fa fa-fw fa-funnel-dollar"></i> <span class="h4"><?= nr($data->payments_month->payments) ?></span></div>
                </div>
            </div>
        </div>
    <?php endif ?>

    <?php if($this->settings->payment->is_enabled): ?>
        <div class="col-6 col-md-3 mb-4">
            <div class="card d-flex flex-row shadow-sm border-0 h-100 overflow-hidden">
                <div class="card-body">
                    <small class="text-muted"><?= $this->language->admin_index->display->earnings_month ?></small>

                    <div class="mt-3"><span class="h4"><?= $data->payments_month->earnings ?></span> <small><?= $this->settings->payment->currency ?></small></div>
                </div>
            </div>
        </div>
    <?php endif ?>

    <div class="col-6 col-md-3 mb-4">
        <div class="card d-flex flex-row shadow-sm border-0 h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><?= $this->language->admin_index->display->clicks ?></small>

                <div class="mt-3"><i class="fa fa-fw fa-chart-line"></i> <span class="h4"><?= nr($data->links->clicks) ?></span></div>
            </div>

            <div class="bg-gray-300 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/links') ?>">
                    <i class="fa fa-fw fa-arrow-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 mb-4">
        <div class="card d-flex flex-row shadow-sm border-0 h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><?= $this->language->admin_index->display->active_users ?></small>

                <div class="mt-3"><i class="fa fa-fw fa-users"></i> <span class="h4"><?= nr($data->users->active_users) ?></span></div>
            </div>

            <div class="bg-gray-300 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/users') ?>">
                    <i class="fa fa-fw fa-arrow-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <?php if($this->settings->payment->is_enabled): ?>
        <div class="col-6 col-md-3 mb-4">
            <div class="card d-flex flex-row shadow-sm border-0 h-100 overflow-hidden">
                <div class="card-body">
                    <small class="text-muted"><?= $this->language->admin_index->display->payments ?></small>

                    <div class="mt-3"><i class="fa fa-fw fa-funnel-dollar"></i> <span class="h4"><?= nr($data->payments->payments) ?></span></div>
                </div>

                <div class="bg-gray-300 px-2 d-flex flex-column justify-content-center">
                    <a href="<?= url('admin/payments') ?>">
                        <i class="fa fa-fw fa-arrow-right text-gray-500"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 mb-4">
            <div class="card d-flex flex-row shadow-sm border-0 h-100 overflow-hidden">
                <div class="card-body">
                    <small class="text-muted"><?= $this->language->admin_index->display->earnings ?></small>

                    <div class="mt-3"><span class="h4"><?= $data->payments->earnings ?></span> <small><?= $this->settings->payment->currency ?></small></div>
                </div>

                <div class="bg-gray-300 px-2 d-flex flex-column justify-content-center">
                    <a href="<?= url('admin/payments') ?>">
                        <i class="fa fa-fw fa-arrow-right text-gray-500"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>

<div class="mb-5">
    <h1 class="h3"><?= $this->language->admin_index->users->header ?></h1>

    <?php
    $ids = $this->user->user_id; 
    if($this->user->superagency == "Y" || $this->user->agency == "Y" || $this->user->subagency == "Y" || $this->user->whitelabel == "Y"){
        $result = \Altum\Database\Database::$database->query("SELECT `user_id`, `name`, `email`, `active`, `date` FROM `users` where ids_insert = $ids ORDER BY `user_id` DESC LIMIT 5");
    }
    else{
        $result = \Altum\Database\Database::$database->query("SELECT `user_id`, `name`, `email`, `active`, `date` FROM `users` ORDER BY `user_id` DESC LIMIT 5");
    }
    ?>
    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= $this->language->admin_index->users->user ?></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php $cnt=0; while($row = $result->fetch_object()): ?>
                <tr>
                    <td>
                        <div class="d-flex">
                            <img src="<?= get_gravatar($row->email) ?>" class="user-avatar rounded-circle mr-3" alt="" />

                            <div class="d-flex flex-column">
                                <?= '<a href="' . url($urladmin.'/user-view/' . $row->user_id) . '">' . $row->name . '</a>' ?>

                                <span class="text-muted"><?= $row->email ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-column">
                                <div><?= $row->active ? '<span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> ' . $this->language->global->active . '</span>' : '<span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> ' . $this->language->global->disabled . '</span>' ?></div>
                                <div><small class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->date, 1) ?>"><?= \Altum\Date::get($row->date, 2) ?></small></div>
                            </div>
                        </div>
                    </td>
                    <td><?= get_admin_options_button('user', $row->user_id, $urladmin) ?></td>
                </tr>
            <?php $cnt++; endwhile ?>
            <?php if($cnt==0) {?>
                <tr><td colspan="3" class="text-center">No User Data</td></tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>


<?php $result = \Altum\Database\Database::$database->query("SELECT `payments`.*, `users`.`name` AS `user_name` FROM `payments` LEFT JOIN `users` ON `payments`.`user_id` = `users`.`user_id` ORDER BY `id` DESC LIMIT 5"); ?>
<?php if($result&&$result->num_rows): ?>
    <div class="mb-5">
        <h1 class="h3"><?= $this->language->admin_index->payments->header ?></h1>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= $this->language->admin_index->payments->payment ?></th>
                    <th><?= $this->language->admin_index->payments->user ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php while($row = $result->fetch_object()): ?>

                    <?php
                    switch($row->processor) {
                        case 'STRIPE':
                            $row->processor = '<span data-toggle="tooltip" title="' . $this->language->admin_payments->table->stripe .'"><i class="fab fa-fw fa-stripe icon-stripe"></i></span>';
                            break;

                        case 'PAYPAL':
                            $row->processor = '<span data-toggle="tooltip" title="' . $this->language->admin_payments->table->paypal .'"><i class="fab fa-fw fa-paypal icon-paypal"></i></span>';
                            break;
                    }
                    ?>

                    <tr>
                        <td>
                            <div class="d-flex flex-column">
                                <?= $row->name ?>
                                <span class="text-muted"><?= $row->email ?></span>
                            </div>
                        </td>
                        <td>
                            <?= '<a href="' . url( 'admin/user-view/' . $row->user_id) . '">' . $row->user_name . '</a>' ?>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <div><span class="text-success"><?= $row->amount ?></span> <?= $row->currency ?></div>
                                <div><small class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->date, 1) ?>"><?= \Altum\Date::get($row->date, 2) ?></small><div>
                                    </div>
                        </td>
                        <td><?= $row->type == 'ONE-TIME' ? '<span data-toggle="tooltip" title="' . $this->language->admin_payments->table->one_time . '"><i class="fa fa-fw fa-hand-holding-usd"></i></span>' : '<span data-toggle="tooltip" title="' . $this->language->admin_payments->table->recurring . '"><i class="fa fa-fw fa-sync-alt"></i></span>' ?></td>
                        <td><?= $row->processor ?></td>
                    </tr>
                <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif ?>

