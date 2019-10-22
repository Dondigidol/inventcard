<?php
session_start();

header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0');

if(isset($_GET["cardId"])){
	$cardId=$_GET['cardId'];
} else {
	$curtime=time();
	$cardId=substr_replace(substr_replace($curtime,"_",3,0),"_",7,0)."_".$_SESSION["ldap"];
}

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
	<link rel = "stylesheet" type = "text/css" href = "css/card.css">
	<link rel = "stylesheet" type = "text/css" href = "css/main.css">
	<title media=noprint>Карточка предподсчета</title>	
</head>
<body>
	<script type= "text/javascript" src= "js/jquery.js"></script>
	<script type= "text/javascript" src= "js/card.js"></script>	
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
			<div id= "printButton" onclick = "saveCard(); window.print();" class = "menuButtons" title="Распечатать"></div>
			<div id= "clearButton" onclick = "clearCard();" class = "menuButtons" title="Очистить все поля"></div>
			<div id= "createNewButton" onclick = "window.location='card.php'" class = "menuButtons" title="Создать новую карточку"></div>
			<div id= "archiveButton" onclick = "window.location='archive.php'" class = "menuButtons" title="Архив карточек"></div>
		</div>
		<a href= "index.php" id="logout" onclick= "logout();" >Выйти</a>
	</div>
	<div id= "cardcont" name= "cardcont">
		<div id= "card" name= "card">
			<div id="title">Инвентаризация - Карточка предподсчета <span id="cardId"><?echo $cardId;?></span></div>
			<table class="tables" id="head_table" width="90%">
				<tr>
					<td width="60%">Дата подготовки: <span id="changingDate"><?php echo Date("d.m.Y");?></span></td>
					<td width="17%" class="head_table_sep">Магазин:</td>
					<td width="23%" style="text-align: center;"><?php echo $_SESSION["shop"];?></td>
				</tr>
				<tr class="tablescont">
					<td><span>Отдел:</span>
						<span id="otdelCont"><select id="otdels_select" onchange="otdelChange();">
							<option value=" "></option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
						</select></span>
					<td class="head_table_sep">№ Адреса:</td>
					<td><input type="text" id="addressNumber" class="header_inputs" onkeyup="checkInputs(this.id);"/></td>
				</tr>
				<tr class="tablescont">
					<td>Сотрудник: <?php echo $_SESSION["username"];?></td>
					<td class="head_table_sep">№ Листа:</td>
					<td id="pageNumber1">1 из 1</td>
				</tr>
				<tr class="tablescont">
					<td>Контролирующий:</td>
					<td class="head_table_sep">№ Коробки:</td>
					<td><input type="text" id="boxNumber" class="header_inputs" onkeyup="checkInputs(this.id);"/></td>
				</tr>
			</table>
			<br>
			<table id="mainTable" class= "tables" width= "95%">
				<tr class="tablescontHeader">
						<td class="pos">№</td>
						<td class="sku">код EAN</td>
						<td class="lm">код ЛМ</td>
						<td class="name">наименование</td>
						<td class="kol">кол-во</td>
						<td class="type">тип</td>
					</tr>
				<script>
					for(i = 1; i<=rows; i++){
						createRow();
					}
				</script>	
				<? if (isset($_GET["cardId"])){
					echo "<script>getCard('".$cardId."');</script>";
				}?>
			
			</table>	
		</div>
	</div>
<div id = "errorCont"></div>

</body>
</html>