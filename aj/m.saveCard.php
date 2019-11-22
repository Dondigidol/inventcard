<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

require_once('../connect.php');
//============подключение к MS SQL===================================================
$mysql = connect_to_mysql('config.ini');
$mysql->checkTable($_SESSION['shop']);

$pgre = connect_to_postgre('config.ini');
//-----------------------------------------------------------------------------------
$curtime=time();
$cardId=substr_replace(substr_replace($curtime,"_",3,0),"_",7,0)."_".$_SESSION["ldap"];

$date = Date("d.m.Y");
if(strlen($_SESSION['otdel_id'])>2){
	$department="";
} else {
	$department=$_SESSION['otdel_id'];
}


if (isset($_SESSION["items"])){
	$items = Array();
	$items = $_SESSION["items"];
	$uniq_items = array_unique($items);
	$pos=1;
	foreach($uniq_items as $uitem){
		
		$i=0;
		foreach($items as $item){
			if($item == $uitem){
				$i++;
			}
		}
		$result = array();
		$result=$pgre->getItem($uitem);
		$lm="";
		$name="";
		if ($result != null && array_key_exists(0, $result)){
			$lm=$result[0][1];
			$name=$result[0][2];
		}
		echo $mysql->addItem($cardId, $date, $_SESSION['username'], $department, $_SESSION['address'], "", $pos, $uitem, $lm, $name, $i, "");
		$pos++;
		
		
	}
	unset($_SESSION["items"]);
}
?>