<?php

namespace Sky\System;
use Zend\Permissions\Acl\Role\GenericRole;
class Acl extends \Zend\Permissions\Acl\Acl{
	protected $_id;
	
	public function __construct() {
		$info = new \Sky\System\Info();
		$this->_id = $roleId = 'group_'.$info->getGroupInfo('id');
		$this->addRole(new GenericRole($roleId));
		$this->allow($roleId, null, $info->getAcl());
	}
	public function isValid($privilege = null){
		$flagAcess = false;
		if($this->isAllowed($this->_id, null, $privilege) == true){
			$flagAcess = true;
		}
		return $flagAcess;
	}
}