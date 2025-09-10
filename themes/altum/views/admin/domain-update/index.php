<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <div class="d-flex align-items-center">
        <h1 class="h3 mr-3"><i class="fa fa-fw fa-xs fa-anchor text-gray-700"></i> <?= $this->language->admin_domain_update->header ?></h1>

        <?= get_admin_options_button('domain', $data->domain->user_id) ?>
    </div>
</div>
<p class="text-muted"><?= $this->language->admin_domain_update->subheader ?></p>

<?php display_notifications() ?>

<div class="card border-0 shadow-sm mt-5">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="form-group">
			<label><i class="fa fa-fw fa-network-wired fa-sm mr-1 text-gray-700"></i> Type Domain</label>
            <div class="row">
				<div class="col-auto">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="type_domain" value="0"<?= $data->domain->type==0 ? ' checked' : null ?>>
							Individual
						</label>
					</div>
				</div>
				<div class="col-auto">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="type_domain" value="1"<?= $data->domain->type==1 ? ' checked' : null ?>>
							Global
						</label>
					</div>
				</div>
			</div>
			</div>
			
			<div id="ptype_domain" class="form-group">
				<label><i class="fa fa-fw fa-user fa-sm mr-1"></i> Domain for User</label>
				<select name="user_id" class="form-control selectpicker with-ajax" id="select-user" data-live-search="true">
					<option value="<?= $data->domain->user_id ?>" data-subtext="<?= $data->domain->email ?>" selected><?= $data->domain->name ?></option>
					<?php while($rows = $data->users->fetch_object()) {?>
					<option value="<?= $rows->user_id ?>" data-subtext="<?= $rows->email ?>" ><?= $rows->name ?></option>
					<?php }?>
				</select>
			</div>
			
			<div class="form-group">
			<label><i class="fa fa-fw fa-network-wired fa-sm mr-1 text-gray-700"></i> Type Account</label>
            <div class="row">
				<div class="col-auto">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="is_admin" value="1"<?= $data->domain->is_admin==1 ? ' checked' : null ?>>
							Admin
						</label>
					</div>
				</div>
				<div class="col-auto">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="is_admin" value="0"<?= $data->domain->is_admin==0 ? ' checked' : null ?>>
							User
						</label>
					</div>
				</div>
			</div>
			<small class="text-muted"><i class="fa fa-fw fa-info-circle"></i> Jika Bukan Admin, Custom Domain tidak bisa muncul di pilihan domain</small>
			</div>
			
			<input type="hidden" name="type_dns" value="0" />
			<input type="hidden" name="host" value="<?= $data->domain->host ?>" />
			
			<div class="form-group">
                <label><i class="fa fa-fw fa-network-wired fa-sm mr-1 text-gray-700"></i> <?= $this->language->admin_domain_create->form->host ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <select name="scheme" class="appearance-none select-custom-altum form-control input-group-text">
                            <option value="https://"<?= $data->domain->scheme=='https://' ? ' selected' : null ?>>https://</option>
                            <option value="http://"<?= $data->domain->scheme=='http://' ? ' selected' : null ?>>http://</option>
                        </select>
                    </div>

                    <input type="text" class="form-control" value="<?= $data->domain->host ?>" placeholder="<?= $this->language->admin_domain_create->form->host_placeholder ?>" readonly/>
                </div>
                <small class="text-muted"><i class="fa fa-fw fa-info-circle"></i> <?= $this->language->admin_domain_create->form->host_help ?></small>
				<div class="form-group mt-3">
					<label><i class="fa fa-fw fa-network-wired fa-sm mr-1 text-gray-700"></i> Status Domain</label>
					<div class="row">
						<div class="col-auto">
							<div class="form-check">
								<label class="form-check-label">
									<input class="form-check-input" type="radio" name="is_active" value="0"<?= $data->domain->is_active==0 ? ' checked' : null ?>>
									Offline
								</label>
							</div>
						</div>
						<div class="col-auto">
							<div class="form-check">
								<label class="form-check-label">
									<input class="form-check-input" type="radio" name="is_active" value="1"<?= $data->domain->is_active==1 ? ' checked' : null ?>>
									Online
								</label>
							</div>
						</div>
					</div>
				</div>
				<br><br>
				<small class="text-muted">
				Change NS to Cloudflare DNS for Custom Domain:
				<br>
				<code>leah.ns.cloudflare.com</code>
				<br>
				<code>pete.ns.cloudflare.com</code>
				</small>
            </div>

            <div class="mt-4">
                <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->update ?></button>
            </div>
        </form>

    </div>
</div>
<style>
.bootstrap-select .dropdown-menu li small{
	display:block;
	width:100%;
	padding:0;
}
.bootstrap-select .dropdown-menu li a.opt {
	padding-left:1.25rem;
}
.dropdown-item {
	white-space: normal; !important;
}
</style>
<script>
$('input[name="type_domain"]').on('change',function(e){
	if($(this).val()==0)
		$('#ptype_domain').show();
	else
		$('#ptype_domain').hide();
})
$(window).on('load',function(e){
	var options = {
	  values: "a, b, c",
	  ajax: {
		url: "<?= url('admin/domains/ajaxsearch') ?>",
		type: "POST",
		dataType: "json",
		// Use "{{{q}}}" as a placeholder and Ajax Bootstrap Select will
		// automatically replace it with the value of the search query.
		data: {
		  q: "{{{q}}}"
		}
	  },
	  locale: {
		emptyTitle: "Select and Begin Typing"
	  },
	  log: 3,
	  preprocessData: function(data) {
		var i,
		  l = data.length,
		  array = [];
		if (l) {
		  for (i = 0; i < l; i++) {
			array.push(
			  $.extend(true, data[i], {
				text: data[i].name,
				value: data[i].id,
				data: {
				  subtext: data[i].email
				}
			  })
			);
		  }
		}
		// You must always return a valid array when processing data. The
		// data argument passed is a clone and cannot be modified directly.
		return array;
	  }
	};
	
	$(".selectpicker")
	  .selectpicker()
	  .filter(".with-ajax")
	  .ajaxSelectPicker(options);
	$("select").trigger("change");

	function chooseSelectpicker(index, selectpicker) {
	  $(selectpicker).val(index);
	  $(selectpicker).selectpicker('refresh');
	}
})
</script>