<?php

namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Frontend\Form\ValidateRegister;

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
      $arrayParam = array();
      $request = $this->getRequest();
      if($request->isXmlHttpRequest()){
        $arrayParam['post'] = $request->getPost();
        $validate = new ValidateRegister($arrayParam);
        if($validate->isError() === true){
          $arrayParam['error'] = $validate->getMessagesError();
        }else{
          
        }
      }
      return new JsonModel($arrayParam);
    }
}
