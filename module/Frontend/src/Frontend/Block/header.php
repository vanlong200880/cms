<?php

namespace Frontend\Block;
use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;
class header extends AbstractHelper
{    
    public function __invoke() {
      $session = new Container('usermember');
      $userInfo = $session->auth;
      $data = $this->view->partial('block/header/header.phtml', array('userinfo' => $userInfo));
      echo $data;
    }
}
