<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-anchor text-gray-700"></i> <?= $this->language->admin_domain_create->header ?></h1>
</div>
<p class="text-muted"><?= $this->language->admin_domain_create->subheader ?></p>

<?php display_notifications() ?>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

			<div class="form-group">
			<label><i class="fa fa-fw fa-network-wired fa-sm mr-1 text-gray-700"></i> Type Domain</label>
            <div class="row">
				<div class="col-auto">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="type_domain" value="0" checked>
							Individual
						</label>
					</div>
				</div>
				<div class="col-auto">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="type_domain" value="1">
							Global
						</label>
					</div>
				</div>
			</div>
			</div>
			
			<div id="ptype_domain" class="form-group">
				<label><i class="fa fa-fw fa-user fa-sm mr-1"></i> Domain for User</label>
				<select name="user_id" class="form-control selectpicker with-ajax" id="select-user" data-live-search="true">
					<?php while($rows = $data->users->fetch_object()) {?>
					<option value="<?= $rows->user_id ?>" data-subtext="<?= $rows->email ?>" ><?= $rows->name ?></option>
					<?php }?>
				</select>
			</div>
			
			<input type="hidden" name="type_dns" value="0" />
			
			<div class="form-group">
                <label><i class="fa fa-fw fa-network-wired fa-sm mr-1 text-gray-700"></i> <?= $this->language->admin_domain_create->form->host ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <select name="scheme" class="appearance-none select-custom-altum form-control input-group-text">
                            <option value="https://">https://</option>
                            <option value="http://">http://</option>
                        </select>
                    </div>

                    <input type="text" class="form-control" name="host" placeholder="<?= $this->language->admin_domain_create->form->host_placeholder ?>" required="required" />
                </div>
                <small class="text-muted"><i class="fa fa-fw fa-info-circle"></i> <?= $this->language->admin_domain_create->form->host_help ?></small>
				<div class="form-group mt-3">
					<label><i class="fa fa-fw fa-network-wired fa-sm mr-1 text-gray-700"></i> Status Domain</label>
					<div class="row">
						<div class="col-auto">
							<div class="form-check">
								<label class="form-check-label">
									<input class="form-check-input" type="radio" name="is_active" value="0" checked>
									Offline
								</label>
							</div>
						</div>
						<div class="col-auto">
							<div class="form-check">
								<label class="form-check-label">
									<input class="form-check-input" type="radio" name="is_active" value="1">
									Online
								</label>
							</div>
						</div>
					</div>
				</div>
				<br><br>
            </div>

            <div class="mt-4">
                <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->admin_domain_create->form->create ?></button>
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
