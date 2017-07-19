<?php 
namespace BigBang;
require_once 'MySQLi_2.php';
require_once 'loader.php';

class Bigbang extends Object {
	
	private $rootDir="";
	private $dbhost="";
	private $dbuser="";
	private $dbpass="";
	private $dbName="";
	
	public function __construct($rootDir="/var/www/",$dbhost="localhost",$dbuser="root",$dbpass='root',$dbName="perso",$verboseMode=1) {
		$this->verbose=$verboseMode;
		$this->dbhost=$dbhost;
		$this->dbuser=$dbuser;
		$this->dbName=$dbName;
		$this->dbpass=$dbpass;
		$this->rootDir=$rootDir;
	}

	
	private function getConnexion(){
		try{
			$mysqli=new MySQLi_2($this->dbhost,$this->dbuser, $this->dbpass,$this->dbName);
			return $mysqli;
		}Catch(Exception $e){
			$this->echoVerbose(0, "Unable to connect to database");
			die;
		}
	}
	
	/**
	 * @todo vérifier l'existance et la base et des tables ou drop et réimport depuis le .sql
	 */
	public function createOrCleanDatabase(){
		$databaseName="perso";
		$mysqli=new MySQLi_2("localhost","root", "root");
		$res=$mysqli->query("SHOW DATABASES LIKE '".$databaseName."';");
		$result=$res->fetch_assoc();
		
		if(!empty($result)){ 
			$this->echoVerbose(1, "Cleaning database from previous executions\n");
			$this->cleanDatabase();
		}else{		
			$this->echoVerbose(1, "Creating database\n");
			$mysqli->query("CREATE DATABASE IF NOT EXISTS ".$databaseName.";");
			$mysqli->select_db($databaseName);
			$mysqli->multi_query(file_get_contents($this->rootDir."bigbang.sql"));			
		}
		return true;
	}
	
	/***
	 * purge database from existing data
	 */
	public function cleanDatabase(){
		$mysqli=$this->getConnexion();
		$mysqli->query('truncate table Stars');
		$mysqli->query('truncate table Systemes');
		$mysqli->query('truncate table Planetes');
		$mysqli->query('truncate table Lifeforms');
		$mysqli->close();
		return true;
	}
	
