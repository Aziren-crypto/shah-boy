<?
/*ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/

require_once ('classes/GameField.php');
require_once ('classes/CObject.php');

session_start();

if(!empty($_SESSION['GameField']['arrObject']))
	GameField::$arrObject = $_SESSION['GameField']['arrObject'];
if(!empty($_SESSION['GameField']['matrix']))
	GameField::$matrix = $_SESSION['GameField']['matrix'];
if(!empty($_SESSION['GameField']['stage']))
	GameField::$stage = $_SESSION['GameField']['stage'];
	


$object = GameField :: $matrix[$_REQUEST['y']][$_REQUEST['x']];
//echo '<pre>'; print_r(GameField :: $matrix); echo '</pre>';
echo $object -> text;
?>