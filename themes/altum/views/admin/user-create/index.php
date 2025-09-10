<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-user text-gray-700"></i> <?= $this->language->admin_user_create->header ?></h1>
</div>

<?php display_notifications() ?>

<div class="card border-0 shadow-sm mt-5">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="form-group">
                <label><?= $this->language->admin_user_create->form->name ?></label>
                <input type="text" name="name" class="form-control" value="<?= $data->values['name'] ?>" placeholder="<?= $this->language->admin_user_create->form->name_placeholder ?>" required="required" />
                <input type="hidden" name="ids_insert" value="<?= $this->user->user_id ?>">
            </div>

            <div class="form-group">
                <label><?= $this->language->admin_user_create->form->email ?></label>
                <input type="text" name="email" class="form-control" value="<?= $data->values['email'] ?>" placeholder="<?= $this->language->admin_user_create->form->email_placeholder ?>" required="required" />
            </div>

            <div class="form-group">
                <label><?= $this->language->admin_user_create->form->password ?></label>
                <input type="password" name="password" class="form-control" value="<?= $data->values['password'] ?>" placeholder="<?= $this->language->admin_user_create->form->password_placeholder ?>" required="required" />
            </div>
			
			<div class="form-group">
                <label><?= $this->language->admin_user_create->form->phone ?></label>
                <input type="text" name="phone" class="form-control" value="<?= $data->values['phone'] ?>" placeholder="<?= $this->language->admin_user_create->form->phone_placeholder ?>" required="required" />
				<small><?= $this->language->admin_user_create->form->phone_help ?></small>
			</div>

            <div class="mt-4">
                <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->create ?></button>
            </div>
        </form>

    </div>
</div>

