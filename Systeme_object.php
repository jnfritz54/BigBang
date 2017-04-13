<?php 
namespace BigBang;

class Systeme{
	
	//probabilité de positionnement dans une galaxie à 4 bras spiraux d'origine équi-angulaire
	private $probaPosition=array(10=>"bulbe",60=>"partout",70=>"bras0",80=>"bras1",90=>"bras2",100=>"bras3");
	//distances en années lumières
	//angles en degrés
	private $rayonMin=5000; //le bulbe a un diametre entre 7k et 15k al
	private $rayonMax=50000;
	private $angleMin=45;
	private $altitudeMax=1000;
	private $epaisseurBras=1000;
	
	public $id;
	public $name;
		
	public $angle;
	public $distance;
	public $altitude;
	
	public function __construct($name=null){
		if($name==null){
			$name=$this->RandomString(10);	
		}
		
		$this->name=$name;
		$this->create_coordinates();
	}
	
	
	public function __toString(){
		return "'".$this->name."' '".$this->angle."' '".$this->distance."' '".$this->altitude."'\n";
	}
	public function __toSqlValues(){
		return "('','".$this->name."','".$this->angle."','".$this->distance."','".$this->altitude."')";
	}
	
	private function create_coordinates(){
		//déterminer la zone (bras ou corps)
		$probaZone=rand(0,100);
		$zone="partout";
		foreach ($this->probaPosition as $key=>$potentiel){
			if($probaZone<=$key){$zone=$potentiel;break;}
		}
		
		switch ($zone){
			case "bras0":
				$this->angle=$this->float_rand($this->angleMin,360,4);
				//calcul de la distance moyenne pour être dans un bras à l'angle donné
				//en considérant qu'un bras atteind la bordure en un tour complet (à vérifier)
				$distanceMoyenne=bcmul($this->rayonMax, bcdiv($this->angle,'360',4),4);
				$distanceMin=$distanceMoyenne-($this->epaisseurBras/2);
				$distanceFinale=$distanceMoyenne+$this->float_rand(0,$this->epaisseurBras,2);
				
				//$this->distance=$distanceFinale;
				$this->distance=$distanceFinale;
				$this->altitude=$this->float_rand(0,$this->altitudeMax,2);
				break;
			case "bras1":
				$this->angle=$this->float_rand($this->angleMin,360,4);
				//calcul de la distance moyenne pour être dans un bras à l'angle donné
				//en considérant qu'un bras atteind la bordure en un tour complet (à vérifier)
				$distanceMoyenne=bcmul($this->rayonMax, bcdiv($this->angle,'360',4),4);
				$distanceMin=$distanceMoyenne-($this->epaisseurBras/2);
				$distanceFinale=$distanceMoyenne+$this->float_rand(0,$this->epaisseurBras,2);
			
				//$this->distance=$distanceFinale;
				$this->distance=$distanceFinale;
				$this->altitude=$this->float_rand(0,$this->altitudeMax,2);
				$this->angle+=90;
				break;
			case "bras2":
				$this->angle=$this->float_rand($this->angleMin,360,4);
				//calcul de la distance moyenne pour être dans un bras à l'angle donné
				//en considérant qu'un bras atteind la bordure en un tour complet (à vérifier)
				$distanceMoyenne=bcmul($this->rayonMax, bcdiv($this->angle,'360',4),4);
				$distanceMin=$distanceMoyenne-($this->epaisseurBras/2);
				$distanceFinale=$distanceMoyenne+$this->float_rand(0,$this->epaisseurBras,2);
					
				//$this->distance=$distanceFinale;
				$this->distance=$distanceFinale;
				$this->altitude=$this->float_rand(0,$this->altitudeMax,2);
				$this->angle+=180;
				break;
			case "bras3":
				$this->angle=$this->float_rand($this->angleMin,360,4);
				//calcul de la distance moyenne pour être dans un bras à l'angle donné
				//en considérant qu'un bras atteind la bordure en un tour complet (à vérifier)
				$distanceMoyenne=bcmul($this->rayonMax, bcdiv($this->angle,'360',4),4);
				$distanceMin=$distanceMoyenne-($this->epaisseurBras/2);
				$distanceFinale=$distanceMoyenne+$this->float_rand(0,$this->epaisseurBras,2);
					
				//$this->distance=$distanceFinale;
				$this->distance=$distanceFinale;
				$this->altitude=$this->float_rand(0,$this->altitudeMax,2);
				$this->angle+=270;
				break;
			//le bulbe contient 5 à 10% des étoiles
			case "bulbe":
				$this->altitude=$this->float_rand(0,$this->altitudeMax,2);
				$this->angle=$this->float_rand(0,360,4);
				$this->distance=$this->float_rand(1,$this->rayonMin,2);
				break;
			case "partout":
			default:
				$this->altitude=$this->float_rand(0,$this->altitudeMax,2);
				$this->angle=$this->float_rand(0,360,4);
				$this->distance=$this->float_rand($this->rayonMin,$this->rayonMax,2);
				break;	
		}
		
		
	}
	
	private function float_rand($Min, $Max, $round=4){
		//validate input
		if ($Min>$Max) { $min=$Max; $max=$Min; }
		else { $min=$Min; $max=$Max; }
		$randomfloat = $min + mt_rand() / mt_getrandmax() * ($max - $min);
		if($round>0)
			$randomfloat = round($randomfloat,$round);
	
			return $randomfloat;
	}
	
	private function RandomString($length) {
	    $keys = array_merge(range('a', 'z'), range('A', 'Z'));
	    $key="";
	    for($i=0; $i < $length; $i++) {
	        $key .= $keys[array_rand($keys)];
	    }
	    return $key;
	}

}

?>