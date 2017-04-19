<?php 
namespace BigBang;

abstract class maths_service{
	
	/**
	 * génére un float aléatoire à précision définie
	 * @param integer $Min
	 * @param integer $Max
	 * @param number $round
	 * @return number
	 */
	static function float_rand($Min, $Max, $round=4){
		//validate input
		if ($Min>$Max) { $min=$Max; $max=$Min; }
		else { $min=$Min; $max=$Max; }
		$randomfloat = $min + mt_rand() / mt_getrandmax() * ($max - $min);
		if($round>0)
			$randomfloat = round($randomfloat,$round);
	
			return $randomfloat;
	}
	
	static function probaDescendante($min,$max,$round=4){
		$random=self::float_rand($min, $max,$round);
	
		$x=self::float_rand(0, 1,10);
		$tmp=bcdiv(sin(($x+2))-cos(($x+2)),($x+1.5),10)+1;
		$y=bcdiv($tmp,2,10);
		//echo $random." sur [".$min." -> ".$max."] \n";
		//echo $ratio." ".$x." ".$y;die;
		//$result=$y/$ratio;
	
		$result=bcmul($random,$y,4);
	
		return $result;
	}
}
?>