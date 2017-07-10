<?php 
namespace BigBang;
require_once 'loader.php';

class LifeForm extends Object{
	
	private $appendicePrehensile=array("aucun","main/patte","pince","tentacule","queue","bouche multi-usage");
	
//	private $regne=array("mineral", "vegetal","animal");
	
	private $sensList=array('vue','odorat','ouie','echolocation','toucher','electromagnetisme');
	
	private $milieuList=array('aquatique','semi-aquatique','terrestre','arboricole','aerien','sousterrain');
	
	private $membres=array(0,2,4,6,8,10);
	
	private $epidermeList=array(0=>"peau",1=>"cuir",2=>"chitine",3=> "fourrure",4=>"ecailles",5=>"plaques osseuses");
	private $epidermeCodeList=array(0,1,2,3,4,5);
	
	private $regimeList=array(0=>'photosynthese',1=> 'vegetarien',2=>'carnivore',3=>'omnivore',4=>'charognard',5=>"chimio-synthèse");
	private $regimeCodeList=array(0,1,2,3,4,5);
	
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
	
	private $avancementList=array(
			0=>"Aucune modif, dépendance", //totale dépendance à l'environnement
			1=>"modifs environnement immédiat, dépendance moyenne", //modification de l'environnement local: habitat et agriculture/élevage
			2=>"modifs locales, dépendance faible", //regroupement de l'habitat, amélioration des techniques, maitrise d'énergie chimique ou électrique
			3=>"impact planétaire, dépendance risques", //civilisation quasi-planétaire avec un forte maitrise de son environnement 
			//qui n'est sujette qu'aux catastrophes et aux extrèmes
			4=>"Gestion planétaire, dépendance risques majeurs", //civilisation planétaire qui s'équilibre avec son propre environnement, 
			//ne craint que les catastrophes majeures séismes
			5=>"Gestion d'environement stellaire", //bases extra-planétaires autonomes, terraformation basique, déviation préventive d'astéroides
			6=>"Maitrise multi-systeme", //intégration de l'espèce/civilisation dans les écosystèmes de plusieurs mondes, voyage spatial, terraformation poussée
			7=>"Auto-modification de l'adaptation biologique",
			//l'espèce s'est modifiée pour vivre dans des conditions très diverses en altérant le moins possible l'écosystème hôte,
			//ce qui lui assure un développement quasi-galactique
			8=>"hypothétique" //indépendance totale de l'environnement, forme de vie énergétique et/ou capable de modifier de grand pans de l'univers
	);
	
	//on part du principe que les probabilités de répartition des espèces sont inversement proportionelles à leur avancement
	// on doit arriver à produire un nombre réduit (>1000) d'espèces dévellopées de stade 5 & 6
	// on ne génère pas d'espèce de stade 7 car la probabilité est inférieure à une par galaxie réelle
	// pas non plus de stade 8 car la probabilité est inférieure à 1 pour l'univers observable
	private $avancementProba=array(
		30=>0,
		50=>1,
		65=>2,
		80=>3,
		90=>4,
		95=>5,
		100=>6,
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
	 * appendice manipulateur
	 * @var string
	 */
	public $appendice;
	
	/**
	 * 
	 * @var integer
	 */
	public $nombreMembres;
	
	/**
	 * 
	 * @var string $epiderme
	 */
	public $epiderme;
	
	/***
	 * régime alimentaire
	 * @var string $regime
	 */
	public $regime;
	
	/***
	 * forme de gouvernement
	 * @var integer
	 */
	public $governement;
	
	/***
	 * espérance de vie 
	 * @var unknown
	 */
	public $dureeVie;
	
	/**
	 * environement préféré / d'émergence
	 * @var unknown
	 */
	public $milieu;
	
	/**
	 * avancement de l'espèce
	 * @var integer
	 */
	public $avancement;
	
	
	public function __construct($planeteOrigine,$systemeOrigine){

		$this->originPlanet=$planeteOrigine;
		$this->originSystem=$systemeOrigine;
		
		$this->name=strings_service::generatePrononcableString(6,rand(6,12));

		$sens=$this->sensList[rand(0,(count($this->sensList)-1))];
		$this->sensPrincipal=$sens;
		
		$epiderme=$this->epidermeCodeList[rand(0,(count($this->epidermeCodeList)-1))];
		$this->epiderme=$epiderme;
		
		$gov=$this->govType[rand(0,(count($this->govType)-1))];
		$this->governement=$gov;
		
		$regime=$this->regimeCodeList[rand(0,(count($this->regimeCodeList)-1))];
		$this->regime=$regime;
		
		$this->milieu=$this->milieuList[rand(0,(count($this->milieuList)-1))];
		$this->appendice=$this->appendicePrehensile[rand(0,(count($this->appendicePrehensile)-1))];
		

		//cohérence: si pas de membres: pas d'appendice préhenseur
		if($this->membres==0){
			$this->appendice=$this->appendicePrehensile[0];
		}
		//cohérence: le sens principal toucher restreint les régimes possibles
		while($regime!="p" && $regime!="v" && $sens=="toucher"){
			$sens=$sens=$this->sensList[rand(0,(count($this->sensList)-1))];
			$this->sensPrincipal=$sens;
		}
		//cohérence: des pinces nécessitent un épiderme rigide
		while($this->appendice=="pinces" && $this->epiderme!="chitine" && $this->epiderme=="plaques osseuses"){
			$this->appendice=$this->appendicePrehensile[rand(0,(count($this->appendicePrehensile)-1))];
		}
		//cohérence: des pinces ne sont pas adaptées a un déplacement arboricole
		while($this->appendice=="pinces" && $this->milieu=="arboricole"){
			$this->appendice=$this->appendicePrehensile[rand(0,(count($this->appendicePrehensile)-1))];
		}
		$this->nombreMembres=$this->membres[rand(0,(count($this->membres)-1))];
				
		$proba=rand(0,100);
		foreach ($this->dureeVieProprotion as $range => $tab){
			if($proba<=$range){
				$this->dureeVie=rand($tab['min'],$tab['max']);break;
			}else{
				continue;
			}
		}		
		
		$proba=rand(0,100);
		foreach ($this->avancementProba as $range => $id){
			if($proba<=$range){
				$this->avancement=$id;
				break;
			}else{
				continue;
			}
		}
		
	}
		

	public function __toString(){
		$string="Espece :'".$this->name."'\n Basée sur le ".$this->sensPrincipal;
		
		if($this->appendice!="aucun"){$string.=" manipulant son environnement grâce à son/ses ".$this->appendice;}
		$string.=" posédant ".$this->nombreMembres." membres et dont le corps est recouvert de ".$this->epidermeList[$this->epiderme].
		".\n Son régime alimentaire ".$this->regimeList[$this->regime]." lui permet de vivre en moyenne ".$this->dureeVie
		." ans dans son milieu naturel ".$this->milieu.".\n Cette espèce est actuellement au stade ".$this->avancement." organisée en "
		.$this->governement." ";

		return $string;	
	}
	

	public function __toSqlValues(){
		return "('','".$this->name."','".$this->originPlanet."','".$this->originSystem."','".$this->sensPrincipal."',
				'".$this->appendice."','".$this->nombreMembres."','".$this->epiderme.
		"','".$this->regime."','".$this->governement."','".$this->dureeVie."','".$this->milieu."','".$this->avancement."') ";
	}

	
}
?>