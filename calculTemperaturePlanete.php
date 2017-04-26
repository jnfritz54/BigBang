<?php 
namespace BigBang;

/***
 * à lancer après le generator
 */

include 'Star_object.php';
include 'Systeme_object.php';
include 'Planete_object.php';
require_once('MySQLi_2.php');


$mysqli=new MySQLi_2("localhost","root", "root", "perso");
$sqlStars="select * from Stars where 1";
$Stars=array();
$resS=$mysqli->query($sqlStars);
while($rowS=$resS->fetch_assoc()){
	$Stars[$rowS['id']]=$rowS;
}

$sql="Select id,objectOrbited, distanceEtoile,systeme from Planetes where objectOrbited is not null";
$res=$mysqli->query($sql);
while($row=$res->fetch_assoc()){
	
	$tempC=calcul($Stars[$row['objectOrbited']]['rayon'],$row['distanceEtoile']*Universe::$astron,$Stars[$row['objectOrbited']]['rayonnement']);
	
	if($tempC>=-30 && $tempC<50){
		echo "Système: ".$row['systeme']." Planète: ".$row['id']."\n";echo $tempC."\n";die;}
}



function calcul($rayonEtoile=696342,$orbitePlanete=149500000,$rayonnementEtoileBrut=3.826E26){
	$distance= $rayonEtoile+$orbitePlanete;
	$distance=$distance*1000;
	$sphere=4*pow($distance,2);
	//echo "4R² ".$sphere."\n";
	$surface=maths_service::exp2int($sphere*pi());
	//echo "surface: ".$surface."\n";
	$production=maths_service::exp2int($rayonnementEtoileBrut);
	
	//echo "production: ".$production."\n";
	$rayonnement=bcdiv($production,$surface,4);
	//$rayonnement-=$rayonnement*0.35; //albédo
	$F=$rayonnement/4;
	$T4=bcdiv($F,maths_service::exp2int(Universe::$StefanBoltzmann),4);
	$TK=pow($T4,1/4);//température en kelvin
	return round(maths_service::kelvin2celsius($TK),3);
	
}
?>