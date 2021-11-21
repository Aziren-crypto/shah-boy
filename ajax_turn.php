<?
/*ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/

require_once ('classes/GameField.php');
require_once ('classes/Direction.php');
require_once ('classes/CObject.php');
require_once ('classes/Party.php');

session_start();

function addToArr($array, $value, $idKey=false){
	if(!$idKey){
		if(!empty($array)){
			$array[] = $value;
		}else{
			$array = [$value];
		}
	}else{
		if(!empty($array)){
			$array[$value] = $value;
		}else{
			$array = [$value => $value];
		}
	}
	return $array;
}

function print_pre($array, $name=''){
	echo '<br>'.$name;
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

function isConflict($arr){
	$Error = false;
	$arConflict = false;
	if(count($arr['begin']) > 1 || count($arr['static']) > 1){
		$Error = 'multi begin or static';
	}
	if((count($arr) > 1) || (count($arr['end']) > 1)){
		$arConflict = [];
		foreach($arr as $k => $v){
			foreach($v as $k2 => $v2){
				$arConflict[$v2] = $k;
			}
		}
	}
	return ['error' => $Error, 'arConflict' => $arConflict];
}

//if(isset($_COOKIE['name'])){
if(isset($_SESSION['idNext'])){
	$GLOBALS['idNext'] = $_SESSION['idNext'];
}else{
	$GLOBALS['idNext'] = 1;
}

/*require_once ('classes/GameField.php');

require_once ('classes/Direction.php');

require_once ('classes/CObject.php');*/

class CObjectAll
{
	//global GameField;
	public static $objects = '';//GameField::$arrObject;
	
	public static function create() {
		self::$objects = GameField::$arrObject;
    }
	
	public static function tryRemove() {	// удалить попытки передвижения у всех юнитов
		self::create();
		foreach(self::$objects as $k => $v){
			//GameField::$arrObject[$k] -> PrepareMove($arr[1]);
			GameField::$arrObject[$k] -> yTry = false;
			GameField::$arrObject[$k] -> xTry = false;
			GameField::$arrObject[$k] -> dirTry = false;
		}
    }
}

//require_once ('classes/CObject.php');



if(!empty($_REQUEST['restart'])){
	$firstStep = true;
	unset($_SESSION['GameField']);
	$GLOBALS['idNext'] = 1;
	
	$t1 = new Head('black', 0, 6);
	$t1 = new Plane('black', 0, 5);
	$t1 = new Tank('black', 1, 6);
	$t1 = new soldier('black', 1, 5);
	$t1 = new soldier('black', 1, 4);
	$t1 = new soldier('black', 1, 3);
	$t1 = new soldier('black', 1, 2);
	$t1 = new soldier('black', 1, 7);
	$t1 = new soldier('black', 1, 8);
	$t1 = new soldier('black', 1, 9);
	$t1 = new soldier('black', 2, 6);
	$t1 = new soldier('black', 2, 5);
	$t1 = new soldier('black', 2, 4);
	$t1 = new soldier('black', 2, 3);
	$t1 = new soldier('black', 2, 2);
	$t1 = new soldier('black', 2, 7);
	$t1 = new soldier('black', 2, 8);
	$t1 = new soldier('black', 2, 9);
	$t1 = new cannon('black', 0, 2);
	$t1 = new cannon('black', 0, 9);
	$t1 = new mashinegun('black', 0, 4);
	$t1 = new mashinegun('black', 0, 7);
	$t1 = new horse('black', 0, 3);
	$t1 = new horse('black', 0, 8);
	
	$t1 = new Tank('red', 10, 6);
	$t1 = new Head('red', 11, 6);
	$t1 = new Plane('red', 11, 5);
	$t1 = new soldier('red', 10, 5);
	$t1 = new soldier('red', 10, 4);
	$t1 = new soldier('red', 10, 3);
	$t1 = new soldier('red', 10, 2);
	$t1 = new soldier('red', 10, 7);
	$t1 = new soldier('red', 10, 8);
	$t1 = new soldier('red', 10, 9);
	$t1 = new soldier('red', 9, 5);
	$t1 = new soldier('red', 9, 4);
	$t1 = new soldier('red', 9, 3);
	$t1 = new soldier('red', 9, 2);
	$t1 = new soldier('red', 9, 7);
	$t1 = new soldier('red', 9, 8);
	$t1 = new soldier('red', 9, 9);
	$t1 = new soldier('red', 9, 6);
	$t1 = new cannon('red', 11, 2);
	$t1 = new cannon('red', 11, 9);
	$t1 = new mashinegun('red', 11, 4);
	$t1 = new mashinegun('red', 11, 7);
	$t1 = new horse('red', 11, 3);
	$t1 = new horse('red', 11, 8);
	
	$t1 = new soldier('red', 3, 9);
	$t1 = new soldier('black', 4, 9);
	$t1 = new Tank('red', 5, 4);
	$t1 = new Tank('red', 3, 4);
	$t1 = new soldier('red', 4, 6);
	$t1 = new soldier('red', 3, 6);
	$t1 = new soldier('black', 4, 8);
	$t1 = new soldier('black', 4, 7);
	$t1 = new soldier('black', 5, 7);
	$t1 = new Head('red', 5, 8);
	
	$t1 = new Plane('red', 3, 0);
	
	//echo '<pre>$t1'; print_r($t1); echo '</pre>';
	
	$_SESSION['idNext'] = $GLOBALS['idNext'];
}



