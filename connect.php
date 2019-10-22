<?php
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
		try
		{
			$connectionInfo = array("Database" => $this->database, "UID" => $this->user, "PWD" => $this->userPW);			
			$this->conn = sqlsrv_connect($this->server, $connectionInfo);
		
		}
		catch (Exception $e)
		{
			echo 'Ошибка при подключении к MSSQL серверу:' . $e->getMessage() . '\n';
		}
		
	}
	
	function sql_query($query_str)
	{
		if (isset($this->conn))
		{
			try 
			{					
				ini_set('max_execution_time', 2000);
				$arr = sqlsrv_query($this->conn, $query_str);
				$query_type = explode(' ', $query_str);
				if (strtoupper($query_type[0]) == 'SELECT')
				{
					$result = array();
					while($val = sqlsrv_fetch_array($arr))
					{
						array_push($result, $val);
					}		
					return $result;
				}					
			}
			catch (Exception $e)
			{
				echo 'Ошибка при выполнении запроса' . $e->getmessage() . '\n';
			} finally {
				mssql_close();
			}
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
		try
		{
			$connectionStr = 'host=' . $this->server . ' port=' . $this->port . ' dbname=' . $this->database . ' user=' . $this->user . ' password=' . $this->userPW;   	
			$this->conn = pg_connect($connectionStr);
		
		}
		catch (Exception $e)
		{
			echo 'Ошибка при подключении к Postgre серверу:' . $e->getMessage() . '\n';
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
		$this->conn = mysqli_connect($this->server, $this->user, $this->userPW, $this->database) or die("Невозможно подключиться к серверу MySQL!");
		//mysqli_query($this->conn, "SET NAMES 'utf8'");
		//ini_set('max_execution_time', 720);
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
	
	function addItem($card_id, $date, $username, $department, $address, $box, $position, $sku, $lm, $name, $kol, $type){
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
				('".$card_id."',
				'".$date."',
				'".$username."',
				'".$department."',
				'".$address."',
				'".$box."',
				'".$position."',
				'".$sku."',
				'".$lm."',
				'".$name."',
				'".$kol."',
				'".$type."');";				
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