<?php
session_start();

header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0');

$user_agent = $_SERVER["HTTP_USER_AGENT"];
if (strpos($user_agent, "MSIE") !== false){
	header("location: wrong_browser.php");
}

if (!isset($_SESSION["ldap"])){
	header("location: index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel = "stylesheet" type = "text/css" href = "css/archive.css">
	<link rel = "stylesheet" type = "text/css" href = "css/main.css">
	<title media=noprint>Карточка предподсчета</title>	
</head>
<body>
	<script type= "text/javascript" src= "js/jquery.js"></script>
	<script type= "text/javascript" src= "js/archive.js"></script>	
	<script type= "text/javascript" src= "js/JsBarcode.all.min.js"></script>	
	<script type= "text/javascript" src= "js/functions.js"></script>
	

	<script>
		document.onclick = function(e){
			<?php
				if (!isset($_SESSION["ldap"])){
					echo "<script>logout();</script>";
				}
			?>
		}		
	</script>
	<div id= "header" name= "header">		
		<div id= "triangle" name="triangle"></div>
		<div id= "logo" name="logo"></div>
		<div id="buttonsBlock">			
			<div id= "createNewButton" onclick = "window.location='card.php'" class = "menuButtons" title="Создать новую карточку"></div>
		</div>
		<a href= "index.php" id="logout" onclick= "logout();" >Выйти</a>
	</div>
	<div id= "cardcont" name= "cardcont">
		<table id="cardsTable" class="table">
			<tr class="header">
				<td class="cardId">Номер карточки</td>
				<td class="date">Дата создания/изменения</td>
				<td class="username">Имя создавшего/изменившего</td>
				<td class="department">Отдел</td>
				<td class="address">Адрес</td>
			</tr>
			<tr class="header">
				<td><input type="text" id="cardIdFilter" onkeyup="getCards();"/></td>
				<td><input type="text" id="dateFilter" onkeyup="getCards();"/></td>
				<td><input type="text" id="usernameFilter" onkeyup="getCards();"/></td>
				<td><input type="text" id="departmentFilter" onkeyup="getCards();"/></td>
				<td><input type="text" id="addressFilter" onkeyup="getCards();"/></td>
			</tr>
		</table>
	</div>
</body>
</html>