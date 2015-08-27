<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Frontend;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Facebook\FacebookSession;
use Zend\Session\Container;
use Frontend\Model\UserHasGroup;
use Zend\Cache\StorageFactory;
class Module
{
    public function onBootstrap(MvcEvent $e)
    {
    	
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'), 100);
//        FacebookSession::setDefaultApplication('640478992715128', '5caadc63c88cd635aad3ae570ec267d5');
       // Start Set Layout
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
        	$controller = $e->getTarget();
        	$controllerClass = get_class($controller);
        	$moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        	$config = $e->getApplication()->getServiceManager()->get('config');
        	if (isset($config['module_layouts'][$moduleNamespace])) {
        		$controller->layout($config['module_layouts'][$moduleNamespace]);
        	}
        }, 100);
        // End Set Layout
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function(MvcEvent $event) {
        		$viewModel = $event->getViewModel();
        		$viewModel->setTemplate('layout/frontend');
        }, -200);
    }

    public function preDispatch(MvcEvent $e)
    {
    
//    	$session = new Container('memberlogin');
//    	$checkUser = '';
//    	if(isset($_COOKIE["checkUser"]) == true){
//    		$checkUser = $_COOKIE["checkUser"];
//    	}
//    	if(empty($session->user['id']) == false && $checkUser == ''){
//    		$data = array('ip' => $_SERVER['REMOTE_ADDR'], 'email' => $session->user['email']);
//    		$endcode = new \Sky\Auth\Encode();
//    		$dataEndcode = $endcode->encodeKey(serialize($data));
//    		setcookie('checkUser', $dataEndcode, time()+60*60*24*30, '/', '.hdonline.vn');
//    	
//    	}else if(empty($session->user['id']) == true && $checkUser != ''){
//    		$endcode = new \Sky\Auth\Encode();
//    		$dataEndcode = $endcode->decodeKey($checkUser);
//    		$dataEndcode = unserialize($dataEndcode);
//    		if($dataEndcode['ip'] == $_SERVER['REMOTE_ADDR']){
//    			$URI = SERVER_ID . '/api/user';
//    			$options = array('location' => $URI, 'uri' => $URI);
//    			$client = new \Zend\Soap\Client(null, $options);
//    			$dataUser = $client->getUser(KEY_API, $dataEndcode['email'], 0);
//    			if($dataUser != false){
//    				$dataUser = json_decode($dataUser, true);
//    				$modelUserHasGroup = new UserHasGroup();
//    				$dataGroup = $modelUserHasGroup->auth($dataUser);
//    				if($dataGroup != false){
//    					$dataUser['group_id'] = $dataGroup['group_id'];
//    				}else{
//    					$dataUser['group_id'] = IDMEMBER;
//    				}
//    				
//    				$session->user = $dataUser;
//    			}
//    		}
//    	}
    }
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
    	return array(
    			'factories' => array(
    			),
    	);
    }
 
}
