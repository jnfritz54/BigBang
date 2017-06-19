<?php 
//a chaque appel d'une classe non chargée on passera par ici
function __autoload($class) {
	$rootDir="/var/www/html/perso/bigbang/";
	$parts = preg_split('#\\\#', $class);
	
	// on extrait le dernier element
	$classname = array_pop($parts);	
	
	// on créé le chemin vers la classe
	$path = implode("\\", $parts);
	
	
	if(ereg("service", $classname)){
		$filename = $rootDir."services/". $classname .".php";
	}else{
		$filename = $rootDir."objects/". $classname ."_object.php";
	}

	if(!file_exists($filename)){
		
		throw new Exception("Class definition file not found ".$filename." in ".basename(__DIR__) ."");
	}
	include_once($filename);
}
?>