<?php

namespace Backend\Block;
use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;
class adminHeader extends AbstractHelper
{    
    public function __invoke() {
        $session = new Container(APPLICATION_KEY);
        $data = $this->view->partial('block/adminHeader/adminHeader.phtml', array('data' => $session->auth));
        echo $data;
    }
}
