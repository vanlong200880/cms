<?php
namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Backend\Model\Role;
use Backend\Form\ValidatePermission;
use Backend\Model\Resource;
use Backend\Model\Permission;
use Backend\Model\RolePermission;

class PermissionController extends AbstractActionController
{
    public function indexAction()
    {
        $data = array();
        $request    = $this->getRequest();
        if($request->isPost() == true){
            $arrayParam['post']     = $request->getPost()->toArray();
            if(isset($arrayParam['post']['function'])){
                switch ($arrayParam['post']['function']){
                    case 1:
                        if(isset($arrayParam['post']['check-all'])){
                            $permission = new Permission();
                            $rolePermission = new RolePermission();
                            foreach ($arrayParam['post']['check-all'] as $value){
                                $arrayParam['id'] = $value;
                                $rolePermission->deleteRolePermissionByPermissionId($arrayParam['id']);
                                $permission->deletePermissionById($arrayParam);
                            }
                            $arrayParam['message'] = "<span class='seccess'>Xóa thành công.</span>";
                        }
                        break;
                    default :
                        break;
                }
                
            }
        }
        $permission =  new Permission();
        $dataPermission = $permission->getAllPermission();
        $data['list'] = $dataPermission;
        return new ViewModel($data);
    }
    // delete
    public function deleteAction(){
        $arrayParam = array();
        $request = $this->getRequest();
        if($request->isPost()){
            $arrayParam['id']         = $request->getPost('id');
            /*
             * + xoa role_permission
             * + xoa permission
             */
            $permission = new Permission();
            $rolePermission = new RolePermission();
            $rolePermission->deleteRolePermissionByPermissionId($arrayParam['id']);
            if($permission->deletePermissionById($arrayParam)){
                $arrayParam['message'] = "<span class='seccess'>Xóa thành công.</span>";
            }
        }
        return new JsonModel($arrayParam);
    }
    
    public function addAction()
    {
        $data = array();
        $arrayParam = $this->params()->fromRoute();
        $request = $this->getRequest();
        $resource = new Resource();
        $listResource = $resource->getListResource();
        $data['option'] = $listResource;
        $dataPost = '';
        if($request->isPost()){
            
            $arrayParam['post'] = $this->params()->fromPost();
            $dataPost = $arrayParam['post']['resource_id'];
            $validate = new ValidatePermission($arrayParam, 'add');
            
			if($validate->isError() === true){
				$arrayParam['error'] = $validate->getMessagesError();
            }else{
                $permission = new Permission();
                if($permission->validatePermissionNameResourceId($arrayParam)){
                    $arrayParam['error']['dbexits'] = 'Chức năng này đã tồn tại.';
                    
                }else{
                    $permission->addPermission($arrayParam);
                    $arrayParam['message'] = 'Thêm Resource thành công.';
                    if(isset($arrayParam['post']['save'])){
                        return $this->redirect()->toRoute('backend', array('controller' => 'permission', 'action' => 'index'));
                    }else{
                        if(isset($arrayParam['post']['save-news'])){
                            return $this->redirect()->toRoute('backend', array('controller' => 'permission', 'action' => 'add'));
                        }
                    }
                }
            }
        }
        $data['postResource'] = $dataPost;
        $data['arrayParam'] = $arrayParam;
        return new ViewModel($data);
    }
    public function editAction(){
        $arrayParam = $this->params()->fromRoute();
        $request = $this->getRequest();
        $resource = new Resource();
        $listResource = $resource->getListResource();
        $data['option'] = $listResource;
        
        $permission= new Permission();
        $arrayParam['id'] = $this->params()->fromRoute('id');
        $permissionInfo = $permission->getPermissionById($arrayParam);
        $dataPost = '';
        if($request->isPost()){
            $arrayParam['post'] = $this->params()->fromPost();
            $dataPost = $arrayParam['post']['resource_id'];
            $validate = new ValidatePermission($arrayParam, 'add');
            
			if($validate->isError() === true){
				$arrayParam['error'] = $validate->getMessagesError();
            }else{
                $permission = new Permission();
                if($permission->validatePermissionNameResourceId($arrayParam)){
                    $arrayParam['error']['dbexits'] = 'Chức năng này đã tồn tại.';
                    
                }else{
                    $permission->addPermission($arrayParam);
                    $arrayParam['message'] = 'Thêm Resource thành công.';
                    return $this->redirect()->toRoute('backend', array('controller' => 'permission', 'action' => 'index'));
                }
            }
        }else{
            $arrayParam['post'] = $permissionInfo;
            $dataPost = $permissionInfo['resource_id'];
        }         
        $data['postResource'] = $dataPost;
        $data['arrayParam'] = $arrayParam;
        return new ViewModel($data);
    }
    
    public function permissionAction(){
        $data = array();
        $role = new Role();
        $dataRole = $role->getAllRole();
        $request = $this->getRequest();
        if($request->isPost() == true){
            $data = $request->getPost();
            $arrayParam = array();
            if(isset($data['role']) && !empty($data['role'])){
                $rolePermisson = new RolePermission();
                $rolePermisson->deleteAllRecord();
                foreach ($data['role'] as $value){
                    $arrayParam['permission_id'] = (int)explode('-', $value)[0];
                    $arrayParam['role_id'] = (int)explode('-', $value)[1];
                    $rolePermisson->addAllRecord($arrayParam);
                }
                return $this->redirect()->refresh();
            }
        }
        // get resource
        $resource = new Resource();
        // lay danh sach module
        $module = $resource->getListResource();
        $permission = new Permission();
        $dataPermission = array();
        $rolePermission = new \Backend\Model\RolePermission();
        if($module){
            foreach ($module as $value){
                $dataPermission[$value['controller']] = $value;
                $arrayParam['id'] = $value['id'];
                $arrPermission = $permission->getListPermissionAction($arrayParam);
                foreach ($arrPermission as $k => $vPermission){
                    $dataPermission[$value['controller']]['permission'][$k] = $vPermission;
                    foreach ($dataRole as $h => $vRole){
                        $dataPermission[$value['controller']]['permission'][$k]['role'][$h] = $vRole;
                        if($rolePermission->getRoleByPermissionId($vPermission['id'], $vRole['id'])){
                            $dataPermission[$value['controller']]['permission'][$k]['role'][$h]['flag'] = true;
                            $dataPermission[$value['controller']]['permission'][$k]['role'][$h]['role_id'] = $vRole['id'];
                        }else
                        {
                            $dataPermission[$value['controller']]['permission'][$k]['role'][$h]['flag'] = false;
                            $dataPermission[$value['controller']]['permission'][$k]['role'][$h]['role_id'] = $vRole['id'];
                        }
                    }
                }
            }
        }
        $data['permission'] = $dataPermission;
        $data['arrayPram'] = $arrayParam;
        $data['role'] = $dataRole;
        return new ViewModel($data);
    }
}
