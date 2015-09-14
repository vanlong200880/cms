<?php
return array(
    'router' => array(
        'routes' => array(
            'backend' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/backend[/:controller][/:action][/:id][/page/:page][/type/:type][/sort/:sort][/order/:order][/status/:status][/textSearch/:textSearch]',
                    'constraints' => array (
                        'controller'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'        => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'page'          => '[0-9]+',
                        'id'            => '[0-9]+',
                        
                        'type'          => '[0-9]+',
                        'sort'          => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order'         => 'asc|desc',
                        'status'        => '[0-9]+',
                        'textSearch'    => '.+', 
                        
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Backend\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'page'          => 1
                    ),
                ),
            ),
        ),
    ),
    
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_VI',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Backend\Controller\Language'	=> 'Backend\Controller\LanguageController',
            'Backend\Controller\Index'		=> 'Backend\Controller\IndexController',
            'Backend\Controller\Product'	=> 'Backend\Controller\ProductController',
            'Backend\Controller\Trademark'	=> 'Backend\Controller\TrademarkController',
            'Backend\Controller\Supplier'	=> 'Backend\Controller\SupplierController',
            'Backend\Controller\Category'	=> 'Backend\Controller\CategoryController',
			'Backend\Controller\Public'     => 'Backend\Controller\PublicController',
            'Backend\Controller\User'       => 'Backend\Controller\UserController'
        ),
    ),
    'view_helpers' => array(
    		'invokables' => array(
                'adminheader' => 'Backend\Block\adminheader',
    		),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/backend'            => __DIR__ . '/../view/layout/layout.phtml',
			'layout/login'              => __DIR__ . '/../view/layout/login.phtml',
            'application/index/index'   => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'                 => __DIR__ . '/../view/error/404.phtml',
            'error/index'               => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy'
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
