<?php

namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Backend\Form\ValidateLogin;
use Zend\Session\Container;
use Backend\Model\User;
use Backend\Model\Role;
use Backend\Model\Permission;
use Backend\Model\RolePermission;

class PublicController extends AbstractActionController
{
	/**---------------------------------------------------------------------------
	 * Không sử dụng
	 ---------------------------------------------------------------------------*/
	public function indexAction()
	{			
		$this->redirect()->toRoute('backend',array('controller' => 'index', 'action' => 'index'));
		return $this->getResponse();
	}
	
	/**---------------------------------------------------------------------------
	 * Đăng nhập
	 ---------------------------------------------------------------------------*/
	public function loginAction()
	{
		$session = new Container(APPLICATION_KEY);
		if(isset($session->auth['userId']) == true){
			$this->redirect()->toRoute('backend',array('controller' => 'index', 'action' => 'index'));
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
//				$URI = SERVER_ID . '/backend/public/login';
//				$options = array('location' => $URI, 'uri' => $URI);
//				$client = new \Zend\Soap\Client(null, $options);
//                var_dump($client);
//				$dataUser = $client->checkUser(KEY_API, $arrayParam['post']['email'], $arrayParam['post']['password']);
                // check Id user
                $user = new User();
                $userInfo = $user->userLogin($arrayParam['post']);
				if(!empty($userInfo)){
                    $arrayUserId = array('user_id' => $userInfo['id']);
                    
                    // list role
                    $role = new Role();
                    $listRole = $role->getRoleByUser($arrayUserId);
                    if(!empty($listRole)){
                        $listRoleId     = array();
                        $listRoleName   = array();
                        foreach ($listRole as $value){
                            $roleId   = $value['id'];
                            $roleName = $value['role_name'];
                        }
                    }else{
                        $roleId   = IDMEMBER;
                        $roleName = 'MEMBER';
                    }                
//                         = '';
//                    $userInfo = json_decode($userInfo);
//                    var_dump($userInfo);
//					$dataUser = json_decode($dataUser);
//					$modelUserHasGroup = new UserHasGroup();
//					$dataGroup = $modelUserHasGroup->auth($dataUser);
                    $dataUser = array(
                        'userId'        => $userInfo['id'],
                        'userFullName'  => $userInfo['fullname'],
                        'userEmail'     => $userInfo['email'],
                        'userAvartar'   => $userInfo['avartar'],
                        'userCreated'   => $userInfo['created'],
                        'roleName'      => $roleName,
                        'role'          => $roleId,
                    );
                     // lay thong tin permission by role
                    $permission = new RolePermission();
                    $dataPermission = $permission->getRolePermissionByRoleId($roleId);
                    $data = array();
                    foreach ($dataPermission as $value){
                        $data[] = strtolower($value['module']) . '/' . strtolower($value['controller']) .'/' . strtolower($value['action']); 
                    }
                    $dataUser['permission'] = json_encode($data);
                    
					$session = new Container(APPLICATION_KEY);
					$session->auth = $dataUser;
					$info = new \Sky\System\Info();		
                    
					$this->redirect()->toRoute('backend', array('controller' => 'index', 'action' => 'index'));
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
		$this->redirect()->toRoute('backend',array('controller' => 'public', 'action' => 'login'));
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
