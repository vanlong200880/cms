<?php

namespace Backend\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Backend\Model\Product;
use Backend\Model\Category;
use Backend\Model\Supplier;
use Backend\Model\Trademark;
use Backend\Form\ValidateCategory;
use Backend\Form\ValidateSupplier;
use Backend\Form\ValidateStore;
use Backend\Form\ValidateTrademark;
use Backend\Form\ValidateProduct;
use Backend\Model\Image;
use Sky\Uploads\Upload;
use Sky\Uploads\Thumbs;
use Zend\Session\Container;

class ProductController extends AbstractActionController
{
    public function indexAction()
    {
        $request    = $this->getRequest();
        $url        = $this->getRequest()->getRequestUri();
        $data = array();
        $product = new Product();
        $arrayParam = $this->params()->fromRoute();
        if($request->isPost() == true){
            $data                   = $this->getRequest();
            $arrayParam['post']     = $request->getPost()->toArray();
            $arrayParam['status']   = $arrayParam['post']['function'];
            
            if(isset($arrayParam['post']['function']) && $arrayParam['post']['function'] != ''){
                switch ($arrayParam['post']['function']){
                    case 'delete':
                        // delete
                        if(isset($arrayParam['post']['check-all']) && !empty($arrayParam['post']['check-all'])){
                            foreach ($arrayParam['post']['check-all'] as $value){
                                $arrayParam['id']   = $value;
                                $userInfo           = $user->getUserById($value);
                                if($userInfo){
                                    $file           = new \Sky\Uploads\Thumbs();
                                    $file->removeImage(USER_ICON ."/", array('1' => '80x80/', '2' => ''), $userInfo['avartar'], 2);
                                    $user->deleteUser($arrayParam);
                                }
                            }
                            $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa thành công.</div>');
                        }
                        return $this->redirect()->toUrl($url);
                        break;
                    case 'published': // active
                    case 'unpublished': // deactive
                        
                        if($product->updateStatus($arrayParam)){
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
                                $product->updateSortById($dataSort);
                            }
                            $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Cập nhật vị trí thành công.</div>');
                            return $this->redirect()->toUrl($url);
                        }
                        break;
                    default :
                        return $this->redirect()->toUrl($url);
                        break;
                }
            }
        }
        
       
        $order      = $this->params()->fromRoute('order') ? $this->params()->fromRoute('order'):'desc';
        $sort       = $this->params()->fromRoute('sort') ? $this->params()->fromRoute('sort'):'id';
        $type       = $this->params()->fromRoute('type') ? $this->params()->fromRoute('type'): null;
        $search     = $this->params()->fromRoute('textSearch') ? $this->params()->fromRoute('textSearch'): null;
        $page       = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : null;
        
        $arrayParam['limit'] = PAGING_LIMIT;
        // lay so trang
        $arrayParam['page'] = (int) $this->params()->fromRoute('page', 0);
        if($arrayParam['page'] != 0){
            $arrayParam['offset'] = ($arrayParam['page'] - 1) * $arrayParam['limit'];
        }else{
            $arrayParam['offset'] = 0;
        }
        $arrParam = array(
			'controller'	=> $arrayParam['__CONTROLLER__'],
			'action'		=> $arrayParam['action'],
            'page'          => (!empty($page))? '/page/'.$page: '',
            'type'          => (!empty($type))? '/type/'.$type: '',
            'sort'          => (!empty($sort))? '/sort/'.$sort: '',
            'order'         => (!empty($order))? '/order/'.$order: '',
            'textSearch'     => (!empty($search))? '/textSearch/'.$search: '',
        );

