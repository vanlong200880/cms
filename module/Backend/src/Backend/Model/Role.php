<?php
namespace Backend\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\Feature;
class Role extends AbstractTableGateway
{
    protected $table = 'role';
    
    public function __construct() {
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
    
    // lay ra role cua user 
    public function getRoleByUser($arrayParam = null)
    {
        $select = new Select();
        $where = new Where();
        $select->from($this->table);
        $select->join('user_role', 'role.id = user_role.role_rid', array('role_rid', 'user_id'), 'left');
        $select->where(array(
            'user_role.user_id = ' .$arrayParam['user_id'],
            'role.status = 1'
        ));
        $resultSet = $this->selectWith($select);
        return $resultSet->toArray();
    }
    // lay thong tin role
    public function auth($arrayParam = null)
    {
        $select = new Select();
        $select->from($this->table);
        $select->where->or->nest()->in('id', $arrayParam['id'])
                ->unnest()
                    ->equalTo('status',1);
        $resultSet = $this->selectWith($select);
        $dataReturn = $resultSet->toArray();
        if(count($dataReturn) > 0){
           return $dataReturn[0];
        }else{
           return false;
        }
    }

}

