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
			<label>Адрес: <input type="text" id="address" placeholder="Отсканируйте адрес..." onkeyup="getEnterKeyAddress(event);" required autofocus /></label><br>
			<input type="button" value="Далее" onclick="setAddress();" /><br>
			<input type="button" value="Выйти" onclick="logout();" /><br>
		</div>
	</div>
</body>
</html>