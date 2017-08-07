<?php
namespace BigBang;

class Planete extends Object{
	
	/**
	 * code et texte des types de planètes
	 * @var array
	 */
	public $naturePlanete=array('T'=>'tellurique','G'=> 'gazeuse',
			'C'=>'chtonienne','A'=>'ceinture asteroides',"B"=>'naine brune','P'=>"Planetoide rocheux");
	/**
	 * code couleur des types de planètes
	 * @var array
	 */
	public $codeCouleurType=array('T'=>'green','G'=> 'orange',
			'C'=>'purple','A'=>'lightgrey',"B"=>'brown','P'=>"grey");
	
	/**
	 * répartition statistique des types de planètes générées
	 * @var array
	 */
	private $probaNature=array(
		35=>'T',
		70=>'G',
		80=>'A',
		85=>'C',
		90=>'B',
		100=>'P'
	);
	
	/**
	 * Sous-types de planètes disponibles par type
	 * @var array
	 */
	private $sousCategorieList=array(
			"T"=>array("sterile et/ou sans atmosphere","désertique","aride","earth-like","à dominante aquatique","planète ocean"),
			"G"=>array("Hydrogène","Helium","Methane"),
			"C"=>array("Standard"),
			"A"=>array("Dense","dispersee","principalement glacière"),
			"B"=>array("Standard","Sous-naine brune"),
			"P"=>array("Standard"),
	);
	
	/**
	 * hydrométrie nécessaire pour qu'une planète tellurique soit dans une sous-catégorie donnée
	 * ex: 3=>0 signifie qu'en dessous d'une hydrométrie de 3 ( <=) une planète aura le sous-type 0
	 * @var array
	 */
	private $sousCategorieTbyHydro=array(
		3=>0,
		10=>1,
		17=>2,
		29=>3,
		35=>4,
		65=>5,
	);
	
	/**
	 * échelle des masses possibles pour une planète selon son type, exprimées en masses terrestres
	 * @var array
	 */
	private $massesParType=array(
		'T'=>array("min"=>0.3,"max"=>25),
		'G'=>array("min"=>25,"max"=>1250),
		'C'=>array("min"=>0.5,"max"=>20),
		'A'=>array("min"=>0.001,"max"=>0.1),
		'B'=>array("min"=>4000,"max"=>23775),
		'P'=>array("min"=>0.01,"max"=>0.3),
	);
	
	/**
	 * échelle des albédo possibles pour une planète selon son type (indice de réfraction entre 0 et 1)
	 * @var array
	 */
	private $albedoParType=array(
		'T'=>array("min"=>0.1,"max"=>1),
		'G'=>array("min"=>0.3,"max"=>0.9),
		'C'=>array("min"=>0.1,"max"=>0.5),
		'A'=>array("min"=>0.05,"max"=>0.3),
		'B'=>array("min"=>0.1,"max"=>0.5),
		'P'=>array("min"=>0.05,"max"=>0.3),
	);
	
	/**
	 * Particularités possibles pour des planètes
	 * @var array
	 */
	private $particularitePlanete=array('0'=>"nothing",'M'=>'moon','Mm'=>'multiples moons','R'=>'rings','F'=>'debris field');
	
	/**
	 * Particularités possibles pour des planètes
	 * @var array
	 */
	private $particulariteProba=array(50=>"0",70=>'M',80=>'Mm',90=>'R',100=>'F');
			
	/**
	 * id du système parent
	 * @var integer
	 */
	public $systeme;
	
	/**
	 * id de l'étoile orbitée, vaut null si système binaire/multiple et si la planète orbite autour du barycentre
	 * du système plutôt que d'un objet particulier
	 * @var integer
	 */
	public $objectOrbited;	
	
	/**
	 * Code du type de la planète
	 * @var string
	 */
	public $type;
	
	/**
	 * Code du sous-type de la planète
	 * @var integer
	 */
	public $sousType;
	
	/**
	 * masse planétaire exprimée en masses terrestres
	 * (une masse en unitées SI nécessite une notation scientifique qui complique les calculs inutilement) 
	 * @var float
	 */
	public $masse;
	
	/**
	 * code de l'éventuelle particularité de la planète
	 * @var string
	 */
	public $particularite;
	
	/**
	 * distance de l'objet orbité, étoile ou barycentre
	 * exprimée en astrons (1as=300 000 000km)
	 * @var float
	 */
	public $distanceEtoile; 
	
	/**
	 * inclinaison orbitale selon un angle en degrées
	 * @var float
	 */
	public $inclinaisonOrbite;
	
	/**
	 * albédo de la planète (réfraction) entre 0 et 1
	 * @var float
	 */
	public $albedo;
	
	/**
	 * vitesse de la planète sur son orbite
	 * @var float
	 */
	public $vitesseOrbitale;
	
	public $dureeAnnee;
	
	public $dureeJour;
	
	/**
	 * rayonnement reçu à la surface en w/m²
	 * @var float
	 */
	public $rayonnement; 
	
	/**
	 * pression atmosphérique en Bar
	 * @var float
	 */
	public $atmosphere; 
	
	/**
	 * quantité d'eau sur la planète exprimée en milième de pourcentage de sa masse (terre = 0.023% =23)
	 * @var float
	 */
	public $hydrometrie=null;
	
	/**
	 * tag facilitant les recherches, après le calcul des températures planétaires un monde très propice à la vie est tagué "Eden"
	 * @var boolean
	 */
	public $eden=0;

