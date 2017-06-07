<?php 
namespace BigBang;
include('Galaxy_object.php');
include 'maths_service.php';

class LifeForm {
	
	private $appendicePrehensile=array("aucun", "main/patte","pince","tentacule","queue","bouche multi-usage");
	
//	private $regne=array("mineral", "vegetal","animal");
	
	private $sens=array('vue','odorat','ouie','echolocation','toucher','electromagnetisme');
	
	private $milieu=array('aquatique','semi-aquatique','terrestre','arboricole','aérien');
	
	private $membres=array(0,2,4,6,8,10);
	
	private $epiderme=array("peau","cuir", "fourrure","ecailles","plaques osseuses");
	
	private $regime=array('photosynthese','vegetarien','carnivore','omnivore','charognard');
	
	private $govType=array(0=>'Fédération',
			1=>'Republique',
			2=>'Royaume Parlementaire',
			3=>'Royaume / Empire',
			4=>'Anarchie',
			5=>'Pas de gouvernement unifié',
			6=>'oligarchie',
			7=>'théocratie',
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
	
	/**
	 * 
	 * @var string sens principal
	 */
	public $sensPrincipal;
	
	/***
	 * 
	 * @var integer
	 */
	public $governement;
	
	public function __construct($planete){
		
	}
	
}
?>