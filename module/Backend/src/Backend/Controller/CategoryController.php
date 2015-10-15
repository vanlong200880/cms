<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Backend\Model\Category;
use Backend\Model\Taxonomy;
use Backend\Form\ValidateCategory;

class CategoryController extends AbstractActionController
{
    public function indexAction()
    {
        $request    = $this->getRequest();
        $url        = $this->getRequest()->getRequestUri();
        $data= array();
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
//        var_dump($categoryData);
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
        $data= array();
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
                $dataPost = $this->params()->fromPost();
                $arrayParam['post'] = $dataPost;
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
            $arrayParam['category_id']         = $request->getPost('category_id');
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
            $html = $this->getDataMenu($arrayParam['category_id'], $dataCategory);
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
}
