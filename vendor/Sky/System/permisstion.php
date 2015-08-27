<?php
namespace Sky\System;
use Zend\Session\Container;
class Permission{
	public static function checkAcl($arrayparam = null)
	{
		$nameModule = explode('\\', $arrayparam['controller']);
		$nameModule = strtolower($nameModule[0]);
		if($nameModule === 'backend'){
			$session = new Container(APPLICATION_KEY);
			if(isset($session->auth->id) == false){
				return array('__NAMESPACE__' => 'Backen\Controller', 'controller' => 'Backend\Controller\Public', 'action' => 'login', '__CONTROLLER__' => 'public');
			}else{
				$info = new \Sky\System\Info();
				if($info->getGroupInfo('cpanel') == 1){
					if($info->getGroupInfo('limit') != 1){
						$acl = new \Sky\System\Acl();
						$privilege = strtolower($nameModule). '_'. $arrayparam['__CONTROLLER__']. '_'. $arrayparam['action'];
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
