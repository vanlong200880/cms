<?php
namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Backend\Model\Role;
use Backend\Form\ValidateRole;
use Backend\Model\Resource;
use Backend\Model\Permission;
use Backend\Model\RolePermission;

class RoleController extends AbstractActionController
{
    public function indexAction()
    {
        $request    = $this->getRequest();
        $arrayParam['listId'] = array();
        if($request->isPost() == true){
            $data                   = $this->getRequest();
            $arrayParam['post']     = $request->getPost()->toArray();
            $arrayParam['status']   = $arrayParam['post']['function'];
            if(isset($arrayParam['post']['function'])){
                $role = new Role();
                switch ($arrayParam['post']['function']){
                    case 1:
                        // delete
                        if(isset($arrayParam['post']['check-all']) && !empty($arrayParam['post']['check-all'])){
                            foreach ($arrayParam['post']['check-all'] as $value){
                                $arrayParam['id']   = $value;
                                $role->deleteRole($arrayParam);
                            }
                            $arrayParam['message'] = "<span class='seccess'>Xóa nhóm thành công.</span>";
                        }else{
                            if(isset($arrayParam['post']['check-all'])){
                                 $arrayParam['listId'] = $arrayParam['post']['check-all'];
                            }
                        }
                        return $this->redirect()->toRoute('backend', array('controller' => 'role', 'action' => 'index'));
                        break;
                    case 2: // active
                    case 3: // unactive
                        $arrayParam['status'] = (isset($arrayParam['status']) && $arrayParam['status'] == 2)? '1':((isset($arrayParam['status']) && $arrayParam['status'] == '3')?'0':'');
                        
                        if(in_array($arrayParam['status'], array(0, 1))){
                            if(isset($arrayParam['post']['check-all']) && !empty($arrayParam['post']['check-all'])){
                                foreach ($arrayParam['post']['check-all'] as $value){
                                    $arrayParam['id']   = $value;
                                    $role->changeStatus($arrayParam);
                                }
                                $arrayParam['message'] = "<span class='seccess'>Lưu thành công.</span>";
                                return $this->redirect()->toRoute('backend', array('controller' => 'role', 'action' => 'index'));
                            }else{
                                $arrayParam['message'] = "<span class='seccess'>Lưu không thành công.</span>";
                                if(isset($arrayParam['post']['check-all'])){
                                    $arrayParam['listId'] = $arrayParam['post']['check-all'];
                               }
                            }
                        }else{
                            $arrayParam['message'] = "<span class='seccess'>Lưu không thành công.</span>";
                            if(isset($arrayParam['post']['check-all'])){
                                 $arrayParam['listId'] = $arrayParam['post']['check-all'];
                            }
                        }
                        break;
                    default :
                        $arrayParam['message'] = "<span class='seccess'>Lưu không thành công.</span>";
                        if(isset($arrayParam['post']['check-all'])){
                            $arrayParam['listId'] = $arrayParam['post']['check-all'];
                        }
                        break;
                }
            }
        }
        $data = array();
        $role = new Role();
        $dataRole = $role->getAllRole();
        $data['list'] = $dataRole;
        $data['listIdRole'] = $arrayParam['listId'];
        return new ViewModel($data);
    }
    public function changestatusAction()
    {
        $request = $this->getRequest();
        $arrayParam	= array();
        if($request->isPost()){
            $role       = new Role();
            $id         = $request->getPost('id');
            if(is_numeric($request->getPost('status')) && in_array($request->getPost('status'), array(0,1))){
                $status     = ($request->getPost('status') == 1)? '0' : '1';
                $arrayParam['id'] = $id;
                $arrayParam['status'] = $status;
                if(is_numeric($id) && is_numeric($status)){
                    $RoleInfo   = $role->getRoleById($arrayParam);
                    if($RoleInfo){
                        $role->changeStatus($arrayParam);
                        $arrayParam['message'] = "<span class='seccess'>Cập nhật thành công.</span>";
                    }
                }
            }
            
        }
        return new JsonModel($arrayParam);
    }
    public function deleteAction(){
        $arrayParam = array();
        $request = $this->getRequest();
        $arrayParam	= array();
        if($request->isPost()){
            $arrayParam['id']         = $request->getPost('id');
            $role = new Role();
            if($role->getRoleById($arrayParam)){                
                if($role->deleteRole($arrayParam)){
                    $arrayParam['message'] = "<span class='seccess'>Xóa thành công.</span>";
                }
            }
        }
        return new JsonModel($arrayParam);
    }
    
