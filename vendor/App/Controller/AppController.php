<?php
namespace App\Controller;
use Zend\Mvc\Controller\AbstractActionController;
class AppController extends AbstractActionController{
	public function __construct() {
		
	}
	
	protected function _getHelper($helper, $serviceLocator)
	{
		return $this->getServiceLocator()->get('viewHelpermanager')->get($helper);
	}
}

