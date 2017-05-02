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

calcul2(); //valeurs par défaut pour la terre
calcul2(81472,1660945,2.00865e23); //valeurs pour le systeme TRAPPIST-1

$sql="Select id,objectOrbited, distanceEtoile,albedo,systeme from Planetes where objectOrbited is not null";
$res=$mysqli->query($sql);
$cpt=0;
$cptEden=0;
while($row=$res->fetch_assoc()){
	
	//on ne compte que celles qui sont en séquence principale
	if($Stars[$row['objectOrbited']]['typeSurcharge']!=$Stars[$row['objectOrbited']]['typeOrigine']){continue;}
	
	$rayonKm=bcdiv($Stars[$row['objectOrbited']]['rayon'],1000);	
	$tempC=calcul2($rayonKm,$row['distanceEtoile']*Universe::$astron,$Stars[$row['objectOrbited']]['rayonnement'],$row['albedo']);
	
	if($tempC>=-30 && $tempC<25 ){
		//echo "Système: ".$row['systeme']." Planète: ".$row['id']." => ".$tempC."°C ( Age: ".$Stars[$row['objectOrbited']]['age'].")\n";
		$cpt++;
		if($tempC>=-20 && $tempC<10 && $Stars[$row['objectOrbited']]['age']>=1.5){
			//echo "Système: ".$row['systeme']." Planète eden: ".$row['id']." => ".$tempC."°C ( Age: ".$Stars[$row['objectOrbited']]['age'].") \n";
			$cptEden++;
		}
	}
	$sqlupdate="Update Planetes set rayonnement='".$tempC."' where id='".$row['id']."';";
	$resUpdate=$mysqli->query($sqlupdate);
	
}
/*
$sql="Select count(*) as nullOrbit from Planetes where objectOrbited is null";
$res=$mysqli->query($sql);
$row=$res->fetch_assoc();
var_dump($row);die;*/

echo "\n ".$cpt." planètes potentiellement habitables";
echo "\n dont ".$cptEden." planètes potentiellement eden\n";

function calcul2($rayonEtoile=696342,$orbitePlanete=149500000,$rayonnementEtoileBrut=3.826E26,$albedo=0.29){
	$distance= $rayonEtoile+$orbitePlanete;
	$distance=$distance*1000;
	$sphere=4*pow($distance,2);
	
	$surface=maths_service::exp2int($sphere*pi());
	//echo "surface: ".$surface."m² // ";
	$production=maths_service::exp2int($rayonnementEtoileBrut); //watts
	
	//echo "production: ".$production."W // ";
	$Io=bcdiv($production,$surface,4); //en watts/m² irradiation solaire rayonnementau niveau de l'orbite terrestre
	//echo "rayonnement: ".$Io."W/m² "; //

	
	//dernière equation de la page:
	//https://fr.wikipedia.org/wiki/Temp%C3%A9rature_d%27%C3%A9quilibre_%C3%A0_la_surface_d%27une_plan%C3%A8te#Calcul_de_la_temp.C3.A9rature_de_corps_noir
	$dividende=bcmul($Io,(1-$albedo),5);
	$diviseur=bcmul(4,maths_service::exp2int(Universe::$StefanBoltzmann),10);	
	$division=bcdiv($dividende,$diviseur);	
	$Teq=pow($division,0.25); //Température d'équilibre de corps noir en kelvins
	
	//echo " Teq: ".$Teq."\n";
	return round(maths_service::kelvin2celsius($Teq),3);
	
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