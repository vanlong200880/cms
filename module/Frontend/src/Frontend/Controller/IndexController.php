<?php

namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use App\Controller\AppController;

class IndexController extends AbstractActionController
{
	public function __construct() {
		// $this->layout()->getView()->headLink()->appendStylesheet('/css/home.css');
		//$this->getServiceLocator()->get('viewhelpermanager')->get('headLink')->appendStylesheet('/css/home.css');
		//$this->_getHelper('HeadLink', $this->getServiceLocator())->prependFile('/css/home.css');
//		 $this->view->headLink()->appendStylesheet('/css/home.css');
	}
	public function indexAction()
    {
        return new ViewModel();
    }
}
