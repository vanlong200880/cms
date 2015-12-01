<?php 
namespace Frontend\Model;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
class User extends AbstractTableGateway{
  // Table name
  protected $table = 'user';
  /* Call apdater */
  public function __construct() {
      $this->featureSet = new Feature\FeatureSet();
      $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
      $this->initialize();
  }
  // get user id by Sponsor ID
  function getUserIdSponsor($arrayParam = null){
      $select = new Select();
      $select->from($this->table);
      $select->columns(array('id'));
      $select->where(array('username' => $arrayParam['post']['sponsor-id']));
      $resultSet = $this->selectWith($select);
      $resultSet = $resultSet->toArray();
      return $resultSet[0];
  }

  // User register
  public function userRegister($arrayParam = null)	
	{
		$data = array(
		  'email'         => $arrayParam['post']['email'], 
		  'password'      => $arrayParam['post']['password'], 
		  'salt'          => $arrayParam['post']['salt'],  
		  'fullname'      => $arrayParam['post']['fullname'],
		  'active'        => $arrayParam['post']['active'],
		  'created'       => $arrayParam['post']['created'],
		  'changed'       => $arrayParam['post']['changed'], 
		  'token'         => $arrayParam['post']['token'], 
		  'status'        => $arrayParam['post']['status'],
      'username'      => $arrayParam['post']['username'],
      'skype'         => $arrayParam['post']['skype'],
      'country_id'		=> $arrayParam['post']['country'],
      'sponsor_id'		=> $arrayParam['post']['sponsor-id']
		);
    // add
    if(!$this->insert($data)){
      return false;
    }else{
      return $this->getLastInsertValue();
//      // add role
//      $role = new UserRole();
//      $userRole = array(
//        'role_rid'	=> $arrayParam['post']['role'],
//        'user_id'	=> $id
//      );
//      $role->addUserRole($userRole);
    }
	}
	// User login
	function userLogin($arrayParam = null)
	{
		$select = new Select();
		$select->from($this->table);
		$select->columns(array('id', 'email', 'username', 'password', 'fullname', 'token', 'active'));
		$select->where(array(
			"username = '". $arrayParam['post']['username']."'",
			"password = '".$arrayParam['post']['password']."'",
			'active = 1'
		));
		$resultSet = $this->selectWith($select);
		$dataUser = $resultSet->toArray();
		return $dataUser;
	}
	
	//Get salt
	public function getSalt($username){
		$select = new Select();
		$select->from($this->table);
		$select->columns(array('salt', 'username', 'active'));
		$select->where(array(
				"username = '". $username."'",
				'active = 1'
		));
		$resultSet = $this->selectWith($select);
		$dataResult = $resultSet->toArray();
		return $dataResult;
	}
  
  // Generator token reset password
  public function generatorTokenReset($arrayParam = null){
    $data = array(
      'token_reset'		=> $arrayParam['post']['token_reset'], 
      'time_reset'    => $arrayParam['post']['time_reset'],
		);
    // update
    if($this->update($data, "email = '". $arrayParam['post']['email']."'")){
        return true;
    }else{
        return false;
    }
  }
	
	// check user change password
	public function checkUserChangePassword($arrayParam = null){
		$select = new Select();
		$select->from($this->table);
		$select->columns(array('id', 'email', 'username', 'password', 'fullname', 'token', 'active'));
		$select->where(array(
			"username = '". $arrayParam['post']['username']."'",
			"password = '".$arrayParam['post']['password-old']."'",
			"token = '".$arrayParam['post']['token']."'",
			'active = 1'
		));
		$resultSet = $this->selectWith($select);
		$dataResult = $resultSet->toArray();
		return $dataResult;
	}
	
	// change password
	public function changePassword($arrayParam = null){
		$data = array(
      'password'		=> $arrayParam['post']['password-new'],
      'salt'    => $arrayParam['post']['salt'],
		);
    // update
    if($this->update($data, "id = '". $arrayParam['post']['id']."'")){
        return true;
    }else{
        return false;
    }
	}
	public function getUserById($arrayParam = null){
		$select = new Select();
		$select->from($this->table);
		$select->where('id ='.(int) $arrayParam['post']['id']);
		$resultSet = $this->selectWith($select);
		$resultSet = $resultSet->toArray();
		return $resultSet;
	}
	
	// update user profile
	public function userUpdateProfile($arrayParam = null)
	{
		$data = array(
		  'email'		=> $arrayParam['post']['email'], 
		  'fullname'	=> $arrayParam['post']['fullname'], 
		  'birthday'	=> $arrayParam['post']['birthday'],  
		  'sex'			=> $arrayParam['post']['sex'], 
		  'address'		=> $arrayParam['post']['address'], 
		  'changed'		=> $arrayParam['post']['changed'], 
			'country_id'		=> $arrayParam['post']['country_id'],
			'phone'		=> $arrayParam['post']['phone']
		);
		if($this->update($data, 'id = '.$arrayParam['post']['id'])){
			return true;
		}else{
			return false;
		}
	}
}