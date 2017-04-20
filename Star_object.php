<?php 
namespace BigBang;

class Star_object{

	private $etoileTypes=array('L'=>"naine brune",'M'=>"naine rouge",
			'K'=>"naine orange",'G'=>'naine jaune','F'=>"jaune/blanche",'A'=>"blanche",'B'=>"blanche/bleue",'O'=>"bleue");
	
	/***
	 * classification personelle des objets stellaires permettant le mécanisme de surcharge de type
	 */
	private $stellarObjectClassification=array('1'=>"Proto-étoile",'2'=>"géante ou supergéante rouge", '3'=>"Etoile à neutrons","4"=>"Trou noir",
		'L'=>"naine brune",'D'=>"Naine blanche",'D2'=>"Naine blanche avec nébuleuse planétaire",'M'=>"naine rouge",
		'K'=>"naine orange",'G'=>'naine jaune','F'=>"jaune/blanche",'A'=>"blanche",'B'=>"blanche/bleue",'O'=>"bleue");
	
	private $ageOfUniverse=13.4;
	
	//les ages sont calculés en milliards d'années
	
	//durée de vie par masse: https://fr.wikipedia.org/wiki/%C3%89volution_stellaire
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
	
	private $massesByType=array(
			"L"=>array("min"=>"0","max"=>"0.08"),
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
	
	public $systeme=null;
	
	//type de l'étoile à sa création
	public $typeOrigine=null;
	
	//type de l'étoile une fois pondéré son age
	public $typeSurcharge;
	
	public $periodeActuelle;
	
	public $masseOrigine;	
	
	public $age;
	
	public $temperature;
	
	public $rayonnement;
	
	public $rayon;
	

	public function __construct($systeme=null){
			
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
		$this->age=maths_service::float_rand(0,$this->ageOfUniverse);
		
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
		
	}
	
	public function __toString(){
		return $this->typeOrigine."(".$this->typeSurcharge.") age: ".$this->age."MA masse: ".$this->masseOrigine."Ms \n";
	}
	
	public function __toSqlValues(){
		return "('','".$this->systeme."' ,'".$this->typeOrigine."','".$this->periodeActuelle."','".$this->typeSurcharge."',
				'".$this->age."','".$this->masseOrigine."','".$this->rayonnement."','".$this->rayon."') ";
	}

	private function massLuminosityRelationWithSurcharge(){
		switch($this->typeSurcharge){
			case 4: //blackhole: lum 0
				$this->rayonnement=0;
				break;
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
	
	//https://en.wikipedia.org/wiki/Mass%E2%80%93luminosity_relation
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

	private function massRayonRelationWithSurcharge(){
		switch($this->typeSurcharge){
			case 4: //blackhole: lum 0
				$this->rayon=0;
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
	
	private function massRayonRelationSequencePrincipale(){
		//http://www2.astro.psu.edu/users/rbc/a534/lec18.pdf
		//https://fr.wikipedia.org/wiki/Relation_masse-rayon#.C3.89toiles_de_la_s.C3.A9quence_principale
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
	
	private function massRayonRelationNaineBlanche(){
		$rayonSoleil='695700000'; //exprimée en m
		$rayon=bcdiv(1/pow($this->masseOrigine,bcdiv(1,3,6)),4);
		$this->rayon=bcmul($rayon,$rayonSoleil,5);
		return $this->rayon;
	}
}
?>
