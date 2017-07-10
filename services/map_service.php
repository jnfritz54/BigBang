<?php 
namespace BigBang;

abstract class map_service{
	
	/***
	 * génère une map selon l'algo diamant-carré
	 * https://fr.wikipedia.org/wiki/Algorithme_Diamant-Carr%C3%A9
	 * @param number $size
	 */
	static function generateMapArray($size){
		
	
		//size of grid to generate, note this must be a
		//value 2^n+1
		//$size = 33;
		//an initial $seed value for the corners of the data
		$seed = 30;
		
		$data = new \SplFixedArray($size);
		for ($x = 0; $x < $size; $x++) {
			$data[$x] = new \SplFixedArray($size);
		}
		//seed the data
		$data[0][0] =  $data[0][$size-1] =  $data[$size-1][0] =
		$data[$size-1][$size-1] = $seed;
		
		$h = 127;//the range (-h -> +h) for the average offset
		
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

		return $data;
	}
	
	static function __old1_generateMapArray($size){
	
		//initialisation du tableau 2d
		$map= array();
		$map=array_fill(0, ($size), array_fill(0, ($size), 0));
	
		//initialisation des 4 coins
		$map[0][0]=200;//rand(0,255);
		$map[0][$size-1]=$map[0][0];
		$map[$size-1][$size-1]=150;//rand(0,255);
		$map[$size-1][0]=$map[$size-1][$size-1];
	
		$i=$size-1;
	
		while($i>1){
			//echo $i." <br>";
			$id=intval($i/2);
			//phase diamant
			for($x=$id;$x<$size;$x+=$i){
				for($y=$id;$y<$size;$y+=$i){
					$moyenne= ($map[$x-$id][$y-$id] + $map[$x-$id][$y+$id] + $map[$x+$id][$y+$id] + $map[$x+$id][$y-$id])/4;
					$map[$x][$y]=$moyenne+rand(-$id,$id);
				}
			}
			//phase carré
			for($x=0;$x<$size;$x+=$id){
				if($x % $i==0){
					$decalage=$id;
				}else{
					$decalage=0;
				}
				for($y=$decalage;$y<$size;$y+=$i){
					$somme=0;
					$n=0;
					if($x>=$id){
						$somme+=$map[$x-$id][$y];
						$n++;
					}
					if($x+$id < $size){
						$somme+=$map[$x+$id][$y];
						$n++;
					}
					if($y>=$id){
						$somme+=$map[$x][$y-$id];
						$n++;
					}
					if($y+$id < $size){
						if(!isset($map[$x][$y+$id])){
							echo "x:".$x." y:".$y." y+id:".($y+$id)."<br/>";
							echo "size:".$size."<br/>";
							$array=$map[$x];
							var_dump(array_keys($array));
							die;
						}
						$somme+=$map[$x][$y+$id];
						$n++;
					}
					//$map[$x][$y]=$somme/$n+rand(0,$id);
					$map[$x][$y]=$somme/$n+rand(-$id,$id);
				}
			}
				
			$i=$id;
		}
	
		return $map;
	}
}
?>