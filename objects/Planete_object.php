<?php
namespace BigBang;

class Planete{
	
	private $naturePlanete=array('T'=>'tellurique','G'=> 'gazeuse',
			'C'=>'chtonienne','A'=>'ceinture asteroides',"B"=>'naine brune','P'=>"Planetoide rocheux");
	
	private $probaNature=array(
		35=>'T',
		70=>'G',
		80=>'A',
		85=>'C',
		90=>'B',
		100=>'P'
	);
	
	private $sousCategorie=array(
			"T"=>array("Tf"=>"en fusion","Ts"=>"sterile et/ou sans atmosphere","Ta"=>"aride","Tt"=>"earth-like","Te"=>"à dominante aquatique"),
			"G"=>array("Gh"=>"Hydrogène", "Ghe"=>"Helium","Gm"=>"Methane"),
			"C"=>array("C"=>"Standard"),
			"A"=>array("Ad"=>"Dense","Al"=>"dispersee"),
			"B"=>array("B"=>"Standard","Bs"=>"Sous-naine brune"),
			"P"=>array("C"=>"Standard"),
	);
	
	//masses exprimées en masses terrestres
	private $massesParType=array(
		'T'=>array("min"=>0.3,"max"=>25),
		'G'=>array("min"=>25,"max"=>1250),
		'C'=>array("min"=>0.5,"max"=>20),
		'A'=>array("min"=>0.001,"max"=>0.1),
		'B'=>array("min"=>4000,"max"=>23775),
		'P'=>array("min"=>0.01,"max"=>0.3),
	);
	
	private $albedoParType=array(
		'T'=>array("min"=>0.1,"max"=>1),
		'G'=>array("min"=>0.3,"max"=>0.9),
		'C'=>array("min"=>0.1,"max"=>0.5),
		'A'=>array("min"=>0.05,"max"=>0.3),
		'B'=>array("min"=>0.1,"max"=>0.5),
		'P'=>array("min"=>0.05,"max"=>0.3),
	);
	
	private $particularitePlanete=array('0'=>"nothing",'M'=>'moon','Mm'=>'multiples moons','R'=>'rings','F'=>'debris field');
	private $particulariteProba=array(50=>"0",70=>'M',80=>'Mm',90=>'R',100=>'F');
		
	public $systeme;
	
	/**
	 * id de l'étoile orbitée, vaut null si système binaire/multiple et si la planète orbite autour du barycentre
	 * du système plutôt que d'un objet particulier
	 * @var integer
	 */
	public $objectOrbited;	
	
	public $type;
	
	public $sousType;
	
	public $masse;
	
	public $particularite;
	
	public $distanceEtoile; //exprimée en astrons (1as=300 000 000km)
	
	public $inclinaisonOrbite; //angle en degrées
	
	public $albedo;
	
	public $vitesseOrbitale;
	
	public $dureeAnnee;
	
	public $dureeJour;
	
	public $rayonnement; // rayonnement reçu à la surface en w/m²
	
	public $eden=0;

	public function __construct($systeme,$idObjetOrbited,$masseObjetOrbited,$rayonnement,$rangPlanete,$contractionSysteme=0.5){
		
		$type=maths_service::float_rand(0, count($this->naturePlanete)-1);
		$arr=array_keys($this->naturePlanete);
		$this->type=$arr[$type];
		
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
	
	public function __toString(){
		return "systeme ".$this->systeme." type: ".$this->type." masse: ".$this->masse."(MT) orbiting ".$this->objectOrbited.
		" at ".$this->distanceEtoile."(al)\n";
				
				
	}
	
	public function __toSqlValues(){
		return "('',".$this->systeme.",".$this->objectOrbited.",'".$this->type."','".$this->masse."','"
				.$this->particularite."','".$this->distanceEtoile."','".$this->inclinaisonOrbite."','".$this->dureeAnnee."','".
		$this->dureeJour."','".$this->albedo."','".$this->rayonnement."','".$this->eden."') ";
	}
	
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