<?php

namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Backend\Form\ValidateLogin;
use Backend\Model\UserHasGroup;
use Zend\Session\Container;

class PublicController extends AbstractActionController
{
	/**---------------------------------------------------------------------------
	 * Không sử dụng
	 ---------------------------------------------------------------------------*/
	public function indexAction()
	{			
		$this->redirect()->toRoute('admincp',array('controller' => 'index', 'action' => 'index'));
		return $this->getResponse();
	}
	
	/**---------------------------------------------------------------------------
	 * Đăng nhập
	 ---------------------------------------------------------------------------*/
	public function loginAction()
	{
		$session = new Container(APPLICATION_KEY);
		if(isset($session->auth->id) == true){
			$this->redirect()->toRoute('admincp',array('controller' => 'index', 'action' => 'index'));
		}
		$this->layout('layout/login');
		$data = array();
		// Lấy thông tin từ router
		$arrayParam = $this->params()->fromRoute();
		$request = $this->getRequest();
		if($request->isPost() == true){
			//Lấy thông tin từ post
			$arrayParam['post']	= $request->getPost()->toArray();
			$validate = new ValidateLogin($arrayParam);
			// Kiểm tra lỗi
			if($validate->isError() == true){
				$arrayParam['error'] = $validate->getMessagesError();
			}else{
				$arrayParam = $validate->getData();
				$URI = SERVER_ID . '/api/user';
				$options = array('location' => $URI, 'uri' => $URI);
				$client = new \Zend\Soap\Client(null, $options);
				$dataUser = $client->checkUser(KEY_API, $arrayParam['post']['username'], $arrayParam['post']['password']);
				if($dataUser != false){
					$dataUser = json_decode($dataUser);
					$modelUserHasGroup = new UserHasGroup();
					$dataGroup = $modelUserHasGroup->auth($dataUser);
					if($dataGroup != false){
						$dataUser->group_id = $dataGroup['group_id'];
					}else{
						$dataUser->group_id = IDMEMBER;
					}
					$session = new Container(APPLICATION_KEY);
					$session->auth = $dataUser;
					$info = new \Sky\System\Info();
					
					$this->redirect()->toRoute('admincp', array('controller' => 'index', 'action' => 'index'));
				}else{
					$arrayParam['error'] = array('Username hoặc Password chưa chính xác.');
				} 
			}
		}
	
		$data['arrayParam'] = $arrayParam;
		$data['title'] = 'Đăng Nhập';
		$view = new ViewModel($data);
		return $view;
	}
	
	/**---------------------------------------------------------------------------
	 * Đăng xuất
	 ---------------------------------------------------------------------------*/
	public function logoutAction(){
		$info = new \Sky\System\Info();
		$info->destroyInfo();
		$this->redirect()->toRoute('admincp',array('controller' => 'index', 'action' => 'index'));
		return $this->getResponse();
		
	}
	
	/**---------------------------------------------------------------------------
	 * Không có quyền truy cập
	 ---------------------------------------------------------------------------*/
	public function noaccessAction(){
		$this->layout('layout/login');
		$data['title'] = 'No Access';
		$view = new ViewModel($data);
		return $view;
	}
}
