<?php
namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Backend\Form\ValidateLanguage;
use Sky\Uploads\Upload;
use Sky\Uploads\Thumbs;
use Backend\Model\Language;
use Zend\Session\Container;

class LanguageController extends AbstractActionController
{
    public function indexAction()
    {
        $language = new Language();
        $dataLanguage = $language->getAll();
        $data = array();
        $data['list'] = $dataLanguage;
        return new ViewModel($data);
    }
    public function addAction()
    {
        $arrayParam = $this->params()->fromRoute();
        $request = $this->getRequest();
        if($request->isPost()){
            $arrayParam['post']	= array_merge_recursive(
                                    $request->getPost()->toArray(),
                                    $request->getFiles()->toArray()
                                );
            $validate = new ValidateLanguage($arrayParam, 'add');
			if($validate->isError() === true){
				$arrayParam['error'] = $validate->getMessagesError();
            }else{
                $data = $validate->getData();
                // upload image
                if(!empty($data['post']['icon']['name'])){
                    $uploadFile = new Upload();
                    $newName = $uploadFile->uploadImage($data['post']['icon']['name'], LANGUAGE_ICON);
                    $data['post']['icon'] = $newName;
                    // create thumb
                    $thumb = new Thumbs();
                    $thumb->createThumb(LANGUAGE_ICON ."/". $newName, array('1' => 40), array('1' => 20), array('1' => LANGUAGE_ICON.'/40x20/'), 1, '');
                    $language = new Language();
                    $language->addLanguage($data);
                    $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Tạo ngôn ngữ thành công.</div>');
                    if(isset($arrayParam['post']['save'])){
                        return $this->redirect()->toRoute('backend', array('controller' => 'language', 'action' => 'index'));
                    }else{
                        if(isset($arrayParam['post']['save-news'])){
                            return $this->redirect()->toRoute('backend', array('controller' => 'language', 'action' => 'add'));
                        }
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
        $language =  new Language();
        $arrayParam = $this->params()->fromRoute();
        $request = $this->getRequest();
        $arrayParam['id'] = $this->params()->fromRoute('id');
        $dataLanguage = $language->getLanguageById($arrayParam);
        if($request->isPost() && !empty($dataLanguage)){
            $arrayParam['post']	= array_merge_recursive(
                                    $request->getPost()->toArray(),
                                    $request->getFiles()->toArray()
                                );

                $validate = new ValidateLanguage($arrayParam, 'edit');
				if($validate->isError() === true){
					$arrayParam['error'] = $validate->getMessagesError();
				}else{
					$data = $validate->getData();
					// upload image
					if(!empty($data['post']['icon']['name'])){
						$uploadFile = new Upload();
						$newName = $uploadFile->uploadImage($data['post']['icon']['name'], LANGUAGE_ICON);
						$data['post']['icon'] = $newName;
						// create thumb
						$thumb = new Thumbs();
						$thumb->createThumb(LANGUAGE_ICON ."/". $newName, array('1' => 40), array('1' => 20), array('1' => LANGUAGE_ICON.'/40x20/'), 1, '');
						$thumb->removeImage(LANGUAGE_ICON ."/", array('1' => '40x20/', '2' => ''), $dataLanguage['icon'], 2);
					}else{
						$data['post']['icon'] = '';
					}
                    $language->addLanguage($data);
					$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Cập nhật thành công.</div>');
					return $this->redirect()->toRoute('backend', array('controller' => 'language','action' => 'index'));
				}
        }else{
            $arrayParam['post'] = $dataLanguage;
        }
        $data['arrayParam'] = $arrayParam;
        $data['id'] = $this->params()->fromRoute('id');
        return new ViewModel($data);
    }
    public function changestatusAction(){
        $request = $this->getRequest();
        $arrayParam	= array();
        
        if($request->isPost()){
            $language = new Language();
            $id         = $request->getPost('id');
            if(is_numeric($request->getPost('status')) && in_array($request->getPost('status'), array(0,1))){
                $status     = ($request->getPost('status') == 1)? '0' : '1';
                $arrayParam['id'] = $id;
                $arrayParam['status'] = $status;
                if(is_numeric($id) && is_numeric($status)){
                    $dataLanguage   = $language->getLanguageById($arrayParam);
                    if($dataLanguage){
                        $language->changeStatus($arrayParam);
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
            $arrayParam['id']         = $request->getPost('id');
            $language = new Language();
//            if($language->getLanguageById($arrayParam)){                
//                if($language->deleteLanguage($arrayParam)){
                    $this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Xóa ngôn ngữ thành công.</div>');
//                }
//            }
        }
        return new JsonModel($arrayParam);
    }
}
