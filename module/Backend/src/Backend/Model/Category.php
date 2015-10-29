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
    // get all category
    public function getAllCategory($arrayParam = null){
        $select = new Select();
        $where = new Where();
        $select->from($this->table);
        $select->join('product', 'category.id = product.category_id', array('count' => new \Zend\Db\Sql\Expression('COUNT(product.id)')), 'left');
        $select->group('category.id');
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
//        $sort = array('fullname', 'id', 'email', 'role', 'status', 'birthday', 'created');
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
    
    // count all category
    // dem tong so user
    public function countAllCategory($arrayParam = null){
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
    
    // get category by taxonomy id
    public function getCategoryByTaxonomyId($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('taxonomy_id' => $arrayParam['id']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet;
    }
    // count category by parent
    public function getCategoryByParent($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('parent' => $arrayParam['parent']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet;
    }
    // get category by id
    public function getCategoryById($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('id' => $arrayParam['id']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet[0];
    }
    
    // get category by slug
    public function getCategoryBySlug($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->join('taxonomy', 'taxonomy.id = category.taxonomy_id', array('taxomony_id' => 'id'), 'left');
        $select->where(array('taxonomy.slug' => $arrayParam['slug']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet;
    }
    // update status category
    public function changeStatus($arrayParam = null){
        $data = array('status' => $arrayParam['status']);
        if($this->update($data, 'id = '. $arrayParam['id'])){
            return true;
        }else {
            return false;
        }
    }
    // add category
    public function addCategory($arrayParam = null)
	{
		$data = array(
            'parent'		=> $arrayParam['post']['parent'], 
            'name'          => $arrayParam['post']['name'],
            'slug'          => $arrayParam['post']['slug'],
            'excerpt'       => $arrayParam['post']['excerpt'],
            'created'       => $arrayParam['post']['created'],
            'changed'       => $arrayParam['post']['changed'],
            'title'         => $arrayParam['post']['title'],
            'keyword'       => $arrayParam['post']['keyword'],
            'description'	=> $arrayParam['post']['description'],
            'sort'          => $arrayParam['post']['sort'],
            'status'        => $arrayParam['post']['status'],
            'taxonomy_id'	=> $arrayParam['post']['taxonomy_id'],
		);
		if(isset($arrayParam['id'])){
            // update
            unset($data['created']);
            $data['changed'] = time();
            if($this->update($data, 'id = '.$arrayParam['id'])){
                return true;
            }else{
                return false;
            }
		}
		else{
			// add
            $data['created'] =  $data['changed'] = time();
			if($this->insert($data)){
                return $this->lastInsertValue;
            }else{
                return false;
            }
		}
	}
    public  function deleteCategoryById($id){
        $this->delete('id = '.$id);
    }
    
}

