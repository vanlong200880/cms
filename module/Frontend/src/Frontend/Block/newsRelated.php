<?php

namespace Frontend\Block;
use Zend\View\Helper\AbstractHelper;
class newsRelated extends AbstractHelper
{    
    public function __invoke() {
        $data = $this->view->partial('block/newsRelated/newsRelated.phtml', array());
        echo $data;
    }
}
