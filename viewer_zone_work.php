<?php
namespace BigBang;
require_once('MySQLi_2.php');
include 'Universe_object.php';
include 'Star_object.php';
require('./maths_service.php');
header("Content-type: image/png");
$image= imagecreate(1000, 1000);

$noir = imagecolorallocate($image, 0, 0, 0);
$orange = imagecolorallocate($image, 255, 128, 0);
$bleu = imagecolorallocate($image, 0, 0, 255);
$bleuclair = imagecolorallocate($image, 156, 227, 254);
$blanc = imagecolorallocate($image, 255, 255, 255);
$blancAlphaMoins=imagecolorallocatealpha($image, 238, 255,238 ,100);

if(!empty($_GET)){
	$hexaByTypeSurcharge=array('1'=>imagecolorallocatealpha($image, 101, 85, 85,1),
			'2'=>imagecolorallocatealpha($image, 255, 32, 32,1),
			'3'=>$noir,
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
	
	//pour faciliter on transforme les coordo polaires en cartésiennes
	$rayon=$_GET['distance'];$angle=$_GET['angle'];
	if($rayon!=intval($rayon)|| $angle!=intval($angle)){ 
		imagestring($image, 4, 400, 0, "Bad Parameter", $blanc);
		imagepng($image);
		die;
	}
	
	$coords=maths_service::polaireToCartesien($rayon,$angle);
	
	
	$minX=$coords['x']-500;
	$maxX=$coords['x']+500;
	
	$minY=$coords['y']-500;
	$maxY=$coords['y']+500;
	
	//on calcule en polaire les coordonées des coins du carré
	$polaire1=maths_service::cartesienToPolaire($minX, $minY);
	$polaire2=maths_service::cartesienToPolaire($minX, $maxY);
	$polaire3=maths_service::cartesienToPolaire($maxX, $maxY);
	$polaire4=maths_service::cartesienToPolaire($maxX, $minY);
	$angleMin=min(array($polaire1['angle'],$polaire2['angle'],$polaire3['angle'],$polaire4['angle']));
	$angleMax=max(array($polaire1['angle'],$polaire2['angle'],$polaire3['angle'],$polaire4['angle']));
	$rayonMin=min(array($polaire1['rayon'],$polaire2['rayon'],$polaire3['rayon'],$polaire4['rayon']));
	$rayonMax=max(array($polaire1['rayon'],$polaire2['rayon'],$polaire3['rayon'],$polaire4['rayon']));
	
	$ratio=1000/1000;
	
	$sql="Select * from Systemes where angle>='".$angleMin."' and angle<='".$angleMax."' and
			 distance>='".$rayonMin."' and distance<='".$rayonMax."'";
	$res=$mysqli->query($sql);
	//	var_dump($sql);
	
	$cpt=0;
	while($point=$res->fetch_assoc()){
		
		$stars=array();
		$cptStars=0;
		$sqlStars="Select * from Stars where systeme=".$point['id'].";";
		$resStars=$mysqli->query($sqlStars);
		while($s=$resStars->fetch_assoc()){
			$stars[]=$s;
		}
		
		$cpt++;
	
		$tmp= maths_service::polaireToCartesien($point['distance'],$point['angle']);
		$x=(($tmp['x']-$minX)/$ratio);
		$y=(($tmp['y']-$minY)/$ratio);
		
		$nbStars=count($stars);
		foreach ($stars as $s){			
			$tmp= maths_service::polaireToCartesien(2, (360/$nbStars)*($cptStars),false);
			$d=3;
			if($s['typeSurcharge']==2){$d=6;}
			if($s['typeSurcharge']=="D" || $s['typeSurcharge']=="D2"){$d=1;}
			
			//dessiner l'étoile:
			imagefilledellipse($image, ($x+$tmp['x']), ($y+$tmp['y']), $d, $d, $hexaByTypeSurcharge[$s['typeSurcharge']]);
			$cptStars++;
		}
			
		if($point['altitude']<=-200){
			//imageline($image, $x, $y, $x, ($y-10), $blanc);
		}else if($point['altitude']>=200){
			//imageline($image, $x, $y, $x, ($y+10), $blanc);
		}
		
	
	}
	imagepng($image,"./views/view_zone.png");
}else{
	imagestring($image, 4, 400, 0, "Waiting for coordinates", $blanc);
}
imagepng($image);

?>