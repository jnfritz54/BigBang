<?php 
namespace BigBang;
require_once 'loader.php';

abstract class Object {
	
	public $verboseTypes=array(0=>"Silent",1=>"Important",2=>"Partial",3=>"Verbose");
	public $verbose=1;

	/***
	 * assigne le mode verbose de l'objet
	 * @param unknown $verbose
	 */
	public function setVerbose($verbose){
		if(isset($this->verboseTypes[$verbose])){
			$this->verbose=$verbose;
		}else{
			throw new \Exception("Asked for unsupported verbose mode");
		}
		return true;
	}
	
	/***
	 * renvoie les propriétés publiques de l'objet sous forme de tableau
	 * @return array
	 */
	public function __toArray(){
		$array=get_object_vars($this);
		return $array;
	}
	
	/***
	 * renvoie les propriétés publiques de l'objet sous forme de string
	 * @return string
	 */
	public function __toString(){
		$string=join(" ", $this->__toArray());
		return $string;
	}
	
	/***
	 * renvoie les propriétés publiques de l'objet sous forme de string compatible avec du html
	 * @return string
	 */
	public function __toHtml(){
		$string=$this->__toString();
		return nl2br(htmlentities($string));
	}
	
	/***
	 * Doit être hérité: renvoie les propriétés de l'objet pour être intégrées dans une requête d'insert 
	 */
	public function __toSqlValues(){
	
	}
	
	/***
	 * Peuple les propriétés de l'objet avec le contenu d'un tableau associatif découlant d'un mysql select
	 */
	public function __loadFromSqlRow($array){
		foreach ($array as $var=>$content){
			$this->{$var}=$content;
		}
		return true;
	}
	
	/***
	 * Gère l'affichage d'une info en fonction du mode verbeux désiré
	 * @return void
	 */
	protected function echoVerbose($index,$string){
		if($index<=$this->verbose){
			echo $string;
		}
	}
}