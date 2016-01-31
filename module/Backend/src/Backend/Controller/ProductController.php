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
use Backend\Form\ValidateAjaxUploadImageProduct;
use Backend\Form\ValidateProductQuickEdit;
use Backend\Form\ValidateStore;
use Backend\Form\ValidateTrademark;
use Backend\Form\ValidateProduct;
use Backend\Model\Image;
use Sky\Uploads\Upload;
use Sky\Uploads\Thumbs;
use Zend\Session\Container;
use Backend\Model\OrderDetail;
use Backend\Model\Comment;

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
        $arrayParam['slug'] = 'products';
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
                    $filter = new \Sky\Filter\SeoUrl();
                    if(empty($arrayParam['post']['slug']))
                      $arrayParam['post']['slug'] = $filter->filter($arrayParam['post']['name']);

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
        // trademark
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
        $arrayParam['slug'] = 'products';
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
                $arrayParam['slug'] = 'products';
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
        $session = new Container(APPLICATION_KEY);
        $data = array();
        $product = new Product();
        $category = new Category();
        $arrayParam = array();
        $arrayParam['slug'] = 'products';
        $arrayParam['id'] = $this->params()->fromRoute('id');
        $dataProduct = $product->getProductById($arrayParam);
        $arrayParam['post'] = $dataProduct;
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
                $validate = new ValidateProduct($arrayParam, 'ecit');
                if($validate->isError() === true){
                    $arrayParam['error'] = $validate->getMessagesError();
                }else{
                    $product->addProduct($arrayParam);
                    // change image
                    if(isset($arrayParam['post']['image']) && !empty($arrayParam['post']['image']['name'])){
                        $image = new Image();
                        $uploadFile = new Upload();
                        $thumb = new Thumbs();
                        if(!empty($arrayParam['post']['image']['name'])){
                            $arrayParam['dataImage'] = array(
                                'product_id' => $arrayParam['id'],
                                'type' => 'product',
                                'name' => $arrayParam['post']['image']['name'],
                                'mine' => $arrayParam['post']['image']['type'],
                                'size' => $arrayParam['post']['image']['size'],
                                'timestamp' => time(),
                                'status' => 1,
                                'highlight' => 1
                            );
                            $dataImage = $image->getImageByProductIdHighlight($arrayParam);
                            
                            if($dataImage){
                                $thumb->removeImage(PRODUCT_ICON ."/", array('1' => '40x80/', '2' => '160x180/', '3' => '260x300/', '4' => ''), $dataImage[0]['name'], 4);
                                $newName = $uploadFile->uploadImage($arrayParam['post']['image']['name'], PRODUCT_ICON);
                                $arrayParam['dataImage']['name'] = $newName;
                                $thumb->createThumb(PRODUCT_ICON ."/". $newName, array('1' => 40, '2' => 160, '3' => 260), array('1' => 80, '2' => 180, '3' => 300), array('1' => PRODUCT_ICON.'/40x80/', '2' => PRODUCT_ICON.'/160x180/', '3' => PRODUCT_ICON.'/260x300/'), 3, '');
                                $arrayParam['image_id'] = $dataImage[0]['id'];
                                $image->addImage($arrayParam);
                            }
                        }
                    } 
                    $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Cập nhất sản phẩm thành công.</div>');
                    return $this->redirect()->toRoute('backend', array('controller' => 'product', 'action' => 'index')); 
                }
            }
            
        }
        // get imag by product id
        $image = new Image();
        $arrayParam['type'] = 'products';
        $dataImage = $image->getImageByProductId($arrayParam);
        $arrayParam['dataImage'] = $dataImage;
        // check category active
        $dataCategory = $category->getCategoryBySlug($arrayParam);
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
        
        // trademark
        $trademark = new Trademark();
        $dataTrademark = $trademark->getAllTrademark();
        $data['trademark'] = $dataTrademark;
        
        $data['arrayParam'] = $arrayParam;
        return new ViewModel($data);
    }
    public function deleteimagegalleryAction(){
        $arrayParam= array();
        $request = $this->getRequest();
        if($request->isPost() == true){
            $image = new Image();
            $arrayParam['id'] = $this->params()->fromPost('id');
            $dataImage = $image->getImageById($arrayParam);
            $arrayParam['message'] = '';
            if($dataImage){
                // remove image
                $thumb = new Thumbs();
                $thumb->removeImage(PRODUCT_ICON ."/", array('1' => '40x80/', '2' => '160x180/', '3' => '260x300/', '4' => ''), $dataImage[0]['name'], 4);
                if(!$image->deleteImageById($arrayParam)){
                    $arrayParam['message'] = 'Ảnh này bạn không được phép xóa.';
                }
            } 
        }
        return new JsonModel($arrayParam); 
    }
    
    public function uploadimaggeajaxAction(){
        $arrayParam = array();
        $arrayParam['id'] = $this->params()->fromPost('product_upload_id');
        $arrayParam['image_form_submit'] = $this->params()->fromPost('image_form_submit');
        if($arrayParam['image_form_submit'] == 1)
        {
            $image = new Image();
            $uploadFile = new Upload();
            $thumb = new Thumbs();
            $images_arr = array();
            $imagePreview = array();
            foreach($_FILES['images']['name'] as $key=>$val){
                $arrayParam['dataImage'] = array(
                    'product_id' => $arrayParam['id'],
                    'type' => 'products',
                    'name' => $_FILES['images']['name'][$key],
                    'mine' => $_FILES['images']['type'][$key],
                    'size' => $_FILES['images']['size'][$key],
                    'timestamp' => time(),
                    'status' => 1,
                    'highlight' => 0
                );
                $arrayParam['post']['image']['tmp_name'] = $_FILES['images']['tmp_name'][$key];
                $validateUpload = new ValidateAjaxUploadImageProduct($arrayParam);
                if($validateUpload->isError() === true){
                    $arrayParam['error'] = $validateUpload->getMessagesError();
                }else{
                    
                    $newName = $uploadFile->uploadImage($_FILES['images']['name'][$key], PRODUCT_ICON);
                    $arrayParam['dataImage']['name'] = $newName;
                    $thumb->createThumb(PRODUCT_ICON ."/". $newName, array('1' => 40, '2' => 160, '3' => 260), array('1' => 80, '2' => 180, '3' => 300), array('1' => PRODUCT_ICON.'/40x80/', '2' => PRODUCT_ICON.'/160x180/', '3' => PRODUCT_ICON.'/260x300/'), 3, '');
                    $id = $image->addImage($arrayParam);
                    $imagePreview[] = array(
                        'id'    => $id,
                        'name'  => $newName
                    );
                }
                
            }
            $arrayParam['imagePreview']= $imagePreview;
        }
        return new JsonModel($arrayParam);
    }
    
    public function deleteproductAction(){
        $arrayParam = array();
        $request = $this->getRequest();
        if($request->isPost() == true){
            $arrayParam['message'] = '';
            $orderDetail = new OrderDetail();
            $arrayParam['product_id'] = $this->params()->fromPost('id');
            $arrayParam['id'] = $this->params()->fromPost('id');
            $dataOrderDetail = $orderDetail->getOrderDetailByProductId($arrayParam);
            if($dataOrderDetail){
                $arrayParam['message'] = 'Bạn không được quyền xóa sản phẩm này.';
            }else{
                // delete comment
                $comment = new Comment();
                $arrayParam['comment_type'] = 'products';
                $comment->deleteCommentByProductId($arrayParam);
                // delete image
                $image = new Image();
                $arrayParam['type'] = 'products';
                $dataImage = $image->getImageByProductId($arrayParam);
                if($dataImage){
                    $thumb = new Thumbs();
                    foreach ($dataImage as $value){
                        $thumb = new Thumbs();
                        $thumb->removeImage(PRODUCT_ICON ."/", array('1' => '40x80/', '2' => '160x180/', '3' => '260x300/', '4' => ''), $value['name'], 4);
                    }
                }
                $product = new Product();
                $product->deleteProductById($arrayParam['id']);
                $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa sản phẩm thành công.</div>');
            }
        }
        return new JsonModel($arrayParam); 
    }
    
    public function quickeditAction(){
        $arrayParam = array();
        $request = $this->getRequest();
        $html = '';
        if($request->isPost() == true){
            $arrayParam['id'] = $this->params()->fromPost('id');
            // get product
            $product = new Product();
            $dataProduct = $product->getProductById($arrayParam);
            $category = new Category();
            $arrayParam['slug'] = 'products';
            $dataCategory = $category->getCategoryBySlug($arrayParam);
            $htmlCategory = $this->getDataCategory($dataProduct['category_id'], $dataCategory);
            if($dataProduct){
                $dataProduct['hot']         = ($dataProduct['hot'] == 1)? 'checked': '';
                $dataProduct['sticky']      = ($dataProduct['sticky'] == 1)? 'checked': '';
                $dataProduct['highlight']   = ($dataProduct['highlight'] == 1)? 'checked': '';
                $dataProduct['promote']     = ($dataProduct['promote'] == 1)? 'checked': '';
                $html .= '<tr id="edit-'.$dataProduct['id'].'" class="edit-row">
                            <td colspan="9" class="">
                                <div class="column-qiuck-edit">
                                    <div class="col-lg-12"><h2>Sửa nhanh</h2></div>
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input type="hidden" value="'.$dataProduct['id'].'" id="id">
                                                    <label for="name">Tên sản phẩm</label><span class="validation">*</span>
                                                    <input type="text" data-toggle="tooltip" value="'.$dataProduct['name'].'" data-placement="top" title="Nhập tên sản phẩm" class="form-control" id="name" name="name">
                                                    <p class="error" id="error_name"></p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="slug">Slug</label><span class="validation">*</span>
                                                    <input type="text" data-toggle="tooltip" value="'.$dataProduct['slug'].'" data-placement="top" title="Nhập alias cho sản phẩm" class="form-control" id="slug" name="slug">
                                                    <p class="error" id="error_slug"></p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="color">Màu sắc</label>
                                                    <input type="text" id="color" class="form-control" value="'.$dataProduct['color'].'" data-toggle="tooltip" data-placement="top" title="Nhập màu sắc cho sản phẩm ( cách nhau bởi dấu "," )" name="color">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="size">Size</label>
                                                    <input type="text" id="size" class="form-control" value="'.$dataProduct['size'].'" id="size" data-toggle="tooltip" data-placement="top" title="Nhập Size cho sản phẩm ( cách nhau bởi dấu "," )" name="size">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="cost">Giá nhập</label><span class="validation">*</span>
                                                    <input type="number" min="0" class="form-control" value="'.$dataProduct['cost'].'" name="cost" data-toggle="tooltip" data-placement="top" title="Giá nhập hàng (giá gốc)" id="cost">
                                                    <p class="error" id="error_cost"></p>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="price">Giá bán</label><span class="validation">*</span>
                                                    <input type="number" id="price" min="0" class="form-control" value="'.$dataProduct['price'].'" name="price" id="price" data-toggle="tooltip" data-placement="top" title="Nhập giá bán">
                                                    <p class="error" id="error_price"></p>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="weight">Giảm giá (%)</label>
                                                    <input type="number" id="sale" name="sale" min="0" max="100" value="'.$dataProduct['sale'].'" class="form-control" id="sale" data-toggle="tooltip" data-placement="top" title="Giảm giá ( min: 0 - max: 100 )">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="tax">Thuế</label>
                                                    <input type="number" min="0" max="30" value="'.$dataProduct['tax'].'" name="tax" class="form-control" data-toggle="tooltip" data-placement="top" title="Thuế (VAT)" id="tax">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="hidden" name="highlight" value="0">
                                                            <input type="checkbox" name="highlight" id="sticky" value="1" '.$dataProduct['highlight'].'>Hiện ở trang chủ
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="hidden" name="hot" value="0">
                                                            <input type="checkbox" name="hot" id="promote" value="1" '.$dataProduct['hot'].'>Sản phẩm mới
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="hidden" name="sticky" value="0">
                                                            <input type="checkbox" name="sticky" id="promote" value="1" '.$dataProduct['sticky'].'>Sản phẩm bán chạy
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="hidden" name="promote" value="0">
                                                            <input type="checkbox" name="promote" id="promote" value="1" '.$dataProduct['promote'].'>Sản phẩm quảng cáo
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="sort">Sắp xếp</label>
                                                    <input type="number" min="0" value="'.$dataProduct['sort'].'" class="form-control" id="sort" name="sort" placeholder="0" data-toggle="tooltip" data-placement="top" title="Nhập thử tự hiển thị">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Danh mục cha</label>
                                                    <select id="basic-quick-edit" class="selectpicker show-tick form-control" data-live-search="true">
                                                        '.$htmlCategory.'
                                                     </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-default btn-back">Trở lại</button>
                                                    <button type="button" class="btn btn-default btn-custom pull-right" id="quick-edit">Cập nhật</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6"></div>
                                </div>
                            </td>
                        </tr>';
            }
        }
        $arrayParam['html'] = $html;
        return new JsonModel($arrayParam);
    }
    
    public function quickupdateAction(){
        $arrayParam = array();
        $request = $this->getRequest();
        if($request->isPost() == true){
            $arrayParam['post'] = $this->params()->fromPost();
            $arrayParam['id'] = $this->params()->fromPost('id');
            $validate = new ValidateProductQuickEdit($arrayParam, 'quick-edit');
            if($validate->isError() === true){
                $arrayParam['error'] = $validate->getMessagesError();
            }else{
                if($arrayParam['post']['slug']){
                  $arrayParam['post']['slug'] = $filter->filter($arrayParam['post']['name']);
                }
                $arrayParam['post']['modified'] = time();
                $product = new Product();
                $product->quickEdit($arrayParam);
            }
        }
        return new JsonModel($arrayParam);
    }
}