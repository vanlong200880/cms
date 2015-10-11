<?php
namespace Backend\Form;
class ValidateCategory{
	
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
        // kiểm taxonomy
        $validator = new \Zend\Validator\ValidatorChain();
		$validator->addValidator(new \Zend\Validator\NotEmpty(), true);
		if(!$validator->isValid($arrayParam['post']['taxonomy_id'])){
			$message = $validator->getMessages();
			$this->_messagesError['taxonomy_id'] = 'Nhóm: ' . current($message);
		}
		// kiểm tra tên
        $validator = new \Zend\Validator\ValidatorChain();
		$validator->addValidator(new \Zend\Validator\NotEmpty(), true);
		if(!$validator->isValid($arrayParam['post']['name'])){
			$message = $validator->getMessages();
			$this->_messagesError['name'] = 'Name: ' . current($message);
		}
		// kiểm tra code
        if(isset($arrayParam['post']['slug'])){
            if(isset($arrayParam['id']) == false){
                $option = array(
                    'table'     => 'category',
                    'field'     => 'slug',
                    'adapter'   => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
                );
            }else{
                $option = array('table' => 'category',
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
        $this->_arrData['post']['taxonomy'] = $filter->filter($this->_arrData['post']['taxonomy']);
        $this->_arrData['post']['category'] = $filter->filter($this->_arrData['post']['category']);
		$this->_arrData['post']['name'] = $filter->filter($this->_arrData['post']['name']);
		$this->_arrData['post']['slug'] = $filter->filter($this->_arrData['post']['slug']);
        $this->_arrData['post']['excerpt'] = $filter->filter($this->_arrData['post']['excerpt']);
        $this->_arrData['post']['slug'] = $filter->filter($this->_arrData['post']['slug']);
        $this->_arrData['post']['title'] = $filter->filter($this->_arrData['post']['title']);
        $this->_arrData['post']['keyword'] = $filter->filter($this->_arrData['post']['keyword']);
        $this->_arrData['post']['description'] = $filter->filter($this->_arrData['post']['description']);
		$this->_arrData['post']['status'] = $filter->filter($this->_arrData['post']['status']);
		return $this->_arrData;
	}
}