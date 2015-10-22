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
    // get all product
    public function getAllProduct($arrayParam = null){
        $select = new Select();
        $where = new Where();
        $select->from($this->table);
        $select->join('category', 'product.category_id = category.id', array('category-name' => 'name'), 'left');
        // search name
        if(isset($arrayParam['textSearch']) == true && $arrayParam['textSearch'] != ''){
            if(isset($arrayParam['sort']) && $arrayParam['sort'] == 'id'){
                $where->like('product.code', '%' .$arrayParam['textSearch']. '%');
                $select->where($where);
                
            }else{
                $where->like('product.name', '%' . $arrayParam['textSearch'] . '%');
                $select->where($where);
            }
	   		
	   	}
        // phan trang
        if(isset($arrayParam['limit']) && $arrayParam['limit'] !== ''){
            $select->limit($arrayParam['limit'])->offset($arrayParam['offset']);
        }
        // search by category
        if(isset($arrayParam['type']) == true && $arrayParam['type'] != ''){
            $select->where('product.category_id = '. $arrayParam['type']);
	   	}
        // trang thai
        if(isset($arrayParam['status']) && $arrayParam['status'] != ''){
            $select->where('status = '. $arrayParam['status']);
        }
        
        // filter
        if(isset($arrayParam['sort']) && $arrayParam['sort'] !== ''){
            if(isset($arrayParam['order']) == true && $arrayParam['order'] != ''){
                $select->order($arrayParam['sort'] .' ' . $arrayParam['order']);
            }else{
                $select->order($arrayParam['sort'] .' desc');
            }
        }else{
            $select->order('id DESC');
        }
        
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet;
    }
    // count total product
    public function countTotalProduct($arrayParam = null){
        $select = new Select();
        $where = new Where();
        $select->from($this->table);
        $select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT('.$this->table.'.id)')));
        // text search
        if(isset($arrayParam['textSearch']) == true && $arrayParam['textSearch'] != ''){
	   		$where->like('name', '%' . $arrayParam['textSearch'] . '%');
	   		$select->where($where);
	   	}
        // search by category
        if(isset($arrayParam['type']) == true && $arrayParam['type'] != ''){
            $select->where('product.category_id = '. $arrayParam['type']);
	   	}
        // trang thai
        if(isset($arrayParam['status']) && $arrayParam['status'] != ''){
            $select->where('status = '. $arrayParam['status']);
        }
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet[0];
    }
    // delete product by id
    public function deleteProductById($id){
        $this->delete('id = '.$id);
    }
    // update status product
    public function changeStatus($arrayParam = null){
        $data = array('status' => $arrayParam['status']);
        if($this->update($data, 'id = '. $arrayParam['id'])){
            return true;
        }else {
            return false;
        }
    }
    // get product by category id
    public function getProductByCategoryId($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('category_id' => $arrayParam['id']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet;
    }
    // get category by id
    public function getProductById($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('id' => $arrayParam['id']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet[0];
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
    
    // update status
	public function updateStatus($arrayParam = null){
        $data = array(
		  'status' => $arrayParam['status']
		);
        if(isset($arrayParam['post']['function'])){
            if($arrayParam['post']['function'] == 'published'){
                $data['status'] = 1;
            }else{
                 if($arrayParam['post']['function'] == 'unpublished'){
                     $data['status'] = 0;
                 }
            }
        }
        if(isset($arrayParam['post']['check-all']) && !empty($arrayParam['post']['check-all'])){
                foreach ($arrayParam['post']['check-all'] as $value){
                    $arrId['id'] = $value;
                    if($this->getProductById($arrId)){
                        $this->update($data, 'id = '. $value);
                    }
                }
            return true;
        }else{
            return false;
        }
	}
    
    // update sort by id
	public function updateSortById($dataSort = null){
        $data = array(
		  'sort' => $dataSort['sort']
		);
        $this->update($data, 'id = '. $dataSort['id']);
	}
	
	// add product
	public function addProduct($arrayParam = null)
	{
		$data = array(
            'trademark_id'  => $arrayParam['post']['trademark_id'],
            'supplier_id'   => $arrayParam['post']['supplier_id'],
            'shop_id'       => $arrayParam['post']['shop_id'],
            'category_id'   => $arrayParam['post']['category_id'],
            'name'          => $arrayParam['post']['name'],
            'slug'          => $arrayParam['post']['slug'],
            'excerpt'       => $arrayParam['post']['excerpt'],
            'content'       => $arrayParam['post']['content'],
            'status'        => $arrayParam['post']['status'],
            'created'       => $arrayParam['post']['created'],
            'modified'      => $arrayParam['post']['modified'],
            'weight'        => $arrayParam['post']['weight'],
            'sale'          => $arrayParam['post']['sale'],
            'hot'           => $arrayParam['post']['hot'],
            'sticky'        => $arrayParam['post']['sticky'],
            'promote'       => $arrayParam['post']['promote'],
            'color'         => $arrayParam['post']['color'],
            'size'          => $arrayParam['post']['size'],
            'sort'          => $arrayParam['post']['sort'],
            'cost'          => $arrayParam['post']['cost'],
            'price'         => $arrayParam['post']['price'],
            'view'          => $arrayParam['post']['view'],
            'quantity'      => $arrayParam['post']['quantity'],
            'user_id'       => $arrayParam['post']['user_id'],
            'startday'      => $arrayParam['post']['startday'],
            'endday'        => $arrayParam['post']['endday']
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
                return $this->lastInsertValue;
            }else{
                return false;
            }
		}
	}
}

