<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
class Product extends AbstractTableGateway
{
    protected $table = 'product';
    
    public function __construct() {
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
    // delete language
    public function deleteNews($id){
        $this->delete('id = '.$id);
    }
    
    // get category by taxonomy id
    public function getCategoryByTaxonomyId($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('taxonomy_id' => $arrayParam['id']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet;
    }
    
    // get news by category id
    public function getNewsByCategoryId($paramId = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('category_id' => $paramId['id']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet;
    }
}

