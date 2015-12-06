<?php
namespace Frontend\Block;
use Zend\View\Helper\AbstractHelper;
use Frontend\Model\Country;
class menuuser extends AbstractHelper
{    
    public function __invoke() {
        $data = $this->view->partial('block/menuuser/menuuser.phtml', array(''));
        echo $data;
    }
}
