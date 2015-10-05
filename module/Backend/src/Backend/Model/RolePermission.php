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
    
    // check role by permission 
    public function getRoleByPermissionId($id, $roleid){
        $select = new Select();
        $select->from($this->table);
        $select->where('permission_id = '. $id);
        $select->where('role_id = '. $roleid);
        $resultSet	= $this->selectWith($select);
		$resultSet = $resultSet->toArray();
        return $resultSet;
    }
    
    // add role permission
    public function addRolePermission($arrayParam = null)
	{
		// delete role_permission current
        $this->deletePermisionByRole($arrayParam);
        // add
        if(isset($arrayParam['permission'])){
            foreach ($arrayParam['permission'] as $val){
                $data = array(
                    'permission_id'		=> $val, 
                    'role_id'   => $arrayParam['id']
                );
                $this->insert($data);
            }
        }
	}
    // delete role_permission by role_id
    public function deleteRolePermissionByRoleId($arrayData = null){
        if($this->delete('role_id = ' . $arrayData['id'])){
            return true;
        }
        else{
            return false;
            }
    }
    
    // delete role_permission by permission id
    public function deleteRolePermissionByPermissionId($id){
        $this->delete('permission_id = ' . $id);
    }
    
    // delete all record in table
    public function deleteAllRecord(){
        if($this->delete('1=1')){
            return true;
        }else{
            return false;
        }
    }
    
    // insert record in table
    public function addAllRecord($arrayParam = null){
        
        $data = array(
            'permission_id' => $arrayParam['permission_id'],
            'role_id'       => $arrayParam['role_id']
        );
//        $data = array(
//            'permission_id' => 1,
//            'role_id'       => 4
//        );
        
//        var_dump($data); die;
        $this->insert($data);
//        die;
    }
}
