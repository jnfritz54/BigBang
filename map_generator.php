<?php 
	namespace BigBang;
	require_once 'loader.php';
	header("Content-type: image/png");
	
	$size=1025;
	$mapObj=new PlaneteMapTellurique($size);
	return $mapObj->__toPng("testmap.png");
	
	
?>