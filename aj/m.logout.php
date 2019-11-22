<?php
session_start();

try {
	unset($_SESSION["ldap"]);
	unset($_SESSION["username"]);
	unset($_SESSION["shop"]);
	unset($_SESSION["otdel_id"]);	
	unset($_SESSION["address"]);
	unset($_SESSION["items"]);
	session_destroy();
} catch (Exception $e) {
	return $e.getMessage();
}


?>