<?php
namespace Frontend\Form;
class ValidateResetPassword{
	//Message error
	protected $_messagesError = NULL;
	//Store data
	protected $_arrData = NULL;
	
	public function __construct($arrayParam = array(), $options = null){
		$this->_arrData	= $arrayParam;
      // check email
      $optionEmail = array(
          'table'     => 'user',
          'field'     => 'email',
          'adapter'   => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
      );
      $validator = new \Zend\Validator\ValidatorChain();
			$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
                ->addValidator(new \Zend\Validator\EmailAddress(), true)
                ->addValidator(new \Zend\Validator\Db\RecordExists($optionEmail), true);
			if(!$validator->isValid($arrayParam['post']['email'])){
				$message = $validator->getMessages();
				$this->_messagesError['email'] = 'Email: ' . current($message);
			}
	}

	//reutrn true if error
	public function isError(){
		if(count($this->_messagesError) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	//Return message error
	public function getMessagesError(){
		return $this->_messagesError;
	}
	//return data request
	public function getData($options = null){
		$filter = new \Zend\Filter\StringTrim(array('charlist' => ' '));
    $this->_arrData['post']['email']      = $filter->filter($this->_arrData['post']['email']);
		return $this->_arrData;
	}
}