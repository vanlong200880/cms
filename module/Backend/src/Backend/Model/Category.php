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
    
    // get all category
    public function getAllCategory($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
//        $select->columns(array('id' ,'name', 'description', 'sort', 'status','count' => new \Zend\Db\Sql\Expression('COUNT(product.id)')));
//        $select->join('product', 'category.id = product.category_id',array(), 'left');
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet;
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
    public function countCategoryByParent($arrayParam = null){
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
                return true;
            }else{
                return false;
            }
		}
	}
    
}

