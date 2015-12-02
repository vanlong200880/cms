<?php

namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Frontend\Model\News;

class NewsController extends AbstractActionController
{
    public function indexAction()
    {
			$news = new News();
			$arrayParam = array(
				'slug' => 'news',
				'limit' => 2
			);
			$page       = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : null;
			// lay so trang
			$arrayParam['page'] = (int) $this->params()->fromRoute('page', 0);
			if($arrayParam['page'] != 0){
					$arrayParam['offset'] = ($arrayParam['page'] - 1) * $arrayParam['limit'];
			}else{
					$arrayParam['offset'] = 0;
			}
			// Count total items new.
			$countNews = $news->countAllNews($arrayParam);
			// khoi tao phan trang
			$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\Null($countNews));
			$paginator->setCurrentPageNumber($arrayParam['page']);
			$paginator->setItemCountPerPage($arrayParam['limit']);
        
			if(is_numeric($page) && $page > $paginator->count())
			{
				return $this->redirect()->toRoute('frontend',array('controller' => 'news', 'action' => 'index'));
			}
			$paginator->setPageRange(PAGE_RAND);
			$data['paginator']      = $paginator;
			$data['page']			= $page;
			var_dump($data);
//			$data['routeParam']		= $param;
//		$data['controller']		= $arrayParam['__CONTROLLER__'];
//		$data['action']			= $arrayParam['action'];
//        $data['paramSort']               = $paramSort;
//        $data['current_link'] = $url;     
//        $data['list'] = $categoryData;
				
			
			$news = new News();
			$dataNews = $news->getAllNews($arrayParam);
			var_dump($dataNews);
        return new ViewModel();
    }
    public function detailAction(){
    	return new ViewModel();
    }
}
