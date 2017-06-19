<?php 
namespace BigBang;

abstract class strings_service{
	
	static function numberFormat($number,$precision=2){
		return number_format ( $number, $precision , "," , " " );
	}
}
	