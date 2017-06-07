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
	
	$probaNbEtoiles=array(50=>1,75=>2,95=>3,100=>4);
	
	$mysqli=new MySQLi_2("localhost","root", "root", "perso");
	
	$i=1;
	$cptStars=0;
	while($i<=1000000){
		$systeme=new Systeme();
		$sysValues[]=$systeme->__toSqlValues();
		$nbStars=1;
		$testProba=rand(1,100);
		foreach ($probaNbEtoiles as $proba=>$nombre){
			if($testProba<=$proba){
				$nbStars=$nombre;break;
			}
		}
		$contractionSysteme=0.5;//maths_service::float_rand(0,1,4); //contraction ou extention aléatoire du systeme
		
		$systemStars=array();
		$stars=array();
		
		for($j=0;$j<$nbStars;$j++){
			$star=new Star_object($i,$nbStars>1);
			
			$starValues[]=$star->__toSqlValues();
			$cptStars++;
			$systemStars[$j]=$cptStars;
			$stars[$cptStars]=$star;
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
			$masse=0;
			$rayonnement=0;
			if(count($systemStars)==1){
				//si système unique, l'objet orbité est automatiquement l'étoile
				$orbited=$systemStars[0];
				
				//pas de planète sur les étoiles uniques post-supernova
				//idem pour les disques protoplanétaires qui n'ont pas encore fini leur formation
				if(in_array($stars[$orbited]->typeSurcharge, array(3,4,1)) ){
					$nbPlanets=0;
					break;
				}
				$masse=$stars[$orbited]->masseOrigine;
				$rayonnement=$stars[$orbited]->rayonnement;
			}else{
				//sinon cela peut-être l'une des étoiles ou le centre de gravité du système (null)
				if(!in_array('null', $systemStars)){$systemStars[]='null';}
				$orbited=$systemStars[rand(0,count($systemStars)-1)];
				if($orbited=='null'){					
					foreach ($systemStars as $s){
						if($s=='null'){continue;}
						$rayonnement+=$stars[$s]->rayonnement;
					}
										
				}else{
					$rayonnement=$stars[$orbited]->rayonnement;
				}
				foreach ($stars as $s){
					$masse+=$s->masseOrigine;
				}
			}
			//echo $i." ".$orbited."\n";
			$planete=new Planete($i,$orbited,$masse,$rayonnement,$h,$contractionSysteme);
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
		if(!empty($sysValues)){
			$requete=$sysInsert.join(", ",$sysValues).";";
			$mysqli->query($requete);
		}
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