<?php
namespace Backend\Form;
class ValidatePermission{
	
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
		
        if($options == 'add'){
			// kiểm tra name
            $validator = new \Zend\Validator\ValidatorChain();
			$validator->addValidator(new \Zend\Validator\NotEmpty(), true);
            if(!$validator->isValid($arrayParam['post']['name'])){
				$message = $validator->getMessages();
				$this->_messagesError['name'] = 'Tên: ' . current($message);
			}
            if(!$validator->isValid($arrayParam['post']['resource_id'])){
				$message = $validator->getMessages();
				$this->_messagesError['resource_id'] = 'Resource: ' . current($message);
			}
			
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
		$this->_arrData['post']['module'] = $filter->filter($this->_arrData['post']['module']);
		$this->_arrData['post']['controller'] = $filter->filter($this->_arrData['post']['controller']);
		return $this->_arrData;
	}
}