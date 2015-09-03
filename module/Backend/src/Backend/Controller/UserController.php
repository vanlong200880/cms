<?php

namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Backend\Form\ValidateUser;
use Backend\Model\Role;

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
			$arrayParam['post']	= $request->getPost()->toArray();
			$validate = new ValidateUser($arrayParam);
			if($validate->isError() === true){
				$arrayParam['error'] = $validate->getMessagesError();
			}else{
				$data = $validate->getData();
			}
		}
		$data['arrayParam'] = $arrayParam;
//		var_dump($data['arrayParam']['post']);
		if($role){
			$data['role'] = $role;
		}
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