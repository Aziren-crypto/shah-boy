<?
class GameField
{
	public static $y = 11;
	public static $x = 11;
	
	public static $whiteFields = [];
	
	public static $arrObject = [];
	public static $matrix = [];
	
	public static $arrStage = ['stable', 'solution', 'collision'];
	
	public static $stage = ''; // stable solution collision
	
	public static function turn(){
		;
	}
	
	public static function makeWhiteFields(){
		for ($y = 0; $y <= self :: $y; $y ++){
			if(isset($color)){
				if($color == 'white'){
					$color = 'gray';
				}elseif($color == 'gray'){
					$color = 'white';
				}
			}else{
				$color = 'white';
			}
			for ($x = 0; $x <= self :: $x; $x ++){
				if($color == 'white'){
					self :: $whiteFields[] = $y.'_'.$x;
					$color = 'gray';
				}elseif($color == 'gray'){
					$color = 'white';
				}
			}
		}
	}
	
	public static function draw(){
		echo '<div class="container">';
		for ($y = 0; $y <= self :: $y; $y ++){
			if(isset($color)){
				if($color == 'white'){
					$color = 'gray';
				}elseif($color == 'gray'){
					$color = 'white';
				}
			}else{
				$color = 'white';
			}
			
			echo '<div class="tr">';
			for ($x = 0; $x <= self :: $x; $x ++){

				echo '<div ';
				if(($y < 2 && $x < 2) || ($y > 9 && $x < 2) || ($y < 2 && $x > 9) || ($y > 9 && $x > 9)){
					echo 'class="td black">';
				}else{
					echo 'class="td '.$color.'" id="td_'.$y.'_'.$x.'">';
					
				}
				if($color == 'white'){
					self :: $whiteFields[] = $y.'_'.$x;
					$color = 'gray';
				}elseif($color == 'gray'){
					$color = 'white';
				}
				/*echo '<span class="unit"';
				
				foreach($arAttr as $attr){
					if(isset($arObj[$objWeb[$k][$k2]][$attr])){
						echo ' '.$attr.'="'.$arObj[$objWeb[$k][$k2]][$attr].'"';
					}
				}
				echo '>'.$v2.'</span>';*/
				if(is_object(self :: $matrix[$y][$x])){
					echo '<div data-type="'.get_class(self :: $matrix[$y][$x]).'" data-x="'.self :: $matrix[$y][$x] -> x.'" data-y="'.self :: $matrix[$y][$x] -> y.'" data-color="'.self :: $matrix[$y][$x] -> color.'"';
					if(self :: $matrix[$y][$x] -> class = 'unit'){
						echo ' class="relative unit';
						if(isset(self :: $matrix[$y][$x] -> controllable) && self :: $matrix[$y][$x] -> controllable == true)
							echo ' controllable';
						echo '"';
					}
					echo '>';
					
					// < TEST
					/*echo '<div class="sign">';
					echo self :: $matrix[$y][$x] -> id;
					echo '</div>';*/
					// > TEST
					
					/*echo '<div class="sign unvisible">↷</div>';*/
					echo '<img class="iconMain';
					if(!empty(self :: $matrix[$y][$x] -> turnableSprite)){
						echo ' turnable '.self :: $matrix[$y][$x] -> direction;
					}
					echo '" src="'.self :: $matrix[$y][$x] -> img.'" alt="'.self :: $matrix[$y][$x] -> name.'" />';
					echo '<div class="id">'.self :: $matrix[$y][$x] -> id.'</div>';
					echo '</div>';
				}/*elseif(($y < 2 && $x < 2) || ($y > 9 && $x < 2) || ($y < 2 && $x > 9) || ($y > 9 && $x > 9)){
					echo '<div class="black"></div>';
				}*/else{
					echo 
					'<div class="relative"></div>';
				}
				echo '</div>';
			}
			echo '</div>';
		}
		//echo '<pre>'; print_r(self :: $whiteFields); echo '</pre>';
		echo '</div>';
		/*foreach(self::$matrix as $k => $v){
			echo '<div class="tr">';
			foreach($v as $k2 => $v2){
				echo '<div class="td">';*/
				/*echo '<span class="unit"';
				
				foreach($arAttr as $attr){
					if(isset($arObj[$objWeb[$k][$k2]][$attr])){
						echo ' '.$attr.'="'.$arObj[$objWeb[$k][$k2]][$attr].'"';
					}
				}
				echo '>'.$v2.'</span>';*/
				/*if(is_object($v2)){
					echo '<img src="'.$v2 -> $img.'" alt="альтернативный текст" />';
				}
				echo '</div>';
			}
			echo '</div>';
		}*/
    }
}

?>