<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

require_once('../connect.php');
//============подключение к MS SQL===================================================
$mysql = connect_to_mysql('config.ini');
$mysql->checkTable($_SESSION['shop']);
//-----------------------------------------------------------------------------------
$cardId=$_POST["cardId"];
$date=$_POST["date"];
$username=$_POST["username"];
$department=$_POST["department"];
$address=$_POST["address"];

$arr=Array();
		
$result=$mysql->getCards($cardId, $date, $username, $department, $address);
foreach($result as $row){
	$arr[]=$row;
}


$json=json_encode($arr);
echo $json;





?>