<?php

namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Backend\Form\ValidateUser;
use Backend\Model\Role;
use Sky\System\EncriptPassword;
use Backend\Model\User;
use Sky\System\ListDateFormat;

class UserController extends AbstractActionController
{
	public function indexAction()
	{
		return new ViewModel();
	}

	public function addAction(){
		$data = array();
		$arrayParam = $this->params()->fromRoute();
		$request = $this->getRequest();
        
		// get all role
		$listRole = new Role();
		$role = $listRole->getAllRole();
		if($request->isPost() === true){
			$arrayParam['post']	= array_merge_recursive(
                                    $request->getPost()->toArray(),
                                    $request->getFiles()->toArray()
                                );
            if(isset($arrayParam['post']['sex'])){
                $arrayParam['post']['sex'] = (int)($arrayParam['post']['sex']);
            }
            if(isset($arrayParam['post']['active'])){
                $arrayParam['post']['active'] = (int)($arrayParam['post']['active']);
            }
            if(isset($arrayParam['post']['role'])){
                $arrayParam['post']['role'] = (int)($arrayParam['post']['role']);
            }
			$validate = new ValidateUser($arrayParam);
			if($validate->isError() === true){
				$arrayParam['error'] = $validate->getMessagesError();
			}else{
				$data = $validate->getData();
                $data['post']['avartar'] = $data['post']['avartar']['name'];
                // upload image
                
                $data['post']['created'] = $data['post']['changed'] = time();
                $data['post']['status'] = 1;
                $data['post']['social'] = 0;
                $data['post']['token'] = '';
                $data['post']['salt'] = '';
                $data['post']['id'] = '';
                $encriptPassword = new EncriptPassword();
                $data['post'] = $encriptPassword->prepareData($data['post']);
                if(isset($data['post']['day']) && isset($data['post']['month']) && isset($data['post']['year'])){
                    $birthday = $data['post']['day'].'-'.$data['post']['month'].'-'.$data['post']['year'];
                    $data['post']['birthday'] = strtotime($birthday);
                }else{
                    $data['post']['birthday'] = '';
                }
                $user = new User();
                $user->addUser($data);
                $this->flashMessenger()->addMessage(array('success' => 'Tạo thành viên thành công.'));
                return $this->redirect()->toRoute('backend', array('controller' => 'user','action' => 'index'));
			}
		}
        
		$data['arrayParam'] = $arrayParam;
		if($role){
			$data['role'] = $role;
		}
        $activeDay = $activeMonth = $activeYear = '';
        if(isset($arrayParam['post']['day'])){
            $activeDay = $arrayParam['post']['day'];
        }
        if(isset($arrayParam['post']['month'])){
            $activeMonth = $arrayParam['post']['month'];
        }
        if(isset($arrayParam['post']['year'])){
            $activeYear = $arrayParam['post']['year'];
        }
        
        // get ngay thang nam sinh
        $date = new \Sky\System\ListDateFormat();
        $data['day'] = implode('', $date->listDay(1,31, $activeDay));
        $data['month'] = implode('', $date->listMonth(1,12, $activeMonth));
        $data['year'] = implode('', $date->listYear(date('Y')- 120, date('Y'), $activeYear));
		$data['title'] = 'Đăng ký thành viên mới';
		return new ViewModel($data);
	}

	public function UpdateAction(){
		return new ViewModel();
	}

	public function changeStatusAction(){
		return new ViewModel();
	}

	public function changeIconAction(){
		return new ViewModel();
	}

	public function deleteAction(){
		return new ViewModel();
	}

	public function blockAction(){

	}
}