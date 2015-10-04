<?php

namespace Backend\Model;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Sql;

class Resource extends AbstractTableGateway{
	// ten bang
	protected $table = 'resource';
	
	//goi apdater
	public function __construct() {
		$this->featureSet = new Feature\FeatureSet();
		$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
		$this->initialize();
	}
	public function __call($method, $arguments) {
		parent::__call($method, $arguments);
	}
    // lay ra danh sach module
    public function getListResource($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $resultSet	= $this->selectWith($select);
		$resultSet = $resultSet->toArray();
        return $resultSet;
    }
    // validate module and controller exits
    public function validateModuleController($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('controller' => $arrayParam['post']['controller'], 'module' => $arrayParam['post']['module']));
        $resultSet	= $this->selectWith($select);
		$resultSet = $resultSet->toArray();
        return $resultSet;
    }
    
    
    // get resource by id
    public function getResourceById($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('id' => $arrayParam['id']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet[0];
    }
    // add resource
    public function addResource($arrayParam = null)
	{
		$data = array(
		  'module'		=> $arrayParam['post']['module'], 
		  'controller'	=> $arrayParam['post']['controller']
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
    // delete resource
    public function deleteResource($arrayParam = null){
        if($this->delete('id = ' . $arrayParam['id'])){
            return true;
        }
        else{
            return false;
        }
    }
}
