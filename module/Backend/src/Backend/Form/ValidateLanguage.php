<?php
namespace Backend\Form;
class ValidateLanguage{
	
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
		// kiểm tra tên
        $validator = new \Zend\Validator\ValidatorChain();
		$validator->addValidator(new \Zend\Validator\NotEmpty(), true);
		if(!$validator->isValid($arrayParam['post']['name'])){
			$message = $validator->getMessages();
			$this->_messagesError['name'] = 'Name: ' . current($message);
		}
		// kiểm tra code
        if(isset($arrayParam['post']['code'])){
            if(isset($arrayParam['id']) == false){
                $option = array(
                    'table'     => 'language',
                    'field'     => 'code',
                    'adapter'   => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
                );
            }else{
                $option = array('table' => 'language',
                    'field'     => 'code',
                    'adapter'   => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
                    'exclude'   => array('field' => 'id',
                                       'value' => $arrayParam['id'])
                 );
            }
            $validator = new \Zend\Validator\ValidatorChain();
            $validator->addValidator(new \Zend\Validator\NotEmpty(), true)
                      ->addValidator(new \Zend\Validator\Db\NoRecordExists($option), true);
            if(!$validator->isValid($arrayParam['post']['code'])){			
                $message = $validator->getMessages();
                $this->_messagesError['code'] = 'Slug: ' . current($message);
            }
            
        }
        // kiểm tra icon
        if($options =='add'){
            $validator = new \Zend\Validator\ValidatorChain();
            $validator->addValidator(new \Zend\Validator\NotEmpty(), true);
            $validator->addValidator(new \Zend\Validator\File\MimeType('image/jpg, image/jpeg, image/png'));
            $validator->addValidator(new \Zend\Validator\File\ImageSize(array(
                'minWidth' => LANGUAGE_MIN_WIDTH, 'minHeight' => LANGUAGE_MIN_HEIGHT,
                'maxWidth' => LANGUAGE_MAX_WIDTH, 'maxHeight' => LANGUAGE_MAX_HRIGHT,
            )));
            if(!$validator->isValid($arrayParam['post']['icon']['tmp_name'])){
                $message = $validator->getMessages();
                $this->_messagesError['icon'] = current($message);
            }
        }
        
        if($options =='edit'){
            if(isset($arrayParam['post']['icon']['name']) && !empty($arrayParam['post']['icon']['name'])){
                $validator = new \Zend\Validator\ValidatorChain();
                $validator->addValidator(new \Zend\Validator\NotEmpty(), true);
                $validator->addValidator(new \Zend\Validator\File\MimeType('image/jpg, image/jpeg, image/png'));
                $validator->addValidator(new \Zend\Validator\File\ImageSize(array(
                    'minWidth' => LANGUAGE_MIN_WIDTH, 'minHeight' => LANGUAGE_MIN_HEIGHT,
                    'maxWidth' => LANGUAGE_MAX_WIDTH, 'maxHeight' => LANGUAGE_MAX_HRIGHT,
                )));
                if(!$validator->isValid($arrayParam['post']['icon']['tmp_name'])){
                    $message = $validator->getMessages();
                    $this->_messagesError['icon'] = current($message);
                }
            }
        }
        // kiểm tra name
        $validator = new \Zend\Validator\ValidatorChain();
        $validator->addValidator(new \Zend\Validator\NotEmpty(), true)
                    ->addValidator(new \Zend\Validator\Db\NoRecordExists($option), true);
        if(!$validator->isValid($arrayParam['post']['code'])){
            $message = $validator->getMessages();
            $this->_messagesError['code'] = 'Slug: ' . current($message);
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
		$this->_arrData['post']['code'] = $filter->filter($this->_arrData['post']['code']);
		$this->_arrData['post']['icon'] = $filter->filter($this->_arrData['post']['icon']);
		$this->_arrData['post']['status'] = $filter->filter($this->_arrData['post']['status']);
		$this->_arrData['post']['default'] = $filter->filter($this->_arrData['post']['default']);
		return $this->_arrData;
	}
}