<?php 

class Star_object{

	static $etoileTypes=array('L'=>"naine brune",'D'=>"Naine blanche",'M'=>"naine rouge",
			'K'=>"naine orange",'G'=>'naine jaune','F'=>"jaune/blanche",'A'=>"blanche",'B'=>"blanche/bleue",'O'=>"bleue");
	
	
	
	//les ages sont calculés en milliards d'années
	
	//durée de vie par masse: https://fr.wikipedia.org/wiki/%C3%89volution_stellaire
	static $periodesByType=array(
			"L"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.01","code"=>"FORM"),
					array('label'=>"Naine Brune", 'age_min'=>"0.01",'age_max'=>"9999","code"=>"END"),
				),
			"M"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.01","code"=>"FORM"),
					array('label'=>"Séquence Principale", 'age_min'=>"0.01",'age_max'=>"750","code"=>"SEQPRI"),
					array('label'=>"Naine Bleue", 'age_min'=>"750",'age_max'=>"800","code"=>"SEQSEC"),
					array('label'=>"Naine Blanche", 'age_min'=>"800",'age_max'=>"9999","code"=>"END"),
				),
			"K"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.01","code"=>"FORM"),
					array('label'=>"Séquence Principale", 'age_min'=>"0.01",'age_max'=>"30","code"=>"SEQPRI"),
					array('label'=>"Naine Blanche", 'age_min'=>"30",'age_max'=>"9999","code"=>"END"),
				),
			"G"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.01","code"=>"FORM"),
					array('label'=>"Séquence Principale", 'age_min'=>"0.01",'age_max'=>"8.5","code"=>"SEQPRI"),
					array('label'=>"Sub-géante rouge", 'age_min'=>"8.5",'age_max'=>"9.5","code"=>"SEQSEC"),
					array('label'=>"Naine Blanche avec nébuleuse planétaire", 'age_min'=>"9,5",'age_max'=>"10","code"=>"NEBUL"),
					array('label'=>"Naine Blanche", 'age_min'=>"10",'age_max'=>"9999","code"=>"END"),
			),
			"F"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.01","code"=>"FORM"),
					array('label'=>"Séquence Principale", 'age_min'=>"0.01",'age_max'=>"6.5","code"=>"SEQPRI"),
					array('label'=>"géante rouge", 'age_min'=>"6.5",'age_max'=>"7","code"=>"SEQSEC"),
					array('label'=>"Naine Blanche avec nébuleuse planétaire", 'age_min'=>"7",'age_max'=>"7.5","code"=>"NEBUL"),
					array('label'=>"Naine Blanche", 'age_min'=>"7.5",'age_max'=>"9999","code"=>"END"),
			),
			"A"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.01","code"=>"FORM"),
					array('label'=>"Séquence Principale", 'age_min'=>"0.01",'age_max'=>"0.25","code"=>"SEQPRI"),
					array('label'=>"supergéante rouge", 'age_min'=>"0.25",'age_max'=>"0.3","code"=>"SEQSEC"),
					array('label'=>"Naine Blanche avec nébuleuse planétaire", 'age_min'=>"0.3",'age_max'=>"1","code"=>"NEBUL"),
					array('label'=>"Naine Blanche", 'age_min'=>"1",'age_max'=>"9999","code"=>"END"),
			),
			"B"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.008","code"=>"FORM"),
					array('label'=>"Séquence Principale", 'age_min'=>"0.008",'age_max'=>"0.1","code"=>"SEQPRI"),
					array('label'=>"supergéante rouge", 'age_min'=>"0.1",'age_max'=>"0.11","code"=>"SEQSEC"),
					array('label'=>"Etoile à neutrons", 'age_min'=>"0.11",'age_max'=>"9999","code"=>"END"),
			),
			"O"=>array(
					array('label'=>"Formation", 'age_min'=>"0",'age_max'=>"0.005","code"=>"FORM"),
					array('label'=>"Séquence Principale", 'age_min'=>"0.005",'age_max'=>"0.055","code"=>"SEQPRI"),
					array('label'=>"supergéante rouge", 'age_min'=>"0.055",'age_max'=>"0.06","code"=>"SEQSEC"),
					array('label'=>"Trou Noir", 'age_min'=>"0.06",'age_max'=>"9999","code"=>"END"),
			),
	);
	
	public $masseOrigine;
	
	public $age;
	

}
?>
