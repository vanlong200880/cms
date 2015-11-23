<?php
namespace Frontend\Block;
use Zend\View\Helper\AbstractHelper;
class payment extends AbstractHelper
{    
    public function __invoke() {
        $data = $this->view->partial('block/payment/payment.phtml', array());
        echo $data;
    }
}
