<?php 
namespace BigBang;
include('Galaxy_object.php');
include 'maths_service.php';

class LifeForm {
	
	private $govType=array(0=>'Fédération',
			1=>'Republique',
			2=>'Royaume Parlementaire',
			3=>'Royaume / Empire',
			4=>'Anarchie',
			5=>'Pas de gouvernement unifié',
			6=>'oligarchie',
			7=>'théocratie',);
	
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
	 * 
	 * @var integer
	 */
	public $governement;
	
	public function __construct($planete){
		
	}
	
}
?>