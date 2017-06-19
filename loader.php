<?php 
//a chaque appel d'une classe non chargée on passera par ici
function __autoload($class) {
	$parts = preg_split('#\\\#', $class);	
	// on extrait le dernier element
	$classname = array_pop($parts);	
	
	// on créé le chemin vers la classe
	$path = implode("\\", $parts);
	
	
	if(ereg("service", $classname)){
		$filename = "./services/". $classname .".php";
	}else{
		$filename = "./objects/". $classname ."_object.php";
	}

	if(!file_exists($filename)){
		throw new Exception("class not found ".$filename." (".$class.")");
	}
	include_once($filename);
}
?>