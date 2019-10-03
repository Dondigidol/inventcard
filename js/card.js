document.onready = function(){
	otdelChange();
}

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

function otdelChange(){
	if (document.getElementById("otdels_select")){
		if (document.getElementById("otdels_select").value == ""){
			document.getElementById("otdelCont").style.background = "rgba(255,0,0,0.3)";
		} else {
			document.getElementById("otdelCont").style.background = "transparent";
		}	
	}
		
}
	
function nextRow(event,row, rowsCount){
	if(event.keyCode==13){
		if (row != rowsCount-1){
			editSku(row+1);
		} else {
			document.getElementById("sku_" + row).blur();
		}
	}
}
	


	
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

function checkInputs(input){
	var val = $("#" +input).val();
	if (val == ""){
		$("#" + input).css("background","rgba(255,0,0,0.3)");
	} else {
		$("#" + input).css("background","transparent");
	}
}