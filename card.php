<?php
session_start();
if (!isset($_SESSION["ldap"])){
	header("location: index.php");
}
$rows=16;
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
					<td width="20%" class="head_table_sep">Магазин:</td>
					<td width="20%" style="text-align: center;"><?php echo $_SESSION["shop"]?></td>
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
					<td><input type="text"/></td>
				</tr>
				<tr class="tablescont">
					<td>Сотрудник: <?php echo $_SESSION["username"];?></td>
					<td class="head_table_sep">№ Листа:</td>
					<td><input type="text"/></td>
				</tr>
				<tr class="tablescont">
					<td>Контролирующий:</td>
					<td class="head_table_sep">№ Коробки:</td>
					<td><input type="text"/></td>
				</tr>
			</table>
			<br>
			<table class= "tables" width= "95%">
				<tr class = "tableshead">
					<td width= "5%">№</td><td width= "23%">код EAN</td><td width= "15%">код ЛМ</td><td width= "37%">наименование</td><td width= "10%">кол-во</td><td width="10%">Тип</td>
				</tr>
				
				<?php
					for($i = 0; $i<$rows; $i++){
						
						echo 	("<tr class = 'tablescont2'>
									<td>" . ($i + 1) . "</td>
									<td>
										<svg id='barcode_" . $i . "' class='barcode'></svg>
										<div id='sku_" . $i . "_label' class='skuLabel' onclick='editSku(" . $i . ");'></div>
										<input type='number' id= 'sku_" . $i . "'  class='tab_article_input' onchange= 'getItemBySku(this.value, " . $i . ");' onkeyup = 'nextRow(event," . $i. ", " .$rows. ");' onfocusout = 'lostFocus(" . $i . ");' tabindex='" . ($tab + 2) . "'/>
									</td>
									<td>
										<div id='lm_" . $i . "_label' class='lmLabel' onclick='editLm(" . $i . ");'></div>
										<input type='number' id= 'lm_" . $i . "'  class='tab_article_input' onchange= 'getItemByLm(this.value, " . $i . ");' onfocusout = 'lostFocus(" . $i . ");' tabindex='" . ($tab + 1) . "'/>
									</td>
									<td>
										<div id = 'name_" . $i . "_label'></div>
									</td>
									<td>
										<input type='number' id= 'kol_" . $i . "' tabindex='" . ($tab + 3) . "' onfocusout = 'lostFocus(" . $i . ");'/>
										
									</td>
									<td>
										<select class='selectors' id='type_" . $i . "' onchange='lostFocus(" . $i . ");' tabindex='" . ($tab + 4) . "'>
											<option value='empty' selected></option>
											<option value=''>A</option>					
										</select>
										<div id = 'clear_" . $i . "' class = 'clearButton' onclick='clearItem(" . $i . ")'></div>
									</td>
									
								</tr>");
						$tab = $tab + 3;
					}			
				?>
			
			</table>	
		</div>
	</div>
<div id = "errorCont"></div>


<script>
	document.onclick = function(e){
		<?php
			if (!isset($_SESSION["ldap"])){
				header("location: index.php");
			}
		?>
	}	
	var lmLabel = [];
	var lmInput = [];
	var skuLabel = [];
	var skuInput = [];
	var barcode = [];
	var kolInput = [];
	var typeSelection = [];
	var nameLabel = [];
	
	window.addEventListener('load', loadVariables, false);
	
	function loadVariables(){
		<?php
			for ($i=0; $i<$rows; $i++){
				echo "lmLabel[" . $i . "] = document.getElementById('lm_" . $i . "_label');";
				echo "lmInput[" . $i . "] = document.getElementById('lm_" . $i . "');";
				echo "skuLabel[" . $i . "] = document.getElementById('sku_" . $i . "_label');";
				echo "skuInput[" . $i . "] = document.getElementById('sku_" . $i . "');";
				echo "barcode[" . $i . "] = document.getElementById('barcode_" . $i . "');";
				echo "kolInput[" . $i . "] = document.getElementById('kol_" . $i . "');";
				echo "typeSelection[". $i . "] = document.getElementById('type_" .$i. "');";
				echo "nameLabel[" . $i . "] = document.getElementById('name_" . $i . "_label');";
			}
		?>
	}
	
	
	
</script>
</body>
</html>