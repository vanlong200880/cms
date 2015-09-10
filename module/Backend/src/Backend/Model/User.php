<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Sql;
class User extends AbstractTableGateway
{
    protected $table = 'user';
    
    public function __construct() {
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
    
    // kiem tra user login
    function userLogin($arrayParam = null)
    {
        $select = new Select();
        $select->from($this->table);
        $select->columns(array('id', 'email', 'password', 'fullname', 'birthday', 'sex', 'address', 'active', 'created', 'avartar'));
        $select->where(array(
            "email = '". $arrayParam['email']."'",
            'password = ' .$arrayParam['password'],
            'active = 1'
        ));
        $resultSet = $this->selectWith($select);
        $dataReturn = $resultSet->toArray();
        if(!empty($dataReturn))
        {
            return $dataReturn[0];
        }
        else
        {
            return false;
        }
    }
	
	// create new use
	public function addUser($arrayParam = null)
	{
		$data = array(
		  'email'		=> $arrayParam['post']['email'], 
		  'password'	=> $arrayParam['post']['password'], 
		  'salt'		=> $arrayParam['post']['salt'],  
		  'fullname'	=> $arrayParam['post']['fullname'], 
		  'alias'		=> $arrayParam['post']['name-alias'], 
		  'birthday'	=> $arrayParam['post']['birthday'],  
		  'sex'			=> $arrayParam['post']['sex'], 
		  'address'		=> $arrayParam['post']['address'], 
		  'active'		=> $arrayParam['post']['active'],  
		  'created'		=> $arrayParam['post']['created'],
		  'changed'		=> $arrayParam['post']['changed'], 
		  'avartar'		=> $arrayParam['post']['avartar'],  
		  'token'		=> $arrayParam['post']['token'], 
		  'status'		=> $arrayParam['post']['status'], 
		  'social'		=> $arrayParam['post']['social'] 
		);
		if(isset($arrayParam['id']) || $arrayParam['post']['id'] !== ''){
			// edit
			if(isset($arrayParam['post']['email']) && $arrayParam['post']['email'] === ''){
				unset($data['email']);
			}
            if(isset($arrayParam['post']['avartar']) && $arrayParam['post']['avartar'] == ''){
                unset($data['avartar']);
            }
			unset($data['password']);
			unset($data['salt']);
			unset($data['created']);
			unset($data['token']);
			unset($data['social']);
			$data['changed'] = time();
            if($this->update($data, 'id = '.$arrayParam['id'])){
                $role = new UserRole();
                $role->updateRoleByUserId($arrayParam['id'], $arrayParam['post']['role']);
            }
		}
		else{
			// add
			$data['created'] = time();
			$data['changed'] = time();
			if(!$this->insert($data)){
                return false;
            }else{
                $id = $this->getLastInsertValue();
                // add role
                $role = new UserRole();
                $userRole = array(
                    'role_rid'	=> $arrayParam['post']['role'],
                    'user_id'	=> $id
                );
                $role->addUserRole($userRole);
            }	
		}
	}
       
    // get user by id
    function getUserById($id){
        $select = new Select();
        $select->from($this->table);
        $select->columns(array('id','email', 'fullname', 'birthday', 'sex', 'address', 'active', 'avartar', 'status'));
        $select->join('user_role', 'user_role.user_id = user.id', array('role_rid'), 'left');
        $select->where('user.id = '.$id);
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet[0];
    }

    // lay ra role cua user
    public function getRoleByUser($arrayParam = null)
    {
        
    }
	
	// xóa avartar
	public function deleteAvartar($arrayParam = null){
		$data = array(
		  'avartar' => ''
		);
		if($this->update($data, 'id = '. $arrayParam['id'])){
			return true;
		}
		else{
			return false;
		}
	}
}

