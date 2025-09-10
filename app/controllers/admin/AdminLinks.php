<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Models\Package;
use Altum\Models\User;
use Altum\Middlewares\Authentication;
use Altum\Response;
use Altum\Routing\Router;

class AdminLinks extends Controller {

    public function index() {

        Authentication::guard('admin');

        /* Main View */
        $view = new \Altum\Views\View('admin/links/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }


    public function get() {

        Authentication::guard('admin');
		
		if($this->user->superagency == 'Y')
			$urladmin = 'agency';
		elseif($this->user->agency == 'Y')
			$urladmin = 'agency';
		elseif($this->user->subagency == 'Y')
			$urladmin = 'subagency';
        elseif($this->user->whitelabel == 'Y')
            $urladmin = 'whitelabel';
		else
			$urladmin = 'admin';

        $datatable = new \Altum\DataTable();
        $datatable->set_accepted_columns(['link_id', 'user_id', 'email', 'biolink_id', 'type', 'subtype', 'url', 'location_url', 'clicks', 'is_enabled', 'date']);
        $datatable->process($_POST);
		$ids_insert = 0;
		if($this->user->type==1 && (!empty($this->user->superagency) || !empty($this->user->agency) || !empty($this->user->subagency) || !empty($this->user->whitelabel)))
			$ids_insert = $this->user->user_id;
			
        $result = Database::$database->query("
            SELECT
                `link_id`, `links`.`user_id`, `users`.`email`, `biolink_id`, `links`.`type`, `subtype`, `url`, `location_url`, `clicks`, `is_enabled`, `links`.`date`, `links`.`domain_id`, `domains`.`scheme`, `domains`.`host`,
                (SELECT COUNT(*) FROM `links` LEFT JOIN `users` ON `links` . `user_id` = `users` . `user_id` " . ($urladmin!=='admin' ? "WHERE `users` . `ids_insert` = {$ids_insert}" : null) . ") AS `total_before_filter`,
                (SELECT COUNT(*) FROM `links` LEFT JOIN `users` ON `links` . `user_id` = `users` . `user_id` WHERE" . ($urladmin!=='admin' ? " `users` . `ids_insert` = {$ids_insert} AND " : null) . " (`users` . `email` LIKE '%{$datatable->get_search()}%' OR `users` . `name` LIKE '%{$datatable->get_search()}%' OR `links` . `url` LIKE '%{$datatable->get_search()}%' OR `links` . `location_url` LIKE '%{$datatable->get_search()}%')) AS `total_after_filter`
            FROM
                `links`
            LEFT JOIN
                `users` ON `links`.`user_id` = `users`.`user_id`
            LEFT JOIN
                `domains` ON `links`.`domain_id` = `domains`.`domain_id`
            WHERE 
				" . ($urladmin!=='admin' ? "`users` . `ids_insert` = {$ids_insert} AND " : null) . "
                (`users` . `email` LIKE '%{$datatable->get_search()}%' 
                OR `users` . `name` LIKE '%{$datatable->get_search()}%'
                OR `links` . `url` LIKE '%{$datatable->get_search()}%'
                OR `links` . `location_url` LIKE '%{$datatable->get_search()}%')
            ORDER BY
                `type` DESC,
                " . $datatable->get_order() . "
            LIMIT
                {$datatable->get_start()}, {$datatable->get_length()}
        ");
		
        $total_before_filter = 0;
        $total_after_filter = 0;

        $data = [];

        while($row = $result->fetch_object()):

            /* Type */
            $type = '
                <span class="fa-stack fa-1x" data-toggle="tooltip" title="' .  $this->language->link->{$row->type}->name . '">
                    <i class="fas fa-circle fa-stack-2x" style="color: ' . $this->language->link->{$row->type}->color . '"></i>
                    <i class="fas ' . $this->language->link->{$row->type}->icon  . ' fa-stack-1x fa-inverse"></i>
                </span>
            ';

            if($row->type == 'biolink' && !empty($row->subtype) && $row->subtype != 'base') {
                $type .= '
                    <span class="fa-stack fa-1x" data-toggle="tooltip" title="' .  $this->language->link->biolink->{$row->subtype}->name . '">
                        <i class="fas fa-circle fa-stack-2x" style="color: ' .  $this->language->link->biolink->{$row->subtype}->color . '"></i>
                        <i class="fas ' .  $this->language->link->biolink->{$row->subtype}->icon . ' fa-stack-1x fa-inverse"></i>
                    </span>
                ';
            }

            $row->type = $type;

            $row->email = '<a href="' . url($urladmin.'/user-update/' . $row->user_id) . '"> ' . $row->email . '</a>';

            /* Location URL */
            if(!empty($row->location_url)) {
                $location_url = '<img src="https://www.google.com/s2/favicons?domain=' . $row->location_url . '" class="img-fluid mr-1" />';
                $location_url .= '<small><a href="' . $row->location_url . '" target="_blank">' . $this->language->admin_links->display->location_url . '</a></small>';
            } else {
                $location_url = '<img src="https://www.google.com/s2/favicons?domain=' . url($row->url) . '" class="img-fluid mr-1" />';
                $location_url .= '<small><a href="' . url($row->url) . '" target="_blank">' . $row->url . '</a></small>';
            }

            $row->location_url = $location_url;

            /* URL */
            $full_url = $row->domain_id ? $row->scheme . $row->host . '/' . $row->url : url($row->url);
            $row->url = '<a href="' . $full_url . '" target="_blank">' . $row->host . '/' . $row->url . '</a>' . '<br />' . $row->location_url;

            /* Clicks */
            $row->clicks = '<i class="fa fa-fw fa-chart-bar"></i> ' . nr($row->clicks);

            /* Is Enabled Status badge */
            $row->is_enabled = $row->is_enabled ? '<span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> ' . $this->language->global->active . '</span>' : '<span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> ' . $this->language->global->disabled . '</span>';

            $row->date = '<span data-toggle="tooltip" title="' . \Altum\Date::get($row->date, 1) . '">' . \Altum\Date::get($row->date, 2) . '</span>';
            $row->actions = get_admin_options_button('link', $row->link_id, $urladmin);

            $data[] = $row;
            $total_before_filter = $row->total_before_filter;
            $total_after_filter = $row->total_after_filter;

        endwhile;

        Response::simple_json([
            'data' => $data,
            'draw' => $datatable->get_draw(),
            'recordsTotal' => $total_before_filter,
            'recordsFiltered' =>  $total_after_filter
        ]);

    }

    public function delete() {

        Authentication::guard();

        $link_id = (isset($this->params[0])) ? (int) $this->params[0] : false;

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
        }

        if(empty($_SESSION['error'])) {

            /* Delete the link */
            $this->database->query("DELETE FROM `links` WHERE `link_id` = {$link_id} OR `biolink_id` = {$link_id}");

            /* Clear the cache */
            \Altum\Cache::$adapter->delete('biolink_links_' . $link_id);

            redirect('admin/links');

        }

        die();
    }

}
