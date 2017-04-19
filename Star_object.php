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
		
	}
	
	public function __toString(){
		return $this->typeOrigine."(".$this->typeSurcharge.") age: ".$this->age."MA masse: ".$this->masseOrigine."Ms \n";
	}
	
	public function __toSqlValues(){
		return "('','".$this->systeme."' ,'".$this->typeOrigine."','".$this->periodeActuelle."','".$this->typeSurcharge."',
				'".$this->age."','".$this->masseOrigine."') ";
	}
	
}
?>
