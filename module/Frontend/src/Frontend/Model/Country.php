<?php 
namespace Frontend\Model;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;
class Country extends AbstractTableGateway{
    // Table name
    protected $table = 'country';
    /* Call apdater */
    public function __construct() {
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
    
    // get all category
    public function getAllCountry($arrayParam = null){
      $select = new Select();
      $select->from($this->table);
      $select->order('name ASC');
      $resultSet = $this->selectWith($select);
      $resultSet = $resultSet->toArray();
      return $resultSet;
  }

}