<?php defined('ALTUMCODE') || die() ?>

<div class="my-3">
    <a href="#" data-toggle="modal" data-target="#cartform_<?= $data->link->link_id ?>" class="btn btn-block btn-primary link-btn <?= $data->link->design->link_class ?>" style="<?= $data->link->design->link_style ?>">

        <?php if($data->link->settings->icon): ?>
            <i class="<?= $data->link->settings->icon ?> mr-1"></i>
        <?php endif ?>

        <?= $data->link->settings->name ?>
    </a>

</div>

<?php ob_start() ?>
<div class="modal fade" id="cartform_<?= $data->link->link_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-shopping-cart"></i> <?= $data->link->settings->name ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
				<div class="cartform">
					<img class="" src="<?= $data->link->settings->photo?>" />
					<h1 class="title"><?= $data->link->settings->title?></h1>
					<div class="price"><?= number_format($data->link->settings->price,0,"",",")?></div>
					<div class="description"><?= str_replace("\n","<br>",$data->link->settings->description)?></div>
				</div>
			
                <form id="cartform_form_<?= $data->link->link_id ?>" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="mail" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="type" value="biolink" />
                    <input type="hidden" name="subtype" value="cartform" />

                    <div class="notification-container"></div>
					
					<div class="form-group">
                        <label><i class="fa fa-fw fa-list fa-sm mr-1"></i> <?= $data->link->language_public->qty?></label>
                        <input type="number" class="form-control" name="qty" min="<?= $data->link->settings->min_qty?>" max="<?= $data->link->settings->max_qty?>" value="1" required="required" />
                    </div>
					
                    <div class="form-group">
                        <label><i class="fa fa-fw fa-user fa-sm mr-1"></i> <?= $data->link->language_public->name?></label>
                        <input type="text" class="form-control" name="name" placeholder="<?= $data->link->language_public->name_placeholder?>" required="required" />
                    </div>
					
					<div class="form-group">
                        <label><i class="fa fa-fw fa-phone-alt fa-sm mr-1"></i> <?= $data->link->language_public->wa_number?></label>
                        <input type="text" class="form-control" name="wa_number" placeholder="<?= $data->link->language_public->wa_number_placeholder?>" required="required" />
                    </div>
					
					<div class="form-group">
						<label><i class="fa fa-fw fa-paragraph fa-sm mr-1"></i> <?= $data->link->language_public->wa_message?></label>
						<textarea class="form-control" name="wa_message" row="3" required="required" placeholder="<?= $data->link->language_public->wa_message_placeholder?>"><?= stripcslashes($data->link->settings->wa_message)?></textarea>
						<small><?= $data->link->language_public->wa_message_notes?></small>
					</div>
					
					<input type="hidden" name="title" value="<?= $data->link->settings->title?>"/>
                    <input type="hidden" name="price" value="<?= intval(preg_replace('/[^0-9+]/','',$data->link->settings->price))?>"/>

                    <div class="text-center mt-4">
                        <button class="btn btn-lg btn-block btn-primary submit"><?= $data->link->language_public->submit?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
listen("load", window, function() {
    var cartform_or = {phone:"<?= $data->link->settings->wa_number?>"} 
	
	var cartform_url = 'https://api.whatsapp.com/send';
	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
	  cartform_url = 'whatsapp://send/';
	}
	
	$("form#cartform_form_<?= $data->link->link_id ?>").validate({
		// Specify validation rules
		rules: {
		  qty: "required",
		  name: "required",
		  wa_number: "required",
		  wa_message: "required",
		  qty: {
			required: true,
			number: true,
			min: <?= $data->link->settings->min_qty?>,
			max: <?= $data->link->settings->max_qty?>
		  },
		  name: {
			required: true,
			minlength: 5
		  },
		  wa_number: {
			required: true,
			number: true,
			minlength: 9
		  },
		  wa_message: {
			required: true,
			minlength: 10
		  }
		},
		// Specify validation error messages
		messages: {
		  qty: {
			required: "Please insert quantity",
			number: "Only numbers are allowed",
			min: "Please enter a value greater than or equal to <?= $data->link->settings->min_qty?>",
			min: "Please enter a value less than or equal to <?= $data->link->settings->max_qty?>"
		  },
		  name: {
			required: "Please insert your full name",
			minlength: "Your full name must be at least 5 characters long"
		  },
		  wa_number: {
			required: "Please insert your WA number",
			number: "Only numbers are allowed",
			minlength: "Your WA number must be at least 9 characters long"
		  },
		  wa_message: {
			required: "Please insert your message",
			minlength: "Your message must be at least 10 characters long"
		  }
		},
		submitHandler: function(form) {
			var via_url = location.href,
			title = $('form#cartform_form_<?= $data->link->link_id ?> input[name="title"]').val(),
			price = $('form#cartform_form_<?= $data->link->link_id ?> input[name="price"]').val(),
			qty = $('form#cartform_form_<?= $data->link->link_id ?> input[name="qty"]').val(),
			name = $('form#cartform_form_<?= $data->link->link_id ?> input[name="name"]').val(),
			wa_number = $('form#cartform_form_<?= $data->link->link_id ?> input[name="wa_number"]').val(),
			wa_message = $('form#cartform_form_<?= $data->link->link_id ?> textarea[name="wa_message"]').val();
			total = parseInt(price)*parseInt(qty)
			var wa_url = cartform_url + '?phone= ' + cartform_or.phone + '&text=*Produk :* ' + title + ' %0A*Qty :* ' + qty + ' x Rp. ' + parseInt(price).toLocaleString() + '%0A%0A*Total:* Rp. ' + (total.toLocaleString()) + ' %0A%0A*Nama :* ' + name + ' %0A*WA :* ' + wa_number + ' %0A*Pesan : ' + window.encodeURIComponent(wa_message) + '* %0A%0Avia ' + via_url;
			
			var win = window.open(wa_url, '_blank');
			win.focus();
			return false;
		}
	});
})
</script>

<?php \Altum\Event::add_content(ob_get_clean(), 'modals') ?>