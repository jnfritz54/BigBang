<?php 
/**
 * fichier de définition de la classe abstraite Galaxy servant à la configuration
 */
namespace BigBang;

/**
 * classe abstraite (pour le moment)
 * servant à la configuration 
 */
abstract class Galaxy{
	/**
	 * probabilité de positionnement dans une galaxie à 4 bras spiraux d'origine équi-angulaire
	 * @var array
	 */
	static $probaPosition=array(10=>"bulbe",80=>"partout",100=>"bras");
	
	/**
	 * rayon minimal en années lumières, là ou commence le disque et ou fini le bulbe
	 * dans notre galaxie le bulbe a un diametre entre 7k et 15k al
	 * @var integer
	 */
	static $rayonMin=5000; 
	
	/**
	 * rayon maximal de la galaxie en en années lumières
	 * @var integer
	 */
	static $rayonMax=50000;
	
	/**
	 * angle minimal en degrés de la spirale pour qu'elle ne commence pas dans le bulbe
	 * @var float
	 */
	static $angleMin=40;
	
	/**
	 * epaisseur du disque galactique en années lumières
	 * @var integer
	 */
	static $altitudeMax=1000;
	
	/**
	 * epaisseur des bras en années lumières
	 * @var integer
	 */
	static $epaisseurBras=2000;
	
	/**
	 * epaisseur du bulbe galactique en années lumières
	 * @var integer
	 */
	static $epaisseurBulbe=2800;
	
	/**
	 * paramétrage des bras: nombre, angle de départ & ouverture en degrés (360= spirale atteind le bord en 1 tour)
	 * @var array
	 */
	static $bras=array(
			//array('angle'=>0,'ouverture'=>300),
			array('angle'=>65,'ouverture'=>200),
			//array('angle'=>180,'ouverture'=>300),
			array('angle'=>245,'ouverture'=>200)
	);
	
	/**
	 * année galactique en années terrestres 
	 * (subdivision du temps théorique mis par une naine blanche ayant atteind la masse de chandrasehkar soit 1.44 masses solaires
	 * pour faire une orbite complète autour du trou noir central galactique à XXX distance)
	 * @var float
	 */
	static $anneeGalactiqueStandard=15;
}
?>
