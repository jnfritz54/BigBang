<?php 
	namespace BigBang;
	include 'Star_object.php';
	require_once('MySQLi_2.php');
	
	$debut=new \DateTime();
	$sqlInsert="insert into Stars values ";
	$sqlValues=array();
	
	$mysqli=new MySQLi_2("localhost","root", "root", "perso");
	
	for($i=0;$i<=10000;$i++){
		$star=new Star_object();
		$sqlValues[]=$star->__toSqlValues();
		if($i%900==0 && !empty($sqlValues)){
			$requete=$sqlInsert.join(",",$sqlValues).";";
			//var_dump($requete);
			$mysqli->query($requete);
			$sqlValues=array();
			
		}			
	}
	if(!empty($sqlValues)){
		$requete=$sqlInsert.join(",",$sqlValues);
		$mysqli->query($requete);
	}
	
	$fin=new \DateTime();
	$diff=$debut->diff($fin);
	
	echo "durée d'exec: ";
	var_dump($diff);
	
?>