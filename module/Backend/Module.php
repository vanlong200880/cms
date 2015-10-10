<?php

namespace Backend;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\Feature;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
		$eventManager			= $e->getApplication()->getEventManager();
		$moduleRouteListener	= new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);
        
		$eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'), 100);
		$eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 100);
        
		// start set layout
		$e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e){
			$controller = $e->getTarget();
			$controllerClass = get_class($controller);
			$moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
			$config = $e->getApplication()->getServiceManager()->get('config');
			if(isset($config['module_layout'][$moduleNamespace])){
				$controller->layout($config['module_layout'][$moduleNamespace]);
			}
		}, 100);
		
        $translator = $e->getApplication()->getServiceManager()->get('translator');
	    $translator->addTranslationFile('phpArray', 
								        'vendor/Sky/Validate.php',
								    	'default');
	    \Zend\Validator\AbstractValidator::setDefaultTranslator($translator);
	  	$serviceManager = $e->getApplication()->getServiceManager();
    	$dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
    	Feature\GlobalAdapterFeature::setStaticAdapter($dbAdapter);
    	$e->stopPropagation(); 
    }
    public function getViewHelperConfig()
    {
    	return array(
            'factories' => array(
//                'adminmenu'   =>  function(){
//                    $helper = new \Backend\Block\adminheader();
//                    return $helper;
//                },
            ),
        );
    }
    
	function onDispatchError(MvcEvent $e)
	{
		$vm = $e->getViewModel();
		$vm->setTemplate('layout/login');
	}
	public function preDispatch(MvcEvent $e){
//		$data = $e->getRouteMatch()->getParams();
//		$dataAcl = \Sky\System\Permission::checkAcl($data);
//		if(!empty($dataAcl) && $data['action'] != $dataAcl['action']){
//			$url = $e->getRouter()->assemble(array('controller' => $dataAcl['__CONTROLLER__'], 'action' => $dataAcl['action']), array('name' => 'backend'));
//			$reponse = $e->getResponse();
//			$reponse->getHeaders()->addHeaderLine('Location', $url);
//			$reponse->setStatusCode(302);
//			$reponse->sendHeaders();
//		}
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
}
