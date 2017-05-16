<?php 
namespace BigBang;
include('Galaxy_object.php');
include 'maths_service.php';

class Systeme{
	
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
		foreach (galaxy::$probaPosition as $key=>$potentiel){
			if($probaZone<=$key){$zone=$potentiel;break;}
		}
		
		$this->altitude=maths_service::probaDescendante(0,maths_service::float_rand(0,bcdiv(galaxy::$altitudeMax,2),2),4);
		if(rand(0,10)<=5){
			$this->altitude=-$this->altitude;
		}
		
		switch ($zone){
			case "bras":
				//quel bras ?
				$brasIndex=rand(0,count(galaxy::$bras)-1);
				//$this->angle=maths_service::float_rand(galaxy::$angleMin,galaxy::$bras[$brasIndex]['ouverture'],4);
				$this->angle=maths_service::probaDescendante(galaxy::$angleMin,galaxy::$bras[$brasIndex]['ouverture'],4);
				
				//calcul de la distance moyenne pour être dans un bras à l'angle donné
				//en considérant qu'un bras atteind la bordure en un tour complet (à vérifier)
				$distanceMoyenne=bcmul(galaxy::$rayonMax, bcdiv($this->angle,galaxy::$bras[$brasIndex]['ouverture'],4),4);
				$distanceMin=$distanceMoyenne-(galaxy::$epaisseurBras/2);
				$distanceFinale=$distanceMoyenne+maths_service::float_rand(0,galaxy::$epaisseurBras,2);
				
				//$this->distance=$distanceFinale;
				$this->distance=$distanceFinale;
				$this->angle+=galaxy::$bras[$brasIndex]['angle'];
				break;
			
			//le bulbe contient 5 à 10% des étoiles
			case "bulbe":				
				$this->angle=maths_service::float_rand(0,360,4);
				
				//on inverse la proba descendante pour tenir compte de la répartition en boule
				$distanceBrute=bcsub(galaxy::$rayonMin,maths_service::probaDescendante(1,galaxy::$rayonMin,4));
				
				//version 1: théorème de pythagore AB²=AC²+BC²
				$ab2=bcmul($distanceBrute,$distanceBrute);
				$bc=maths_service::float_rand(0, $distanceBrute,4);
				$bc2=bcmul($bc,$bc);
				$ac2=$ab2-$bc2;
				$ac=sqrt($ac2);
				
				
				$this->distance=$ac;
				$this->altitude=$bc;
				
				/**
				// version 2 : loi des sinus: a / sin(α) = b / sin(β) = c / sin(γ) 
				$C=$angleVertical=maths_service::probaDescendante(0,90,4);
				$a=$distanceBrute;
				$A=90;
				$B=180-$C-$A;
				$b=$a*sin(deg2rad($B));
				$c=$a*sin(deg2rad($C));
				
				$this->distance=$c;
				$this->altitude=$b;
				*/
				//$this->altitude=maths_service::probaDescendante(0,maths_service::float_rand(0,bcdiv(galaxy::$epaisseurBulbe,2),2),4);
				if(rand(0,10)<=5){
					$this->altitude=-$this->altitude;
				}
				break;
			case "partout":
			default:
				
				$this->angle=maths_service::float_rand(0,360,4);
				//$this->distance=maths_service::float_rand(galaxy::$rayonMin,galaxy::$rayonMax,2);
				$this->distance=maths_service::probaDescendante(galaxy::$rayonMin,galaxy::$rayonMax,2);
				break;	
		}
		
		
	}
	
	static function RandomString($length) {
	    $keys = array_merge(range('a', 'z'), range('A', 'Z'));
	    $key="";
	    for($i=0; $i < $length; $i++) {
	        $key .= $keys[array_rand($keys)];
	    }
	    return $key;
	}
	


}

?>