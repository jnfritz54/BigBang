<?php 
namespace BigBang;

require_once('MySQLi_2.php');

$mysqli=new MySQLi_2("localhost","root", "root", "perso");

$result=array();
$offset=0;
if(isset($_POST)){
	if(isset($_POST['offset']) && $_POST['offset']!=0){$offset=intval($_POST['offset']);}
	
	//mettre en cache les étoiles par système:
	$query="select * from Stars where systeme>".$offset." order by Id limit 100000;";
	$res=$mysqli->query($query);
	
	$query="select * from Systemes where id>".$offset." order by Id limit 100000;";
	$res=$mysqli->query($query);
	$cpt=0;
	while($row=$res->fetch_assoc()){
		$tmp=polaireToCartesien($row['distance'],$row['angle']);
		$tmp['x']=intval($tmp['x']/(50000/600));
		//$tmp['x']=intval($tmp['x']/2);
		$tmp['y']=intval($tmp['y']/(50000/600));
		//$tmp['y']=intval($tmp['y']/2);
		$result[]=$tmp;
		$cpt++;
	}
}
header('Content-Type: application/json');
echo json_encode($result);

function polaireToCartesien($rayon,$angle){
	$x=$rayon*cos(deg2rad($angle));
	$y=$rayon*sin(deg2rad($angle));

	return array("x"=>$x,"y"=>$y);
}
?>