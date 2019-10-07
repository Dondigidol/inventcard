var rows = 16;

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
	if ($("#otdels_select").val() == ""){
		$("#otdelCont").css("background", "rgba(255,0,0,0.2)");
	} else {
		$("#otdelCont").css("background", "transparent");
	}		
}

function createRow(){
	rowId=getMaxRowId()+1;
	rowPos=getMaxRowPos()+1;
	//rows = getRowsCount();
	var tr = "<tr id='row" + rowId + "' class = 'tablescont2'>" +
							"<td id='pos" + rowId + "'>" + rowPos + "</td>" +
							"<td>" +
								"<svg id='barcode_" + rowId + "' class='barcode' style='visibility: visible;'></svg>" +
								"<div id='sku_" + rowId + "_label' class='skuLabel' onclick='editSku(" + rowId + ");'></div>" + 
								"<input type='number' id= 'sku_" + rowId + "'  class='tab_article_input' onchange= 'getItemBySku(this.value, " + rowId + ");' onkeyup = 'nextSkuRow(event," + rowId + ");' onfocusout = 'lostFocus(" + rowId + ");' tabindex='" + (rowPos*4) + "'/>" +
							"</td>" +
							"<td>" +
								"<div id='lm_" + rowId + "_label' class='lmLabel' onclick='editLm(" + rowId + ");'></div>" +
								"<input type='number' id= 'lm_" + rowId + "'  class='tab_article_input' onchange= 'getItemByLm(this.value, " + rowId + ");' onkeyup = 'nextLmRow(event," + rowId + ");' onfocusout = 'lostFocus(" + rowId + ");' tabindex='" + (rowPos*4+1) + "'/>" +
							"</td>" +
							"<td>" +
								"<div id = 'name_" + rowId + "_label'></div>" +
							"</td>" +
							"<td>" +
								"<input type='number' id= 'kol_" + rowId + "' onfocusout = 'lostFocus(" + rowId + ");' onkeyup = 'nextKolRow(event," + rowId + ");' tabindex='" + (rowPos*4+2) + "'/>" +
								
							"</td>" +
							"<td>" +
								"<select class='selectors' id='type_" + rowId + "' onchange='lostFocus(" + rowId + ");' tabindex='" + (rowPos*4+3) + "'>" +
									"<option value='empty' selected></option>" +
									"<option value=''>A</option>" +
								"</select>" +
								"<div id = 'clear_" + rowId + "' class = 'clearButton' onclick='clearItem(" + rowId + ")'></div>" +
							"</td>" +
							
						"</tr>";	
	$("#mainTable").append(tr);
	//console.log("текущая позиция: " + rowPos + ", всего позиций: " + getMaxRowPos());
}
	
function nextSkuRow(event,row){
	if(event.keyCode==13){
		var next = parseInt(getNext(row));
		if (next<=getMaxRowId()){			
			editSku(next);
		}
	}
}

function nextLmRow(event,row){
	if(event.keyCode==13){
		var next = parseInt(getNext(row));
		if (next<=getMaxRowId()){			
			editLm(next);
		}
	}
}

function nextKolRow(event,row){
	if(event.keyCode==13){
		var next = parseInt(getNext(row));
		if (next<=getMaxRowId()){			
			$("#kol_"+next).focus();
		}
	}
}

function getNext(pos){
	var itemPos;
	var curPos = parseInt($("#pos"+pos).html());	
	$(".tablescont2").each(function(){
		var rowId = $(this).attr('id').split("row")[1];
		var rowPos = parseInt($("#pos" + rowId).html());
		if (((curPos)+1)==rowPos){
			itemPos = parseInt(rowId);
		}
	});
	return parseInt(itemPos);
}


	
function getMaxRowPos(){
	var max=0;
	var rowId;
	var posNum;
	$(".tablescont2").each(function(){
		rowId=$(this).attr("id").split("row")[1];
		posNum=parseInt($("#pos"+rowId).html());
		if (posNum>max){
			max=posNum;
		}
	});
	return parseInt(max);
}

function getMaxRowId(){
	var max=0;
	var rowId;
	$(".tablescont2").each(function(){
		rowId = parseInt($(this).attr("id").split("row")[1]);
		if (rowId>max){
			max=rowId;
		}
	});
	return parseInt(max);
}

function getRowsCount(){
	var rowsCount = $(".tablescont2").length;
	return parseInt(rowsCount);
}
	
