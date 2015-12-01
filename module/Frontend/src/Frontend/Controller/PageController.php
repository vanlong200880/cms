<?php

namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Frontend\Model\Page;
class PageController extends AbstractActionController{
  public function indexAction(){
    return new ViewModel();
  }
  
  public function aboutAction()
  {
    $page = new Page();
    $arrayParam['slug'] = 'about-us';
    $data = $page->getPageBySlug($arrayParam);
    $arrayParam['about'] = $data[0];
    return new ViewModel($arrayParam);
  }
  
  public function contactAction(){
    return new ViewModel();
  }
}
