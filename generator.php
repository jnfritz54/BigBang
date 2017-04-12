<?php 
	namespace BigBang;
	include 'Star_object.php';
	include 'Systeme_object.php';
	require_once('MySQLi_2.php');
	
	$starInsert="insert into Stars values ";
	$starValues=array();
	$sysInsert="insert into Systemes values ";
	$sysValues=array();
	
	$mysqli=new MySQLi_2("localhost","root", "root", "perso");
	
	$i=0;
	while($i<100000){
		$systeme=new Systeme();
		$sysValues[]=$systeme->__toSqlValues();
		$nbStars=rand(1,2);
		
		for($j=0;$j<$nbStars;$j++){
			$star=new Star_object($i);
			$starValues[]=$star->__toSqlValues();
			if($i%500==0 && !empty($starValues) && !empty($sysValues)){
				$requete=$starInsert.join(", ",$starValues).";";
				$mysqli->query($requete);
				$requete=$sysInsert.join(", ",$sysValues).";";
				$mysqli->query($requete);
				$sysValues=array();$starValues=array();
			}			
		}
		$i++;
	}
	if(!empty($starValues)){
		$requete=$starInsert.join(",",$starValues);
		$mysqli->query($requete);
		$requete=$sysInsert.join(", ",$sysValues).";";
		$mysqli->query($requete);
	}
	echo "End\n";
?>