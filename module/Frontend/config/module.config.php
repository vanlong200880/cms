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
		  
			'user-profile' => array (
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array (
                    'regex' => '/user-profile?((?<char>[/]+))?(\.(?<format>(html)))?',
                    'defaults' => array (
                        'controller' => 'Frontend\Controller\User',
                        'action' => 'index',
                        'format' => 'html',
                    ),
                    'spec' => '/user-profile%char%.%format%'
                )
            ),

            'user-edit-profile' => array (
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array (
                    'regex' => '/user-profile/edit?((?<char>[/]+))?(\.(?<format>(html)))?',
                    'defaults' => array (
                        'controller' => 'Frontend\Controller\User',
                        'action' => 'edit',
                        'format' => 'html',
                    ),
                    'spec' => '/user-profile/edit%char%.%format%'
                )
            ),

            'login' => array (
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array (
                    'regex' => '/login?((?<char>[/]+))?(\.(?<format>(html)))?',
                    'defaults' => array (
                        'controller' => 'Frontend\Controller\User',
                        'action' => 'login',
                        'format' => 'html',
                    ),
                    'spec' => '/login%char%.%format%'
                )
            ),

            'logout' => array (
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array (
                    'regex' => '/logout?((?<char>[/]+))?(\.(?<format>(html)))?',
                    'defaults' => array (
                        'controller' => 'Frontend\Controller\User',
                        'action' => 'logout',
                        'format' => 'html',
                    ),
                    'spec' => '/logout%char%.%format%'
                )
            ),

            'forgotpassword' => array (
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array (
                    'regex' => '/forgotpassword?((?<char>[/]+))?(\.(?<format>(html)))?',
                    'defaults' => array (
                        'controller' => 'Frontend\Controller\User',
                        'action' => 'forgotpassword',
                        'format' => 'html',
                    ),
                    'spec' => '/forgotpassword%char%.%format%'
                )
            ),

            'changepassword' => array (
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array (
                    'regex' => '/changepassword?((?<char>[/]+))?(\.(?<format>(html)))?',
                    'defaults' => array (
                        'controller' => 'Frontend\Controller\User',
                        'action' => 'changepassword',
                        'format' => 'html',
                    ),
                    'spec' => '/changepassword%char%.%format%'
                )
            ),
          
          'about' => array (
            'type' => 'Zend\Mvc\Router\Http\Regex',
            'options' => array (
                'regex' => '/about?((?<char>[/]+))?(\.(?<format>(html)))?',
                'defaults' => array (
                    'controller' => 'Frontend\Controller\Page',
                    'action' => 'about',
                    'format' => 'html',
                ),
                'spec' => '/about%char%.%format%'
            )
          ),
          
          'contact' => array (
            'type' => 'Zend\Mvc\Router\Http\Regex',
            'options' => array (
                'regex' => '/contact?((?<char>[/]+))?(\.(?<format>(html)))?',
                'defaults' => array (
                    'controller' => 'Frontend\Controller\Page',
                    'action' => 'contact',
                    'format' => 'html',
                ),
                'spec' => '/contact%char%.%format%'
            )
          ),
          
          'faq' => array (
            'type' => 'Zend\Mvc\Router\Http\Regex',
            'options' => array (
                'regex' => '/faq?((?<char>[/]+))?(\.(?<format>(html)))?',
                'defaults' => array (
                    'controller' => 'Frontend\Controller\Page',
                    'action' => 'index',
                    'format' => 'html',
                ),
                'spec' => '/faq%char%.%format%'
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
    'view_helpers'    => array(
        'invokables'  => array(
            'header'        => 'Frontend\Block\header',
            'footer'        => 'Frontend\Block\footer', 
            'slider'        => 'Frontend\Block\slider',
            'payment'       => 'Frontend\Block\payment',
            'statitics'     => 'Frontend\Block\statitics', 
            'newsrelated'   => 'Frontend\Block\newsRelated', 
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
            'Frontend\Controller\News'  => 'Frontend\Controller\NewsController',
            'Frontend\Controller\User'  => 'Frontend\Controller\UserController',
            'Frontend\Controller\Page'  => 'Frontend\Controller\PageController',
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
