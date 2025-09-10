<?php

namespace Altum\Controllers;

use Altum\Middlewares\Authentication;

class AdminDomainsWL extends Controller {

    public function index() {

        Authentication::guard('admin');

        /* Main View */
        $view = new \Altum\Views\View('admin/domains-whitelabel/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }
}
