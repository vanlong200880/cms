<?php
namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
//use Backend\Model\Role;
use Backend\Form\ValidateResource;
use Backend\Model\Resource;
use Backend\Model\Permission;
use Backend\Model\RolePermission;

class ResourceController extends AbstractActionController
{
    public function indexAction()
    {
        $request    = $this->getRequest();
        if($request->isPost() == true){
            $arrayParam['post']     = $request->getPost()->toArray();
            if(isset($arrayParam['post']['function'])){
                switch ($arrayParam['post']['function']){
                    case 1:
                        if(isset($arrayParam['post']['check-all']))
                        {
                            foreach ($arrayParam['post']['check-all'] as $value){
                                $arrayParam['id'] = $value;
                                $permission = new Permission();
                                $dataPermission = $permission->getListPermissionByResourceId($arrayParam);
                                if(!empty($dataPermission)){
                                    $rolePermission = new RolePermission();
                                    foreach ($dataPermission as $value){
                                        $rolePermission->deleteRolePermissionByPermissionId($value['id']);
                                    }
                                    $permission->deletePermissionByReourceId($arrayParam);
                                }
                                $resource = new Resource();
                                $resource->deleteResource($arrayParam);
                            }
                            $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa thành công.</div>');
                        }
                        break;
                    default :
                        break;
                }
            }
        }
        $data = array();
        $listResource =  new Resource();
        $dataResource = $listResource->getListResource();
        $data['list'] = $dataResource;
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
             * + xoa resource
             */
            $permission = new Permission();
            $dataPermission = $permission->getListPermissionByResourceId($arrayParam);
            if(!empty($dataPermission)){
                $rolePermission = new RolePermission();
                foreach ($dataPermission as $value){
                    $rolePermission->deleteRolePermissionByPermissionId($value['id']);
                }
                $permission->deletePermissionByReourceId($arrayParam);
            }
            $resource = new Resource();
            if($resource->deleteResource($arrayParam))
            {
                $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa thành công.</div>');
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
            $validate = new ValidateResource($arrayParam, 'add');
            $resource = new Resource();
            
			if($validate->isError() === true){
				$arrayParam['error'] = $validate->getMessagesError();
            }else{
                if($resource->validateModuleController($arrayParam)){
                    $arrayParam['error']['dbexits'] = 'Module và Controller này đã tồn tại.';
                }else{
                    $resource->addResource($arrayParam);
                    $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Lưu thành công.</div>');
                    if(isset($arrayParam['post']['save'])){
                        return $this->redirect()->toRoute('backend', array('controller' => 'resource', 'action' => 'index'));
                    }else{
                        if(isset($arrayParam['post']['save-new'])){
                            return $this->redirect()->toRoute('backend', array('controller' => 'resource', 'action' => 'add'));
                        }
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
        $resource = new Resource();
        $arrayParam['id'] = $this->params()->fromRoute('id');
        $resourceInfo = $resource->getResourceById($arrayParam);
        if($request->isPost() && !empty($resourceInfo)){
            $arrayParam['post'] = $this->params()->fromPost();
            if($resource->validateModuleController($arrayParam)){
                $arrayParam['error']['dbexits'] = 'Module và Controller này đã tồn tại.';
            }else{
                // update
                $validate = new ValidateResource($arrayParam, 'edit');
                if($validate->isError() === true){
                    $arrayParam['error'] = $validate->getMessagesError();
                }else{
                    $resource->addResource($arrayParam);
                    $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Lưu thành công.</div>');
                    return $this->redirect()->toRoute('backend', array('controller' => 'resource', 'action' => 'index'));
                }
            }
        }else{
            $arrayParam['post'] = $resourceInfo;
        }
        $data['arrayParam'] = $arrayParam;
        return new ViewModel($data);
    }
}
