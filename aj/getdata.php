<?php
header('Content-Type: text/html; charset=utf-8');

require_once('../connect.php');
//============подключение к MS SQL===================================================
//$conn = connect_to_postgre('config.ini');
$conn2 = connect_to_mssql('config.ini');
//-----------------------------------------------------------------------------------

$result = array();


	if (isset($_POST["lm"])){
		$result=$conn2->getItem($_POST["lm"]);
	} elseif (isset($_POST["sku"])){
		$result=$conn2->getItem($_POST["sku"]);
	}

	if ($result != null && array_key_exists(0, $result)){
		$result = $result[0][0] . "|" . $result[0][1] . "|" . iconv('cp1251', 'UTF-8', $result[0][2]);
	} else {
		$result = "||";
	}

	echo $result;

?>