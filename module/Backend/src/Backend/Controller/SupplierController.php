<?php

namespace Backend\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Backend\Model\Supplier;
use Backend\Model\Product;
use Backend\Model\Comment;
use Backend\Model\Image;
use Sky\Uploads\Upload;
use Sky\Uploads\Thumbs;

class SupplierController extends AbstractActionController
{
    public function indexAction()
    {
      $supplier = new Supplier();
			$request    = $this->getRequest();
			$url        = $this->getRequest()->getRequestUri();
			$data= array();
			if($request->isPost() == true){
				$arrayParam['post']     = $request->getPost()->toArray();
				$arrayParam['status']   = $arrayParam['post']['function'];
				if(isset($arrayParam['post']['function']) && $arrayParam['post']['function'] != ''){
					switch ($arrayParam['post']['function']){
						case 'delete':
							// delete
							if(isset($arrayParam['post']['check-all']) && !empty($arrayParam['post']['check-all']))
							{
								foreach ($arrayParam['post']['check-all'] as $value){
									$arrayParam['id']   = $value;
									$newsInfo           = $news->getNewById($arrayParam);
									if($newsInfo){
										$file           = new \Sky\Uploads\Thumbs();
										$file->removeImage(NEWS_ICON ."/", array('1' => '150x150/', '2' => ''), $newsInfo['image'], 2);
										$news->deleteNews($newsInfo['id']);
									}
								}
								$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa thành công.</div>');
							}
							return $this->redirect()->toUrl($url);
							break;
						case 'published': // active
						case 'unpublished': // deactive
								if($supplier->updateStatus($arrayParam)){
										$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Cập nhật trạng thái thành công.</div>');
										return $this->redirect()->toUrl($url);
								}else{
										$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Cập nhật thất bại.</div>');
										 return $this->redirect()->toUrl($url);
								}
								break;
						case 'sort':
								if(isset($arrayParam['post']['sort']) && !empty($arrayParam['post']['sort'])){
										$dataSort = array();
										foreach ($arrayParam['post']['sort'] as $key => $value){
												$dataSort['id'] = $key;
												$dataSort['sort'] = $value;
												$supplier->updateSortById($dataSort);
										}
										$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Cập nhật vị trí thành công.</div>');
										return $this->redirect()->toUrl($url);
								}
								break;
						default :
							$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Bạn chưa chọn chức năng.</div>');
							return $this->redirect()->toUrl($url);
					}
				}else{
					$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Bạn chưa chọn chức năng.</div>');
				}
			}
			$arrayParam = $this->params()->fromRoute();
			$order      = $this->params()->fromRoute('order') ? $this->params()->fromRoute('order'):'desc';
			$sort       = $this->params()->fromRoute('sort') ? $this->params()->fromRoute('sort'):'id';
			$search     = $this->params()->fromRoute('txtSearch') ? $this->params()->fromRoute('txtSearch'): null;
			$page       = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : null;

			$arrayParam['limit'] = PAGING_LIMIT;
			// lay so trang
			$arrayParam['page'] = (int) $this->params()->fromRoute('page', 0);
			if($arrayParam['page'] != 0){
					$arrayParam['offset'] = ($arrayParam['page'] - 1) * $arrayParam['limit'];
			}else{
					$arrayParam['offset'] = 0;
			}
			$supplierData = $supplier->getAll($arrayParam);
			$arrParam = array(
				'controller'	=> $arrayParam['__CONTROLLER__'],
				'action'		=> $arrayParam['action'],
				'page'          => (!empty($page))? '/page/'.$page: '',
				'sort'          => (!empty($sort))? '/sort/'.$sort: '',
				'order'         => (!empty($order))? '/order/'.$order: '',
				'txtSearch'     => (!empty($search))? '/txtSearch/'.$search: '',
			);
			// dem tong so user
			$countCategory = $supplier->countAllSupplier($arrayParam);

			// khoi tao phan trang
			$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\Null($countCategory[0]['count']));        
			$paginator->setCurrentPageNumber($arrayParam['page']);
			$paginator->setItemCountPerPage($arrayParam['limit']);

			if(is_numeric($page) && $page > $paginator->count())
			{
							// redirect 404
				return $this->redirect()->toRoute('backend',array('controller' => 'category', 'action' => 'index'));
			}
					$paginator->setPageRange(PAGE_RAND);


					$param      = '';
					if(!empty($sort)){ $param .= '/sort/'. $sort; }
					if(!empty($order)){ $param .= '/order/'. $order; }
					if(!empty($search)){ $param .= '/textSearch/'. $search; }

