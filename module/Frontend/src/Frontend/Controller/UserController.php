<?php

namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Frontend\Form\ValidateRegister;
use Sky\System\EncriptPassword;
use Frontend\Model\User;
use Frontend\Model\Country;
use Zend\Session\Container;
use Frontend\Form\ValidateLogin;
use Frontend\Form\ValidateResetPassword;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

use Frontend\Form\ValidateChangePassword;

class UserController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    public function editAction(){
			$session = new Container('usermember');
			$userInfo = $session->auth;
			$arrayParam = array();
			if(!empty($userInfo)){
				$user = new User();
				$arrayParam['post']['id'] = $userInfo['userid'];
				$dataUser = $user->getUserById($arrayParam);
				$request = $this->getRequest();
				$arrayParam['data'] = $dataUser[0];
				if($request->isPost()){
					$arrayParam['post'] = $request->getPost()->toArray();
					$arrayParam['post']['id'] = $userInfo['userid'];
					$validate = new \Frontend\Form\ValidateUserEdit($arrayParam);
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
					if($validate->isError() === true){
						$arrayParam['error'] = $validate->getMessagesError();
					}else{
						//update user info
						if($arrayParam['post']['day'] != '' && $arrayParam['post']['month'] != '' && $arrayParam['post']['year'] != ''){
							$birthday = $arrayParam['post']['day'].'-'.$arrayParam['post']['month'].'-'.$arrayParam['post']['year'];
							$arrayParam['post']['birthday'] = strtotime($birthday);
						}else{
							$arrayParam['post']['birthday'] = '';
						}
						$arrayParam['post']['changed'] = time();
						if($user->userUpdateProfile($arrayParam)){
							$session->auth['fullname'] = $arrayParam['post']['fullname'];
							$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Update profile successfull.</div>');
							return $this->redirect()->refresh();
						}
					}
				}else{
					$arrayParam['post'] = $dataUser[0];
					if(!empty($dataUser[0]['birthday'])){
							$activeDay = date('d', $dataUser[0]['birthday']);
							$activeMonth = date('m', $dataUser[0]['birthday']);
							$activeYear = date('Y', $dataUser[0]['birthday']);
					}else{
							$activeDay = $activeMonth = $activeYear = '';
					}
				}
			}else
			{
				return $this->redirect()->toRoute('home', array('controller' => 'index', 'action' => 'index'));
			}
			$country = new Country();
			// get day month year
			$dataCountry = $country->getAllCountry();
			$arrayParam['arrayParam'] = $arrayParam;
			$date = new \Sky\System\ListDateFormat();
			$data['day'] = implode('', $date->listDay(1,31, $activeDay));
			$data['month'] = implode('', $date->listMonth(1,12, $activeMonth));
			$data['year'] = implode('', $date->listYear(date('Y')- 120, date('Y'), $activeYear));
				