if(!empty($_SESSION['GameField']['arrObject']))
	GameField::$arrObject = $_SESSION['GameField']['arrObject'];
if(!empty($_SESSION['GameField']['matrix']))
	GameField::$matrix = $_SESSION['GameField']['matrix'];
if(!empty($_SESSION['GameField']['stage']))
	GameField::$stage = $_SESSION['GameField']['stage'];


/*echo '<pre>$arrObject: ';
print_r(GameField::$arrObject);
echo '</pre>';
echo '<pre>$_REQUEST[action]: ';
print_r($_REQUEST['action']);
echo '</pre>';
echo '<pre>$_SESSION[GameField]: ';
print_r($_SESSION['GameField']);
echo '</pre>';*/

// < обработка  хода
//GameField::$stage
if(isset($presetPhase)){
	$phaseRequest = $presetPhase;
}elseif(isset($_REQUEST['phase'])){
	$phaseRequest = $_REQUEST['phase'];
}

// очистить намерения
if($phaseRequest == 'attempt'){
	//echo '<br>$phaseRequest == collision';
	//echo '<br>CObjectAll::tryRemove()';
	//CObjectAll::tryRemove();
}

if(!empty($_REQUEST['action'])){
	if($phaseRequest == 'collision'){
		foreach($_REQUEST['action'] as $k => $v){
			$arr = explode('_', $v);
			if($arr[0] == 'move'){
				GameField::$arrObject[$k] -> PrepareMove($arr[1]);
			}elseif($arr[0] == 'turn'){
				GameField::$arrObject[$k] -> rotateToDir($arr[1]);
				GameField::$arrObject[$k] -> clearTry();
			}	
		}
	}
}

if($phaseRequest == 'attempt'){
	// < передвижение юнитов
	// <block 1>
	/*echo '<pre>GameField::$matrix:1 ';
	print_r(GameField::$matrix);
	echo '</pre>';*/
	/*echo '<pre>GameField::$arrObject:1 ';
	print_r(GameField::$arrObject);
	echo '</pre>';*/
	
	echo '<pre>arrActionLine: ';
	print_r($_SESSION['arrActionLine']);
	echo '</pre>';
	
	if(!empty($_SESSION['arrActionLine'])){
		foreach($_SESSION['arrActionLine'] as $k => $v){
			/*echo '<pre>arrActionLine: ';
			print_r($v);
			echo '</pre>';*/
			GameField::$arrObject[$v] -> MoveWithoutTest();
		}
	}
	
	/*echo '<pre>GameField::$matrix: ';
	print_r(GameField::$matrix);
	echo '</pre>';*/
	echo '<pre>GameField::$arrObject: ';
	print_r(GameField::$arrObject);
	echo '</pre>';
	// </block 1>
	foreach(GameField::$arrObject as $k => $obj){
		if(!empty($obj -> dirTry)){
			echo '<br>objId: '.$k;
			$obj -> TryMove($arr[1]);
		}
		//$arr = explode('_', $v);
		//if($arr[0] == 'move'){
		//	GameField::$arrObject[$k] -> TryMove($arr[1]);
		//}elseif($arr[0] == 'turn'){
		//	GameField::$arrObject[$k] -> rotateToDir($arr[1]);
		//}	
	}
	// > передвижение юнитов
	
	// < фикс ситуации, когда юнит не отображается после передвижения
	foreach(GameField::$arrObject as $k => $v){
		GameField::$matrix[$v -> y][$v -> x] = $v;
	}
	// > фикс ситуации, когда юнит не отображается после передвижения

}
	
