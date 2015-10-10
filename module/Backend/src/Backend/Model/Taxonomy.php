<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
class Taxonomy extends AbstractTableGateway
{
    protected $table = 'taxonomy';
    
    public function __construct() {
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
    // delete language
    public function deleteTaxonomy($arrayParam = null){
        $userRole = new UserRole();
//        $rolePermision = new RolePermission();
//        $userRole->deleteUserRoleByRoleId($arrayParam['id']);
//        $rolePermision->deletePermisionByRole($arrayParam);
        if($this->delete('id = ' . $arrayParam['id'])){
            return true;
        }
        else{
            return false;
        }
    }
    // get role by id
    public function changeStatus($arrayParam = null){
        $data = array('status' => $arrayParam['status']);
        if($this->update($data, 'id = '. $arrayParam['id'])){
            return true;
        }else {
            return false;
        }
    }
    
    // get language by id
    public function getTaxonomyById($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where(array('id' => $arrayParam['id']));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet[0];
    }
    
    // get all language
    public function getAll()
    {
        $select = new Select();
        $select->from($this->table);
        $resultSet = $this->selectWith($select);
        return $resultSet->toArray();
    }
    
    // add language
    public function addTaxonomy($arrayParam = null)
	{
		$data = array(
            'name'		=> $arrayParam['post']['name'], 
            'slug'      => $arrayParam['post']['slug'],
            'status'    => $arrayParam['post']['status']
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

}

