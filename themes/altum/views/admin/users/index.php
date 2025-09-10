<?php defined('ALTUMCODE') || die() ?>

<header class="mb-3 d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-users text-gray-700"></i> <?= $this->language->admin_users->header ?></h1>

    <div class="col-auto">
		<a href="#" class="btn btn-primary rounded-pill mr-2" data-toggle="modal" data-target="#register_widget" data-toggle="tooltip" data-placement="bottom" title="Register Widget"><i class="fas fa-fw fa-file-alt"></i></a>
        <a href="<?= url('admin/user-create') ?>" class="btn btn-primary rounded-pill"><i class="fa fa-fw fa-plus-circle"></i> <?= $this->language->admin_user_create->menu ?></a>
	</div>
</header>

<?php display_notifications() ?>

<div class="mt-5">
    <table id="results" class="table table-custom">
        <thead class="thead-black">
        <tr>
			<th><?= $this->language->admin_users->table->name ?></th>
            <th><?= $this->language->admin_users->table->email ?></th>
			<th>Phone</th>
			<th><?= $this->language->admin_users->table->utype ?></th>
			<th class="text-center"><?= $this->language->admin_users->table->license ?></th>
			<th class="text-center"><?= $this->language->admin_users->table->package_id ?></th>
            <th class="text-center"><?= $this->language->admin_users->table->active ?></th>
			<th class="text-center">Upline</th>
			<th class="text-center">T.Login</th>
			<th class="text-center">T.User</th>
            <th class="text-center reg-date"><?= $this->language->admin_users->table->registration_date ?></th>
			<th></th>
            <th class="disable_export"></th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
	<style>
	#results th:nth-child(2),#results td:nth-child(2) {text-align:center !important} #results td:nth-child(2) .badge-primary {font-size:1.05rem}
	.table-responsive{min-height:60vh !important;background-color:#fff}
	</style>
</div>

<div class="modal fade" id="register_widget" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
			<div class="modal-header">
                <h5 class="modal-title">Register Widget</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
			<p class="text-muted modal-subheader">User registration can be done and shared publicly through your account ID</p>
			<div class="modal-body">
                <div class="form-group">
					<label>Your Register Widget Link</label>
					<div class="form-widget d-flex">
						<div class="widget-item flex-grow-1"><?= (trim($_SERVER['SERVER_NAME'])!=BASE_DOMAIN ? 'https://' . $_SERVER['SERVER_NAME'] . '/' : SITE_URL) . 'join-trial/' . $this->user->user_id; ?></div>
						<a href="javascript:;" data-toggle="tooltip" title="Copy Widget Link" data-clipboard-text="<?= SITE_URL . 'join-trial/' . $this->user->user_id; ?>" data-original-title="Copy Link" class="widget-item link text-success"><i class="far fa-copy"></i></a>
					</div>
					<div class="text-center"><small class="text-danger">To test the Link, be sure to Log Out first</small></div>
				</div>
            </div>
			<style>.form-widget{padding:0 .5rem;color:#000;background-color:#f1f1f1;border-radius:10px}.form-widget .widget-item{padding:.75rem .25rem}.form-widget .widget-item.link{padding-left:1rem;padding-right:1rem}</style>
		</div>
    </div>
</div>

<div class="modal fade" id="export_excel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
			<div class="modal-header">
                <h5 class="modal-title">Export Ke Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
			<div class="modal-body">
                <div class="form-group">
					<label id="text-processing" class="w-100 text-center">Export Semua User ke Excel</label>
					<div class="text-center"><small id="text-note" class="text-dark">Proses akan membutuhkan beberapa menit untuk selesai!</small></div>
				</div>
            </div>
			<div class="modal-footer pb-1 d-flex justify-content-center">
				<button data-gr-export="excel" class="btn btn-primary">Export ke Excel</button>
			</div>
			<style>.form-widget{padding:0 .5rem;color:#000;background-color:#f1f1f1;border-radius:10px}.form-widget .widget-item{padding:.75rem .25rem}.form-widget .widget-item.link{padding-left:1rem;padding-right:1rem}</style>
		</div>
    </div>
</div>

<?php ob_start() ?>
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/datatables.min.css' ?>" rel="stylesheet" media="screen">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/datatables.min.js' ?>"></script>
<script>
var isPageLoading=false
let datatable = $('#results').DataTable({
    language: <?= json_encode($this->language->datatable) ?>,
    serverSide: true,
    processing: true,
    ajax: {
        <?php if($_SERVER['SERVER_NAME'] == whitelabel('url')): ?>
        url: "<?= 'https://'.whitelabel('url').'/admin/users/read' ?>",
        <?php else: ?>
        url: <?= json_encode(url('/admin/users/read')) ?>,
        <?php endif ?>
        type: 'POST'
    },
    autoWidth: false,
    lengthMenu: [[25, 50, 100], [25, 50, 100]],
    buttons: [
        {
            extend: 'excelHtml5',
            exportOptions: {
                modifier: {
                    search: 'none'
                },
                columns: ':not(.disable_export)'
            }
        }
    ],
	exportOptions: {
		columns: 'th:not(:last-child)'
	},
    columns: [
        {
            data: 'name',
            searchable: true,
            sortable: true
        },
		{
            data: 'email',
            searchable: true,
            sortable: true
        },
		{
            data: 'phone',
			visible:false,
            searchable: true,
            sortable: false
        },
		{
            data: 'utype',
			visible:false,
            searchable: true,
            sortable: false
        },
		{
            data: 'license',
            searchable: true,
            sortable: true
        },
		{
            data: 'package_id',
            searchable: false,
            sortable: false
        },
        {
            data: 'active',
            searchable: false,
            sortable: true
        },
		{
            data: 'email_upline',
            searchable: false,
            sortable: false
        },
		{
            data: 'total_login',
            searchable: false,
            sortable: true
        },
		{
            data: 'total_user',
            searchable: false,
            sortable: true
        },
        {
            data: 'date',
            searchable: false,
            sortable: true
        },
		{
            data: 'date_created',
            searchable: false,
            sortable: true,
			visible: false
        },
        {
            data: 'actions',
            searchable: false,
            sortable: false
        }
    ],
	'columnDefs': [
		{
			"targets": [4,7], // your case first column
			"className": "text-center",
			"width": "1%"
		},
		{
			"targets": 8,
			"className": "text-center",
		},
		{
			"targets": 9, // your case first column
			"orderData": 10
		}
	],
	order:[[10, 'desc']],
    responsive: true,
	drawCallback: () => {
        $('[data-toggle="tooltip"]').tooltip();
    },
    dom: "<'row'<'col-sm-12 col-md-6'<'row'<'col-auto'<'btn-export'>><'col-auto'l>>><'col-sm-12 col-md-6'f>>" +
        "<'table-responsive table-custom-container my-3'tr>" +
        "<'row'<'col-sm-12 col-md-5 text-muted'i><'col-sm-12 col-md-7'p>>",
	initComplete: function () {
		<?php if($this->user->type == 1) {?>
		$('#export_excel').modal({backdrop:'static',keyboard:false,show:false})
		$('.btn-export').html('<button class="btn btn-primary" data-gr-export="modal" data-toggle="modal" data-target="#export_excel" data-toggle="tooltip" data-placement="bottom" title="Export Users">Export</div>')
		//$('[data-gr-export="modal"]').off('click').on('click',function(e){
			
		//	$($(this).data('target')).modal('show')
		//})
		var txtNote = $('#text-note').text()
		$('[data-gr-export="excel"]').off('click').on('click',function(e){
			var ths = $(this)
			var oriTxt = $('#text-processing').text()
			if(!isPageLoading) {
				isPageLoading = true
				ths.prop('disabled',true)
				ths.text('Processing')
				$('#text-processing').text('Mohon tunggu, memulai proses export (Jangan Ditutup)...')
				$.ajax({
					type: 'POST',
					url: "admin/user-export",
					dataType:'json',
					success: function(data){
						isPageLoading = false
						ths.prop('disabled',false)
						//window.open(data.link,'_blank' );
						window.location.assign(data.link)
						$('#text-processing').text(oriTxt)
						$('#text-note').html(txtNote + '<br>File has been downloaded!.')
						ths.text('Export ke Excel')
					}
				});
			}
		})
		<?php }else{?>
		$('.btn-export').parent().remove();
		<?php }?>
		var api = this.api()
		this.api().columns(2).every( function () {
			//var column = this.api().column(1);
			var column = this;
			<?php if(!empty($this->user->whitelabel) && $this->user->whitelabel = 'Y') { ?>
				var user_types = '<div class="col-auto"><div class="dataTables_length"><select id="type_user" class="form-control custom-select-sm form-control form-control-sm"><option value="">All</option><option value="3">Agency</option><option value="4">Sub Agency</option><option value="5">User</option></select></div></div>';
			<?php } elseif(!empty($this->user->superagency) && $this->user->superagency = 'Y') { ?>
				var user_types = '<div class="col-auto"><div class="dataTables_length"><select id="type_user" class="form-control custom-select-sm form-control form-control-sm"><option value="">All</option><option value="3">Agency</option><option value="4">Sub Agency</option><option value="5">User</option></select></div></div>';
			<?php } elseif(!empty($this->user->agency) && $this->user->agency = 'Y') { ?>
				var user_types = '<div class="col-auto"><div class="dataTables_length"><select id="type_user" class="form-control custom-select-sm form-control form-control-sm"><option value="">All</option><option value="4">Sub Agency</option><option value="5">User</option></select></div></div>';
			<?php } elseif(!empty($this->user->subagency) && $this->user->subagency = 'Y') { ?>
				var user_types = '<div class="col-auto"><div class="dataTables_length"><select id="type_user" class="form-control custom-select-sm form-control form-control-sm"><option value="">All</option><option value="5">User</option></select></div></div>';
			<?php } else { ?>
				var user_types = '<div class="col-auto"><div class="dataTables_length"><select id="type_user" class="form-control custom-select-sm form-control form-control-sm"><option value="">All</option><option value="0">Admin</option><option value="1">Whitelabel</option><option value="2">Super Agency</option><option value="3">Agency</option><option value="4">Sub Agency</option><option value="5">User</option></select></div></div>';
			<?php } ?>
			var select = $(user_types)
			   .appendTo('#results_wrapper .row .col-sm-12.col-md-6 .row');
			   $('#type_user').on('change', function () {
					var val = $.fn.dataTable.util.escapeRegex(
							$(this).val()
						);

					column
					.search( val ? val : '', true, false )
					.draw();
			   });
		})
		$('[data-toggle="tooltip"]').tooltip()
    }
});
$('#register_widget').on('shown.bs.modal',function(e){
	$('.widget-item.link').off('click').on('click', function() {
		var ths = $(this)
		let clipboard = new ClipboardJS('[data-clipboard-text]', {
			container: document.getElementById('register_widget')
		});
		clipboard.on('success',function(){
			ths.attr('data-original-title', 'Copied!').tooltip('show').attr('data-original-title', 'Copy Link!');
		})
	})
})

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
