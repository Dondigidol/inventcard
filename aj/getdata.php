<?php
header('Content-Type: text/html; charset=utf-8');

require_once('../connect.php');
//============подключение к MS SQL===================================================
$conn = connect_to_postgre('config.ini');
//-----------------------------------------------------------------------------------

/* if (isset($_POST["lm"]) && is_numeric($_POST["lm"]) && strlen($_POST["lm"])==8){
	$query_str = ("select BAR_CODE , LM_CODE, CAPTION from [OST].[dbo].[Scenter](nolock) where otdel IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15) and lm_code = '" . $_POST["lm"] . "'");
} elseif (isset($_POST["sku"]) && is_numeric($_POST["sku"]) && strlen($_POST["sku"])==13){
	$query_str = ("select BAR_CODE , LM_CODE, CAPTION from [OST].[dbo].[Scenter](nolock) where otdel IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15) and bar_code = '" . $_POST["sku"] . "'");
} */

if (isset($_POST["lm"]) && is_numeric($_POST["lm"]) && strlen($_POST["lm"])==8){
	$query_str = ("select item, item_parent, item_desc from rms_p009qtzb_rms_ods.item_master where item_parent='". $_POST["lm"] ."' and is_actual='1' limit 1");	
} elseif (isset($_POST["sku"]) && is_numeric($_POST["sku"]) && strlen($_POST["sku"])==13){
	$query_str = ("select item, item_parent, item_desc from rms_p009qtzb_rms_ods.item_master where item='". $_POST["sku"] ."' and is_actual='1' limit 1");
}

$result = $conn->sql_query($query_str);

if (count($result) > 0){
	$result = $result[0][0] . "|" . $result[0][1] . "|" . iconv('cp1251', 'UTF-8', $result[0][2]);
} else {
	$result = "||";
}

echo $result;
?>