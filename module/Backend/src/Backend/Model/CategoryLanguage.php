<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
//use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\Feature;
class CategoryLanguage extends AbstractTableGateway
{
    protected $table = 'category_language';
    
    public function __construct() {
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
    // delete language
    public function delete($arrayParam = null){
        if($this->delete('language_id = ' . $arrayParam['id'])){
            return true;
        }
        else{
            return false;
        }
    }
}

