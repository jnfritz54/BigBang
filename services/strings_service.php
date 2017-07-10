<?php 
namespace BigBang;

abstract class strings_service{
	
	static function numberFormat($number,$precision=2){
		return number_format ( $number, $precision , "," , " " );
	}
	
	static function generatePrononcableString($min_length,$max_length){
		
		$length=rand($min_length,$max_length-2);
		
		$conso=array('b','c','ch','d','f','g','gn','gu','h','j','k','l','m','n','p','ph','qu','r','s','t','v','w','x','z');
		$vocal=array('a','e','i','o','u','y','oi','ou','ai','eu');
		//$replace=array('quu'=>'k',"guou"=>"gou");
		$string='';

		srand ((double)microtime()*1000000);
		$max = $length/2;
		for($i=1; $i<=$max; $i++){
			$c=$conso[rand(0,22)];
			$v=$vocal[rand(0,9)];
			$string.=$c.$v;
			if(count($c)>1){$i++;}
			if(count($v)>1){$i++;}
		}

		if(count($string)>$max_length){
			$string=substr($string, 0,$max_length);
		}
		
		return $string;
	}
}
	