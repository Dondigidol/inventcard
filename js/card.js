var rows = 16;
var rows2 = 19;

document.onready = function(){
	otdelChange();
	
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
	if ($("#otdels_select").val() == " "){
		$("#otdelCont").css("background", "rgba(255,0,0,0.2)");
	} else {
		$("#otdelCont").css("background", "transparent");
	}		
}

function createRow(){
	rowId=getMaxRowId()+1;
	rowPos=getMaxRowPos()+1;
		
	var tr = "<tr id='row" + rowId + "' class = 'tablescont2'>" +
							"<td id='pos" + rowId + "' class='pos'>" + rowPos + "</td>" +
							"<td class='sku'>" +
								"<svg id='barcode_" + rowId + "' class='barcode' style='visibility: visible;'></svg>" +
								"<div id='sku_" + rowId + "_label' class='skuLabel' onclick='editSku(" + rowId + ");'></div>" + 
								"<input type='number' id= 'sku_" + rowId + "'  class='tab_article_input' onchange= 'getItemBySku(this.value, " + rowId + ");' onkeyup = 'nextSkuRow(event," + rowId + ");' onfocusout = 'lostFocus(" + rowId + ");' tabindex='" + (rowPos*4) + "'/>" +
							"</td>" +
							"<td class='lm'>" +
								"<div id='lm_" + rowId + "_label' class='lmLabel' onclick='editLm(" + rowId + ");'></div>" +
								"<input type='number' id= 'lm_" + rowId + "'  class='tab_article_input' onchange= 'getItemByLm(this.value, " + rowId + ");' onkeyup = 'nextLmRow(event," + rowId + ");' onfocusout = 'lostFocus(" + rowId + ");' tabindex='" + (rowPos*4+1) + "'/>" +
							"</td>" +
							"<td class='name'>" +
								"<div id = 'name_" + rowId + "_label' class='nameField'></div>" +
							"</td>" +
							"<td class='kol'>" +
								"<input type='number' id= 'kol_" + rowId + "' onfocusout = 'lostFocus(" + rowId + ");' onkeyup = 'nextKolRow(event," + rowId + ");' tabindex='" + (rowPos*4+2) + "'/>" +
								
							"</td>" +
							"<td class='type'>" +
								"<select class='selectors' id='type_" + rowId + "' onchange='lostFocus(" + rowId + ");' tabindex='" + (rowPos*4+3) + "'>" +
									"<option value=' ' selected></option>" +
									"<option value='A'>A</option>" +
								"</select>" +
								"<div id = 'clear_" + rowId + "' class = 'clearButton' onclick='clearItem(" + rowId + ")'></div>" +
							"</td>" +
							
						"</tr>";	
	$("#mainTable").append(tr);
	addHeaders();
	changePageNumber();
}

