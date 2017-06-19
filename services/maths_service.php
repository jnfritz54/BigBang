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
		do{
			$randomfloat = $min + mt_rand() / mt_getrandmax() * ($max - $min);
		}while($randomfloat<$min || $randomfloat>$max);
		if($round>0){
			$randomfloat = round($randomfloat,$round);
		}
			return $randomfloat;
	}
	
	static function old_probaDescendante($min,$max,$round=4){
		$random=self::float_rand($min, $max,$round);
	
		$x=self::float_rand(0, 1,10);
	//	$tmp=bcdiv(sin(($x+2))-cos(($x+2)),($x+1.5),10)+1;
		$tmp=bcdiv(1,(pow($x,3)+1),10);
		$y=bcdiv($tmp,2,10);
		
		$result=bcmul($random,$y,4);
	
		return $result;
	}
	
	static function probaDescendante($min,$max,$round=4){
		$random=self::float_rand(0, $max-$min,$round);

		$x=self::float_rand(0, 1,10);
		//	$tmp=bcdiv(sin(($x+2))-cos(($x+2)),($x+1.5),10)+1;
		$tmp=bcdiv(1,(pow($x,3)+1),10);
		$y=$tmp;
	
		$result=$min+bcmul($random,$y,4);
		//echo $random." ".$x." ".$y." ".$result;
		return $result;
	}
	
	static function probaDescendanteLineaire($min,$max,$round=4){
		$random=self::float_rand(0, $max-$min,$round);

		$x=self::float_rand(0, 1,10);
		//	$tmp=bcdiv(sin(($x+2))-cos(($x+2)),($x+1.5),10)+1;		
		$y=1-$x;
	
		$result=$min+bcmul($random,$y,4);		
		return $result;
	}	
		
	static function polaireToCartesien($rayon,$angle){
		$x=$rayon*cos(deg2rad($angle));
		$y=$rayon*sin(deg2rad($angle));

		return array("x"=>$x,"y"=>$y);
	}
	
	static function cartesienToPolaire($x,$y){
		$rayon=sqrt(bcadd(pow($x,2), pow($y,2),4));
		$angle=rad2deg(atan(bcdiv($y,$x,4)));
		return array("rayon"=>$rayon,"angle"=>$angle);
	}
	
	static function exp2int($exp) {
		if(!strstr(strtolower($exp), 'e')){return $exp;}
		list($mantissa, $exponent) = explode("e", strtolower($exp));
		if($exponent=='') return $exp;
		list($int, $dec) = explode(".", $mantissa);
		bcscale (abs($exponent-strlen($dec)));
		return bcmul($mantissa, bcpow("10", $exponent));
	}
	
	static function kelvin2celsius($K){
		return $K-273;
	}
	
	static function celsius2kelvin($C){
		return $C+273;
	}
	
	/**
	 * calcule une orbite probable, valable à priori pour les étoiles de masse solaire
	 * https://fr.wikipedia.org/wiki/Loi_de_Titius-Bode
	 * @todo: chercher une extrapolation en fonction de la masse stellaire
	 * @param unknown $rang
	 */
	static function planetesLoiTitusBode($rang,$randomized=true){
		if($rang==0){$pow=-INF;
		}else{$pow=$rang-1;	}
		//echo "rang ".$rang." pow ".$pow." ";
		$R=0.4+0.3*pow(2,$pow); //calcul en dixième d'ua
		//echo " R: ".$R." \n";
		//$R=bcdiv($R,2,4);//divise par 2 pour transformer les 1/10ua en 1/10astrons
		
		//randomization
		if($randomized){
			$delta=self::float_rand(0, bcdiv($R,8,5));
			$R=$R- bcdiv($R,16,5)+$delta;
		}
		return $R; //renvoie la distance en astrons
		
	}
	
	/***
	 * tentative d'extrapolation de la loi de titus bode pour prendre en compte la masse/catégorie de l'étoile orbitée
	 * afin de créer des systemes plus réalistes en se s'inspirant des relations de calcul des rayons et luminosités
	 * @param unknown $rang
	 * @param unknown $MMo ratio masse étoile/masse solaire (= masse étoile puisqu'exprimée en masses solaires)
	 * @param float $expandSystem float(0,1) commun au système permettant d'étendre ou de contracter la distribution des planètes
	 * @param string $randomized
	 */
	static function planetesLoiTitusBodeExtanded($rang,$MMo,$expandSystem=0.5,$randomized=false){
		if($rang==0){$pow=-INF;
		}else{$pow=$rang-1;	}
					
		if($MMo<0.8){
			$min=0.01;			
			$coeff=0.1;
			$puissance=1.6;
		}elseif($MMo<=2){
			$min=0.4;
			$coeff=0.3;
			$puissance=2;
		}elseif($MMo<=20){
			$min=0.8;
			$coeff=0.4;
			$puissance=2;
		}else{
			$min=0.8;
			$coeff=0.5;
			$puissance=2;
		}
		/***
		$min=0.4*$MMo;
		$min=0.5*$min+($expandSystem*$min);
		$coeff=0.3*$MMo;
		
		$puissance=1.9+(0.2*$expandSystem);
		*/
		//echo "rang ".$rang." pow ".$pow." ";
		$R=$min+$coeff*pow($puissance,$pow); //calcul en dixième d'ua
		//$R=0.011+bcmul(0.0042,pow(1.85,$pow),4);
		//echo " R: ".$R." \n";
		$R=bcdiv($R,2,4);//divise par 2 pour transformer les 1/10ua en 1/10astrons
	
		//randomization
		if($randomized){
			$delta=self::float_rand(0, bcdiv($R,8,5));
			$R=$R- bcdiv($R,16,5)+$delta;
		}
		return $R; //renvoie la distance en astrons
	
	}
}
?>