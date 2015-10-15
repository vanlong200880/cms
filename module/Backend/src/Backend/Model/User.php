<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Sql;
use Backend\Model\UserRole;
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
        $select->columns(array('id','email', 'fullname','alias', 'birthday', 'sex', 'address', 'active', 'avartar', 'status'));
        $select->join('user_role', 'user_role.user_id = user.id', array('role_rid'), 'left');
        $select->join('role', 'user_role.role_rid = role.id', array('role_name'), 'left');
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
    
    // update status
	public function updateStatus($arrayParam = null){
        $where= new Where();
        $data = array(
		  'status' => $arrayParam['status']
		);
        if(isset($arrayParam['post']['function'])){
            if((int) $arrayParam['post']['function'] == 2){
                $data['status'] = 2;
            }else{
                 if((int) $arrayParam['post']['function'] == 3){
                     $data['status'] = 1;
                 }else{
                     if((int) $arrayParam['post']['function'] == 4){
                         $data['status'] = 0;
                     }
                 }
            }
        }
        if(isset($arrayParam['post']['check-all'])){
            if(!empty($arrayParam['post']['check-all'])){
                foreach ($arrayParam['post']['check-all'] as $value){
                    if($this->checkUser($value)){
                        $this->update($data, 'id = '. $value);
                    }
                }
            }
            return true;
        }else{
            if(isset($arrayParam['id'])){
                if($this->update($data, 'id = '. $arrayParam['id'])){
                    return true;
                }
            }
            else{
                return false;
            }
        }
	}
    
    // check user
    function checkUser($id){
        $select = new Select();
        $select->from($this->table);
        $select->columns(array('id','email', 'fullname', 'birthday', 'sex', 'address', 'active', 'avartar', 'status'));
        $select->where('user.id = '.$id);
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet[0];
    }
    
    
    // delete user
    public function deleteUser($arrayParam = null){
        $userRole = new UserRole();
        if($userRole->deleteRoleByUserId($arrayParam['id'])){
            if($this->delete('id = ' . $arrayParam['id'])){
                return true;
            }
            else{
                return false;
            }
        }else{
            return false;
        }
	}
    
    // lay danh sach user
    public function listUser($arrayParam = null){
        $select = new Select();
        $where = new Where();
        $select->from($this->table);
        $select->columns(array('id','email', 'fullname', 'birthday', 'sex', 'address', 'active', 'created', 'avartar', 'status'));
        $select->join('user_role', 'user_role.user_id = user.id', array('role_rid'), 'left');
        $select->join('role', 'user_role.role_rid = role.id', array('role_name'), 'left');
        // text search
        if(isset($arrayParam['textSearch']) == true && $arrayParam['textSearch'] != ''){
	   		$where->like('user.fullname', '%' . $arrayParam['textSearch'] . '%');
	   		$select->where($where);
	   	}
        // search role
        if(isset($arrayParam['type']) == true && $arrayParam['type'] != ''){
            $select->where('role.id = '. $arrayParam['type']);
	   	}
        // phan trang
        if(isset($arrayParam['limit']) && $arrayParam['limit'] !== ''){
            $select->limit($arrayParam['limit'])->offset($arrayParam['offset']);
        }
        
        // trang thai
        if(isset($arrayParam['status']) && $arrayParam['status'] != ''){
            $select->where('user.status = '. $arrayParam['status']);
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
    
    // dem tong so user
    public function countUser($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT('.$this->table.'.id)')));
        if(isset($arrayParam['txtSearch']) === true && $arrayParam['txtSearch'] != ''){
            $where = new Where();
            $where->like('fullname', '%'. $arrayParam['textSearct']. '%');
            $select->where($where);
        }
        // trang thai
        if(isset($arrayParam['status'])){
            $select->where('status = '. $arrayParam['status']);
        }
        $resultSet = $this->selectWith($select);
        return $resultSet->toArray();
    }
    
    // kiem tra mat khau nhap dung hay khong
    public function validatePassword($arrayParam = null){
        $select = new Select();
        $select->from($this->table);
        $select->where('password = '. $arrayParam['post']['password']);
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        if(!empty($resultSet)){
            return true;
        }else{
            return false;
        }
    }
    
    // kiem tra user doi password
    function getUserChangePassword($id){
        $select = new Select();
        $select->from($this->table);
        $select->columns(array('id', 'salt',  'status'));
        $select->join('user_role', 'user_role.user_id = user.id', array('role_rid'), 'left');
        $select->where('user.id = '.$id);
        $resultSet = $this->selectWith($select);
        $resultSet = $resultSet->toArray();
        return $resultSet[0];
    }
    
    // doi mat khau
    public function changepassword($arrayParam = null){
        $data = array(
            'password'  => $arrayParam['post']['password'],
            'salt'      => $arrayParam['post']['salt']
		);
		if($this->update($data, 'id = '. $arrayParam['post']['id'])){
			return true;
		}
		else{
			return false;
		}
    }
}

