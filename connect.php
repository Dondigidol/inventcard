<?php
session_start();
class ldap_connection
{
	var $server;
	var $port;
	var $conn;
	var $user;
	var $dn;
	
	function get_params($ini_file, $str)
	{
		$params_arr = parse_ini_file($ini_file);
		if ($str=='ru'){			
			isset($params_arr['ldap_server']) ? $this->server = $params_arr['ldap_server'] : die('В файле конфигурации нет данных о ldap-сервере');
			isset($params_arr['ldap_port']) ? $this->port = $params_arr['ldap_port'] : die('В файле конфигурации нет данных о порте ldap-сервера');
			isset($params_arr['ldap_dn']) ? $this->dn = $params_arr['ldap_dn'] : die('В файле конфигурации нет данных о структуре ldap-сервера');
		} elseif($str=='kz'){
			isset($params_arr['ldap_server_kz']) ? $this->server = $params_arr['ldap_server_kz'] : die('В файле конфигурации нет данных о ldap-сервере');
			isset($params_arr['ldap_port_kz']) ? $this->port = $params_arr['ldap_port_kz'] : die('В файле конфигурации нет данных о порте ldap-сервера');
			isset($params_arr['ldap_dn_kz']) ? $this->dn = $params_arr['ldap_dn_kz'] : die('В файле конфигурации нет данных о структуре ldap-сервера');
		}
	}
	
	function set_connection()
	{	
		isset($this->server) and isset($this->port) ? $this->conn = ldap_connect($this->server, $this->port) : die('Не указан сервер, либо порт подключения');
	}
	
	function set_bind($user, $userPW)
	{
		$this->user = $user;
		if (isset($this->conn))
		{
			ldap_set_option($this->conn, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_bind($this->conn, $user, $userPW) or die('Ошибка! Невреный логин или пароль!');		
		}
		else die('Подключение не создано!');		
	}
	
	function get_result()
	{	
		if (isset($this->conn) and isset($this->dn))
		{
			$user=explode("\\",$this->user)[1];
			$result = ldap_search($this->conn, $this->dn, "(sAMAccountName=$user)") or die ("Ошибка поиска");
			return ldap_get_entries($this->conn, $result);
		}
		else die('Подключение к ldap-серверу не готово!');
		
	}	
}

class mobile_ldap_connection
{
	var $server;
	var $port;
	var $conn;
	var $user;
	var $password;
	var $dn;
	
	function get_params($ini_file, $str)
	{
		$params_arr = parse_ini_file($ini_file);
		if ($str=='ru'){			
			isset($params_arr['ldap_server']) ? $this->server = $params_arr['ldap_server'] : die('В файле конфигурации нет данных о ldap-сервере');
			isset($params_arr['ldap_port']) ? $this->port = $params_arr['ldap_port'] : die('В файле конфигурации нет данных о порте ldap-сервера');
			isset($params_arr['ldap_dn']) ? $this->dn = $params_arr['ldap_dn'] : die('В файле конфигурации нет данных о структуре ldap-сервера');
			isset($params_arr['ldap_user']) ? $this->user = "ru1000\\".$params_arr['ldap_user'] : die('В файле конфигурации нет данных о логине');
			isset($params_arr['ldap_password']) ? $this->password = $params_arr['ldap_password'] : die('В файле конфигурации нет данных о пароле');
		} elseif($str=='kz'){
			isset($params_arr['ldap_server_kz']) ? $this->server = $params_arr['ldap_server_kz'] : die('В файле конфигурации нет данных о ldap-сервере');
			isset($params_arr['ldap_port_kz']) ? $this->port = $params_arr['ldap_port_kz'] : die('В файле конфигурации нет данных о порте ldap-сервера');
			isset($params_arr['ldap_dn_kz']) ? $this->dn = $params_arr['ldap_dn_kz'] : die('В файле конфигурации нет данных о структуре ldap-сервера');
			isset($params_arr['ldap_user_kz']) ? $this->user = "kz1000\\".$params_arr['ldap_user_kz'] : die('В файле конфигурации нет данных о логине');
			isset($params_arr['ldap_password_kz']) ? $this->password = $params_arr['ldap_password_kz'] : die('В файле конфигурации нет данных о пароле');
		}
	}
	
	function set_connection()
	{	
		isset($this->server) and isset($this->port) ? $this->conn = ldap_connect($this->server, $this->port) : die('Не указан сервер, либо порт подключения');
	}
	
