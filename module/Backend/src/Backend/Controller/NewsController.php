<?php
namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Backend\Model\News;
use Backend\Model\Category;
use Sky\Uploads\Upload;
use Sky\Uploads\Thumbs;
use Backend\Form\ValidateNews;
use Backend\Model\Comment;
use Backend\Form\ValidateCategory;
use Zend\Session\Container;
class NewsController extends AbstractActionController
{
    public function indexAction()
    {
        $request    = $this->getRequest();
        $url        = $this->getRequest()->getRequestUri();
        $data = array();
        $news = new News();
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
                        
                        if($news->updateStatus($arrayParam)){
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
                                $news->updateSortById($dataSort);
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
            'textSearch'    => (!empty($search))? '/textSearch/'.$search: '',
        );

        $dataNews = $news->getAllNews($arrayParam);
        // dem tong so user
        $countTotalNews = $news->countTotalNews($arrayParam);
        // khoi tao phan trang
        $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\Null($countTotalNews['count']));        
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
        $arrayParam['slug'] = 'news';
        $dataCategory = $category->getCategoryBySlug($arrayParam);

        $module                 = explode('\\', $arrayParam['controller']);
        $data['module']         = $module[0];
        $data['arrayParam']     = $arrayParam;
        $data['list']           = $dataNews;
        $data['category']       = '<option value="">-- Chọn --</option>'.$this->getDataCategory(0, $dataCategory);
        $data['title']          = "Danh sách user";
        $data['param']          = $arrParam;
        $data['paginator']      = $paginator;
		$data['page']			= $page;
		$data['routeParam']		= $param;
		$data['controller']		= $arrayParam['__CONTROLLER__'];
		$data['action']			= $arrayParam['action'];
        $data['paramSort']      = $paramSort;
        $data['current_link'] = $url;
        return new ViewModel($data);
    }
    public function changestatusAction(){
        $request = $this->getRequest();
        $arrayParam	= array(); 
        if($request->isPost()){
            $news = new News();
            $id         = $request->getPost('id');
            if(is_numeric($request->getPost('status')) && in_array($request->getPost('status'), array(0,1))){
                $status     = ($request->getPost('status') == 1)? '0' : '1';
                $arrayParam['id'] = $id;
                $arrayParam['status'] = $status;
                if(is_numeric($id) && is_numeric($status)){
                    $dataNews  = $news->getNewById($arrayParam);
                    if($dataNews){
                        $news->changeStatus($arrayParam);
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
        $arrayParam = array();
        $request = $this->getRequest();
        $category = new Category();
        $arrayParam['slug'] = 'news';
        if($request->isPost()){
            $arrayParam['post']	= array_merge_recursive(
                                    $request->getPost()->toArray(),
                                    $request->getFiles()->toArray()
                                );
                                
            $validate = new ValidateNews($arrayParam, 'add');
			if($validate->isError() === true){
				$arrayParam['error'] = $validate->getMessagesError();
            }else{
                $news = new News();
                $uploadFile = new Upload();
                $thumb = new Thumbs();
                $arrayParam['post']['created']  = $arrayParam['post']['modified'] = time();
                $arrayParam['post']['user_id']  = $session->auth['userId'];
                $arrayParam['post']['startday'] = ($arrayParam['post']['startday'])? strtotime($arrayParam['post']['startday']) : 0;
                $arrayParam['post']['endday']   = ($arrayParam['post']['endday'])? strtotime($arrayParam['post']['endday']) : 0;
                $newName = $uploadFile->uploadImage($arrayParam['post']['image']['name'], NEWS_ICON);
                $thumb->createThumb(NEWS_ICON ."/". $newName, array('1' => 150), array('1' => 150), array('1' => NEWS_ICON.'/150x150/'), 1, '');
                $arrayParam['post']['image'] = $newName;
                $news->addNews($arrayParam);
                $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Đăng tin thành công.</div>');
                if(isset($arrayParam['post']['save']) && $arrayParam['post']['save'] == 'save'){
                    return $this->redirect()->toRoute('backend', array('controller' => 'news', 'action' => 'index'));
                }else{
                    if(isset($arrayParam['post']['save-news']) && $arrayParam['post']['save-news'] == 'save-news'){
                        return $this->redirect()->toRoute('backend', array('controller' => 'news', 'action' => 'add'));
                    }
                }
            }
        }
        $data['arrayParam'] = $arrayParam;
        $dataCategory = $category->getCategoryBySlug($arrayParam);
        $categoryActive = isset($arrayParam['post']['category_id'])? $arrayParam['post']['category_id']: '0';
        $data['category'] = '<option value="">-- Chọn --</option>'.$this->getDataCategory($categoryActive, $dataCategory);
        $data['arrayParam'] = $arrayParam;
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
    
    public function ajaxnewsaddcategoryAction(){
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
                $arrayParam['slug'] = 'news';
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
            }
        }
        return new JsonModel($arrayParam);
    }
    
    public function ajaxnewsloadcategoryAction(){
        $arrayParam	= array(); 
        $category = new Category();
        $arrayParam['slug'] = 'news';
        $dataCategory = $category->getCategoryBySlug($arrayParam);
        $html = $this->getDataCategory(0, $dataCategory);
        $arrayParam['data'] = '<option value="0">-- Chọn --</option>'.$html;
        return new JsonModel($arrayParam);
    }
    public function editAction(){
        $session = new Container(APPLICATION_KEY);
        $data = array();
        $news = new News();
        $category = new Category();
        $arrayParam = array();
        $arrayParam['slug'] = 'news';
        $arrayParam['id'] = $this->params()->fromRoute('id');
        $dataNews = $news->getNewById($arrayParam);
        $arrayParam['post'] = $dataNews;
        $request = $this->getRequest();
        if($request->isPost() == true){
          $arrayParam['post']	= array_merge_recursive(
                              $request->getPost()->toArray(),
                              $request->getFiles()->toArray());
          $arrayParam['post']['startday'] = ($arrayParam['post']['startday'])? strtotime($arrayParam['post']['startday']) : 0;
          $arrayParam['post']['endday'] = ($arrayParam['post']['endday'])? strtotime($arrayParam['post']['endday']) : 0;
          $arrayParam['post']['created'] = $arrayParam['post']['modified'] = time();
          $arrayParam['post']['user_id']  = $session->auth['userId'];
          $validate = new ValidateNews($arrayParam, 'edit');
          if($validate->isError() === true){
              $arrayParam['error'] = $validate->getMessagesError();
              $arrayParam['post']['image'] = $dataNews['image'];
          }else{
            if($arrayParam['post']['image']['name'] == ''){
              $arrayParam['post']['image'] = $dataNews['image'];
            }else{
              $uploadFile = new Upload();
              $thumb = new Thumbs();
              $newName = $uploadFile->uploadImage($arrayParam['post']['image']['name'], NEWS_ICON);
              // remove image news thumb
              $thumb->removeImage(NEWS_ICON ."/", array('1' => '150x150/', '2' => ''), $dataNews['image'], 2);
              // update news image
              $thumb->createThumb(NEWS_ICON ."/". $newName, array('1' => 150), array('1' => 150), array('1' => NEWS_ICON.'/150x150/'), 1, '');
              $arrayParam['post']['image'] = $newName;
            }
            // update news
            $news->addNews($arrayParam); 
            $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Cập nhật tin thành công.</div>');
            return $this->redirect()->toRoute('backend', array('controller' => 'news', 'action' => 'index')); 
          }
            
        }
        // check category active
        $dataCategory = $category->getCategoryBySlug($arrayParam);
        $categoryActive = isset($arrayParam['post']['category_id'])? $arrayParam['post']['category_id']: '0';
        $data['category'] = '<option value="0">-- Chọn --</option>'.$this->getDataCategory($categoryActive, $dataCategory);
        
        $data['arrayParam'] = $arrayParam;
        return new ViewModel($data);
    }
    public function rolepermissionAction(){
        $arrayParam = array();
        $arrayParam['id'] = $this->params()->fromRoute('id');
        // get role name
        $role = new Role();
        $roleInfo = $role->getRoleById($arrayParam);
        if(!empty($roleInfo)){
            $arrayParam['rolename'] = $roleInfo;
        }
        // save role permission
        $request = $this->getRequest();
        if($request->isPost()){
            $arrayParam['permission'] = $this->params()->fromPost('permission');
            if(isset($arrayParam['permission'])){
                $rolePermission = new RolePermission();
                $rolePermission->addRolePermission($arrayParam);
                $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Lưu thành công.</div>');
                return $this->redirect()->toRoute('backend', array('controller' => 'role', 'action' => 'rolepermission', 'id' => $arrayParam['id']));
            }
        } 
        $arrayParam['role-id'] = $this->params()->fromRoute('id');
        
        if(isset($arrayParam['id'])){
            if($role->getRoleById($arrayParam)){
                // lay danh sach module
                $resource = new Resource();
                $module = $resource->getListResource();
                $permission = new Permission();
                $dataList = array();
                if($module){
                    foreach ($module as $value){
                        $arrayParam['id'] = $value['id'];
                        // kiem tra co quyen chua
                        $actionList = $permission->getListPermissionAction($arrayParam);
                       
                        if(!empty($actionList)){
                            $rolePermission = new \Backend\Model\RolePermission();
                            foreach ($actionList as $key => $v){
                                if($rolePermission->getRoleByPermissionId($v['id'], $arrayParam['role-id'])){
                                    $actionList[$key]['flag'] = true;
                                }else
                                {
                                    $actionList[$key]['flag'] = false;
                                }
                                
                            }
                        }
                        $dataList[$value['controller']] =  array(
                            'module'        => $value['module'],
                            'controller'    => $value['controller'],
                            'action'        => $actionList
                        );
                    }
                }
                $arrayParam['list'] = $dataList;
            }
        }
        $data['arrayParam'] = $arrayParam;        
        return new ViewModel($data);
    }
    public function deletenewsAction(){
        $arrayParam = array();
        $news = new News();
        $request = $this->getRequest();
        if($request->isPost() == true){
            $arrayParam['message'] = '';
            $arrayParam['product_id'] = $this->params()->fromPost('id');
            $arrayParam['id'] = $this->params()->fromPost('id');
            // delete comment
            $dataNews = $news->getNewById($arrayParam);
            if($dataNews){
              $comment = new Comment();
              $arrayParam['comment_type'] = 'news';
              $comment->deleteCommentByProductId($arrayParam);
              $thumb = new Thumbs();
              $thumb->removeImage(NEWS_ICON ."/", array('1' => '150x150/', '2' => ''), $dataNews['image'], 2);
              $news->deleteNews($arrayParam['id']);
              $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa tin thành công.</div>');
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
            $news = new News();
            $dataNews = $news->getNewById($arrayParam);
            $category = new Category();
            $arrayParam['slug'] = 'news';
            $dataCategory = $category->getCategoryBySlug($arrayParam);
            $htmlCategory = $this->getDataCategory($dataNews['category_id'], $dataCategory);
            if($dataNews){
                $dataNews['status']         = ($dataNews['status'] == 1)? 'checked': '';
                $html .= '<tr id="edit-'.$dataNews['id'].'" class="edit-row">
                            <td colspan="6">
                                <div class="column-qiuck-edit">
                                    <div class="col-lg-12"><h2>Sửa nhanh</h2></div>
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input type="hidden" value="'.$dataNews['id'].'" id="id">
                                                    <label for="name">Tên sản phẩm</label><span class="validation">*</span>
                                                    <input type="text" data-toggle="tooltip" value="'.$dataNews['name'].'" data-placement="top" title="Tiêu đề tin" class="form-control" id="name" name="name">
                                                    <p class="error" id="error_name"></p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="slug">Slug</label><span class="validation">*</span>
                                                    <input type="text" data-toggle="tooltip" value="'.$dataNews['slug'].'" data-placement="top" title="Nhập định danh" class="form-control" id="slug" name="slug">
                                                    <p class="error" id="error_slug"></p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="hidden" name="status" value="0">
                                                            <input type="checkbox" name="status" id="status" value="1" '.$dataNews['status'].'>Đăng
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Danh mục cha</label>
                                                    <select id="basic-quick-edit" class="selectpicker show-tick form-control" data-live-search="true">
                                                        '.$htmlCategory.'
                                                     </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="sort">Sắp xếp</label>
                                                    <input type="number" min="0" value="'.$dataNews['sort'].'" class="form-control" id="sort" name="sort" placeholder="0" data-toggle="tooltip" data-placement="top" title="Nhập thử tự hiển thị">
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
            $validate = new ValidateNews($arrayParam, 'quick-edit');
            if($validate->isError() === true){
                $arrayParam['error'] = $validate->getMessagesError();
            }else{
                $arrayParam['post']['modified'] = time();
                $news = new News();
                $news->quickEdit($arrayParam);
            }
        }
        return new JsonModel($arrayParam);
    }
}
