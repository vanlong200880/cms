<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
class Supplier extends AbstractTableGateway
{
    protected $table = 'supplier';
    
    public function __construct() {
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
    // delete language
//    public function delete($arrayParam = null){
//        if($this->delete('id = ' . $arrayParam['id'])){
//            return true;
//        }
//        else{
//            return false;
//        }
//    }
    
    // get all supplier
    public function getAllSupplier($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet;
    }
    public function getAll($arrayParam = null){
        $select = new Select();
        $where = new Where();
        $select->from($this->table);
				$select->join('product', 'supplier.id = product.category_id', array('count' => new \Zend\Db\Sql\Expression('COUNT(product.id)')), 'left');
        $select->group('supplier.id');
        // text search
        if(isset($arrayParam['textSearch']) == true && $arrayParam['textSearch'] != ''){
	   		$where->like('name', '%' . $arrayParam['textSearch'] . '%');
	   		$select->where($where);
	   	}
        // phan trang
        if(isset($arrayParam['limit']) && $arrayParam['limit'] !== ''){
            $select->limit($arrayParam['limit'])->offset($arrayParam['offset']);
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
    
    // count all supplier
    public function countAllSupplier($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT('.$this->table.'.id)')));
        if(isset($arrayParam['txtSearch']) === true && $arrayParam['txtSearch'] != ''){
            $where = new Where();
            $where->like('name', '%'. $arrayParam['txtSearch']. '%');
            $select->where($where);
        }
        // trang thai
        if(isset($arrayParam['status'])){
            $select->where('status = '. $arrayParam['status']);
        }
        $resultSet = $this->selectWith($select);
        return $resultSet->toArray();
    }
		
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
                    $arrayParam['id'] = $value;
                    if($this->getSupplierById($arrayParam)){
                        $this->update($data, 'id = '. $value);
                    }
                }
            return true;
        }else{
            return false;
        }
	}
	
	public function updateSortById($dataSort = null){
        $data = array(
		  'order' => $dataSort['sort']
		);
        $this->update($data, 'id = '.$dataSort['id']);
	}
	public  function deleteSupplierById($id){
		return $this->delete('id = '.$id);
	}
	
    
    // get category by taxonomy id
//    public function getCategoryByTaxonomyId($arrayParam = null){
//        $select = new Select();
//        $select->from($this->table);
//        $select->where(array('taxonomy_id' => $arrayParam['id']));
//        $resultSet = $this->selectWith($select);
//        $resultSet = $resultSet->toArray();
//        return $resultSet;
//    }
    // count category by parent
//    public function countCategoryByParent($arrayParam = null){
//        $select = new Select();
//        $select->from($this->table);
//        $select->where(array('parent' => $arrayParam['parent']));
//        $resultSet = $this->selectWith($select);
//        $resultSet = $resultSet->toArray();
//        return $resultSet;
//    }
    // get supplier by id
    public function getSupplierById($arrayParam = null){
			$select = new Select();
			$select->from($this->table);
			$select->where(array('id' => $arrayParam['id']));
			$resultSet = $this->selectWith($select);
			$resultSet = $resultSet->toArray();
			return $resultSet[0];
    }
    
    // get category by slug
//    public function getCategoryBySlug($arrayParam = null){
//        $select = new Select();
//        $select->from($this->table);
//        $select->join('taxonomy', 'taxonomy.id = category.taxonomy_id', array('taxomony_id' => 'id'), 'left');
//        $select->where(array('taxonomy.slug' => $arrayParam['slug']));
//        $resultSet = $this->selectWith($select);
//        $resultSet = $resultSet->toArray();
//        return $resultSet;
//    }
    // update status category
//    public function changeStatus($arrayParam = null){
//        $data = array('status' => $arrayParam['status']);
//        if($this->update($data, 'id = '. $arrayParam['id'])){
//            return true;
//        }else {
//            return false;
//        }
//    }
    // add category
    public function addSupplier($arrayParam = null)
	{
		$data = array(
            'name'              => $arrayParam['post']['name'],
						'slug'              => $arrayParam['post']['slug'],
            'phone'             => $arrayParam['post']['phone'],
            'address'           => $arrayParam['post']['address'],
            'email'             => $arrayParam['post']['email'],
            'companyname'       => $arrayParam['post']['companyname'],
            'tax'               => $arrayParam['post']['tax'],
            'note'              => $arrayParam['post']['note'],
            'account'           => $arrayParam['post']['account'],
            'status'            => $arrayParam['post']['status'],
						'order'             => $arrayParam['post']['order']
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
	// update status
	public function changeStatus($arrayParam = null){
			$data = array('status' => $arrayParam['status']);
			if($this->update($data, 'id = '. $arrayParam['id'])){
					return true;
			}else {
					return false;
			}
	}
}

