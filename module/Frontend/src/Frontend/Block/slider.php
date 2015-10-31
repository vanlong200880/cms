<?php

namespace Frontend\Block;
use Zend\View\Helper\AbstractHelper;
class slider extends AbstractHelper
{    
    public function __invoke() {
        $data = $this->view->partial('block/slider/slider.phtml', array());
        echo $data;
    }
}
