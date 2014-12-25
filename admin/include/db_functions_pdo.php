<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) http://www.dadabik.org/
Copyright (C) 2001-2012  Eugenio Tacchini

This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). At a glance, you can use this program and you can modifiy it to create a database application for you or for other people but you cannot redistribute the program files in any format. All the details, including examples, in dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact eugenio.tacchini@gmail.com
***********************************************************************************
*/
?>
<?php
// db functions
function connect_db($server, $user, $password, $name_db)
{
	global $debug_mode, $dbms_type;
	
		try {
			switch ($dbms_type){
				case 'sqlite':
					$conn = new PDO($dbms_type.":".$name_db, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
					break;
				case 'mysql':
					//$conn = new PDO('mysql:host='.$server.';dbname='.$name_db, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
					
					$conn = new PDO('mysql:host='.$server.';dbname='.$name_db, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
					
					$res = execute_db("SET NAMES 'UTF8'", $conn);
					
					
					break;
				case 'postgres':
					$conn = new PDO('pgsql:dbname='.$name_db.';host='.$server, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
					
					$res = execute_db("SET NAMES 'UTF8'", $conn);
					
					break;
				default:
					echo 'Error';
					exit;
			}
  		}
		catch(PDOException $e)
    	{
    		echo '<b>[06] Error:</b> during database connection.';
    		if ($debug_mode === 1){
    			echo '<br/>The DBMS server said: '.$e->getMessage();
    		}
    	}
    	
	return $conn;
}

function create_table_db($conn, $table_name, $fields)
{
	$sql = "CREATE TABLE ".$table_name." (".$fields;

	execute_db($sql, $conn);
}

function drop_table_db($conn, $table_name)
{
	if (table_exists($table_name)) {
		$sql = "DROP TABLE $table_name";

		execute_db($sql, $conn);
	} // end if
}

function create_index_db($conn, $data_dictionary, $table_name, $index_name, $index_fields, $options_ar)
{
	$sql_ar = $data_dictionary->CreateIndexSQL($index_name, $table_name, $index_fields, $options_ar);
	foreach ($sql_ar as $sql){
		execute_db($sql, $conn);
	} // end foreach
}

/*
function drop_index_db($conn, $data_dictionary, $table_name, $index_name)
{
	$sql_ar = $data_dictionary -> DropIndexSQL ($index_name, $table_name);
	foreach ($sql_ar as $sql){
		execute_db($sql, $conn);
	} // end foreach
}
*/

function execute_db($sql, $conn)
{
	global $debug_mode;
    	
    try {
    	$results = $conn->query($sql);
    	//$results->setFetchMode(PDO::FETCH_BOTH);
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during query execution.';
    	if ($debug_mode === 1){
    		//echo ' '.htmlentities($sql).'<br/>The DBMS server said: '.$e->getMessage();
    		echo ' '.htmlspecialchars($sql).'<br/>The DBMS server said: '.$e->getMessage();
    	}
    	exit();
    }
    
	return $results;
}

function format_date_for_dbms($date)
{
	return "'".$date."'";
}

function format_date_time_for_dbms($date_time)
{
	return "'".$date_time."'";
}



function execute_db_limit($sql, $conn, $records_page, $start_from)
{
	global $debug_mode, $dbms_type;
	
	switch($dbms_type){
		case 'mysql':
		case 'sqlite':
			$sql .= " LIMIT ".$start_from.", ".$records_page;
			break;
		case 'postgres':
			$sql .= " OFFSET ".$start_from." LIMIT ".$records_page;
			break;
		default:
			echo 'Error';
			exit;
	}
	
	try {
    	$results = $conn->query($sql);
    	//$results->setFetchMode(PDO::FETCH_BOTH);
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during query execution.';
    	if ($debug_mode === 1){
    		//echo ' '.htmlentities($sql).'<br/>The DBMS server said: '.$e->getMessage();
    		echo ' '.htmlspecialchars($sql).'<br/>The DBMS server said: '.$e->getMessage();
    	}
    	exit();
    }
    
	return $results;
}

function fetch_row_db(&$rs)
{
	
	try {
		return $rs->fetch();
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during record fetching.';
    	
    	exit();
    }
	
}

function get_num_rows_db($input, $use_sql=0)
{
	
	global $conn;
	
	if ($use_sql === 1){
		$sql = $input;
	}
	else{
		$sql = $input->queryString;
	}
	
	$temp_ar = explode(" FROM ", $sql);
	
	$sql = "SELECT COUNT(*) FROM ".$temp_ar[1];
	
	$res = execute_db($sql, $conn);
	
	$row = fetch_row_db($res);
	
	return (int)($row[0]);
}

function get_last_ID_db()
{
	global $conn;
	
	try {
		return $conn->lastInsertId();
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during last ID fetching.';
    	
    	exit();
    }
	
	
}
 
 
 /*
function list_fields_db($db_name, $table_name, $conn)
{
	//return mysql_list_fields ($db_name, $table_name, $conn);
	return $conn->MetaColumns($table_name);
}

*/

function num_fields_db($fields)
{
	
	try {
		return $fields->columnCount();
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during number field fetching.';
    	
    	exit();
    }
}

function get_unique_field_db($table_name, $directly_from_db = 0)
// goal: get the name of the first uniqe field in a table
// input: $table_name
// output: $unique_field_name, the name of the first unique field in the table
{
	global $conn, $dbms_type, $quote, $table_list_name;
	
	switch($directly_from_db){
		case 0:
			$sql = "SELECT pk_field_table FROM ".$quote.$table_list_name.$quote." WHERE name_table = '".$table_name."'";
		
			$res = execute_db($sql, $conn);
			
			$row = fetch_row_db($res);
			
			return $row['pk_field_table'];
			
			break;
		case 1:
		
			switch ($dbms_type){
				case 'mysql':
					$sql = "show columns from ".$quote.$table_name.$quote;
					break;
				case 'sqlite':
					$sql = "PRAGMA table_info(".$quote.$table_name.$quote.")";
					break;
				case 'postgres':
					$sql = "SELECT pg_attribute.attname FROM pg_index, pg_class, pg_attribute WHERE pg_class.oid = '".$table_name."'::regclass AND indrelid = pg_class.oid AND pg_attribute.attrelid = pg_class.oid AND  pg_attribute.attnum = any(pg_index.indkey)";
					break;
				default:
					echo 'Error';
					exit;
			}
			
			foreach (execute_db($sql, $conn) as $row){
				switch ($dbms_type){
					case 'mysql':
						if ($row['Key'] == 'PRI'){
							return $row['Field'];
						}
						break;
					case 'sqlite':
						if ($row['pk'] == 1){
							return $row['name'];
						}
						break;
					case 'postgres':
						return $row['attname'];
						break;
					default:
						echo 'Error';
						exit;
				}
			}
			
			return NULL;
			
			
			break;
	}
	
} // end function get_unique_field_db


function get_tables_list()
// goal: get a list of the tables available in the current database
// input:
// output: $tables_ar, an array containing all the table names
{
	global $conn, $dbms_type;
	$tables_ar = array();
	
	switch ($dbms_type){
		case 'mysql':
			$sql = "SELECT TABLE_NAME as name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=SCHEMA() order by TABLE_NAME";
			break;
		case 'sqlite':
			$sql = "SELECT name FROM sqlite_master WHERE (type='table' or type = 'view') AND name <> 'sqlite_sequence' order by name";
			break;
		case 'postgres':
			$sql = "select table_name as name from information_schema.tables where table_schema not in ('pg_catalog','information_schema') union select table_name as name from information_schema.views where table_schema not in ( 'pg_catalog','information_schema') order by name";
			break;
		default:
			echo 'Error';
			exit;
	}
	foreach (execute_db($sql, $conn) as $row) $tables_ar[] = $row['name'];
	
	return $tables_ar;
	
}

function get_fields_list($table_name)
// goal: get a list of the fields available in a table
// input:
// output: $fields_ar, an array containing all the field names
{
	global $conn, $dbms_type, $quote;
	$fields_ar = array();
	
	switch ($dbms_type){
		case 'mysql':
			$sql = "show columns from ".$quote.$table_name.$quote;
			foreach (execute_db($sql, $conn) as $row) $fields_ar[] = $row[0];
			break;
		case 'sqlite':
			$sql = "PRAGMA table_info(".$quote.$table_name.$quote.")";
			foreach (execute_db($sql, $conn) as $row) $fields_ar[] = $row[1];
			break;
		case 'postgres':
			$sql = "SELECT column_name FROM information_schema.columns WHERE table_name ='".$table_name."' order by ordinal_position";
			foreach (execute_db($sql, $conn) as $row) $fields_ar[] = $row[0];
			break;
		default:
			echo 'Error';
			exit;
	}
	
	return $fields_ar;
	
}

function escape($string)
{
	global $conn, $dbms_type;
	
	switch ($dbms_type){
		case 'mysql':
			return addslashes($string);
			break;
		case 'postgres':
			return str_replace("'", "''", $string);
			break;
		case 'sqlite':
			return str_replace("'", "''", $string);
			break;
		default:
			echo 'Error';
			exit;
	}
}

function unescape($string)
{
	global $conn, $dbms_type;
	
	switch ($dbms_type){
		case 'mysql':
			return stripslashes($string);
			break;
		case 'postgres':
			return str_replace("''", "'", $string);
			break;
		case 'sqlite':
			return str_replace("''", "'", $string);
			break;
		default:
			echo 'Error';
			exit;
	}
}

function begin_trans_db()
{
	global $conn;
	
	try {
		$conn->beginTransaction();
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during transaction start.';
    	
    	exit();
    }
}

function complete_trans_db()
{
	global $conn;
	
	try {
		$conn->commit();
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during transaction commit.';
    	
    	exit();
    }
}
?>