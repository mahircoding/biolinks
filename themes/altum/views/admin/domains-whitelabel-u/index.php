<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <div class="d-flex align-items-center">
        <h1 class="h3 mr-3"><i class="fa fa-fw fa-xs fa-anchor text-gray-700"></i> Update White Label Domain</h1>
    </div>
</div>

<?php display_notifications() ?>

<div class="card border-0 shadow-sm mt-5">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
			
			<div id="ptype_domain" class="form-group">
				<label><i class="fa fa-fw fa-user fa-sm mr-1"></i> Domain for User</label>
				<select name="user_id" class="form-control selectpicker with-ajax" id="select-user" data-live-search="true">
					<?php while($rows = $data->users->fetch_object()) {?>
					<option value="<?= $rows->user_id ?>" data-subtext="<?= $rows->email ?>" ><?= $rows->name ?></option>
					<?php }?>
				</select>
			</div>
			
            <div class="form-group">
                <label><i class="fa fa-fw fa-network-wired fa-sm mr-1 text-gray-700"></i> <?= $this->language->admin_domain_create->form->host ?></label>
                <div class="input-group">
                    <input type="text" class="form-control" name="url" placeholder="<?= $this->language->admin_domain_create->form->host_placeholder ?>" value="<?= $data->domain->url ?>" required="required" />
                </div>
                <small class="text-muted"><i class="fa fa-fw fa-info-circle"></i> <?= $this->language->admin_domain_create->form->host_help ?></small>
				<br><br>
				<small class="text-muted">
				Change NS to Cloudflare DNS for Custom Domain:
				<br>
				<code><?= $data->domain->name_servers[0] ?></code>
				<br>
				<code><?= $data->domain->name_servers[1] ?></code>
				</small>
			</div>

            <div class="mt-4">
                <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->update ?></button>
            </div>
        </form>

    </div>
</div>
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
