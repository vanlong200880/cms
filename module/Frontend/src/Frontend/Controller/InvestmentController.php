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
  
}
