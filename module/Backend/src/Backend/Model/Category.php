<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
class Category extends AbstractTableGateway
{
    protected $table = 'category';
    
    public function __construct() {
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
    // delete language
    public function delete($arrayParam = null){
        if($this->delete('id = ' . $arrayParam['id'])){
            return true;
        }
        else{
            return false;
        }
    }
    
    // get category by taxonomy id
    public function getCategoryByTaxonomyId($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        //$select->where(array('taxonomy_id' => $arrayParam['id']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet;
    }
    // count category by parent
    public function countCategoryByParent($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('parent' => $arrayParam['parent']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet;
    }
    
}

