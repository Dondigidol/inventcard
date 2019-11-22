<?php
session_start();
require_once '../connect.php';

$ldap = $_POST["ldap"];

if (is_numeric($ldap) && strlen($ldap)==8){
	error_reporting(E_ERROR | E_PARSE);
	$data = mobile_connect_to_ldap($ldap, 'config.ini');
	$_SESSION['ldap'] = $ldap;
	$_SESSION['username'] = $data[0]["displayname"][0];
	$_SESSION['shop'] = $data[0]["postofficebox"][0];
	$_SESSION['otdel_id'] = $data[0]["extensionattribute5"][0];
	echo 'true';
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
} else {
	echo "Ошибка! Необходимо авторизоваться под личным лдапом!";
}
?>