<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

require_once('../connect.php');
//============подключение к MS SQL===================================================
$mysql = connect_to_mysql('config.ini');
$mysql->checkTable($_SESSION['shop']);
//-----------------------------------------------------------------------------------
$cardId=$_POST["cardId"];
$arr=Array();

if (isset($cardId)){		
	$result=$mysql->getCard($cardId);
 	foreach($result as $row){
		$arr[]=$row;
	}
}

$json=json_encode($arr);
echo $json;

/* $username=$_SESSION["username"];
$shop=$_SESSION["shop"];
$card_id=$_POST["card_id"];
$department=$_POST["department"];
$position=$_POST["position"];
$sku=$_POST["sku"];
$lm=$_POST["lm"];
$name=$_POST["name"];
$kol=$_POST["kol"];
$type=$_POST["type"]; */





?>