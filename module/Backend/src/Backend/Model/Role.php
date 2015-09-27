<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Sql;
use Backend\Model\UserRole;
use Backend\Model\RolePermission;
class Role extends AbstractTableGateway
{
    protected $table = 'role';
    
    public function __construct() {
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
    // lay ra tat ca cac role
	public function getAllRole(){
		$select = new Select();
		$select->from($this->table);
		$select->columns(array('id', 'role_name', 'description', 'status', 'weight'));
        $select->order('weight ASC');
		$resultSet = $this->selectWith($select);
		return $resultSet->toArray();
		
	}
    // change status
    public function changeStatus($arrayParam = null){
        $data = array('status' => $arrayParam['status']);
        if($this->update($data, 'id = '. $arrayParam['id'])){
            return true;
        }else {
            return false;
        }
    }
    public function updateWeight($arrayParam = null){
        $data = array('weight' => $arrayParam['weight']);
        if($this->update($data, 'id = '. $arrayParam['id'])){
            return true;
        }else {
            return false;
        }
    }
    // delete role
    public function deleteRole($arrayParam = null){
        $userRole = new UserRole();
        $rolePermision = new RolePermission();
        $userRole->deleteUserRoleByRoleId($arrayParam['id']);
        $rolePermision->deletePermisionByRole($arrayParam);
        if($this->delete('id = ' . $arrayParam['id'])){
            return true;
        }
        else{
            return false;
        }
    }
    // get role by id
    public function getRoleById($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array(
            'role.id = ' .$arrayParam['id']
        ));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet[0];
    }
    
    // get role by role_name
    public function getRoleByName($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where('role_name',$arrayParam['post']['role_name']);
        $resultSet = $this->selectWith($select);
        return $resultSet->toArray();
    }
    
    // lay ra role cua user 
    public function getRoleByUser($arrayParam = null)
    {
        $select = new Select();
        $where = new Where();
        $select->from($this->table);
        $select->join('user_role', 'role.id = user_role.role_rid', array('role_rid', 'user_id'), 'left');
        $select->where(array(
            'user_role.user_id = ' .$arrayParam['user_id'],
            'role.status = 1'
        ));
        $resultSet = $this->selectWith($select);
        return $resultSet->toArray();
    }
    // lay thong tin role
    public function auth($arrayParam = null)
    {
        $select = new Select();
        $select->from($this->table);
        $select->where->or->nest()->in('id', $arrayParam['id'])
                ->unnest()
                    ->equalTo('status',1);
        $resultSet = $this->selectWith($select);
        $dataReturn = $resultSet->toArray();
        if(count($dataReturn) > 0){
           return $dataReturn[0];
        }else{
           return false;
        }
    }
    
    public function addRole($arrayParam = null)
	{
		$data = array(
		  'role_name'		=> $arrayParam['post']['role_name'], 
		  'description'	=> $arrayParam['post']['description'], 
		  'status'		=> $arrayParam['post']['status'],
		  'weight'		=> $arrayParam['post']['weight']
		);
		if(isset($arrayParam['id'])){
            // update
            if($this->getRoleByName($arrayParam)){
                 var_dump($data);
                unset($data['role_name']);
            }
           
            if($this->update($data, 'id = '.$arrayParam['id'])){
                return true;
            }else{
                return false;
            }
		}
		else{
			// add
			if($this->insert($data)){
                return true;
            }else{
                return false;
            }
		}
	}

}

