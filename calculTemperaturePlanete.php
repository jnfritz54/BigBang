<?php 
namespace BigBang;

/***
 * à lancer après le generator
 */

require_once 'loader.php';
require_once('MySQLi_2.php');

$mysqli=new MySQLi_2("localhost","root", "root", "perso");


//on va devoir fonctionner par tranches de 100-200 000 systemes
//si on essaye de prendre le million on mange un kill-9 systeme
$debut=0;
$pas=100000;

$sqlResetEden="Update Planetes set eden=0 where eden=1;";
$resResetEden=$mysqli->query($sqlResetEden);

$sqlCountSys="select id from Systemes order by id DESC limit 1;";
$resCountSys=$mysqli->query($sqlCountSys);
$sys=$resCountSys->fetch_assoc();
$fin=$sys['id'];

while($debut<$fin){
	$limit=$debut+$pas;
	//echo $debut." => ".$limit."\n";
		
	$sqlStars="select s.* from Stars s where  s.systeme>='".$debut."' and s.systeme<'".$limit."';";
	$Stars=array();
	$resS=$mysqli->query($sqlStars);
	while($rowS=$resS->fetch_assoc()){
		$Stars[$rowS['id']]=$rowS;
	}
	/***
	calcul2(); //valeurs par défaut pour la terre
	calcul2(81472,1660945,2.00865e23); //valeurs pour le systeme TRAPPIST-1
	*/
	

	//pour gagner du temps on ne compte pas les géantes gazeuses sans satellites,
	//ni les planétoides trop petits pour avoir une atmosphère, ni les ceintures d'astéroides
	$sql="Select p.* from Planetes p, Systemes sy where sy.id=p.systeme and sy.distance>'".Galaxy::$rayonMin."' and p.systeme>='".$debut."' and p.systeme<'".$limit."' and p.objectOrbited is not null and (type not in ('A','P','G') or (type='G' and (particularite='m' or particularite='Mm')) )";
	$res=$mysqli->query($sql);
	$cpt=0; //mondes survivables
	$cptHab=0; //mondes habitables
	$cptEden=0; //mondes habitables
	
	while($row=$res->fetch_assoc()){
		$eden=0;
		//on ne compte que celles qui sont en séquence principale, celles en formation n'ont pas encore de planètes créées, et celles qui ont dépassé
		//ce stade ne peuvent plus abriter la vie
		if($Stars[$row['objectOrbited']]['typeSurcharge']!=$Stars[$row['objectOrbited']]['typeOrigine']){continue;}
		
		$rayonKm=bcdiv($Stars[$row['objectOrbited']]['rayon'],1000);	
		$tempC=calcul2($rayonKm,$row['distanceEtoile']*Universe::$astron,$Stars[$row['objectOrbited']]['rayonnement'],$row['albedo']);
		
		if($tempC>=-30 && $tempC<20 ){
			//planète relativement tempérée
			//echo "Système: ".$row['systeme']." Planète: ".$row['id']." => ".$tempC."°C ( Age: ".$Stars[$row['objectOrbited']]['age'].")\n";
			$cpt++;
			if($tempC>=-20 && $tempC<10 && $Stars[$row['objectOrbited']]['age']>=1){
				//planète tempérée suffisement agée pour voir des formes de vie se dévelloper 
				//echo "Système: ".$row['systeme']." Planète eden: ".$row['id']." => ".$tempC."°C ( Age: ".$Stars[$row['objectOrbited']]['age'].") \n";
				$cptHab++;
				if($Stars[$row['objectOrbited']]['age']>=2 && $row['masse']<5 
						&& ($row['particularite']=="m" || $row['particularite']=="Mm")){
					//planète tempérée suffisement agée et stable pour voir des formes de vie et un écosysteme complexe 
					$cptEden++; 
					$eden=1;
					$sqlupdate="Update Planetes set rayonnement='".$tempC."', eden=1 where id='".$row['id']."';";
				}else{
					$sqlupdate="Update Planetes set rayonnement='".$tempC."', eden=0 where id='".$row['id']."';";
				}
			}else{
				$sqlupdate="Update Planetes set rayonnement='".$tempC."', eden=0 where id='".$row['id']."';";
			}
		}else{
			$sqlupdate="Update Planetes set rayonnement='".$tempC."', eden=0 where id='".$row['id']."';";
		}
		//$sqlupdate="Update Planetes set rayonnement='".$tempC."', eden='".$eden."' where id='".$row['id']."';";
		$resUpdate=$mysqli->query($sqlupdate);
		
	}
	$debut+=$pas;
}
/*
$sql="Select count(*) as nullOrbit from Planetes where objectOrbited is null";
$res=$mysqli->query($sql);
$row=$res->fetch_assoc();
var_dump($row);die;*/

echo "\n ".$cpt." planètes potentiellement survivables";
echo "\n dont ".$cptHab." planètes potentiellement habitables (anciennement eden)";
echo "\n dont ".$cptEden." planètes stabilisées potentiellement habitables avec biotope\n";

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

?>