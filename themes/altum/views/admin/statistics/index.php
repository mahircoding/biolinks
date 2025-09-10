<?php defined('ALTUMCODE') || die(); ?>

<?php ob_start() ?>
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/datepicker.min.css' ?>" rel="stylesheet" media="screen">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/datepicker.min.js' ?>"></script>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/Chart.bundle.min.js' ?>"></script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<div class="d-flex flex-column flex-lg-row justify-content-between mb-5">
    <div>
        <div class="d-flex justify-content-between">
            <h1 class="h3"><i class="fa fa-fw fa-xs fa-chart-line text-gray-700"></i> <?= sprintf($this->language->admin_statistics->header) ?></h1>
        </div>
        <p class="text-muted"><?= $this->language->admin_statistics->subheader ?></p>
    </div>

    <div class="col-auto p-0">
        <form class="form-inline" id="datepicker_form">
            <label>
                <div id="datepicker_selector" class="text-muted clickable">
                    <span class="mr-1">
                        <?php if($data->date->start_date == $data->date->end_date): ?>
                            <?= \Altum\Date::get($data->date->start_date, 2, \Altum\Date::$default_timezone) ?>
                        <?php else: ?>
                            <?= \Altum\Date::get($data->date->start_date, 2, \Altum\Date::$default_timezone) . ' - ' . \Altum\Date::get($data->date->end_date, 2, \Altum\Date::$default_timezone) ?>
                        <?php endif ?>
                    </span>
                    <i class="fa fa-fw fa-caret-down"></i>
                </div>

                <input
                        type="text"
                        id="datepicker_input"
                        data-range="true"
                        name="date_range"
                        value="<?= $data->date->input_date_range ? $data->date->input_date_range : '' ?>"
                        placeholder=""
                        autocomplete="off"
                        readonly="readonly"
                        class="custom-control-input"
                >
            </label>
        </form>
    </div>
</div>

<?php display_notifications() ?>

