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
        $taxonomy = new Taxonomy();
        $data= array();
        
        return new ViewModel($data);
    }
    
    public function getDataMenu($function = '',$active = '', $data ,$parent = 0, $text = ''){
        $dataOption = array();
        
        foreach ($data as $key => $value){
//            if($function == 'edit'){
//                unset($data[6]);
//            }
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
                if($function == 'edit'){
                    unset($dataOption[6]);
                }
                $selected = ($val['id'] == $active)?'selected':'';
                $html .= '<option '.$selected.' value="'.$val['id'].'">'.$text.$val['name']. '</option>';
                $html .= $this->getDataMenu($function,$active, $data, $val['id'], $text.'--');
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
                var_dump($dataPost);
                $arrayParam['post'] = $dataPost;
                $arrayParam['post']['created'] = $arrayParam['post']['changed'] = '';
                $category->addCategory($arrayParam);
                $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Cập nhật danh mục thành công.</div>');
                //return $this->redirect()->toRoute('backend', array('controller' => 'category', 'action' => 'index'));
                
            }
        }else{
            $arrayParam['post'] = $dataCategory;
        }
        var_dump($arrayParam);
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
            $arrayParam['category_id']         = $request->getPost('category_id');
            $dataCategory = $category->getCategoryByTaxonomyId($arrayParam);
            
            $html = $this->getDataMenu($arrayParam['action'] ,$arrayParam['category_id'], $dataCategory, $arrayParam['id'] );
            $arrayParam['data'] = '<option value="0">-- Chọn --</option>'.$html;
        }
        return new JsonModel($arrayParam);
    }
}
