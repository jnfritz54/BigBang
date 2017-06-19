<?php
namespace BigBang;
require_once('../MySQLi_2.php');
require('../loader.php');

//header("Content-type: image/png");
$image= imagecreate(1200, 1200);

$noir = imagecolorallocate($image, 0, 0, 0);
$orange = imagecolorallocate($image, 255, 128, 0);
$jaune = imagecolorallocate($image, 255, 220, 20);
$bleu = imagecolorallocate($image, 0, 0, 255);
$bleuclair = imagecolorallocate($image, 156, 227, 254);
$blanc = imagecolorallocate($image, 255, 255, 255);
$blancAlphaMoins=imagecolorallocatealpha($image, 238, 255,238 ,100);
$gris=imagecolorallocatealpha($image, 120,120,120 ,0);
$vert=imagecolorallocatealpha($image, 16, 180,16 ,0);

$hexaByTypeSurcharge=array('1'=>imagecolorallocatealpha($image, 101, 85, 85,1),
			'2'=>imagecolorallocatealpha($image, 255, 32, 32,1),
			'3'=>imagecolorallocatealpha($image, 100, 100,100,1),
			"4"=>$noir,
			'L'=>imagecolorallocatealpha($image, 32, 16, 32,80),
			'D'=>$blancAlphaMoins,
			'D2'=>$blancAlphaMoins,
			'M'=>imagecolorallocatealpha($image, 255, 32, 32,30),
			'K'=>imagecolorallocatealpha($image, 255, 144, 0,1),
			'G'=>imagecolorallocatealpha($image, 238, 238, 0,1),
			'F'=>imagecolorallocatealpha($image, 255, 147, 0,1),
			'A'=>$blanc,
			'B'=>imagecolorallocatealpha($image, 174, 203, 255,1),
			'O'=>imagecolorallocatealpha($image, 123, 167, 243,1)			
	);

$mysqli=new MySQLi_2("localhost","root", "root", "perso");



$result=array('S'=>array(),'P'=>array());
if(isset($_GET['id'])){
	$idSysteme=intval($_GET['id']);
}else{
	$idSysteme=1;
}

$sqlStars="select * from Stars where systeme=".$idSysteme.";";

$ratio=1500/600;
$stars=array();

$resStar=$mysqli->query($sqlStars);
$cpt=0;
while($star=$resStar->fetch_assoc()){
		
	$stars[$star['id']]=array('rayonAs'=>$star['rayon']/Universe::$astron,
		'distance'=>$star['distanceBarycentre'],'couleur'=>$hexaByTypeSurcharge[$star['typeSurcharge']]
	);
	$cpt++;

}

$index=0;
foreach ($stars as $key=>$s){
	if($cpt==1){
		$stars[$key]['x']=0;
		$stars[$key]['y']=0;
	}else{
		$tmp= maths_service::polaireToCartesien($stars[$key]['distance']*$ratio, (360/$cpt)*($index),false);
		$stars[$key]['x']=$tmp['x'];
		$stars[$key]['y']=$tmp['y'];
	}
	$rayon=($stars[$key]['distance']*$ratio);
	imageellipse($image, 600, 600,($rayon*2) , ($rayon*2), $gris);
	imagefilledellipse($image,(600+$tmp['x']), (600+$tmp['y']), 6,6, $stars[$key]['couleur']);

	$index++;
}


$sqlPlanetes="select * from Planetes where systeme=".$idSysteme.";";



$resPlanetes=$mysqli->query($sqlPlanetes);
while($planete=$resPlanetes->fetch_assoc()){
	
	if($planete['objectOrbited']==null){
		$star=array('rayonAs'=>0);
		$fillerX=0;$fillerY=0;
	}else{
		$star=$stars[$planete['objectOrbited']];
		$fillerX=$star['x'];$fillerY=$star['y'];
	}
	if($planete['distanceEtoile']<=0.5){
		$ratioPlanete=$ratio/15;
	}elseif($planete['distanceEtoile']<=1){
		$ratioPlanete=$ratio/8;
	}else if($planete['distanceEtoile']<=2){
		$ratioPlanete=$ratio/4;
	}
	else if($planete['distanceEtoile']<=5){
		$ratioPlanete=$ratio/2;
	}
		
	$rayonTotal=bcdiv($star['rayonAs'],Universe::$astron)+$planete['distanceEtoile'];
	//cercle de l'orbite
	imageellipse($image, (600+$fillerX), (600+$fillerY), ($rayonTotal*2/$ratioPlanete),($rayonTotal*2/$ratioPlanete), $gris);
	if($fillerX<0){
		imagefilledellipse($image,(600+$fillerX-($rayonTotal/$ratioPlanete)), (600+$fillerY), 3, 3, $vert);
	}else{
		imagefilledellipse($image,(($rayonTotal/$ratioPlanete)+600+$fillerX), (600+$fillerY), 3, 3, $vert);
	}

}

imagepng($image);
?>
