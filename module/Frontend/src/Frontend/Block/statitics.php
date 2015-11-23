<?php
namespace Frontend\Block;
use Zend\View\Helper\AbstractHelper;
class statitics extends AbstractHelper
{    
    public function __invoke() {
        $data = $this->view->partial('block/statitics/statitics.phtml', array());
        echo $data;
    }
}
