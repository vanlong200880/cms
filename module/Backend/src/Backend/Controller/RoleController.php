<?php
namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Backend\Model\Role;
use Backend\Form\ValidateRole;

class RoleController extends AbstractActionController
{
    public function indexAction()
    {
        $data = array();
        $role = new Role();
        $dataRole = $role->getAllRole();
        $data['list'] = $dataRole;
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
}
