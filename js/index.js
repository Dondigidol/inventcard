// Нажатие кнопки логина =================
function loginbtn_clk(){	
	$("#alerts").html('');
	var ldap = $("#login").val();
	var ldappw = $("#password").val();
	if ((ldap != "") && (ldappw != "")){
		
		$.ajax({
			type: 'POST',
			url: 'aj/login.php',
			data: {'user': ldap, 'userPW': ldappw},
			success: function(data) {
				if (data == "true")
				{
					location.replace("./card.php");		
				}
				else
				{
					$("#alerts").css("visibility", "visible");
					$("#alerts").html(data);
				}
			}
		});
	} else {
		$("#alerts").css("visibility", "visible");
		$("#alerts").html("Внимание! Заполните все поля.");
	}
}
//----------------------------------------