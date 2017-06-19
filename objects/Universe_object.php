<?php
namespace BigBang;
/**
 * classe de paramétrage des grandeurs universelles
 * @author jnfritz
 *
 */

abstract class Universe{
	
	static $c=299792458; //vitesse de la lumière dans le vide absolu (m/s)
	static $G=6.67408E-11; //constante de gravitation (N·m2·kg-2)
	static $StefanBoltzmann=5.670373E-8;
	
	static $ageOfUniverse=13.798 ; //en M d'années
	
	static $astron=300000000; //1 astron vaut 300 000 000km (orbite mercure de 0.155as à 0.235as / orbite de cérès: 1450as)
	

}
?>