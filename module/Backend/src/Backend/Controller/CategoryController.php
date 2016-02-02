<?php
namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Backend\Model\Category;
use Backend\Model\Taxonomy;
use Backend\Model\Product;
use Backend\Model\Comment;
use Backend\Model\Image;
use Sky\Uploads\Upload;
use Backend\Model\News;
use Sky\Uploads\Thumbs;
use Backend\Model\OrderDetail;
use Backend\Form\ValidateCategory;

class CategoryController extends AbstractActionController
{
  public function indexAction()
  {
    $category = new Category();
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
              if($category->updateStatus($arrayParam)){
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
                      $category->updateSortById($dataSort);
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
    $category = new Category();
    $categoryData = $category->getAllCategory($arrayParam);
    $arrParam = array(
      'controller'	=> $arrayParam['__CONTROLLER__'],
      'action'		=> $arrayParam['action'],
            'page'          => (!empty($page))? '/page/'.$page: '',
            'sort'          => (!empty($sort))? '/sort/'.$sort: '',
            'order'         => (!empty($order))? '/order/'.$order: '',
            'txtSearch'     => (!empty($search))? '/txtSearch/'.$search: '',
    );
    // dem tong so user
    $countCategory = $category->countAllCategory($arrayParam);

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
        $data['title']          = "Danh mục";
        $data['param']          = $arrParam;
        $data['paginator']      = $paginator;
        $data['page']			= $page;
        $data['routeParam']		= $param;
        $data['controller']		= $arrayParam['__CONTROLLER__'];
        $data['action']			= $arrayParam['action'];
        $data['paramSort']               = $paramSort;
        $data['current_link'] = $url;     
        $data['list'] = $categoryData;
        return new ViewModel($data);
    }
    
    public function getDataMenuListCategory($data ,$parent = 0, $text = ''){
//        var_dump($data); die;
        $server_url = $this->getRequest()->getUri();
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
                $html .= '<tr id="id-taxonomy-'. $val['id'].'">';
                    $html .= '<td><input type="checkbox" class="form-checkbox check-all"></td>';
                    $html .= '<td>'.$val['id'].'</td>';
                    $html .= '<td class="column-title column_name">';
                        $html .= '<strong class="key-title">'.$text.$val['name'].'</strong>';
                        $html .= '<div class="row-action">';
                            $html .= '<span class="edit">';
                                $html .= '<a class="icon-edit" href="'.$server_url.'/edit/'.$val['id'].'" title="Edit"><i class="fa fa-pencil-square-o"></i></a> ';
                            $html .= '</span>';
                            $html .= '<span class="delete">';
                                $html .= '<a class="icon-delete" data-id="'.$val['id'].'" data-toggle="modal" data-target=".modal-delete-taxonomy" title="Delete"><i class="fa fa-times"></i></a> ';
                            $html .= '</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="column_descripton">'.$val['description'].'</td>';
                    $html .= '<td class="column_weight"><input type="number" min="0" value="'.$val['sort'].'" name="sort[]" class="update_weight form-control"></td>';
                    $html .= '<td class="column_quantity">200</td>';
                    $html .= '<td class="column_status status">';
                    if($val['status'] == 1){
                        $html .= '<a data-status='.json_encode(array('id' => $val['id'], 'status' => 1)).' href="javascript:void(0)" title="Hiển thị" class="active fa fa-check-circle"></a>';
                    }else
                    {
                        $html .= '<a data-status='.json_encode(array('id' => $val['id'], 'status' => 0)).' title="Không hiển thị" class="unactive fa fa-minus-circle"></a>';
                    }
                    $html .= '</td>';
                $html .= $this->getDataMenuListCategory($data, $val['id'], $text.'--');
                $html .= '</tr>';
            }
        }
        return $html;
    }
    public function getDataMenu($active = '', $data ,$parent = 0, $text = ''){
        $dataOption = array();
        
        foreach ($data as $key => $value){
            if($value['parent'] == $parent){
                $dataOption[] = $value;
                unset($data[$key]);
            }
        }
//        if($dataOption){
//            echo '<ul>';
//            foreach ($dataOption as $key => $val){
//                echo '<li>'.$text.$val['name'];
//                $this->getDataMenu($data, $val['id'], $text.'--');
//                echo '</li>';
//            }
//            echo '</ul>';
//        }
        $html = '';
        if($dataOption){
            foreach ($dataOption as $key => $val){
                $selected = ($val['id'] == $active)?'selected':'';
                $html .= '<option '.$selected.' value="'.$val['id'].'">'.$text.$val['name']. '</option>';
                $html .= $this->getDataMenu($active, $data, $val['id'], $text.'--');
            }
        }
        return $html;
    }

    public function addAction()
    {
			$taxonomy = new Taxonomy();
			$data	= array();
			$data['taxonomyList'] = $taxonomy->getAll();
			$request = $this->getRequest();
			$arrayParam = $this->params()->fromRoute();
			if($request->isPost() == true){
					$category = new Category();
					$arrayParam['post'] = $this->params()->fromPost();
					$validate = new ValidateCategory($arrayParam, 'add');
					if($validate->isError() === true){
					$arrayParam['error'] = $validate->getMessagesError();
					}else{
							$dataPost = $this->params()->fromPost();
							$arrayParam['post'] = $dataPost;
							$filter = new \Sky\Filter\SeoUrl();
							if(empty($arrayParam['post']['slug']))
									$arrayParam['post']['slug'] = $filter->filter($arrayParam['post']['name']);
								else
									$arrayParam['post']['slug'] = $filter->filter($arrayParam['post']['slug']);
							$arrayParam['post']['created'] = $arrayParam['post']['changed'] = '';
							$category->addCategory($arrayParam);
							$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Tạo danh mục thành công.</div>');
							if(isset($arrayParam['post']['save'])){
									return $this->redirect()->toRoute('backend', array('controller' => 'category', 'action' => 'index'));
							}else{
									if(isset($arrayParam['post']['save-news'])){
											return $this->redirect()->toRoute('backend', array('controller' => 'category', 'action' => 'add'));
									}
							}
					}
			}
			$data['arrayParam'] = $arrayParam;
			return new ViewModel($data);
    }
    
    public function editAction(){
        $category = new Category();
        $taxonomy = new Taxonomy();
        $data= array();
        $arrayParam['id'] = $this->params()->fromRoute('id');
        $dataCategory = $category->getCategoryById($arrayParam);
        $request = $this->getRequest();
        if($request->isPost()== true){
            $arrayParam['post'] = $this->params()->fromPost();
            $validate = new ValidateCategory($arrayParam, 'edit');
            if($validate->isError() === true){
						$arrayParam['error'] = $validate->getMessagesError();
            }else{
                $filter = new \Sky\Filter\SeoUrl();
                $arrayParam['post']['slug'] = $filter->filter($arrayParam['post']['slug']);
								$arrayParam['post']['created'] = $arrayParam['post']['changed'] = '';
								$category->addCategory($arrayParam);
								$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Cập nhật danh mục thành công.</div>');
								return $this->redirect()->toRoute('backend', array('controller' => 'category', 'action' => 'index'));
            }
        }else{
            $arrayParam['post'] = $dataCategory;
        }
        $data['taxonomyList'] = $taxonomy->getAll();
        $data['arrayParam'] = $arrayParam;
        return new ViewModel($data);
    }

    public function loadcategoryAction(){
        $request = $this->getRequest();
        $arrayParam	= array();
        if($request->isPost()){
            $category = new Category();
            $arrayParam['id']         = $request->getPost('id');
            $arrayParam['action'] = $request->getPost('action');
            $arrayParam['parent'] = $request->getPost('parent');
            $arrayParam['parent_id']         = $request->getPost('parent_id');
            $dataCategory = $category->getCategoryByTaxonomyId($arrayParam);
            if($arrayParam['action'] == 'edit'){
                foreach ($dataCategory as $key => $value){
                    if($value['id'] == $arrayParam['parent'])
                    {
//                        $dataCategory[$key]['flag'] = true;
                        unset($dataCategory[$key]);
//                    }else{
//                        $dataCategory[$key]['flag'] = false;
                    }
                }
            }
            $html = $this->getDataMenu($arrayParam['parent_id'], $dataCategory);
            $arrayParam['data'] = '<option value="0">-- Chọn --</option>'.$html;
        }
        return new JsonModel($arrayParam);
    }
    public function changestatusAction(){
        $request = $this->getRequest();
        $arrayParam	= array(); 
        if($request->isPost()){
            $category = new Category();
            $id         = $request->getPost('id');
            if(is_numeric($request->getPost('status')) && in_array($request->getPost('status'), array(0,1))){
                $status     = ($request->getPost('status') == 1)? '0' : '1';
                $arrayParam['id'] = $id;
                $arrayParam['status'] = $status;
                if(is_numeric($id) && is_numeric($status)){
                    $dataCategory  = $category->getCategoryById($arrayParam);
                    if($dataCategory){
                        $category->changeStatus($arrayParam);
                        $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Thay đổi trạng thái thành công.</div>');
                    }
                }
            }
        }
        return new JsonModel($arrayParam);
    }
    
    public function deletecategoryAction(){
        $arrayParam = array();
        $category = new Category();
        $request = $this->getRequest();
        if($request->isPost() == true){
            $arrayParam['id'] = $this->params()->fromPost('category_id');
            $dataCategory = $category->getCategoryById($arrayParam);
            if($dataCategory){
                // get taxonomy slug
                $taxonomy = new Taxonomy();
                $arrParam['id'] = $dataCategory['taxonomy_id'];
                $dataTaxonomy = $taxonomy->getTaxonomyById($arrParam);
                $product = new Product();
                if($dataTaxonomy){
                    switch ($dataTaxonomy['slug']){
                    case 'products':
                        // list category by taxonomy id
                        $arrayParamTaxonomyId['id'] = $dataTaxonomy['id'];
                        $dataListCategory = $category->getCategoryByTaxonomyId($arrayParamTaxonomyId);
                        $dataListCategoryByParent = $this->getDataCategoryByParent($dataListCategory, $arrayParam['id']);
                        array_unshift($dataListCategoryByParent, $arrayParam['id']);                        
                        // get list product id 
                        if($dataListCategoryByParent){
                            $arrayParam['list'] = $dataListCategoryByParent;
                            $dataListProduct = $product->getAllProductByArrayCategoryId($arrayParam);
                            if($dataListProduct){
                                $dataListProductTemp = array();
                                foreach ($dataListProduct as $value){
                                    array_push($dataListProductTemp, $value['id']);
                                }
                                $arrayParam['listIdProduct'] = $dataListProductTemp;
                                $orderDetail = new OrderDetail();
                                $dataListIdProductInOrderDetail = $orderDetail->countAllProductByArrayCategoryId($arrayParam);
                                $arrayParam['a'] = $dataListIdProductInOrderDetail;
                                if($dataListIdProductInOrderDetail > 0){
                                    // Not permission delete category
                                    $arrayParam['message'] = 'Bạn không có quyền xóa danh mục này.';
                                    $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Bạn không có quyền xóa danh mục này.</div>');
                                }else{
                                    $comment = new Comment();
                                    $image = new Image();
                                    //delete product by id
                                    foreach ($dataListProductTemp as $val){
                                        // delete comment by product id
                                        $arrayParam['comment_type'] = 'products';
                                        $arrayParam['product_id'] = $val;
                                        $comment->deleteCommentByProductId($arrayParam);
                                        // delete image by product id
                                        $arrayParam['type'] = 'products';
                                        $dataImage = $image->getImageByProductId($arrayParam);
                                        if($dataImage){
                                            $thumb = new Thumbs();
                                            foreach ($dataImage as $value){
                                                $thumb->removeImage(PRODUCT_ICON ."/", array('1' => '40x80/', '2' => '160x180/', '3' => '260x300/', '4' => ''), $value['name'], 4);
                                            }
                                        }
                                        // delete product by id
                                        $product->deleteProductById($val);
                                    }
                                    // delete list category
                                    foreach ($dataListCategoryByParent as $v){
                                        $category->deleteCategoryById($v);
                                    }
                                    $arrayParam['message'] = 'Xóa thành công.';
                                    $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa thành công.</div>');
                                }
                            }else{
                                // delete list category
                                foreach ($dataListCategoryByParent as $v){
                                    $category->deleteCategoryById($v);
                                }
                                $arrayParam['message'] = 'Xóa thành công.';
                                $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa thành công.</div>');
                            }                            
                        }
                        break;
                    case 'news':
                        // list category by taxonomy id
                        $news = new News();
                        $arrayParamTaxonomyId['id'] = $dataTaxonomy['id'];
                        $dataListCategory = $category->getCategoryByTaxonomyId($arrayParamTaxonomyId);
                        $dataListCategoryByParent = $this->getDataCategoryByParent($dataListCategory, $arrayParam['id']);
                        array_unshift($dataListCategoryByParent, $arrayParam['id']);
                        if($dataListCategoryByParent){
                            $arrayParam['list'] = $dataListCategoryByParent;
                            $dataListNews = $news->getAllNewsByArrayCategoryId($arrayParam);
                            if($dataListNews){
                                $comment = new Comment();
                                foreach ($dataListNews as $value){
                                    $arrayParam['comment_type'] = 'news';
                                    $arrayParam['product_id'] = $value;
                                    $comment->deleteCommentByProductId($arrayParam);
                                    $arrayParam['id']   = $value;
                                    $newsInfo           = $news->getNewById($arrayParam);
                                    if($newsInfo){
                                        $file           = new Thumbs();
                                        $file->removeImage(NEWS_ICON ."/", array('1' => '150x150/', '2' => ''), $newsInfo['image'], 2);
                                        $news->deleteNews($newsInfo['id']);
                                    }
                                }
                                foreach ($dataListCategoryByParent as $v){
                                        $category->deleteCategoryById($v);
                                    }
                                $arrayParam['message'] = 'Xóa thành công.';
                                $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa thành công.</div>');
                            }else{
                                // delete list category
                                foreach ($dataListCategoryByParent as $v){
                                    $category->deleteCategoryById($v);
                                }
                                $arrayParam['message'] = 'Xóa thành công.';
                                $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa thành công.</div>');
                            }                            
                        }
                        break;
                    }
                }
            }
        }
        return new JsonModel($arrayParam);
    }
    
    public function getDataCategoryByParent( $data ,$parent = 0){
        $dataOption = array();
        
        foreach ($data as $key => $value){
            if($value['parent'] == $parent){
                $dataOption[] = $value;
                unset($data[$key]);
            }
        }
        $arr = array();
        if($dataOption){
            foreach ($dataOption as $key => $val){
                array_push($arr, $val['id']);
                $arr = array_merge($arr, $this->getDataCategoryByParent($data, $val['id']));
            }
        }
        return $arr;
    }
    
    public function quickeditAction(){
        $arrayParam = array();
        $request = $this->getRequest();
        $html = '';
        if($request->isPost() == true){
            $category = new Category();
            // get list category by taxonomy id
            $arrayParam['id']         = $request->getPost('taxonomy_id');
            $arrayParam['parent'] = $request->getPost('id');
            $arrayParam['category_id']         = $request->getPost('id');
            $dataListCategory = $category->getCategoryByTaxonomyId($arrayParam);
            foreach ($dataListCategory as $key => $value){
                if($value['id'] == $arrayParam['parent'])
                {
                    unset($dataListCategory[$key]);
                }
            }
            $arrayParam['id'] = $request->getPost('id');
            $dataCategory = $category->getCategoryById($arrayParam);
            $htmlCategory = $this->getDataMenu($arrayParam['category_id'], $dataListCategory);
            if($dataCategory){
                $dataCategory['status']         = ($dataCategory['status'] == 1)? 'checked': '';
                $html .= '<tr id="edit-'.$dataCategory['id'].'" class="edit-row">
                            <td colspan="7">
                                <div class="column-qiuck-edit">
                                    <div class="col-lg-12"><h2>Sửa nhanh</h2></div>
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input type="hidden" value="'.$dataCategory['id'].'" id="id">
                                                    <label for="name">Tên danh mục</label><span class="validation">*</span>
                                                    <input type="text" data-toggle="tooltip" value="'.$dataCategory['name'].'" data-placement="top" title="Tên danh mục" class="form-control" id="name" name="name">
                                                    <p class="error" id="error_name"></p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="slug">Slug</label><span class="validation">*</span>
                                                    <input type="text" data-toggle="tooltip" value="'.$dataCategory['slug'].'" data-placement="top" title="Nhập định danh" class="form-control" id="slug" name="slug">
                                                    <p class="error" id="error_slug"></p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="hidden" name="status" value="0">
                                                            <input type="checkbox" name="status" id="status" value="1" '.$dataCategory['status'].'>Đăng
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
                                                    <input type="number" min="0" value="'.$dataCategory['sort'].'" class="form-control" id="sort" name="sort" placeholder="0" data-toggle="tooltip" data-placement="top" title="Nhập thử tự hiển thị">
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
    public function quickupdateAction(){
        $arrayParam = array();
        $request = $this->getRequest();
        if($request->isPost() == true){
            $arrayParam['post'] = $this->params()->fromPost();
            $arrayParam['id'] = $this->params()->fromPost('id');
            $validate = new ValidateCategory($arrayParam, 'category_ajax');
            if($validate->isError() === true){
                $arrayParam['error'] = $validate->getMessagesError();
            }else{
              $filter = new \Sky\Filter\SeoUrl();
              $arrayParam['post']['slug'] = $filter->filter($arrayParam['post']['slug']);
                $arrayParam['post']['changed'] = time();
                $category = new Category();
                $category->updateQuickEdit($arrayParam);
            }
        }
        return new JsonModel($arrayParam);
    }
}
