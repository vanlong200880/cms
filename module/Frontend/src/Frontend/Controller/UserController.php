<?php

namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Frontend\Form\ValidateRegister;
use Sky\System\EncriptPassword;
use Frontend\Model\User;
use Zend\Session\Container;
use Frontend\Form\ValidateLogin;

class UserController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    public function editAction(){
    	return new ViewModel();
    }

    public function loginAction()
    {
			$arrayParam['message'] = '';
      $session = new Container('usermember');
      if(isset($session->auth['userid']) == true){
        $arrayParam['message'] = 'done';
      }else{
				$arrayParam = array();
				$request = $this->getRequest();
				if($request->isXmlHttpRequest()){
					$arrayParam['post'] = $request->getPost();
					$validate = new ValidateLogin($arrayParam);
					if($validate->isError() == true){
						$arrayParam['error'] = $validate->getMessagesError();
					}else{
						$user = new User();
						$salt = $user->getSalt($arrayParam['post']['username']);
						$arrayParam['sa'] = $salt;
						if(!empty($salt)){
							$arrayParam['post']['salt'] = $salt[0]['salt'];
							$encriptPassword = new EncriptPassword();
							$arrayParam['post'] = $encriptPassword->encriptPasswordLogin($arrayParam['post']);
							$userData = $user->userLogin($arrayParam);
							if(!empty($userData)){
								$userInfo = array(
									'userid'    => $userData[0]['id'],
									'username'  => $userData[0]['username'],
									'fullname'  => $userData[0]['fullname'],
									'email'     => $userData[0]['email'],
									'token'     => $userData[0]['token'],
									'role'      =>'Member'
								);
								$session->auth = $userInfo;
								$arrayParam['message'] = 'done';
								$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Login successfull.</div>');
							}else{
								$arrayParam['error']['username'] = 'Username or password incorrect.';
							}
						}
						else{
							$arrayParam['error']['username'] = 'Username or password incorrect.';
						}
					}
				}
			}
    	return new JsonModel($arrayParam);
    }
    public function logoutAction(){
			$session = new Container('usermember');
			$session->getManager()->getStorage()->clear('usermember');
			return $this->redirect()->toRoute('home', array('controller' => 'index', 'action' => 'index'));
    }
    public function forgotpasswordAction(){
    	return new ViewModel();
    }
    public function changepasswordAction(){
    	return new ViewModel();
    }
    public function registerAction()
    {
      $arrayParam['message'] = '';
      $session = new Container('usermember');
      if(isset($session->auth['userid']) == true){
        $arrayParam['message'] = 'done';
      }else{
        $arrayParam = array();
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()){
          $arrayParam['post'] = $request->getPost();
          $validate = new ValidateRegister($arrayParam);
          if($validate->isError() === true){
            $arrayParam['error'] = $validate->getMessagesError();
          }else{
            $encriptPassword = new EncriptPassword();
            $user = new User();
            $sponsorId = $user->getUserIdSponsor($arrayParam);
            $arrayParam['post']['sponsor-id'] = $sponsorId['id'];
            $arrayParam['post'] = $encriptPassword->prepareData($arrayParam['post']);
            $arrayParam['post']['created'] = $arrayParam['post']['changed'] = time();
            $arrayParam['post']['active'] = 1;
            $arrayParam['post']['status'] = 1;
            $id = $user->userRegister($arrayParam);
            if($id){
              // login
              $userInfo = array(
                'userid'    => $id,
                'username'  => $arrayParam['post']['username'],
                'fullname'  => $arrayParam['post']['fullname'],
                'email'     => $arrayParam['post']['email'],
                'token'     => $arrayParam['post']['token'],
                'role'      =>'Member'
              );
              $session->auth = $userInfo;
              $arrayParam['message'] = 'done';
							$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert"> User register successfull.</div>');
            }
          }
        }
      }
      return new JsonModel($arrayParam);
    }    
}
