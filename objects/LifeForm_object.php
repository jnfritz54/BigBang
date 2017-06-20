<?php 
namespace BigBang;
require_once 'loader.php';

class LifeForm {
	
	private $appendicePrehensile=array("aucun", "main/patte","pince","tentacule","queue","bouche multi-usage");
	
//	private $regne=array("mineral", "vegetal","animal");
	
	private $sensList=array('vue','odorat','ouie','echolocation','toucher','electromagnetisme');
	
	private $milieuList=array('aquatique','semi-aquatique','terrestre','arboricole','aerien','sousterrain');
	
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
	private $avancementProba=array(
		30=>0,
		50=>1,
		65=>2,
		75=>3,
		85=>4,
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
		
		$sens=$this->sensList[rand(0,(count($this->sensList)-1))];
		$this->sensPrincipal=$sens;
		
		$epiderme=$this->epidermeList[rand(0,(count($this->epidermeList)-1))];
		$this->epiderme=$epiderme;
		
		$gov=$this->govType[rand(0,(count($this->govType)-1))];
		$this->governement=$gov;
		
		$regime=$this->regimeList[rand(0,(count($this->regimeList)-1))];
		$this->regime=$regime;
		
		$this->milieu=$this->milieuList[rand(0,(count($this->milieuList)-1))];
		
		//cohérence
		while($regime!="photosynthese" && $regime!="vegetarien" && $sens=="toucher"){
			$sens=$sens=$this->sensList[rand(0,(count($this->sensList)-1))];
			$this->sensPrincipal=$sens;
		}
		
		$this->appendice=$this->appendicePrehensile[rand(0,(count($this->appendicePrehensile)-1))];
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
		return "\n";
	
	
	}
	
	public function __toSqlValues(){
		return "('','".$this->name."','".$this->originPlanet."','".$this->originSystem."','".$this->sensPrincipal."',
				'".$this->appendice."','".$this->nombreMembres."','".$this->epiderme.
		"','".$this->regime."','".$this->governement."','".$this->dureeVie."','".$this->milieu."','".$this->avancement."') ";
	}
	
}
?>