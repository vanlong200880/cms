<?php
namespace Backend\Form;
class ValidateUserChangePassword{
	
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
		
		// kiểm tra email đã tồn tại trong database chưa
//        if(isset($arrayParam['post']['email'])){
//            $options = array(
//                'table' => 'user',
//                'field' => 'email',
//                'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
//            );
//        }
        if($options == 'changepassword'){
            // kiểm tra mật khẩu cũ nhập có đúng không
            $options = array(
                'table' => 'user',
                'field' => 'password',
                'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
            );
            $validator = new \Zend\Validator\ValidatorChain();
            $validator->addValidator(new \Zend\Validator\NotEmpty(), true)
                        ->addValidator(new \Zend\Validator\Db\RecordExists($options), true);
            if(!$validator->isValid($arrayParam['post']['passwordold'])){
                $message = $validator->getMessages();
                $this->_messagesError['passwordold'] = 'Mật khẩu hiện tại: ' . current($message);
            }
			//Kiểm Tra Password
			$validator = new \Zend\Validator\ValidatorChain();
			$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
						->addValidator(new \Zend\Validator\StringLength(6,32), true);
			if(!$validator->isValid($arrayParam['post']['password'])){
				$message = $validator->getMessages();
				$this->_messagesError['password'] = 'Mật khẩu: ' . current($message);
			}

			// kiểm tra nhập lại password
			if($arrayParam['post']['password'] !== ''){
				$validator = new \Zend\Validator\ValidatorChain();
				$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
						  ->addValidator(new \Zend\Validator\Identical(array('token' => $arrayParam['post']['password'])));
				if(!$validator->isValid($arrayParam['post']['repassword'])){
					$message = $validator->getMessages();
					$this->_messagesError['repassword'] = current($message);
				}
			}
        } 

	}
    
    /*
     * kiem tra nam nhuan
     */
    function LeapYear($year){
        $flag = false;
        if( ($year % 400 == 0) || ($year % 4 == 0 && $year % 100 != 0) ) 
            $flag = true;
        return $flag;
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
		$this->_arrData['post']['fullname'] = $filter->filter($this->_arrData['post']['fullname']);
		$this->_arrData['post']['day'] = $filter->filter($this->_arrData['post']['day']);
		$this->_arrData['post']['month'] = $filter->filter($this->_arrData['post']['month']);
		$this->_arrData['post']['year'] = $filter->filter($this->_arrData['post']['year']);
		$this->_arrData['post']['sex'] = $filter->filter($this->_arrData['post']['sex']);
		$this->_arrData['post']['address'] = $filter->filter($this->_arrData['post']['address']);
		$this->_arrData['post']['avartar'] = $filter->filter($this->_arrData['post']['avartar']);
		return $this->_arrData;
	}
}