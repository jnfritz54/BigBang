<?php
namespace BigBang;
require_once 'loader.php';

$project_root="/var/www/html/perso/bigbang/";
$time=new \DateTime();
echo "Start \n";
$bigbang=new Bigbang($project_root,2);

echo "Cleaning previous data ".$time->format("H:i:s")."\n";
$bigbang->cleanDatabase();

$time=new \DateTime();
echo "Generating raw data ".$time->format("H:i:s")."\n";
$bigbang->generateRaw();

$time=new \DateTime();
echo "Correcting multi-stars systems ".$time->format("H:i:s")."\n";
$bigbang->grandChambardement();

$time=new \DateTime();
echo "Searching for habitable planets ".$time->format("H:i:s")."\n";
$bigbang->calculTemperaturePlanete();

$time=new \DateTime();
echo "Generate lifeforms".$time->format("H:i:s")."\n";
$bigbang->generateLifeforms();

$time=new \DateTime();
echo "End ".$time->format("H:i:s")."\n";

/*
 une fois tout ca généré pour trouver une planète semblable à la terre:
  SELECT * FROM `Planetes` WHERE `type`='T' and `masse` <1.1 and `masse`>0.9 and 
 `particularite`="m" and`inclinaisonOrbite`<5 and `rayonnement` >-10 and `rayonnement` <10 and `rayonnement`!=0
 */
?>