function getItemBySku(sku, pos){
	$("#lm_"+pos+"_label").html("");
	$("#sku_"+pos+"_label").html("");
	var rowsCount = $(".tablescont2").length;
	$.ajax({
		type: "POST",
		url: "aj/getdata.php",
		data: {"sku": sku},
		success: function(data){
			data = data.split("|");
			if (data[0] == ""){
				data[0] = sku;
			}
			$("#lm_"+pos+"_label").html(data[1]);
			$("#sku_"+pos+"_label").html(data[0]);
			if (data[0] != "") {
				JsBarcode("#barcode_" + pos, data[0], {
					width: 1.5,
					height: 30,
					font: "Helvetica"					
				});
				if (pos == getMaxRowId()){
					createRow();
					editSku(getNext(pos));
				}
			}
			$("#name_"+pos+"_label").html(data[2]);				
		}
	});
}
	
function getItemByLm(lm, pos){
	$("#lm_"+pos+"_label").html("");
	$("#barcode_"+pos).html("");
	var rowsCount = $(".tablescont2").length;
	$.ajax({
		type: "POST",
		url: "aj/getdata.php",
		data: {"lm": lm},
		success: function(data){
			data = data.split("|");
			if (data[1] == ""){
				data[1] = lm;
				if (pos == getMaxRowId()){
					createRow();
					editLm(getNext(pos));
				}
			}
			$("#lm_"+pos+"_label").html(data[1]);
			$("#sku_"+pos+"_label").html(data[0]);
			if (data[0] != "") {
				JsBarcode("#barcode_" + pos, data[0], {
					width: 1.5,
					height: 30,
					font: "Helvetica"					
				});
			}			
			$("#name_"+pos+"_label").html(data[2]);				
		}
	});
}
	
function editLm(pos){
	$("#lm_"+pos).val($("#lm_"+pos+"_label").html());		
	$("#lm_"+pos+"_label").css("visibility","hidden");
	$("#lm_"+pos).css("visibility","visible");
	$("#lm_"+pos).focus();
}
	
function editSku(pos){
	$("#sku_"+pos).val($("#sku_"+pos+"_label").html());
	$("#sku_"+pos+"_label").css("visibility","hidden");
	$("#barcode_"+pos).css("visibility","hidden");
	$("#sku_"+pos).css("visibility","visible");
	$("#sku_"+pos).focus();
}
	
function lostFocus(pos){
	$("#sku_"+pos).css("visibility","hidden");
	$("#lm_"+pos).css("visibility","hidden");
	$("#sku_"+pos+"_label").css("visibility","visible");
	$("#lm_"+pos+"_label").css("visibility","visible");
	$("#barcode_"+pos).css("visibility","visible");
	if ($("#sku_"+pos).val() != "" || $("#lm_"+pos).val() != "" || $("#kol_"+pos).val() != "" || $("#type_"+pos).val() != "empty"){
		$("#clear_" + pos).css("display","block");
	} else{
		$("#clear_" + pos).css("display","none");
	}
	
	if (($("#sku_"+pos).val() != "" || $("#lm_"+pos).val() != "") && $("#kol_"+pos).val() == ""){
		$("#kol_"+pos).css("background","rgba(255,0,0,0.2)");
	} else {
		$("#kol_"+pos).css("background","transparent");
	}
	
}
	
function clearItem(pos){
	var rowsCount = $(".tablescont2").length;
	var posNum = $("#pos" + pos).html();
	if (posNum<=rows && (rowsCount - rows ==0)){		
		$("#lm_"+pos).val("");		
		$("#sku_"+pos).val("");
		$("#kol_"+pos).val("");
		$("#type_"+pos).val("empty");
		$("#lm_"+pos+"_label").html("");
		$("#sku_"+pos+"_label").html("");
		$("#barcode_"+pos).html("");
		$("#name_"+pos+"_label").html("");
		$("#kol_"+pos).css("background","transparent");
		$("#clear_" + pos).css("display","none");
	} else {
		//$("#row"+pos).remove();
		remove(pos);
	}
}

function remove(pos){
	$("#row"+pos).remove();
	var i=1;
	$(".tablescont2").each(function(){
		var rowNum = ($(this).attr('id')).split("row")[1];
		$("#pos" + rowNum).html(i);
		i++
	});
	
}
	
function checkKol(){
	for (i=0; i<=2; i++){
		if (($("lm_"+i+"_label").html() != "" || $("#sku_"+i+"_label").html() != "") && $("#kol_"+i).val() == ""){
			$("#kol_"+i).css("background","rgba(255,0,0,0.2)");
		} else {
			$("#kol_"+i).css("background","transparent");
		}
	}		
}

function checkInputs(input){
	var val = $("#" +input).val();
	if (val == ""){
		$("#" + input).css("background","rgba(255,0,0,0.2)");
	} else {
		$("#" + input).css("background","transparent");
	}
}