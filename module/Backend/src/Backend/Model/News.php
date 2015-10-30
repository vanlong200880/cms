<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
class News extends AbstractTableGateway
{
    protected $table = 'news';
    
    public function __construct() {
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
    // delete language
    public function deleteNews($id){
        $this->delete('id = '.$id);
    }
    
    public function getAllNews($arrayParam = null){
        $select = new Select();
        $where = new Where();
        $select->from($this->table);
        $select->join('category', 'news.category_id = category.id', array('category-name' => 'name'), 'left');
        // search name
        if(isset($arrayParam['textSearch']) == true && $arrayParam['textSearch'] != ''){
            if(isset($arrayParam['sort']) && $arrayParam['sort'] == 'id'){
                $where->like('news.id', '%' .$arrayParam['textSearch']. '%');
                $select->where($where);
            }else{
                $where->like('news.name', '%' . $arrayParam['textSearch'] . '%');
                $select->where($where);
            }
	   	}
        // phan trang
        if(isset($arrayParam['limit']) && $arrayParam['limit'] !== ''){
            $select->limit($arrayParam['limit'])->offset($arrayParam['offset']);
        }
        // search by category
        if(isset($arrayParam['type']) == true && $arrayParam['type'] != ''){
            $select->where('news.category_id = '. $arrayParam['type']);
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
    
    public function countTotalNews($arrayParam = null){
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
            $select->where('news.category_id = '. $arrayParam['type']);
	   	}
        // trang thai
        if(isset($arrayParam['status']) && $arrayParam['status'] != ''){
            $select->where('status = '. $arrayParam['status']);
        }
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
    
    public function addNews($arrayParam = null)
	{
		$data = array(
            'name'          => $arrayParam['post']['name'], 
            'slug'          => $arrayParam['post']['slug'], 
            'excerpt'		=> $arrayParam['post']['excerpt'],
            'content'		=> $arrayParam['post']['content'],
            'image'         => $arrayParam['post']['image'],
            'created'		=> $arrayParam['post']['created'],
            'modified'		=> $arrayParam['post']['modified'],
            'title'         => $arrayParam['post']['title'],
            'description'	=> $arrayParam['post']['description'],
            'keyword'		=> $arrayParam['post']['keyword'],
            'sort'          => $arrayParam['post']['sort'],
            'startday'      => $arrayParam['post']['startday'],
            'endday'        => $arrayParam['post']['endday'],
            'category_id'	=> $arrayParam['post']['category_id'],
            'user_id'		=> $arrayParam['post']['user_id']
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
                return true;
            }else{
                return false;
            }
		}
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
                    $arrId['id'] = $value;
                    if($this->getNewById($arrId)){
                        $this->update($data, 'id = '. $value);
                    }
                }
            return true;
        }else{
            return false;
        }
	}
    public function getNewById($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('id' => $arrayParam['id']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet[0];
    }
    public function updateSortById($dataSort = null){
        $data = array(
		  'sort' => $dataSort['sort']
		);
        $this->update($data, 'id = '. $dataSort['id']);
	}
    
    // update status news
    public function changeStatus($arrayParam = null){
        $data = array('status' => $arrayParam['status']);
        if($this->update($data, 'id = '. $arrayParam['id'])){
            return true;
        }else {
            return false;
        }
    }
}

