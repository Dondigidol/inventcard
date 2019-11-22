<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

$items = Array();
$items = $_SESSION["items"];

if (isset($_POST["sku"])){
	array_push($items, $_POST["sku"]);
	$_SESSION["items"] = $items;
}

?>