        $dataProduct = $product->getAllProduct($arrayParam);
        // dem tong so user
        $countUser = $product->countTotalProduct($arrayParam);
        // khoi tao phan trang
        $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\Null($countUser['count']));        
        $paginator->setCurrentPageNumber($arrayParam['page']);
        $paginator->setItemCountPerPage($arrayParam['limit']);
        
		if(is_numeric($page) && $page > $paginator->count())
		{
            // redirect 404
			///return $this->redirect()->toRoute('backend',array('controller' => 'product', 'action' => 'index'));
		}
        $paginator->setPageRange(PAGE_RAND);
		
		
        $param      = '';
        if(!empty($type)){ $param .= '/type/'. $type; }
        if(!empty($sort)){ $param .= '/sort/'. $sort; }
        if(!empty($order)){ $param .= '/order/'. $order; }
        if(!empty($search)){ $param .= '/textSearch/'. $search; }
        
        // sort
        $paramSort = array();
        $this->params()->fromRoute('page') ? $paramSort['page'] = '/page/'.(int) $this->params()->fromRoute('page') : '';
        $this->params()->fromRoute('type') ? $paramSort['type'] ='/type/'. $this->params()->fromRoute('type'): '';
        $this->params()->fromRoute('sort') ? $paramSort['sort'] ='/sort/'. $this->params()->fromRoute('sort'):'';
        ($this->params()->fromRoute('order') === 'asc') ? $paramSort['order'] = '/order/desc': $paramSort['order'] = '/order/asc';
        $this->params()->fromRoute('textSearch') ? $paramSort['textSearch'] = '/textSearch/'. $this->params()->fromRoute('textSearch'): '';
        $category = new Category();
        $module                 = explode('\\', $arrayParam['controller']);
        $data['module']         = $module[0];
        $data['arrayParam']     = $arrayParam;
        $data['list']           = $dataProduct;
        $data['type']           = $category->getAllCategory();
        $data['title']          = "Danh sách user";
        $data['param']          = $arrParam;
        $data['paginator']      = $paginator;
		$data['page']			= $page;
		$data['routeParam']		= $param;
		$data['controller']		= $arrayParam['__CONTROLLER__'];
		$data['action']			= $arrayParam['action'];
        $data['paramSort']               = $paramSort;
        $data['current_link'] = $url;
        return new ViewModel($data);
    }
    public function changestatusAction(){
        $request = $this->getRequest();
        $arrayParam	= array(); 
        if($request->isPost()){
            $product = new Product();
            $id         = $request->getPost('id');
            if(is_numeric($request->getPost('status')) && in_array($request->getPost('status'), array(0,1))){
                $status     = ($request->getPost('status') == 1)? '0' : '1';
                $arrayParam['id'] = $id;
                $arrayParam['status'] = $status;
                if(is_numeric($id) && is_numeric($status)){
                    $dataProduct  = $product->getProductById($arrayParam);
                    if($dataProduct){
                        $product->changeStatus($arrayParam);
                        $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Thay đổi trạng thái thành công.</div>');
                    }
                }
            }
        }
        return new JsonModel($arrayParam);
    }
    
    public function addAction()
    {
        $session = new Container(APPLICATION_KEY);
        $data = array();
        $category = new Category();
        $product = new Product();
        $arrayParam = array();
        $arrayParam['slug'] = 'product';
        $request = $this->getRequest();
        if($request->isPost() == true){
            if(!empty($session->auth['userId'])){
                $arrayParam['post']	= array_merge_recursive(
                                    $request->getPost()->toArray(),
                                    $request->getFiles()->toArray()
                                );
                $arrayParam['post']['user_id'] = $session->auth['userId'];
                $arrayParam['post']['view'] = 0;
                $arrayParam['post']['startday'] = ($arrayParam['post']['startday'])? strtotime($arrayParam['post']['startday']) : 0;
                $arrayParam['post']['endday'] = ($arrayParam['post']['endday'])? strtotime($arrayParam['post']['endday']) : 0;
                $arrayParam['post']['created'] = $arrayParam['post']['modified'] = time();
                $validate = new ValidateProduct($arrayParam, 'add');
                if($validate->isError() === true){
                    $arrayParam['error'] = $validate->getMessagesError();
                }else{
                    $id = $product->addProduct($arrayParam);
                    $image = new Image();
                    $uploadFile = new Upload();
                    $thumb = new Thumbs();
                    if(!empty($arrayParam['post']['image']['name'])){
                        $arrayParam['dataImage'] = array(
                            'product_id' => $id,
                            'type' => 'product',
                            'name' => $arrayParam['post']['image']['name'],
                            'mine' => $arrayParam['post']['image']['type'],
                            'size' => $arrayParam['post']['image']['size'],
                            'timestamp' => time(),
                            'status' => 1,
                            'highlight' => 1
                        );
                        $newName = $uploadFile->uploadImage($arrayParam['post']['image']['name'], PRODUCT_ICON);
                        $arrayParam['dataImage']['name'] = $newName;
                        $thumb->createThumb(PRODUCT_ICON ."/". $newName, array('1' => 40, '2' => 160, '3' => 260), array('1' => 80, '2' => 180, '3' => 300), array('1' => PRODUCT_ICON.'/40x80/', '2' => PRODUCT_ICON.'/160x180/', '3' => PRODUCT_ICON.'/260x300/'), 3, '');
                        $image->addImage($arrayParam);
                    }
                    
                    // upload image gallery
                    if(isset($arrayParam['post']['gallery']) && !empty($arrayParam['post']['gallery'])){
                        foreach ($arrayParam['post']['gallery'] as $galleryValue){
                            if($galleryValue['name']){
                                $arrayParam['dataImage'] = array(
                                    'product_id' => $id,
                                    'type' => 'product',
                                    'name' => $galleryValue['name'],
                                    'mine' => $galleryValue['type'],
                                    'size' => $galleryValue['size'],
                                    'timestamp' => time(),
                                    'status' => 1,
                                    'highlight' => 0
                                );
                                $newName = $uploadFile->uploadImage($galleryValue['name'], PRODUCT_ICON);
                                $arrayParam['dataImage']['name'] = $newName;
                                $thumb->createThumb(PRODUCT_ICON ."/". $newName, array('1' => 40, '2' => 160, '3' => 260), array('1' => 80, '2' => 180, '3' => 300), array('1' => PRODUCT_ICON.'/40x80/', '2' => PRODUCT_ICON.'/160x180/', '3' => PRODUCT_ICON.'/260x300/'), 3, '');
                                $image->addImage($arrayParam);
                            }
                        }
                    }
                    $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Đăng sản phẩm thành công.</div>');
                    if(isset($arrayParam['post']['save'])){
                        return $this->redirect()->toRoute('backend', array('controller' => 'product', 'action' => 'index'));
                    }else{
                        if(isset($arrayParam['post']['save-news'])){
                            return $this->redirect()->toRoute('backend', array('controller' => 'product', 'action' => 'add'));
                        }
                    }                    
                }
            }
            
        }
        $data['arrayParam'] = $arrayParam;
        $dataCategory = $category->getCategoryBySlug($arrayParam);
        
        // check category active
        $categoryActive = isset($arrayParam['post']['category_id'])? $arrayParam['post']['category_id']: '0';
        $data['category'] = '<option value="0">-- Chọn --</option>'.$this->getDataCategory($categoryActive, $dataCategory);
        // supplier
        $supplier = new Supplier();
        $dataSupplider = $supplier->getAllSupplier();
        $data['supplier'] = $dataSupplider;
        // shop
        $shop = new \Backend\Model\Store();
        $dataShop = $shop->getAllShop();
        $data['shop'] = $dataShop;
        // shop
        $trademark = new Trademark();
        $dataTrademark = $trademark->getAllTrademark();
        $data['trademark'] = $dataTrademark;
        return new ViewModel($data);
    }
    public function getDataCategory($active = '', $data ,$parent = 0, $text = ''){
        $dataOption = array();
        
        foreach ($data as $key => $value){
            if($value['parent'] == $parent){
                $dataOption[] = $value;
                unset($data[$key]);
            }
        }
        $html = '';
        if($dataOption){
            foreach ($dataOption as $key => $val){
                $selected = ($val['id'] == $active)?'selected':'';
                $html .= '<option '.$selected.' value="'.$val['id'].'">'.$text.$val['name']. '</option>';
                $html .= $this->getDataCategory($active, $data, $val['id'], $text.'--');
            }
        }
        return $html;
    }
    
    public function ajaxloadcategoryAction(){
        $arrayParam	= array(); 
        $category = new Category();
        $arrayParam['slug'] = 'product';
        $dataCategory = $category->getCategoryBySlug($arrayParam);
        $html = $this->getDataCategory(0, $dataCategory);
        $arrayParam['data'] = '<option value="0">-- Chọn --</option>'.$html;
        return new JsonModel($arrayParam);
    }
    public function ajaxaddcategoryAction(){
        $arrayParam	= array();
        $request = $this->getRequest();
        if($request->isPost()){
            $arrayParam['post'] = $this->params()->fromPost();
            $category = new Category();
            $validate = new ValidateCategory($arrayParam, 'category_ajax');
            if($validate->isError() === true){
                $arrayParam['error'] = $validate->getMessagesError();
            }else{
                $arrayParam['error'] = '';
                $taxonomy = new \Backend\Model\Taxonomy();
                $arrayParam['slug'] = 'product';
                $dataTaxonomy = $taxonomy->getTaxonomyBySlug($arrayParam);
                $arrayParam['post']['excerpt']      = '';
                $arrayParam['post']['created']      = time();
                $arrayParam['post']['changed']      = time();
                $arrayParam['post']['title']        = '';
                $arrayParam['post']['keyword']      = '';
                $arrayParam['post']['description']  = '';
                $arrayParam['post']['sort']         = 0;
                $arrayParam['post']['status']       = 1;
                $arrayParam['post']['taxonomy_id']  = $dataTaxonomy['id'];
                $id = $category->addCategory($arrayParam);
                
                // reload category
                $dataCategory = $category->getCategoryBySlug($arrayParam);
                $html = $this->getDataCategory($id, $dataCategory);
                $arrayParam['reloadCategory'] = $html;
//                $arrayParam['data'] = '<option value="0">-- Chọn --</option>'.$html;
            }
        }
        return new JsonModel($arrayParam);
    }
    
    public function ajaxaddsupplierAction(){
        $arrayParam	= array();
        $request = $this->getRequest();
        if($request->isPost()){
            $arrayParam['post'] = $this->params()->fromPost();
            $supplier = new Supplier();
            $validate = new ValidateSupplier($arrayParam, 'supplier_ajax');
            if($validate->isError() === true){
                $arrayParam['error'] = $validate->getMessagesError();
            }else{
                $arrayParam['error'] = '';
                $arrayParam['post']['phone']        = '';
                $arrayParam['post']['address']      = '';
                $arrayParam['post']['email']        = '';
                $arrayParam['post']['companyname']  = '';
                $arrayParam['post']['tax']          = '';
                $arrayParam['post']['note']         = '';
                $arrayParam['post']['account']      = '';
                $arrayParam['post']['status']       = 1;
                $id = $supplier->addSupplier($arrayParam);
                // reload category
                $dataSupplier = $supplier->getAllSupplier($arrayParam);
                $html = '';
                $html .= '<option value="0">-- Chọn --</option>';
                if($dataSupplier){
                    foreach ($dataSupplier as $value){
                        $selected = ($id == $value['id'])?'selected':'';
                        $html .= '<option '.$selected.' value='.$value['id'].'>'.$value['name'].'</option>';
                    }
                }
                $arrayParam['reloadSupplier'] = $html;
            }
        }
        return new JsonModel($arrayParam);
    }
    
    public function ajaxaddstoreAction(){
        $arrayParam	= array();
        $request = $this->getRequest();
        if($request->isPost()){
            $arrayParam['post'] = $this->params()->fromPost();
            $shop = new \Backend\Model\Store();
            $validate = new ValidateStore($arrayParam, 'shop_ajax');
            if($validate->isError() === true){
                $arrayParam['error'] = $validate->getMessagesError();
            }else{
                $arrayParam['error'] = '';
                $arrayParam['post']['phone']        = '';
                $arrayParam['post']['address']      = '';
                $arrayParam['post']['fax']        = '';
                $arrayParam['post']['status']       = 1;
                $id = $shop->addShop($arrayParam);
                // reload category
                $dataSupplier = $shop->getAllShop($arrayParam);
                $html = '';
                $html .= '<option value="0">-- Chọn --</option>';
                if($dataSupplier){
                    foreach ($dataSupplier as $value){
                        $selected = ($id == $value['id'])?'selected':'';
                        $html .= '<option '.$selected.' value='.$value['id'].'>'.$value['name'].'</option>';
                    }
                }
                $arrayParam['reloadShop'] = $html;
            }
        }
        return new JsonModel($arrayParam);
    }
    public function ajaxaddtrademarkAction(){
        $arrayParam	= array();
        $request = $this->getRequest();
        if($request->isPost()){
            $arrayParam['post'] = $this->params()->fromPost();
            $trademark = new Trademark();
            $validate = new ValidateTrademark($arrayParam, 'trademark_ajax');
            if($validate->isError() === true){
                $arrayParam['error'] = $validate->getMessagesError();
            }else{
                $arrayParam['error'] = '';
                $arrayParam['post']['title']        = '';
                $arrayParam['post']['description']  = '';
                $arrayParam['post']['keyword']      = '';
                $arrayParam['post']['status']       = 1;
                $id = $trademark->addTrademark($arrayParam);
                // reload category
                $dataTrademark = $trademark->getAllTrademark($arrayParam);
                $html = '';
                $html .= '<option value="0">-- Chọn --</option>';
                if($dataTrademark){
                    foreach ($dataTrademark as $value){
                        $selected = ($id == $value['id'])?'selected':'';
                        $html .= '<option '.$selected.' value='.$value['id'].'>'.$value['name'].'</option>';
                    }
                }
                $arrayParam['reloadTrademark'] = $html;
            }
        }
        return new JsonModel($arrayParam);
    }
    
    public function editAction(){
        $data = array();
        return new ViewModel($data);
    }
}