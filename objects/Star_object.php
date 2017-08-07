<?php 
/**
 * fichier de la classe Star définissant et construisant les objets étoiles
 */
namespace BigBang;

/**
 * Définition de l'objet "Star" et méthodes pour le construire 
 */
class Star extends Object{

	/**
	 * Types d'étoiles génériques, code et textuel
	 * @var array
	 */
	private $etoileTypes=array('L'=>"naine brune",'M'=>"naine rouge",
			'K'=>"naine orange",'G'=>'naine jaune','F'=>"jaune/blanche",'A'=>"blanche",'B'=>"blanche/bleue",'O'=>"bleue");
	
	/**
	 * couleur hexa pour représentation de l'étoile en fonction de son type surchargé
	 * @see Star::$typeSurcharge
	 * @var array
	 */
	public $hexaByTypeSurcharge=array('1'=>"#655555",'2'=>"#FF2020", '3'=>"#DD00DD","4"=>"#000000",
		'L'=>"#201020",'D'=>"#EEFFEE",'D2'=>"#EEFFEE",'M'=>"#FF2020",
		'K'=>"#FF9000",'G'=>'#EEEE00','F'=>"#FF9300",'A'=>"#FFFFFF",'B'=>"#AECBE1",'O'=>"#7BA7F3");
	
	/***
	 * classification personelle des objets stellaires permettant le mécanisme de surcharge de type en fonction de son age et masse
	 * @var array
	 */
	private $stellarObjectClassification=array('1'=>"Proto-étoile",'2'=>"géante ou supergéante rouge", '3'=>"Etoile à neutrons","4"=>"Trou noir",
		'L'=>"naine brune",'D'=>"Naine blanche",'D2'=>"Naine blanche avec nébuleuse planétaire",'M'=>"naine rouge",
		'K'=>"naine orange",'G'=>'naine jaune','F'=>"jaune/blanche",'A'=>"blanche",'B'=>"blanche/bleue",'O'=>"bleue");
		
