<?php
header('Content-Type: text/html; charset=utf-8');

require_once('../connect.php');
//============подключение к MS SQL===================================================
$conn = connect_to_postgre('config.ini');
//-----------------------------------------------------------------------------------

$result = array();

if (isset($_POST["lm"])){
	$result=$conn->getItem($_POST["lm"]);
} elseif (isset($_POST["sku"])){
	$result=$conn->getItem($_POST["sku"]);
}

if (count($result) > 0){
	$result = $result[0][0] . "|" . $result[0][1] . "|" . $result[0][2];
} else {
	$result = "||";
}

echo $result;
?>