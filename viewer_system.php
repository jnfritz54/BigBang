<?php
namespace BigBang;
require_once('MySQLi_2.php');
include 'Universe_object.php';
include 'Star_object.php';

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
$sqlPlanetes="select * from Planetes where systeme=".$idSysteme.";";

$resStar=$mysqli->query($sqlStars);
$star=$resStar->fetch_assoc();

$rayonAs=$star['rayon']/Universe::$astron;

$resStar=$mysqli->query($sqlStars);
$star=$resStar->fetch_assoc();
$ratio=1500/600;
$string.="ctx.fillStyle = '".$hexaByTypeSurcharge[$star['typeSurcharge']]."';
	ctx.beginPath();
  ctx.lineWidth='2';
  ctx.arc(600, 600, ".($rayonAs/($ratio/3)).", 0, 2 * Math.PI);
  ctx.fill();
	ctx.fillStyle = '#00FF00';";

$resPlanetes=$mysqli->query($sqlPlanetes);
while($planete=$resPlanetes->fetch_assoc()){
	
	$rayonTotal=bcdiv($star['rayon'],Universe::$astron)+$planete['distanceEtoile'];
	//cercle de l'orbite
	$string.="ctx.beginPath();
	  ctx.lineWidth='1';
	  ctx.arc(600,600,".($rayonTotal/$ratio).", 0, 2 * Math.PI);
	  ctx.stroke();
	  ctx.beginPath();
	  ctx.arc(".(($rayonTotal/$ratio)+600).",600, 1, 0, 2 * Math.PI);
	  ctx.fill();		
	  ";
}


$string.="}
</script>
</body></html>";
echo $string;
?>
