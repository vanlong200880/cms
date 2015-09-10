<?php

namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Backend\Form\ValidateUser;
use Backend\Model\Role;
use Sky\System\EncriptPassword;
use Backend\Model\User;
use Sky\System\ListDateFormat;
use Sky\Uploads\Upload;
use Sky\Uploads\Thumbs;

class UserController extends AbstractActionController
{
    protected $_messagesError = NULL;
	public function indexAction()
	{
		return new ViewModel();
	}

	public function addAction(){
		$data = array();
		$arrayParam = $this->params()->fromRoute();
		$request = $this->getRequest();
        
		// get all role
		$listRole = new Role();
		$role = $listRole->getAllRole();
		if($request->isPost() === true){
			$arrayParam['post']	= array_merge_recursive(
                                    $request->getPost()->toArray(),
                                    $request->getFiles()->toArray()
                                );
            if(isset($arrayParam['post']['sex'])){
                $arrayParam['post']['sex'] = (int)($arrayParam['post']['sex']);
            }
            if(isset($arrayParam['post']['active'])){
                $arrayParam['post']['active'] = (int)($arrayParam['post']['active']);
            }
            if(isset($arrayParam['post']['role'])){
                $arrayParam['post']['role'] = (int)($arrayParam['post']['role']);
            }
			$validate = new ValidateUser($arrayParam, 'add');
			if($validate->isError() === true){
				$arrayParam['error'] = $validate->getMessagesError();
			}else{
                
				$data = $validate->getData();
                // upload image
                if(!empty($data['post']['avartar']['name'])){
                    $uploadFile = new Upload();
                    $newName = $uploadFile->uploadImage($data['post']['avartar']['name'], USER_ICON);
//                    var_dump($newName); die;
                    $data['post']['avartar'] = $newName;
                    // create thumb
                    $thumb = new Thumbs();
                    $thumb->createThumb(USER_ICON ."/". $newName, array('1' => 80), array('1' => 80), array('1' => USER_ICON.'/80x80/'), 1, '');
                }else{
                    $data['post']['avartar'] = '';
                }
                
                $data['post']['created']    = $data['post']['changed'] = time();
                $data['post']['status']     = 1;
                $data['post']['social']     = 0;
                $data['post']['token']      = '';
                $data['post']['salt']       = '';
                $data['post']['id']         = '';
                $encriptPassword = new EncriptPassword();
                $data['post'] = $encriptPassword->prepareData($data['post']);
                if(isset($data['post']['day']) && isset($data['post']['month']) && isset($data['post']['year'])){
                    $birthday = $data['post']['day'].'-'.$data['post']['month'].'-'.$data['post']['year'];
                    $data['post']['birthday'] = strtotime($birthday);
                }else{
                    $data['post']['birthday'] = '';
                }
                $user = new User();
                $user->addUser($data);
                $this->flashMessenger()->addMessage(array('success' => 'Tạo thành viên thành công.'));
                return $this->redirect()->toRoute('backend', array('controller' => 'user','action' => 'index'));
			}
		}
        
		$data['arrayParam'] = $arrayParam;
		if($role){
			$data['role'] = $role;
		}
        $activeDay = $activeMonth = $activeYear = '';
        if(isset($arrayParam['post']['day'])){
            $activeDay = $arrayParam['post']['day'];
        }
        if(isset($arrayParam['post']['month'])){
            $activeMonth = $arrayParam['post']['month'];
        }
        if(isset($arrayParam['post']['year'])){
            $activeYear = $arrayParam['post']['year'];
        }
        // get ngay thang nam sinh
        $date = new \Sky\System\ListDateFormat();
        $data['day'] = implode('', $date->listDay(1,31, $activeDay));
        $data['month'] = implode('', $date->listMonth(1,12, $activeMonth));
        $data['year'] = implode('', $date->listYear(date('Y')- 120, date('Y'), $activeYear));
		$data['title'] = 'Đăng ký thành viên mới';
		return new ViewModel($data);
	}

