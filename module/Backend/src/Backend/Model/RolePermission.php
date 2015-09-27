<?php

namespace Backend\Model;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Sql;

class RolePermission extends AbstractTableGateway{
	// ten bang
	protected $table = 'role_permission';
	
	//goi apdater
	public function __construct() {
		$this->featureSet = new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	public function __call($method, $arguments) {
		parent::__call($method, $arguments);
	}
	// delete permission by role id
    public function deletePermisionByRole($arrayParam = null){
        if($this->delete('role_id = '. $arrayParam['id'])){
            return true;
        }else{
            return false;
        }
    }
}
