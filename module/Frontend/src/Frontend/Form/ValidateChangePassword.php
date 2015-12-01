<?php
namespace Frontend\Form;
class ValidateChangePassword{
	//Message error
	protected $_messagesError = NULL;
	//Store data
	protected $_arrData = NULL;
	
	public function __construct($arrayParam = array(), $options = null){
		$this->_arrData	= $arrayParam;
      // check password old
      $validator = new \Zend\Validator\ValidatorChain();
			$validator->addValidator(new \Zend\Validator\NotEmpty(), true);
								
			if(!$validator->isValid($arrayParam['post']['password-old'])){
				$message = $validator->getMessages();
				$this->_messagesError['passwordold'] = 'Password old: ' . current($message);
			}
			// check password new
      $validator = new \Zend\Validator\ValidatorChain();
			$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
						->addValidator(new \Zend\Validator\StringLength(6,32), true);
			if(!$validator->isValid($arrayParam['post']['password-new'])){
				$message = $validator->getMessages();
				$this->_messagesError['passwordnew'] = 'Password New: ' . current($message);
			}
      
      // check confirm password
      if($arrayParam['post']['password-new'] !== ''){
        $validator = new \Zend\Validator\ValidatorChain();
        $validator->addValidator(new \Zend\Validator\NotEmpty(), true)
              ->addValidator(new \Zend\Validator\Identical(array('token' => $arrayParam['post']['password-new'])));
        if(!$validator->isValid($arrayParam['post']['confirm-password-new'])){
          $message = $validator->getMessages();
          $this->_messagesError['repasswordnew'] = current($message);
        }
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
    $this->_arrData['post']['password-old']      = $filter->filter($this->_arrData['post']['password-old']);
		$this->_arrData['post']['password-new']      = $filter->filter($this->_arrData['post']['password-new']);
		$this->_arrData['post']['confirm-password-new']      = $filter->filter($this->_arrData['post']['confirm-password-new']);
		return $this->_arrData;
	}
}