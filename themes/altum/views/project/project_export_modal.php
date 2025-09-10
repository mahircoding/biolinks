<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="project_export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form name="project_export" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                <input type="hidden" name="request_type" value="export" />
                
                <?php display_notifications() ?>
                <?php if(count($data->links_logs)): ?>
                        <div class="mt-2">
                            <table id="results" class="table table-custom">
                                <thead class="thead-black">
                                <tr>
                                    <th>Project</th>
                                    <th>Link</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <style>
                            #results th:nth-child(2),#results td:nth-child(2) {text-align:center !important} #results td:nth-child(2) .badge-primary {font-size:1.05rem}
                            .table-responsive{min-height:60vh !important;background-color:#fff}
                            </style>
                        </div>
                    <?php else: ?>
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_data.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->project->links->no_data ?>" />
                        <h2 class="h4 text-muted"><?= $this->language->project->links->no_data ?></h2>
                        <p><a href="#" data-toggle="modal" data-target="#create_biolink"><?= $this->language->project->links->no_data_help ?></a></p>
                    </div>
                    <?php endif ?>
                </form>

        </div>
    </div>
</div>

<?php ob_start() ?>
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/datatables.min.css' ?>" rel="stylesheet" media="screen">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/datatables.min.js' ?>"></script>
<script>
let datatable = $('#results').DataTable({
    language: <?= json_encode($this->language->datatable) ?>,
    serverSide: true,
    processing: true,
    ajax: {
        <?php if($_SERVER['SERVER_NAME'] == whitelabel('url')): ?>
        url: "<?= 'https://'.whitelabel('url').'/project/read' ?>",
        <?php else: ?>
        url: <?= json_encode(url('project/read')) ?>,
        <?php endif ?>
        type: 'POST'
    },
    autoWidth: false,
    lengthMenu: [ [-1], ["All"] ],
    buttons: [
        {
            extend: 'excelHtml5',
            exportOptions: {
                columns: ':not(.disable_export)'
            }
        }
    ],
	exportOptions: {
		columns: 'th:not(:last-child)'
	},
    columns: [
        {
            data: 'url',
            searchable: false,
            sortable: false
        },
        {
            data: 'full_url',
            searchable: false,
            sortable: false
        }
    ],
	'columnDefs': [
		{
			"targets": 1, // your case first column
			"className": "text-center",
			"width": "10%"
		},
		{
			"targets": 1,
			"className": "text-center",
			"width": "1%"
		}
	],
    responsive: true,
    dom: "<'row'<'col-sm-12 col-md-6'<'row'<'col-auto'B><'col-auto'l>>><'col-sm-12 col-md-6'f>>" +
        "<'table-responsive table-custom-container my-3'tr>",
});

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>