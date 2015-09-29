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
}
