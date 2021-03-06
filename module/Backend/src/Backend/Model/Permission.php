<?php

namespace Backend\Model;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Sql;

class Permission extends AbstractTableGateway{
	// ten bang
	protected $table = 'permission';
	
	//goi apdater
	public function __construct() {
		$this->featureSet = new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	public function __call($method, $arguments) {
		parent::__call($method, $arguments);
	}
	
	// list functions
	public function listItem($arrayparam = null){
		$select = new Select();
		$where = new Where();
		$select->from($this->table);
		
		// phan trang
		if(isset($arrayparam['limit']) == true && $arrayparam['limit'] != ''){
			$select->limit($arrayparam['limit'])->offset($arrayparam['offset']);
		}
		
		// tim kiem
		if(isset($arrayparam['textSearch']) == true && $arrayparam != ''){
			$where->like('name', '%' . $arrayparam['textSearch']. '%');
			$select->where($where);
		}
		
		// sap xep
		if(isset($arrayparam['dataFilter']['col']) && isset($arrayparam['dataFilter']['order'])){
			$select->order($arrayparam['dataFilter']['col'] . ' ' . $arrayparam['dataFilter']['order']);
		}
		$resultSet = $this->selectWith($select);
		$resultSet = $resultSet->toArray();
		return $resultSet;
	}
	
	//dem ton so chuc nang
	public function countItem($arrayParam = null){
		$select = new Select();
		$select->from($this->table)->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT('.$this->table.'.id)')));
		if(isset($arrayParam['textSearch']) == true && $arrayParam['textSearch'] != ''){
			$where = new Where();
			$where->like('name', '%'. $arrayParam .'%');
			$select->where($where);
		}
		$resultSet = $this->selectWith($select);
		return $resultSet->toArray();
	}
	
	// thong tin function
	public function getItem($arrayparam = null){
		$select = new Select();
		$select->from($this->table);
		$select->where('functions.id = '.$arrayparam['id']);
		$resultSet	= $this->selectWith($select);
		$dataReturl = $resultSet->toArray();
		return $dataReturl[0];
	}
	// Luu thong tin functions
	public function saveData($arrayparam = null){
		$data = array(
			'name'					=> $arrayparam['post']['name'],
			'name_module'			=> $arrayparam['post']['name_module'],
			'name_controller'		=> $arrayparam['post']['name_controller'],
			'name_action'			=> $arrayparam['post']['name_action'],
			'status'				=> $arrayparam['post']['status']
		);
		if(isset($arrayparam['id']) == true && $arrayparam['id'] != ''){
			// edit
			$this->update($data, 'id = '.$arrayparam['id']);
			$id = $arrayparam['id'];
		}else{
			// add
			$this->insert($data);
			$id = $this->getLastInsertValue();
		}
		return $id;
	}
	
	// thay doi status
	public function changeStatus($arrayParam = null){
		$data = array('status' => $arrayParam['status']);
		$this->update($data, 'id = '. $arrayParam['id']);
	}
	
	// xoa function 
	public function deleteItem($arrayparam = null){
		$this->delete('id = '. $arrayparam['id']);
	}
    // lay thong tin acl
	public function acl($arrayparam = null){
		$sql = new Sql(\Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter());
		$select = $sql->select();
//		$select->from(array('f' => $this->table))
//				->join(array('gf' => 'fucntions_has_group'), new \Zend\Db\Sql\Expression('gf.functions_id = f.id'), '*', 'left');
//		$select->where('gf.id = ' .$arrayparam['id'])
//				->where('gf.status = 1')
//				->where('f.status = 1');
        $select->from($this->table);
        $select->join('role_permission', 'permission.id = role_permission.permission_id', array('permission_id', 'role_id'), 'left');
        $select->join('role', 'role.id = role_permission.role_id', array(), 'left');
        $select->join('resource', 'resource.id = permission.resource_id', array('module', 'controller'));
        $select->where->or->nest()->in('role.id', array($arrayparam['role']))
                ->unnest()
                    ->equalTo('role.status',1);
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		return \Zend\Stdlib\ArrayUtils::iteratorToArray($result);
	}
	
	// get list functions
	public function getListFunctions($arrayparam = null){
		$select = new Select();
		$select->from($this->table);
		$select->columns(array('id'));
		$resultSet = $this->selectWith($select);
		$dataReturn = $resultSet->toArray();
		if(count($dataReturn) == 0){
			$dataReturn = false;
		}
		return $dataReturn;
	}
    // get list all permission
    public function getAllPermission($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $resultSet	= $this->selectWith($select);
		$resultSet = $resultSet->toArray();
        return $resultSet;
    }
    // lay ra danh sach action
    public function getListPermissionAction($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where('resource_id = '. $arrayParam['id']);
        $resultSet	= $this->selectWith($select);
		$resultSet = $resultSet->toArray();
        return $resultSet;
    }
    
    // get list permission by resource id
    public function getListPermissionByResourceId($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where('resource_id = '. $arrayParam['id']);
        $resultSet	= $this->selectWith($select);
		$resultSet = $resultSet->toArray();
        return $resultSet;
    }
    
    // get permission by id
    public function getPermissionById($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where('id = '. $arrayParam['id']);
        $resultSet	= $this->selectWith($select);
		$resultSet = $resultSet->toArray();
        return $resultSet[0];
    }
    // delete permission by resource id
    public function deletePermissionByReourceId($arrayParam = null){
        if($this->delete('resource_id = ' . $arrayParam['id'])){
            return true;
        }
        else{
            return false;
        }
    }
    
    // delete permission by id
    public function deletePermissionById($arrayParam = null){
        if($this->delete('id = ' . $arrayParam['id'])){
            return true;
        }
        else{
            return false;
        }
    }
    
    // validate permission name and resource id
    public function validatePermissionNameResourceId($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('name' => $arrayParam['post']['name'], 'resource_id' => $arrayParam['post']['resource_id']));
        $resultSet	= $this->selectWith($select);
		$resultSet = $resultSet->toArray();
        return $resultSet;
    }
    
    // add permission
    public function addPermission($arrayParam = null)
	{
		$data = array(
            'name'		=> $arrayParam['post']['name'], 
            'resource_id'		=> $arrayParam['post']['resource_id'], 
            'description'	=> $arrayParam['post']['description']
		);
		if(isset($arrayParam['id'])){
            // update
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