if($phaseRequest == 'collision'){
	echo '<br>$phaseRequest == attempt';
	$arAttemptMatrix = [];
	$arAttemptObj = [];
	$arCollisionMatrix = [];
	$arCollisionType = [];
	foreach(GameField::$arrObject as $k => $obj){
		if(!empty($obj -> yTry) || !empty($obj -> xTry)){
			$arAttemptMatrix[$obj -> yTry][$obj -> xTry] = addToArr($arAttemptMatrix[$obj -> yTry][$obj -> xTry], $k);
			$arAttemptObj[$k] = ['y' => $obj -> yTry, 'x' => $obj -> xTry];
			$arCollisionMatrix[$obj -> yTry][$obj -> xTry]['end'] = addToArr($arCollisionMatrix[$obj -> yTry][$obj -> xTry]['end'], $k);
			$arCollisionMatrix[$obj -> y][$obj -> x]['begin'] = addToArr($arCollisionMatrix[$obj -> y][$obj -> x]['begin'], $k);
			$arCollisionType['end'] = addToArr($arCollisionType['end'], $k);
			$arCollisionType['begin'] = addToArr($arCollisionType['begin'], $k);
		}else{
			$arCollisionMatrix[$obj -> y][$obj -> x]['static'] = addToArr($arCollisionMatrix[$obj -> y][$obj -> x]['static'], $k);
			$arCollisionType['static'] = addToArr($arCollisionType['static'], $k);
		}
	}
	echo '<div>$arCollisionType</div>';
	print_pre($arCollisionType);
	echo '<div>$arCollisionMatrix</div>';
	print_pre($arCollisionMatrix);
	$arConflict = [];
	$arConflictTypes = ['def-agr' => [], 'agr-agr' => [], 'agr-esc' => [], 'multi' => []];
	
	$isError = false;
	foreach($arCollisionMatrix as $k => $y){
		foreach($y as $k2 => $x){
			$arConf = isConflict($x);
			echo '<div>$y: '.$k.' $x: '.$k2.' $arConf</div>';
			print_pre($arConf);
			if($arConf['error'] != false){
				$isError = $arConf['error'];
			}elseif($arConf['arConflict'] != false && count($arConf['arConflict']) > 0){
				$arConflict[$k][$k2] = $arConf['arConflict'];
			}
		}
	}
	if($isError != false){
		echo '<div>ERROR: '.$isError.'</div>';
	}else{
		echo '<div>$arConflict</div>';
		print_pre($arConflict);
		
		
		echo '<script>$(document).ready(function() {';
		foreach($arConflict as $k => $y){
			foreach($y as $k2 => $x){
				//GameField::$matrix[$k][$k2] = $this;
				echo '$(\'#td_'.$k.'_'.$k2.' .relative\').append(\'<div class="conflict"></div>\');';
				
				if(count($x) == 2){
					$currentType = [];
					foreach ($x as $k3 => $val){
						$currentType[] = $val;
					}
					//if static begin
					if(($currentType[0] == 'static' && $currentType[1] == 'end') || ($currentType[1] == 'static' && $currentType[0] == 'end')){
						$arConflictTypes['def-agr'][] = $x;
					}elseif($currentType[0] == 'end' && $currentType[1] == 'end'){
						$arConflictTypes['agr-agr'][] = $x;
					}elseif(($currentType[0] == 'begin' && $currentType[1] == 'end') || ($currentType[1] == 'begin' && $currentType[0] == 'end')){
						$arConflictTypes['agr-esc'][] = $x;
					}
				}else{
					$arConflictTypes['multi'][] = $x;
				}
			}
		}
		echo '});</script>';
		
		echo '<div>$arConflictTypes</div>';
		
		$arrActionLine = [];
		
		if(!empty($arConflictTypes['multi'])){
			
		}elseif(!empty($arConflictTypes['def-agr'])){
			
		}elseif(!empty($arConflictTypes['agr-agr'])){
			
		}elseif(!empty($arConflictTypes['agr-esc'])){
			foreach($arConflictTypes['agr-esc'] as $k => $v){
				$id = array_search('end', $v);
				if($id !== false){
					//GameField::$arrObject[$id] -> MoveWithoutTest();
					$arrActionLine[] = $id;
					//проверить на ситуацию, если манёвр нескольких юнитов заканчивается на одном поле
				}
				
			}
		}
		
		$_SESSION['arrActionLine'] = $arrActionLine;
		print_pre($arConflictTypes);
	}
}
	
	
	/*foreach($arAttempt as $y => $yContent){
		foreach($y as $x => $xContent){
			if(count($xContent) > 1){
				$arConflict[$y][$x] = $xContent;
				new Conflict($y, $x, 'u');
				//GameField::$matrix[$y][$x];
			}
		}
	}*/



// > обработка  хода

$_SESSION['GameField']['arrObject'] = GameField :: $arrObject;
$_SESSION['GameField']['matrix'] = GameField :: $matrix;
$_SESSION['GameField']['stage'] = GameField :: $stage;
$_SESSION['GameField']['whiteFields'] = GameField :: $whiteFields;

