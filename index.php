<?php
session_start();

$user_agent = $_SERVER["HTTP_USER_AGENT"];
if (strpos($user_agent, "MSIE") !== false){
	header("location: wrong_browser.php");
}

if (isset($_SESSION["ldap"])){
	header("location: card.php");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<link rel = "stylesheet" type = "text/css" href = "css/signin.css">
<link rel = "stylesheet" type = "text/css" href = "css/bootstrap.min.css">
<script type= "text/javascript" src= "js/jquery.js"></script>
<script type= "text/javascript" src= "js/index.js"></script>
<script src="js/bootstrap.min.js"></script>
</head>
<body class="background">
<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<center><img src="img/logo2.png" /></center>
				<div class="row">
						<div class="col-sm-4 col-sm-offset-4">
							<form role="form" onsubmit="loginbtn_clk(); return false;" method="post">
								<center><h2 class="form-signin-heading">Карточка предподсчета</h2></center><br/>
								<center><h4 class="form-signin-heading" style="color: #6c6;">Добро пожаловать</h4></center>
								<label>LDAP *</label>
								<input type="text" class="form-control" id="login" name="login" placeholder="600XXXXX" required autofocus><br/>
								<label>Пароль *</label>
								<input type="password" class="form-control" id="password" name="password" placeholder="Password" required><br/>
								<button class="btn btn-lg btn-success btn-block" type="submit">Войти</button>
							</form>
						</div>
				</div>

			</div>
		</div>
</div>

<div class="modal fade bs-example-modal-sm" id="alerts" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title" id="myModalLabel">Оповещение</h4>
	  </div>
	  <div class="modal-body">
		<h5><div id="alerts_message"></div></h5>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
	  </div>
	</div>
  </div>
</div>

</body>
</html>