	/**
	 * constructeur
	 * @param integger $systeme
	 * @param integer $idObjetOrbited
	 * @param float $masseObjetOrbited
	 * @param float $rayonnement
	 * @param integer $rangPlanete
	 * @param real $contractionSysteme
	 */
	public function __construct($systeme,$idObjetOrbited,$masseObjetOrbited,$rayonnement,$rangPlanete,$contractionSysteme=0.5){
		
		$type=maths_service::float_rand(0, count($this->naturePlanete)-1);
		$arr=array_keys($this->naturePlanete);
		$this->type=$arr[$type];
		
		
		if(count($this->sousCategorieList[$this->type])==1){
			$this->sousType=0;
		}elseif($this->type=='T'){
			
			$this->hydrometrie=rand(0,70);
			
			foreach ($this->sousCategorieTbyHydro as $range => $subTypeIndex){
				if($this->hydrometrie<=$range){
					$this->sousType=$subTypeIndex;break;
				}else{
					continue;
				}
			}
		}else{
			$this->sousType= rand(0,count($this->sousCategorieList[$this->type])-1);
		}
		
		//$this->distanceEtoile=maths_service::probaDescendanteLineaire(0.05, 1500,4);
		//$this->distanceEtoile=maths_service::planetesLoiTitusBode($rangPlanete);
		$this->distanceEtoile=maths_service::planetesLoiTitusBodeExtanded($rangPlanete,$masseObjetOrbited,$contractionSysteme,false);
		//echo $this->distanceEtoile."\n";
		$this->inclinaisonOrbite=maths_service::probaDescendante(0, 20);
		
		$this->masse=maths_service::float_rand($this->massesParType[$this->type]['min'], $this->massesParType[$this->type]['max']);
		
		//détermination de sa particularité
		$proba=rand(0,100);
		foreach ($this->particulariteProba as $range => $class){
			if($proba<=$range){
				$this->particularite=$class;break;
			}else{
				continue;
			}
		}
		$this->simplifiedCircularOrbit($masseObjetOrbited);
		$this->systeme=$systeme;
		$this->objectOrbited=$idObjetOrbited;
		$this->albedo=maths_service::float_rand($this->albedoParType[$this->type]['min'], $this->albedoParType[$this->type]['max']);
		
		$this->rayonnement=bcdiv($rayonnement,bcmul($this->distanceEtoile,4*pi()),4);
	}

	/**
	 * surcharge pour afficher un nombre d'infos limitées
	 * @see \BigBang\Object::__toString()
	 */
	public function __toString(){
		$string="";
		if(in_array($this->type,array("T","C","G"))){
			$string.="planète ";
		}
		$string.=$this->naturePlanete[$this->type]." (".$this->sousCategorieList[$this->type][$this->sousType].") masse: ".$this->masse."(MT) ";
		if($this->objectOrbited==null){
			$string.=" orbitant à ".$this->distanceEtoile."(as) du barycentre ";
		}else{
			$string.=" orbitant à ".$this->distanceEtoile."(as)";
		}
		if($this->rayonnement!=0 and $this->rayonnement<9999){
			$string.=" température ".$this->rayonnement."°C en surface ";
		}
		//$string.=" at ".$this->distanceEtoile."(al)\n";
				
		return $string;
				
	}
	
	/**
	 * renvoie le code couleur de la planète en fonction de son type
	 */
	public function getColor(){
		return $this->codeCouleurType[$this->type];
	}

	
	/**
	 * @see \BigBang\Object::__toSqlValues()
	 */
	public function __toSqlValues(){
		return "('',".$this->systeme.",".$this->objectOrbited.",'".$this->type."','".$this->sousType."','".$this->masse."','"
				.$this->particularite."','".$this->distanceEtoile."','".$this->inclinaisonOrbite."','".$this->dureeAnnee."','".
		$this->dureeJour."','".$this->albedo."','".$this->rayonnement."','".$this->hydrometrie."','".$this->eden."') ";
	}
	
	/**
	 * calcule une orbite circulaire en fonction des masses
	 * @param float $masseObjetOrbited
	 * @return number
	 */
	public function simplifiedCircularOrbit($masseObjetOrbited){
		/**
		 * données de test pour l'orbite de la lune autour de la terre
		 */
		//echo $masseObjetOrbited."\n";
		
		$distanceEtoileKM=bcmul($this->distanceEtoile, Universe::$astron,5);
		//https://fr.wikipedia.org/wiki/Vitesse_orbitale		
		$m=maths_service::exp2int(7.3477e+22); //masse de la lune
		$M=bcmul(maths_service::exp2int(5.9736e+24),$masseObjetOrbited,4); //masse de la terre
		
		$G=maths_service::exp2int(Universe::$G); //constante de gravitation
		$mu=bcmul($G,$M);
		$V=sqrt(bcdiv($mu,$distanceEtoileKM,10));

		//echo "mu: ".$mu."\n";
		//echo "vitesse ".$V." (m/s)\n";
		$this->vitesseOrbitale=$V;
		if($V==0){ echo "dist: ".$distanceEtoileKM." ou ".$this->distanceEtoile." M: ".$M."\n";
			echo $masseObjetOrbited;die;}
		$traject=bcmul(bcmul($distanceEtoileKM,2),pi());
		//echo "circonférence orbitale: ".$traject."\n";
		
		$dureeOrbite=bcdiv($traject,bcdiv($V,1000,5)); //ajuster les unités (v en ms=>km/s)		
		$dureeOrbite=bcdiv($dureeOrbite,3600);
		$this->dureeAnnee=bcdiv($dureeOrbite, 24);
		return $V;
	}
	
	



}

?>