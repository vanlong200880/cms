<?php

namespace Backend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Backend\Form\ValidateUser;
use Backend\Form\ValidateUserChangePassword;
use Backend\Model\Role;
use Sky\System\EncriptPassword;
use Backend\Model\User;
use Sky\System\ListDateFormat;
use Sky\Uploads\Upload;
use Sky\Uploads\Thumbs;
use Zend\Session\Container;

class UserController extends AbstractActionController
{
    protected $_messagesError = NULL;
	public function indexAction()
	{
        $data = array();
        $arrayParam = $this->params()->fromRoute();
        $session = new Container($arrayParam['controller']);
        $arrayParam['limit'] = PAGING_LIMIT;
        // lay so trang
        $arrayParam['page'] = (int) $this->params()->fromRoute('page', 0);
        if($arrayParam['page'] != 0){
            $arrayParam['offset'] = ($arrayParam['page'] - 1) * $arrayParam['limit'];
        }else{
            $arrayParam['offset'] = 0;
        }
        $user = new User();
        $userData = $user->listUser($arrayParam);
        // dem tong so user
        $countUser = $user->countUser($arrayParam);
        
        // khoi tao phan trang
//        $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\Null($countUser[0]['count']));
//        var_dump($arrayParam['page']);
//         die;
//        $paginator->setCurrentPageNumber($arrayParam['page']);
//        $paginator->setItemCountPerPage($arrayParam['limit']);
//        $paginator->setPageRange(5);
        $data['arrayParam']     = $arrayParam;
        $data['list']           = $userData;
        $data['title']          = "Danh sách user";
//        $data['paginator']      = $paginator;
//        var_dump($data);
		return new ViewModel($data);
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
                $arrayParam['post']['password']     = '';
                $arrayParam['post']['salt']         = '';
                $arrayParam['post']['token']        = '';
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
                $arrayParam['post']['password']     = '';
                $arrayParam['post']['salt']         = '';
                $arrayParam['post']['token']        = '';
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
                
            }
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
        $arrayParam['id'] = $id;
        $data['arrayParam'] = $arrayParam;
		if($role){
			$data['role'] = $role;
		}
        
		return new ViewModel($data);
	}

	public function changestatusAction(){
		$request = $this->getRequest();
        $arrayParam	= array();
        if($request->isPost()){
            $user       = new User();
            $id         = $request->getPost('id');
            $status     = $request->getPost('status');
            $arrayParam['id'] = $id;
            $arrayParam['status'] = $status;
            if(is_numeric($id) && is_numeric($status)){
                $userInfo   = $user->getUserById($id);
                if($userInfo){
                    $user->updateStatus($arrayParam);
                    $arrayParam['message'] = "<span class='seccess'>Cập nhật thành công.</span>";
                }
            }

        }
        return new JsonModel($arrayParam);
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
        $request = $this->getRequest();
        $arrayParam	= array();
        if($request->isPost()){
            $user       = new User();
            $id         = $request->getPost('id');
            $arrayParam['id'] = $id;
            $userInfo   = $user->getUserById($id);
            if($userInfo){
                $file = new \Sky\Uploads\Thumbs();
                $file->removeImage(USER_ICON ."/", array('1' => '80x80/', '2' => ''), $userInfo['avartar'], 2);
                $user->deleteUser($arrayParam);
                $arrayParam['message'] = "<span class='seccess'>Xóa user thành công.</span>";
            }
        }
        return new JsonModel($arrayParam);
	}

    public function changepasswordAction()
    {
        $session = new Container();
        $data = array();
        $arrayParam = $this->params()->fromRoute();
        $request = $this->getRequest();
        if($request->isPost() === true){
            $user       = new User();
            $userInfo   = $user->getUserChangePassword($arrayParam['id']);
            if($userInfo){
                $arrayParam['post']             = $request->getPost()->toArray();
                $encriptPassword                = new EncriptPassword();
                $data['post']                   = $arrayParam['post'];
                $data['post']['passwordold']    = $encriptPassword->encriptChangePassword($arrayParam['post']['passwordold'], $userInfo['salt']);
                $validate                       = new ValidateUserChangePassword($data, 'changepassword');
                if($validate->isError() === true){
                    $arrayParam['error'] = $validate->getMessagesError();
                }else{
                    $user = new User();
                    $data['post']['token']      = '';
                    $data['post']['salt']       = '';
                    $encriptPassword            = new EncriptPassword();
                    $data['post']               = $encriptPassword->prepareData($data['post']);
                    $data['post']['id']         = $arrayParam['id'];
                    if($user->changepassword($data)){
                        $this->flashMessenger()->addMessage(array('success' => 'Đổi mật khẩu thành công.'));
                        return $this->redirect()->refresh();
                    }else{
                        $this->flashMessenger()->addMessage(array('success' => 'Đổi mật khẩu thất bại. Vui lòng thử lại.'));
                    }
                }
            }
			
        }
        $data['arrayParam'] = $arrayParam;
        return new ViewModel($data);
        
    }
    public function blockAction(){
		//sdfsdf
	}
}