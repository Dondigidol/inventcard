function logout(){
    $.ajax({
		type: "POST",
		url: "aj/logout.php",
		success: function(data){
			if (data.length>0){
				$("#alerts").html(data);
			}
		}
	});
}

function getItem(itemName){
	var val = $("#" + itemName).val();
	var posLine = itemName.split("_")[1];
	switch (val.length){
		case 8: {
				$("#kol_" + posLine).focus();
			}
			break;
		case 12: {
				$("#kol_" + posLine).focus();
			}
			break;
		default: $("#" + itemName).focus();
			break;
	}
}