	function set_bind()
	{
		if (isset($this->conn))
		{
			ldap_set_option($this->conn, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_bind($this->conn, $this->user, $this->password) or die('Ошибка! Невреный логин или пароль!');		
		}
		else die('Подключение не создано!');		
	}
	
	function get_result($ldap)
	{	
		if (isset($this->conn) and isset($this->dn))
		{
			$result = ldap_search($this->conn, $this->dn, "(sAMAccountName=$ldap)") or die ("Ошибка поиска");
			return ldap_get_entries($this->conn, $result);
		}
		else die('Подключение к ldap-серверу не готово!');
		
	}	
}

class mssql_connection
{
	var $server;
	var $database;
	var $user;
	var $userPW;
	var $conn;
	function get_params($ini_file)
	{
		$params_arr = parse_ini_file($ini_file);
		isset($params_arr['mssql_server']) ? $this->server = $params_arr['mssql_server'] : die('В файле конфигурации нет данных о MSSQL сервере');
		isset($params_arr['mssql_database']) ? $this->database = $params_arr['mssql_database'] : die('В файле конфигурации нет данных о подключаемой базе MSSQL сервера');
		isset($params_arr['mssql_user']) ? $this->user = $params_arr['mssql_user'] : die('В файле конфигурации нет данных о логине подключения к MSSQL серверу');
		isset($params_arr['mssql_password']) ? $this->userPW = $params_arr['mssql_password'] : die('В файле конфигурации нет данных о пароле подключения к MSSQL серверу');
	}
	
	function set_connection()
	{
		$connectionInfo = array("Database" => $this->database, "UID" => $this->user, "PWD" => $this->userPW, "LoginTimeout" => 3);
		try
		{
			
			$this->conn = sqlsrv_connect($this->server, $connectionInfo);
			return $this->conn;
		
		}
		catch (Exception $e)
		{
			return false;
		}
		
	}
	
	function getItem($item){
		if (is_numeric($item) && (strlen($item)==8 || strlen($item)==13)){
			switch(strlen($item)){
				case 8: 
					$query_str = ("select BAR_CODE , LM_CODE, CAPTION from [OST].[dbo].[Scenter](nolock) where otdel IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15) and lm_code = '" . $item . "'");
					break;
				case 13:
					$query_str = ("select BAR_CODE , LM_CODE, CAPTION from [OST].[dbo].[Scenter](nolock) where otdel IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15) and bar_code = '" . $item . "'");
					break;
			}
			$arr = sqlsrv_query($this->conn, $query_str);
			$result = array();
			while($val = sqlsrv_fetch_array($arr)){
				array_push($result, $val);
			}
			return $result;			
		}
	}	
}

class postgre_connection{
	var $server;
	var $port;
	var $database;
	var $user;
	var $userPW;
	var $conn;
	
	function get_params($ini_file){
		$params_arr = parse_ini_file($ini_file);
		isset($params_arr['postgre_server']) ? $this->server = $params_arr['postgre_server'] : die('В файле конфигурации нет данных о Postgre сервере');
		isset($params_arr['postgre_port']) ? $this->port = $params_arr['postgre_port'] : die('В файле конфигурации нет данных о номере порта Postgre сервера');
		isset($params_arr['postgre_database']) ? $this->database = $params_arr['postgre_database'] : die('В файле конфигурации нет данных о подключаемой базе Postgre сервера');
		isset($params_arr['postgre_user']) ? $this->user = $params_arr['postgre_user'] : die('В файле конфигурации нет данных о логине подключения к Postgre серверу');
		isset($params_arr['postgre_password']) ? $this->userPW = $params_arr['postgre_password'] : die('В файле конфигурации нет данных о пароле подключения к Postgre серверу');			
		
	}
	
	function set_connection()
	{
		$connectionStr = 'host=' . $this->server . ' port=' . $this->port . ' dbname=' . $this->database . ' user=' . $this->user . ' password=' . $this->userPW . " connect_timeout=3";   	
		try
		{			
			$this->conn = @pg_connect($connectionStr);
			return $this->conn;
		}
		catch (Exception $e)
		{
			return false;
		}
		
	}
	
	function getItem($item){
		if (is_numeric($item) && (strlen($item)==8 || strlen($item)==13)){
			switch(strlen($item)){
				case 8: 
					$query_str = ("select item, item_parent, short_desc from rms_p009qtzb_rms_ods.item_master where item_parent='". $item ."' and is_actual='1' limit 1");
					break;
				case 13:
					$query_str = ("select item, item_parent, short_desc from rms_p009qtzb_rms_ods.item_master where item='". $item ."' and is_actual='1' limit 1");
					break;
			}
			$arr = pg_query($this->conn, $query_str);
			$result = array();
			while($val = pg_fetch_array($arr)){
				array_push($result, $val);
			}
			return $result;			
		}
	}	
}

class mysql_connection
{
	var $server;
	var $database;
	var $user;
	var $userPW;
	var $conn;
	var $store;
	function get_params($ini_file)
	{
		$params_arr = parse_ini_file($ini_file);
		isset($params_arr['mysql_server']) ? $this->server = $params_arr['mysql_server'] : die('В файле конфигурации нет данных о MySQL сервере');
		isset($params_arr['mysql_database']) ? $this->database = $params_arr['mysql_database'] : die('В файле конфигурации нет данных о подключаемой базе MySQL сервера');
		isset($params_arr['mysql_user']) ? $this->user = $params_arr['mysql_user'] : die('В файле конфигурации нет данных о логине подключения к MySQL серверу');
		isset($params_arr['mysql_password']) ? $this->userPW = $params_arr['mysql_password'] : die('В файле конфигурации нет данных о пароле подключения к MySQL серверу');
	}
	