	/**
	 * Evolution simplifiée des étoiles en fonction de leur type et age
	 * les ages sont calculés en milliards d'années
	 * @see https://fr.wikipedia.org/wiki/%C3%89volution_stellaire "durée de vie par masse)
	 * @var array
	 */
	private $evolutionByType=array(
			"L"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.01","code"=>"FORM",'surcharge'=>"1"),
					array('label'=>"Naine Brune", 'age_min'=>"0.01",'age_max'=>"9999","code"=>"END",'surcharge'=>"L"),
				),
			"M"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.01","code"=>"FORM",'surcharge'=>"1"),
					array('label'=>"Sequence Principale", 'age_min'=>"0.01",'age_max'=>"750","code"=>"SEQPRI",'surcharge'=>"M"),
					array('label'=>"Naine Bleue", 'age_min'=>"750",'age_max'=>"800","code"=>"SEQSEC",'surcharge'=>"M"),
					array('label'=>"Naine Blanche", 'age_min'=>"800",'age_max'=>"9999","code"=>"END",'surcharge'=>"D"),
				),
			"K"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.01","code"=>"FORM",'surcharge'=>"1"),
					array('label'=>"Sequence Principale", 'age_min'=>"0.01",'age_max'=>"30","code"=>"SEQPRI",'surcharge'=>"K"),
					array('label'=>"Naine Blanche", 'age_min'=>"30",'age_max'=>"9999","code"=>"END",'surcharge'=>"D"),
				),
			"G"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.01","code"=>"FORM",'surcharge'=>"1"),
					array('label'=>"Sequence Principale", 'age_min'=>"0.01",'age_max'=>"8.5","code"=>"SEQPRI",'surcharge'=>"G"),
					array('label'=>"Sub-geante rouge", 'age_min'=>"8.5",'age_max'=>"9.5","code"=>"SEQSEC",'surcharge'=>"2"),
					array('label'=>"Naine Blanche avec nébuleuse planétaire", 'age_min'=>"9,5",'age_max'=>"10","code"=>"NEBUL",'surcharge'=>"D2"),
					array('label'=>"Naine Blanche", 'age_min'=>"10",'age_max'=>"9999","code"=>"END",'surcharge'=>"D"),
			),
			"F"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.01","code"=>"FORM",'surcharge'=>"1"),
					array('label'=>"Sequence Principale", 'age_min'=>"0.01",'age_max'=>"6.5","code"=>"SEQPRI",'surcharge'=>"F"),
					array('label'=>"geante rouge", 'age_min'=>"6.5",'age_max'=>"7","code"=>"SEQSEC",'surcharge'=>"2"),
					array('label'=>"Naine Blanche avec nébuleuse planétaire", 'age_min'=>"7",'age_max'=>"7.5","code"=>"NEBUL",'surcharge'=>"D2"),
					array('label'=>"Naine Blanche", 'age_min'=>"7.5",'age_max'=>"9999","code"=>"END",'surcharge'=>"D"),
			),
			"A"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.01","code"=>"FORM",'surcharge'=>"1"),
					array('label'=>"Sequence Principale", 'age_min'=>"0.01",'age_max'=>"0.25","code"=>"SEQPRI",'surcharge'=>"A"),
					array('label'=>"supergeante rouge", 'age_min'=>"0.25",'age_max'=>"0.3","code"=>"SEQSEC",'surcharge'=>"2"),
					array('label'=>"Naine Blanche avec nébuleuse planétaire", 'age_min'=>"0.3",'age_max'=>"1","code"=>"NEBUL",'surcharge'=>"D2"),
					array('label'=>"Naine Blanche", 'age_min'=>"1",'age_max'=>"9999","code"=>"END",'surcharge'=>"D"),
			),
			"B"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.008","code"=>"FORM",'surcharge'=>"1"),
					array('label'=>"Sequence Principale", 'age_min'=>"0.008",'age_max'=>"0.1","code"=>"SEQPRI",'surcharge'=>"B"),
					array('label'=>"supergeante rouge", 'age_min'=>"0.1",'age_max'=>"0.11","code"=>"SEQSEC",'surcharge'=>"2"),
					array('label'=>"Etoile a neutrons", 'age_min'=>"0.11",'age_max'=>"9999","code"=>"END",'surcharge'=>"3"),
			),
			"O"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.005","code"=>"FORM",'surcharge'=>"1"),
					array('label'=>"Sequence Principale", 'age_min'=>"0.005",'age_max'=>"0.055","code"=>"SEQPRI",'surcharge'=>"O"),
					array('label'=>"supergéante rouge", 'age_min'=>"0.055",'age_max'=>"0.06","code"=>"SEQSEC",'surcharge'=>"2"),
					array('label'=>"Trou Noir", 'age_min'=>"0.06",'age_max'=>"9999","code"=>"END",'surcharge'=>"4"),
			),
	);
	
	/**
	 * échelle des masses possibles en fonction du type d'étoile (exprimées en masses solaires)
	 * @var array
	 */
	private $massesByType=array(
			"L"=>array("min"=>"0.001","max"=>"0.08"),
			"M"=>array("min"=>"0.08","max"=>"0.5"),
			"K"=>array("min"=>"0.5","max"=>"0.8"),
			"G"=>array("min"=>"0.8","max"=>"1.2"),
			"F"=>array("min"=>"1.2","max"=>"1.5"),
			"A"=>array("min"=>"1.5","max"=>"3"),
			"B"=>array("min"=>"3","max"=>"10"),
			"O"=>array("min"=>"10","max"=>"300"),
	);

	/***
	 * gère les probabilités des types d'étoiles créées !
	 * @var array $probaType
	 */
	private $probaType=array(
			"5"=>"L", //5%
			"50"=>"M", //45%
			"80"=>"K", //30%
			"90"=>"G", //10%
			"93"=>"F", //3%%
			"96"=>"A",	//3%
			"98"=>"B", //2%
			"100"=>"O", //2 
	);
	
	/**
	 * id du système parent
	 * @var integer
	 */
	public $systeme=null;
	
	/**
	 * distance etoile-barycentre en astrons (si système multiple, sinon 0)
	 * @var float
	 */
	public $distanceBarycentre=0;
	
	/**
	 * type de l'étoile à sa création
	 * @var string
	 */
	public $typeOrigine=null;
	

	/**
	 * type de l'étoile une fois pondéré son age
	 * @var string
	 */
	public $typeSurcharge;
	
	/**
	 * période textuelle de l'histoire stellaire (formation - séquence principale - ...) 
	 * @var string
	 */
	public $periodeActuelle;
	
	/**
	 * masse d'origine servant aux différents calculs (en masses solaires)
	 * @var float
	 */
	public $masseOrigine;	
	
	/**
	 * age en miliards d'années
	 * @var float
	 */
	public $age;		
	
	/**
	 * puissance de rayonnement brut de l'étoile
	 * @see Star::massLuminosityRelationWithSurcharge() calcul de la propriété
	 * @var unknown
	 */
	public $rayonnement;
	
	/**
	 * rayon de l'étoile en km
	 * @var unknown
	 */
	public $rayon;
	

	public function __construct($systeme=null,$multiple=false){
			
		//détermination de son type
		$proba=rand(0,100);

		foreach ($this->probaType as $range => $class){
			if($proba<=$range){
				$this->typeOrigine=$class;break;
			}else{
				continue;		
			}
		}
		if($this->typeOrigine==null){
			throw new \Exception("Type origine null ! (proba ".$proba.")");
		}
		
		//détermination de sa masse
		$this->masseOrigine=maths_service::float_rand($this->massesByType[$this->typeOrigine]['min'],$this->massesByType[$this->typeOrigine]['max']);
		
		//détermination de son age
		$this->age=maths_service::float_rand(0,Universe::$ageOfUniverse);
		
		//calcul de son état actuel selon son age:
		foreach ($this->evolutionByType[$this->typeOrigine] as $periode){
			if($this->age >=$periode['age_min'] && $this->age <$periode['age_max']){
				if(isset($periode['surcharge'])){
					$this->typeSurcharge=$periode['surcharge'];
				}else{
					$this->typeSurcharge=$this->typeOrigine;					
				}
				$this->periodeActuelle=$periode['label'];
				break;
			}
		}
		
		$this->systeme=$systeme;	
		
		//calcul approx du rayon et de la luminosité d'après la masse
		$this->massLuminosityRelationWithSurcharge();
		$this->massRayonRelationWithSurcharge();
		
		//positionnement artificiel:
		if($multiple){
			$this->distanceBarycentre=maths_service::float_rand(0.01, 15);
		}
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \BigBang\Object::__toString()
	 */
	public function __toString(){
		return $this->typeOrigine."(".$this->typeSurcharge.") age: ".$this->age."MA masse: ".$this->masseOrigine."Ms \n";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \BigBang\Object::__toSqlValues()
	 */
	public function __toSqlValues(){
		return "('','".$this->systeme."' ,'".$this->distanceBarycentre."','".$this->typeOrigine."','".$this->periodeActuelle."','".$this->typeSurcharge."',
				'".$this->age."','".$this->masseOrigine."','".$this->rayonnement."','".$this->rayon."') ";
	}

	/**
	 * Calcul de la luminosité en fonction du type de l'étoile et de son état actuel
	 * @see Star::massLuminosityRelation() calcul de la propriété pour les séquences principales
	 */
	private function massLuminosityRelationWithSurcharge(){
		switch($this->typeSurcharge){
			case 'L': //brown dwarf, lum=0				
			case 4: //blackhole: lum 0				
			case 3: //neutronstar: lum 0
				$this->rayonnement=0;
				break;
			case 'D':	//naines blanches
			case 'D2':
				$this->rayonnement=10000;
				break;
			case 1: //proto-star lum ~0
				$this->rayonnement=10000;
				break;
			default:
				$this->massLuminosityRelation();
				break;
		}
	}
	
	
	/**
	 * Calcul de la luminosité en fonction du type de l'étoile et de son état actuel
	 * @see https://en.wikipedia.org/wiki/Mass%E2%80%93luminosity_relation
	 */
	private function massLuminosityRelation(){
		$luminositeSoleil='3.846e26'; //exprimée en W 
		$luminositeSoleil=maths_service::exp2int($luminositeSoleil);
		$masseSoleil='1.9891e30';
		$masseSoleil=maths_service::exp2int($masseSoleil);
		
		$multiplier=1;
		$puissance=4;
		if($this->masseOrigine<=0.43){
			$multiplier=0.23;
			$puissance=2.3;
		}elseif($this->masseOrigine<=2){
			$multiplier=1;
			$puissance=4;
		}elseif($this->masseOrigine<=20){
			$multiplier=1.5;
			$puissance=3.5;
		}elseif($this->masseOrigine>20){
			$multiplier=3200;
			$puissance=1;
		}
		//L=(multiplier*(M/masseSoleil)^puissance)/luminositéSoleil 	
		$M=bcmul($masseSoleil,$this->masseOrigine);
		$MM=bcdiv($M,$masseSoleil,4);
		$L=bcmul(bcmul($multiplier,	pow($MM,$puissance)),$luminositeSoleil,4);

		$this->rayonnement=$L;
		return $L;
	}

	/**
	 * Calcul du rayon en fonction de la masse et de l'état actuel de l'étoile
	 * @see Star::massRayonRelationBlackHole pour les trous noirs
	 * @see Star::massRayonRelationSequencePrincipale pour les séquences principales
	 * @see Starr::massRayonRelationNaineBlanche pour les naines blanches
	 */
	private function massRayonRelationWithSurcharge(){
		switch($this->typeSurcharge){
			case 4: //blackhole: rayon = 0 (singularité) rayon = rayon de l'horizon des évenements
				$this->massRayonRelationBlackHole();
				break;
			case 3: //neutronstar: lum 0
				$this->rayon=maths_service::float_rand(15000, 30000);
				break;
			case 2:	
				//approx dégueulasse pour faire artificiellement grossir les géantes rouges				
				$this->rayon=bcmul($this->massRayonRelationSequencePrincipale(),2);
				break;
			case 'D':	//naines blanches
			case 'D2':
				$this->massRayonRelationNaineBlanche();
				break;
			case 1: //proto-star lum ~0
				$this->rayon=10000000;
				break;
			default:
				$this->massRayonRelationSequencePrincipale();
				break;
		
		}
	}
	
	/***
	 * calcul le rayon de l'horizon des evenements d'un trou noir
	 * @see https://fr.wikipedia.org/wiki/Horizon_des_%C3%A9v%C3%A8nements
	 * @return float
	 */
	private function massRayonRelationBlackHole(){
		// R= (2GM)/c²
		$masseSoleil='1.9891e30';
		$masseSoleil=maths_service::exp2int($masseSoleil);
		
		$mu=bcmul(bcmul($this->masseOrigine,$masseSoleil), maths_service::exp2int(Universe::$G),4);
		$R=bcdiv(bcmul($mu,2,4),pow(Universe::$c,2));
		$this->rayon=$R;	
		return $R;
	}
	
	/***
	 * calcul le rayon d'une étoile de séquence principale
	 * @see https://fr.wikipedia.org/wiki/Relation_masse-rayon#.C3.89toiles_de_la_s.C3.A9quence_principale
	 * @see http://www2.astro.psu.edu/users/rbc/a534/lec18.pdf
	 * @return float
	 */
	private function massRayonRelationSequencePrincipale(){
	
		$rayonSoleil='695700000'; //exprimée en m		
		$masseSoleil='1.9891e30';
		$masseSoleil=maths_service::exp2int($masseSoleil);

		$puissance=4;
		if($this->masseOrigine<=1){
			$puissance=0.8;
		}elseif($this->masseOrigine>1){
			$puissance=0.57;
		}
		//L=(multiplier*(M/masseSoleil)^puissance)/luminositéSoleil
		$M=bcmul($masseSoleil,$this->masseOrigine);
		$MM=bcdiv($M,$masseSoleil,4);
		$R=bcmul(pow($MM,$puissance),$rayonSoleil,4);
		
		$this->rayon=$R;
		return $R;
	}
	
	/**
	 * calcul de rayon spécifique aux naines blanches 
	 * @return float
	 */
	private function massRayonRelationNaineBlanche(){
		$rayonSoleil='695700000'; //exprimée en m
		$rayon=bcdiv(1/pow($this->masseOrigine,bcdiv(1,3,6)),4);
		$this->rayon=bcmul($rayon,$rayonSoleil,5);
		return $this->rayon;
	}
}
?>
