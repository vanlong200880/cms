<?php
namespace Sky\System;
use Zend\Session\Container;
class Permission{
	public static function checkAcl($arrayparam = null)
	{
		$dataModule = explode('\\', $arrayparam['controller']);
		$nameModule = strtolower($dataModule[0]);
        $nameController = $dataModule[2];
		if($nameModule === 'backend'){
			$session = new Container(APPLICATION_KEY);
			if(isset($session->auth['userId']) == false){
				return array('__NAMESPACE__' => 'Backend\Controller', 'controller' => 'Backend\Controller\Public', 'action' => 'login', '__CONTROLLER__' => 'public');
			}else{
				$info = new \Sky\System\Info();
				if($info->getGroupInfo('name')){
					if($info->getGroupInfo('limit') != 1){
						$acl = new \Sky\System\Acl();
						$privilege = $dataModule[0]. '_'. $nameController . '_'. $arrayparam['action'];
						if($acl->isValid($privilege) == false){
							return $arrayReturn = array('__NAMESPACE__' => 'Backend\Controller', 'controller' => 'Backend\Controller\Public', 'action' => 'noaceess', '__CONTROLLER__' => 'public');
						}
					}
				}else{
					return $arrayReturn = array('__NAMESPACE__' => 'Backend\Controller', 'controller' => 'Backend\Controller\Public', 'action' => 'noaccess', '__CONTROLLER__' => 'public');
				}
			}
		}
	}
}
