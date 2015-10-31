<?php
namespace Frontend\Block;
use Zend\View\Helper\AbstractHelper;
class footer extends AbstractHelper
{    
    public function __invoke() {
        $data = $this->view->partial('block/footer/footer.phtml', array());
        echo $data;
    }
}