<?php ob_start() ?>
<script>
    /* Datepicker */
    $.fn.datepicker.language['altum'] = <?= json_encode(require APP_PATH . 'includes/datepicker_translations.php') ?>;
    let datepicker = $('#datepicker_input').datepicker({
        language: 'altum',
        dateFormat: 'yyyy-mm-dd',
        autoClose: true,
        timepicker: false,
        toggleSelected: false,
        minDate: false,
        maxDate: new Date($('#datepicker_input').data('max')),

        onSelect: (formatted_date, date) => {

            if(date.length > 1) {
                let [ start_date, end_date ] = formatted_date.split(',');

                if(typeof end_date == 'undefined') {
                    end_date = start_date
                }

                /* Redirect */
                redirect(`admin/statistics/${start_date}/${end_date}`);
            }
        }
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>


<?php if($this->settings->payment->is_enabled): ?>
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-body">
            <h2 class="h4"><i class="fa fa-fw fa-dollar-sign fa-xs text-muted"></i> <?= $this->language->admin_statistics->sales->header ?></h2>

            <?php $sales_data = $this->database->query("SELECT SUM(`amount`) AS `earnings`, `currency`, COUNT(`id`) AS `count` FROM `payments` WHERE `date` BETWEEN '{$data->date->start_date_query}' AND DATE_ADD('{$data->date->end_date_query}', INTERVAL 1 DAY) GROUP BY `currency` ") ?>
            <?php if(!$sales_data->num_rows): ?>
                <p class="text-muted"><?= $this->language->admin_statistics->sales->no_sales ?></p>
            <?php else: ?>

                <?php
                $logs_chart = [];
                $result = $this->database->query("SELECT COUNT(*) AS `total_sales`, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`, TRUNCATE(SUM(`amount`), 2) AS `total_earned` FROM `payments` WHERE `date` BETWEEN '{$data->date->start_date_query}' AND DATE_ADD('{$data->date->end_date_query}', INTERVAL 1 DAY) GROUP BY `formatted_date`");
                while($row = $result->fetch_object()) {

                    $logs_chart[$row->formatted_date] = [
                        'total_earned' => $row->total_earned,
                        'total_sales' => $row->total_sales
                    ];

                }

                $logs_chart = get_chart_data($logs_chart);
                ?>


                <?php while($sales = $sales_data->fetch_object()): ?>
                    <h6 class="text-muted">
                        <?= sprintf($this->language->admin_statistics->sales->subheader, '<span class="text-info">' . $sales->count . '</span>', '<span class="text-success">' . number_format($sales->earnings, 2) . '</span>', $sales->currency) ?>
                    </h6>
                <?php endwhile ?>

                <div class="chart-container">
                    <canvas id="payments"></canvas>
                </div>

            <?php endif ?>
        </div>
    </div>

    <?php ob_start() ?>
    <script>
        /* Display chart */
        new Chart(document.getElementById('payments').getContext('2d'), {
            type: 'line',
            data: {
                labels: <?= $logs_chart['labels'] ?? '[]' ?>,
                datasets: [{
                    label: <?= json_encode($this->language->admin_statistics->sales->chart_total_sales) ?>,
                    data: <?= $logs_chart['total_sales'] ?? '[]' ?>,
                    backgroundColor: '#237f52',
                    borderColor: '#237f52',
                    fill: false
                },
                {
                    label: <?= json_encode($this->language->admin_statistics->sales->chart_total_earned) ?>,
                    data: <?= $logs_chart['total_earned'] ?? '[]' ?>,
                    backgroundColor: '#37D28D',
                    borderColor: '#37D28D',
                    fill: false
                }]
            },
            options: {
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                title: {
                    text: '',
                    display: true
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            userCallback: (value, index, values) => {
                                if (Math.floor(value) === value) {
                                    return nr(value);
                                }
                            },
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>

<?php
$logs_chart = [];
$result = $this->database->query("    
    SELECT 
        formatted_date, 
        SUM(users) AS `users`, 
        SUM(projects) AS `projects`,
        SUM(links) AS `links`
    FROM (
        SELECT DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`, COUNT(*) AS `users`, 0 AS `projects`, 0 AS `links`
        FROM `users`
        WHERE `date` BETWEEN '{$data->date->start_date_query}' AND DATE_ADD('{$data->date->end_date_query}', INTERVAL 1 DAY)
        GROUP BY `formatted_date`
        
        UNION ALL
        
        SELECT DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`, 0 AS `users`, COUNT(*) AS `projects`, 0 AS `links`
        FROM `projects`
        WHERE `date` BETWEEN '{$data->date->start_date_query}' AND DATE_ADD('{$data->date->end_date_query}', INTERVAL 1 DAY)
        GROUP BY `formatted_date`
        
        UNION ALL
        
        SELECT DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`, 0 AS `users`, 0 AS `projects`, COUNT(*) AS `links`
        FROM `links`
        WHERE `date` BETWEEN '{$data->date->start_date_query}' AND DATE_ADD('{$data->date->end_date_query}', INTERVAL 1 DAY)
        GROUP BY `formatted_date`
    ) AS `altumcode`
    
    GROUP BY `formatted_date`;
");
while($row = $result->fetch_object()) {

    $logs_chart[$row->formatted_date] = [
        'users' => $row->users,
        'projects' => $row->projects,
        'links' => $row->links
    ];

}

$logs_chart = get_chart_data($logs_chart);
?>

<div class="card border-0 shadow-sm mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-seedling fa-xs text-muted"></i> <?= $this->language->admin_statistics->growth->header ?></h2>
        <p class="text-muted"><?= $this->language->admin_statistics->growth->subheader ?></p>

        <div class="chart-container">
            <canvas id="growth"></canvas>
        </div>

    </div>
</div>

<?php ob_start() ?>
<script>
    /* Display chart */
    new Chart(document.getElementById('growth').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?= $logs_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode($this->language->admin_statistics->growth->chart_users) ?>,
                data: <?= $logs_chart['users'] ?? '[]' ?>,
                backgroundColor: '#007bff',
                borderColor: '#007bff',
                fill: false
            },
            {
                label: <?= json_encode($this->language->admin_statistics->growth->chart_projects) ?>,
                data: <?= $logs_chart['projects'] ?? '[]' ?>,
                backgroundColor:'#9684F7',
                borderColor:'#9684F7',
                fill: false
            },
            {
                label: <?= json_encode($this->language->admin_statistics->growth->chart_links) ?>,
                data: <?= $logs_chart['links'] ?? '[]' ?>,
                backgroundColor: '#f75581',
                borderColor: '#f75581',
                fill: false
            }]
        },
        options: {
            tooltips: {
                mode: 'index',
                intersect: false
            },
            title: {
                text: '',
                display: true
            },
            scales: {
                yAxes: [{
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        userCallback: (value, index, values) => {
                            if (Math.floor(value) === value) {
                                return nr(value);
                            }
                        },
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }]
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

