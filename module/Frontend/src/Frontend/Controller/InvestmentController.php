<?php

namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
class InvestmentController extends AbstractActionController{
  public function indexAction(){
    return new ViewModel();
  }
  public function makedepositAction(){
    return new ViewModel();
  }
	
	public function investmentconfirmAction(){
		return new ViewModel();
	}
  public function historyAction(){
		return new ViewModel();
	}
  public function mydepositAction(){
		return new ViewModel();
	}
	public function withdrawAction(){
		return new ViewModel();
	}
  
}
