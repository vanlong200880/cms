<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
//use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\Feature;
class Language extends AbstractTableGateway
{
    protected $table = 'language';
    
    public function __construct() {
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
    // delete language
    public function deleteLanguage($arrayParam = null){
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
    public function getLanguageById($arrayParam = null){
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
    
    // check language is default
    public function languageIsDefault(){
        
        $select = new Select();
        $select->from($this->table);
        $select->where(array(
            'default' => 1
        ));
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        
        if($resultSet){
            return true;
        }else{
            return false;
        }
    }
    // add language
    public function addLanguage($arrayParam = null)
	{
		$data = array(
            'name'		=> $arrayParam['post']['name'], 
            'code'	=> $arrayParam['post']['code'], 
            'icon'		=> $arrayParam['post']['icon'],
            'status'		=> $arrayParam['post']['status'],
            'default'		=> $arrayParam['post']['default']
		);
		if(isset($arrayParam['id'])){
            // update
            if(empty($arrayParam['post']['icon'])){
                unset($data['icon']);
            }
            
            if($this->update($data, 'id = '.$arrayParam['id'])){
                return true;
            }else{
                return false;
            }
		}
		else{
			// add
            if($this->languageIsDefault()){
                $data['default'] = 0;
            }
			if($this->insert($data)){
                return true;
            }else{
                return false;
            }
		}
	}

}

