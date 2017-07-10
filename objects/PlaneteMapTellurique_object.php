<?php
namespace BigBang;

class PlaneteMapTellurique extends Object{
	
	
	/***
	 * array²
	 * @var array
	 */
	public $data;
	
	public $size=513;
	
	public $height=256;
	
	public $seed=150;
	
	/**
	 * constructeur, /!\ la taille doit être multiple de 2^n+1
	 * @param number $size
	 * @param number $seed
	 */
	public function __construct($size=513,$seed=150){
		$this->generateMapArray($size, $seed);
	}
	
	/***
	 * génère une map selon l'algo diamant-carré
	 * https://fr.wikipedia.org/wiki/Algorithme_Diamant-Carr%C3%A9
	 * @param number $size
	 * @return array[] 
	 */
	public function generateMapArray($size,$seed){
		$this->size=$size;
		//size of grid to generate, note this must be a
		//value 2^n+1
		//$size = 33;
		//an initial $this->seed value for the corners of the data
		$this->seed= $seed;
		
		$data = new \SplFixedArray($size);
		for ($x = 0; $x < $size; $x++) {
			$data[$x] = new \SplFixedArray($size);
		}
		//seed the data
		$data[0][0] =  $data[0][$size-1] =  $data[$size-1][0] =
		$data[$size-1][$size-1] = $this->seed;
		
		$h = $this->height/2;//the range (-h -> +h) for the average offset
		
		//side length is distance of a single square side
		//or distance of diagonal in diamond
		for($sideLength = $size-1;
		//side length must be >= 2 so we always have
		//a new value (if its 1 we overwrite existing values
		//on the last iteration)
		$sideLength >= 2;
		//each iteration we are looking at smaller squares
		//diamonds, and we decrease the variation of the offset
		$sideLength /=2, $h/= 2.0){
			//half the length of the side of a square
			//or distance from diamond center to one corner
			//(just to make calcs below a little clearer)
			$halfSide = $sideLength/2;
		
			//generate the new square values
			for($x=0;$x<$size-1;$x+=$sideLength){
				for($y=0;$y<$size-1;$y+=$sideLength){
					//x, y is upper left corner of square
					//calculate average of existing corners
					$avg =  $data[$x][$y] + //top left
					$data[$x+$sideLength][$y] +//top right
					$data[$x][$y+$sideLength] + //lower left
					$data[$x+$sideLength][$y+$sideLength];//lower right
					$avg /= 4.0;
		
					//center is average plus random offset
					$data[$x+$halfSide][$y+$halfSide] = $avg + rand(-$h,$h);
					//We calculate random value in range of 2h
					//and then subtract $h so the end value is
					//in the range (-h, +h)
		
				}
			}
		
			//generate the diamond values
			//since the diamonds are staggered we only move x
			//by half side
			//NOTE: if the data shouldn't wrap then x < $size
			//to generate the far edge values
			for($x=0;$x<$size;$x+=$halfSide){
				//and y is x offset by half a side, but moved by
				//the full side length
				//NOTE: if the data shouldn't wrap then y < $size
				//to generate the far edge values
				for($y=($x+$halfSide)%$sideLength;$y<$size;$y+=$sideLength){
					//x, y is center of diamond
					//note we must use mod  and add $size for subtraction
					//so that we can wrap around the array to find the corners
					$avg =
					/***$data[($x-$halfSide+$size)%$size][$y] + //left of center
					 $data[($x+$halfSide)%$size][$y] + //right of center
					 $data[$x][($y+$halfSide)%$size] + //below center
					 $data[$x][($y-$halfSide+$size)%$size]; //above center
					 */
					$data[($x-$halfSide+$size-1)%($size-1)][$y] + //left of center
					$data[($x+$halfSide)%($size-1)][$y] + //right of center
					$data[$x][($y+$halfSide)%($size-1)] + //below center
					$data[$x][($y-$halfSide+$size-1)%($size-1)]; //above cente
					$avg /= 4.0;
		
					//new value = average plus random offset
					//We calculate random value in range of 2h
					//and then subtract $h so the end value is
					//in the range (-h, +h)
					$avg = $avg +  rand(-$h,$h)*2;
					//update value for center of diamond
					$data[$x][$y] = $avg;
		
					//wrap values on the edges, remove
					//this and adjust loop condition above
					//for non-wrapping values.
					if($x == 0)   $data[$size-1][$y] = $avg;
					if($y == 0)   $data[$x][$size-1] = $avg;
				}
			}
		}
		
		$this->data=  $data;
		
		return $data;
	}
	
		
	/***
	 * appelle la transformation __toImage et converti en image png pour la servir directement ou l'enregistrer
	 * @param unknown $filename
	 * @return boolean
	 */
	public function __toPng($filename=null){
		$image=$this->__toImage();
		if($filename!=null){
			imagepng($image,"./views/img/map_".$filename);
		}
		return imagepng($image);
	}
	
	/**
	 * transforme le tableau map généré en image gd
	 * @return resource
	 */
	public function __toImage(){
		$image= imagecreate($this->size,$this->size);
		$white=imagecolorallocate($image, 255, 255, 255);
		$bleu=imagecolorallocate($image, 35, 35, 170);
		$brun=imagecolorallocate($image, 80, 50, 50);
		$vert=imagecolorallocate($image, 80,120, 50);
		
		$colors=array();
		for($i=0;$i<255;$i++){
			//$colors[$i]=imagecolorallocate($image, $i, $i, $i);
			//continue;
			if($i<35){
				$colors[$i]=imagecolorallocate($image, 30, $i*1.5,$i*2);
				//$colors[$i]=$bleu;
			}elseif($i<40){
					$colors[$i]=imagecolorallocate($image, 30, $i*1.5,$i*1.5);
			
			}elseif($i<80){
				$colors[$i]=imagecolorallocate($image, $i, $i/1.5,30);
				//$colors[$i]=$brun;
			}elseif($i<140){
				$colors[$i]=imagecolorallocate($image, 40, $i, 50);
				//$colors[$i]=$vert;
			}elseif($i<200){
					$colors[$i]=imagecolorallocate($image, $i*0.75, $i*0.75, $i*0.75);
			}else{
				$colors[$i]=imagecolorallocate($image, $i, $i, $i);
			}
		}
		$colors[255]=$white;
		
		//$map=map_service::generateMapArray($this->size);
		$map=$this->generateMapArray($this->size);
		
		//echo "<table>";
		foreach ($map as $x=>$array){
			//echo "<tr>";
			foreach ($array as $y=>$val){
					
				$color=abs(intval($val));
				//echo "<td>";
				//echo abs(intval($val));
				imagesetpixel($image, intval($x),intval($y), $colors[$color]);
				//echo "</td>";
			}
			//echo "<br/>";
			//echo "</tr>";
		}
		return $image;
	}
	
	// surcharge des méthodes de Object
	public function __toArray(){
		return $this->data;
	}
	
	public function __toString(){
		return serialize($this->data);
	}
	
	public function __toSqlValues(){
		return new \Exception("Not implemented or incoherent method");
	}
	public function __loadFromSqlRow(){
		return new \Exception("Not implemented or incoherent method");
	}
	
	public function __toHtml(){
		header("Content-type: image/png");
		return $this->__toPng();
	}
}
?>