	public function editAction(){
        $data = array();
		$arrayParam = $this->params()->fromRoute();
		$request = $this->getRequest();
        $id = $this->params()->fromRoute('id');
        $user = new User();
        $userInfo = $user->getUserById($id);
        if($request->isPost() === true && !empty($userInfo)){
            $arrayParam['post']	= array_merge_recursive(
                                    $request->getPost()->toArray(),
                                    $request->getFiles()->toArray()
                                );
            
            $activeDay = $activeMonth = $activeYear = '';
            if(isset($arrayParam['post']['day'])){
                $activeDay = $arrayParam['post']['day'];
            }
            if(isset($arrayParam['post']['month'])){
                $activeMonth = $arrayParam['post']['month'];
            }
            if(isset($arrayParam['post']['year'])){
                $activeYear = $arrayParam['post']['year'];
            }
            
            if(isset($arrayParam['post']['sex'])){
                $arrayParam['post']['sex'] = (int)($arrayParam['post']['sex']);
            }
            if(isset($arrayParam['post']['active'])){
                $arrayParam['post']['active'] = (int)($arrayParam['post']['active']);
            }
            
            if($userInfo['email'] !== $arrayParam['post']['email']){
                $arrayParam['post']['password'] = '';
                $arrayParam['post']['salt'] = '';
                $arrayParam['post']['token'] = '';
                $validate = new ValidateUser($arrayParam, 'edit');
                if($validate->isError() === true){
                    $arrayParam['error'] = $validate->getMessagesError();
                }else{
                    $data = $validate->getData();
                    $data['post']['created']    = $data['post']['changed'] = time();
                    $data['post']['status']     = 1;
                    $data['post']['social']     = 0;
                    $data['post']['id']         = '';
                    if(isset($data['post']['day']) && isset($data['post']['month']) && isset($data['post']['year'])){
                        $birthday = $data['post']['day'].'-'.$data['post']['month'].'-'.$data['post']['year'];
                        $data['post']['birthday'] = strtotime($birthday);
                    }else{
                        $data['post']['birthday'] = '';
                    }
                    // upload image
                    if(!empty($data['post']['avartar']['name'])){
                        $uploadFile = new Upload();
                        $newName = $uploadFile->uploadImage($data['post']['avartar']['name'], USER_ICON);
                        $data['post']['avartar'] = $newName;
                        // create thumb
                        $thumb = new Thumbs();
                        $thumb->createThumb(USER_ICON ."/". $newName, array('1' => 80), array('1' => 80), array('1' => USER_ICON.'/80x80/'), 1, '');
                        $thumb->removeImage(USER_ICON ."/", array('1' => '80x80/', '2' => ''), $userInfo['avartar'], 2);
                    }else{
                        $data['post']['avartar'] = '';
                    }
                    $user = new User();
                    $user->addUser($data);
                    $this->flashMessenger()->addMessage(array('success' => 'Cập nhật thành công'));
                    return $this->redirect()->toRoute('backend', array('controller' => 'user','action' => 'index'));
                }
            }else{
                $arrayParam['post']['password'] = '';
                $arrayParam['post']['salt'] = '';
                $arrayParam['post']['token'] = '';
                $validate = new ValidateUser($arrayParam, 'edit');
                var_dump($arrayParam);
				if($validate->isError() === true){
					$arrayParam['error'] = $validate->getMessagesError();
				}else{
					$data = $validate->getData();

					$data['post']['created']    = $data['post']['changed'] = time();
					$data['post']['status']     = 1;
					$data['post']['social']     = 0;
					$data['post']['id']         = '';
					if(isset($data['post']['day']) && isset($data['post']['month']) && isset($data['post']['year'])){
						$birthday = $data['post']['day'].'-'.$data['post']['month'].'-'.$data['post']['year'];
						$data['post']['birthday'] = strtotime($birthday);
					}else{
						$data['post']['birthday'] = '';
					}
					// upload image
					if(!empty($data['post']['avartar']['name'])){
						$uploadFile = new Upload();
						$newName = $uploadFile->uploadImage($data['post']['avartar']['name'], USER_ICON);
						$data['post']['avartar'] = $newName;
						// create thumb
						$thumb = new Thumbs();
						$thumb->createThumb(USER_ICON ."/". $newName, array('1' => 80), array('1' => 80), array('1' => USER_ICON.'/80x80/'), 1, '');
						$thumb->removeImage(USER_ICON ."/", array('1' => '80x80/', '2' => ''), $userInfo['avartar'], 2);
					}else{
						$data['post']['avartar'] = '';
					}
					$user = new User();
					$user->addUser($data);
					$this->flashMessenger()->addMessage(array('success' => 'Cập nhật thành công'));
					return $this->redirect()->toRoute('backend', array('controller' => 'user','action' => 'index'));
				}
                
            }
//            var_dump($arrayParam);
        }else
        {
            if(!empty($userInfo['birthday'])){
                $activeDay = date('d', $userInfo['birthday']);
                $activeMonth = date('m', $userInfo['birthday']);
                $activeYear = date('Y', $userInfo['birthday']);
            }else{
                $activeDay = $activeMonth = $activeYear = '';
            }
            
            $arrayParam['post'] = $userInfo;
            // get user role 
            $arrayParam['post']['role'] = (int)$userInfo['role_rid'];
        }
        
        
        $arrayParam['post']['sex'] = (int)$userInfo['sex'];
        $date = new \Sky\System\ListDateFormat();
        $data['day'] = implode('', $date->listDay(1,31, $activeDay));
        $data['month'] = implode('', $date->listMonth(1,12, $activeMonth));
        $data['year'] = implode('', $date->listYear(date('Y')- 120, date('Y'), $activeYear));
        // get all role
		$listRole = new Role();
		$role = $listRole->getAllRole();
//        var_dump($arrayParam);
        $arrayParam['id'] = $id;
        $data['arrayParam'] = $arrayParam;
		if($role){
			$data['role'] = $role;
		}
        
		return new ViewModel($data);
	}

	public function changeStatusAction(){
		return new ViewModel();
	}

    public function deleteavartarAction(){
        $request = $this->getRequest();
        $arrayParam	= array();
        if($request->isPost()){
            $user       = new User();
            $id         = $request->getPost('id');
            $userInfo   = $user->getUserById($id);
            if($userInfo){
                $file = new \Sky\Uploads\Thumbs();
                $file->removeImage(USER_ICON ."/", array('1' => '80x80/', '2' => ''), $userInfo['avartar'], 2);
                $user->deleteAvartar($arrayParam);
                $arrayParam['message'] = "<span class='seccess'>Xóa avartar thành công.</span>";
            }
        }
        return new JsonModel($arrayParam);
    }
    public function deleteAction(){
		return new ViewModel();
	}

	public function blockAction(){
		
	}
}