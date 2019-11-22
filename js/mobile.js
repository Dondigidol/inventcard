var items=[];

function getEnterKeyLogin(event){
	if(event.keyCode==13){
		login();
	}
}

function getEnterKeyAddress(event){
	if(event.keyCode==13){
		setAddress();
	}
}

function getEnterKeyCard(event){
	if(event.keyCode==13){
		saveSku();
	}
}



function login(){
	var ldap=$("#login").val();
	$.ajax({
		type: "POST",
		url: "aj/m.login.php",
		data:{"ldap": ldap},
		success: function(data){
			if(data=="true"){
				window.location="m.address.php";
			}			
		}
	});
}

function setAddress(){
	
	var address=$("#address").val();
	$.ajax({
		type: "POST",
		url: "aj/m.setAddress.php",
		data: {"address": address},
		success: function(data){
			if (data=="true"){
				window.location="m.card.php";
			}
		}
	});
}


function saveSku(){
	var sku=$("#sku").val();
	$("#sku").val("");
	$("#lastSku").html(sku);
	$("#scanCount").html(parseInt($("#scanCount").html()) + 1);
	$.ajax({
		type: "POST",
		url: "aj/m.saveSku.php",
		data: {"sku": sku}
	});	
}

function saveCard(){
	$.ajax({
		type: "POST",
		url: "aj/m.saveCard.php"
	});	
	window.location="m.address.php";
}

function logout(){
	$.ajax({
		type: "POST",
		url: "aj/m.logout.php",
		success: function(){
			window.location="m.index.php";
		}
	});
}