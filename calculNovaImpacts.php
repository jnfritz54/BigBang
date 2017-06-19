<?php 
namespace BigBang;

/***
 * à lancer après le generator, le chambardement
 * et avant la génération des formes de vie
 */

include 'Star_object.php';
include 'Systeme_object.php';
include 'Planete_object.php';
require_once('MySQLi_2.php');


$mysqli=new MySQLi_2("localhost","root", "root", "perso");

$debut=0;
$pas=100000;
$sqlCountSys="select id from Systemes order by id DESC limit 1;";
$resCountSys=$mysqli->query($sqlCountSys);
$sys=$resCountSys->fetch_assoc();
$fin=$sys['id'];

$systemesResults=array();

while($debut<$fin){
	$limit=$debut+$pas;
	
	/***
	 *caching Systemes/stars
	 */
	$sqlCache="Select * from Systemes where id>='".$debut."' and id<'".$limit."';";
	$Sys=array();
	$resCache=$mysqli->query($sqlCache);
	while($sy=$resCache->fetch_assoc()){
		
		$Sys[$sy['id']]=$sy;
	}
	
	$sqlNovas="select * from Stars s where (s.typeSurcharge=3 or s.typeSurcharge=4) and systeme>='".$debut."' and systeme<'".$limit."'";
	$resNova=$mysqli->query($sqlNovas);
	while($s=$resNova->fetch_assoc()){
		//calcul coordonées zone affectée
		/**
		 * grosse approximation dégueu, on calcule un cube au lieu d'une sphère
		 */
		$coords=maths_service::polaireToCartesien($Sys[$s['systeme']]['distance'],$Sys[$s['systeme']]['angle']);
		
		
		$minX=$coords['x']-20;
		$maxX=$coords['x']+20;	
		
		$minY=$coords['y']-20;
		$maxY=$coords['y']+20;
		
		$minZ=$Sys[$s['systeme']]['altitude']-20;
		$maxZ=$Sys[$s['systeme']]['altitude']+20;
		
		//on calcule en polaire les coordonées des coins du carré
		$polaire1=maths_service::cartesienToPolaire($minX, $minY);		
		$polaire2=maths_service::cartesienToPolaire($minX, $maxY);
		$polaire3=maths_service::cartesienToPolaire($maxX, $maxY);
		$polaire4=maths_service::cartesienToPolaire($maxX, $minY);
		$angleMin=min(array($polaire1['angle'],$polaire2['angle'],$polaire3['angle'],$polaire4['angle']));
		$angleMax=max(array($polaire1['angle'],$polaire2['angle'],$polaire3['angle'],$polaire4['angle']));
		$rayonMin=min(array($polaire1['rayon'],$polaire2['rayon'],$polaire3['rayon'],$polaire4['rayon']));
		$rayonMax=max(array($polaire1['rayon'],$polaire2['rayon'],$polaire3['rayon'],$polaire4['rayon']));
		
		$sql="Select count(*) as cpt from Systemes where angle>='".$angleMin."' and angle<='".$angleMax."' and
			 distance>='".$rayonMin."' and distance<='".$rayonMax."' and altitude<='".$maxZ."' and altitude>'".$minZ."' and id!='".$s['systeme']."' ";
		$res=$mysqli->query($sql);
		$count=$res->fetch_assoc();
		
		
		if($count['cpt']>0){$systemesResults[]=$s['systeme'];}
	}
	if($limit%1000==0){
			echo $limit." ".count($systemesResults);
			$dt= new \DateTime();			
			echo " ".$dt->format("H:i")."\n";
			$sqlUpdate="update Planetes set eden=0 where eden=1 and systeme in (".join(",", $systemesResults).");";
			$resUpdate=$mysqli->query($sqlUpdate);	
			$systemesResults=array();
	}
	$debut+=$pas;
}



?>