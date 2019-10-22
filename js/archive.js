document.onready = function(){
	getCards();	
}

function logout(){
    $.ajax({
		type: "POST",
		url: "aj/logout.php",
		success: function(data){
			window.location.href = '/index.php';
			if (data.length>0){
				$("#alerts").html(data);
			}
		}
	});
}

function getCards(){
	$(".row").each(function(){
		$(this).remove();
	});
	var cardId = $("#cardIdFilter").val();
	var date = $("#dateFilter").val();
	var username = $("#usernameFilter").val();
	var department = $("#departmentFilter").val();
	var address = $("#addressFilter").val();
	$.ajax({
		type: "POST",
		url: "aj/getCards.php",
		data: {"cardId": cardId, "date": date, "username": username, "department": department, "address": address},
		success: function(data){
			var result=[];
			console.log(data);
			if(data.length>0){
				result = JSON.parse(data);
				var i=1;
				while(i<=result.length){
					if(i%2==0){
						var style="background: rgba(128,128,128,0.1);";
					}else {
						var style="background: transparent;";
					}
					
					var tr="<tr class='row' style='"+style+"'ondblclick=loadCard('"+result[i-1]["card_id"]+"') >" +
							"<td class='cardId'>"+result[i-1]["card_id"]+"</td>" +
							"<td class='date'>"+result[i-1]["date"]+"</td>" +
							"<td class='username'>"+result[i-1]["user_name"]+"</td>" +
							"<td class='department'>"+result[i-1]["department"]+"</td>" +
							"<td class='address'>"+result[i-1]["address"]+"</td>" +
							"</tr>";
					$("#cardsTable").append(tr);
					i++; 
				}
			}
		}		
	});
}

function loadCard(cardId){
	window.location.href="card.php?cardId="+cardId;
}