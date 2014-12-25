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
header('Content-Type: text/html; charset=utf-8');
ob_start();

ini_set('session.cookie_path', $site_path);

session_start();

if (!isset($_POST)){
	$_POST=$HTTP_POST_VARS;
}
if (!isset($_GET)){
	$_GET=$HTTP_GET_VARS;
}
if (!isset($_FILES)){
	$_FILES=$HTTP_POST_FILES;
}
if (!isset($_SESSION)){
	$_SESSION=$HTTP_SESSION_VARS;
}


if ( (double)phpversion() >= 5.1){
	date_default_timezone_set($timezone);
}

if ($dbms_type != "" && $db_name != "" && $site_url != "" && $site_path != "" &&  $timezone != "" && ($host != "" && $user != "" || $dbms_type === 'sqlite' || $dbms_type === 'sqlite2')) {

	$conn = connect_db($host, $user, $pass, $db_name);
	
	if ($dbms_type === 'mssql' || $dbms_type === 'oci8po' || $dbms_type === 'sqlite' || $dbms_type === 'sqlite2') { // hack for mssql and oracle
		$quote = '';
		$autoincrement_word = 'INTEGER PRIMARY KEY AUTOINCREMENT';
		$date_time_word = 'datetime';
	} // end if
	elseif($dbms_type === 'mysql'){
		$autoincrement_word = 'INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT';
		$date_time_word = 'datetime';
		$quote = '`';
	}
	elseif($dbms_type === 'postgres'){
		$autoincrement_word = 'SERIAL';
		$date_time_word = 'timestamp';
		$quote = '"';
	}
	
} // end if
else{
	echo "<p><b>[01] Error:</b> please specify host, username, database name, site url, site path and timezone in config.php. If you use sqlite host and username are not needed.";
	exit;
} // end else


if (get_magic_quotes_gpc()==1) {
    function unescape_array($var) {
        return is_array($var)? array_map("unescape_array", $var):unescape($var);
    }

    $_POST = unescape_array($_POST);
    $_COOKIE = unescape_array($_COOKIE);
    $_GET = unescape_array($_GET);
}

function addslashes_array($var) {
	return is_array($var)? array_map("addslashes_array", $var):escape($var);
}

$_POST = addslashes_array($_POST);
$_COOKIE = addslashes_array($_COOKIE);
$_GET = addslashes_array($_GET);

$users_table_name = $prefix_internal_table.'users';
$users_table_id_field = 'id_user';
$users_table_id_group_field = 'id_group';
$users_table_username_field = 'username_user';
$users_table_password_field = 'password_user'; 

$groups_table_name = $prefix_internal_table.'groups';
$groups_table_name_field = 'name_group'; 
$groups_table_id_field = 'id_group';
$id_admin_group = 1;

// tables present in the databse
$table_names_ar = build_tables_names_array(0, 0);

if (count($table_names_ar) == 0){ // no table
	echo "<p><b>[02] Error:</b> your database ".$db_name." is empty. No tables found. Please create some tables to manage before using DaDaBIK.";
	exit;
} // end if

// the var is set in check_login but check_login it's not included by e.g. admin, and it's useful for some functions (e.g. build_tables_names_array) to have it set
$current_user_is_administrator = 0;

if ($mbstring_check === 1 && (!function_exists('mb_strlen') || !function_exists('mb_strpos') || !function_exists('mb_strtolower') || !function_exists('mb_strtoupper') || !function_exists('mb_substr') || !function_exists('mb_send_mail'))){
	echo '<p><b>[09] Error:</b> the PHP mbstring extension is not installed, without this extension, if you have multibyte encoding content, you can get unexpected results such as content corruption. If you want to suppress this message at your own risk, set $mbstring_check to 0 in config.php';
	exit;
}






?>