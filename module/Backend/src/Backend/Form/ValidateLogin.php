<?php
namespace Backend\Form;
class ValidateLogin{
	
	//============================================
	//Chứa Thông Báo Lỗi
	//============================================
	protected $_messagesError = NULL;
	
	//============================================
	//Chứa Dữ Liệu Đúng Yêu Cầu
	//============================================
	protected $_arrData = NULL;
	
	public function __construct($arrayParam = array(), $options = null){
		$this->_arrData	= $arrayParam;
		
		//============================================
		//Kiểm Tra Tên
		//============================================
		$validator = new \Zend\Validator\ValidatorChain();
		$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
//					->addValidator(new \Zend\Validator\StringLength(6,32), true)
                    ->addValidator(new \Zend\Validator\EmailAddress, true);
		if(!$validator->isValid($arrayParam['post']['email'])){
			$message = $validator->getMessages();
			$this->_messagesError['email'] = 'Email: ' . current($message);
		}
		
		//============================================
		//Kiểm Tra Password
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
	//Kiem Tra Va Tra Ve True Neu Co Loi
	//============================================
	public function isError(){
		if(count($this->_messagesError) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	//============================================
	//Tra Ve Cac Thong Bao Loi
	//============================================
	public function getMessagesError(){
		return $this->_messagesError;
	}
	
	//============================================
	//Tra Ve Cac Du Lieu Dung Yeu Cau
	//============================================
	public function getData($options = null){
		$filter = new \Zend\Filter\StringTrim(array('charlist' => ' '));
		$this->_arrData['post']['email'] = $filter->filter($this->_arrData['post']['email']);
		$this->_arrData['post']['password'] = $filter->filter($this->_arrData['post']['password']);
		return $this->_arrData;
	}
}