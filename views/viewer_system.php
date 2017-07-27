<?php
namespace BigBang;
require_once('../MySQLi_2.php');
require('../loader.php');

	$id=1;
	if(!empty($_POST)){
		$id=urlencode($_POST['id']);	
		if($id!=intval($id)){ die('bad parameter');}		
	}

	$mysqli=new MySQLi_2("localhost","root", "root", "perso");
	$idSysteme=$id;
	
	//récupération info système
	$sqlSysteme="select * from Systemes where id='".$idSysteme."';";
	$resSys=$mysqli->query($sqlSysteme);
	$sysRow=$resSys->fetch_assoc();
	$sys=new Systeme();
	$sys->__loadFromSqlRow($sysRow);
	
	?>
<html>
<head>
<title>D&eacute;tails syst&egrave;me stellaire</title>
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
			<h3><?php echo $sys->__toHtml(); ?></h3>
		</div>
	</div>
	<div class='row'>
		<div class='box'>
			<form method='POST' id='form'>
				Id systeme: <input type='text' name='id' value='<?php echo $id;?>' size="5"/>
				<input type='submit' name='ok' value='Go'>
			</form>
			<a href="./viewer_system_work.php?id=<?php echo $id; ?>" target='_blank'>
				<img id="screen" src='./viewer_system_work.php?id=<?php echo $id; ?>' width="600px" height="600px"/>
			</a>
		</div>
		<div class='box'>
		<?php 
		
		//récupération infos étoiles
		$sqlStar="select * from Stars where systeme='".$idSysteme."';";
		$resStar=$mysqli->query($sqlStar);
		while($starRow=$resStar->fetch_assoc()){
			$star=new Star();
			$star->__loadFromSqlRow($starRow);
			
			
			echo "<div class='row'><div class='box'><table><thead><tr><th></th><th>Etoile ".$star->__toString()."</th></tr></thead>";
			//récupération infos planètes orbitant l'étoile
			$sqlPlanet="select * from Planetes where systeme='".$idSysteme."' and objectOrbited= '".$starRow['id']."';";
			$resPlanet=$mysqli->query($sqlPlanet);
			while($PlanetRow=$resPlanet->fetch_assoc()){
				$Planet=new Planete(1, 1, 1, 1, 1);
				$Planet->__loadFromSqlRow($PlanetRow);
			
				echo "<tr><td style='background-color:".$Planet->getColor()."'>&nbsp;&nbsp;</td><td>".$Planet->__toHtml()."</td></tr>";
			}
			echo "</table></div></div>";
		}
		
		//récupération infos planètes orbitant le systeme
		echo "<div class='row'><div class='box'><table><thead><tr><th></th><th>Barycentre syst&egrave;me</th></tr></thead>";
		
		$sqlPlanet="select * from Planetes where systeme='".$idSysteme."' and objectOrbited is null;";
		$resPlanet=$mysqli->query($sqlPlanet);

		while($PlanetRow=$resPlanet->fetch_assoc()){
			$Planet=new Planete(1, 1, 1, 1, 1);
			$Planet->__loadFromSqlRow($PlanetRow);
			
			echo "<tr><td style='background-color:".$Planet->getColor()."'>&nbsp;&nbsp;</td><td>".$Planet->__toHtml()."</td></tr>";
		
		}
		echo "</table></div></div>";
?>
	</div>
	</div>
	</div>
</body>
</html>