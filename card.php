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
					<td width="70%">Дата подготовки: <?php echo Date("d.m.Y");?></td>
					<td width="20%" class="head_table_sep">Магазин:</td>
					<td width="10%" style="text-align: center;"><?php echo $_SESSION["shop"]?></td>
				</tr>
				<tr class="tablescont">
					<td>Отдел: 
						<select class="selectors" style="width: 2em; text-align: center;">
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
						</select>
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
					<td width= "5%">№</td><td width= "15%">код ЛМ</td><td width= "23%">код EAN</td><td width= "37%">наименование</td><td width= "10%">кол-во</td><td width="10%">Тип</td>
				</tr>
				
				<?php
					for($i = 0; $i<$rows; $i++){
						
						echo 	("<tr class = 'tablescont2'>
									<td>" . ($i + 1) . "</td>
									<td>
										<div id='lm_" . $i . "_label' class='lmLabel' onclick='editLm(" . $i . ");'></div>
										<input type='number' id= 'lm_" . $i . "'  class='tab_article_input' onchange= 'getItemByLm(this.value, " . $i . ");' onfocusout = 'lostFocus(" . $i . ");' tabindex='" . ($tab + 1) . "'/>
									</td>
									<td>
										<svg id='barcode_" . $i . "' class='barcode'></svg>
										<div id='sku_" . $i . "_label' class='skuLabel' onclick='editSku(" . $i . ");'></div>
										<input type='number' id= 'sku_" . $i . "'  class='tab_article_input' onchange= 'getItemBySku(this.value, " . $i . ");' onkeyup = 'nextRow(event," . $i. ");' onfocusout = 'lostFocus(" . $i . ");' tabindex='" . ($tab + 2) . "'/>
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

	document.onready = function(){
		otdelChange();
	}

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
	
	function otdelChange(){
		if (document.getElementById("otdels_select")){
			if (document.getElementById("otdels_select").value == ""){
				document.getElementById("otdelCont").style.background = "rgba(255,0,0,0.3)";
			} else {
				document.getElementById("otdelCont").style.background = "transparent";
			}	
		}
			
	}
	
	function nextRow(event,row){
		if(event.keyCode==13){
			if (row != <?php echo $rows-1;?>){
				editSku(row+1);
			} else {
				document.getElementById("sku_" + row).blur();
			}
		}
	}
	
	window.addEventListener('load', loadVariables, false);

	
	function getItemBySku(sku, pos){
		skuLabel[pos].innerHTML="";
		barcode[pos].innerHTML="";
		$.ajax({
			type: "POST",
			url: "aj/getdata.php",
			data: {"sku": sku},
			success: function(data){
				data = data.split("|");
				if (data[0] == ""){
					data[0] = sku;
				}
				lmLabel[pos].innerHTML = data[1];
				skuLabel[pos].innerHTML = data[0];
				if (data[0] != "") {
					JsBarcode("#barcode_" + pos, data[0], {
						width: 1.5,
						height: 30,
						font: "Helvetica"					
					});						
				}
				nameLabel[pos].innerHTML = data[2];				
			}
		});
	}
	
	function getItemByLm(lm, pos){
		lmLabel[pos].innerHTML="";
		barcode[pos].innerHTML="";
		$.ajax({
			type: "POST",
			url: "aj/getdata.php",
			data: {"lm": lm},
			success: function(data){
				data = data.split("|");
				if (data[1] == ""){
					data[1] = lm;
				}
				lmLabel[pos].innerHTML = data[1];
				skuLabel[pos].innerHTML = data[0];
				if (data[0] != "") {
					JsBarcode("#barcode_" + pos, data[0], {
						width: 1.5,
						height: 30,
						font: "Helvetica"					
					});						
				}			
				nameLabel[pos].innerHTML = data[2];				
			}
		});
	}
	
	function editLm(pos){
		lmInput[pos].value = lmLabel[pos].innerHTML;		
		lmLabel[pos].style.visibility = "hidden";
		lmInput[pos].style.visibility = "visible";
		lmInput[pos].focus();
	}
	
	function editSku(pos){
		skuInput[pos].value = skuLabel[pos].innerHTML;
		skuLabel[pos].style.visibility = "hidden";
		barcode[pos].style.visibility = "hidden";
		skuInput[pos].style.visibility = "visible";
		skuInput[pos].focus();
	}
	
	function lostFocus(pos){
		skuInput[pos].style.visibility = "hidden";
		lmInput[pos].style.visibility = "hidden";
		skuLabel[pos].style.visibility = "visible";
		lmLabel[pos].style.visibility = "visible";
		barcode[pos].style.visibility = "visible";
		if (skuInput[pos].value != "" || lmInput[pos].value != "" || kolInput[pos].value != "" || typeSelection[pos].value != "empty"){
			document.getElementById("clear_" + pos).style.display="block";
		} else{
			document.getElementById("clear_" + pos).style.display="none";
		}
		
		if ((skuInput[pos].value != "" || lmInput[pos].value != "") && kolInput[pos].value == ""){
			kolInput[pos].style.background="rgba(255,0,0,0.3)";
		} else {
			kolInput[pos].style.background="transparent";
		}
		
	}
	
	function clearItem(pos){
		lmInput[pos].value = "";		
		skuInput[pos].value = "";
		kolInput[pos].value = "";
		typeSelection[pos].value = "empty";
		lmLabel[pos].innerHTML = "";
		skuLabel[pos].innerHTML = "";
		barcode[pos].innerHTML="";
		nameLabel[pos].innerHTML="";
		kolInput[pos].style.background="transparent";
		document.getElementById("clear_" + pos).style.display="none";
		
	}
	
	function checkKol(){
		for (i=0; i<=2; i++){
			if ((lmLabel[i].innerHTML != "" || skuLabel[i].innerHTML != "") && kolInput[i].value == ""){
				kolInput[i].style.background="rgba(255,0,0,0.3)";
			} else {
				kolInput[i].style.background="transparent";
			}
		}		
	}
	
</script>
</body>
</html>