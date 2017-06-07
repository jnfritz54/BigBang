<?php 
namespace BigBang;
require_once('MySQLi_2.php');
require_once('Galaxy_object.php');
	
$mysqli=new MySQLi_2("localhost","root", "root", "perso");

$sqlSystemes="select count(*) as cpt from Systemes;";
$resSys=$mysqli->query($sqlSystemes);

$tmp=$resSys->fetch_assoc();
$cptSystemes=$tmp['cpt'];

$sqlStars="select count(*) as cpt from Stars;";
$resSt=$mysqli->query($sqlStars);
$tmp=$resSt->fetch_assoc();
$cptStars=$tmp['cpt'];

$sqlStars2="select count(*) as cpt from Stars where typeOrigine=typeSurcharge;";
$resSt2=$mysqli->query($sqlStars2);
$tmp=$resSt2->fetch_assoc();
$cptStars2=$tmp['cpt'];

$sqlPlanetes="select count(*) as cpt from Planetes;";
$resPl=$mysqli->query($sqlPlanetes);
$tmp=$resPl->fetch_assoc();
$cptPlanetes=$tmp['cpt'];

//infos versionning 
$commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));

$commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
$commitDate->setTimezone(new \DateTimeZone('UTC'));

$git=sprintf('v %s <br/> du %s',  $commitHash, $commitDate->format('Y-m-d H:m:s'));

?>
<html>
<head>
<title>Index Projet BigBang</title>
<style>
	body{background-color:#101010;color:white;height:100%;}
	a{color:yellow}
	a:hover{color:orange}
	.colone{
		width:30%;
		background-color:#000000;
		border-radius:10px;
		border: 1px solid #101010;
		box-shadow:0px 0px 7px 5px #999999 ;
		margin-left: 1%;
		margin-right: 1%;
		margin-bottom: 2%;
		padding-left:15px;}
	#header{
		background-color:#000000;
		margin: 25px;
		margin-left: 2%;
		margin-right: 2%;
		padding:10px;
		border-radius:10px;
		border: 1px solid #101010;
		box-shadow:0px 0px 7px 5px #999999 ;
		text-align:center
	}
	.footer{
		background-color:#000000;
		margin: 25px;
		margin-left: 2%;
		margin-right: 2%;
		padding:8px;
		border-radius:10px;
		border: 1px solid #101010;
		box-shadow:0px 0px 5px 5px #555555 ;
		text-align:center
	}
	
</style>
</head>
<body>
	<div>
	<div id='header' style=''><h2>Index R&eacute;sum&eacute; du projet BigBang</h2></div>
	<div id='header' style=''>
		<img alt="Side" src="./views/view_side.png" width="300px" height="300px">
		<img alt="Top" src="./views/view_top.png" width="300px" height="300px" >		
		<img alt="Local" src="./views/view_zone.png" width="300px" height="300px">
	</div>
	<div id='content' style='margin:1%'>
		<div id='liens' class="colone" style='float:left;'>
			<h3> Acces aux vues</h3>
			<dl>
				<dt><a href="./viewer.php">Vue galactique</a></dt>
				<dt><a href="./viewer_zone.php">Vue partielle</a></dt>
				<dt><a href="./viewer_system.php">Vue systeme</a></dt>
			</dl>
			
			<h3> Versioning</h3>
			<?php echo $git; ?>
			<br/><br/>
		</div>
		<div id='infos' class="colone" style='float:left;'>
			<h3>Etat actuel de la base de donn&eacute;es:</h3>
			<dl>
				<dt><?php echo $cptSystemes;?> Systemes stellaires</dt>
				<dt><?php echo $cptStars;?> Etoiles</dt>
				<dt><?php echo $cptStars2;?> Etoiles en s&eacute;quence principale</dt>
				<dt><?php echo $cptPlanetes;?> Planetes</dt>
			</dl>
			Soit en moyenne:
			<dl>
				<dt><?php echo ($cptStars/$cptSystemes);?> Etoiles par systeme</dt>
				<dt><?php echo ($cptPlanetes/$cptSystemes);?> Plan&egrave;tes par systeme</dt>
			</dl>
		</div>
		<div id='constantes' class="colone" style='float:left;'>
			<h3>Constantes utilis&eacute;es:</h3>
			Univers:
			<dl>
				<dt>Age de l'univers: <?php echo Universe::$ageOfUniverse; ?> Ma</dt>
				<dt>Constante de gravitation: <?php echo Universe::$G; ?> N.m^2.kg-2</dt>
				<dt>Vitesse de la lumi&egrave;re dans le vide: <?php echo Universe::$c; ?> m/s</dt>
				<dt>Astron: <?php echo Universe::$astron; ?> km</dt>
				
			</dl>
			Galaxie:
			<dl>
				<dt>Nombre de bras spiraux: <?php echo count( galaxy::$bras); ?></dt>
				<dt>Rayon maximal du disque: <?php echo galaxy::$rayonMax ; ?> al</dt>
				<dt>Epaisseur du disque: <?php echo galaxy::$altitudeMax; ?> al </dt>
			</dl>
				
		</div>
		
		<div style="clear:both"></div>
	</div>
	
	</div>
</body>
</html>