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
//        $category = new Category();
//        $arrayParam = array();
//        $arrayParam['id'] = 1;
//        $data = $category->getCategoryByTaxonomyId($arrayParam);
////        
//        var_dump($this->getDataMenu(1,$data));
////       
////        
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
                var_dump($arrayParam);
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
        }else{
            
        }
        $data['arrayParam'] = $arrayParam;
        return new ViewModel($data);
    }
    public function loadcategoryAction(){
        $request = $this->getRequest();
        $arrayParam	= array(); 
        if($request->isPost()){
            
            $category = new Category();
            $arrayParam['id']         = $request->getPost('id');
            $arrayParam['category_id']         = $request->getPost('category_id');
            $dataCategory = $category->getCategoryByTaxonomyId($arrayParam);
            $html = $this->getDataMenu($arrayParam['category_id'], $dataCategory, $arrayParam['id'] );
            $arrayParam['data'] = '<option value="0">-- Chọn --</option>'.$html;
        }
        return new JsonModel($arrayParam);
    }
}
