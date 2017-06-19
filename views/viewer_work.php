<?php
namespace BigBang;
require_once('../MySQLi_2.php');
require('../loader.php');
header("Content-type: image/png");
$image= imagecreate(800, 800);

$noir = imagecolorallocate($image, 0, 0, 0);
$orange = imagecolorallocate($image, 255, 128, 0);
$jaune = imagecolorallocate($image, 220, 200, 20);
$bleu = imagecolorallocate($image, 0, 0, 255);
$bleuclair = imagecolorallocate($image, 156, 227, 254);
$blanc = imagecolorallocate($image, 255, 255, 255);
$blancAlphaMoins=imagecolorallocatealpha($image, 238, 255,238 ,100);

$mysqli=new MySQLi_2("localhost","root", "root", "perso");

$pas=100000;
$result=array();
$offset=0;
while($offset<1000000){

	//mettre en cache les étoiles par système:
	//$query="select * from Stars where systeme>".$offset." order by Id limit 100000;";
	//$res=$mysqli->query($query);
	
	$query="select * from Systemes where id>".$offset." and id <=".($offset+$pas)." order by Id limit ".$pas.";";
	$res=$mysqli->query($query);
	$cpt=0;
	while($row=$res->fetch_assoc()){
		$tmp=polaireToCartesien($row['distance'],$row['angle']);
		
		$tmp['x']=intval($tmp['x']/(50000/400));		
		$tmp['y']=intval($tmp['y']/(50000/400));
		$tmp['z']=intval($row['altitude']/(50000/400));
		if($_GET['view']=='top'){
			imagesetpixel($image, ($tmp['x']+400),($tmp['y']+400), $jaune);
		}elseif($_GET['view']=='side'){
			imagesetpixel($image, ($tmp['y']+400),($tmp['z']+400), $orange);
		}
		
		$cpt++;
	}
	$offset+=$pas;
}
imagepng($image);
imagepng($image,"./img/view_".$_GET['view'].".png");

function polaireToCartesien($rayon,$angle){
	$x=$rayon*cos(deg2rad($angle));
	$y=$rayon*sin(deg2rad($angle));

	return array("x"=>$x,"y"=>$y);
}
?>