<?php 
namespace Frontend\Model;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;
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
}