<?php

namespace Backend\Model;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Sql;

class Functions extends AbstractTableGateway{
	// ten bang
	protected $table = 'functions';
	
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
		$select->from(array('f' => $this->table))
				->join(array('gf' => 'fucntions_has_group'), new \Zend\Db\Sql\Expression('gf.functions_id = f.id'), '*', 'left');
		$select->where('gf.id = ' .$arrayparam['id'])
				->where('gf.status = 1')
				->where('f.status = 1');
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
}
