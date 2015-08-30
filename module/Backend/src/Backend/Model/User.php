<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
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
    
    // lay ra role cua user
    
    public function getRoleByUser($arrayParam = null)
    {
        
    }
}

