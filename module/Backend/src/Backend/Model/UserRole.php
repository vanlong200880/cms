<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
class UserRole extends AbstractTableGateway
{
    protected $table = 'user_role';
    
    public function __construct() {
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
    
    // add user role
	public function addUserRole($arrayParam = null){
		$data = array(
		  'role_rid'	=> $arrayParam['role_id'],
		  'user_id'		=> $arrayParam['user_id']
		);
		if($data){
			$this->insert($data);
		}
	}
}

