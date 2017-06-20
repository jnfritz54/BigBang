<?php
namespace BigBang;
require_once('../MySQLi_2.php');
require('../loader.php');
header("Content-type: image/png");
$image= imagecreate(800, 800);

$noir = imagecolorallocate($image, 0, 0, 0);

$orange = imagecolorallocate($image, 255, 128, 0);
$vert = imagecolorallocate($image, 25, 220, 20);
$jaune = imagecolorallocate($image, 255, 220, 20);
$bleu = imagecolorallocate($image, 0, 0, 255);
$bleuclair = imagecolorallocate($image, 156, 227, 254);
$blanc = imagecolorallocate($image, 255, 255, 255);
$blancAlphaMoins=imagecolorallocatealpha($image, 238, 255,238 ,100);

imagecolortransparent($image, $noir);

$mysqli=new MySQLi_2("localhost","root", "root", "perso");

$pas=100000;
$result=array();
$offset=0;
	//mettre en cache les étoiles par système:
	//$query="select * from Stars where systeme>".$offset." order by Id limit 100000;";
	//$res=$mysqli->query($query);
	
	$query="select * from Systemes where id in (Select originSystem from Lifeforms where avancement >=5) order by Id;";
	$res=$mysqli->query($query);
	$cpt=0;
	while($row=$res->fetch_assoc()){
		$tmp=polaireToCartesien($row['distance'],$row['angle']);
		
		$tmp['x']=intval($tmp['x']/(50000/400));		
		$tmp['y']=intval($tmp['y']/(50000/400));
		$tmp['z']=intval($row['altitude']/(50000/400));

		//imagesetpixel($image, ($tmp['x']+400),($tmp['y']+400), $vert);
		imagefilledellipse($image,(400+$tmp['x']), (400+$tmp['y']), 4,4, $vert);

		$cpt++;
	}
imagepng($image);
imagepng($image,"./img/view_lifeforms.png");

if(file_exists('./img/view_top.png')){
	$baseImage = imagecreatefrompng('./img/view_top.png');
	imagecolortransparent($baseImage, $noir);
	imagecopymerge($baseImage, $image, 0, 0, 0, 0, 800, 800, 100);
	imagepng($baseImage,"./img/view_mixed.png");
	
	
}

function polaireToCartesien($rayon,$angle){
	$x=$rayon*cos(deg2rad($angle));
	$y=$rayon*sin(deg2rad($angle));

	return array("x"=>$x,"y"=>$y);
}
?>