<?php
namespace BigBang;
require_once('MySQLi_2.php');
include 'Universe_object.php';
include 'Star_object.php';
require('./maths_service.php');

$hexaByTypeSurcharge=array('1'=>"#655555",'2'=>"#FF2020", '3'=>"#DD00DD","4"=>"#000000",
		'L'=>"#201020",'D'=>"#EEFFEE",'D2'=>"#EEFFEE",'M'=>"#FF2020",
		'K'=>"#FF9000",'G'=>'#EEEE00','F'=>"#FF9300",'A'=>"#FFFFFF",'B'=>"#AECBE1",'O'=>"#7BA7F3");

$mysqli=new MySQLi_2("localhost","root", "root", "perso");
$string="<html><head></head>
<body style='background-color:#000000'>
<button onclick='drawIt();'>Test</button><br/>
<canvas id='screen' width='1200px' height='1200px' style='border:1px solid #000000;'>
</canvas>

<script type='text/javascript'>

function drawIt (){
	var canvas = document.getElementById('screen');
	var canvasWidth = canvas.width;
	var canvasHeight = canvas.height;
	var ctx = canvas.getContext('2d');
	ctx.fillStyle = '#000000';
	ctx.strokeStyle='#909090';
	ctx.fillRect(0, 0, canvasHeight, canvasWidth);
";

$result=array('S'=>array(),'P'=>array());
if(isset($_GET['id'])){
	$idSysteme=intval($_GET['id']);
}else{
	$idSysteme=0;
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
	$string.="ctx.beginPath();
	  ctx.lineWidth='1';
	  ctx.arc(600,600,".($stars[$key]['distance']*$ratio).", 0, 2 * Math.PI);
	  ctx.stroke();
	  ctx.beginPath();
	  ctx.fillStyle = '".$stars[$key]['couleur']."';
	  ctx.arc(".(600+$tmp['x']).",".(600+$tmp['y']).", 3, 0, 2 * Math.PI);
	  ctx.fill();		
	  ";
	$index++;
}


$sqlPlanetes="select * from Planetes where systeme=".$idSysteme.";";

$string.="ctx.fillStyle='#10EE10';";
		
$resPlanetes=$mysqli->query($sqlPlanetes);
while($planete=$resPlanetes->fetch_assoc()){
	
	if($planete['objectOrbited']==null){
		$star=array('rayonAs'=>0);
		$fillerX=0;$fillerY=0;
	}else{
		$star=$stars[$planete['objectOrbited']];
		$fillerX=$star['x'];$fillerY=$star['y'];
	}
	$rayonTotal=bcdiv($star['rayonAs'],Universe::$astron)+$planete['distanceEtoile'];
	//cercle de l'orbite
	$string.="ctx.beginPath();
	  ctx.lineWidth='1';
	  ctx.arc(".(600+$fillerX).",".(600+$fillerY).",".($rayonTotal/$ratio).", 0, 2 * Math.PI);
	  ctx.stroke();
	  ctx.beginPath();
	  ctx.arc(".(($rayonTotal/$ratio)+600+$fillerX).",".(600+$fillerY).", 1, 0, 2 * Math.PI);
	  ctx.fill();		
	  ";
}


$string.="}
</script>
</body></html>";
echo $string;
?>
