<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-anchor text-gray-700"></i> <?= $this->language->admin_domain_create->header ?></h1>

    <div class="col-auto">
        <a href="<?= url('admin/domain-create') ?>" class="btn btn-primary rounded-pill"><i class="fa fa-fw fa-plus-circle"></i> <?= $this->language->admin_domain_create->menu ?></a>
    </div>
</div>
<p class="text-muted"><?= $this->language->admin_domains->subheader ?></p>

<?php display_notifications() ?>

<div class="mt-5">
    <table id="results" class="table table-custom">
        <thead class="thead-black">
        <tr>
            <th><?= $this->language->admin_domains->table->type ?></th>
            <th><?= $this->language->admin_domains->table->host ?></th>
	    <th>User Name</th>
	    <th>Phone</th>
            <th><?= $this->language->admin_domains->table->date ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
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
        url: <?= json_encode(url('admin/domains/read')) ?>,
        type: 'POST'
    },
    autoWidth: false,
    lengthMenu: [[25, 50, 100,-1], [25, 50, 100,'All']],
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
            data: 'type',
            searchable: false,
            sortable: true
        },
        {
            data: 'host',
            searchable: true,
            sortable: false
        },
        {
            data: 'name',
            searchable: true,
            sortable: false
        },
        {
            data: 'phone',
            searchable: true,
            sortable: false
        },
        {
            data: 'date',
            searchable: false,
            sortable: true
        },
        {
            data: 'actions',
            searchable: false,
            sortable: false
        }
    ],
    responsive: true,
    drawCallback: () => {
        $('[data-toggle="tooltip"]').tooltip();
    },
    dom: "<'row'<'col-sm-12 col-md-6'<'row'<'col-auto'B><'col-auto'l>>><'col-sm-12 col-md-6'f>>" +
        "<'table-responsive table-custom-container my-3'tr>" +
        "<'row'<'col-sm-12 col-md-5 text-muted'i><'col-sm-12 col-md-7'p>>"
});
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
