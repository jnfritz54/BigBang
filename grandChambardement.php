<?php 
	namespace BigBang;
	
	/******************************************
	 * Rectifie les orbites dans les systemes *
	 * à étoiles multiples                    *
	 ******************************************/
	include 'Star_object.php';
	include 'Systeme_object.php';
	include 'Planete_object.php';
	require_once('MySQLi_2.php');

	$mysqli=new MySQLi_2("localhost","root", "root", "perso");
	
	/***
	 *caching Systemes/stars
	 */
	$sqlCache="Select * from Stars where systeme <100000;";
	$sysStars=array();
	$stars=array();
	$resCache=$mysqli->query($sqlCache);
	while($s=$resCache->fetch_assoc()){
		if(!isset($sysStars[$s['systeme']])){	$sysStars[$s['systeme']]=array();}
		$sysStars[$s['systeme']][]=$s['id'];
		$stars[$s['id']]=$s;
	}
	
	
	/***
	 * caching planetes
	 */
	$planetes=array();
	$sqlCacheP="Select * from Planetes where systeme <100000;";
	$resCacheP=$mysqli->query($sqlCacheP);
	while($p=$resCacheP->fetch_assoc()){
		if(!isset($planetes[$p['systeme']])){$planetes[$p['systeme']]=array();}
		$planetes[$p['systeme']][]=$p;
	}
	/***
	 * 
	 */
	foreach ($sysStars as $sys=> $array){
		if(count($array)<=1 || $array == null){
			foreach ($array as $id){
				unset($stars[$id]);				
			}
			unset($sysStars[$sys]);			
			unset($planetes[$sys]);
		}		
	}	
	
	echo "Cache done\n";

	$sqlDelete="";
	$cptDelete=0;
	$cptUpdate=0;
	foreach ($sysStars as $idSys=>$array){
		if($array==null){ echo "null \n";continue;}
		
		//echo "\n".$idSys;
		$starOrbiteMax=0;
		$starOrbiteMin=9999;
		foreach ($array as $id){
			$s=$stars[$id];
			if($s['distanceBarycentre']>$starOrbiteMax){$starOrbiteMax=$s['distanceBarycentre'];}
			if($s['distanceBarycentre']<$starOrbiteMin){$starOrbiteMin=$s['distanceBarycentre'];}
		}
		
		if(gettype($planetes[$idSys])!="array"){
			var_dump($planetes[$idSys]);
		}
		foreach ($planetes[$idSys] as $p){
			if($p['objectOrbited']==null && $p['distanceEtoile']<$starOrbiteMin){
				//planète à l'intérieur de l'orbite de l'étoile la plus proche du barycentre = nope = couldn't have ever existed = destroy
				$sqlDelete=" delete from Planetes where id=".$p['id'].";";
				$mysqli->query($sqlDelete);
				$cptDelete++;
				//echo " - delete1";
			}elseif($p['objectOrbited']!=null && $p['distanceEtoile']>$starOrbiteMax){
				//planète autour d'une étoile, orbitant plus loin que le barycentre = nope = 
				if( $p['distanceEtoile']>(2*$starOrbiteMax)){
					//orbite stellaire supérieure à l'orbite maximale de l'autre coté du barycentre => transformer en orbite générale
					$sqlUpdate=" update Planetes set objectOrbited=null where id=".$p['id'].";";
					$mysqli->query($sqlUpdate);
					$cptUpdate++;
					//echo "- update";
				}else{
					//couldn't have ever existed = destroy
					$sqlDelete=" delete from Planetes where id=".$p['id'].";";
					$mysqli->query($sqlDelete);
					$cptDelete++;
					//echo " - delete2";
				}
			}else{
				//echo "-rien";
			}
			
		}
		
	}
	
	echo $cptDelete." suppressions et ".$cptUpdate." modifications d'orbite \n";
?>