<?php
namespace Frontend\Form;
use Zend\Session\Container;
class ValidateUserEdit{
	//Message error
	protected $_messagesError = NULL;
	//Store data
	protected $_arrData = NULL;
	
	public function __construct($arrayParam = array(), $options = null){
		$this->_arrData	= $arrayParam;
    
		//Check full name
		$validator = new \Zend\Validator\ValidatorChain();
		$validator->addValidator(new \Zend\Validator\NotEmpty(), true);
		if(!$validator->isValid($arrayParam['post']['fullname'])){
			$message = $validator->getMessages();
			$this->_messagesError['fullname'] = 'Full Name: ' . current($message);
		}
		// check country
		$optionCountry = array(
				'table'     => 'country',
				'field'     => 'id',
				'adapter'   => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
		);
		$validator = new \Zend\Validator\ValidatorChain();
		$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
							->addValidator(new \Zend\Validator\Digits(), true)
							->addValidator(new \Zend\Validator\Db\RecordExists($optionCountry), true);
		if(!$validator->isValid($arrayParam['post']['country_id'])){			
				$message = $validator->getMessages();
				$this->_messagesError['country'] = 'Country: ' . current($message);
		}

		// check phone number
		$validator = new \Zend\Validator\ValidatorChain();
		$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
					->addValidator(new \Zend\Validator\Regex('/^[0-9 .-]+$/'))
					->addValidator(new \Zend\Validator\StringLength(6,16), true);
		if(!$validator->isValid($arrayParam['post']['phone'])){
			$message = $validator->getMessages();
			$this->_messagesError['phone'] = 'Phone: ' . current($message);
		}
      
		// check email
		$session = new Container('usermember');
		$userInfo = $session->auth;
		if($arrayParam['post']['email'] != $userInfo['email']){
			$optionEmail = array(
				'table'     => 'user',
				'field'     => 'email',
				'adapter'   => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
			);
			$validator = new \Zend\Validator\ValidatorChain();
			$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
								->addValidator(new \Zend\Validator\EmailAddress(), true)
								->addValidator(new \Zend\Validator\Db\NoRecordExists($optionEmail), true);
			if(!$validator->isValid($arrayParam['post']['email'])){
				$message = $validator->getMessages();
				$this->_messagesError['email'] = 'Email: ' . current($message);
			}
		}
		// check skype
		$validator = new \Zend\Validator\ValidatorChain();
		$validator->addValidator(new \Zend\Validator\NotEmpty(), true);
		if(!$validator->isValid($arrayParam['post']['skype'])){
			$message = $validator->getMessages();
			$this->_messagesError['skype'] = 'Skpye: ' . current($message);
		}
		
		// check date month year
		if(($arrayParam['post']['day'] !='' && $arrayParam['post']['month'] != '' && $arrayParam['post']['year'] != '') || ($arrayParam['post']['day'] =='' && $arrayParam['post']['month'] == '' && $arrayParam['post']['year'] == '')){
			
		}else{
			$this->_messagesError['date'] = 'Invalid birthday.';
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
    $this->_arrData['post']['sponsor-id'] = $filter->filter($this->_arrData['post']['sponsor-id']);
		$this->_arrData['post']['fullname']   = $filter->filter($this->_arrData['post']['fullname']);
    $this->_arrData['post']['username']   = $filter->filter($this->_arrData['post']['username']);
		$this->_arrData['post']['password']   = $filter->filter($this->_arrData['post']['password']);
    $this->_arrData['post']['country']    = $filter->filter($this->_arrData['post']['country']);
    $this->_arrData['post']['phone']      = $filter->filter($this->_arrData['post']['phone']);
    $this->_arrData['post']['email']      = $filter->filter($this->_arrData['post']['email']);
    $this->_arrData['post']['skype']      = $filter->filter($this->_arrData['post']['skype']);
		return $this->_arrData;
	}
}