GameField::draw();

if(empty($phaseRequest))
	$phaseRequest = 'attempt';
	
if($phaseRequest == 'collision'){
	$phase = 'attempt';
	$button = 'Вычислить столкновения';
}else{
	$phase = 'collision';
	$button = 'Ввести решения';
}
?>
<form id="actions" style="display: none">
	<h3>Фаза: <?=$phaseRequest?></h3>
	
	<?//if($phaseRequest == 'attempt' || !empty($firstStep)){
	if($phaseRequest == 'attempt'){
		echo '<fieldset>';
		if(is_array($_REQUEST['action'])){
			foreach($_REQUEST['action'] as $k => $v){
				echo '<input type="text" name="action['.$k.']" class="object_'.$k.'" value="'.$v.'">';
			}
		}
		foreach(GameField::$arrObject as $k => $obj){
			/*echo '<pre>$obj: ';
			print_r($obj);
			echo '</pre>';*/
			if((!empty($obj -> dirTry)) && empty($_REQUEST['action'][$k])){
				echo '<input type="text" name="action['.$k.']" class="object_'.$k.'" value="move_'.$obj -> dirTry.'">';
			}
		}
		echo '</fieldset>';

	}?>
	
	<input type="hidden" name="phase" value="<?=$phase?>">
	<input id="turnEnd" type="submit" name="turnEnd" value="<?=$button?>">
</form>

<?echo '<br>$arrObject<br>';
//print_r(GameField::$arrObject);

/*foreach($web as $k => $v){
	echo '<div class="damp" style="display: block">'.$k.': ';
	foreach($v as $k2 => $v2){
		echo '<span>';
		echo $k2.': '.$v2.'; ';
		echo '</span>';
	}
	echo '</div>';
}*/

if(!empty($message)){
	echo '<dev class="message">';
	echo $message;
	echo '</dev>';
}
echo '</dev>';
echo '<dev class="status" style="display: inline-block; vertical-align: top">';
?>
<style>

/*
.gol td{
	border: 1px solid black;
	width: 20px;
	height: 20px;
}

td.black{
	background-color: black;
}

span.unit{
	z-index: 99999;
	display: block;
}
span.unit:hover::after {
	content:
	<?$arAttr = ['Hlt', 'HltMax', 'Dmg', 'Am', 'As', 'Dm', 'Ds'];
	foreach($arAttr as $attr){
		echo '"'.$attr.':" attr('.$attr.') " | "';
	}?>;
	background-color: #c2c2c2;
	z-index: 99999;

	position: absolute;
}

.damp div{
	display: inline-block;
}

table td .colordiv{
	height: 100%;
	width: 100%;
	
}

.console button{
	width: 50px;
}

.box {
  position: relative;
  width: 60px;
  height: 60px;
  border: 1px solid red;
}
.raz{
  border: 1px solid red;
  position: absolute;
}
.raz.rt {
  right: 0;
}
.raz.rd {
  right: 0;
  bottom: 0;
}
.raz.lt {
  left: 0;
}
.raz.ld {
  left: 0;
  bottom: 0;
}

.td{
	display: inline-block;
	height: 50px;
	width: 50px;
	//border: 1px solid gray;
	vertical-align: middle;
}

.td span{
	display: inline-block;
}

.tr .td img.iconMain{
	width: 100%;
	height: 100%;
	max-height: 100%;
	max-width: 100%;
}

.tr .td .unit.selected img.iconMain{
	animation: 0.5s blinker linear infinite;
	border: 2px solid red;
	box-sizing:border-box;
}

.tr .td .unit .id{
	display: none;
}

img.arrowTry, img.arrowDir{
	position: absolute;
}

img.arrowDir{
	z-index: 100;
	width: 100%;
	left: 0;
}

img.arrowTry{
	z-index: 200;
	pointer-events: none;
	width: 100%;
}

div.unit, div.relative{
	position: relative;
}

@keyframes blinker {
	0% { opacity: 1.0; }
	50% { opacity: 0.00; }
	100% { opacity: 1.0; }
}

.unvisible{
	display: none;
}

.sign{
	right: 0;
	border: 1px solid red;
	position: absolute;
	background-color: ebf2bb;
	z-index: 200;
	padding: 2px;
}

form#actions{
	margin: 20px;
}

form#actions fieldset{
	display: inline;
}

form#actions input{
	display: block;
}

.conflict{
	position: absolute;
	z-index: 3;
	animation: 0.5s blinker linear infinite;
	background-color: red;
	height: 50px;
	width: 50px;
	top: 0px;
	//box-sizing:border-box;
}*/

</style>