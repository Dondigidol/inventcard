<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

require_once('../connect.php');
//============подключение к MS SQL===================================================
$mysql = connect_to_mysql('config.ini');
$mysql->checkTable($_SESSION['shop']);
$mysql->clearCard($_POST["cardId"]);
//-----------------------------------------------------------------------------------

if (isset($_POST["rows"])){		
	foreach($_POST["rows"] as $row){	
		$mysql->addItem($row[0], $row[1], $_SESSION['username'], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10]);
	}		
}


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