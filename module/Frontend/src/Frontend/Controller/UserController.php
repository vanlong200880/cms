<?php

namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Frontend\Form\ValidateRegister;
use Sky\System\EncriptPassword;
use Frontend\Model\User;
use Zend\Session\Container;

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
    	return new ViewModel();
    }
    public function logoutAction(){
    	return new ViewModel();
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
            }
          }
        }
      }
      return new JsonModel($arrayParam);
    }    
}