    public function updateweightAction(){
        $arrayParam = array();
        $request = $this->getRequest();
        if($request->isPost()){
            $data        = $request->getPost('info');
            if(!empty($data)){
                $role = new Role();
                foreach ($data as $key => $value){
                    $arrayParam['id'] = $key;
                    $arrayParam['weight'] = $value;
                    $role->updateWeight($arrayParam);
                    $arrayParam['message'] = "<span class='seccess'>Lưu thành công.</span>";
                }
            }
        }
        return new JsonModel($arrayParam);
    }
    
    public function addAction()
    {
        $arrayParam = $this->params()->fromRoute();
        $request = $this->getRequest();
        if($request->isPost()){
            $arrayParam['post'] = $this->params()->fromPost();
            $validate = new ValidateRole($arrayParam, 'add');
			if($validate->isError() === true){
				$arrayParam['error'] = $validate->getMessagesError();
            }else{
                $role = new Role();
                $role->addRole($arrayParam);
                $arrayParam['message'] = 'Thêm nhóm thành công.';
                if(isset($arrayParam['post']['save'])){
                    return $this->redirect()->toRoute('backend', array('controller' => 'role', 'action' => 'index'));
                }else{
                    if(isset($arrayParam['post']['save-new'])){
                        return $this->redirect()->toRoute('backend', array('controller' => 'role', 'action' => 'add'));
                    }
                }
            }
        }
        $data['arrayParam'] = $arrayParam;
        return new ViewModel($data);
    }
    public function editAction(){
        $arrayParam = $this->params()->fromRoute();
        $request = $this->getRequest();
        $role = new Role();
        $arrayParam['id'] = $this->params()->fromRoute('id');
        $roleInfo = $role->getRoleById($arrayParam);
        if($request->isPost() && !empty($roleInfo)){
            $arrayParam['post'] = $this->params()->fromPost();
            $validate = new ValidateRole($arrayParam, 'edit');
			if($validate->isError() === true){
				$arrayParam['error'] = $validate->getMessagesError();
            }else{
                $role->addRole($arrayParam);
                $arrayParam['message'] = 'Sửa nhóm thành công.';
                return $this->redirect()->toRoute('backend', array('controller' => 'role', 'action' => 'index'));
            }
        }else{
            $arrayParam['post'] = $roleInfo;
        }
        $data['arrayParam'] = $arrayParam;
        return new ViewModel($data);
    }
    public function rolepermissionAction(){
        $arrayParam = array();
        $arrayParam['id'] = $this->params()->fromRoute('id');
        // get role name
        $role = new Role();
        $roleInfo = $role->getRoleById($arrayParam);
        if(!empty($roleInfo)){
            $arrayParam['rolename'] = $roleInfo;
        }
        // save role permission
        $request = $this->getRequest();
        if($request->isPost()){
            $arrayParam['permission'] = $this->params()->fromPost('permission');
            if(isset($arrayParam['permission'])){
                $rolePermission = new RolePermission();
                $rolePermission->addRolePermission($arrayParam);
                $arrayParam['message'] = 'Lưu thành công.';
                return $this->redirect()->toRoute('backend', array('controller' => 'role', 'action' => 'rolepermission', 'id' => $arrayParam['id']));
            }
        } 
        $arrayParam['role-id'] = $this->params()->fromRoute('id');
        
        if(isset($arrayParam['id'])){
            if($role->getRoleById($arrayParam)){
                // lay danh sach module
                $resource = new Resource();
                $module = $resource->getListResource();
                $permission = new Permission();
                $dataList = array();
                if($module){
                    foreach ($module as $value){
                        $arrayParam['id'] = $value['id'];
                        // kiem tra co quyen chua
                        $actionList = $permission->getListPermissionAction($arrayParam);
                       
                        if(!empty($actionList)){
                            $rolePermission = new \Backend\Model\RolePermission();
                            foreach ($actionList as $key => $v){
                                if($rolePermission->getRoleByPermissionId($v['id'], $arrayParam['role-id'])){
                                    $actionList[$key]['flag'] = true;
                                }else
                                {
                                    $actionList[$key]['flag'] = false;
                                }
                                
                            }
                        }
                        $dataList[$value['controller']] =  array(
                            'module'        => $value['module'],
                            'controller'    => $value['controller'],
                            'action'        => $actionList
                        );
                        
                        
                    }
                }
                $arrayParam['list'] = $dataList;
            }
        }
        $data['arrayParam'] = $arrayParam;        
        return new ViewModel($data);
    }
}
