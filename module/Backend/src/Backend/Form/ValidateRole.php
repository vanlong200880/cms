<?php
namespace Backend\Form;
class ValidateRole{
	
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
		
		// kiểm tra nhóm đã tồn tại trong database chưa
        if(isset($arrayParam['post']['role_name'])){
            $option = array(
                'table' => 'role',
                'field' => 'role_name',
                'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
            );
        }
        if($options == 'add'){
			// kiểm tra name
            $validator = new \Zend\Validator\ValidatorChain();
			$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
						->addValidator(new \Zend\Validator\Db\NoRecordExists($option), true);
			if(!$validator->isValid($arrayParam['post']['role_name'])){
				$message = $validator->getMessages();
				$this->_messagesError['name'] = 'Nhóm: ' . current($message);
			}
			
        }
//		
//		if($options == 'edit'){
//			if(!empty($arrayParam['post']['name'])){
//				// kiểm tra nhóm
//				$validator = new \Zend\Validator\ValidatorChain();
//				$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
//							->addValidator(new \Zend\Validator\EmailAddress, true)
//							->addValidator(new \Zend\Validator\Db\NoRecordExists($option), true);
//				if(!$validator->isValid($arrayParam['post']['name'])){
//					$message = $validator->getMessages();
//					$this->_messagesError['email'] = 'Nhóm: ' . current($message);
//				}
//			}
//		}
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
		$this->_arrData['post']['role_name'] = $filter->filter($this->_arrData['post']['role_name']);
		$this->_arrData['post']['description'] = $filter->filter($this->_arrData['post']['description']);
		$this->_arrData['post']['fullname'] = $filter->filter($this->_arrData['post']['fullname']);
		$this->_arrData['post']['weight'] = $filter->filter($this->_arrData['post']['weight']);
		$this->_arrData['post']['status'] = $filter->filter($this->_arrData['post']['status']);
		return $this->_arrData;
	}
}