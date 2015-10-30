<?php
namespace Backend\Form;
class ValidateNews{
	
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
			$this->_messagesError['name'] = 'Tiêu đề: ' . current($message);
		}
		// kiểm tra slug
        if(isset($arrayParam['id']) == false){
            $option = array(
                'table'     => 'news',
                'field'     => 'slug',
                'adapter'   => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
            );
        }else{
            $option = array('table' => 'news',
                'field'     => 'slug',
                'adapter'   => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
                'exclude'   => array('field' => 'id',
                                   'value' => $arrayParam['id'])
             );
        }
        $validator = new \Zend\Validator\ValidatorChain();
        $validator->addValidator(new \Zend\Validator\NotEmpty(), true)
                  ->addValidator(new \Zend\Validator\Db\NoRecordExists($option), true);
        if(!$validator->isValid($arrayParam['post']['slug'])){			
            $message = $validator->getMessages();
            $this->_messagesError['slug'] = 'Slug: ' . current($message);
        }
        // kiểm tra category
        $validator = new \Zend\Validator\ValidatorChain();
		$validator->addValidator(new \Zend\Validator\NotEmpty(), true);
		if(!$validator->isValid($arrayParam['post']['category_id'])){
			$message = $validator->getMessages();
			$this->_messagesError['category_id'] = 'Danh mục: ' . current($message);
		}
        
        // kiểm tra image
        if($options == 'add'){
          if(isset($arrayParam['id']) == false){
            $validator = new \Zend\Validator\ValidatorChain();
            $validator->addValidator(new \Zend\Validator\NotEmpty(), true);
            $validator->addValidator(new \Zend\Validator\File\MimeType('image/jpg, image/jpeg, image/png'));
            $validator->addValidator(new \Zend\Validator\File\ImageSize(array(
                'minWidth' => NEWS_MIN_WIDTH, 'minHeight' => NEWS_MIN_HEIGHT,
                'maxWidth' => NEWS_MAX_WIDTH, 'maxHeight' => NEWS_MAX_HRIGHT,
            )));
            if(!$validator->isValid($arrayParam['post']['image']['tmp_name'])){
                $message = $validator->getMessages();
                $this->_messagesError['image'] = current($message);
            }
          }
        }
        if($options == 'edit'){
          if(isset($arrayParam['post']['image']) && !empty($arrayParam['post']['image']['name'])){
            $validator = new \Zend\Validator\ValidatorChain();
            $validator->addValidator(new \Zend\Validator\NotEmpty(), true);
            $validator->addValidator(new \Zend\Validator\File\MimeType('image/jpg, image/jpeg, image/png'));
            $validator->addValidator(new \Zend\Validator\File\ImageSize(array(
                'minWidth' => NEWS_MIN_WIDTH, 'minHeight' => NEWS_MIN_HEIGHT,
                'maxWidth' => NEWS_MAX_WIDTH, 'maxHeight' => NEWS_MAX_HRIGHT,
            )));
            if(!$validator->isValid($arrayParam['post']['image']['tmp_name'])){
                $message = $validator->getMessages();
                $this->_messagesError['image'] = current($message);
            }
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
		return $this->_arrData;
	}
}