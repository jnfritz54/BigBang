<?php 
namespace BigBang;
include 'Universe_object.php';

/**
 * classe abstraite (pour le moment)
 * servant à la configuration 
 * @author jnfritz
 *
 */
abstract class Galaxy{
	//probabilité de positionnement dans une galaxie à 4 bras spiraux d'origine équi-angulaire
	static $probaPosition=array(10=>"bulbe",80=>"partout",100=>"bras");
	//distances en années lumières
	//angles en degrés
	static $rayonMin=5000; //le bulbe a un diametre entre 7k et 15k al
	static $rayonMax=50000;
	static $angleMin=40;
	static $altitudeMax=1000;
	static $epaisseurBras=2000;
	static $epaisseurBulbe=2800;
	
	//paramétrage des bras: angle de départ & ouverture en degrés (360= spirale atteind le bord en 1 tour)
	static $bras=array(
			array('angle'=>0,'ouverture'=>300),
			array('angle'=>65,'ouverture'=>300),
			array('angle'=>180,'ouverture'=>300),
			array('angle'=>245,'ouverture'=>300)
	);
	
	//année galactique en années terrestres 
	//(subdivision du temps théorique mis par une naine blanche ayant atteind la masse de chandrasehkar soit 1.44 masses solaires
	//pour faire une orbite complète autour du trou noir central galactique à XXX distance)
	static $anneeGalactiqueStandard=15;
}
?>
