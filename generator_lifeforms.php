<?php 
	namespace BigBang;
	require_once 'loader.php';
	require_once 'MySQLi_2.php';
	
	$mysqli=new MySQLi_2("localhost","root", "root", "perso");
	
	$sqlInsert="Insert into Lifeforms values ";
	$sqlValues=array();
	$cptInsert=0;
	
	//planètes habitables à traiter:
	$sqlHab="Select p.* from Planetes p , Systemes sy where sy.distance>'".Galaxy::$rayonMin."' and p.systeme =sy.id and eden=1";
	$resHab=$mysqli->query($sqlHab);
	
	while($habPlanet=$resHab->fetch_assoc()){
		$life=new LifeForm($habPlanet['id'],$habPlanet['systeme']);
		$sqlValues[]=$life->__toSqlValues();
		$cptInsert++;

		
	}

	$requete=$sqlInsert.join(", ",$sqlValues).";";
	$mysqli->query($requete);
	
	echo $cptInsert." formes de vie créées\n"
?>