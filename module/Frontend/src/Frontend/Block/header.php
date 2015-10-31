<?php

namespace Frontend\Block;
use Zend\View\Helper\AbstractHelper;
class header extends AbstractHelper
{    
    public function __invoke() {
        $data = $this->view->partial('block/header/header.phtml', array());
        echo $data;
    }
}
