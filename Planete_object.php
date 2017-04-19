<?php
namespace BigBang;

class Planete{
	
	private $naturePlanete=array('T'=>'tellurique','G'=> 'gazeuse',
			'C'=>'chtonienne','A'=>'ceinture asteroides',"B"=>'naine brune');
	
	private $probaNature=array(
		45=>'T',
		80=>'G',
		93=>'A',
		96=>'C',
		100=>'B'
	);
	
	private $sousCategorie=array(
			"T"=>array("Tf"=>"en fusion","Ts"=>"sterile et/ou sans atmosphere","Ta"=>"aride","Tt"=>"earth-like","Te"=>"à dominante aquatique"),
			"G"=>array("Gh"=>"Hydrogène", "Ghe"=>"Helium","Gm"=>"Methane"),
			"C"=>array("C"=>"Standard"),
			"A"=>array("Ad"=>"Dense","Al"=>"dispersee"),
			"B"=>array("B"=>"Standard","Bs"=>"Sous-naine brune"),
	);
	
	//masses exprimées en masses terrestres
	private $massesParType=array(
		'T'=>array("min"=>0.3,"max"=>15),
		'G'=>array("min"=>25,"max"=>450),
		'C'=>array("min"=>0.5,"max"=>20),
		'A'=>array("min"=>0.001,"max"=>0.1),
		'B'=>array("min"=>4000,"max"=>23775),
	);
	
	private $particularitePlanete=array(50=>"aucune",70=>'lune',80=>'lunes multiples',90=>'anneaux',100=>'champ de débris');
		
	public $systeme;
	
	/**
	 * id de l'étoile orbitée, vaut null si système binaire/multiple et si la planète orbite autour du barycentre
	 * du système plutôt que d'un objet particulier
	 * @var integer
	 */
	public $objectOrbited;	
	
	public $type;
	
	public $masse;
	
	public $particularite;
	
	public $distanceEtoile;
		
	public $dureeJour;

	public function __construct($systeme,$idObjetOrbited){
		$type=maths_service::float_rand(0, count($this->naturePlanete)-1);
		$arr=array_keys($this->naturePlanete);
		$this->type=$arr[$type];
		
		$this->distanceEtoile=maths_service::float_rand(0, 0.8,7);
		$this->masse=maths_service::float_rand($this->massesParType[$this->type]['min'], $this->massesParType[$this->type]['max']);
		
		//détermination de sa particularité
		$proba=rand(0,100);
		
		foreach ($this->particularitePlanete as $range => $class){
			if($proba<=$range){
				$this->particularite=$class;break;
			}else{
				continue;
			}
		}
		$this->systeme=$systeme;
		$this->objectOrbited=$idObjetOrbited;
	}
	
	public function __toString(){
		return "systeme ".$this->systeme." type: ".$this->type." masse: ".$this->masse."(MT) orbiting ".$this->objectOrbited.
		" at ".$this->distanceEtoile."(al)\n";
				
				
	}
	
	public function __toSqlValues(){
		return "('',".$this->systeme.",".$this->objectOrbited.",'".$this->type."','".$this->masse."','"
				.$this->particularite."',".$this->distanceEtoile.",'".$this->dureeJour."') ";
	}
	
	public function simplifiedCircularOrbit(){
		/**
		 * données de test pour l'orbite de la lune autour de la terre
		 */
		$this->distanceEtoile=384399000+6378137;
		$this->masse=7.3477E22;
		//https://fr.wikipedia.org/wiki/Vitesse_orbitale		
		$m=$this->exp2int(7.3477E22); //masse de la lune
		$M=$this->exp2int(5,9736E24); //masse de la terre
		
		$G=$this->exp2int(6.67408E-11); //constante de gravitation
		$mu=bcmul($G,$m);
		$V=sqrt(bcdiv($mu, $this->distanceEtoile,10));

		echo "g: ".$G."\n";
		echo "mu: ".$mu."\n";
		echo "vitesse ".$V."\n";
		$traject=bcmul(bcmul($this->distanceEtoile,2),pi());
		echo "circonférence orbitale: ".$traject."\n";
		
		$dureeOrbite=bcdiv($traject,$V);
		echo "durée orbite: ".$dureeOrbite."\n";
		
		return $V;
	}
	
	private function float_rand($Min, $Max, $round=4){
		//validate input
		if ($Min>$Max) { $min=$Max; $max=$Min; }
		else { $min=$Min; $max=$Max; }
		$randomfloat = $min + mt_rand() / mt_getrandmax() * ($max - $min);
		if($round>0)
			$randomfloat = round($randomfloat,$round);
	
			return $randomfloat;
	}

	private function exp2int($exp) {
		list($mantissa, $exponent) = explode("e", strtolower($exp));
		if($exponent=='') return $exp;
		list($int, $dec) = explode(".", $mantissa);
		bcscale (abs($exponent-strlen($dec)));
		return bcmul($mantissa, bcpow("10", $exponent));
	}
}

?>