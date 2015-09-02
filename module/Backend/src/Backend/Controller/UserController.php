<?php

namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
	public function indexAction()
	{
		return new ViewModel();
	}

	public function addAction(){
		return new ViewModel();
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