<?php
namespace Backend\Form;
class ValidateTrademark{
	
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
        // kiểm tra slug
        if(isset($arrayParam['post']['slug'])){
            if(isset($arrayParam['id']) == false){
                $option = array(
                    'table'     => 'trademark',
                    'field'     => 'slug',
                    'adapter'   => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
                );
            }else{
                $option = array('table' => 'trademark',
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
		$this->_arrData['post']['name'] = $filter->filter($this->_arrData['post']['name']);
		return $this->_arrData;
	}
}