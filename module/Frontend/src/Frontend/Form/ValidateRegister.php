<?php
namespace Frontend\Form;
class ValidateLogin{
	
	//============================================
	//Message error
	//============================================
	protected $_messagesError = NULL;
	
	//============================================
	//Store data
	//============================================
	protected $_arrData = NULL;
	
	public function __construct($arrayParam = array(), $options = null){
		$this->_arrData	= $arrayParam;
		
		//============================================
		//CHeck email
		//============================================
		$validator = new \Zend\Validator\ValidatorChain();
		$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
					->addValidator(new \Zend\Validator\EmailAddress(), true);
		if(!$validator->isValid($arrayParam['post']['email'])){
			$message = $validator->getMessages();
			$this->_messagesError['Email'] = 'Email: ' . current($message);
		}
		
		//============================================
		//Check Password
		//============================================
		$validator = new \Zend\Validator\ValidatorChain();
		$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
					->addValidator(new \Zend\Validator\StringLength(6,32), true);
		if(!$validator->isValid($arrayParam['post']['password'])){
			$message = $validator->getMessages();
			$this->_messagesError['password'] = 'Password: ' . current($message);
		}	
	}
	
	//============================================
	//reutrn true if error
	//============================================
	public function isError(){
		if(count($this->_messagesError) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	//============================================
	//Return message error
	//============================================
	public function getMessagesError(){
		return $this->_messagesError;
	}
	
	//============================================
	//return data request
	//============================================
	public function getData($options = null){
		$filter = new \Zend\Filter\StringTrim(array('charlist' => ' '));
		$this->_arrData['post']['username'] = $filter->filter($this->_arrData['post']['email']);
		$this->_arrData['post']['password'] = $filter->filter($this->_arrData['post']['password']);
		return $this->_arrData;
	}
}