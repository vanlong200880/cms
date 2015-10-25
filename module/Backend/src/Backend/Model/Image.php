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
            'mine'          => $arrayParam['dataImage']['mine'],
            'size'          => $arrayParam['dataImage']['size'],
            'timestamp'     => $arrayParam['dataImage']['timestamp'],
            'status'        => $arrayParam['dataImage']['status'],
            'highlight'     => $arrayParam['dataImage']['highlight']
		);
        if(isset($arrayParam['id'])){
            // update
            unset($data['product_id'], $data['type']);
            $this->update($data, array('id' => $arrayParam['image_id'], 'highlight' => 1));
        }else{
            if($this->insert($data)){
                return $this->lastInsertValue;
            }else{
                return false;
            }
        }
        
	}
    // get image by product id
    public function getImageByProductId($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('product_id' => $arrayParam['id'], 'type' => $arrayParam['type']));
        $resultSet = $this->selectWith($select);
        return $resultSet->toArray();
    }
    
    // get image by id
    public  function getImageById($arrayPram = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('id' => $arrayPram['id']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet;
    }
    // get image by id and highlight
    public  function getImageByProductIdHighlight($arrayPram = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('product_id' => $arrayPram['id'], 'highlight' => 1));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet;
    }
    // delete product by id
    public function deleteImageById($arrayParam = null){
        if($this->delete('id = ' . $arrayParam['id'])){
            return true;
        }
        else{
            return false;
        }
    }
    
    // delete image by product id and type
    public function deleteImageByProductId($arrayParam = null){
       if($this->delete(array('product_id = ' . $arrayParam['id'], 'type' => $arrayParam['type']))){
           return true;
       } else{
           return false;
       }
    }


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

