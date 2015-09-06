<?php 
namespace Sky\System;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EncriptPassword{

	public function prepareData($data)
	{
		$data['salt'] = $this->generateDynamicSalt();				
		$data['password'] = $this->encriptPassword(
			$this->getStaticSalt(), 
			$data['password'], 
			$data['salt']
		);
		$data['token'] = md5(uniqid(mt_rand(), true));
		return $data;
	}

	public function generateDynamicSalt()
	{
		$dynamicSalt = '';
		for ($i=0; $i < 50; $i++) { 
			$dynamicSalt .= chr(rand(33,126));
		}
		return $dynamicSalt;
	}

	public function getStaticSalt()
	{
		$getStaticSalt = '';
        $config = new \Zend\Config\Config( include ROOT_PATH . '/config/autoload/global.php' );
		$getStaticSalt = $config['static_salt'];
		return $getStaticSalt;
	}

	public function encriptPassword($staticSalt, $password, $dynamicSalt){
		return sha1(md5($staticSalt . $password . $dynamicSalt));
	}

	public function generatePassword($l = 8, $c = 0, $n = 0, $s = 0){
		$count = $c + $n + $s;
		$out = '';
		if(!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
		  	trigger_error('Argument(s) not an integer', E_USER_WARNING);
			return false;
		}
		elseif($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
			trigger_error('Argument(s) out of range', E_USER_WARNING);
			return false;
		}
		elseif($c > $l) {
			trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
			return false;
		}
		elseif($n > $l) {
			trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
			return false;
		}
		elseif($s > $l) {
			trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
			return false;
		 }
		 elseif($count > $l) {
			trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
			return false;
		}

		// all inputs clean, proceed to build password
		// change these strings if you want to include or exclude possible password characters
		$chars = "abcdefghijklmnopqrstuvwxyz";
		$caps = strtoupper($chars);
		$nums = "0123456789";
		$syms = "!@#$%^&*()-+?";
	 
		// build the base password of all lower-case letters
		for($i = 0; $i < $l; $i++) {
			$out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
	 
		// create arrays if special character(s) required
		if($count) {
			// split base password to array; create special chars array
			$tmp1 = str_split($out);
			$tmp2 = array();
	 
			// add required special character(s) to second array
			for($i = 0; $i < $c; $i++) {
				array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
			}
			for($i = 0; $i < $n; $i++) {
				array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
			}
			for($i = 0; $i < $s; $i++) {
				array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
			}
	 
			// hack off a chunk of the base password array that's as big as the special chars array
			$tmp1 = array_slice($tmp1, 0, $l - $count);
			// merge special character(s) array with base password array
			$tmp1 = array_merge($tmp1, $tmp2);
			// mix the characters up
			shuffle($tmp1);
			// convert to string for output
			$out = implode('', $tmp1);
		}
	 
		return $out;
	}
}