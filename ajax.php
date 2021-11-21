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
	
	/*$t1 = new soldier('red', 3, 9);
	$t1 = new soldier('black', 4, 9);
	$t1 = new Tank('red', 5, 4);
	$t1 = new Tank('red', 3, 4);
	$t1 = new soldier('red', 4, 6);
	$t1 = new soldier('red', 3, 6);
	$t1 = new soldier('black', 4, 8);
	$t1 = new soldier('black', 4, 7);
	$t1 = new soldier('black', 5, 7);
	$t1 = new Head('red', 5, 8);
	
	$t1 = new Plane('red', 3, 0);*/
	
	//echo '<pre>$t1'; print_r($t1); echo '</pre>';
	
	$_SESSION['idNext'] = $GLOBALS['idNext'];
}else{
	if(!empty($_SESSION['GameField']['arrObject']))
		GameField::$arrObject = $_SESSION['GameField']['arrObject'];
	if(!empty($_SESSION['GameField']['matrix']))
		GameField::$matrix = $_SESSION['GameField']['matrix'];
	if(!empty($_SESSION['GameField']['stage']))
		GameField::$stage = $_SESSION['GameField']['stage'];
	
	if(isset($_REQUEST['id'])){
		$object = GameField::$arrObject[$_REQUEST['id']];
		unset(GameField :: $matrix[$object -> y][$object -> x]);
		$object -> y = $_REQUEST['y'];
		$object -> x = $_REQUEST['x'];
		GameField :: $matrix[$_REQUEST['y']][$_REQUEST['x']] = $object;
	}else{
		GameField :: $matrix[$_REQUEST['y']][$_REQUEST['x']] = false;
	}
}






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

if(isset($presetPhase)){
	$phaseRequest = $presetPhase;
}elseif(isset($_REQUEST['phase'])){
	$phaseRequest = $_REQUEST['phase'];
}

// > обработка  хода

$_SESSION['GameField']['arrObject'] = GameField :: $arrObject;
$_SESSION['GameField']['matrix'] = GameField :: $matrix;
$_SESSION['GameField']['stage'] = GameField :: $stage;
$_SESSION['GameField']['whiteFields'] = GameField :: $whiteFields;

GameField::draw();

if(empty($phaseRequest))
	$phaseRequest = 'attempt';
	
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

<?/*echo '<br>$arrObject<br>';
print_r(GameField::$arrObject[$_REQUEST['id']]);*/

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
echo '</div>';