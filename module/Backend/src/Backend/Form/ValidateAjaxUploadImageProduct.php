<?php
namespace Backend\Form;
class ValidateAjaxUploadImageProduct{
	
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
        // kiểm tra image
        if(isset($arrayParam['id']) == false){
            $validator = new \Zend\Validator\ValidatorChain();
            $validator->addValidator(new \Zend\Validator\NotEmpty(), true);
            $validator->addValidator(new \Zend\Validator\File\MimeType('image/jpg, image/jpeg, image/png'));
            $validator->addValidator(new \Zend\Validator\File\ImageSize(array(
                'minWidth' => PRODUCT_MIN_WIDTH, 'minHeight' => PRODUCT_MIN_HEIGHT,
                'maxWidth' => PRODUCT_MAX_WIDTH, 'maxHeight' => PRODUCT_MAX_HRIGHT,
            )));
            if(!$validator->isValid($arrayParam['post']['image']['tmp_name'])){
                $message = $validator->getMessages();
                var_dump(current($message));
                $this->_messagesError['image'] = current($message);
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
		$this->_arrData['post']['name'] = $filter->filter($this->_arrData['post']['name']);
		$this->_arrData['post']['slug'] = $filter->filter($this->_arrData['post']['slug']);
		$this->_arrData['post']['status'] = $filter->filter($this->_arrData['post']['status']);
		return $this->_arrData;
	}
}