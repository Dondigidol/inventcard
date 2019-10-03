// Нажатие кнопки логина =================
function loginbtn_clk(){	
	$("#alerts").modal('hide');
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
					$("#alerts").modal("show");
					$("#alerts_message").text(data);
				}
			}
		});
	} else {
		$("#alerts").modal("show");
		$("#alerts_message").text("Внимание! Заполните все поля.");
		
	}
}
//----------------------------------------