	/***
	 * peuple la bdd avec des données aléatoires
	 */
	public function generateRaw() {
		$starInsert="insert into Stars values ";
		$starValues=array();
		$sysInsert="insert into Systemes values ";
		$sysValues=array();
		$planeteInsert="insert into Planetes values ";
		$planeteValues=array();
		
		$probaNbEtoiles=array(50=>1,75=>2,95=>3,100=>4);
		
		
		$mysqli=$this->getConnexion();
		
		$i=1;
		$cptStars=0;
		while($i<=1000000){
			$systeme=new Systeme();
			$sysValues[]=$systeme->__toSqlValues();
			$nbStars=1;
			$testProba=rand(1,100);
			foreach ($probaNbEtoiles as $proba=>$nombre){
				if($testProba<=$proba){
					$nbStars=$nombre;break;
				}
			}
			$contractionSysteme=0.5;//maths_service::float_rand(0,1,4); //contraction ou extention aléatoire du systeme
		
			$systemStars=array();
			$stars=array();
		
			for($j=0;$j<$nbStars;$j++){
				$star=new Star($i,$nbStars>1);
					
				$starValues[]=$star->__toSqlValues();
				$cptStars++;
				$systemStars[$j]=$cptStars;
				$stars[$cptStars]=$star;
				if($i%500==0 && !empty($starValues) && !empty($sysValues)){
					$requete=$starInsert.join(", ",$starValues).";";
					$mysqli->query($requete);
					$requete=$sysInsert.join(", ",$sysValues).";";
					$mysqli->query($requete);
					$sysValues=array();$starValues=array();
				}
			}
		
			$nbPlanets=rand(1,15);
			for($h=0;$h<$nbPlanets;$h++){
				$orbited=null;
				$masse=0;
				$rayonnement=0;
				if(count($systemStars)==1){
					//si système unique, l'objet orbité est automatiquement l'étoile
					$orbited=$systemStars[0];
		
					//pas de planète sur les étoiles uniques post-supernova
					//idem pour les disques protoplanétaires qui n'ont pas encore fini leur formation
					if(in_array($stars[$orbited]->typeSurcharge, array(3,4,1)) ){
						$nbPlanets=0;
						break;
					}
					$masse=$stars[$orbited]->masseOrigine;
					$rayonnement=$stars[$orbited]->rayonnement;
				}else{
					//sinon cela peut-être l'une des étoiles ou le centre de gravité du système (null)
					if(!in_array('null', $systemStars)){$systemStars[]='null';}
					$orbited=$systemStars[rand(0,count($systemStars)-1)];
					if($orbited=='null'){
						foreach ($systemStars as $s){
							if($s=='null'){continue;}
							$rayonnement+=$stars[$s]->rayonnement;
						}
		
					}else{
						$rayonnement=$stars[$orbited]->rayonnement;
					}
					foreach ($stars as $s){
						$masse+=$s->masseOrigine;
					}
				}
				//echo $i." ".$orbited."\n";
				$planete=new Planete($i,$orbited,$masse,$rayonnement,$h,$contractionSysteme);
				$planeteValues[]=$planete->__toSqlValues();
			}
		
			if(count($planeteValues)>900){
				$requeteP=$planeteInsert.join(", ",$planeteValues).";";
				$mysqli->query($requeteP);
				$planeteValues=array();
			}
		
			$i++;
		}
		if(!empty($starValues)){
			$requete=$starInsert.join(",",$starValues);
			$mysqli->query($requete);
			if(!empty($sysValues)){
				$requete=$sysInsert.join(", ",$sysValues).";";
				$mysqli->query($requete);
			}
		}
		if(!empty($planeteValues)){
			$requete=$planeteInsert.join(", ",$planeteValues).";";
			$mysqli->query($requete);
		}
		$this->echoVerbose(1,"Raw data generated\n");
		return true;
	}

