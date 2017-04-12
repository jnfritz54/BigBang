<?php 
namespace BigBang;

class Systeme{
	
	//distances en années lumières
	private $rayonMin=7500;
	private $rayonMax=50000;
	private $altitudeMax=1000;
	
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
		$this->angle=$this->float_rand(0,180,4);
		$this->distance=$this->float_rand($this->rayonMin,$this->rayonMax,2);
		$this->altitude=$this->float_rand(0,$this->altitudeMax,2);
	}
	
	
	public function __toString(){
		return "'".$this->name."' '".$this->angle."' '".$this->distance."' '".$this->altitude."'\n";
	}
	public function __toSqlValues(){
		return "('','".$this->name."','".$this->angle."','".$this->distance."','".$this->altitude."')";
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