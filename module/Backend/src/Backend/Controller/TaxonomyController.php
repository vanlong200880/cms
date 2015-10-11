<?php
namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Backend\Form\ValidateTaxonomy;
use Sky\Uploads\Upload;
use Sky\Uploads\Thumbs;
use Backend\Model\Taxonomy;
use Backend\Model\Category;
use Backend\Model\Product;
use Backend\Model\Comment;
use Backend\Model\Image;
use Backend\Model\News;
use Zend\Session\Container;

class TaxonomyController extends AbstractActionController
{
    public function indexAction()
    {
        $taxonomy = new Taxonomy();
        $dataTaxonomy = $taxonomy->getAll();
        $data = array();
        $data['list'] = $dataTaxonomy;
        return new ViewModel($data);
    }
    public function addAction()
    {
        $arrayParam = $this->params()->fromRoute();
        $request = $this->getRequest();
        if($request->isPost()){
            $arrayParam['post']	= $request->getPost()->toArray();
            $validate = new ValidateTaxonomy($arrayParam, 'add');
			if($validate->isError() === true){
				$arrayParam['error'] = $validate->getMessagesError();
            }else{
                $data = $validate->getData();
                $taxonomy = new Taxonomy();
                $taxonomy->addTaxonomy($data);
                $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Tạo Taxonomy thành công.</div>');
                if(isset($arrayParam['post']['save'])){
                    return $this->redirect()->toRoute('backend', array('controller' => 'taxonomy', 'action' => 'index'));
                }else{
                    if(isset($arrayParam['post']['save-news'])){
                        return $this->redirect()->toRoute('backend', array('controller' => 'taxonomy', 'action' => 'add'));
                    }
                }
            }
        }
        $data['arrayParam'] = $arrayParam;
        return new ViewModel($data);
    }
    public function editAction()
    {
        $data = array();
        $taxonomy =  new Taxonomy();
        $arrayParam = $this->params()->fromRoute();
        $request = $this->getRequest();
        $arrayParam['id'] = $this->params()->fromRoute('id');
        $dataTaxonomy = $taxonomy->getTaxonomyById($arrayParam);
        if($request->isPost() && !empty($dataTaxonomy)){
            $arrayParam['post']	= $request->getPost()->toArray();

                $validate = new ValidateTaxonomy($arrayParam, 'edit');
				if($validate->isError() === true){
					$arrayParam['error'] = $validate->getMessagesError();
				}else{
					$data = $validate->getData();
                    $taxonomy->addTaxonomy($data);
					$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Cập nhật Taxonomy thành công.</div>');
					return $this->redirect()->toRoute('backend', array('controller' => 'taxonomy','action' => 'index'));
				}
        }else{
            $arrayParam['post'] = $dataTaxonomy;
        }
        $data['arrayParam'] = $arrayParam;
        $data['id'] = $this->params()->fromRoute('id');
        return new ViewModel($data);
    }
    public function changestatusAction(){
        $request = $this->getRequest();
        $arrayParam	= array(); 
        if($request->isPost()){
            $taxonomy = new Taxonomy();
            $id         = $request->getPost('id');
            if(is_numeric($request->getPost('status')) && in_array($request->getPost('status'), array(0,1))){
                $status     = ($request->getPost('status') == 1)? '0' : '1';
                $arrayParam['id'] = $id;
                $arrayParam['status'] = $status;
                if(is_numeric($id) && is_numeric($status)){
                    $dataTaxonomy  = $taxonomy->getTaxonomyById($arrayParam);
                    if($dataTaxonomy){
                        $taxonomy->changeStatus($arrayParam);
                        $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Thay đổi trạng thái thành công.</div>');
                    }
                }
            }
        }
        return new JsonModel($arrayParam);
    }
    
    public function deleteAction(){
        $arrayParam = array();
        $request = $this->getRequest();
        if($request->isPost()){
            $arrayParam['id']         = $request->getPost('id');
            $taxonomy = new Taxonomy();
            /*
             * + delete news
             * + delete product
             * + delete category
             * + delete taxonomy
             */
            $category       = new Category();
            $dataCategory   = $category->getCategoryByTaxonomyId($arrayParam);
            $file = new Thumbs();
            if(!empty($dataCategory)){
                $news = new News();
                $product = new Product();
                $comment = new Comment();
                $image = new Image();
                foreach ($dataCategory as $value){
                    //delete table news
                    $paramId['id'] = $value['id'];
                    $dataNews = $news->getNewsByCategoryId($paramId);
                    if(!empty($dataNews)){
                        foreach ($dataNews as $val){
                            // remove image
                            $file->removeImage(NEWS_ICON ."/", array('1' => '120x120/', '2' => ''), $val['image'], 2);
                            $news->deleteNews($val['id']);
                        }
                    }
                    //delete table product
                    $productData['id'] = $value['id'];
                    $dataProduct = $product->getProductByCategoryId($productData);
                    if(!empty($dataProduct)){
                        foreach ($dataProduct as $val){
                            // delete comment
                            $comment->deleteCommentByProductId($val['id']);
                            // delete image
                            $imageParam['id'] = $val['id'];
                            $dataImage = $image->getImageByProductId($imageParam);
                            if($dataImage){
                                foreach ($dataImage as $vimage){
                                    // remove image
                                    
                                    // delete image
                                    $image->deleteImageById($vimage['id']);
                                }
                            }
                            // delete product id
                            $product->deleteProductById($val['id']);
                        }
                    }
                    
                }
            }
            
//            if($language->getLanguageById($arrayParam)){                
//                if($language->deleteLanguage($arrayParam)){
//                    $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa ngôn ngữ thành công.</div>');
//                }
//            }
        }
        return new JsonModel($arrayParam);
    }
}
