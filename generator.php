<?php 
	namespace BigBang;
	include 'Star_object.php';
	include 'Systeme_object.php';
	include 'Planete_object.php';
	require_once('MySQLi_2.php');
		
	$starInsert="insert into Stars values ";
	$starValues=array();
	$sysInsert="insert into Systemes values ";
	$sysValues=array();
	$planeteInsert="insert into Planetes values ";
	$planeteValues=array();
	
	$mysqli=new MySQLi_2("localhost","root", "root", "perso");
	
	$i=0;
	$cptStars=0;
	while($i<1000000){
		$systeme=new Systeme();
		$sysValues[]=$systeme->__toSqlValues();
		$nbStars=rand(1,3);
		
		$systemStars=array();
		
		for($j=0;$j<$nbStars;$j++){
			$star=new Star_object($i);
			$starValues[]=$star->__toSqlValues();
			$cptStars++;
			$systemStars[$j]=$cptStars;
			if($i%500==0 && !empty($starValues) && !empty($sysValues)){
				$requete=$starInsert.join(", ",$starValues).";";
				$mysqli->query($requete);
				$requete=$sysInsert.join(", ",$sysValues).";";
				$mysqli->query($requete);
				$sysValues=array();$starValues=array();
			}			
		}

		$nbPlanets=rand(1,15);
		for($h=0;$h<$nbPlanets;$h++){
			$orbited=null;			
			if(count($systemStars)==1){
				//si système unique, l'objet orbité est automatiquement l'étoile
				$orbited=$systemStars[0];
			}else{
				//sinon cela peut-être l'une des étoiles ou le centre de gravité du système (null)
				$systemStars[]='null';
				$orbited=$systemStars[rand(0,count($systemStars)-1)];
			}
			//echo $i." ".$orbited."\n";
			$planete=new Planete($i,$orbited);
			$planeteValues[]=$planete->__toSqlValues();
		}
		
		if(count($planeteValues)>900){			
			$requeteP=$planeteInsert.join(", ",$planeteValues).";";
			$mysqli->query($requeteP);			
			$planeteValues=array();
		}
		
		$i++;
	}
	if(!empty($starValues)){
		$requete=$starInsert.join(",",$starValues);
		$mysqli->query($requete);
		$requete=$sysInsert.join(", ",$sysValues).";";
		$mysqli->query($requete);
	}
	if(!empty($planeteValues)){
		$requete=$planeteInsert.join(", ",$planeteValues).";";
		$mysqli->query($requete);
	}
	echo "End\n";
	
	function polaireToCartesien($rayon,$angle){
		$x=$rayon*cos($angle);
		$y=$rayon*sin($angle);
		
		return array("x"=>$x,"y"=>$y);
	}
?>