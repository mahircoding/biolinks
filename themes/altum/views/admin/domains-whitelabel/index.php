<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-anchor text-gray-700"></i> White Label Domains</h1>
    <div class="col-auto">
        <a href="<?= url('admin/domains-whitelabel-c') ?>" class="btn btn-primary rounded-pill"><i class="fa fa-fw fa-plus-circle"></i> Add White Label Domain</a>
    </div>
</div>
<p class="text-muted">White Label Domains for your users to use if they need or want.</p>

<?php display_notifications() ?>

<div class="mt-5">
    <table id="results" class="table table-custom">
        <thead class="thead-black">
        <tr>
            <th>User</th>
            <th>Email</th>
            <th>URL</th>
            <th>Title</th>
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
        url: <?= json_encode(url('admin/domains/read_wl')) ?>,
        type: 'POST'
    },
    autoWidth: false,
    lengthMenu: [[25, 50, 100], [25, 50, 100]],
    columns: [
        {
            data: 'name',
            searchable: true,
            sortable: false
        },
        {
            data: 'email',
            searchable: true,
            sortable: false
        },
        {
            data: 'url',
            searchable: true,
            sortable: false
        },
        {
            data: 'title',
            searchable: false,
            sortable: false
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
    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'table-responsive table-custom-container my-3'tr>" +
        "<'row'<'col-sm-12 col-md-5 text-muted'i><'col-sm-12 col-md-7'p>>"
});
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
