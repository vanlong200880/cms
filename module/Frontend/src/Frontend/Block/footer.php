<?php
namespace Frontend\Block;
use Zend\View\Helper\AbstractHelper;
use Frontend\Model\Country;
class footer extends AbstractHelper
{    
    public function __invoke() {
      $country = new Country();
      $dataCountry = $country->getAllCountry();
        $data = $this->view->partial('block/footer/footer.phtml', array('country' => $dataCountry));
        echo $data;
    }
}