			$arrayParam['country'] = $dataCountry;
			$arrayParam['date'] = $data;
    	return new ViewModel($arrayParam);
    }

    public function loginAction()
    {
			$arrayParam['message'] = '';
      $session = new Container('usermember');
      if(isset($session->auth['userid']) == true){
        $arrayParam['message'] = 'done';
      }else{
				$arrayParam = array();
				$request = $this->getRequest();
				if($request->isXmlHttpRequest()){
					$arrayParam['post'] = $request->getPost();
					$validate = new ValidateLogin($arrayParam);
					if($validate->isError() === true){
						$arrayParam['error'] = $validate->getMessagesError();
					}else{
						$user = new User();
						$salt = $user->getSalt($arrayParam['post']['username']);
						if(!empty($salt)){
							$arrayParam['post']['salt'] = $salt[0]['salt'];
							$encriptPassword = new EncriptPassword();
							$arrayParam['post'] = $encriptPassword->encriptPasswordLogin($arrayParam['post']);
							$userData = $user->userLogin($arrayParam);
							if(!empty($userData)){
								$userInfo = array(
									'userid'    => $userData[0]['id'],
									'username'  => $userData[0]['username'],
									'fullname'  => $userData[0]['fullname'],
									'email'     => $userData[0]['email'],
									'token'     => $userData[0]['token'],
									'role'      =>'Member'
								);
								$session->auth = $userInfo;
								$arrayParam['message'] = 'done';
								$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Login successfull.</div>');
							}else{
								$arrayParam['error']['username'] = 'Username or password incorrect.';
							}
						}
						else{
							$arrayParam['error']['username'] = 'Username or password incorrect.';
						}
					}
				}
			}
    	return new JsonModel($arrayParam);
    }
    public function logoutAction(){
			$session = new Container('usermember');
			$session->getManager()->getStorage()->clear('usermember');
			return $this->redirect()->toRoute('home', array('controller' => 'index', 'action' => 'index'));
    }
    public function forgotpasswordAction(){
      $request = $this->getRequest();
//      $arrayParam['error'] = array();
      if($request->isXmlHttpRequest()){
        $arrayParam = array();
        $arrayParam['post'] = $request->getPost();
        $validate = new ValidateResetPassword($arrayParam);
        if($validate->isError() === true){
          $arrayParam['error'] = $validate->getMessagesError();
        }else{
          // Create link reset password
          $user = new User();
          $arrayParam['post']['time_reset'] = time();
          $arrayParam['post']['token_reset'] = md5(uniqid(mt_rand(), true));
          if($user->generatorTokenReset($arrayParam)){
            // Send email
            $subject = 'demo reset password';
            $message = 'link reset';
            $config = $this->getServiceLocator()->get('Config');
            $arrayParam['cof'] = $config['mail']['transport'];
            $message = new Message();
            $bodyPart = new \Zend\Mime\Message();
            $bodyMessage = new \Zend\Mime\Part($body);
            $bodyMessage->type = 'text/html';
            $bodyPart->setParts(array($bodyMessage));

            $message->addTo('support@unimedia.vn')
                ->addFrom('vanlong200880@gmail.com')
                ->setSubject($subject)
                ->setBody($bodyPart);
            $message->setEncoding('UTF-8');

            $transport = new SmtpTransport();
//            $options   = new SmtpOptions(array(
//              'name'              => 'localhost',
//              'host'              => 'mail.unimedia.vn',
//              'connection_class'  => 'plain',
//              'connection_config' => array(
//                  'username' => 'demo@unimedia.vn',
//                  'password' => '123654789',
//              ),
//          ));
//            $transport->setOptions($options);
									
            $transport->setOptions($config['mail']['transport']);
            $transport->send($message);		
          }
          
        }
      }
    	return new JsonModel($arrayParam);
    }
    public function changepasswordAction(){
			$session = new Container('usermember');
			$userInfo = $session->auth;
			if($userInfo['userid']){
				$arrayParam = $dataPost = array();
				$request = $this->getRequest();
				if($request->isPost()){
					$arrayParam['post'] = $request->getPost()->toArray();
					$arrayParam['post']['username'] = $userInfo['username'];
					$arrayParam['post']['id'] = $userInfo['userid'];
					$arrayParam['post']['token'] = $userInfo['token'];
					$dataPost = $arrayParam;
					$validate = new ValidateChangePassword($arrayParam);
					$encriptPassword = new EncriptPassword();
					$user = new User();
					$salt = $user->getSalt($arrayParam['post']['username']);
					if(!empty($salt)){
							$arrayParam['post']['salt'] = $salt[0]['salt'];
							$arrayParam['post'] = $encriptPassword->encriptCheckChangePassword($arrayParam['post']);
							if($validate->isError() === true){
								$dataPost['error'] = $validate->getMessagesError();
							}else{
									if(!empty($user->checkUserChangePassword($arrayParam))){
										// change password
										$arrayParam['post'] = $encriptPassword->prepareDataChangePassword($arrayParam['post']);
										$user->changePassword($arrayParam);
										$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert">Change password successfull.</div>');
										return $this->redirect()->refresh();
									}else{
										$dataPost['post']['error'] = 'Username or password incorrect.';
									}
							}
					}
					else{
						$dataPost['post']['error'] = 'Username or password incorrect.';
					}
				}
			}
			$arrayParam['arrayParam'] = $dataPost;
    	return new ViewModel($arrayParam);
    }
    public function registerAction()
    {
      $arrayParam['message'] = '';
      $session = new Container('usermember');
      if(isset($session->auth['userid']) == true){
        $arrayParam['message'] = 'done';
      }else{
        $arrayParam = array();
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()){
          $arrayParam['post'] = $request->getPost();
          $validate = new ValidateRegister($arrayParam);
          if($validate->isError() === true){
            $arrayParam['error'] = $validate->getMessagesError();
          }else{
            $encriptPassword = new EncriptPassword();
            $user = new User();
            $sponsorId = $user->getUserIdSponsor($arrayParam);
            $arrayParam['post']['sponsor-id'] = $sponsorId['id'];
            $arrayParam['post'] = $encriptPassword->prepareData($arrayParam['post']);
            $arrayParam['post']['created'] = $arrayParam['post']['changed'] = time();
            $arrayParam['post']['active'] = 1;
            $arrayParam['post']['status'] = 1;
            $id = $user->userRegister($arrayParam);
            if($id){
              // login
              $userInfo = array(
                'userid'    => $id,
                'username'  => $arrayParam['post']['username'],
                'fullname'  => $arrayParam['post']['fullname'],
                'email'     => $arrayParam['post']['email'],
                'token'     => $arrayParam['post']['token'],
                'role'      =>'Member'
              );
              $session->auth = $userInfo;
              $arrayParam['message'] = 'done';
							$this->flashMessenger()->addMessage('<div class="alert alert-success" role="alert"> User register successfull.</div>');
            }
          }
        }
      }
      return new JsonModel($arrayParam);
    }    
}
