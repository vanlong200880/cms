<?php

namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
class PageController extends AbstractActionController{
  public function indexAction(){
    return new ViewModel();
  }
  
  public function aboutAction()
  {
    return new ViewModel();
  }
  
  public function contactAction(){
    return new ViewModel();
  }
}