					// sort
					$paramSort = array();
					$this->params()->fromRoute('page') ? $paramSort['page'] = '/page/'.(int) $this->params()->fromRoute('page') : '';
					$this->params()->fromRoute('sort') ? $paramSort['sort'] ='/sort/'. $this->params()->fromRoute('sort'):'';
					($this->params()->fromRoute('order') === 'asc') ? $paramSort['order'] = '/order/desc': $paramSort['order'] = '/order/asc';
					$this->params()->fromRoute('textSearch') ? $paramSort['textSearch'] = '/textSearch/'. $this->params()->fromRoute('textSearch'): '';

					$module                 = explode('\\', $arrayParam['controller']);
					$data['module']         = $module[0];
					$data['arrayParam']     = $arrayParam;
					$data['title']          = "Nhà cung cấp";
					$data['param']          = $arrParam;
					$data['paginator']      = $paginator;
					$data['page']			= $page;
					$data['routeParam']		= $param;
					$data['controller']		= $arrayParam['__CONTROLLER__'];
					$data['action']			= $arrayParam['action'];
					$data['paramSort']               = $paramSort;
					$data['current_link'] = $url;     
					$data['list'] = $supplierData;
					return new ViewModel($data);
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
		public function editAction(){
			$arrayParam = array();
			$arrayParam['id'] = $this->params()->fromRoute('id');
			$supplier = new Supplier();
			$dataCategory = $supplier->getSupplierById($arrayParam);
			$request = $this->getRequest();
			if($request->isPost()== true){
				$arrayParam['post'] = $this->params()->fromPost();
				$validate = new \Backend\Form\ValidateSupplier($arrayParam, 'edit');
				if($validate->isError() === true){
				$arrayParam['error'] = $validate->getMessagesError();
				}else{
					$filter = new \Sky\Filter\SeoUrl();
					$arrayParam['post']['slug'] = $filter->filter($arrayParam['post']['slug']);
					$supplier->addSupplier($arrayParam);
					$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Cập nhật nhà cung cấp thành công.</div>');
					return $this->redirect()->toRoute('backend', array('controller' => 'supplier', 'action' => 'index'));
				}
			}else{
				$arrayParam['post'] = $dataCategory;
			}
			$data['arrayParam'] = $arrayParam;
			return new ViewModel($data);
		}
		public function changestatusAction(){
        $request = $this->getRequest();
        $arrayParam	= array(); 
        if($request->isPost()){
            $supplier = new Supplier();
            $id         = $request->getPost('id');
            if(is_numeric($request->getPost('status')) && in_array($request->getPost('status'), array(0,1))){
                $status     = ($request->getPost('status') == 1)? '0' : '1';
                $arrayParam['id'] = $id;
                $arrayParam['status'] = $status;
                if(is_numeric($id) && is_numeric($status)){
                    $dataSupplier  = $supplier->getSupplierById($arrayParam);
                    if($dataSupplier){
											$supplier->changeStatus($arrayParam);
											$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Thay đổi trạng thái thành công.</div>');
                    }
                }
            }
        }
        return new JsonModel($arrayParam);
    }
		
		public function deletesupplierAction(){
			$arrayParam = array();
			$supplier = new Supplier();
			$request = $this->getRequest();
			if($request->isPost() == true){
				$arrayParam['supplier_id'] = $this->params()->fromPost('supplier_id');
				$product = new Product();
				$dataProduct = $product->getAllProductBySupplierId($arrayParam);
				if(!empty($dataProduct)){
					$image = new Image();
					$comment = new Comment();
					foreach ($dataProduct as $value){
						// delete comment by product id
						$arrayParam['comment_type'] = 'products';
						$arrayParam['product_id'] = $arrayParam['id'] =  $value['id'];
						$comment->deleteCommentByProductId($arrayParam);
						// delete image by product id
						$arrayParam['type'] = 'product';
						$dataImage = $image->getImageByProductId($arrayParam);
						if($dataImage){
							$thumb = new Thumbs();
							foreach ($dataImage as $val){
								$thumb->removeImage(PRODUCT_ICON ."/", array('1' => '40x80/', '2' => '160x180/', '3' => '260x300/', '4' => ''), $val['name'], 4);
								$image->deleteImageByProductId($arrayParam);
							}
						}
						// delete product by id
						$product->deleteProductById($arrayParam['id']);
					}
				}
				// delete supplier
				if($supplier->deleteSupplierById($arrayParam['supplier_id'])){
					$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa nhà cung cấp thành công.</div>');
				}else{
					$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa thất bại...</div>');
				}
			}
			return new JsonModel($arrayParam);
		}
}	