	function set_connection()
	{
		$this->conn = @mysqli_connect($this->server, $this->user, $this->userPW, $this->database) or die("Невозможно подключиться к серверу MySQL!");
		return $this->conn;
	}
	
	function checkTable($tableName){
		$this->store=$tableName;
		$query="CREATE TABLE IF NOT EXISTS `inventcard`.`".$tableName."` (
				    `id` int(11) NOT NULL AUTO_INCREMENT,
					  `card_id` VARCHAR(40) NOT NULL,
					  `date` VARCHAR(10) NOT NULL,
					  `user_name` varchar(60) NOT NULL,
					  `department` varchar(2) DEFAULT NULL,
					  `address` VARCHAR(40),
					  `box` VARCHAR(40),
					  `position` VARCHAR(11) NOT NULL,
					  `sku` varchar(13) DEFAULT NULL,
					  `lm` varchar(8) DEFAULT NULL,
					  `name` varchar(255) DEFAULT NULL,
					  `kol` varchar(15) DEFAULT NULL,
					  `type` varchar(10) DEFAULT NULL,
					  PRIMARY KEY (`id`));";				  
		mysqli_query($this->conn, $query);
	}
	
	function addItem($row){
		$store=$this->store;
		$query="INSERT INTO `inventcard`.`".$store."`
				(`card_id`,
				`date`,
				`user_name`,
				`department`,
				`address`,
				`box`,
				`position`,
				`sku`,
				`lm`,
				`name`,
				`kol`,
				`type`)
				VALUES
				('".$row[0]."',
				'".$row[1]."',
				'".$row[2]."',
				'".$row[3]."',
				'".$row[4]."',
				'".$row[5]."',
				'".$row[6]."',
				'".$row[7]."',
				'".$row[8]."',
				'".$row[9]."',
				'".$row[10]."',
				'".$row[11]."');";				
		mysqli_query($this->conn, $query);
	}
	
	function clearCard($card_id){
		$store=$this->store;
		mysqli_query($this->conn, "SET SQL_SAFE_UPDATES = 0");
		$query="DELETE FROM `inventcard`.`".$store."`
				WHERE `card_id` = '".$card_id."'";
		mysqli_query($this->conn, $query);
		mysqli_query($this->conn, "SET SQL_SAFE_UPDATES = 1");
	}
	
	function getCard($card_id){
		$store=$this->store;
		$result=Array();
		$query="SELECT * FROM `inventcard`.`".$store."`
				WHERE `card_id`='".$card_id."'
				order by id";
		$result=mysqli_query($this->conn, $query);
		return($result);
	}
	
	function getCards($cardId, $date, $username, $department, $address){
		$store=$this->store;
		$result=Array();
		$query="SELECT `card_id`, `date`, `user_name`, `department`, `address` FROM `inventcard`.`".$store."`
				WHERE `card_id` LIKE '%".$cardId."%' and `date` LIKE '%".$date."%' and `user_name` LIKE '%".$username."%' and `department` LIKE '%".$department."%' and `address` LIKE '%".$address."%'
				group by `card_id`, `date`, `user_name`, `department`, `address`";
		$result=mysqli_query($this->conn, $query);
		return($result);
	}
}

function connect_to_ldap($user, $userPW, $ini_file)
{
	$ldap = new ldap_connection;
	if (substr($user, 0, 1)=='6'){
		$ldap->get_params($ini_file, 'ru');
		$user2="ru1000\\".$user;
	} else {
		$ldap->get_params($ini_file, 'kz');
		$user2="kz1000\\".$user;
	}	
	$ldap->set_connection();
	$ldap->set_bind($user2, $userPW);
	return $ldap->get_result();
}

function mobile_connect_to_ldap($user, $ini_file)
{
	$ldap = new mobile_ldap_connection;
	if (substr($user, 0, 1)=='6'){
		$ldap->get_params($ini_file, 'ru');
	} else {
		$ldap->get_params($ini_file, 'kz');
	}	
	$ldap->set_connection();
	$ldap->set_bind();
	return $ldap->get_result($user);
}

function connect_to_mssql($ini_file)
{
	$mssql = new mssql_connection;
	$mssql->get_params($ini_file);
	$mssql->set_connection();
	return $mssql;
}

function connect_to_mysql($ini_file)
{
	$mysql = new mysql_connection;
	$mysql->get_params($ini_file);
	$mysql->set_connection();
	return $mysql;
}

function connect_to_postgre($ini_file)
{
	$postgre = new postgre_connection;
	$postgre->get_params($ini_file);
	$postgre->set_connection();
	return $postgre;
	
}

 
?>