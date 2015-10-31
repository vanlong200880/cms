<?php
namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Backend\Form\ValidateTaxonomy;
use Sky\Uploads\Thumbs;
use Backend\Model\Taxonomy;
use Backend\Model\Category;
use Backend\Model\Product;
use Backend\Model\Comment;
use Backend\Model\OrderDetail;
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
            $taxonomy = new Taxonomy();
            $category = new Category();
            $arrayParam['id']         = $request->getPost('id');
            // get taxonomy by slug;
            $dataTaxonomy = $taxonomy->getTaxonomyById($arrayParam);
            if($dataTaxonomy){
                // get category by taxonomy id
                $dataListCategory = $category->getCategoryByTaxonomyId($arrayParam);
                $arrayParam['dataListCategory'] = $dataListCategory;
                switch ($dataTaxonomy['slug']){
                    case 'products':
                        if($dataListCategory){
                            $dataListProductId = array();
                            foreach ($dataListCategory as $value){
                                array_push($dataListProductId, $value['id']);
                            }
                            // get list product in array() category
                            $product = new Product();
                            $arrayParam['list'] = $dataListProductId;
                            $listProduct = $product->getAllProductByArrayCategoryId($arrayParam);
                            if($listProduct){
                                $listProductId = array();
                                foreach ($listProduct as $val){
                                    array_push($listProductId, $val['id']);
                                }
                                // check product in order detail
                                $orderDetail = new OrderDetail();
                                $arrayParam['listIdProduct'] = $listProductId;
                                $dataOrderDetail = $orderDetail->countAllProductByArrayCategoryId($arrayParam);
                                if($dataOrderDetail){
                                    $arrayParam['message'] = 'Bạn không được xóa taxonomy này.';
                                }else{
                                    $comment = new Comment();
                                    $image = new Image();
                                    foreach ($listProductId as $v){
                                        // delete comment
                                        $arrayParam['comment_type'] = 'products';
                                        $arrayParam['product_id'] = $v;
                                        $comment->deleteCommentByProductId($arrayParam);
                                        $arrayParam['type'] = 'products';
                                        $dataImage = $image->getImageByProductId($arrayParam);
                                        if($dataImage){
                                            $thumb = new Thumbs();
                                            foreach ($dataImage as $value){
                                                $thumb->removeImage(PRODUCT_ICON ."/", array('1' => '40x80/', '2' => '160x180/', '3' => '260x300/', '4' => ''), $value['name'], 4);
                                            }
                                        }
                                        // delete product by id
                                        $product->deleteProductById($v);
                                    }
                                    // delete category by taxonomy id
                                    $category->deleteCategoryByTaxonomyId($arrayParam);
                                    // delete list taxonomy
                                    $taxonomy->deleteTaxonomy($arrayParam);
                                    $arrayParam['message'] = 'Xóa thành công.';
                                }  
                            }else{
                                // delete category by taxonomy id
                                $category->deleteCategoryByTaxonomyId($arrayParam);
                                // delete list taxonomy
                                $taxonomy->deleteTaxonomy($arrayParam);
                                $arrayParam['message'] = 'Xóa thành công.';
                            }
                        }else{
                            //delete taxonomy
                            $taxonomy->deleteTaxonomy($arrayParam);
                            $arrayParam['message'] = 'Xóa thành công.';
                        }
                        break;
                    case 'news':
                        $news = new News();
                        if($dataListCategory){
                            $dataListNewsId = array();
                            foreach ($dataListCategory as $value){
                                array_push($dataListNewsId, $value['id']);
                            }
//                            $arrayParam['sdf'] = $dataListNewsId;
                            $arrayParam['list'] = $dataListNewsId;
                            $listNews = $news->getAllNewsByArrayCategoryId($arrayParam);
//                            $arrayParam['234'] = $listNews;
                            if($listNews){
                                $comment = new Comment();
                                $thumb = new Thumbs();
                                foreach ($listNews as $val){
                                    // delete comment
                                    $arrayParam['comment_type'] = 'news';
                                    $arrayParam['product_id'] = $val['id'];
                                    $comment->deleteCommentByProductId($arrayParam);
                                    // delete news
                                    $thumb->removeImage(NEWS_ICON ."/", array('1' => '150x150/', '2' => ''), $val['image'], 2);
                                    $news->deleteNews($val['id']);
                                }
                                // delete category
                                $category->deleteCategoryByTaxonomyId($arrayParam);
                                // delete taxonomy
                                $taxonomy->deleteTaxonomy($arrayParam);
                                $arrayParam['message'] = 'Xóa thành công.';
                                $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa thành công.</div>');
                            }else{
                                // delete category
                                $category->deleteCategoryByTaxonomyId($arrayParam);
                                // delete taxonomy
                                $taxonomy->deleteTaxonomy($arrayParam);
                                $arrayParam['message'] = 'Xóa thành công.';
                                $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa thành công.</div>');
                            }
                        }else{
                            // delete taxonomy
                            $taxonomy->deleteTaxonomy($arrayParam);
                            $arrayParam['message'] = 'Xóa thành công.';
                            $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa thành công.</div>');
                        }
                        break;
                    default :
                        $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Taxonomy không được phép xóa.</div>');
                        break;
                }
            }
        }
        return new JsonModel($arrayParam);
    }
}
