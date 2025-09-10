<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Models\Domain;
use Altum\Title;
use Altum\Response;

class Project extends Controller {

    public function index() {

        Authentication::guard();
		
		if(strtotime($this->user->package_expiration_date) < strtotime('NOW')) {
			redirect('package/new');
		}
		
        $project_id = isset($this->params[0]) ? (int) $this->params[0] : false;

        /* Make sure the project exists and is accessible to the user */
        if(!$project = Database::get('*', 'projects', ['project_id' => $project_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }
		
		$projects_all = null;
		if($pr_all = Database::$database->query("SELECT * FROM projects WHERE user_id = {$this->user->user_id}")){
			while($prl = $pr_all->fetch_object()) {
				$projects_all[] = $prl;
			}
		}

        /* Get the links list for the project */
        $links_result = Database::$database->query("
            SELECT 
                `links`.*, `domains`.`scheme`, `domains`.`host`
            FROM 
                `links`
            LEFT JOIN 
                `domains` ON `links`.`domain_id` = `domains`.`domain_id`
            WHERE 
                `links`.`project_id` = {$project->project_id} AND 
                `links`.`user_id` = {$this->user->user_id} AND 
                (`links`.`subtype` = 'base' OR `links`.`subtype` = '')
            ORDER BY
                `links`.`type`
        ");

        /* Iterate over the links */
        $links_logs = [];

        while($row = $links_result->fetch_object()) {
            $row->full_url = $row->domain_id ? $row->scheme . $row->host . '/' . $row->url : url($row->url);

            $links_logs[] = $row;
        }

        /* Get statistics */
        if(count($links_logs)) {
            $logs_chart = [];
            $start_date_query = (new \DateTime())->modify('-30 day')->format('Y-m-d H:i:s');
            $end_date_query = (new \DateTime())->modify('+1 day')->format('Y-m-d H:i:s');
            $project_ids = implode(', ', array_unique(array_map(function($row) {
                return (int) $row->link_id;
            }, $links_logs)));

            $logs_result = Database::$database->query("
                SELECT
                     `count`,
                     DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
                FROM
                     `track_links`
                WHERE
                    `link_id` IN ({$project_ids})
                    AND (`date` BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                ORDER BY
                    `formatted_date`
            ");

            /* Generate the raw chart data and save logs for later usage */
            while($row = $logs_result->fetch_object()) {
                $logs[] = $row;

                /* Handle if the date key is not already set */
                if (!array_key_exists($row->formatted_date, $logs_chart)) {
                    $logs_chart[$row->formatted_date] = [
                        'impressions' => 0,
                        'uniques' => 0,
                    ];
                }

                /* Distribute the data from the database row */
                $logs_chart[$row->formatted_date]['uniques']++;
                $logs_chart[$row->formatted_date]['impressions'] += $row->count;
            }

            $logs_chart = get_chart_data($logs_chart);
        }

        /* Create Link Modal */
        $domains = (new Domain())->get_biolink_domains_custom($this->user->user_id);

        $data = [
            'project' => $project,
			'projects_all'	=> $projects_all,
            'domains' => $domains
        ];

        $view = new \Altum\Views\View('project/create_link_modals', (array) $this);

        \Altum\Event::add_content($view->run($data), 'modals');

        /* Update Project Modal */
        $view = new \Altum\Views\View('project/project_update_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Project Modal */
        $view = new \Altum\Views\View('project/project_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Export Project Modal */
        /* Prepare the View */
        $data = [
            'project'        => $project,
            'links_logs'     => $links_logs,
            'logs_chart'     => $logs_chart ?? false
        ];
        $view = new \Altum\Views\View('project/project_export_modal', (array) $this);
        \Altum\Event::add_content($view->run($data), 'modals');

        /* Prepare the View */
        $data = [
            'project'        => $project,
            'links_logs'     => $links_logs,
            'logs_chart'     => $logs_chart ?? false
        ];

        $view = new \Altum\Views\View('project/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->project->title, $project->name));

    }

    public function read() {

        Authentication::guard();

        $datatable = new \Altum\DataTable();
        $datatable->set_accepted_columns(['url', 'full_url']);
        $datatable->process($_POST);
        $ids = $this->user->user_id; 
        $url1 = $_SERVER['HTTP_REFERER'];
        $ppp1 = explode('/',trim($url1,'/'));
        $ppp  = end($ppp1);
		$limit = "LIMIT {$datatable->get_start()}, {$datatable->get_length()}";
		if($datatable->get_length()<=-1) {
			$limit = "";
		}
		
        $result = Database::$database->query("
        SELECT a.*,
				(SELECT count(*) FROM (SELECT user_id as btype FROM `projects` WHERE `projects`.`name` LIKE '%{$datatable->get_search()}%' OR `projects`.`project_id` LIKE '%{$datatable->get_search()}%') b WHERE btype LIKE '%".$_POST['columns'][0]['search']['value']."%') as total_after_filter FROM (
			SELECT
            `links`.`user_id`, `links`.`project_id`, `links`.`url`,  `links`.`location_url`, `links`.`link_id`, `domains`.`domain_id`, `domains`.`scheme`, `domains`.`host`, 
			`links`.type as utype,
            (SELECT COUNT(*) FROM `links`) AS `total_before_filter`
        FROM
            `links`
        LEFT JOIN 
            `domains` ON `links`.`domain_id` = `domains`.`domain_id`
        WHERE
            `links`.`user_id` = $ids AND
            `links`.`project_id` = $ppp AND
            `links`.`subtype` = 'base'
        ORDER BY
        `links`.`type` DESC,
            " . $datatable->get_order() . "
		) a WHERE utype LIKE '%".$_POST['columns'][0]['search']['value']."%'
		ORDER BY
			`user_id` DESC
		{$limit}
        ");

        $total_before_filter = 0;
        $total_after_filter = 0;

        $data = [];
		
		
        while($row = $result->fetch_object()):
			
            $row->url;
            $row->full_url = $row->domain_id ? $row->scheme . $row->host . '/' . $row->url : url($row->url);
			
            $data[] = $row;

            $total_before_filter = $row->total_before_filter;
            $total_after_filter = $row->total_after_filter;

        endwhile;
		
        Response::simple_json([
            'data' => $data,
            'draw' => $datatable->get_draw(),
        ]);
    }

}
