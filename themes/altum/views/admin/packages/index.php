<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-box-open text-gray-700"></i> <?= $this->language->admin_packages->header ?></h1>

    <div class="col-auto p-0">
        <a href="<?= url('admin/package-create') ?>" class="btn btn-primary rounded-pill"><i class="fa fa-fw fa-plus-circle"></i> <?= $this->language->admin_packages->create ?></a>
    </div>
</div>

<?php display_notifications() ?>

<div class="mt-5 table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
        <tr>
			<?php if($this->user->agency == 'Y' || $this->user->subagency == 'Y' || $this->user->whitelabel == 'Y'){?>
			<th><?= $this->language->admin_packages->table->name ?></th>
            <th><?= $this->language->admin_packages->table->users ?></th>
            <th width="10%"><?= $this->language->admin_packages->table->is_enabled ?></th>
            <th width="1%"></th>
			<?php }else{?>
            <th><?= $this->language->admin_packages->table->name ?></th>
            <th><?= $this->language->admin_packages->table->monthly_price ?></th>
            <th><?= $this->language->admin_packages->table->annual_price ?></th>
            <th><?= $this->language->admin_packages->table->users ?></th>
            <th><?= $this->language->admin_packages->table->is_enabled ?></th>
            <th></th>
			<?php }?>
        </tr>
        </thead>
        <tbody>
		<?php if($this->user->agency == 'Y' || $this->user->subagency == 'Y' || $this->user->whitelabel == 'Y'){?>
		<?php }else{?>
        <tr>
            <td><?= $this->settings->package_free->name ?></td>
            <td>-</td>
            <td>-</td>
            <td><i class="fa fa-fw fa-users text-muted"></i> <?= $this->database->query("SELECT COUNT(*) AS `total` FROM `users` WHERE `package_id` = 'free'")->fetch_object()->total ?? 0 ?></td>
            <td><?= $this->settings->package_free->is_enabled ? '<span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> ' . $this->language->global->active . '</span>' : '<span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> ' . $this->language->global->disabled . '</span>' ?></td>
            <td><?= get_admin_options_button('package', 'free') ?></td>
        </tr>

        <tr>
            <td><?= $this->settings->package_trial->name ?></td>
            <td>-</td>
            <td>-</td>
            <td><i class="fa fa-fw fa-users text-muted"></i> <?= $this->database->query("SELECT COUNT(*) AS `total` FROM `users` WHERE `package_id` = 'trial'")->fetch_object()->total ?? 0 ?></td>
            <td><?= $this->settings->package_trial->is_enabled ? '<span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> ' . $this->language->global->active . '</span>' : '<span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> ' . $this->language->global->disabled . '</span>' ?></td>
            <td><?= get_admin_options_button('package', 'trial') ?></td>
        </tr>

        <tr>
            <td>
                <?= $this->settings->package_custom->name ?>
                <span data-toggle="tooltip" title="<?= $this->language->admin_packages->table->custom_help ?>"><i class="fa fa-fw fa-info-circle text-muted"></i></span>
            </td>
            <td>-</td>
            <td>-</td>
            <td><i class="fa fa-fw fa-users text-muted"></i> <?= $this->database->query("SELECT COUNT(*) AS `total` FROM `users` WHERE `package_id` = 'custom'")->fetch_object()->total ?? 0 ?></td>
            <td><span class="badge badge-pill badge-info"><i class="fa fa-fw fa-eye-slash"></i> <?= $this->language->global->hidden ?></span></td>
            <td></td>
        </tr>
		<?php }?>

        <?php if($data->packages_result->num_rows>0) { 
		while($row = $data->packages_result->fetch_object()): ?>

            <tr>
                <td><?= $row->name ?></td>
				<?php if($this->user->agency == 'Y' || $this->user->subagency == 'Y' || $this->user->whitelabel == 'Y'){?>
				<?php }else{?>
                <td><?= $row->monthly_price . ' ' . $this->settings->payment->currency ?></td>
                <td><?= $row->annual_price . ' ' . $this->settings->payment->currency ?></td>
				<?php }?>
                <td><i class="fa fa-fw fa-users text-muted"></i> <?= $this->database->query("SELECT COUNT(*) AS `total` FROM `users` WHERE `package_id` = '{$row->package_id}'")->fetch_object()->total ?? 0 ?></td>
                <td><?= $row->is_enabled ? '<span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> ' . $this->language->global->active . '</span>' : '<span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> ' . $this->language->global->disabled . '</span>' ?></td>
                <td><?= get_admin_options_button('package', $row->package_id) ?></td>
            </tr>

        <?php endwhile;
		} else {
		?>
		<tr class="odd"><td colspan="5" class="text-center">No data available in table</td></tr>
		<?php }?>
        </tbody>
    </table>
</div>
