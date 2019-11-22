<html>
<head>
<title>Карточка предподсчета</title>
	<script type= "text/javascript" src= "js/jquery.js"></script>
	<script type= "text/javascript" src= "js/mobile.js"></script>
	<link rel = "stylesheet" type = "text/css" href = "css/m.main.css">
</head>
<body>
	<div class="body">
		<div class="cont">
			<div id="logo"></div><br>
			<h5>Карточка предподсчета</h5>
			<label>Ldap: <input type="text" id="login" name="login" placeholder="600XXXXX" onkeyup="getEnterKeyLogin(event);" required autofocus /></label><br>
			<input type="button" onclick="login();" value="Войти" />
		</div>
	</div>
</body>