<?php
namespace Backend\Form;
class ValidateUser{
	
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
        if(isset($arrayParam['post']['email'])){
            $option = array(
                'table' => 'user',
                'field' => 'email',
                'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
            );
        }
        if($options == 'add'){
			// kiểm tra email
            $validator = new \Zend\Validator\ValidatorChain();
			$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
						->addValidator(new \Zend\Validator\EmailAddress, true)
						->addValidator(new \Zend\Validator\Db\NoRecordExists($option), true);
			if(!$validator->isValid($arrayParam['post']['email'])){
				$message = $validator->getMessages();
				$this->_messagesError['email'] = 'Email: ' . current($message);
			}
			//Kiểm Tra Password
			$validator = new \Zend\Validator\ValidatorChain();
			$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
						->addValidator(new \Zend\Validator\StringLength(6,32), true);
			if(!$validator->isValid($arrayParam['post']['password'])){
				$message = $validator->getMessages();
				$this->_messagesError['password'] = 'Password: ' . current($message);
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
		
		if($options == 'edit'){
			if(!empty($arrayParam['post']['email'])){
				// kiểm tra email
				$validator = new \Zend\Validator\ValidatorChain();
				$validator->addValidator(new \Zend\Validator\NotEmpty(), true)
							->addValidator(new \Zend\Validator\EmailAddress, true)
							->addValidator(new \Zend\Validator\Db\NoRecordExists($option), true);
				if(!$validator->isValid($arrayParam['post']['email'])){
					$message = $validator->getMessages();
					$this->_messagesError['email'] = 'Email: ' . current($message);
				}
			}
		}
        
        // kiểm tra vartar
        if($arrayParam['post']['avartar']['name'] !== ''){
            $validator = new \Zend\Validator\ValidatorChain();
            $validator->addValidator(new \Zend\Validator\File\MimeType('image/jpg, image/jpeg, image/png'));
            $validator->addValidator(new \Zend\Validator\File\ImageSize(array(
                'minWidth' => USER_MIN_WIDTH, 'minHeight' => USER_MIN_HEIGHT,
                'maxWidth' => USER_MAX_WIDTH, 'maxHeight' => USER_MAX_HRIGHT,
            )));
            if(!$validator->isValid($arrayParam['post']['avartar']['tmp_name'])){
                $message = $validator->getMessages();
                $this->_messagesError['avartar'] = current($message);
            }
        }
        
        // kiểm tra tên
        $validator = new \Zend\Validator\ValidatorChain();
		$validator->addValidator(new \Zend\Validator\NotEmpty(), true);
		if(!$validator->isValid($arrayParam['post']['fullname'])){
			$message = $validator->getMessages();
			$this->_messagesError['fullname'] = 'Họ và tên: ' . current($message);
		}
        
        // kiểm tra giới tính
        if(!isset($arrayParam['post']['sex'])){
            $this->_messagesError['sex'] = 'Giới tính: ' . current($message);
        }else{
            $validator = new \Zend\Validator\ValidatorChain();
            $validator->addValidator(new \Zend\Validator\NotEmpty(), true);
            if(!$validator->isValid($arrayParam['post']['sex'])){
                $message = $validator->getMessages();
                $this->_messagesError['sex'] = 'Giới tính: ' . current($message);
            }
        }
        // kiểm tra chọn role
        if(!isset($arrayParam['post']['role'])){
            $this->_messagesError['role'] = 'Bạn chưa chọn quyền truy cập.';
        }else{
            $validator = new \Zend\Validator\ValidatorChain();
            $validator->addValidator(new \Zend\Validator\NotEmpty(), true);
            if(!$validator->isValid($arrayParam['post']['role'])){
                $message = $validator->getMessages();
                $this->_messagesError['role'] = 'Role: ' . current($message);
            }
        }
        
        // kiem tra ngay thang nam
        if(!empty($arrayParam['post']['day']) && !empty($arrayParam['post']['month']) && !empty($arrayParam['post']['year'])){
            $day = (int)$arrayParam['post']['day'];
            $month = (int)$arrayParam['post']['month'];
            $year = (int)$arrayParam['post']['year'];
            switch ($month){
                case 1:
                    if($day <= 0 || $day > 31){
                        $this->_messagesError['date'] = 'Ngày tháng năm không hợp lệ.';
                    }
                    break;
                case 2:
                    if($day <= 0 || $day >= 31){
                        $this->_messagesError['date'] = 'Ngày tháng năm không hợp lệ.';
                    }else{
                        if($this->LeapYear($year)){
                            if($day  > 29){
                                $this->_messagesError['date'] = 'Ngày không hợp lệ.';
                            }
                        }else{
                            if($day  > 28){
                                $this->_messagesError['date'] = 'Ngày không hợp lệ.';
                            }
                        }
                    }
                    break;
                case 3:
                    if($day <= 0 || $day > 31){
                        $this->_messagesError['date'] = 'Ngày không hợp lệ.';
                    }
                    break;
                case 4:
                    if($day <= 0 || $day > 30){
                        $this->_messagesError['date'] = 'Ngày không hợp lệ.';
                    }
                    break;
                case 5:
                    if($day <= 0 || $day > 31){
                        $this->_messagesError['date'] = 'Ngày không hợp lệ.';
                    }
                    break;
                case 6:
                    if($day <= 0 || $day > 30){
                        $this->_messagesError['date'] = 'Ngày không hợp lệ.';
                    }
                    break;
                case 7:
                    if($day <= 0 || $day > 31){
                        $this->_messagesError['date'] = 'Ngày không hợp lệ.';
                    }
                    break;
                case 8:
                    if($day <= 0 || $day > 31){
                        $this->_messagesError['date'] = 'Ngày không hợp lệ.';
                    }
                    break;
                case 9:
                    if($day <= 0 || $day > 30){
                        $this->_messagesError['date'] = 'Ngày không hợp lệ.';
                    }
                    break;
                case 10:
                    if($day <= 0 || $day > 31){
                        $this->_messagesError['date'] = 'Ngày không hợp lệ.';
                    }
                    break;
                case 11:
                    if($day <= 0 || $day > 30){
                        $this->_messagesError['date'] = 'Ngày không hợp lệ.';
                    }
                    break;
                case 12:
                    if($day <= 0 || $day > 31){
                        $this->_messagesError['date'] = 'Ngày không hợp lệ.';
                    }
                    break;
                default:
                    $this->_messagesError['date'] = 'Ngày tháng năm không hợp lệ.';
                break;
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