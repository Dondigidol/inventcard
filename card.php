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
					header("location: index.php");
				}
			?>
		}		
	</script>
	<div id= "header" name= "header">		
		<div id= "triangle" name="triangle"></div>
		<div id= "logo" name="logo"></div>
		<div id="buttonsBlock">			
			<div id= "printButton" onclick = "window.print();" class = "menuButtons" title="Распечатать"></div>
			<div id= "clearButton" onclick = "window.location.reload();" class = "menuButtons" title="Очистить все поля"></div>
		</div>
		<a href= "index.php" id="logout" onclick= "logout();" >Выйти</a>
	</div>
	<div id= "cardcont" name= "cardcont">
		<div id= "card" name= "card">
			<div id="title">Карточка предподсчета - Инвентаризация</div>
			<table class="tables" id="head_table" width="90%">
				<tr>
					<td width="60%">Дата подготовки: <?php echo Date("d.m.Y");?></td>
					<td width="17%" class="head_table_sep">Магазин:</td>
					<td width="23%" style="text-align: center;"><?php echo $_SESSION["shop"];?></td>
				</tr>
				<tr class="tablescont">
					<td><span>Отдел:</span>
						<span id="otdelCont"><select id="otdels_select" onchange="otdelChange();">
							<option></option>
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
							<option>6</option>
							<option>7</option>
							<option>8</option>
							<option>9</option>
							<option>10</option>
							<option>11</option>
							<option>12</option>
							<option>13</option>
							<option>14</option>
							<option>15</option>
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

			
			</table>	
		</div>
	</div>
<div id = "errorCont"></div>

</body>
</html>