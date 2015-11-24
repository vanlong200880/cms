<?php

namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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
}
