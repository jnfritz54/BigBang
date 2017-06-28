<?php 
namespace BigBang;
require_once 'loader.php';

abstract class Object {

	public function __toArray(){
		$array=get_object_vars($this);
		return $array;
	}
	
	public function __toString(){
		$string=join(" ", $this->__toArray());
		return $string;
	}
	
	public function __toHtml(){
		$string=$this->__toString();
		return nl2br(htmlentities($string));
	}
	
	public function __toSqlValues(){

	}
	
	public function __loadFromSqlRow($array){
		foreach ($array as $var=>$content){
			$this->{$var}=$content;
		}
	
	}
}