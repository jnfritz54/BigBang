<?php 
namespace BigBang;
require_once 'loader.php';

class LifeForm {
	
	private $appendicePrehensile=array("aucun", "main/patte","pince","tentacule","queue","bouche multi-usage");
	
//	private $regne=array("mineral", "vegetal","animal");
	
	private $sensList=array('vue','odorat','ouie','echolocation','toucher','electromagnetisme');
	
	private $milieu=array('aquatique','semi-aquatique','terrestre','arboricole','aérien');
	
	private $membres=array(0,2,4,6,8,10);
	
	private $epidermeList=array("peau","cuir","chitine", "fourrure","ecailles","plaques osseuses");
	
	private $regimeList=array('photosynthese','vegetarien','carnivore','omnivore','charognard');
	
	private $dureeVieProprotion=array(
			5=>array('min'=>20,'max'=>30), //pre-civilisation
			10=>array('min'=>30,'max'=>45), 
			30=>array('min'=>45,'max'=>65), //industrial
			60=>array('min'=>80,'max'=>125), //post-industrial global
			80=>array('min'=>130,'max'=>200), 
			100=>array('min'=>150,'max'=>900)
	);
	
	private $govType=array(0=>'Federation',
			1=>'Republique',
			2=>'Royaume Parlementaire',
			3=>'Royaume / Empire',
			4=>'Anarchie',
			5=>'Pas de gouvernement unifié',
			6=>'oligarchie',
			7=>'gerontocratie',
			7=>'theocratie',
			8=>'ploutocratie'
	);
	
	/**
	 * id autoinc
	 * @var integer $id
	 */
	public $id;
	
	/***
	 * name
	 * @var string $name
	 */
	public $name;
	
	/***
	 * planete id
	 * @var integer $originPlanet
	 */
	public $originPlanet;
	
	/***
	 * sytem id
	 * @var integer $originSystem
	 */
	public $originSystem;
	
	/**
	 * 
	 * @var string sens principal
	 */
	public $sensPrincipal;
	
	/**
	 * 
	 * @var string $epiderme
	 */
	public $epiderme;
	
	/***
	 * 
	 * @var string $regime
	 */
	public $regime;
	
	/***
	 * 
	 * @var integer
	 */
	public $governement;
	
	
	
	/***
	 * espérance de vie 
	 * @var unknown
	 */
	public $dureeVie;
	
	public function __construct($planeteOrigine,$systemeOrigine){
		$this->originPlanet=$planeteOrigine;
		$this->originSystem=$systemeOrigine;
		
		$sens=$this->sensList[rand(0,(count($this->sensList)-1))];
		$this->sensPrincipal=$sens;
		
		$epiderme=$this->epidermeList[rand(0,(count($this->epidermeList)-1))];
		$this->epiderme=$epiderme;
		
		$gov=$this->govType[rand(0,(count($this->govType)-1))];
		$this->governement=$gov;
		
		$regime=$this->regimeList[rand(0,(count($this->regimeList)-1))];
		$this->regime=$regime;
		
		while($regime!="photosynthese" && $regime!="vegetarien" && $sens=="toucher"){
			$sens=$sens=$this->sensList[rand(0,(count($this->sensList)-1))];
			$this->sensPrincipal=$sens;
		}
		
		$proba=rand(0,100);
		foreach ($this->dureeVieProprotion as $range => $tab){
			if($proba<=$range){
				$this->dureeVie=rand($tab['min'],$tab['max']);break;
			}else{
				continue;
			}
		}
		
	}
	
	
	

	public function __toString(){
		return "\n";
	
	
	}
	
	public function __toSqlValues(){
		return "('','".$this->name."','".$this->originPlanet."','".$this->originSystem."','".$this->sensPrincipal."','".$this->epiderme.
		"','".$this->regime."','".$this->governement."','".$this->dureeVie."') ";
	}
	
}
?>