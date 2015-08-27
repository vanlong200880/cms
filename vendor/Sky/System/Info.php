<?php
namespace Sky\System;
use Zend\Session\Container;
class Info{
	protected $_session;
	
	// function contruct
	public function __construct() {
		$this->_session = new Container(APPLICATION_KEY);
		$this->setMemberInfo($this->_session->auth);
		$this->setGroupInfo($this->_session->auth);
		$this->setAcl($this->_session->auth);
	}
	
	// clear information login
	public function destroyInfo(){
		$this->_session->getManager()->getStorage()->clear();
	}
	
	//thiet lap thong tin ca nhan nguoi dang nhap
	public function setMemberInfo($infoAuth)
	{
		$this->_session->member = $infoAuth;
	}
	
	// thiet lap thong tin nhom cua nguoi dang nhap
	public function setGroupInfo($infoAuth){
		$id = $infoAuth->group_id;
		$result = new \Backend\Model\Group();
		$result = $result->auth(array('id' => $id));
		$this->_session->group = $result;
	}
	
	// lay thong tin ca nhan cua nguoi dang nhap
	public function getMemberInfo($part = null){
		$memberInfo = $this->_session->member;
		if($part !== null)
		{
			$memberInfo = $memberInfo[$part];
		}
		return $memberInfo;
	}
	
	// lay thong tin nhom cua nguoi dang nhap
	public function getGroupInfo($part = null){
		$groupInfo = $this->_session->group;
		if(isset($groupInfo[$part]) == true){
			$groupInfo = $groupInfo[$part];
		}
		return $groupInfo;
	}
	
	// lay tat ca cac thong tin
	public function getInfo(){
		$result = $this->_session->getIterator();
		return $result;
	}
	
	// thiet lap thong tin phan quyen
	public function setAcl($infoAuth){
		$result = new \Backend\Model\Functions();
		$id = $infoAuth->group_id;
		$result = $result->acl(array('id' => $id));
		if(count($result) > 0){
			$arrayPrivilege = array();
			foreach ($result as $val => $key){
				$arrayPrivilege[] = $key['name_module'].'_'. $key['name_controller']. '_'. $key['name_action'];
			}
			$this->_session->privilege = $arrayPrivilege;
		}
	}
	
	// lay thong tin phan quyen
	
	public function getAcl($part = null){
		$infoPrivilege = $this->_session->privilege;
		if($part != null){
			$infoPrivilege = $infoPrivilege[$part];
		}
		return $infoPrivilege;
	}
	
}

