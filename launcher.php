<?php
$project_root="/var/www/html/perso/bigbang/";
$time=new DateTime();$output="";$val="";
echo "Start \n";

echo "Cleaning previous data ".$time->format("H:i:s")."\n";
exec("php ".$project_root."cleaner.php",$output,$val);

$time=new DateTime();$output="";$val="";
echo "Generating raw data ".$time->format("H:i:s")."\n";
exec("php ".$project_root."generator.php",$output,$val);

$time=new DateTime();$output="";$val="";
echo "Correcting multi-stars systems ".$time->format("H:i:s")."\n";
exec("php ".$project_root."grandChambardement.php",$output,$val);

$time=new DateTime();$output="";$val="";
echo "Searching for habitable planets ".$time->format("H:i:s")."\n";
exec("php ".$project_root."calculTemperaturePlanete.php",$output,$val);
foreach($output as $ligne){
	echo $ligne."\n";
}

$time=new DateTime();
echo "End ".$time->format("H:i:s")."\n";

/*
 une fois tout ca généré pour trouver une planète semblable à la terre:
  SELECT * FROM `Planetes` WHERE `type`='T' and `masse` <1.1 and `masse`>0.9 and 
 `particularite`="m" and`inclinaisonOrbite`<5 and `rayonnement` >-10 and `rayonnement` <10 and `rayonnement`!=0
 */
?>