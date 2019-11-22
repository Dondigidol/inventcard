<?
session_start();
?>
<html>
<head>
	<script type= "text/javascript" src= "js/jquery.js"></script>
	<script type= "text/javascript" src= "js/mobile.js"></script>
	<link rel = "stylesheet" type = "text/css" href = "css/m.main.css">
</head>
<body>
	<div class="body">
		<div class="cont">
			<label>ШК: <input type="text" id="sku" onkeyup="getEnterKeyCard(event);" autofocus required /></label><br><br>

			<input type="button" value="Закончить" onclick="saveCard();" />		
		</div>
	</div>

</body>
</html>