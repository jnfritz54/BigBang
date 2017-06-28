<?php 
namespace BigBang;
require_once('MySQLi_2.php');
require_once('loader.php');
	
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

$sqlLifeforms="select count(*) as cpt from Lifeforms;";
$resLf=$mysqli->query($sqlLifeforms);
$tmp=$resLf->fetch_assoc();
$cptLifeForms=$tmp['cpt'];

$sqlLifeforms2="select count(*) as cpt from Lifeforms where avancement>=5;";
$resLf2=$mysqli->query($sqlLifeforms2);
$tmp=$resLf2->fetch_assoc();
$cptLifeForms2=$tmp['cpt'];

//infos versionning 
$commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));

$commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
$commitDate->setTimezone(new \DateTimeZone('UTC'));

$git=sprintf('v %s <br/> du %s',  $commitHash, $commitDate->format('Y-m-d H:m:s'));

$sql="select * from Lifeforms where 1 limit 1";
$res=$mysqli->query($sql);
$row=$res->fetch_assoc();
/***
$lf=new LifeForm();
$lf->__loadFromSqlRow($row);

echo $lf->__toHtml();
*/
?>
<html>
<head>
<title>Index Projet BigBang</title>
<style>
	body{background-color:#101010;color:white;height:100%;}
	a{color:yellow}
	a:hover{color:orange}
	
	#content{
		display:table;
		table-layout: auto;
		border-collapse: separate;
		border-spacing:20px 10px;
		width:100%;
	}
	.row{
		display:table;
		table-layout: auto;
		border-collapse: separate;
		border-spacing:20px 10px;
		width:100%;
	}
	.box{		
		display:table-cell;
		background-color:#000000;
		border-radius:10px;
		border: 1px solid #101010;
		box-shadow:0px 0px 7px 5px #999999 ;
		padding:15px;
	}
	.header{
		width:100%;		
		text-align:center;
	}
	.colone{		
		/*display:table-cell;*/	
		margin:15px;
	}

	
</style>
</head>
<body>
	<div id="content">
	<div class='row'>
		<div class='header box'>
			<h2>Index R&eacute;sum&eacute; du projet BigBang</h2>
		</div>
	</div>
	<div class='row'>
		<div class="header box" >
			<img alt="Side" src="./views/img/view_side.png" width="300px" height="300px">
			<img alt="Top" src="./views/img/view_top.png" width="300px" height="300px" >
			<!-- <img alt="Mixed" src="./views/img/view_mixed.png" width="300px" height="300px" > -->
			<img alt="Lifeform" src="./views/img/view_lifeforms.png" width="300px" height="300px" >		
			<img alt="Local" src="./views/img/view_zone.png" width="300px" height="300px">
		</div>
	</div>
	<div class='row'>
		<div id='liens' class="colone box">
			<h3> Acces aux vues</h3>
			<dl>
				<dt><a href="./views/viewer.html" target="_blank">Vue galactique</a></dt>
				<dt><a href="./views/viewer_lifeforms.html" target="_blank">Vue vie</a></dt>
				<dt><a href="./views/viewer_zone.php" target="_blank">Vue partielle</a></dt>
				<dt><a href="./views/viewer_system.php" target="_blank">Vue systeme</a></dt>
			</dl>
			
			<h3> Documentation</h3>
			<dl>
				<dt><a href="./README.md" target="_blank">Readme</a></dt>
				<dt><a href="./LICENSE" target="_blank">License</a></dt>
				<dt><a href="./docs/echelle_civilisations.ods" target="_blank">Classification des civilisations </a></dt>				
			</dl>
			
			<h3> Versioning</h3>
			<?php echo $git; ?><br/>
			<a href="https://github.com/MilamberRedux/BigBang" target="_blank">Voir sur Github</a>
			<br/><br/>
		</div>
		<div id='infos' class="box colone ">
			<h3>Etat actuel de la base de donn&eacute;es:</h3>
			<dl>
				<dt><?php echo strings_service::numberFormat($cptSystemes,0);?> Systemes stellaires</dt>
				<dt><?php echo strings_service::numberFormat($cptStars,0);?> Etoiles</dt>
				<dt><?php echo strings_service::numberFormat($cptStars2,0);?> Etoiles en s&eacute;quence principale</dt>
				<dt><?php echo strings_service::numberFormat($cptPlanetes,0);?> Planetes</dt>
				<dt><?php echo strings_service::numberFormat($cptLifeForms,0);?> Formes de vie</dt>
				<dt><?php echo strings_service::numberFormat($cptLifeForms2,0);?> Formes de vie de stade 5 et plus</dt>
			</dl>
			Soit en moyenne:
			<dl>
				<dt><?php echo strings_service::numberFormat($cptStars/$cptSystemes);?> Etoiles par systeme</dt>
				<dt><?php echo strings_service::numberFormat($cptPlanetes/$cptSystemes);?> Plan&egrave;tes par systeme</dt>
				<dt>Une forme de vie pour <?php echo strings_service::numberFormat($cptSystemes/$cptLifeForms,2);?> systemes</dt>
			</dl>
		</div>
		<div id='constantes' class=" box colone">
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
				<dt>Nombre de bras spiraux: <?php echo count( Galaxy::$bras); ?></dt>
				<dt>Rayon maximal du disque: <?php echo Galaxy::$rayonMax ; ?> al</dt>
				<dt>Epaisseur du disque: <?php echo Galaxy::$altitudeMax; ?> al </dt>
			</dl>
				
		</div>
	</div>
	
	</div>
</body>
</html>