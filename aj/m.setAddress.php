<?php
session_start();

if (isset($_POST["address"]) && strlen($_POST["address"])>0){
	$_SESSION["address"]=$_POST["address"];
	$_SESSION['items'] = Array();
	echo "true";
} else {
	echo "false";
}
?>