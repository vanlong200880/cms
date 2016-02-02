<?php

namespace Backend\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Backend\Model\Supplier;

class SupplierController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    public function addAction()
    {
			$arrayParam = array();
			$request = $this->getRequest();
			if($request->isPost()== true){
				$arrayParam['post'] = $this->params()->fromPost();
				$validate = new \Backend\Form\ValidateSupplier($arrayParam, 'add');
				if($validate->isError() === true){
				$arrayParam['error'] = $validate->getMessagesError();
				}else{
					$supplier = new Supplier();
					$filter = new \Sky\Filter\SeoUrl();
					if(empty($arrayParam['post']['slug']))
						$arrayParam['post']['slug'] = $filter->filter($arrayParam['post']['name']);
					else
						$arrayParam['post']['slug'] = $filter->filter($arrayParam['post']['slug']);

					$supplier->addSupplier($arrayParam);
					$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Thêm nhà cung câp thành công.</div>');
					if(isset($arrayParam['post']['save'])){
						return $this->redirect()->toRoute('backend', array('controller' => 'supplier', 'action' => 'index'));
					}else{
						if(isset($arrayParam['post']['save-news'])){
							return $this->redirect()->toRoute('backend', array('controller' => 'supplier', 'action' => 'add'));
						}
					}
				}
			}
			
			$data['arrayParam'] = $arrayParam;
			return new ViewModel($data);
    }
}