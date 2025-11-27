<?php

namespace Altum\Routing;

use Altum\Database\Database;

class Router {
    public static $params = [];
    public static $path = '';
    public static $controller_key = 'index';
    public static $controller = 'Index';
    public static $controller_settings = [
        'menu_no_margin'        => false,
        'body_white'            => true,
        'wrapper'               => 'wrapper',
        'no_authentication_check' => false
    ];
    public static $method = 'index';

    public static $routes = [
        'link' => [
            'link' => [
                'controller' => 'Link',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],
        ],

        '' => [
            'index' => [
                'controller' => 'Index'
            ],

            'login' => [
                'controller' => 'Login',
                'settings' => [
                    'wrapper' => 'basic_wrapper'
                ]
            ],

            'register' => [
                'controller' => 'Register',
                'settings' => [
                    'wrapper' => 'basic_wrapper'
                ]
            ],
			
			'join-trial' => [
                'controller' => 'JoinTrial',
                'settings' => [
                    'wrapper' => 'templates_wrapper'
                ]
            ],

            'pages' => [
                'controller' => 'Pages'
            ],

            'page' => [
                'controller' => 'Page'
            ],

            'activate-user' => [
                'controller' => 'ActivateUser'
            ],

            'lost-password' => [
                'controller' => 'LostPassword',
                'settings' => [
                    'wrapper' => 'basic_wrapper'
                ]
            ],

            'reset-password' => [
                'controller' => 'ResetPassword',
                'settings' => [
                    'wrapper' => 'basic_wrapper'
                ]
            ],

            'resend-activation' => [
                'controller' => 'ResendActivation',
                'settings' => [
                    'wrapper' => 'basic_wrapper'
                ]
            ],

            'logout' => [
                'controller' => 'Logout'
            ],

            'notfound' => [
                'controller' => 'NotFound'
            ],

            /* Logged in */
            'dashboard' => [
                'controller' => 'Dashboard',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],

            'project' => [
                'controller' => 'Project',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],

            'link' => [
                'controller' => 'Link',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],

            'account' => [
                'controller' => 'Account',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],
            
            'bank-account' => [
                'controller' => 'BankAccount',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],

            'domains' => [
                'controller' => 'Domains',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],

            'account-package' => [
                'controller' => 'AccountPackage',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],

            'account-payments' => [
                'controller' => 'AccountPayments',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],

            'account-logs' => [
                'controller' => 'AccountLogs',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],

            'invoice' => [
                'controller' => 'Invoice',
                'settings' => [
                    'wrapper' => 'invoice/invoice_wrapper',
                    'body_white' => false,
                ]
            ],

            'package' => [
                'controller' => 'Package',
            ],

            'pay' => [
                'controller' => 'Pay'
            ],

			/* Digital products & orders */
			'digital-product' => [
				'controller' => 'DigitalProduct',
				'settings' => [
					'menu_no_margin' => true,
					'body_white' => false
				]
			],

            'digital-product-create' => [
                'controller' => 'DigitalProduct',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],

            'digital-product-edit' => [
                'controller' => 'DigitalProduct',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],

            'tripay-settings' => [
                'controller' => 'TripaySettings',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],

            'digital-order' => [
				'controller' => 'DigitalOrder',
				'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
				]
			],

            'digital-order-manage' => [
                'controller' => 'DigitalOrder',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],

            'digital-order-update-status' => [
                'controller' => 'DigitalOrder',
                'method' => 'update_status',
                'settings' => [
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],


            /* Webhooks */
            'webhook-paypal' => [
                'controller' => 'WebhookPaypal'
            ],

            'webhook-stripe' => [
                'controller' => 'WebhookStripe'
            ],

            'tripay-callback' => [
                'controller' => 'TripayCallback',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

            /* Ajax */
            'project-ajax' => [
                'controller' => 'ProjectAjax'
            ],

            'link-ajax' => [
                'controller' => 'LinkAjax'
            ],

            /* Others */
            'get-captcha' => [
                'controller' => 'GetCaptcha'
            ],

            'sitemap' => [
                'controller' => 'Sitemap'
            ],

            'cron' => [
                'controller' => 'Cron'
            ],
        ],

        /* Admin Panel */
        'admin' => [
            'index' => [
                'controller' => 'AdminIndex'
            ],

            'users' => [
                'controller' => 'AdminUsers'
            ],

            'user-create' => [
                'controller' => 'AdminUserCreate'
            ],

            'user-view' => [
                'controller' => 'AdminUserView'
            ],

            'user-update' => [
                'controller' => 'AdminUserUpdate'
            ],
			
			'user-export' => [
                'controller' => 'AdminUserExport'
            ],

            'links' => [
                'controller' => 'AdminLinks'
            ],

            'domains-whitelabel' => [
                'controller' => 'AdminDomainsWL'
            ],
            
            'domains-whitelabel-u' => [
                'controller' => 'AdminDomainsWLUpdate'
            ],
            
            'domains-whitelabel-c' => [
                'controller' => 'AdminDomainsWLCreate'
            ],

            'domains' => [
                'controller' => 'AdminDomains'
            ],

            'domain-create' => [
                'controller' => 'AdminDomainCreate'
            ],

            'domain-update' => [
                'controller' => 'AdminDomainUpdate'
            ],


            'pages-categories' => [
                'controller' => 'AdminPagesCategories'
            ],

            'pages-category-create' => [
                'controller' => 'AdminPagesCategoryCreate'
            ],

            'pages-category-update' => [
                'controller' => 'AdminPagesCategoryUpdate'
            ],

            'pages' => [
                'controller' => 'AdminPages'
            ],

            'page-create' => [
                'controller' => 'AdminPageCreate'
            ],

            'page-update' => [
                'controller' => 'AdminPageUpdate'
            ],


            'packages' => [
                'controller' => 'AdminPackages'
            ],

            'package-create' => [
                'controller' => 'AdminPackageCreate'
            ],

            'package-update' => [
                'controller' => 'AdminPackageUpdate'
            ],

            'codes' => [
                'controller' => 'AdminCodes'
            ],

            'code-create' => [
                'controller' => 'AdminCodeCreate'
            ],

            'code-update' => [
                'controller' => 'AdminCodeUpdate'
            ],


            'payments' => [
                'controller' => 'AdminPayments'
            ],


            'statistics' => [
                'controller' => 'AdminStatistics'
            ],


            'settings' => [
                'controller' => 'AdminSettings'
            ],

            'whitelabel-settings' => [
                'controller' => 'WhiteLabelSettings'
            ],
        ],
		
		 /* Ecommerce Panel */
        'ecommerce' => [
			'index' => [
                'controller' => 'EcommerceIndex'
            ],
			
			'profiles' => [
                'controller' => 'EcommerceProfiles'
            ],
		],
		
		's' => [
			'index' => [
                'controller' => 'ShopIndex'
            ],
		],
		
		/* Super Agency Panel */
        'superagency' => [
			'alias' => 'admin'
        ],
		
		/* Agency Panel */
        'agency' => [
			'alias' => 'admin'
        ],
		
		/* Sub Agency Panel */
        'subagency' => [
			'alias' => 'admin'
        ],

        /* White Label Panel */
        'whitelabel' => [
            'alias' => 'admin'
        ],

        /* API Endpoints */
        'api' => [
            'user-create' => [
                'controller' => 'ApiUser',
                'method' => 'create',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],
            'user-package' => [
                'controller' => 'ApiUser',
                'method' => 'update_package',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],
            'packages' => [
                'controller' => 'ApiUser',
                'method' => 'get_packages',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ]
        ],

        /* User Products */
        'user-products' => [
            'index' => [
                'controller' => 'UserProducts',
                'settings' => [
                    'wrapper' => 'minimal_wrapper',
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],
            'view' => [
                'controller' => 'UserProducts',
                'settings' => [
                    'wrapper' => 'minimal_wrapper',
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ],
            'checkout' => [
                'controller' => 'UserProducts',
                'settings' => [
                    'wrapper' => 'minimal_wrapper',
                    'menu_no_margin' => true,
                    'body_white' => false
                ]
            ]
        ]

    ];



    public static function parse_url() {

        $params = self::$params;

        if(isset($_GET['altum'])) {
            $params = explode('/', filter_var(rtrim($_GET['altum'], '/'), FILTER_SANITIZE_URL));
        }

        self::$params = $params;

        return $params;

    }

    public static function get_params() {

        return self::$params = array_values(self::$params);
    }

    public static function parse_controller() {

        /* Check for potential other paths than the default one (admin panel) */
        if(!empty(self::$params[0])) {

            /* Check for special paths first (admin, agency, etc.) */
            if(in_array(self::$params[0], ['admin','superagency','agency','subagency','whitelabel','ecommerce','s','p','api'])) {
                self::$path = self::$params[0];

                unset(self::$params[0]);
                self::$params = array_values(self::$params);
            } 
            /* Check if it's a numeric user_id for digital products - ONLY if no other path matched */
            else if(is_numeric(self::$params[0])) {
                /* Verify it looks like a valid user_id (positive integer) */
                $potential_user_id = intval(self::$params[0]);
                if($potential_user_id > 0) {
                    self::$path = 'user-products';
                    /* Keep params[0] as is - controller needs the user_id */
                    /* Don't call array_values yet - preserve parameter structure */
                }
            }
        }
		
		/* Handle route aliases */
		if(isset(self::$routes[self::$path]['alias']) && self::$routes[self::$path]['alias']) {
			self::$path = self::$routes[self::$path]['alias']; 
		}

        /* Now parse the actual controller */
        if(!empty(self::$params[0])) {

            /* Special handling for user-products path */
            if(self::$path == 'user-products') {
                /* Default to index (user product list) */
                self::$controller_key = 'index';
                
                /* Check if we have product slug in params[1] */
                if(isset(self::$params[1]) && !empty(self::$params[1])) {
                    /* Product detail view */
                    self::$controller_key = 'view';
                    
                    /* Check if params[2] is 'checkout' */
                    if(isset(self::$params[2]) && self::$params[2] === 'checkout') {
                        self::$controller_key = 'checkout';
                    }
                }
                /* Keep all params intact - controller needs them */
            } 
            /* Check if controller exists in routes */
            else if(array_key_exists(self::$params[0], self::$routes[self::$path])) {
                /* Verify the controller file actually exists */
                $controller_file = APP_PATH . 'controllers/' . (self::$path != '' ? self::$path . '/' : null) . self::$routes[self::$path][self::$params[0]]['controller'] . '.php';
                
                if(file_exists($controller_file)) {
                    self::$controller_key = self::$params[0];
                    unset(self::$params[0]);
                    self::$params = array_values(self::$params);
                } else {
                    /* Controller file not found, treat as 404 */
                    self::$path = '';
                    self::$controller_key = 'notfound';
                }
            } 
            /* Check if it's a custom link URL */
            else {
                /* Try to check if the link exists via the cache */
                $cache_instance = \Altum\Cache::$adapter->getItem('available_links_' . self::$params[0]);

                /* Set cache if not existing */
                if(!$cache_instance->get()) {

                    /* Get data from the database */
                    $link_url = Database::simple_get('url', 'links', ['url' => self::$params[0]]);

                    \Altum\Cache::$adapter->save($cache_instance->set($link_url)->expiresAfter(86400));

                } else {

                    /* Get cache */
                    $link_url = $cache_instance->get();

                }

                /* Check if there is any link available in the database */
                if($link_url) {
                    self::$params[0] = Database::clean_string(self::$params[0]);

                    self::$controller_key = 'link';
                    self::$controller = 'Link';
                    self::$path = 'link';

                } else {

                    /* Not found controller */
                    self::$path = '';
                    self::$controller_key = 'notfound';

                }
            }

        }

        /* Save the current controller */
        self::$controller = self::$routes[self::$path][self::$controller_key]['controller'];

        /* Make sure we also save the controller specific settings */
        if(isset(self::$routes[self::$path][self::$controller_key]['settings'])) {
            self::$controller_settings = array_merge(self::$controller_settings, self::$routes[self::$path][self::$controller_key]['settings']);
        }

        return self::$controller;

    }

    public static function get_controller($controller_name, $path = '') {

        require_once APP_PATH . 'controllers/' . ($path != '' ? $path . '/' : null) . $controller_name . '.php';

        /* Create a new instance of the controller */
        $class = 'Altum\\Controllers\\' . $controller_name;

        /* Instantiate the controller class */
        $controller = new $class;

        return $controller;
    }

    public static function parse_method($controller) {

        $method = self::$method;

        /* Check if method is defined in route definition */
        if(isset(self::$routes[self::$path][self::$controller_key]['method'])) {
            $method = self::$routes[self::$path][self::$controller_key]['method'];
        }
        /* For user-products path, use the controller_key we already determined */
        else if(self::$path == 'user-products') {
            $method = self::$controller_key;
        }
        /* Make sure to check the class method if set in the url */
        else if(isset(self::get_params()[0]) && method_exists($controller, self::get_params()[0])) {

            /* Make sure the method is not private */
            $reflection = new \ReflectionMethod($controller, self::get_params()[0]);
            if($reflection->isPublic()) {
                $method = self::get_params()[0];

                unset(self::$params[0]);
                self::$params = array_values(self::$params);
            }

        }

        return $method;

    }

}