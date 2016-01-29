<?php

namespace Sky\Filter;
class RemoveCircumflex implements \Zend\Filter\FilterInterface{	
	public function filter($value){
		$arr = array('a'=>'à|ả|ã|á|ạ|ă|ằ|ẳ|ẵ|ắ|ặ|â|ầ|ẩ|ẫ|ấ|ậ',
					 'd'=>'đ',
					 'e'=>'è|ẻ|ẽ|é|ẹ|ê|ề|ể|ễ|ế|ệ',
					 'i'=>'ì|ỉ|ĩ|í|ị',
					 'o'=>'ò|ỏ|õ|ó|ọ|ô|ồ|ổ|ỗ|ố|ộ|ơ|ờ|ở|ỡ|ớ|ợ',
					 'u'=>'ù|ủ|ũ|ú|ụ|ư|ừ|ử|ữ|ứ|ự',
					 'y'=>'ỳ|ỷ|ỹ|ý|ỵ',
					 '' =>":|&amp;",
					);
		foreach ($arr as $key => $val){
			$pattern = '#(' . $val . ')#imu';
			$value = preg_replace($pattern,$key,$value);
		}
		$value = preg_replace("/[^A-Za-z0-9\s\s-]/","",$value);
		$value = preg_replace("/-+/","-",$value);
		$value = preg_replace("/^\-+|\-+$/","",$value);
		return $value;
	}
}