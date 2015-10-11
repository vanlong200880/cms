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

class CategoryController extends AbstractActionController
{
    public function indexAction()
    {
        $taxonomy = new Taxonomy();
        $data= array();
        
        return new ViewModel($data);
    }
    public function addAction()
    {
        $category = new Category();
        $arrayParam = array();
        $arrayParam['id'] = 1;
        $dataCategory = $category->getCategoryByTaxonomyId();
        $dataOption = array();
        
        if($dataCategory){
            foreach ($dataCategory as $value){
                $arrayParam['parent'] = $value['id'];
                if($category->countCategoryByParent($arrayParam)){
                    $dataOption[$value['name']] = $value['name'];
                }else{
                    $dataOption[] = $value['name'];
                }
            }
        }
        var_dump($dataOption);
        
        $taxonomy = new Taxonomy();
        $data= array();
        $data['taxonomy'] = $taxonomy->getAll();
        return new ViewModel($data);
    }
    public function loadcategoryAction(){
        $request = $this->getRequest();
        $arrayParam	= array(); 
        if($request->isPost()){
            
//            $category = new Category();
//            $arrayParam['id']         = $request->getPost('id');
//            $dataCategory = $category->getCategoryByTaxonomyId($arrayParam);
//            $dataOption = array();
//            if($dataCategory){
//                foreach ($dataCategory as $value){
//                    $arrayParam['parent'] = $value['id'];
//                    if($category->countCategoryByParent($arrayParam)){
//                        
//                    }else{
//                        $dataOption[] = $value['name'];
//                    }
//                }
//            }
//            if(is_numeric($request->getPost('status')) && in_array($request->getPost('status'), array(0,1))){
//                $status     = ($request->getPost('status') == 1)? '0' : '1';
//                $arrayParam['id'] = $id;
//                $arrayParam['status'] = $status;
//                if(is_numeric($id) && is_numeric($status)){
//                    $dataTaxonomy  = $taxonomy->getTaxonomyById($arrayParam);
//                    if($dataTaxonomy){
//                        $taxonomy->changeStatus($arrayParam);
//                        $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Thay đổi trạng thái thành công.</div>');
//                    }
//                }
//            }
        }
        return new JsonModel($arrayParam);
    }
}
