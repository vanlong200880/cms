<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
class Image extends AbstractTableGateway
{
    protected $table = 'image';
    
    public function __construct() {
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
    // add image
    public function addImage($arrayParam = null)
	{
		$data = array(
            'product_id'    => $arrayParam['dataImage']['product_id'],
            'type'          => $arrayParam['dataImage']['type'],
            'name'          => $arrayParam['dataImage']['name'],
            'timestamp'     => $arrayParam['dataImage']['timestamp'],
            'status'        => $arrayParam['dataImage']['status'],
            'highlight'     => $arrayParam['dataImage']['highlight']
		);
        if($this->insert($data)){
            return $this->lastInsertValue;
        }else{
            return false;
        }
	}
    // delete product by id
//    public function deleteImageById($id){
//        $this->delete('id = '.$id);
//    }
    
    // get image by product id and type = product
//    public function getImageByProductId($arrayParam = null){
//        $select = new Select();
//        $select->from($this->table);
//        $select->where(array('product_id' => $arrayParam['id'], 'type' => 'product'));
//        $resultSet = $this->selectWith($select);
//        $resultSet = $resultSet->toArray();
//        return $resultSet;
//    }
    
    // get news by category id
//    public function getNewsByCategoryId($paramId = null){
//        $select = new Select();
//        $select->from($this->table);
//        $select->where(array('category_id' => $paramId['id']));
//        $resultSet = $this->selectWith($select);
//        $resultSet = $resultSet->toArray();
//        return $resultSet;
//    }
}

