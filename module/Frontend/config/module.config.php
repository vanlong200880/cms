<?php
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Frontend\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),

            'news' => array (
                 // 'regex' => '/manual/(?<manufacturer>[a-zA-Z0-9_-]+)/(?<category>[a-zA-Z0-9_-]+)',
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array (
                    'regex' => '/category-news(/page-(?<page>[0-9]+))?(\.(?<format>(html)))?',
                    'defaults' => array (
                        'controller' => 'Frontend\Controller\News',
                        'action' => 'index',
                        'format' => 'html',
                    ),
                    'spec' => '/category-news/page-%page%.%format%'
                )
            ),

            'news-detail' => array (
                    'type' => 'regex',
                    'options' => array (
                            'regex' => '/news/(?<slug>[a-zA-Z0-9-]+)?(\.(?<format>(html)))?',
                            'defaults' => array (
                                    'controller' => 'Frontend\Controller\News',
                                    'action' => 'detail',
                                    'format' => 'html',
                            ),
                            'spec' => '/news/%slug%.%format%'
                    )
            ),
		  
			'user' => array (
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array (
                    'regex' => '/user-profile/(?<name>[a-zA-Z0-9-]+)?(\.(?<format>(html)))?',
                    'defaults' => array (
                        'controller' => 'Frontend\Controller\User',
                        'action' => 'index',
                        'format' => 'html',
                    ),
                    'spec' => '/user-profile/%name%.%format%'
                )
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
    'view_helpers' => array(
        'invokables' => array(
            'header' => 'Frontend\Block\header',
            'footer' => 'Frontend\Block\footer', 
            'slider' => 'Frontend\Block\slider',
            'payment' => 'Frontend\Block\payment',
            'statitics' => 'Frontend\Block\statitics', 
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
            'Frontend\Controller\Index'	=> 'Frontend\Controller\IndexController',
            'Frontend\Controller\News' => 'Frontend\Controller\NewsController',
			'Frontend\Controller\User' => 'Frontend\Controller\UserController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/frontend'           => __DIR__ . '/../view/layout/layout.phtml',
            'frontend/index/index' => __DIR__ . '/../view/frontend/index/index.phtml',
            'frontend/404'               => __DIR__ . '/../view/error/404.phtml',
            'frontend/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
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