	/******************************************
	 * Rectifie les orbites dans les systemes *
	 * à étoiles multiples                    *
	 *****************************************/
	public function grandChambardement(){
		$mysqli=$this->getConnexion();
		$cptDelete=0;
		$cptUpdate=0;
		
		//on va devoir fonctionner par tranches de 100-200 000 systemes
		//si on essaye de prendre le million on mange un kill-9 systeme
		$debut=0;
		$pas=100000;
		
		$sqlCountSys="select id from Systemes order by id DESC limit 1;";
		$resCountSys=$mysqli->query($sqlCountSys);
		$sys=$resCountSys->fetch_assoc();
		$fin=$sys['id'];
		
		while($debut<$fin){
			$limit=$debut+$pas;
			$this->echoVerbose(3, $debut." => ".$limit."\n");
			/***
			 *caching Systemes/stars
			 */
			$sqlCache="Select * from Stars where systeme>='".$debut."' and systeme<'".$limit."';";
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
			$sqlCacheP="Select * from Planetes where systeme>='".$debut."' and systeme<'".$limit."';";
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
		
			$this->echoVerbose(3,"Cache done\n");
		
			$sqlDelete="";
		
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
					}
						
				}
		
			}
			$debut+=$pas;
		}
		$this->echoVerbose(1,$cptDelete." suppressions et ".$cptUpdate." modifications d'orbite \n");
		$mysqli->close();
		return true;
	}

	/**
	 * Détermine la température de semi-corps noir des planètes	 
	 */
	public function calculTemperaturePlanete(){
		$mysqli=$this->getConnexion();
		//on va devoir fonctionner par tranches de 100-200 000 systemes
		//si on essaye de prendre le million on mange un kill-9 systeme
		$debut=0;
		$pas=100000;
		
		$sqlResetEden="Update Planetes set eden=0 where eden=1;";
		$resResetEden=$mysqli->query($sqlResetEden);
		
		$sqlCountSys="select id from Systemes order by id DESC limit 1;";
		$resCountSys=$mysqli->query($sqlCountSys);
		$sys=$resCountSys->fetch_assoc();
		$fin=$sys['id'];
		
		while($debut<$fin){
			$limit=$debut+$pas;
			$this->echoVerbose(2, $debut." => ".$limit."\n");
		
			$sqlStars="select s.* from Stars s where  s.systeme>='".$debut."' and s.systeme<'".$limit."';";
			$Stars=array();
			$resS=$mysqli->query($sqlStars);
			while($rowS=$resS->fetch_assoc()){
				$Stars[$rowS['id']]=$rowS;
			}
			/***
			 $this->_calculTemperaturePlanete_sub(); //valeurs par défaut pour la terre
			 $this-> _calculTemperaturePlanete_sub(81472,1660945,2.00865e23); //valeurs pour le systeme TRAPPIST-1
			 */
		
		
			//pour gagner du temps on ne compte pas les géantes gazeuses sans satellites,
			//ni les planétoides trop petits pour avoir une atmosphère, ni les ceintures d'astéroides
			$sql="Select p.* from Planetes p, Systemes sy where sy.id=p.systeme and sy.distance>'".Galaxy::$rayonMin."' and p.systeme>='".$debut."' and p.systeme<'".$limit."' and p.objectOrbited is not null and (type not in ('A','P','G') or (type='G' and (particularite='m' or particularite='Mm')) )";
			$res=$mysqli->query($sql);
			$cpt=0; //mondes survivables
			$cptHab=0; //mondes habitables
			$cptEden=0; //mondes habitables
		
			while($row=$res->fetch_assoc()){
				$eden=0;
				//on ne compte que celles qui sont en séquence principale, celles en formation n'ont pas encore de planètes créées, et celles qui ont dépassé
				//ce stade ne peuvent plus abriter la vie
				if($Stars[$row['objectOrbited']]['typeSurcharge']!=$Stars[$row['objectOrbited']]['typeOrigine']){continue;}
		
				$rayonKm=bcdiv($Stars[$row['objectOrbited']]['rayon'],1000);
				$tempC=$this->_calculTemperaturePlanete_sub($rayonKm,$row['distanceEtoile']*Universe::$astron,$Stars[$row['objectOrbited']]['rayonnement'],$row['albedo']);
		
				if($tempC>=-30 && $tempC<20 ){
					//planète relativement tempérée
					//echo "Système: ".$row['systeme']." Planète: ".$row['id']." => ".$tempC."°C ( Age: ".$Stars[$row['objectOrbited']]['age'].")\n";
					$cpt++;
					if($tempC>=-20 && $tempC<10 && $Stars[$row['objectOrbited']]['age']>=1){
						//planète tempérée suffisement agée pour voir des formes de vie se dévelloper
						//echo "Système: ".$row['systeme']." Planète eden: ".$row['id']." => ".$tempC."°C ( Age: ".$Stars[$row['objectOrbited']]['age'].") \n";
						$cptHab++;
						if($Stars[$row['objectOrbited']]['age']>=2 && $row['masse']<5
								&& ($row['particularite']=="m" || $row['particularite']=="Mm")){
									//planète tempérée suffisement agée et stable pour voir des formes de vie et un écosysteme complexe
									$cptEden++;
									$eden=1;
									$sqlupdate="Update Planetes set rayonnement='".$tempC."', eden=1 where id='".$row['id']."';";
						}else{
							$sqlupdate="Update Planetes set rayonnement='".$tempC."', eden=0 where id='".$row['id']."';";
						}
					}else{
						$sqlupdate="Update Planetes set rayonnement='".$tempC."', eden=0 where id='".$row['id']."';";
					}
				}else{
					$sqlupdate="Update Planetes set rayonnement='".$tempC."', eden=0 where id='".$row['id']."';";
				}
				//$sqlupdate="Update Planetes set rayonnement='".$tempC."', eden='".$eden."' where id='".$row['id']."';";
				$resUpdate=$mysqli->query($sqlupdate);
		
			}
			$debut+=$pas;
		}
		/*
		 $sql="Select count(*) as nullOrbit from Planetes where objectOrbited is null";
		 $res=$mysqli->query($sql);
		 $row=$res->fetch_assoc();
		 var_dump($row);die;*/
		$mysqli->close();
		
		$this->echoVerbose(1, "\n".$cpt." planètes potentiellement survivables");
		$this->echoVerbose(1, "\ndont ".$cptHab." planètes potentiellement habitables (anciennement eden)");
		$this->echoVerbose(1, "\ndont ".$cptEden." planètes stabilisées potentiellement habitables avec biotope\n");
	}
	
	/***
	 * calcule la température de surface d'une planète selon son orbite, albédo et étoile mère
	 * $this->_calculTemperaturePlanete_sub(); //valeurs par défaut pour la terre
	 * $this-> _calculTemperaturePlanete_sub(81472,1660945,2.00865e23); //valeurs pour le systeme TRAPPIST-1
	 * @param number $rayonEtoile
	 * @param number $orbitePlanete
	 * @param real $rayonnementEtoileBrut
	 * @param real $albedo
	 */
	private function _calculTemperaturePlanete_sub($rayonEtoile=696342,$orbitePlanete=149500000,$rayonnementEtoileBrut=3.826E26,$albedo=0.29){
		$distance= $rayonEtoile+$orbitePlanete;
		$distance=$distance*1000;
		$sphere=4*pow($distance,2);
		
		$surface=maths_service::exp2int($sphere*pi());
		//echo "surface: ".$surface."m² // ";
		$production=maths_service::exp2int($rayonnementEtoileBrut); //watts
		
		//echo "production: ".$production."W // ";
		$Io=bcdiv($production,$surface,4); //en watts/m² irradiation solaire rayonnementau niveau de l'orbite terrestre
		//echo "rayonnement: ".$Io."W/m² "; //
		
		
		//dernière equation de la page:
		//https://fr.wikipedia.org/wiki/Temp%C3%A9rature_d%27%C3%A9quilibre_%C3%A0_la_surface_d%27une_plan%C3%A8te#Calcul_de_la_temp.C3.A9rature_de_corps_noir
		$dividende=bcmul($Io,(1-$albedo),5);
		$diviseur=bcmul(4,maths_service::exp2int(Universe::$StefanBoltzmann),10);
		$division=bcdiv($dividende,$diviseur);
		$Teq=pow($division,0.25); //Température d'équilibre de corps noir en kelvins
		
		//echo " Teq: ".$Teq."\n";
		return round(maths_service::kelvin2celsius($Teq),3);
	}

	/***
	 * calcul l'impact possible des explosions de novas sur les planètes désignées habitables
	 * @todo: trouver un moyen d'accélérer ca
	 * @throws \Exception
	 */
	public function calculNovas(){
		throw new \Exception("Cannot be launched unless 10h of execution is acceptable");
	}


	/**
	 * Lance la génération et enregistrement des formes de vies
	 */
	public function generateLifeforms(){
		$mysqli=$this->getConnexion();
		
		$sqlInsert="Insert into Lifeforms values ";
		$sqlValues=array();
		$cptInsert=0;
		
		//planètes habitables à traiter:
		$sqlHab="Select p.* from Planetes p , Systemes sy where sy.distance>'".Galaxy::$rayonMin."' and p.systeme =sy.id and eden=1";
		$resHab=$mysqli->query($sqlHab);
		
		while($habPlanet=$resHab->fetch_assoc()){
			$life=new LifeForm($habPlanet['id'],$habPlanet['systeme']);
			$sqlValues[]=$life->__toSqlValues();
			$cptInsert++;
		
		}
		
		$requete=$sqlInsert.join(", ",$sqlValues).";";
		$mysqli->query($requete);
		
		echo $cptInsert." formes de vie créées\n";
		$mysqli->close();
		return true;
	}
}

?>