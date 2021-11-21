<?
/*ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/

require_once ('classes/GameField.php');
require_once ('classes/Direction.php');
require_once ('classes/CObject.php');
require_once ('classes/Party.php');

session_start();

if(!empty($_SESSION['GameField']['arrObject']))
	GameField::$arrObject = $_SESSION['GameField']['arrObject'];
if(!empty($_SESSION['GameField']['matrix']))
	GameField::$matrix = $_SESSION['GameField']['matrix'];
if(!empty($_SESSION['GameField']['whiteFields']))
	GameField::$whiteFields = $_SESSION['GameField']['whiteFields'];
if(!empty($_SESSION['GameField']['stage']))
	GameField::$stage = $_SESSION['GameField']['stage'];

/*
https://zen.yandex.ru/media/aprelev/tankom-hodi-zabytye-sovetskie-shahmaty-60bb7c6fbbd5974178138fb8

Мат (точнее, “штаб-бит”) можно поставить либо, как в классических шахматах, штабу, либо, перебив всю пехоту. 
У каждой фигуры свой радиус действия. Самолет, пушка и пулемет могут перемахивать через одну фигуру своего цвета.
*/

$object = GameField :: $matrix[$_REQUEST['y']][$_REQUEST['x']];

$result = $object -> getVariants();
$json = json_encode($result);
echo $json;
?>