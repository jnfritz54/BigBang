<?php

namespace BigBang;

class MySQLi_2 extends \mysqli{
	
	public function query($query){
		$retour = parent::query($query);
		if ($retour === false){
			var_dump($this->error);
			print_r("requete : ".$query);
			error_log(print_r($this->error, true));
			error_log(print_r($query, true));
			die("Erreur lors de l'execution du script !");
		}
		return $retour;
	}
}