function addHeaders(){
	$(".pageCount").each(function(){
		$(this).remove();
	});
	$(".tablescont2Header").each(function(){
		$(this).remove();
	});	
	
	if (getMaxRowPos()>rows)
		i=1;
		pageRows = rows;
		$(".tablescont2").each(function(){
			var rowId=$(this).attr("id").split("row")[1];
			var rowPos=$("#pos"+rowId).html();
			if (rowPos%pageRows===0 && rowPos<getMaxRowPos()){
				var tr="<tr id='pageCount"+(i + 1)+"' class='pageCount'>"+
							"<td class='address'>Адрес</td>" +
							"<td class='addressVal'></td>" +
							"<td class='page'>Лист</td>" +
							"<td id='pageNumber"+(i + 1)+"' class='pageVal'></td>" +
						"</tr>"+
						"<tr id='#headerRow"+ (i + 1) +"' class='tablescont2Header'>" + 
							"<td class='pos'>№</td>" +
							"<td class='sku'>код EAN</td>" +
							"<td class='lm'>код ЛМ</td>" +
							"<td class='name'>наименование</td>" +
							"<td class='kol'>кол-во</td>" +
							"<td class='type'>тип</td>" +
						"</tr>";
				i++;
				$(tr).insertAfter($("#row"+rowId));
				pageRows = pageRows + rows2;
			}
		});	
			
	changePageNumber();
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

function getPageCount(){
	return Math.ceil(Math.ceil((getMaxRowPos()-rows)/rows2))+1;

}

function changePageNumber(){
	var pageCount = getPageCount();
	$("#pageNumber1").html("1 из " + pageCount);
	$('.pageCount').each(function(){
		var id = $(this).attr("id").split("pageCount")[1];
		$("#pageNumber"+id).html(id + " из " + pageCount);
	});
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
	if ($("#sku_"+pos).val() != "" || $("#sku_"+pos+"_label").html() != "" || $("#lm_"+pos+"_label").html() != "" || $("#lm_"+pos).val() != "" || $("#kol_"+pos).val() != "" || $("#type_"+pos).val() != " "){
		$("#clear_" + pos).css("display","block");
	} else{
		$("#clear_" + pos).css("display","none");
	}
	
	if (($("#sku_"+pos).val() != "" || $("#sku_"+pos+"_label").html() != "" || $("#lm_"+pos+"_label").html() != "" || $("#lm_"+pos).val() != "") && $("#kol_"+pos).val() == ""){
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
		remove(pos);
	}
}

function remove(pos){
	
	$("#row"+pos).remove();
	var i=1;
	var pageCount = parseInt(getMaxRowPos()/rows+1);
	$(".tablescont2").each(function(){
		var rowNum = ($(this).attr('id')).split("row")[1];
		$("#pos" + rowNum).html(i);
		i++
	});
	addHeaders();
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
	$(".addressVal").html($("#addressNumber").val());
	if (val.length==0){
		$("#" + input).css("background","rgba(255,0,0,0.2)");
	} else {
		$("#" + input).css("background","transparent");
	}
}

function saveCard(){
	var arr = [];	
	var i=0;
	var cardId = $("#cardId").html();
	$(".tablescont2").each(function(){
		var rowId = $(this).attr("id").split("row")[1];
		var sku = $("#sku_"+rowId+"_label").html();
		var lm = $("#lm_"+rowId+"_label").html();
		
		if (sku>0 || lm.length>0){
			var arr2 = [];			
			arr2[0] = $("#cardId").html();
			arr2[1] = $("#changingDate").html();
			arr2[2] = $("#otdels_select").val();
			arr2[3] = $("#addressNumber").val();
			arr2[4] = $("#boxNumber").val();
			arr2[5] = $("#pos"+rowId).html();
			arr2[6] = sku;
			arr2[7] = lm;
			arr2[8] = $("#name_"+rowId+"_label").html();
			arr2[9] = $("#kol_"+rowId).val();
			arr2[10] = $("#type_"+rowId).val();
			
			arr[i]= arr2;
			i++;
		}
	});
	$.ajax({
		type: "POST",
		url: "aj/saveCard.php",
		data: {"rows": arr, "cardId": cardId},
		success: function(data){
			//console.log(data);
		}		
	});
}

function getCard(cardId){
	var result=[];
	$.ajax({
		type: "POST",
		url: "aj/getCard.php",
		data: {"cardId": cardId},
		success: function(data){
			
			var result=[];
			result = JSON.parse(data);
			if(result.length>0){
				if (result.length>rows){
					var arrRows = result.length-rows;
					var i=0;
					while(i<=arrRows){
						createRow();
						i++;
					}
				}
				var i=1;
				$("#otdels_select").val(result[0]["department"]);
				$("#addressNumber").val(result[0]["address"]);
				$("#boxNumber").val(result[0]["box"]);
				otdelChange();
				checkInputs('addressNumber');
				checkInputs('boxNumber');
				while(i<=result.length){
					$("#sku_"+i+"_label").html(result[i-1]["sku"]);
					JsBarcode("#barcode_"+i, result[i-1]["sku"], {
						width: 1.5,
						height: 30,
						font: "Helvetica"					
					});
					$("#lm_"+i+"_label").html(result[i-1]["lm"]);
					$("#name_"+i+"_label").html(result[i-1]["name"]);
					$("#kol_"+i).val(result[i-1]["kol"]);
					$("#type_"+i).val(result[i-1]["type"]);
					lostFocus(i);
					i++; 
				}
			}
		}
	});	
}

function clearCard(){
	$(".tablescont2").each(function(){
		clearItem($(this).attr("id").split("row")[1]);
	});
}