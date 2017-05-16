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

?>