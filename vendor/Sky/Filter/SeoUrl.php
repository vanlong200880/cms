<?php

namespace Sky\Filter;
use Zend\Filter\FilterChain;
class SeoUrl implements \Zend\Filter\FilterInterface{
	public function filter($value){
		$filter = new FilterChain();
		
		$filter->attach(new \Zend\Filter\StringToLower(array('encoding'=>'utf-8')))
			   ->attach(new \Zend\Filter\StringTrim())
			   ->attach(new \Zend\Filter\PregReplace(array('pattern'=>'#\s{2,255}#','replacement'=> ' ')))
			   ->attach(new \Zend\Filter\Word\SeparatorToSeparator(' ','-'))
			   ->attach(new \Sky\Filter\RemoveCircumflex());
		
		$value = $filter->filter($value);
		return $value;
	}
}