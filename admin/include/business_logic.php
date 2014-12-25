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
/*
function check_login()
//goal: check on role to adjust permission in case $current_user is an admin
// input:
// output:
{
	/*global $simple_login_user;

	$simple_login_user->CheckLogin();
	$simple_login_user->ManageUsers(); // put this line if you want the admin to see the manager interface
	*/
/*lz:*****************ADVANCED LOGIN**************/
//goal: this function is only called when entering the users table (set in config and called in common-start) to check
//that only users with admin-role have permission to access this table
/*
	global $auth;
	
	$role = $auth->getSessionVar('role');
	
	return $role;
	
} // end function check_login()
*/

function check_login()
// goal: check if the user is logged, if not display the login page
// input:
// output:
{
	global $simple_login_user;

	$simple_login_user->CheckLogin();
	$simple_login_user->ManageUsers(); // put this line if you want the admin to see the manager interface

} // end function check_login()


/*
function get_user()
// goal: get the current user
// input: nothing
// output: $user
{
	/*global $simple_login_user;
	
	$user = $simple_login_user->userName();*/
/*lz:*****************ADVANCED LOGIN**************/
/*
    global $auth;
    
	$user = $auth->getSessionVar('login');

	return $user;
} // end function get_ID_user
*/

function get_user()
// goal: get the current user
// input: nothing
// output: $user
{
	global $simple_login_user;
	
	$user = $simple_login_user->userName();

	return $user;
} // end function get_ID_user

function unlock_record($id_locked_record)
// goal: unlock a record
{
	global $conn, $quote, $prefix_internal_table;
	
	$sql = "delete from ".$quote.$prefix_internal_table."locked_records".$quote." WHERE ".$quote."id_locked_record".$quote." = '".$id_locked_record."'";
	
	$res = execute_db($sql, $conn);
}

function unlock_expired_locks($table_name)
// goal: delete expired locks related to a table
{
	global $conn, $quote, $prefix_internal_table, $seconds_after_automatic_unlock;
	
	$sql = "delete from ".$quote.$prefix_internal_table."locked_records".$quote." where (".time()."-".$quote."timestamp_locked_record".$quote.") >  ".$seconds_after_automatic_unlock;
	
	$res = execute_db($sql, $conn);
}

function get_lock_infos($table_name, $where_field, $where_value)
// goal: get the username and other infos about the current lock of a record, if exists a lock on that record, otherwise return false
{
	global $conn, $quote, $prefix_internal_table;
	
	$sql = "select ".$quote."username_user".$quote.", ".$quote."id_locked_record".$quote." FROM ".$quote.$prefix_internal_table."locked_records".$quote." WHERE ".$quote."table_locked_record".$quote." = '".$table_name."' AND ".$quote."pk_locked_record".$quote." = '".$where_field."' AND ".$quote."value_locked_record".$quote." = '".$where_value."'";
	
	$res = execute_db($sql, $conn);
	
	$num_rows = get_num_rows_db($res);
	
	if ($num_rows === 1){
	
		$row = fetch_row_db($res);
		$lock_infos['username_user'] = $row['username_user'];
		$lock_infos['id_locked_record'] = $row['id_locked_record'];
		
		return $lock_infos;
	}
	elseif($num_rows === 0){
		return false;
	}
	else{
		echo 'Unexpected error';
		exit;
	}
	
}

function lock_record($table_name, $where_field, $where_value, $current_user)
// goal: write a record in the locks tabe to lock a record
{
	global $conn, $quote, $prefix_internal_table;
	
	if (strlen_custom($table_name) > 50 || strlen_custom($where_field) > 50 || strlen_custom($where_value) > 100){
		echo "<p><b>[07] Error:</b> your table name, unique field name or unique field value is larger than allowed, the record locking feature could produce unexpected results. You should make them shorter or disable the record locking feature.";
		
		exit;
	}
	
	$sql = "insert into ".$quote.$prefix_internal_table."locked_records".$quote." (".$quote."table_locked_record".$quote.", ".$quote."pk_locked_record".$quote.", ".$quote."value_locked_record".$quote.", ".$quote."username_user".$quote.", ".$quote."timestamp_locked_record".$quote.") values ('".$table_name."', '".$where_field."', '".$where_value."', '".escape($current_user)."', ".time().")";
	
	$res = execute_db($sql, $conn);
	
	// for maximum compatibility I use this instaed of lastid
	$sql = "select ".$quote."id_locked_record".$quote." FROM ".$quote.$prefix_internal_table."locked_records".$quote." WHERE ".$quote."table_locked_record".$quote." = '".$table_name."' AND ".$quote."pk_locked_record".$quote." = '".$where_field."' AND ".$quote."value_locked_record".$quote." = '".$where_value."'";
	
	$res = execute_db($sql, $conn);
	
	$num_rows = get_num_rows_db($res);
	
	if ($num_rows === 1){
	
		$row = fetch_row_db($res);
		$id_locked_record = $row['id_locked_record'];
		
		return $id_locked_record;
	}
	else{
		echo 'Unexpected error';
		exit;
	}
	
}


function get_current_version($retrieve_version_2=1)
{
	global $conn, $prefix_internal_table, $quote;
	
	if (table_exists($prefix_internal_table.'installation_tab')){
	
		if ($retrieve_version_2 === 1){
			$sql = "SELECT dadabik_version_installation, dadabik_version_2_installation, date_time_installation FROM ".$quote.$prefix_internal_table.'installation_tab'.$quote;
			
			$res = execute_db($sql, $conn);
		
			$row = fetch_row_db($res);
			$dadabik_version_installation = $row['dadabik_version_installation'];
			$dadabik_version_2_installation = $row['dadabik_version_2_installation'];
			$date_time_installation = $row['date_time_installation'];
		
			
		}
		else{
			
			$sql = "SELECT dadabik_version_installation, date_time_installation FROM ".$quote.$prefix_internal_table.'installation_tab'.$quote;
			
			$res = execute_db($sql, $conn);
		
			$row = fetch_row_db($res);
			$dadabik_version_installation = $row['dadabik_version_installation'];
			$dadabik_version_2_installation = '';
			$date_time_installation = $row['date_time_installation'];
		}
		
		
		$upgrade = manage_other_infos_installation('select', 'upgrade_version'); // check if there were upgrades
		
		if ($upgrade !== ''){
			$upgrade_time = manage_other_infos_installation('select', 'upgrade_time'); // check if there were upgrades
			$dadabik_version_installation = $upgrade.','.date("Y-m-d H:i:s", $upgrade_time).','.$dadabik_version_2_installation;
			
		}
		else{
			$dadabik_version_installation = $dadabik_version_installation.','.$date_time_installation.','.$dadabik_version_2_installation;
		}
		
		return $dadabik_version_installation;
	}
	else{
		return 'unknown';
	}
}

function get_max_number_fields_row($fields_ar, $field_to_ceck, $new_line_field){
// get the maximum number of fields which stand on the same row in the form

    $max = 1;
    $count = 1;
    foreach($fields_ar as $fields_ar_elm){
        if ($fields_ar_elm[$field_to_ceck] === '1'){
            if ($fields_ar_elm[$new_line_field] === '0'){
                $count++;
                if ($count > $max ){
                    $max = $count;
                }
            }
            else{
                $count = 1;
            }
        }
    }
    return $max;
}

function current_user_is_owner($where_field, $where_value, $table_name, $fields_labels_ar){
// goal: check if authentication is required and, if yes, if the current user is the owner so that he can delete/update the record.
// input: $where_field, $where_value, $table_name (to identify the record), $fields_labels_ar
// output: true|false

	global $current_user, $conn, $db_name, $quote;

	// get the name of the field that has ID_user type
	$ID_user_field_name = get_ID_user_field_name($fields_labels_ar);

	if ($ID_user_field_name === false) {
		return true; // no ID_user field type, no authentication needed
	} // end if
	else {
		// check if the owner of the record is current_user
		$sql = "SELECT ".$quote.$ID_user_field_name.$quote." FROM ".$quote.$table_name.$quote." WHERE ".$quote.$where_field.$quote." = '".$where_value."' AND ".$quote.$ID_user_field_name.$quote." = '".escape($current_user)."'";

		$res = execute_db($sql, $conn);
		$num_rows= get_num_rows_db($res);

		if ($num_rows === 1){
			return true;
		} // end if
		else{
			return false;
		} // end else
	} // end else

} // end function current_user_is_owner()

function buid_static_pages_ar()
{
	global $conn, $quote, $prefix_internal_table;
	
	$static_pages_ar = array();
	$cnt = 0;
	
	$sql = "select id_static_page, link_static_page, content_static_page, is_homepage_static_page FROM ".$quote.$prefix_internal_table.'static_pages'.$quote;
	
	$res = execute_db($sql, $conn);
	
	while($row = fetch_row_db($res)){
		$static_pages_ar[$cnt]['id_static_page'] = $row['id_static_page']; 
		$static_pages_ar[$cnt]['link_static_page'] = $row['link_static_page'];
		$static_pages_ar[$cnt]['content_static_page'] = $row['content_static_page'];
		$static_pages_ar[$cnt]['is_homepage_static_page'] = $row['is_homepage_static_page'];
		$cnt++;
	}
	
	return $static_pages_ar;
}

function get_ID_user_field_name($fields_labels_ar)
// goal: get the name of the first ID_user type field
//input: $fields_labels_ar
//output: the field name or false if there aren't any ID_user field so the authentication is not needed
{
	$ID_user_field_name = false;

	$fields_labels_ar_count = count($fields_labels_ar);
	$i = 0;
	
	while ($i < $fields_labels_ar_count && $ID_user_field_name === false) {
		if ($fields_labels_ar[$i]['type_field'] === 'ID_user') {
			$ID_user_field_name = $fields_labels_ar[$i]['name_field'];
		} // end if
		$i++;
	} // end while

	return $ID_user_field_name;
} // end function get_ID_user_field_name()

function user_is_allowed($user_infos_ar, $object_type_permission, $object_permission, $permit_type)
{
	$user_is_allowed = 0;
	
	
	
	foreach ($user_infos_ar['permissions']['group'] as $permissions_row){
		
		if ($permissions_row['object_type_permission'] === $object_type_permission && $permissions_row['object_permission'] === $object_permission && $permissions_row['id_permission_type'] == $permit_type){
			$user_is_allowed = (int)$permissions_row['value_permission'];
			
		}
	}
	/*
	foreach ($user_infos_ar['permissions']['user'] as $permissions_row){
		if ($permissions_row['object_type_permission'] === $object_type_permission && $permissions_row['object_permission'] === $object_permission && $permissions_row['id_permission_type'] === $permit_type){
			$user_is_allowed = (int)$permissions_row['value_permission'];
		}
	}
	*/
	
	return $user_is_allowed;
}

function get_user_infos_ar_from_username_password($username_user, $password_user, $just_refresh_permissions = 0, $use_id_instead_username=0)
// goal: get information about a user, starting from the username and the password
// input: $username_user, $password_user
// output: the array $user_infos_ar or FALSE if no user is associated to the username and password couple
{
	global $conn, $users_table_name, $users_table_username_field, $users_table_password_field, $quote, $prefix_internal_table, $generate_portable_password_hash, $enable_granular_permissions, $users_table_id_field, $groups_table_id_field, $users_table_id_group_field;
	
	if ($just_refresh_permissions === 0){
	
		if ($use_id_instead_username === 1){
		
		$sql = "SELECT ".$quote.$users_table_id_field.$quote.", ".$quote.$users_table_id_group_field.$quote.", ".$quote.$users_table_username_field.$quote.", ".$quote.$users_table_password_field.$quote." FROM ".$quote.$users_table_name.$quote." WHERE ".$quote.$users_table_id_field.$quote." = ".$username_user;
		}
		else{
		$sql = "SELECT ".$quote.$users_table_id_field.$quote.", ".$quote.$users_table_id_group_field.$quote.", ".$quote.$users_table_username_field.$quote.", ".$quote.$users_table_password_field.$quote." FROM ".$quote.$users_table_name.$quote." WHERE ".$quote.$users_table_username_field.$quote." = '".$username_user."'";
		}
	
		$res = execute_db($sql, $conn);
	
		if (get_num_rows_db($res) === 1){
			$row = fetch_row_db($res);
			$user_infos_ar['id_user'] = $row[$users_table_id_field];
			$user_infos_ar['id_group'] = $row[$users_table_id_group_field];
			$user_infos_ar['username_user'] = $row[$users_table_username_field];

			$t_hasher = new PasswordHash(8, $generate_portable_password_hash);
			
			$check = $t_hasher->CheckPassword($password_user, $row[$users_table_password_field]);
			
			if ($check === false){
				return false;
			}
		} // end if
		else {
			return false;
		} // end else
	}
	else{
	
		if ($use_id_instead_username === 1){
	
		$sql = "SELECT ".$quote.$users_table_id_field.$quote.", ".$quote.$users_table_id_group_field.$quote.", ".$quote.$users_table_username_field.$quote." FROM ".$quote.$users_table_name.$quote." WHERE ".$quote.$users_table_id_field.$quote." = ".$username_user;
	
		}
		else{
	
		$sql = "SELECT ".$quote.$users_table_id_field.$quote.", ".$quote.$users_table_id_group_field.$quote.", ".$quote.$users_table_username_field.$quote." FROM ".$quote.$users_table_name.$quote." WHERE ".$quote.$users_table_username_field.$quote." = '".$username_user."'";
		}
	
		$res = execute_db($sql, $conn);
	
		if (get_num_rows_db($res) === 1){
			$row = fetch_row_db($res);
			$user_infos_ar['id_user'] = $row[$users_table_id_field];
			$user_infos_ar['id_group'] = $row[$users_table_id_group_field];
			$user_infos_ar['username_user'] = $row[$users_table_username_field];
		} // end if
		else {
			return false;
		} // end else
	}
	
	$sql = "select subject_type_permission, object_type_permission, object_permission, id_permission_type, value_permission FROM ".$quote.$prefix_internal_table."permissions".$quote. " WHERE subject_type_permission = 'user' AND id_subject = ".$user_infos_ar['id_user']." OR subject_type_permission = 'group' AND id_subject = '".$user_infos_ar['id_group']."'";
	
	$res = execute_db($sql, $conn);
	
	$user_infos_ar['permissions']['user'] = array();
	$user_infos_ar['permissions']['group'] = array();
	
	$cnt_user = 0;
	$cnt_group = 0;

	while ($row = fetch_row_db($res)) {
		if ($row['subject_type_permission'] === 'user'){
		
			$user_infos_ar['permissions']['user'][$cnt_user]['object_type_permission'] = $row['object_type_permission'];
			$user_infos_ar['permissions']['user'][$cnt_user]['object_permission'] = $row['object_permission'];
			$user_infos_ar['permissions']['user'][$cnt_user]['id_permission_type'] = $row['id_permission_type'];
			$user_infos_ar['permissions']['user'][$cnt_user]['value_permission'] = $row['value_permission'];
			
			$cnt_user++;
		}
		elseif ($row['subject_type_permission'] === 'group'){
		
			$user_infos_ar['permissions']['group'][$cnt_group]['object_type_permission'] = $row['object_type_permission'];
			$user_infos_ar['permissions']['group'][$cnt_group]['object_permission'] = $row['object_permission'];
			$user_infos_ar['permissions']['group'][$cnt_group]['id_permission_type'] = $row['id_permission_type'];
			$user_infos_ar['permissions']['group'][$cnt_group]['value_permission'] = $row['value_permission'];
			
			$cnt_group++;
		}
	
	}
		
	return $user_infos_ar;
	
} // end function get_user_infos_ar_from_username_password()

function build_fields_names_array($table_name)
// goal: build an array ($fields_names_ar) containing the names of the fields of a specified table
// input:name of the table
// output: $fields_names_ar
{
	global $conn;
	$fields_ar = get_fields_list($table_name);

	$i=0;

	// put the name of the table fields in an array
	foreach ($fields_ar as $field){
		if (is_object($field)){ // adodb
			$fields_names_ar[$i] = $field->name;
		}
		else{ // pdo
			$fields_names_ar[$i] = $field;
		}
		$i++;
	} // end foreach

	return $fields_names_ar;
} // end build_fields_names_array function


function build_tables_names_array($exclude_not_allowed = 1, $exclude_not_installed = 1, $inlcude_users_table = 0)
// goal: build an array ($tables_names_ar) containing the names of the tables of the db, excluding the internal tables, get the list from $table_list_name tab if $exclude_not_installed = 1, otherwise directly from the DBMS
// input: $exclude_not_allowed (1 if the tables excluded by the user are excluded), $exclude_not_installed (1 if the tables not installed are excluded), $inlcude_users_table (1 if it is necessary to include the users table, even if the user is not admin (useful in admin.php)
// output: $tables_names_ar
{
	global $conn, $db_name, $prefix_internal_table, $table_list_name, $quote, $users_table_name, $current_user_is_administrator, $dbms_type, $groups_table_name;

	$z = 0;
	$tables_names_ar = array();

	if ( $exclude_not_installed == 1 ) { // get the list from $table_list_name tab 
		$sql = "SELECT name_table FROM ".$quote.$table_list_name.$quote;
		if ( $exclude_not_allowed == 1) { // excluding not allowed if necessary
			$sql .= " WHERE allowed_table = '1'";
		} // end if
		$sql .= " order by alias_table";
		
		$res = execute_db($sql, $conn);
		while ($row = fetch_row_db($res)) {
			
			if ($current_user_is_administrator === 1 || ( ($row[0] !== $users_table_name || $inlcude_users_table === 1) && ($row[0] !== $prefix_internal_table.'static_pages' ||  $inlcude_users_table === 1 ) ||  ($row[0] !== $groups_table_name ||  $inlcude_users_table === 1 )  )) {
				$tables_names_ar[$z] = $row[0];
				$z++;
			} // end if
		}
	} // end if
	else{ // get the list directly from db
		$tables_ar=get_tables_list();
		$table_number = count($tables_ar);
		for ($i=0; $i<$table_number; $i++){
			$table_name_temp = $tables_ar[$i];
			
			// if the table is not internal
			if (  
			
			(substr_custom($table_name_temp, 0, strlen_custom($prefix_internal_table)) != $prefix_internal_table && $table_name_temp != $table_list_name && $table_name_temp != $users_table_name && $table_name_temp != $groups_table_name && $table_name_temp != $prefix_internal_table."static_pages"   ) || 
			
			($table_name_temp == $users_table_name || $table_name_temp == $groups_table_name || $table_name_temp == $prefix_internal_table."static_pages" && ($current_user_is_administrator || $inlcude_users_table == 1)
			
			)
			
			
			&& (!(substr_custom($table_name_temp, 0, 4) === 'bin$') || $dbms_type !== 'oci8po') ){
				$tables_names_ar[$z] = $tables_ar[$i];
				$z++;
			} // end if
			
			
			
			
		} // end for
	} // end else
	return $tables_names_ar;
} // end build_tables_names_array function

function table_exists($table_name)
// goal: check if a table exists
// input: $table_name
// output: true or false
{
	global $conn;
	$tables_ar=get_tables_list();
	$tables_number = count($tables_ar);
	$i=0;;
	while ($tables_number > $i && ($table_name !== $tables_ar[$i]) ) {
		$i++;
	} // end while

	if ($tables_number === $i) {
		return false;
	} // end if
	else {
		return true;
	} // end else
} // end function table_exists

function build_fields_labels_array($table_internal_name, $order_type)
// goal: build an array ($fields_labels_ar) containing the fields labels and other information about fields (e.g. the type, display/don't display) of a specified table to use in the form
// input: name of the internal table, $order_type: 0|1|2 no order| by order_form_field | by id_field
// output: fields_labels_ar, a 2 dimensions associative array: $fields_labels_ar[field_number]["internal table field (e.g. present_insert_form_field)"]
// global $error_messages_ar, the array containg the error messages
{
	global $conn, $error_messages_ar, $quote, $prefix_internal_table, $enable_granular_permissions, $enable_authentication;

	$table_alias_suffixes_ar = array();
	
	$table_name = substr_custom($table_internal_name, strlen_custom($prefix_internal_table));
	
	$sql = "SELECT ".$quote."name_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_filter_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."type_field".$quote.", ".$quote."custom_validation_function_field".$quote.", ".$quote."custom_formatting_function_field".$quote.", ".$quote."custom_csv_formatting_function_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."content_field".$quote.", ".$quote."label_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."where_clause_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."tooltip_field".$quote.",  ".$quote."order_form_field".$quote.", ".$quote."details_separator_before_field".$quote.", ".$quote."insert_separator_before_field".$quote.", ".$quote."search_separator_before_field".$quote.", ".$quote."edit_separator_before_field".$quote.", ".$quote."search_new_line_after_field".$quote.", ".$quote."insert_new_line_after_field".$quote.", ".$quote."edit_new_line_after_field".$quote.", ".$quote."details_new_line_after_field".$quote." FROM ".$quote.$prefix_internal_table."forms".$quote." WHERE ".$quote."table_name".$quote." = '".$table_name."'";
	
	$sql_back = $sql;
	
	if ($order_type == "1"){
		$sql .= " ORDER BY ".$quote."order_form_field".$quote;
	} // end if
	elseif ($order_type == "2") {
		$sql .= " ORDER BY ".$quote."id_field".$quote;
	} // end else

	$res_field = execute_db($sql, $conn);
	
	$i = 0;
	if (get_num_rows_db($sql_back, 1) > 0) { // at least one record
		while($field_row = fetch_row_db($res_field)){
			$fields_labels_ar[$i]["name_field"] = $field_row["name_field"]; // the name of the field
			
			if ($enable_granular_permissions === 1 && $enable_authentication === 1){
				$fields_labels_ar[$i]["present_insert_form_field"] = (string)user_is_allowed($_SESSION['logged_user_infos_ar'], 'field', $table_name.'.'.$field_row["name_field"], '9');
				
				$fields_labels_ar[$i]["present_edit_form_field"] = (string)user_is_allowed($_SESSION['logged_user_infos_ar'], 'field', $table_name.'.'.$field_row["name_field"], '8');
				
				
				$fields_labels_ar[$i]["present_filter_form_field"] = (string)user_is_allowed($_SESSION['logged_user_infos_ar'], 'field', $table_name.'.'.$field_row["name_field"], '11');
				
				
				$fields_labels_ar[$i]["present_search_form_field"] = (string)user_is_allowed($_SESSION['logged_user_infos_ar'], 'field', $table_name.'.'.$field_row["name_field"], '12');
				
				
				$fields_labels_ar[$i]["present_results_search_field"] = (string)user_is_allowed($_SESSION['logged_user_infos_ar'], 'field', $table_name.'.'.$field_row["name_field"], '7');
				
				
				$fields_labels_ar[$i]["present_details_form_field"] = (string)user_is_allowed($_SESSION['logged_user_infos_ar'], 'field', $table_name.'.'.$field_row["name_field"], '10');
			
			}
			elseif ($enable_granular_permissions === 0 || $enable_authentication === 0){
			
				$fields_labels_ar[$i]["present_insert_form_field"] = $field_row["present_insert_form_field"]; // 1 if the user want to display it in the insert form
				
				$fields_labels_ar[$i]["present_edit_form_field"] = $field_row["present_edit_form_field"]; // 1 if the user want to display it in the edit form
			
				$fields_labels_ar[$i]["present_filter_form_field"] = $field_row["present_filter_form_field"]; // 1 if the user want to display it in the filter row
			
				$fields_labels_ar[$i]["present_ext_update_form_field"] = $field_row["present_ext_update_form_field"]; // 1 if the user want to display it in the external update form
			
				$fields_labels_ar[$i]["present_search_form_field"] = $field_row["present_search_form_field"]; // 1 if the user want to display it in the search form
			
				$fields_labels_ar[$i]["present_results_search_field"] = $field_row["present_results_search_field"]; // 1 if the user want to display it in the basic results page
			
				$fields_labels_ar[$i]["present_details_form_field"] = $field_row["present_details_form_field"]; // 1 if the user want to display it in the basic results page
			}
			
			
			$fields_labels_ar[$i]["required_field"] = $field_row["required_field"]; // 1 if the field is required in the insert (the field must be in the insert form, otherwise this flag hasn't any effect
			$fields_labels_ar[$i]["check_duplicated_insert_field"] = $field_row["check_duplicated_insert_field"]; // 1 if the field needs to be checked for duplicated insert
			
            $fields_labels_ar[$i]["insert_new_line_after_field"] = $field_row["insert_new_line_after_field"];
            $fields_labels_ar[$i]["search_new_line_after_field"] = $field_row["search_new_line_after_field"];
            $fields_labels_ar[$i]["details_new_line_after_field"] = $field_row["details_new_line_after_field"];
            $fields_labels_ar[$i]["edit_new_line_after_field"] = $field_row["edit_new_line_after_field"];
			
            $fields_labels_ar[$i]["insert_separator_before_field"] = $field_row["insert_separator_before_field"];
            $fields_labels_ar[$i]["edit_separator_before_field"] = $field_row["edit_separator_before_field"];
            $fields_labels_ar[$i]["search_separator_before_field"] = $field_row["search_separator_before_field"];
            $fields_labels_ar[$i]["details_separator_before_field"] = $field_row["details_separator_before_field"];
			
			$fields_labels_ar[$i]["label_field"] = $field_row["label_field"]; // the label of the field

			// hack for mssql
			if ($fields_labels_ar[$i]["label_field"] === ' ') {
				$fields_labels_ar[$i]["label_field"] = '';
			} // end if

			$fields_labels_ar[$i]["type_field"] = $field_row["type_field"]; // the type of the field
			$fields_labels_ar[$i]["custom_validation_function_field"] = $field_row["custom_validation_function_field"];
			$fields_labels_ar[$i]["custom_formatting_function_field"] = $field_row["custom_formatting_function_field"];
			$fields_labels_ar[$i]["custom_csv_formatting_function_field"] = $field_row["custom_csv_formatting_function_field"];
			
			
			$fields_labels_ar[$i]["other_choices_field"] = $field_row["other_choices_field"]; // 0/1 the possibility to add another choice with select single menu
			$fields_labels_ar[$i]["content_field"] = $field_row["content_field"]; // the control type of the field (eg: numeric, alphabetic, alphanumeric)
			$fields_labels_ar[$i]["select_options_field"] = $field_row["select_options_field"]; // the options, separated by separator, possible in a select field

			// hack for mssql
			if ($fields_labels_ar[$i]["select_options_field"] === ' ') {
				$fields_labels_ar[$i]["select_options_field"] = '';
			} // end if

			$fields_labels_ar[$i]["separator_field"] = $field_row["separator_field"]; // the separator of different possible values for a select field

			// hack for mssql
			if ($fields_labels_ar[$i]["separator_field"] === ' ') {
				$fields_labels_ar[$i]["separator_field"] = '';
			} // end if


			$fields_labels_ar[$i]["primary_key_field_field"] = $field_row["primary_key_field_field"]; // the primary key field_name if this field is a foreign key

			// hack for mssql
			if ($fields_labels_ar[$i]["primary_key_field_field"] === ' ') {
				$fields_labels_ar[$i]["primary_key_field_field"] = '';
			} // end if

			$fields_labels_ar[$i]["primary_key_table_field"] = $field_row["primary_key_table_field"]; // the primary key table_name if this field is a foreign key

			// hack for mssql
			if ($fields_labels_ar[$i]["primary_key_table_field"] === ' ') {
				$fields_labels_ar[$i]["primary_key_table_field"] = '';
			} // end if

			$fields_labels_ar[$i]["primary_key_db_field"] = $field_row["primary_key_db_field"]; // the primary key database if this field is a foreign key
			$fields_labels_ar[$i]["linked_fields_field"] = $field_row["linked_fields_field"]; // the fields linked to through the pk

			// hack for mssql
			if ($fields_labels_ar[$i]["linked_fields_field"] === ' ') {
				$fields_labels_ar[$i]["linked_fields_field"] = '';
			} // end if

			$fields_labels_ar[$i]["linked_fields_order_by_field"] = $field_row["linked_fields_order_by_field"]; // the fields by which order when retreiving the linked fields

			// hack for mssql
			if ($fields_labels_ar[$i]["linked_fields_order_by_field"] === ' ') {
				$fields_labels_ar[$i]["linked_fields_order_by_field"] = '';
			} // end if

			$fields_labels_ar[$i]["linked_fields_order_type_field"] = $field_row["linked_fields_order_type_field"]; // the order type (ASC|DESC) to use in the order clause when retreiving the linked fields

			// hack for mssql
			if ($fields_labels_ar[$i]["linked_fields_order_type_field"] === ' ') {
				$fields_labels_ar[$i]["linked_fields_order_type_field"] = '';
			} // end if

			$fields_labels_ar[$i]["items_table_names_field"] = $field_row["items_table_names_field"]; // the linked items table names
			
			$fields_labels_ar[$i]["where_clause_field"] = $field_row["where_clause_field"]; // the the where clause to use when retreiving the linked fields

			// hack for mssql
			if ($fields_labels_ar[$i]["items_table_names_field"] === ' ') {
				$fields_labels_ar[$i]["items_table_names_field"] = '';
			} // end if

			$fields_labels_ar[$i]["items_table_fk_field_names_field"] = $field_row["items_table_fk_field_names_field"]; // the fx field names of the linked item tables

			// hack for mssql
			if ($fields_labels_ar[$i]["items_table_fk_field_names_field"] === ' ') {
				$fields_labels_ar[$i]["items_table_fk_field_names_field"] = '';
			} // end if

			$fields_labels_ar[$i]["select_type_field"] = $field_row["select_type_field"]; // the type of select, exact match or like
			$fields_labels_ar[$i]["prefix_field"] = $field_row["prefix_field"]; // the prefix of the field (e.g. http:// - only for text, textarea and rich_editor)

			// hack for mssql
			if ($fields_labels_ar[$i]["prefix_field"] === ' ') {
				$fields_labels_ar[$i]["prefix_field"] = '';
			} // end if

			$fields_labels_ar[$i]["default_value_field"] = $field_row["default_value_field"]; // the default value of the field (only for text, textarea and rich_editor)

			// hack for mssql
			if ($fields_labels_ar[$i]["default_value_field"] === ' ') {
				$fields_labels_ar[$i]["default_value_field"] = '';
			} // end if


			$fields_labels_ar[$i]["width_field"] = $field_row["width_field"]; // the width size of the field in case of text, textarea or rich_editor

			// hack for mssql
			if ($fields_labels_ar[$i]["width_field"] === ' ') {
				$fields_labels_ar[$i]["width_field"] = '';
			} // end if

			$fields_labels_ar[$i]["height_field"] = $field_row["height_field"]; // the height size of the field in case of textarea or rich_editor

			// hack for mssql
			if ($fields_labels_ar[$i]["height_field"] === ' ') {
				$fields_labels_ar[$i]["height_field"] = '';
			} // end if

			$fields_labels_ar[$i]["maxlength_field"] = $field_row["maxlength_field"]; // the maxlength of the field in case of text

			// hack for mssql
			if ($fields_labels_ar[$i]["maxlength_field"] === ' ') {
				$fields_labels_ar[$i]["maxlength_field"] = '';
			} // end if

			$fields_labels_ar[$i]["hint_insert_field"] = $field_row["hint_insert_field"]; // the hint to display after the field in the insert form (e.g. use only number here!!)
			
			$fields_labels_ar[$i]["tooltip_field"] = $field_row["tooltip_field"];
			// hack for mssql
			if ($fields_labels_ar[$i]["hint_insert_field"] === ' ') {
				$fields_labels_ar[$i]["hint_insert_field"] = '';
			} // end if

			$fields_labels_ar[$i]["order_form_field"] = $field_row["order_form_field"]; // the position of the field in the form

			if ($field_row["primary_key_field_field"] !== '' && $field_row["primary_key_field_field"] !== NULL) {
				$linked_fields_ar = explode($field_row["separator_field"], $field_row["linked_fields_field"]);

				if ( array_key_exists($field_row["primary_key_table_field"], $table_alias_suffixes_ar) === false){
					$table_alias_suffixes_ar[$field_row["primary_key_table_field"]] = 1;
					$fields_labels_ar[$i]["alias_suffix_field"] = 1;
				} // end if
				else {
					$table_alias_suffixes_ar[$field_row["primary_key_table_field"]]++;
					$fields_labels_ar[$i]["alias_suffix_field"] = $table_alias_suffixes_ar[$field_row["primary_key_table_field"]];
				} // end else

			} // end if

			$i++;
		} // end while
	} // end if
	else { // no records
		echo $error_messages_ar["int_db_empty"];
	} // end else
	return $fields_labels_ar;
} // end build_fields_labels_array function


function build_filters_row($fields_labels_ar, $table_name, $filters_ar)
{
	// goal: build the filter row
	
	global $dadabik_main_file, $submit_buttons_ar, $quote, $conn, $year_field_suffix, $month_field_suffix, $day_field_suffix, $hours_field_suffix, $minutes_field_suffix, $seconds_field_suffix;
	
	$filter_row = '';
	$filter_row .= "\n<!--[if !lte IE 9]><!-->\n";
	$filter_row .= '<form method="post" class="css_form" action="'.$dadabik_main_file.'?table_name='.urlencode($table_name).'&function=search&execute_search=1&search_from_filter=1"><input type="hidden" name="operator" value="and"><table><tr>'."\n";
	$filter_row .= "<!--<![endif]-->\n";
	$filter_row .= "<!--[if lte IE 9]>\n";
	$filter_row .= '<form method="post" action="'.$dadabik_main_file.'?table_name='.urlencode($table_name).'&function=search&execute_search=1&search_from_filter=1"><input type="hidden" name="operator" value="and"><table><tr>'."\n";
	$filter_row .= "<![endif]-->\n";
	
	
	
	$at_least_one_filter = 0;
	
	foreach ($fields_labels_ar as $field_element){
	
		if ($field_element['present_filter_form_field'] == '1'){
		
			$at_least_one_filter = 1;
	
			$primary_key_field_field = $field_element["primary_key_field_field"];
			if ($primary_key_field_field != ""){
		
				$primary_key_field_field = $field_element["primary_key_field_field"];
				$primary_key_table_field = $field_element["primary_key_table_field"];
				$primary_key_db_field = $field_element["primary_key_db_field"];
				$where_clause_field = $field_element["where_clause_field"];
				$linked_fields_field = $field_element["linked_fields_field"];
				$linked_fields_ar = explode($field_element["separator_field"], $linked_fields_field);
				$linked_fields_order_by_field = $field_element["linked_fields_order_by_field"];
				if ($linked_fields_order_by_field !== '' && $linked_fields_order_by_field !== NULL) {
					$linked_fields_order_by_ar = explode($field_element["separator_field"], $linked_fields_order_by_field);
				} // end if
				else {
					unset($linked_fields_order_by_ar);
				} // end else

				$linked_fields_order_type_field = $field_element["linked_fields_order_type_field"];

				$sql = "SELECT ".$quote.$primary_key_field_field.$quote;

				$count_temp = count($linked_fields_ar);
				for ($j=0; $j<$count_temp; $j++) {
					$sql .= ", ".$quote.$linked_fields_ar[$j].$quote;
				}
				$sql .= " FROM ".$quote.$primary_key_table_field.$quote;
				
				if ($where_clause_field != ''){
					$sql .= " WHERE ".$where_clause_field;
				}
				
				$sql_res_primary_key_back = $sql;

				if (isset($linked_fields_order_by_ar)) {
					$sql .= " ORDER BY ";
					$count_temp = count($linked_fields_order_by_ar);
					for ($j=0; $j<$count_temp; $j++) {
						$sql .= $quote.$linked_fields_order_by_ar[$j].$quote.", ";
					}
					$sql = substr_custom($sql, 0, -2); // delete the last ","
					$sql .= " ".$linked_fields_order_type_field;
				} // end if

				$res_primary_key = execute_db($sql, $conn);
			} // end if
		
			$filter_row .= '<td class="td_input_form" nowrap>'.$field_element['label_field'].'<br/>';
			
			$select_type_select = build_select_type_select($field_element['name_field'], $field_element["select_type_field"], 0, $filters_ar); // build the select type select form (is_equal....)
			
			$select_type_date_select = build_select_type_select($field_element['name_field'], $field_element["select_type_field"], 1, $filters_ar); // build the select type select form (is_equal....) for date fields, with the first option blank

			switch ($field_element['type_field']){
				case 'select_single':
					$filter_row .= $select_type_select.'</td>';
					$filter_row .= '<td valign="bottom" nowrap><div class="select_element select_element_filters"><select name="'.$field_element['name_field'].'"><option value=""></option>'; // first blank option

					$field_temp = substr_custom($field_element["select_options_field"], 1, -1); // delete the first and the last separator

					if (trim($field_temp) !== '') {
						$select_values_ar = explode($field_element["separator_field"],$field_temp);

						$count_temp = count($select_values_ar);
						for ($j=0; $j<$count_temp; $j++){
							$filter_row .= '<option value="'.htmlspecialchars($select_values_ar[$j]).'"';
							
							if ( isset($filters_ar['values'][$field_element['name_field']]) && $filters_ar['values'][$field_element['name_field']] === $select_values_ar[$j] ){
								$filter_row .= ' selected';
							}
							
							$filter_row .= ">".htmlspecialchars($select_values_ar[$j])."</option>";
						} // end for
					} // end if

					if ($field_element["primary_key_field_field"] != ""){
						if (get_num_rows_db($sql_res_primary_key_back, 1) > 0){
							$fields_number = num_fields_db($res_primary_key);
							while ($primary_key_row = fetch_row_db($res_primary_key)){
								
								$primary_key_value = $primary_key_row[0];
								$linked_fields_value = "";
								for ($z=1; $z<$fields_number; $z++){
									$linked_fields_value .= $primary_key_row[$z];
									$linked_fields_value .= " - ";
								} // end for
								$linked_fields_value = substr_custom($linked_fields_value, 0, -3); // delete the last " - 
								
								$filter_row .= '<option value="'.htmlspecialchars($primary_key_value).'"';
								
								if ( isset($filters_ar['values'][$field_element['name_field']]) && $filters_ar['values'][$field_element['name_field']] === $primary_key_value ){
									$filter_row .= ' selected';
								}
								
								$filter_row .= ">".htmlspecialchars($linked_fields_value)."</option>"; // second part of the form row
							} // end while
						} // end if
					} // end if

					$filter_row .= "</select></div>";
				
					break;
				case "date":
				case "insert_date":
				case "update_date":
					$filter_row .= $select_type_date_select.'</td>';
					
					if (isset($filters_ar['values'][$field_element['name_field'].$day_field_suffix])){
					
						$date_select = build_date_select($field_element['name_field'],$filters_ar['values'][$field_element['name_field'].$day_field_suffix],$filters_ar['values'][$field_element['name_field'].$month_field_suffix],$filters_ar['values'][$field_element['name_field'].$year_field_suffix], 0, 1);
					}
					else{
						$date_select = build_date_select($field_element['name_field'],'','','', 0, 1);
					}
					
					$filter_row .= '<td valign="bottom" nowrap>'.$date_select;
					
					break;
				case "date_time":
					$filter_row .= $select_type_date_select.'</td>';
					
					if (isset($filters_ar['values'][$field_element['name_field'].$day_field_suffix])){
					
						$date_time_select = build_date_time_select($field_element['name_field'],$filters_ar['values'][$field_element['name_field'].$day_field_suffix],$filters_ar['values'][$field_element['name_field'].$month_field_suffix],$filters_ar['values'][$field_element['name_field'].$year_field_suffix],$filters_ar['values'][$field_element['name_field'].$hours_field_suffix],$filters_ar['values'][$field_element['name_field'].$minutes_field_suffix],$filters_ar['values'][$field_element['name_field'].$seconds_field_suffix], 0, 1);
					}
					else{
						$date_time_select = build_date_time_select($field_element['name_field'],'','','','','','', 0, 1);
					}
					
					$filter_row .= '<td valign="bottom" nowrap>'.$date_time_select;
					
					break;
				default:
					$filter_row .= $select_type_select.'</td>';
					$filter_row .= '<td valign="bottom" nowrap><input type="text" name="'.$field_element['name_field'].'"';
					if ($field_element["width_field"] != ""){
						$filter_row .= ' size="'.$field_element["width_field"].'"';
					} // end if
					
					if ( isset( $filters_ar['values'][$field_element['name_field']] ) ){
						$filter_row .= ' value="'.htmlspecialchars($filters_ar['values'][$field_element['name_field']]).'"';
					}
					
					$filter_row .= ' maxlength="'.$field_element["maxlength_field"].'">';
					
					break;
			}
					
			$filter_row .= '</td><td>&nbsp;&nbsp;&nbsp;</td>';
		
		} // end if
		
	} // end foreach
	
	if ($at_least_one_filter === 0){
		return '';
	}
	
	$filter_row .= '<td valign="bottom"><input class="button_form button_form_quick_search" type="submit" value="'.$submit_buttons_ar['quick_search'].' >>"></td>';
	
	$filter_row .= '</tr></table></form>';
	
	return $filter_row;
	
} // end function

function build_form($table_name, $action, $fields_labels_ar, $form_type, $res_details, $where_field, $where_value, $_POST_2, $_FILES_2, $show_insert_form_after_error, $show_edit_form_after_error, $master_table_name, $master_table_function, $master_table_where_field , $master_table_where_value, $is_items_table, $set_field_default_value, $default_value_field_name, $default_value)
// goal: build a tabled form by using the info specified in the array $fields_labels_ar
// input: $table_name, array containing labels and other info about fields, $action (the action of the form), $form_type, $res_details, $where_field, $where_value (the last three useful just for update forms), $_POST_2, $_FILES_2 (the last two useful for insert and edit to refill the form), $show_insert_form_after_error (0|1), $show_edit_form_after_error (0|1), tha last two useful to know if the inser or edit forms are showed after a not successful insert and update and so it is necessary to refill the fields, $master_table_name, $master_table_function, $master_table_where_field , $master_table_where_value, $is_items_table, $set_field_default_value, $default_value_field_name, $default_value (the last two to set a default value for an insert form, used to insert a record of an item table in a master/details view)
// global: $submit_buttons_ar, the array containing the values of the submit buttons, $normal_messages_ar, the array containig the normal messages, $select_operator_feature, wheter activate or not displaying "and/or" in the search form, $default_operator, the default operator if $select_operator_feature is not activated, $db_name, $size_multiple_select, the size (number of row) of the select_multiple_menu fields, $table_name
// output: $form, the html tabled form
{
	global $conn, $submit_buttons_ar, $normal_messages_ar, $select_operator_feature, $default_operator, $db_name, $size_multiple_select, $upload_relative_url, $show_top_buttons, $quote, $enable_authentication, $enable_browse_authorization, $current_user, $null_checkbox_prefix, $year_field_suffix, $month_field_suffix, $day_field_suffix, $hours_field_suffix, $minutes_field_suffix, $seconds_field_suffix, $start_year, $null_checkbox, $users_table_name, $prefix_internal_table, $enable_granular_permissions, $dadabik_main_file;

	switch ($form_type) {
		case 'insert':
			$function = 'insert';
			$new_line_field = 'insert_new_line_after_field';
			$separator_before_field = 'insert_separator_before_field';
			break;
		case 'update':
			$function = 'update';
			$new_line_field = 'edit_new_line_after_field';
			$separator_before_field = 'edit_separator_before_field';
			break;
		case 'ext_update':
			$function = 'ext_update';
			break;
		case 'search':
			$function = 'search';
			$new_line_field = 'search_new_line_after_field';
			$separator_before_field = 'search_separator_before_field';
			break;
	} // end switch
	
	$form_querystring_temp = '';

	if ( $form_type == "update" or $form_type == "ext_update") {
		$form_querystring_temp .= "&where_field=".urlencode($where_field)."&where_value=".urlencode(unescape($where_value));
	}

	if ( $form_type == "search") {
		$form_querystring_temp .= "&execute_search=1";
	}

	if ($is_items_table === 1) {
		$form_querystring_temp .= '&master_table_name='. urlencode($master_table_name).'&master_table_function='.$master_table_function.'&master_table_where_field='.urlencode($master_table_where_field).'&master_table_where_value='.urlencode(unescape($master_table_where_value)).'&is_items_table='.$is_items_table;
	} // end if

	if ($set_field_default_value === 1) {
		$form_querystring_temp .= '&set_field_default_value=1&default_value_field_name='. urlencode($default_value_field_name).'&default_value='.urlencode($default_value);
	} // end if
	
	$form = "";
	
	$form .= "\n<!--[if !lte IE 9]><!-->\n";
	
	$form .= "<form class=\"css_form\" id=\"dadabik_main_form\" name=\"contacts_form\" method=\"post\" action=\"".$action."?table_name=".urlencode($table_name)."&function=".$function.$form_querystring_temp."\" enctype=\"multipart/form-data\">\n";
	$form .= "<!--<![endif]-->\n";
	$form .= "<!--[if lte IE 9]>\n";
	$form .= "<form id=\"dadabik_main_form\" name=\"contacts_form\" method=\"post\" action=\"".$action."?table_name=".urlencode($table_name)."&function=".$function.$form_querystring_temp."\" enctype=\"multipart/form-data\">\n";
	$form .= "<![endif]-->\n";
	
	$form .= '<table border="0">';

	switch($form_type){
		case "insert":
			$number_cols = 3;
			$field_to_ceck = "present_insert_form_field";
			$max_number_fields_row = get_max_number_fields_row($fields_labels_ar, $field_to_ceck, $new_line_field);
			break;
		case "update":
			$number_cols = 3;
			//$field_to_ceck = "present_insert_form_field";
			$field_to_ceck = "present_edit_form_field";
			$max_number_fields_row = get_max_number_fields_row($fields_labels_ar, $field_to_ceck, $new_line_field);

			if ($show_edit_form_after_error === 0) {
				$details_row = fetch_row_db($res_details); // get the values of the details
				$_SESSION['details_row_back'] = $details_row; // keep it in session, could be useful after submit with error to enable_browse_authorization
			} // end if
			if ( $show_top_buttons == 1) {
				$form .= "<tr class=\"tr_button_form\"><td colspan=\"".$number_cols*$max_number_fields_row."\" class=\"td_button_form\"><input class=\"button_form\" type=\"submit\" value=\"".$submit_buttons_ar[$form_type]." >>\"></td></tr><tr class=\"tr_button_form\"><td colspan=\"".$number_cols*$max_number_fields_row."\" class=\"td_button_form\">&nbsp;</td></tr>";
			}
			
			// if there is a field having associated a NULL value, the null_checkbox parameter is set to 1, even if it was 0 in config
			for ($i=0; $i<count($fields_labels_ar); $i++){
				$field_name_temp = $fields_labels_ar[$i]["name_field"];
				
				if (
								($show_edit_form_after_error === 1 && isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1')
								||
								(isset($details_row) && is_null($details_row[$field_name_temp]))
							){
					$null_checkbox = 1;
					break;
				}
			}
			
			break;
		case "ext_update":
			$number_cols = 4;
			$field_to_ceck = "present_ext_update_form_field";
			$max_number_fields_row = get_max_number_fields_row($fields_labels_ar, $field_to_ceck, $new_line_field);
			$details_row = fetch_row_db($res_details); // get the values of the details
			if ( $show_top_buttons == 1) {
				$form .= "<tr class=\"tr_button_form\"><td colspan=\"".$number_cols."\" class=\"td_button_form\"><input class=\"button_form\" type=\"submit\" value=\"".$submit_buttons_ar[$form_type]."\"></td></tr>";
			}
			break;
		case "search":
			$number_cols = 2;
			$field_to_ceck = "present_search_form_field";
			$max_number_fields_row = get_max_number_fields_row($fields_labels_ar, $field_to_ceck, $new_line_field);
			if ($select_operator_feature == "1"){
				$form .= "<tr class=\"tr_operator_form\"><td colspan=\"".$number_cols*$max_number_fields_row."\" class=\"td_button_form\"><div class=\"select_element select_element_searcs_operator\"><select name=\"operator\"><option value=\"and\">".$normal_messages_ar["all_conditions_required"]."</option><option value=\"or\">".$normal_messages_ar["any_conditions_required"]."</option></select></div><br/></td></tr>";
			} // end if
			else{
				$form .= "<input type=\"hidden\" name=\"operator\" value=\"".$default_operator."\">";
			} // end else
			if ( $show_top_buttons == 1) {
				$form .= "<tr class=\"tr_button_form\"><td colspan=\"".$number_cols*$max_number_fields_row."\"><input  class=\"button_form\" type=\"submit\" value=\"".$submit_buttons_ar[$form_type]." >>\"></td></tr><tr class=\"tr_button_form\"><td colspan=\"".$number_cols*$max_number_fields_row."\">&nbsp;</td></tr>";
			}
			break;
	} // end switch
	
	

	if ($form_type === 'insert' || $form_type === 'update') {
		// check if there is at least a not required field in the form or there is a null value in the db
		$form_has_not_requied_field = 0;
		$record_has_null_fields = 0;
		

		$i=0;
		$count_temp = count($fields_labels_ar);
		while ($i < $count_temp && $form_has_not_requied_field === 0) {
			$field_name_temp = $fields_labels_ar[$i]["name_field"];
			if (($fields_labels_ar[$i]['present_insert_form_field'] === '1' && $form_type === 'insert' || $fields_labels_ar[$i]['present_edit_form_field'] === '1' && $form_type === 'update')  && $fields_labels_ar[$i]['required_field'] === '0') {
				$form_has_not_requied_field = 1;
			} // end if
			
			if ($form_type === 'update'){
				if (($show_edit_form_after_error === 0 && is_null($details_row[$field_name_temp])) || ($show_edit_form_after_error === 1 && isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1')){
					$record_has_null_fields = 1;
				}
			}
			
			
			$i++;
		} // end while
		
		/*
		if ($form_has_not_requied_field && $null_checkbox === 1 || $form_type === 'update' && $record_has_null_fields === 1) {
			$form .= '<tr>';
				for ($i=0;$i<$max_number_fields_row;$i++){
					$form .= '<td></td><td><span class="null_word">'.$normal_messages_ar['null'].'</td><td></td>';
				}
			$form .= '</tr>';
		} // end if
		*/
	} // end if
	
	$open_new_line = 1;
    
    $fields_in_row_counter = 0;
    
	for ($i=0; $i<count($fields_labels_ar); $i++){
		if ($fields_labels_ar[$i][$field_to_ceck] == "1") { // the user want to display the field in the form
		
			if ($fields_labels_ar[$i][$separator_before_field] !== ''){
				$form .= "<tr><td colspan=\"".$number_cols*$max_number_fields_row."\"></td></tr>";
				$form .= "<tr class=\"tr_form_separator\"><td colspan=\"".$number_cols*$max_number_fields_row."\">".$fields_labels_ar[$i][$separator_before_field]."</td></tr>";
				$form .= "<tr><td colspan=\"".$number_cols*$max_number_fields_row."\"></td></tr>";
			}
			
			
			if (substr_custom($fields_labels_ar[$i]["default_value_field"], 0, 4) === 'SQL:'){
				
				$sql_temp = substr_custom($fields_labels_ar[$i]["default_value_field"],4,strlen_custom($fields_labels_ar[$i]["default_value_field"]));
				
				
				$res_temp = execute_db($sql_temp, $conn);
				
				$row_temp = fetch_row_db($res_temp);
				
				$default_value_field = htmlspecialchars($row_temp[0]);
			}
			else{
				$default_value_field = htmlspecialchars($fields_labels_ar[$i]["default_value_field"]);
			}

			if ($show_edit_form_after_error === 0) {
				// hack for mssql, an empty varchar ('') is returned as ' ' by the driver, see http://bugs.php.net/bug.php?id=26315
				if ($form_type === 'update' && $details_row[$fields_labels_ar[$i]["name_field"]] === ' ') {
					$details_row[$fields_labels_ar[$i]["name_field"]] = '';
				} // end if
			} // end if
			
			if ($open_new_line === 1){
                $form .= "<tr>";
			}
            
            $fields_in_row_counter++;
			
			// build the first coloumn (label)
			//////////////////////////////////
			// I put a table inside the cell to get the same margin of the second coloumn
			//$form .= "<tr><td align=\"right\" valign=\"top\"><table><tr><td class=\"td_label_form\">";
			$form .= "<td align=\"right\" valign=\"top\"><table><tr><td class=\"td_label_form\">";
			$form .= $fields_labels_ar[$i]["label_field"]." ";
			if ($fields_labels_ar[$i]["required_field"] == "1" and $form_type != "search"){
				$form .= "<span style=\"color:red\">*</span>";
			} // end if 
			$form .= "</td></tr></table></td>";
			//////////////////////////////////
			// end build the first coloumn (label)

			$field_name_temp = $fields_labels_ar[$i]["name_field"];
			
			if ($null_checkbox === 1){

				// build the second coloumn (NULL checkbox)
				//////////////////////////////////
				// I put a table inside the cell to get the same margin of the third coloumn
	
				switch ($form_type) {
					case 'insert':
						// display the NULL checkbox only if the field is not required
						if ($fields_labels_ar[$i]['required_field'] === '0'){
							$form .= '<td align="right" valign="top"><table><tr><td class="td_null_checkbox_form">';
	
							// display the null checkbox selected if it is a re-display after an error and the checkbox was selected before submitting
							if ($show_insert_form_after_error === 1 && isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1'){
								$form .= '<input type="checkbox" name="'.$null_checkbox_prefix.$field_name_temp.'"  title="'.$normal_messages_ar['is_null'].'" value="1" checked onclick="javascript:enable_disable_input_box_insert_edit_form(\''.$null_checkbox_prefix.'\', \''.$year_field_suffix.'\', \''.$month_field_suffix.'\', \''.$day_field_suffix.'\', \''.$hours_field_suffix.'\', \''.$minutes_field_suffix.'\', \''.$seconds_field_suffix.'\')">';
							} // end if
							else {
								$form .= '<input type="checkbox" name="'.$null_checkbox_prefix.$field_name_temp.'"  title="'.$normal_messages_ar['is_null'].'" value="1" onclick="javascript:enable_disable_input_box_insert_edit_form(\''.$null_checkbox_prefix.'\', \''.$year_field_suffix.'\', \''.$month_field_suffix.'\', \''.$day_field_suffix.'\', \''.$hours_field_suffix.'\', \''.$minutes_field_suffix.'\', \''.$seconds_field_suffix.'\')">';
							} // end else
	
							$form .= '</td></tr></table></td>';
						} // end if
						// otherwise build an empty cell
						else {
							$form .= '<td align="right" valign="top"><table><tr><td class="td_null_checkbox_form">';
							$form .= '</td></tr></table></td>';
						} // end else
						break;
					case 'update':
						// display the NULL checkbox only if the field is not required, unless the NULL value is already in the database
						if ($fields_labels_ar[$i]['required_field'] === '0' || ($show_edit_form_after_error === 0 && is_null($details_row[$field_name_temp])) || ($show_edit_form_after_error === 1 && isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1')){
							$form .= '<td align="right" valign="top"><table><tr><td class="td_null_checkbox_form">';
	
							/*
							display the null checkbox selected in two cases:
							2) it is a re-display after an error and the checkbox was selected before submitting
							3) the corresponding field in the db is NULL
							*/
							if (
								($show_edit_form_after_error === 1 && isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1')
								||
								(isset($details_row) && is_null($details_row[$field_name_temp]))
							){
								$form .= '<input type="checkbox" title="'.$normal_messages_ar['is_null'].'" name="'.$null_checkbox_prefix.$field_name_temp.'" value="1" checked onclick="javascript:enable_disable_input_box_insert_edit_form(\''.$null_checkbox_prefix.'\', \''.$year_field_suffix.'\', \''.$month_field_suffix.'\', \''.$day_field_suffix.'\', \''.$hours_field_suffix.'\', \''.$minutes_field_suffix.'\', \''.$seconds_field_suffix.'\')">';
							} // end if
							else {
								$form .= '<input type="checkbox" title="'.$normal_messages_ar['is_null'].'" name="'.$null_checkbox_prefix.$field_name_temp.'" value="1" onclick="javascript:enable_disable_input_box_insert_edit_form(\''.$null_checkbox_prefix.'\', \''.$year_field_suffix.'\', \''.$month_field_suffix.'\', \''.$day_field_suffix.'\', \''.$hours_field_suffix.'\', \''.$minutes_field_suffix.'\', \''.$seconds_field_suffix.'\')">';
							} // end else
	
							$form .= '</td></tr></table></td>';
						} // end if
						// otherwise build an empty cell
						else {
							$form .= '<td align="right" valign="top"><table><tr><td class="td_null_checkbox_form">';
							$form .= '</td></tr></table></td>';
						} // end else
						break;
				} // end switch
	
				//////////////////////////////////
				// end build the second coloumn (NULL checkbox)
			}

			// build the third coloumn (input field)
			/////////////////////////////////////////
			$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
			if ($primary_key_field_field != ""){
				/*
				if (substr_custom($foreign_key_temp, 0, 4) == "SQL:"){
					$sql = substr_custom($foreign_key_temp, 4, strlen_custom($foreign_key_temp)-4);
				} // end if
				else{
				*/
					$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
					$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
					$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
					$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
					$where_clause_field = $fields_labels_ar[$i]["where_clause_field"];
					$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
					$linked_fields_order_by_field = $fields_labels_ar[$i]["linked_fields_order_by_field"];
					if ($linked_fields_order_by_field !== '' && $linked_fields_order_by_field !== NULL) {
						$linked_fields_order_by_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_order_by_field);
					} // end if
					else {
						unset($linked_fields_order_by_ar);
					} // end else

					$linked_fields_order_type_field = $fields_labels_ar[$i]["linked_fields_order_type_field"];

					$sql = "SELECT ".$quote.$primary_key_field_field.$quote;

					$count_temp = count($linked_fields_ar);
					for ($j=0; $j<$count_temp; $j++) {
						$sql .= ", ".$quote.$linked_fields_ar[$j].$quote;
					}
					$sql .= " FROM ".$quote.$primary_key_table_field.$quote;
				
					if ($where_clause_field != ''){
						$sql .= " WHERE ".$where_clause_field;
					}
					
					if ($enable_granular_permissions === 1 && $enable_authentication === 1){
	
						$enabled_features_ar_2_linked = build_enabled_features_ar_2($primary_key_table_field);
						
						$enable_browse_authorization_linked = 0;
						
					}
					elseif ($enable_granular_permissions === 0 || $enable_authentication === 0){
						
						$enabled_features_ar_linked = build_enabled_features_ar($primary_key_table_field);
						
						$enable_browse_authorization_linked = 0;
					}
					else{
						echo "<p>An error occured";
					} // end else
					
					$sql_res_primary_key_back = $sql;

					if (isset($linked_fields_order_by_ar)) {
						$sql .= " ORDER BY ";
						$count_temp = count($linked_fields_order_by_ar);
						for ($j=0; $j<$count_temp; $j++) {
							$sql .= $quote.$linked_fields_order_by_ar[$j].$quote.", ";
						}
						$sql = substr_custom($sql, 0, -2); // delete the last ","
						$sql .= " ".$linked_fields_order_type_field;
					} // end if

				$res_primary_key = execute_db($sql, $conn);
				
			} // end if

			if ($form_type == "search"){
				$select_type_select = build_select_type_select($field_name_temp, $fields_labels_ar[$i]["select_type_field"], 0); // build the select type select form (is_equal....)
				$select_type_date_select = build_select_type_select($field_name_temp, $fields_labels_ar[$i]["select_type_field"], 1); // build the select type select form (is_equal....) for date fields, with the first option blank
			} // end if
			else{
				$select_type_select = "";
				$select_type_date_select = "";
			} // end else
			
			$form .= "<td valign=\"top\"><table border=\"0\"><tr>";
			switch ($fields_labels_ar[$i]["type_field"]){
				case "text":
				case "ID_user":
				case "unique_ID":
					$form .= "<td class=\"td_input_form\">".$select_type_select."</td><td class=\"td_input_form\">";
					
					if ($fields_labels_ar[$i]["tooltip_field"] !== ''){
						$form .= "<div class=\"tooltip\">";
					}
					$form .= "<input type=\"text\" name=\"".$field_name_temp."\"";
					if ($fields_labels_ar[$i]["width_field"] != ""){
						$form .= " size=\"".$fields_labels_ar[$i]["width_field"]."\"";
					} // end if
					$form .= " maxlength=\"".$fields_labels_ar[$i]["maxlength_field"]."\"";
					if ($form_type == "update" or $form_type == "ext_update"){
						if ($show_edit_form_after_error === 1){
							if (isset($_POST_2[$field_name_temp])) {
								$form .= " value=\"".htmlspecialchars(unescape($_POST_2[$field_name_temp]))."\"";
							} // end if
						} // end if
						else {
							$form .= " value=\"".htmlspecialchars($details_row[$field_name_temp])."\"";
						} // end else
					} // end if
					if ($form_type == "insert"){
						if ($show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp])) {
							$form .= ' value="'.htmlspecialchars(unescape($_POST_2[$field_name_temp])).'"';
						} // end if
						else {
							$form .= " value=\"".htmlspecialchars($fields_labels_ar[$i]["prefix_field"]).$default_value_field."\"";
						} // end else
					} // end if
					
					$form .= ">";
					
					if ($fields_labels_ar[$i]["tooltip_field"] !== ''){
					
						$form .= "<span class=\"tooltip_content\">".$fields_labels_ar[$i]["tooltip_field"]."</span></div>";
					}
					
					if (($form_type == "update" or $form_type == "insert") and $fields_labels_ar[$i]["content_field"] == "city"){
						$form .= "<a name=\"".$field_name_temp."\" href=\"#".$field_name_temp."\" onclick=\"javascript:fill_cap('".$field_name_temp."')\">?</a>";
					} // end if
					$form .= "</td>"; // add the second coloumn to the form
					break;
				case "generic_file":
				case "image_file":
					if ($form_type == "search") { // build a textbox instead of a file input
						$form .= "<td class=\"td_input_form\">".$select_type_select."</td><td class=\"td_input_form\">";
						
						
					
					if ($fields_labels_ar[$i]["tooltip_field"] !== ''){
					
						$form .= "<div class=\"tooltip\">";
					}
						
						
						
						
						$form .= "<input type=\"text\" name=\"".$field_name_temp."\" size=\"".$fields_labels_ar[$i]["width_field"]."\">";
					}
					else{
						$form .= "<td class=\"td_input_form\">";
						
						
						
						
					
					if ($fields_labels_ar[$i]["tooltip_field"] !== ''){
					
						$form .= "<div class=\"tooltip\">";
					}
						
						
						$form .= "<input type=\"file\" name=\"".
						  $field_name_temp."\" size=\"".$fields_labels_ar[$i]["width_field"]."\">";
						if ($form_type == "update" or $form_type == "ext_update"){
							if ($show_edit_form_after_error === 0) { // show the current file
								$file_name_temp = $details_row[$field_name_temp];
								if ($file_name_temp != ""){
									$form .= "<br>".$normal_messages_ar["current_upload"].": <a href=\"".$upload_relative_url;
									$form .= rawurlencode($file_name_temp);
									$form .= "\">";
									$form .= htmlspecialchars($file_name_temp);
									$form .= "</a><br/><input type=\"checkbox\" value=\"".htmlspecialchars($file_name_temp)."\" name=\"".$field_name_temp."_file_uploaded_delete\"> (".$normal_messages_ar['delete'].")";

									$form .= "<input type=\"hidden\" value=\"1\" name=\"".$field_name_temp."_file_available\">";
								} // end if
								else {
									$form .= "<input type=\"hidden\" value=\"0\" name=\"".$field_name_temp."_file_available\">";
								} // end else
							} // end if
						} // end if
					}
					
					
					if ($fields_labels_ar[$i]["tooltip_field"] !== ''){
					
						$form .= "<span class=\"tooltip_content\">".$fields_labels_ar[$i]["tooltip_field"]."</span></div>";
					}
					$form .= "</td>";
					break;
				case "textarea":
					$form .= "<td class=\"td_input_form\">".$select_type_select."</td>";
					$form .= "<td class=\"td_input_form\">";
					
					if ($fields_labels_ar[$i]["tooltip_field"] !== ''){
					
						$form .= "<div class=\"tooltip\">";
					}
					
					
					$form .= "<textarea cols=\"".$fields_labels_ar[$i]["width_field"]."\" rows=\"".$fields_labels_ar[$i]["height_field"]."\" name=\"".$field_name_temp."\">";
					if ($form_type == "update" or $form_type == "ext_update"){
						if ($show_edit_form_after_error === 1) {
							if (isset($_POST_2[$field_name_temp])) {
								$form .= htmlspecialchars(unescape($_POST_2[$field_name_temp]));
							} // end if
						} // end if
						else {
							$form .= htmlspecialchars($details_row[$field_name_temp]);
						} // end else
					} // end if
					if ($form_type == "insert"){

						if ($show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp])) {
							$form .= htmlspecialchars(unescape($_POST_2[$field_name_temp]));
						} // end if
						else {
							$form .= htmlspecialchars($fields_labels_ar[$i]["prefix_field"]).$default_value_field;
						} // end else
						
					} // end if
					
					$form .= "</textarea>";
					
					if ($fields_labels_ar[$i]["tooltip_field"] !== ''){
					
						$form .= "<span class=\"tooltip_content\">".$fields_labels_ar[$i]["tooltip_field"]."</span></div>";
					}
					
					$form .= "</td>";
					break;
				case "rich_editor":
					$form .= "<td class=\"td_input_form\">".$select_type_select."</td>";
					//$form .= "<td class=\"td_input_form\"><textarea cols=\"".$fields_labels_ar[$i]["width_field"]."\" rows=\"".$fields_labels_ar[$i]["height_field"]."\" name=\"".$field_name_temp."\">";
					
					if ($form_type === 'search'){ // just a normal textarea if it is a search form
						$form .= "<td class=\"td_input_form\"><textarea cols=\"".$fields_labels_ar[$i]["width_field"]."\" rows=\"".$fields_labels_ar[$i]["height_field"]."\" name=\"".$field_name_temp."\">";
					}
					else{
						$form .= "<td class=\"td_input_form\"><textarea cols=\"".$fields_labels_ar[$i]["width_field"]."\" rows=\"".$fields_labels_ar[$i]["height_field"]."\" name=\"".$field_name_temp."\" class=\"rich_editor\">";
					}
					
					if ($form_type == "update" or $form_type == "ext_update"){
						if ($show_edit_form_after_error === 1) {
							if (isset($_POST_2[$field_name_temp])) {
								$form .= htmlspecialchars(unescape($_POST_2[$field_name_temp]));
							} // end if
						} // end if
						else {
							$form .= htmlspecialchars($details_row[$field_name_temp]);
						} // end else
					} // end if
					if ($form_type == "insert"){

						if ($show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp])) {
							$form .= htmlspecialchars(unescape($_POST_2[$field_name_temp]));
						} // end if
						else {
							$form .= htmlspecialchars($fields_labels_ar[$i]["prefix_field"]).$default_value_field;
						} // end else
						
					} // end if
					
					$form .= "</textarea></td>"; // add the second coloumn to the form
					//$form .= "<script language=\"javascript1.2\">editor_generate('".$field_name_temp."');</script>";
					break;
				/*
				case "password":
					$form .= "<td class=\"td_input_form\">".$select_type_select."<input type=\"password\" name=\"".$field_name_temp."\" size=\"".$fields_labels_ar[$i]["width_field"]."\" maxlength=\"".$fields_labels_ar[$i]["maxlength_field"]."\"";
					if ($form_type == "update" or $form_type == "ext_update"){
						$form .= " value=\"".htmlspecialchars($details_row[$field_name_temp])."\"";
					} // end if
					
					$form .= "></td>"; // add the second coloumn to the form
					break;
					*/
				case "date":
					//$operator_select = "";
					switch($form_type){
						case "update":
							if ($show_edit_form_after_error === 1) {
								if (isset($_POST_2[$field_name_temp.$year_field_suffix]) && isset($_POST_2[$field_name_temp.$month_field_suffix]) && isset($_POST_2[$field_name_temp.$day_field_suffix])) {

									// get $day, $mont and $year posted
									split_date($_POST_2[$field_name_temp.$year_field_suffix].'-'.$_POST_2[$field_name_temp.$month_field_suffix].'-'.$_POST_2[$field_name_temp.$day_field_suffix], $day, $month, $year);
								} // end if
								elseif(isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1'){
									$day = '01';
									$month = '01';
									$year = $start_year;
								}
							} // end if
							else {
								if (is_null($details_row[$field_name_temp])) {
									$day = '01';
									$month = '01';
									$year = $start_year;
								} // end if
								else {
									split_date($details_row[$field_name_temp], $day, $month, $year);
								} // end else
							} // end else
							
							
							$date_select = build_date_select($field_name_temp, $day, $month, $year, 1);
							
							if ($date_select === false){
								$build_form_error['type'] = 'build_date_select';
								$build_form_error['value'] = $year.'-'.$month.'-'.$day;
								
								return $build_form_error;
							}
							break;
						case "insert":
							if ($show_insert_form_after_error === 1) {
								if (isset($_POST_2[$field_name_temp.$year_field_suffix]) && isset($_POST_2[$field_name_temp.$month_field_suffix]) && isset($_POST_2[$field_name_temp.$day_field_suffix])) {

									// get $day, $mont and $year posted
									split_date($_POST_2[$field_name_temp.$year_field_suffix].'-'.$_POST_2[$field_name_temp.$month_field_suffix].'-'.$_POST_2[$field_name_temp.$day_field_suffix], $day, $month, $year);

									$date_select = build_date_select($field_name_temp, $day, $month, $year);
								} // end if
							} // end if
							else {
								$date_select = build_date_select($field_name_temp,"","","");
							} // end else
							break;
						case "search":
							//$operator_select = build_date_select_type_select($field_name_temp."_select_type");
							$date_select = build_date_select($field_name_temp,"","","");
							break;
					} // end switch
					$form .= "<td class=\"td_input_form\">".$select_type_date_select."</td>".$date_select; // add the second coloumn to the form
					break;
				case "date_time":
					//$operator_select = "";
					switch($form_type){
						case "update":
							if ($show_edit_form_after_error === 1) {
								if (isset($_POST_2[$field_name_temp.$year_field_suffix]) && isset($_POST_2[$field_name_temp.$month_field_suffix]) && isset($_POST_2[$field_name_temp.$day_field_suffix]) && isset($_POST_2[$field_name_temp.$hours_field_suffix])  && isset($_POST_2[$field_name_temp.$minutes_field_suffix])  && isset($_POST_2[$field_name_temp.$seconds_field_suffix]) ) {

									// get $day, $mont and $year posted
									split_date( $_POST_2[$field_name_temp.$year_field_suffix].'-'.$_POST_2[$field_name_temp.$month_field_suffix].'-'.$_POST_2[$field_name_temp.$day_field_suffix], $day, $month, $year);
									
									$hours = $_POST_2[$field_name_temp.$hours_field_suffix];
									$minutes = $_POST_2[$field_name_temp.$minutes_field_suffix];
									$seconds = $_POST_2[$field_name_temp.$secondss_field_suffix];
								} // end if
								elseif(isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1'){
									$day = '01';
									$month = '01';
									$year = $start_year;
									$hours = '00';
									$minutes = '00';
									$seconds = '00';
								}
							} // end if
							else {
								if (is_null($details_row[$field_name_temp])) {
									$day = '01';
									$month = '01';
									$year = $start_year;
									$hours = '00';
									$minutes = '00';
									$seconds = '00';
								} // end if
								else {
									split_date_time($details_row[$field_name_temp], $day, $month, $year, $hours, $minutes, $seconds);
								} // end else
							} // end else
							
							
							$date_time_select = build_date_time_select($field_name_temp, $day, $month, $year, $hours, $minutes, $seconds, 1);
							
							if ($date_time_select === false){
								$build_form_error['type'] = 'build_date_select';
								$build_form_error['value'] = $year.'-'.$month.'-'.$day;
								
								return $build_form_error;
							}
							break;
						case "insert":
							if ($show_insert_form_after_error === 1) {
								if (isset($_POST_2[$field_name_temp.$year_field_suffix]) && isset($_POST_2[$field_name_temp.$month_field_suffix]) && isset($_POST_2[$field_name_temp.$day_field_suffix])  && isset($_POST_2[$field_name_temp.$hours_field_suffix])  && isset($_POST_2[$field_name_temp.$minutes_field_suffix])  && isset($_POST_2[$field_name_temp.$seconds_field_suffix]) ) {

									// get $day, $mont and $year posted
									split_date($_POST_2[$field_name_temp.$year_field_suffix].'-'.$_POST_2[$field_name_temp.$month_field_suffix].'-'.$_POST_2[$field_name_temp.$day_field_suffix], $day, $month, $year);
									
									$hours = $_POST_2[$field_name_temp.$hours_field_suffix];
									$minutes = $_POST_2[$field_name_temp.$minutes_field_suffix];
									$seconds = $_POST_2[$field_name_temp.$secondss_field_suffix];

									$date_time_select = build_date_time_select($field_name_temp, $day, $month, $year, $hours, $minutes, $seconds);
								} // end if
							} // end if
							else {
								$date_time_select = build_date_time_select($field_name_temp,"","","","","","");
							} // end else
							break;
						case "search":
							//$operator_select = build_date_select_type_select($field_name_temp."_select_type");
							$date_time_select = build_date_time_select($field_name_temp,"","","","","","");
							break;
					} // end switch
					$form .= "<td class=\"td_input_form\">".$select_type_date_select."</td>".$date_time_select; // add the second coloumn to the form
					break;
				case "insert_date":
				case "update_date":
					//$operator_select = "";
					$date_select = "";
					switch($form_type){
						case "search":
							//$operator_select = build_date_select_type_select($field_name_temp."_select_type");
							$date_select = build_date_select($field_name_temp,"","","");
							break;
					} // end switch
					$form .= "<td class=\"td_input_form\">".$select_type_date_select."</td>".$date_select."</td>"; // add the second coloumn to the form
					break;
				case "select_multiple_menu":
				case "select_multiple_checkbox":

					// first part, retreive value from the option specified (select_options_field)

					$form .= "<td class=\"td_input_form\">"; // first part of the second coloumn of the form

					if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
						$form .= "<select name=\"".$field_name_temp."[]\" size=".$size_multiple_select." multiple>";
					} // end if

					if ( $fields_labels_ar[$i]["select_options_field"] != "") {
						$options_labels_temp = substr_custom($fields_labels_ar[$i]["select_options_field"], 1, -1); // delete the first and the last separator
					
						$select_labels_ar = explode($fields_labels_ar[$i]["separator_field"],$options_labels_temp);

						$select_labels_ar_number = count($select_labels_ar);

						for ($j=0; $j<$select_labels_ar_number; $j++){
							if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
								$form .= "<option value=\"".htmlspecialchars($select_labels_ar[$j])."\"";
							} // end if
							elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
								$form .= "<input type=\"checkbox\" name=\"".$field_name_temp."[]\" value=\"".htmlspecialchars($select_labels_ar[$j])."\"";
							} // end elseif

							if ($form_type == "update" or $form_type == "ext_update"){
								$options_values_temp = substr_custom($details_row[$field_name_temp], 1, -1); // delete the first and the last separator
								
								$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$options_values_temp);

								if ( in_array($select_labels_ar[$j],$select_values_ar )) {
									if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
										$form .= " selected";
									} // end if
									elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
										$form .= " checked";
									} // end elseif
								}

								/*
								$found_flag = 0;
								$z = 0;
								$count_temp = count($select_values_ar)
								
								while ($z < $count_temp and $found_flag == 0){
									if ($select_labels_ar[$j] == $select_values_ar[$z]){
										if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
											$form .= " selected";
										} // end if
										elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
											$form .= " checked";
										} // end elseif
										$found_flag = 1;
									} // end if
									$z++;
								} // end while
								*/
							} // end if
							if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
								$form .= "> ".$select_labels_ar[$j]."</option>";
							} // end if
							elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
								$form .= "> ".$select_labels_ar[$j]."<br>";
							} // end elseif
						} // end for - central part of the form row
					} // end if

					// second part, retreive value from the foreign key

					if ($fields_labels_ar[$i]["foreign_key_field"] != ""){
						if (get_num_rows_db($res_primary_key) > 0){
							$fields_number = num_fields_db($res_primary_key);
							while ($primary_key_row = fetch_row_db($res_primary_key)){
								
								$primary_key_value = "";
								for ($z=0; $z<$fields_number; $z++){
									$primary_key_value .= $primary_key_row[$z];
									$primary_key_value .= " - ";
								} // end for
								$primary_key_value = substr_custom($primary_key_value, 0, -3); // delete the last " - "
								
								if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
									$form .= "<option value=\"".htmlspecialchars($primary_key_value)."\"";
								} // end if
								elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
									$form .= "<input type=\"checkbox\" name=\"".$field_name_temp."[]\" value=\"".htmlspecialchars($primary_key_value)."\"";
								} // end elseif

								if ($form_type == "update" or $form_type == "ext_update"){
									$options_values_temp = substr_custom($details_row[$field_name_temp], 1, -1); // delete the first and the last separator
									$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$options_values_temp);

									if ( in_array($primary_key_value, $select_values_ar)) {
										if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
											$form .= " selected";
										} // end if
										elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
											$form .= " checked";
										} // end elseif
									}

									/*
									while ($z<$count_temp and $found_flag == 0){
										if ($primary_key_value == $select_values_ar[$z]){
											if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
												$form .= " selected";
											} // end if
											elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
												$form .= " checked";
											} // end elseif
											$found_flag = 1;
										} // end if
										$z++;
									} // end while
									*/

								} // end if
								if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
									$form .= "> ".$primary_key_value."</option>";
								} // end if
								elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
									$form .= "> ".$primary_key_value."<br>"; // second part of the form row
								} // end elseif
							} // end while
						} // end if
					} // end if ($fields_labels_ar[$i]["foreign_key_field"] != "")

					if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
						$form .= "</select>";
					} // end if

					$form .= "</td>"; // last part of the second coloumn of the form
					break;

				case "select_single":
					$form .= "<td class=\"td_input_form\">".$select_type_select."</td><td class=\"td_input_form\"><div class=\"select_element\">
<select name=\"".$field_name_temp."\" onchange=\"javascript:show_hide_text_other();\">"; // first part of the second coloumn of the form

					$form .= "<option value=\"\"></option>"; // first blank option
					
					if ($fields_labels_ar[$i]["primary_key_field_field"] == ""){


						$field_temp = substr_custom($fields_labels_ar[$i]["select_options_field"], 1, -1); // delete the first and the last separator
	
						if (trim($field_temp) !== '') {
							$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$field_temp);
	
							$count_temp = count($select_values_ar);
							for ($j=0; $j<$count_temp; $j++){
								$form .= "<option value=\"".htmlspecialchars($select_values_ar[$j])."\"";
	
								if ($form_type === 'update' or $form_type === 'ext_update') {
									if ($show_edit_form_after_error === 1) {
										if (isset($_POST_2[$field_name_temp]) && $select_values_ar[$j] == unescape($_POST_2[$field_name_temp])) {
											$form .= " selected";
										} // end if
									} // end if
									else {
										if ($select_values_ar[$j] == $details_row[$field_name_temp]) {
											$form .= " selected";
										} // end if
									} // end else
								} // end if
	
								elseif ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp]) && $select_values_ar[$j] == unescape($_POST_2[$field_name_temp])){
									$form .= " selected";
								} // end if
								
								$form .= ">".htmlspecialchars($select_values_ar[$j])."</option>"; // second part of the form row
							} // end for
						} // end if
					}

					if ($fields_labels_ar[$i]["primary_key_field_field"] != ""){
						if (get_num_rows_db($sql_res_primary_key_back, 1) > 0){
							$fields_number = num_fields_db($res_primary_key);
							while ($primary_key_row = fetch_row_db($res_primary_key)){
								
								$primary_key_value = $primary_key_row[0];
								$linked_fields_value = "";
								for ($z=1; $z<$fields_number; $z++){
									$linked_fields_value .= $primary_key_row[$z];
									$linked_fields_value .= " - ";
								} // end for
								$linked_fields_value = substr_custom($linked_fields_value, 0, -3); // delete the last " - 
								
								$form .= "<option value=\"".htmlspecialchars($primary_key_value)."\"";

								if ($form_type === 'update' or $form_type === 'ext_update') {
									if ($show_edit_form_after_error === 1) {
										if (isset($_POST_2[$field_name_temp]) && $primary_key_value == unescape($_POST_2[$field_name_temp])) {
											$form .= " selected";
										} // end if
									} // end if
									else {
										if ($primary_key_value == $details_row[$field_name_temp]) {
											$form .= " selected";
										} // end if
									} // end else
								} // end if

								if ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp]) && $primary_key_value == unescape($_POST_2[$field_name_temp])){
									$form .= " selected";
								} // end if
								elseif ($form_type === 'insert' && $set_field_default_value === 1 && $field_name_temp === $default_value_field_name && $primary_key_value == $default_value){
									$form .= " selected";
								} // end elseif
								
								$form .= ">".htmlspecialchars($linked_fields_value)."</option>"; // second part of the form row
							} // end while
						} // end if
					} // end if ($fields_labels_ar[$i]["foreign_key_field"] != "")
					
					if ($fields_labels_ar[$i]["other_choices_field"] == "1" and ($form_type == "insert" or $form_type == "update")){
						$form .= "<option value=\"......\"";
						if ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp]) && $_POST_2[$field_name_temp] === '......'){
							$form .= " selected";
						} // end if
						if ($form_type === 'update' && $show_edit_form_after_error === 1 && isset($_POST_2[$field_name_temp]) && $_POST_2[$field_name_temp] === '......') {
							$form .= " selected";
						} // end if
						$form .= ">".$normal_messages_ar["other...."]."</option>"; // last option with "other...."
					} // end if

					$form .= "</select></div>";

					if ($fields_labels_ar[$i]["other_choices_field"] == "1" and ($form_type == "insert" or $form_type == "update")){
						
						$form .= '<div id="other_textbox_'.$field_name_temp.'" style="visibility:hidden">';
						
						$form .= " <input type=\"text\" name=\"".$field_name_temp."_other____"."\" maxlength=\"".$fields_labels_ar[$i]["maxlength_field"]."\"";

						if ($fields_labels_ar[$i]["width_field"] != ""){
							$form .= " size=\"".$fields_labels_ar[$i]["width_field"]."\"";
						} // end if

						if ($form_type == "insert" && $show_insert_form_after_error === 1){
							if (isset($_POST_2[$field_name_temp."_other____"])) {
								if (isset($_POST_2[$field_name_temp]) && $_POST_2[$field_name_temp] === '......'){
									$form .= ' value="'.htmlspecialchars(unescape($_POST_2[$field_name_temp."_other____"])).'"';
								} // end if
							} // end if
						} // end if

						if ($form_type == "update" && $show_edit_form_after_error === 1){
							if (isset($_POST_2[$field_name_temp."_other____"])) {
								if (isset($_POST_2[$field_name_temp]) && $_POST_2[$field_name_temp] === '......'){
									$form .= ' value="'.htmlspecialchars(unescape($_POST_2[$field_name_temp."_other____"])).'"';
								} // end if
							} // end if
						} // end if

						$form .= ">"; // text field for other....
					} // end if
					
					$form .= "</div>";
					
					$form .= "</td>"; // last part of the second coloumn of the form
					break;
			} // end switch
			/////////////////////////////////////////	
			// end build the third coloumn (input field)

			if ($form_type == "insert" or $form_type == "update" or $form_type == "ext_update"){
				$form .= "<td class=\"td_hint_form\">&nbsp;&nbsp;".$fields_labels_ar[$i]["hint_insert_field"]."</td>"; // display the insert hint if it's the insert form
			} // end if
			$form .= "</tr></table></td>";
			
            if ($fields_labels_ar[$i][$new_line_field] === '1'){
                $open_new_line = 1;
               
                for ($j=0;$j<($max_number_fields_row-$fields_in_row_counter);$j++){
                	if (($form_type === 'insert' || $form_type === 'update') && ($form_has_not_requied_field && $null_checkbox === 1 || $form_type === 'update' && $record_has_null_fields === 1)) {
                    	$form .= '<td align="right" valign="top"><table><tr><td class="td_label_form"></td></tr></table></td><td align="right" valign="top"><table><tr><td class="td_null_checkbox_form"></td></tr></table></td><td><table border="0"><tr><td class="td_input_form"></td><td class="td_hint_form"></td></tr></table></td>';
					}
					else{
						$form .= '<td align="right" valign="top"><table><tr><td class="td_label_form"></td></tr></table></td><td><table border="0"><tr><td class="td_input_form"></td><td class="td_hint_form"></td></tr></table></td>';
					}
                    
                }
                $form .= "</tr>";
                 $fields_in_row_counter = 0;
                
            }
            else{
                $open_new_line = 0;
            }
		} // end if ($fields_labels_ar[$i]["$field_to_ceck"] == "1")
	} // enf for loop for each field in the label array
	
	$form .= "<tr><td class=\"tr_button_form\" colspan=\"".$number_cols*$max_number_fields_row."\"><input type=\"submit\" class=\"button_form\" value=\"".$submit_buttons_ar[$form_type]." >>\">";
	
	if ($is_items_table === 1){
		
		$form .= ' <input type="button" class="button_form" value="'.$normal_messages_ar['back_to_the_main_table'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?table_name='.urlencode($master_table_name).'&function='.$master_table_function.'&where_field='.urlencode($master_table_where_field).'&where_value='.urlencode(unescape($master_table_where_value)).'\'">';
		
	}
	
	else if ($form_type === 'update' || $form_type === 'details'){
		$form .= ' <input type="button" class="button_form" value="'.$submit_buttons_ar['go_back'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?function=search&table_name='.urlencode($table_name).'\'">';
	}
	
	$form.= "</td></tr></table></form>";
	return $form;
} // end build_form function




function build_select_type_select($field_name, $select_type, $first_option_blank, $filters_ar = '')
// goal: build a select with the select type of the field (e.g. is_equal, contains....)
// input: $field_name, $select_type (e.g. is_equal/contains), $first_option_blank(0|1)
// output: $select_type_select
// global: $normal_messages_ar, the array containing the normal messages
{
	global $normal_messages_ar, $select_type_select_suffix, $year_field_suffix, $month_field_suffix, $day_field_suffix, $hours_field_suffix, $minutes_field_suffix, $seconds_field_suffix;

	$select_type_select = "";

	$operators_ar = explode("/",$select_type);

	if (count($operators_ar) > 1 || $first_option_blank === 1){ // more than on operator, need a select
		$select_type_select .= "<div class=\"select_element select_element_select_type\"><select onchange=\"javascript:enable_disable_input_box_search_form('".$field_name."', '".$select_type_select_suffix."', '".$year_field_suffix."', '".$month_field_suffix."', '".$day_field_suffix."', '".$hours_field_suffix."', '".$minutes_field_suffix."', '".$seconds_field_suffix."')\" name=\"".$field_name.$select_type_select_suffix."\">";
		$count_temp = count($operators_ar);
		if ($first_option_blank === 1) {
			$select_type_select .= "<option value=\"\"></option>";
		} // end if
		for ($i=0; $i<$count_temp; $i++){
			$select_type_select .= "<option value=\"".$operators_ar[$i]."\"";
			if (isset($filters_ar['select_types'][$field_name]) && $filters_ar['select_types'][$field_name] === $operators_ar[$i]){
				$select_type_select .= " selected";
			}
			$select_type_select .= ">".$normal_messages_ar[$operators_ar[$i]]."</option>";
		} // end for
		$select_type_select .= "</select></div> ";
	} // end if
	else{ // just an hidden
		$select_type_select .= "<input type=\"hidden\" name=\"".$field_name.$select_type_select_suffix."\" value=\"".$operators_ar[0]."\"> ";
	}

	return $select_type_select;
} // end function build_select_type_select

function check_required_fields($_POST_2, $_FILES_2, $fields_labels_ar, $function)
// goal: check if the user has filled all the required fields
// input: all the fields values ($_POST_2), $_FILES_2 (for uploaded files) and the array containing infos about fields ($fields_labels_ar), $function
// output: $check, set to 1 if the check is ok, otherwise 0
{
	global $null_checkbox_prefix;
	$i =0;
	$check = 1;
	$count_temp = count($fields_labels_ar);
	while ($i<$count_temp and $check == 1){
	
	switch($function){
		case 'insert':
			$field_to_check = $fields_labels_ar[$i]["present_insert_form_field"];
			break;
		case 'update':
			$field_to_check = $fields_labels_ar[$i]["present_edit_form_field"];
			break;
	}
	
		if ($fields_labels_ar[$i]["type_field"] === 'select_single' && $field_to_check == "1"){
			$field_name_temp = $fields_labels_ar[$i]["name_field"];
			if ($fields_labels_ar[$i]["other_choices_field"] == "1" and (isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1') === false and $_POST_2[$field_name_temp] == "......"){
				$field_name_other_temp = $field_name_temp."_other____";
				if ($_POST_2["$field_name_other_temp"] == ""){
					$check = 0;
				} // end if
			} // end if
		}
		if ($fields_labels_ar[$i]["required_field"] == "1" and $field_to_check == "1"){
			$field_name_temp = $fields_labels_ar[$i]["name_field"];
			
			if (isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1') { // NULL checkbox selected
				$check = 0;
			} // end if
			else {
				switch($fields_labels_ar[$i]["type_field"]){
					case "date":
					case "date_time":
						break; // date is always filled
					case "select_single":
						if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST_2[$field_name_temp] == "......"){
							$field_name_other_temp = $field_name_temp."_other____";
							if ($_POST_2["$field_name_other_temp"] == ""){
								$check = 0;
							} // end if
						} // end if
						else{
							if ($_POST_2[$field_name_temp] == ""){
								$check = 0;
							} // end if
						} // end else
						break;
					case "generic_file":
					case "image_file":
						// check passed only if a new file is uploaded or a file is already present and the user don't want to delete it
						if ($function === 'update') {
							if ((!isset($_FILES_2[$field_name_temp]['name']) || $_FILES_2[$field_name_temp]['name'] === '') && ($_POST_2[$field_name_temp.'_file_available'] == '0' || isset($_POST_2[$field_name_temp.'_file_uploaded_delete']))){
								$check = 0;
							} // end if
						} // end if
						else { // function is 'insert'
							if (!isset($_FILES_2[$field_name_temp]['name']) || $_FILES_2[$field_name_temp]['name'] === ''){
								$check = 0;
							} // end if
						} // end else
						
						break;
					default:
						if (unescape($_POST_2[$field_name_temp]) == $fields_labels_ar[$i]["prefix_field"]){
							$_POST_2[$field_name_temp] = "";
						} // end if
						if ($_POST_2[$field_name_temp] == ""){
							$check = 0;
						} // end if
						break;
				} // end switch
			} // end else
		} // end if
		$i++;
	} // end while
	return $check;
} // end function check_required_fields

function check_length_fields($_POST_2, $fields_labels_ar)
// goal: check if the text, password, textarea, rich_editor, select_single, select_multiple_checkbox, select_multiple_menu fields contains too much text
// input: all the fields values ($_POST_2) and the array containing infos about fields ($fields_labels_ar)
// output: $check, set to 1 if the check is ok, otherwise 0
{
	$i =0;
	$check = 1;
	$count_temp = count($fields_labels_ar);
	
	while ($i<$count_temp and $check == 1){
	
		$field_name_temp = $fields_labels_ar[$i]["name_field"];
		// I use isset for select_multiple because could be unset
		if ($fields_labels_ar[$i]["maxlength_field"] != "" && isset($_POST_2[$field_name_temp])){
		
			switch($fields_labels_ar[$i]["type_field"]){
				case "text":
				//case "password":
				case "textarea":
				case "rich_editor":
					if (strlen_custom(unescape($_POST_2[$field_name_temp])) > $fields_labels_ar[$i]["maxlength_field"]){
						$check = 0;
					} // end if
					break;
				case "select_multiple_checkbox":
				case "select_multiple_menu":
					$count_temp_2 = count($_POST_2[$field_name_temp]);
					$value_temp = "";
					for ($j=0; $j<$count_temp_2; $j++) {
						$value_temp .= $fields_labels_ar[$i]["separator_field"].$_POST_2[$field_name_temp][$j];
					}
					$value_temp .= $fields_labels_ar[$i]["separator_field"]; // add the last separator
					if (strlen_custom($value_temp) > $fields_labels_ar[$i]["maxlength_field"]){
						$check = 0;
					} // end if

					break;
				case "select_single":
					if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST_2[$field_name_temp] == "......"){
					
						$field_name_other_temp = $field_name_temp."_other____";
						if (strlen_custom(unescape($_POST_2[$field_name_other_temp])) > $fields_labels_ar[$i]["maxlength_field"]){
							$check = 0;
						} // end if
					} // end if
					else{
					
						if (strlen_custom(unescape($_POST_2[$field_name_temp])) > $fields_labels_ar[$i]["maxlength_field"]){
							$check = 0;
						} // end if
					} // end else
					break;
			} // end switch
		} // end if
		$i++;
	} // end while
	
	return $check;
} // end function check_length_fields

function write_temp_uploaded_files($_FILES_2, $fields_labels_ar)
// goal: write an upload file with a temporary name, checking the size and the file extension, the correct name will be assigned later in insert_record or update_record
// input: all the uploaded files ($_FILES_2) and the array containing infos about fields ($fields_labels_ar)
// output: $check, set to 1 everyhings is fine (also the write procedure), otherwise 0
{
	global $upload_directory, $max_upload_file_size, $allowed_file_exts_ar, $allowed_all_files;
	$uploaded_file_names_ar = array();
	$i =0;
	$check = 1;
	$count_temp = count($fields_labels_ar);
	while ($i<$count_temp and $check == 1){
		switch($fields_labels_ar[$i]["type_field"]){
			case "generic_file":
			case "image_file":
				$field_name_temp = $fields_labels_ar[$i]["name_field"];

				if (isset($_FILES_2[$field_name_temp]['name']) && $_FILES_2[$field_name_temp]['name'] != ''){ // not set if NULL checkbox is set
					
					$file_name_temp = $_FILES_2[$field_name_temp]['tmp_name'];
					$file_name = unescape($_FILES_2[$field_name_temp]['name']);
					$file_name = get_valid_name_uploaded_file($file_name, 1);

					$file_size = $_FILES_2[$field_name_temp]['size'];

					$file_suffix_temp = strrchr($file_name, ".");
					$file_suffix_temp = substr_custom($file_suffix_temp, 1); // remove the first dot
					
					if ( !in_array(strtolower_custom($file_suffix_temp), $allowed_file_exts_ar) && $allowed_all_files != 1){
						$check = 0;
					}
					else {
						if ($file_size > $max_upload_file_size) {
							$check = 0;
						}
						else { //go ahead and copy the file into the upload directory
							if (!(move_uploaded_file($file_name_temp, $upload_directory.'dadabik_tmp_file_'.$file_name))) {
								$check = 0;
							}
						} // end else
					} // end else
				} // end if
				break;
		} // end switch
		$i++;
	} // end while
	return $check;
} // end function write_temp_uploaded_files

function check_fields_types($_POST_2, $fields_labels_ar, &$content_error_type, $function)
// goal: check if the user has well filled the form, according to the type of the field (e.g. no numbers in alphabetic fields, emails and urls correct)
// input: all the fields values ($_POST_2) and the array containing infos about fields ($fields_labels_ar), &$content_error_type, a string that change according to the error made (alphabetic, numeric, email, phone, url....)
// output: $check, set to 1 if the check is ok, otherwise 0
{
	global $year_field_suffix, $month_field_suffix, $day_field_suffix, $null_checkbox_prefix;

	$i =0;
	$check = 1;
	$count_temp = count($fields_labels_ar);
	
	while ($i<$count_temp and $check == 1){
		$field_name_temp = $fields_labels_ar[$i]["name_field"];
		
		switch($function){
		case 'insert':
			$field_to_check = $fields_labels_ar[$i]["present_insert_form_field"];
			break;
		case 'update':
			$field_to_check = $fields_labels_ar[$i]["present_edit_form_field"];
			break;
		}
		
		if (isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1') { // NULL checkbox selected
			$check = 1;
		} // end if
		elseif (isset($_POST_2[$field_name_temp])){ // otherwise it has not been filled
			if (unescape($_POST_2[$field_name_temp]) == $fields_labels_ar[$i]["prefix_field"]){
				$_POST_2[$field_name_temp] = "";
			} // end if
			if ($fields_labels_ar[$i]["type_field"] == "select_single" && $fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST_2[$field_name_temp] == "......"){ // other field filled
				$field_name_temp = $field_name_temp."_other____";
			} // end if
			if (($fields_labels_ar[$i]["type_field"] == "text" || $fields_labels_ar[$i]["type_field"] == "textarea") and $field_to_check == "1" and $_POST_2[$field_name_temp] != ""){
			
				// enterprise
				// pro
				if ($fields_labels_ar[$i]["custom_validation_function_field"] !== ''){
					
					$parameters_ar = array();
					
					foreach ($_POST_2 as $key => $value)
					{
						$parameters_ar[$key] = unescape($value);
					}
					
					
					$check = call_user_func($fields_labels_ar[$i]["custom_validation_function_field"], $parameters_ar);
					
					if ($check === false){
						$check = 0;
						$content_error_type = $fields_labels_ar[$i]["custom_validation_function_field"];
					}
					
				}
				else{
				// end enterprise
				// end pro
				
					switch ($fields_labels_ar[$i]["content_field"]){
						
						
						case "alphabetic":
							if ( $fields_labels_ar[$i]["type_field"] == "select_multiple_menu" || $fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox") {
								$count_temp_2 = count($_POST_2[$field_name_temp]);
								$j=0;
								while ($j<$count_temp_2 && $check == 1) {
									if (contains_numerics($_POST_2[$field_name_temp][$j])){
										$check = 0;
										$content_error_type = $fields_labels_ar[$i]["content_field"];
									} // end if
									$j++;
								}
							}
							else{
								if (contains_numerics(unescape($_POST_2[$field_name_temp]))){
									$check = 0;
									$content_error_type = $fields_labels_ar[$i]["content_field"];
								} // end if
							}
							break;
						case "numeric":
							if ( $fields_labels_ar[$i]["type_field"] == "select_multiple_menu" || $fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox") {
								$count_temp_2 = count($_POST_2[$field_name_temp]);
								$j=0;
								while ($j<$count_temp_2 && $check == 1) {
									if (!is_numeric($_POST_2[$field_name_temp][$j])){
										$check = 0;
										$content_error_type = $fields_labels_ar[$i]["content_field"];
									} // end if
									$j++;
								}
							}
							else{
								if (!is_numeric(unescape($_POST_2[$field_name_temp]))){
									$check = 0;
									$content_error_type = $fields_labels_ar[$i]["content_field"];
								} // end if
							}
							break;
						case "phone":
							if ( $fields_labels_ar[$i]["type_field"] == "select_multiple_menu" || $fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox") {
								$count_temp_2 = count($_POST_2[$field_name_temp]);
								$j=0;
								while ($j<$count_temp_2 && $check == 1) {
									if (!is_valid_phone($_POST_2[$field_name_temp][$j])){
										$check = 0;
										$content_error_type = $fields_labels_ar[$i]["content_field"];
									} // end if
									$j++;
								}
							}
							else{
								if (!is_valid_phone(unescape($_POST_2[$field_name_temp]))){
									$check = 0;
									$content_error_type = $fields_labels_ar[$i]["content_field"];
								} // end if
							}
							break;
						case "timestamp":
							if ( $fields_labels_ar[$i]["type_field"] == "select_multiple_menu" || $fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox") {
								$count_temp_2 = count($_POST_2[$field_name_temp]);
								$j=0;
								while ($j<$count_temp_2 && $check == 1) {
									if (!is_numeric($_POST_2[$field_name_temp][$j]) || (int)$_POST_2[$field_name_temp][$j] != $_POST_2[$field_name_temp][$j] ){
										$check = 0;
										$content_error_type = $fields_labels_ar[$i]["content_field"];
									} // end if
									$j++;
								}
							}
							else{
								if (!is_numeric(unescape($_POST_2[$field_name_temp])) || (int)(unescape($_POST_2[$field_name_temp])) != unescape($_POST_2[$field_name_temp])){
									$check = 0;
									$content_error_type = $fields_labels_ar[$i]["content_field"];
								} // end if
							}
							break;
						case "email":
							if ( $fields_labels_ar[$i]["type_field"] == "select_multiple_menu" || $fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox") {
								$count_temp_2 = count($_POST_2[$field_name_temp]);
								$j=0;
								while ($j<$count_temp_2 && $check == 1) {
									if (!is_valid_email($_POST_2[$field_name_temp][$j])){
										$check = 0;
										$content_error_type = $fields_labels_ar[$i]["content_field"];
									} // end if
									$j++;
								}
							}
							else{
								if (!is_valid_email(unescape($_POST_2[$field_name_temp]))){
									$check = 0;
									$content_error_type = $fields_labels_ar[$i]["content_field"];
								} // end if
							}
							break;
						case "url":
							if ( $fields_labels_ar[$i]["type_field"] == "select_multiple_menu" || $fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox") {
								$count_temp_2 = count($_POST_2[$field_name_temp]);
								$j=0;
								while ($j<$count_temp_2 && $check == 1) {
									if (!is_valid_url($_POST_2[$field_name_temp][$j])){
										$check = 0;
										$content_error_type = $fields_labels_ar[$i]["content_field"];
									} // end if
									$j++;
								}
							}
							else{
								if (!is_valid_url(unescape($_POST_2[$field_name_temp]))){
									$check = 0;
									$content_error_type = $fields_labels_ar[$i]["content_field"];
								} // end if
							}
							break;
					} // end switch
				
				// enterprise
				// pro
				}
				// end enterprise
				// end pro
				
				
				
			} // end if
		} // end elseif
		elseif( $fields_labels_ar[$i]["type_field"] == "date" || $fields_labels_ar[$i]["type_field"] == "date_time"){
			$day = $_POST_2[$field_name_temp.$day_field_suffix];
			$month = $_POST_2[$field_name_temp.$month_field_suffix];
			$year = $_POST_2[$field_name_temp.$year_field_suffix];
			if (!is_numeric($day) || !is_numeric($month) || !is_numeric($year) || !checkdate( $month, $day, $year )){
				$check = 0;
				$content_error_type = "date";
			} // end if
		}
		$i++;
	} // end while
	
	return $check;
} // end function check_fields_types

function build_select_duplicated_query($_POST_2, $table_name, $fields_labels_ar, &$string1_similar_ar, &$string2_similar_ar)
// goal: build the select query to select the record that can be similar to the record inserted
// input: all the field values ($_POST_2), $table_name, $fields_labels_ar, &$string1_similar_ar, &$string2_similar_ar (the two array that will contain the similar string found)
// output: $sql, the sql query
// global $percentage_similarity, the percentage after that two strings are considered similar, $number_duplicated_records, the maximum number of records to be displayed as duplicated
{
	global $percentage_similarity, $number_duplicated_records, $conn, $quote, $enable_authentication, $enable_browse_authorization, $current_user, $null_checkbox_prefix;
	
	// get the unique key of the table
	$unique_field_name = get_unique_field_db($table_name);

	if ($unique_field_name != "" && $unique_field_name != NULL){ // a unique key exists, ok, otherwise I'm not able to select the similar record, which field should I use to indicate it?

		/*
		reset ($_POST_2);
		while (list($key, $value) = each ($_POST_2)){
			$$key = $value;
		} // end while
		*/

		$sql = "";
		$sql_select_all = "";
		$sql_select_all = "SELECT ".$quote.$unique_field_name.$quote.", "; // this is used to select the records to check similiarity
		//$select = "SELECT * FROM ".$quote.$table_name.$quote."";
		$select = build_select_part($fields_labels_ar, $table_name);
		$where_clause = "";	

		// build the sql_select_all clause
		$j = 0;
		// build the $fields_to_check_ar array, containing the field to check for similiarity
		$fields_to_check_ar = array();
		$count_temp = count($fields_labels_ar);
		for ($i=0; $i<$count_temp; $i++){
			if ($fields_labels_ar[$i]["check_duplicated_insert_field"] == "1"){
				//if (${$fields_labels_ar[$i]["name_field"]} != ""){
					$fields_to_check_ar[$j] = $fields_labels_ar[$i]["name_field"]; // I put in the array only if the field is non empty, otherwise I'll check it even if I don't need it
				//} // end if
				$sql_select_all .= $quote.$fields_labels_ar[$i]["name_field"].$quote.", ";
				$j++;
			} // end if
		} // end for
		$sql_select_all = substr_custom ($sql_select_all, 0, -2); // delete the last ", "
		$sql_select_all .= " FROM ".$quote.$table_name.$quote;
		
		// end build the sql_select_all clause

		// at the end of the above procedure I'll have, for example, "select ID, name, email from table" if ID is the unique key, name and email are field to check

		// execute the select query
		$res_contacts = execute_db($sql_select_all, $conn);	

		if (get_num_rows_db($res_contacts) > 0){
			while ($contacts_row = fetch_row_db($res_contacts)){ // *A* for each record in the table
				$count_temp = count($fields_to_check_ar);
				for ($i=0; $i<$count_temp; $i++){ // *B* and for each field the user has inserted
					if (!isset($_POST_2[$null_checkbox_prefix.$fields_to_check_ar[$i]]) || $_POST_2[$null_checkbox_prefix.$fields_to_check_ar[$i]] !== '1') { // NULL checkbox  is not selected
						$z=0;
						$found_similarity =0; // set to 1 when a similarity is found, so that it's possible to exit the loop (if I found that a record is similar it doesn't make sense to procede with other fields of the same record)
					
						// *C* check if the field inserted are similiar to the other fields to be checked in this record (*A*)
						$count_temp_2 = count($fields_to_check_ar);
						while ($z<$count_temp_2 and $found_similarity == 0){
						$string1_temp = unescape($_POST_2[$fields_to_check_ar[$i]]); // the field the user has inserted
							$string2_temp = $contacts_row[$z+1]; // the field of this record (*A*); I start with 1 because 0 is alwais the unique field (e.g. ID, name, email)

							// hack for mssql, an empty varchar ('') is returned as ' ' by the driver, see http://bugs.php.net/bug.php?id=26315
							if ($string2_temp === ' ') {
								$string2_temp = '';
							} // end if
							
							similar_text(strtolower_custom($string1_temp), strtolower_custom($string2_temp), $percentage);
							if ($percentage >= $percentage_similarity){ // the two strings are similar
								//$where_clause .= $quote.$unique_field_name.$quote." = \"".$contacts_row[$unique_field_name]."\" or ";
								$where_clause .= $quote.$unique_field_name.$quote." = '".$contacts_row[$unique_field_name]."' or ";
								$found_similarity = 1;
								$string1_similar_ar[]=$string1_temp;
								$string2_similar_ar[]=$string2_temp;
							} // end if the two strings are similar
							$z++;
						} // end while
					} // end if
				} // end for loop for each field to check
			} // end while loop for each record
		} // end if (get_num_rows_db($res_contacts) > 0)

		$where_clause = substr_custom($where_clause, 0, -4); // delete the last " or "
		if ($where_clause != ""){
			//$sql = $select." where ".$where_clause." limit 0,".$number_duplicated_records;
			$sql = $select." where ".$where_clause;
		} // end if
		else{ // no duplication
			$sql = "";
		} // end else*
	} // end if if ($unique_field_name != "")
	else{ // no unique keys
		$sql = "";
	} // end else
	return $sql;	
} // end function build_select_duplicated_query

function build_insert_duplication_form($_POST_2, $fields_labels_ar, $table_name, $table_internal_name)
// goal: build a tabled form composed by two buttons: "Insert anyway" and "Go back"
// input: all the field values ($_POST_2), $fields_labels_ar, $conn, $table_name, $table_internal_name
// output: $form, the form
// global $submit_buttons_ar, the array containing the caption on submit buttons
{
	global $submit_buttons_ar, $dadabik_main_file, $null_checkbox_prefix, $year_field_suffix, $month_field_suffix, $day_field_suffix, $hours_field_suffix, $seconds_field_suffix, $minutes_field_suffix;

	$form = "";

	$form .= "<table><tr><td>";
	
	$form .= "\n<!--[if !lte IE 9]><!-->\n";
	$form .= "<form class=\"css_form\" action=\"".$dadabik_main_file."?table_name=".urlencode($table_name)."&function=insert&insert_duplication=1\" method=\"post\"><table><tr>\n";
	$form .= "<!--<![endif]-->\n";
	$form .= "<!--[if lte IE 9]>\n";
	$form .= "<form action=\"".$dadabik_main_file."?table_name=".urlencode($table_name)."&function=insert&insert_duplication=1\" method=\"post\"><table><tr>\n";
	$form .= "<![endif]-->\n";

	/*
	// re-post all the fields values
	reset ($_POST_2);
	while (list($key, $value) = each ($_POST_2)){
		$$key = $value;
	} // end while
	*/

	$count_temp = count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++){

		$field_name_temp = $fields_labels_ar[$i]["name_field"];

		if ($fields_labels_ar[$i]["present_insert_form_field"] == "1"){

			if (isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1') { // NULL checkbox selected
				$form .= '<input type="hidden" name="'.$null_checkbox_prefix.$field_name_temp.'" value="1">'; // add the NULL checbox with value 1
			} // end if
			else {
				switch ($fields_labels_ar[$i]["type_field"]){
					case "select_multiple_menu":
					case "select_multiple_checkbox":
						if (isset($_POST_2[$field_name_temp])){
							$count_temp_2 = count($$field_name_temp);
							for ($j=0; $j<$count_temp_2; $j++){
								$form .= "<input type=\"hidden\" name=\"".$field_name_temp."[".$j."]"."\" value=\"".htmlspecialchars(unescape($_POST_2[$field_name_temp][$j]))."\">";// add the field value to the sql statement
							} // end for
						} // end if
						break;
					case "date":
						$year_field = $field_name_temp.$year_field_suffix;
						$month_field = $field_name_temp.$month_field_suffix;
						$day_field = $field_name_temp.$day_field_suffix;

						$form .= "<input type=\"hidden\" name=\"".$year_field."\" value=\"".$_POST_2[$year_field]."\">";
						$form .= "<input type=\"hidden\" name=\"".$month_field."\" value=\"".$_POST_2[$month_field]."\">";
						$form .= "<input type=\"hidden\" name=\"".$day_field."\" value=\"".$_POST_2[$day_field]."\">";
						break;
					case "date_time":
						$year_field = $field_name_temp.$year_field_suffix;
						$month_field = $field_name_temp.$month_field_suffix;
						$day_field = $field_name_temp.$day_field_suffix;
						$hours_field = $field_name_temp.$hours_field_suffix;
						$minutes_field = $field_name_temp.$minutes_field_suffix;
						$seconds_field = $field_name_temp.$seconds_field_suffix;

						$form .= "<input type=\"hidden\" name=\"".$year_field."\" value=\"".$_POST_2[$year_field]."\">";
						$form .= "<input type=\"hidden\" name=\"".$month_field."\" value=\"".$_POST_2[$month_field]."\">";
						$form .= "<input type=\"hidden\" name=\"".$day_field."\" value=\"".$_POST_2[$day_field]."\">";
						
						$form .= "<input type=\"hidden\" name=\"".$hours_field."\" value=\"".$_POST_2[$hours_field]."\">";
						
						$form .= "<input type=\"hidden\" name=\"".$minutes_field."\" value=\"".$_POST_2[$minutes_field]."\">";
						
						$form .= "<input type=\"hidden\" name=\"".$seconds_field."\" value=\"".$_POST_2[$seconds_field]."\">";
						break;
					case "select_single":
						if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST_2[$field_name_temp] == "......"){ // other choice filled
							$field_name_other_temp = $field_name_temp."_other____";
							$form .= "<input type=\"hidden\" name=\"".$field_name_temp."\" value=\"".htmlspecialchars(unescape($_POST_2[$field_name_temp]))."\">";
							$form .= "<input type=\"hidden\" name=\"".$field_name_other_temp."\" value=\"".htmlspecialchars(unescape($_POST_2[$field_name_other_temp]))."\">";
						} // end if
						else{
							$form .= "<input type=\"hidden\" name=\"".$field_name_temp."\" value=\"".htmlspecialchars(unescape($_POST_2[$field_name_temp]))."\">";
						} // end else
						break;
					default: // textual field
						if (unescape($_POST_2[$fields_labels_ar[$i]["name_field"]]) == $fields_labels_ar[$i]["prefix_field"]){ // the field contain just the prefix
							$_POST_2[$fields_labels_ar[$i]["name_field"]] = "";
						} // end if
							
						$form .= "<input type=\"hidden\" name=\"".$field_name_temp."\" value=\"".htmlspecialchars(unescape($_POST_2[$fields_labels_ar[$i]["name_field"]]))."\">";
						break;
				} // end switch
			} // end else
		} // end if
	} // end for
	$form .= "<input type=\"submit\" class=\"button_form\" value=\"".$submit_buttons_ar["insert_anyway"]." >>\"></form>";

	$form .= "</td><td>";

	//$form .= "<form><input type=\"button\" value=\"".$submit_buttons_ar["go_back"]."\" onclick=\"javascript:history.back(-1)\"></form>";

	$form .= "</td></tr></table>";

	return $form;
} // end function build_insert_duplication_form

function build_print_labels_form($name_mailing)
// goal: build an HTML form with a button "Print labels" to print labels
// input: $name_mailing
// ouput: $print_labels_form
// global: $submit_buttons_ar, the array containing the values of the submit buttons
{
	global $submit_buttons_ar;
	$print_labels_form = "";
	$print_labels_form .= "<form action=\"mail.php\" method=\"GET\">";
	$print_labels_form .= "<input type=\"hidden\" name=\"function\" value=\"send\">";
	$print_labels_form .= "<input type=\"hidden\" name=\"type_mailing\" value=\"labels\">";
	$print_labels_form .= "<input type=\"hidden\" name=\"name_mailing\" value=\"".$name_mailing."\">";
	$print_labels_form .= "<input type=\"submit\" value=\"".$submit_buttons_ar["print_labels"]."\">";
	$print_labels_form .= "</form>";

	return $print_labels_form;
} // end function build_print_labels_form

function build_email_form($from_addresses_ar, $mailing_row)
// goal: build a tabled HTML form with field for a new mailing
// input: $from_addresses_ar, the array containing the from addresses, $mailing_row a row, result set of a mailing, when call from edit mailing
// ouput: $form
// global: $submit_buttons_ar, the array containing the values of the submit buttons and $normal_messages_ar, the array containg the normal messages
{
	global $submit_buttons_ar, $normal_messages_ar;
	$form = "";
	$action = 'mail.php';
	if(is_array($mailing_row)) { // come from edit	
		$action .= '?function=update&old_name_mailing='.urlencode($mailing_row['name_mailing']);
	} // end if
	else{
		$action .= '?function=new_create';
	} // end else

	if (get_cfg_var("file_uploads")){
		$form .= "<form action=\"".$action."\" method=\"post\" enctype=\"multipart/form-data\">";
	} // end if
	else{
		$form .= "<form action=\"".$action."\" method=\"post\">";
	} // end else
	$form .= "<table>";
	
	$form .= "<tr><th>".$normal_messages_ar["mailing_name"]."</th><td><input type=\"text\" name=\"name_mailing\"";
	if(isset($mailing_row['name_mailing'])) {
		$form .= ' value="'.htmlspecialchars($mailing_row['name_mailing']).'"';		
	}	
	$form .= ">&nbsp;".$normal_messages_ar["specify_mailing_name"]."</td></tr>";
	
	$form .= "<tr><th>".$normal_messages_ar["mailing_type"]."</th><td>".$normal_messages_ar["e-mail"]."<input type=\"radio\" name=\"type_mailing\" value=\"email\"";
	if(isset($mailing_row['type_mailing']) && $mailing_row['type_mailing'] == 'email') {
		$form .= ' checked';		
	}	
	$form .= ">&nbsp;&nbsp;&nbsp;&nbsp;".$normal_messages_ar["normal_mail"]."<input type=\"radio\" name=\"type_mailing\" value=\"normal_mail\"";
	if(isset($mailing_row['type_mailing']) && $mailing_row['type_mailing'] == 'normal_mail') {
		$form .= ' checked';		
	}	
	$form .= "></td></tr>";
	
	$form .= "<tr><td colspan=\"2\"><i>".$normal_messages_ar["email_specific_fields"]."</i></td></tr>";
	
	$form .= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
	
	$form .= "<tr><th><font color=\"#0000ff\">".$normal_messages_ar["from"]."</font></th><td>";
	$form .= "<select name=\"from_mailing\">";
	for ($i=0; $i<count($from_addresses_ar); $i++){
		$form .= "<option value=\"$i\"";
		if(isset($mailing_row['from_mailing']) && $mailing_row['from_mailing'] == $i){
			$form .= ' selected';
		}		
		$form .= ">\"".$from_addresses_ar[$i][0]."\" &lt;".$from_addresses_ar[$i][1]."&gt;</option>";
	} // end for
	$form .= "</select>";
	$form .= "</td></tr>";

	$form .= "<tr><th><font color=\"#0000ff\">".$normal_messages_ar["subject"]."</th><td><input type=\"text\" name=\"subject_mailing\"";
	if(isset($mailing_row['subject_mailing'])) {
		$form .= ' value="'.htmlspecialchars($mailing_row['subject_mailing']).'"';		
	}	
	$form .= "></font></td></tr>";
	
	$form .= "<tr><th>".$normal_messages_ar["body"]."</th><td><input type=\"text\" name=\"salute_mailing\"";
	if(isset($mailing_row['salute_mailing'])) {
		$form .= ' value="'.htmlspecialchars($mailing_row['salute_mailing']).'"';		
	}
	else{
		$form .= "value=\"".$normal_messages_ar["dear"]."\"";
	}
	
	$form .= ">".$normal_messages_ar["john_smith"]."<br><textarea name=\"body_mailing\" cols=\"30\" rows=\"10\">";
	if(isset($mailing_row['body_mailing'])) {
		$form .= htmlspecialchars($mailing_row['body_mailing']);		
	}	
	$form .= "</textarea></td></tr>";
	
	if (get_cfg_var("file_uploads")){
		$form .= "<tr><th><font color=\"#0000ff\">".$normal_messages_ar["attachment"]."</th><td><input type=\"file\" name=\"attachment_mailing\"></font>";
		if(is_array($mailing_row) && $mailing_row['attachment_name_mailing'] !== '') {
			$form .= ' ('.htmlspecialchars($mailing_row['attachment_name_mailing']).') ';		
		}		
		$form .= "</td></tr>";
	} // end if
	

	
	$form .= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
	$form .= "<tr><td>".$normal_messages_ar["additional_notes_mailing"]."</th><td><textarea name=\"notes_mailing\" cols=\"30\" rows=\"10\">";
	if(isset($mailing_row['notes_mailing'])) {
		$form .= htmlspecialchars($mailing_row['notes_mailing']);		
	}
	$form .= "</textarea></td></tr>";
	$form .= "<tr><td colspan=\"2\">&nbsp;</td></tr>";	
	
	$form .= "</table>";
	if(is_array($mailing_row)) { // come from edit	
		$form .= "<input type=\"submit\" value=\"".$submit_buttons_ar["save_this_mailing"]."\">";
	}
	else{
		$form .= "<input type=\"submit\" value=\"".$submit_buttons_ar["create_this_mailing"]."\">";
	}
	$form .= "</form>";

	return $form;
} // end function build_email_form

/*
function build_email_form($from_addresses_ar)
// goal: build a tabled HTML form with field for a new mailing
// input: $from_addresses_ar, the array containing the from addresses
// ouput: $form
{
	global $submit_buttons_ar, $normal_messages_ar;
	$form = "";
	if (get_cfg_var("file_uploads")){
		$form .= "<form action=\"mail.php?function=new_create\" method=\"post\" enctype=\"multipart/form-data\">";
	} // end if
	else{
		$form .= "<form action=\"mail.php?function=new_create\" method=\"post\">";
	} // end else
	$form .= "<table>";
	$form .= "<tr><th>".$normal_messages_ar["mailing_name"]."</th><td><input type=\"text\" name=\"name_mailing\">&nbsp;".$normal_messages_ar["specify_mailing_name"]."</td></tr>";
	$form .= "<tr><th>".$normal_messages_ar["mailing_type"]."</th><td>".$normal_messages_ar["e-mail"]."<input type=\"radio\" name=\"type_mailing\" value=\"email\">&nbsp;&nbsp;&nbsp;&nbsp;".$normal_messages_ar["normal_mail"]."<input type=\"radio\" name=\"type_mailing\" value=\"normal_mail\"></td></tr>";
	$form .= "<tr><td colspan=\"2\"><i>".$normal_messages_ar["email_specific_fields"]."</i></td><td>";
	$form .= "<tr><td colspan=\"2\">&nbsp;</td><td>";
	$form .= "<tr><th><font color=\"#0000ff\">".$normal_messages_ar["from"]."</font></th><td>";
	$form .= "<select name=\"from_mailing\">";

	for ($i=0; $i<count($from_addresses_ar); $i++){
		$form .= "<option value=\"".$i."\">\"".$from_addresses_ar[$i][0]."\" &lt;".$from_addresses_ar[$i][1]."&gt;</option>";
	} // end for

	$form .= "</select>";

	$form .= "</td></tr>";
	$form .= "<tr><th><font color=\"#0000ff\">".$normal_messages_ar["subject"]."</th><td><input type=\"text\" name=\"subject_mailing\"></font></td></tr>";
	$form .= "<tr><th>".$normal_messages_ar["body"]."</th><td><input type=\"text\" name=\"salute_mailing\" value=\"".$normal_messages_ar["dear"]."\">".$normal_messages_ar["john_smith"]."<br><textarea name=\"body_mailing\" cols=\"30\" rows=\"10\"></textarea></td></tr>";
	if (get_cfg_var("file_uploads")){
		$form .= "<tr><th><font color=\"#0000ff\">".$normal_messages_ar["attachment"]."</th><td><input type=\"file\" name=\"attachment_mailing\"></font></td></tr>";
	} // end if
	$form .= "<tr><td colspan=\"2\">&nbsp;</td><td>";
	$form .= "<tr><td>".$normal_messages_ar["additional_notes_mailing"]."</th><td><textarea name=\"notes_mailing\" cols=\"30\" rows=\"10\"></textarea></td></tr>";
	$form .= "<tr><td colspan=\"2\">&nbsp;</td><td>";	
	$form .= "</table>";
	$form .= "<input type=\"submit\" value=\"".$submit_buttons_ar["create_this_mailing"]."\">";
	$form .= "</form>";

	return $form;
} // end function build_email_form
*/
function build_add_to_mailing_form($res_mailing, $select_without_limit, $results_number)
// goal: build a tabled HTML form with add to mailing button and a select for choose mailing
// input: $res_mailing, the result set of a sql select on all the mailing, $select_without_limit, the sql select on records, to pass as hidden value, $results_number, the number of records found in the search, to pass as hidden value
// output: $form, the form
{
	global $submit_buttons_ar;
	$mailing_select = build_mailing_select(0);
	$form = "";
	$form .= "<form action=\"mail.php\" method=\"GET\">";
	$form .= "<input type=\"submit\" value=\"".$submit_buttons_ar["add_to_mailing"]."\">";
	$form .= " ".$mailing_select." ";
	$form .= "<input type=\"hidden\" name=\"function\" value=\"add_contacts\">";
	$form .= "<input type=\"hidden\" name=\"select_without_limit\" value=\"".$select_without_limit."\">";
	$form .= "<input type=\"hidden\" name=\"results_number\"value=\"".$results_number."\">";
	$form .= "</form>";

	return $form;
} // end function build_add_to_mailing_form

function build_mailing_select($show_sent_mailing)
// goal: build a select to choose the mailing
// input: $show_sent_mailing (0|1) retreive or not the sent mailing
// output: the html select or false if no mailings are found
{
	global $conn, $quote, $normal_messages_ar;
	$mailing_select = "";

	// get, for each mailing, the name, the number of contact associated, the sent_mailing(1|0) info
	$sql = "SELECT mailing_tab.name_mailing, mailing_contacts_tab.name_mailing AS name_mailing_2, count(*) as mailing_contact_number, sent_mailing from mailing_tab LEFT JOIN mailing_contacts_tab ON mailing_tab.name_mailing = mailing_contacts_tab.name_mailing";

	if ($show_sent_mailing === 0) {
		$sql .= " WHERE sent_mailing = '0'";
	} // end if
	
	$sql .= " GROUP BY mailing_tab.name_mailing ORDER BY sent_mailing, date_created_mailing DESC";

	// execute the query
	$res_mailing = execute_db($sql, $conn);

	if (get_num_rows_db($res_mailing) > 0) {
		$mailing_select .= "<select name=\"name_mailing\">";
		
		while($mailing_row = fetch_row_db($res_mailing)){
			$name_mailing = $mailing_row["name_mailing"];
			$name_mailing_2 = $mailing_row["name_mailing_2"];
			if ($name_mailing_2 === NULL) { // it means that no contacts are associated with the mailing, I set $mailing_contact_number to 0 to avoid (1)
				$mailing_contact_number = 0;
			} // end if
			else{
				$mailing_contact_number = $mailing_row["mailing_contact_number"];
			} // end else
			$sent_mailing = (int)$mailing_row["sent_mailing"];

			$mailing_select .= "<option value=\"".$name_mailing."\">".$name_mailing." (".$mailing_contact_number.")";
			if ($sent_mailing === 1) {
				$mailing_select .= ' ('.$normal_messages_ar['sent'].')';
			} // end if
			$mailing_select .= "</option>";
		} // end while
		
		$mailing_select .= "</select>";
	} // end if
	else {
		$mailing_select = false;
	} // end else
	return $mailing_select;
} // end function build_mailing_select

function build_change_table_form($function)
// goal: build a form to choose the table
// input: 
// output: the listbox
{
	global $conn, $table_name, $autosumbit_change_table_control, $dadabik_main_file, $enable_granular_permissions, $users_table_name, $prefix_internal_table, $groups_table_name, $enable_authentication;
	
	$change_table_form_2 = '';
	$change_table_form = '';
	
	
	$change_table_form .= "\n<!--[if !lte IE 9]><!-->\n";
	$change_table_form .= '<form class="css_form" method="get" action="'.$dadabik_main_file.'" name="change_table_form"><input type="hidden" name="function" value="'.$function.'">'."\n";
	$change_table_form .= "<!--<![endif]-->\n";
	$change_table_form .= "<!--[if lte IE 9]>\n";
	$change_table_form .= '<form method="get" action="'.$dadabik_main_file.'" name="change_table_form"><input type="hidden" name="function" value="search">'."\n";
	$change_table_form .= "<![endif]-->\n";
	
	if (  false && $autosumbit_change_table_control == 0) {
		$change_table_form .= '<input type="submit" class="button_change_table" value="'.$submit_buttons_ar["change_table"].'">';
	} // end if
	$change_table_form .= "<div class=\"select_element select_element_change_table\"><select name=\"table_name\" class=\"select_change_table\"";
	if ( true || $autosumbit_change_table_control == 1) {
		$change_table_form .= " onchange=\"javascript:document.change_table_form.submit()\"";
	}
	$change_table_form .= ">";
	
	if ($enable_granular_permissions === 1 && $enable_authentication === 1){
		$only_include_allowed = 1;
		$temp_ar = build_installed_table_infos_ar($only_include_allowed, 1);
		
		$allowed_table_infos_ar = array();
		
		foreach($temp_ar as $temp_ar_el){
		
			//var_dump(user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $temp_ar_el['name_table'],'1'));
			//var_dump($temp_ar_el['name_table']);
			if ( user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $temp_ar_el['name_table'], '1') === 1 || user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $temp_ar_el['name_table'], '1') === 2 || $temp_ar_el['name_table'] === $users_table_name || $temp_ar_el['name_table'] === $groups_table_name || $temp_ar_el['name_table'] === $prefix_internal_table.'static_pages'){
				$allowed_table_infos_ar[] = $temp_ar_el;
			}
		}
	}
	elseif($enable_granular_permissions === 0 || $enable_authentication === 0){
		$only_include_allowed = 1;
		$temp_ar = build_installed_table_infos_ar($only_include_allowed, 1);
		
		$allowed_table_infos_ar = array();
		
		foreach($temp_ar as $temp_ar_el){
			$enabled_features_ar = build_enabled_features_ar($temp_ar_el['name_table']);
		
			if ($enabled_features_ar['list'] === '1'){
				$allowed_table_infos_ar[] = $temp_ar_el;
			}
		}
	}
	else{
		echo "<p>An error occured";
	} // end else

	$count_temp = count($allowed_table_infos_ar);
	for($i=0; $i<$count_temp; $i++){
	
		if ($allowed_table_infos_ar[$i]['name_table'] === $users_table_name || $allowed_table_infos_ar[$i]['name_table'] === $groups_table_name || $allowed_table_infos_ar[$i]['name_table'] === $prefix_internal_table."static_pages"){
			if ($change_table_form_2 === ''){
				$change_table_form_2 .= '<optgroup label="admin tables:">';
			}
			$change_table_form_2 .= "<option value=\"".htmlspecialchars($allowed_table_infos_ar[$i]['name_table'])."\"";
			if ($table_name == $allowed_table_infos_ar[$i]['name_table']){
				$change_table_form_2 .= " selected";
			}
			$change_table_form_2 .= ">".$allowed_table_infos_ar[$i]['alias_table']."</option>";
		}
		else{
			$change_table_form .= "<option value=\"".htmlspecialchars($allowed_table_infos_ar[$i]['name_table'])."\"";
			if ($table_name == $allowed_table_infos_ar[$i]['name_table']){
				$change_table_form .= " selected";
			}
			$change_table_form .= ">".$allowed_table_infos_ar[$i]['alias_table']."</option>";
		}
	} // end for
	if ($change_table_form_2 !== ''){
		$change_table_form .= $change_table_form_2.'</optgroup>';
	}
	$change_table_form .= "</select></div>";
	$change_table_form .= '</form>';

	if ($count_temp == 1 || $count_temp == 0){
		return "";
	} // end if
	else{
		return $change_table_form;
	} // end else

} // end function build_change_table_form

function build_change_table_select($exclude_not_allowed=1, $inlcude_users_table = 0) 
// goal: build a select to choose the table
// input: $exclude_not_allowed, $inlcude_users_table (1 if it is necessary to include the users table, even if the user is not admin (useful in admin.php)
// output: $select, the html select
{
	global $conn, $table_name, $autosumbit_change_table_control, $users_table_name, $groups_table_name, $prefix_internal_table;
	$change_table_select = "";
	$change_table_select_2 = "";
	$change_table_select .= "<select name=\"table_name\" class=\"select_change_table\"";
	if ( true || $autosumbit_change_table_control == 1) {
		$change_table_select .= " onchange=\"javascript:document.change_table_form.submit()\"";
	}
	$change_table_select .= ">";

	if ($exclude_not_allowed == 1){
		if ($inlcude_users_table == 0) {
			// get the array containing the names of the tables installed(excluding not allowed)
			$tables_names_ar = build_tables_names_array(1, 1, 0);
		} // end if
		else {
			// get the array containing the names of the tables installed(excluding not allowed)
			$tables_names_ar = build_tables_names_array(1, 1, 1);
		} // end else
	} // end if
	else{
		if ($inlcude_users_table == 0) {
			// get the array containing the names of the tables installed
			$tables_names_ar = build_tables_names_array(0, 1, 0);
		} // end if
		else {
			// get the array containing the names of the tables installed
			$tables_names_ar = build_tables_names_array(0, 1, 1);
		} // end else
	} // end else

	$count_temp = count($tables_names_ar);
	for($i=0; $i<$count_temp; $i++){
	
		
		
		
		if ($tables_names_ar[$i] === $users_table_name || $tables_names_ar[$i] === $groups_table_name || $tables_names_ar[$i] === $prefix_internal_table."static_pages"){
			if ($change_table_select_2 === ''){
				$change_table_select_2 .= '<optgroup label="admin tables:">';
			}
			$change_table_select_2 .= "<option value=\"".htmlspecialchars($tables_names_ar[$i])."\"";
			if ($table_name == $tables_names_ar[$i]){
				$change_table_select_2 .= " selected";
			}
			$change_table_select_2 .= ">".$tables_names_ar[$i]."</option>";
		}
		else{
			$change_table_select .= "<option value=\"".htmlspecialchars($tables_names_ar[$i])."\"";
			if ($table_name == $tables_names_ar[$i]){
				$change_table_select .= " selected";
			}
			$change_table_select .= ">".$tables_names_ar[$i]."</option>";
		}
	} // end for
	if ($change_table_select_2 !== ''){
		$change_table_select .= $change_table_select_2.'</optgroup>';
	}
	
	$change_table_select .= "</select>";
	if ($count_temp == 1){
		return "";
	} // end if
	else{
		return $change_table_select;
	} // end else
} // end function build_change_table_select

function mailing_counter($name_mailing)
// goal: count how many contact are present in a mailing
// input:  $name_mailing, the name of the mailing
// output: $mailing_count, the number of contact
{
	global $conn;
	$sql = "select count(*) as mailing_count from mailing_contacts_tab where name_mailing = '".$name_mailing."'";
	
	// execute the query
	$res_mailing_count = execute_db($sql, $conn);
	$mailing_count_row = fetch_row_db($res_mailing_count);
	$mailing_count = $mailing_count_row["mailing_count"];
	return $mailing_count;
} // end function mailing_counter

function table_contains($db_name_2, $table_name, $field_name, $value)
// goal: check if a table contains a record which has a field set to a specified value
// input: $db_name, $table_name, $field_name, $value
// output: true or false
{
	global $conn, $quote, $db_name;
	/*
	if ( $db_name_2 != "") {
		select_db($db_name_2, $conn);
	}
	*/
	$sql = "SELECT COUNT(".$quote.$field_name.$quote.") FROM ".$quote.$table_name.$quote." WHERE ".$quote.$field_name.$quote." = '".$value."'";
	$res_count = execute_db($sql, $conn);
	$count_row = fetch_row_db($res_count);
	if ($count_row[0] > 0){
		return true;
	} // end if
	/*
	// re-select the old db
	if ( $db_name_2 != "") {
		select_db($db_name, $conn);
	}
	*/
	return false;
} // end function table_contains

function insert_record($_FILES_2, $_POST_2, $fields_labels_ar, $table_name, $table_internal_name)
// goal: insert a new record in the table
// input $_FILES_2 (needed for the name of the files), $_POST_2 (the array containing all the values inserted in the form), $fields_labels_ar, $table_name, $table_internal_name
// output: nothing
{
	global $conn, $db_name, $quote, $upload_directory, $current_user, $null_checkbox_prefix, $year_field_suffix, $month_field_suffix, $day_field_suffix, $hours_field_suffix, $minutes_field_suffix, $seconds_field_suffix;

	/*
	// get the post variables of the form
	reset ($_POST_2);
	while (list($key, $value) = each ($_POST_2)){
		$$key = $value;
	} // end while
	*/
	
	//begin_trans_db();

	$uploaded_file_names_count = 0;

	// build the insert statement
	/////////////////////////////
	$sql = "";
	$sql .= "INSERT INTO ".$quote.$table_name.$quote." (";

	$count_temp=count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++){
		if ($fields_labels_ar[$i]["present_insert_form_field"] == "1" || $fields_labels_ar[$i]["type_field"] == "insert_date" || $fields_labels_ar[$i]["type_field"] == "update_date" || $fields_labels_ar[$i]["type_field"] == "ID_user" || $fields_labels_ar[$i]["type_field"] == "unique_ID"){ // if the field is in the form or need to be inserted because it's an insert data, an update data, an ID_user or a unique_ID
			$sql .= $quote.$fields_labels_ar[$i]["name_field"].$quote.", "; // add the field name to the sql statement
		} // end if
		elseif ($fields_labels_ar[$i]["default_value_field"] != '') {
			$sql .= $quote.$fields_labels_ar[$i]["name_field"].$quote.", "; // add the field name to the sql statement
		}
	} // end for

	$sql = substr_custom($sql, 0, (strlen_custom($sql)-2));

	$sql .= ") VALUES (";

	for ($i=0; $i<$count_temp; $i++){
		if ($fields_labels_ar[$i]["present_insert_form_field"] == "1"){ // if the field is in the form

			$name_field_temp = $fields_labels_ar[$i]["name_field"];

			if (isset($_POST_2[$null_checkbox_prefix.$name_field_temp]) && $_POST_2[$null_checkbox_prefix.$name_field_temp] === '1') { // NULL checkbox selected
				$sql .= "NULL, "; // add the NULL value to the sql statement
			} // end if
			else {
				switch ($fields_labels_ar[$i]["type_field"]){
					case "generic_file":
					case "image_file":
						
						$file_name = unescape($_FILES_2[$name_field_temp]['name']);
						
						if ($file_name != '') {
							$file_name = get_valid_name_uploaded_file($file_name, 0);
						}

						$sql .= "'".escape($file_name)."', "; // add the field value to the sql statement
						//$sql .= "'".escape($uploaded_file_names_ar[$uploaded_file_names_count])."', "; // add the field value to the sql statement
						$uploaded_file_names_count++;
						
						if ($file_name != '') {
							// rename the temp name of the uploaded file
							copy ($upload_directory.'dadabik_tmp_file_'.$file_name, $upload_directory.$file_name);
							unlink($upload_directory.'dadabik_tmp_file_'.$file_name);
						} // end if
						break;
					case "select_multiple_menu":
					case "select_multiple_checkbox":
						$field_name_temp = $fields_labels_ar[$i]["name_field"];
						$sql .= "'";
						if (isset($_POST_2[$fields_labels_ar[$i]["name_field"]])){ // otherwise the user hasn't checked any options
							$count_temp_2 = count($_POST_2[$fields_labels_ar[$i]["name_field"]]);
							for ($j=0; $j<$count_temp_2; $j++){
								$sql .= $fields_labels_ar[$i]["separator_field"].$_POST_2[$field_name_temp][$j];// add the field value to the sql statement
							} // end for
							$sql .= $fields_labels_ar[$i]["separator_field"]; // add the last separator
						} // end if
						$sql .= "', ";
						break;
					case "date":
						$field_name_temp = $fields_labels_ar[$i]["name_field"];
						
						$year_field = $field_name_temp.$year_field_suffix;
						$month_field = $field_name_temp.$month_field_suffix;
						$day_field = $field_name_temp.$day_field_suffix;

						$mysql_date_value = $_POST_2[$year_field]."-".$_POST_2[$month_field]."-".$_POST_2[$day_field];
						//$sql .= "'".$mysql_date_value."', "; // add the field value to the sql statement
						$sql .= format_date_for_dbms($mysql_date_value).", "; // add the field value to the sql statement

						break;
					case "date_time":
						$field_name_temp = $fields_labels_ar[$i]["name_field"];
						
						$year_field = $field_name_temp.$year_field_suffix;
						$month_field = $field_name_temp.$month_field_suffix;
						$day_field = $field_name_temp.$day_field_suffix;
						
						$hours_field = $field_name_temp.$hours_field_suffix;
						$minutes_field = $field_name_temp.$minutes_field_suffix;
						$seconds_field = $field_name_temp.$seconds_field_suffix;
						
						
						$mysql_date_time_value = $_POST_2[$year_field]."-".$_POST_2[$month_field]."-".$_POST_2[$day_field]." ".$_POST_2[$hours_field].":".$_POST_2[$minutes_field].":".$_POST_2[$seconds_field];
						//$sql .= "'".$mysql_date_value."', "; // add the field value to the sql statement
						$sql .= format_date_time_for_dbms($mysql_date_time_value).", "; // add the field value to the sql statement
						
					

						break;
					case "select_single":
						$field_name_temp = $fields_labels_ar[$i]["name_field"];
						$field_name_other_temp = $fields_labels_ar[$i]["name_field"]."_other____";

						if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST_2[$field_name_temp] == "......" and $_POST_2[$field_name_other_temp] != ""){ // insert the "other...." choice
							$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
							if ($primary_key_field_field != ""){
								
								$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $fields_labels_ar[$i]["linked_fields_field"]);

								$primary_key_field_field = insert_other_field($fields_labels_ar[$i]["primary_key_db_field"], $fields_labels_ar[$i]["primary_key_table_field"], $linked_fields_ar[0], $_POST_2[$field_name_other_temp]);
								/*
								if (substr_custom($foreign_key_temp, 0, 4) != "SQL:"){ // with arbitrary sql statement the insert in the primary key table is not supported yet
									insert_other_field($foreign_key_temp, $_POST_2[$field_name_other_temp]);
								} // end if
								*/
								$sql .= "'".$primary_key_field_field."', "; // add the last ID inserted to the sql statement
							} // end if ($foreign_key_temp != "")
							else{ // no foreign key field
								$sql .= "'".$_POST_2[$field_name_other_temp]."', "; // add the field value to the sql statement
								if ( strpos_custom($fields_labels_ar[$i]["select_options_field"], $fields_labels_ar[$i]["separator_field"].$_POST_2[$field_name_other_temp].$fields_labels_ar[$i]["separator_field"]) === false ){ // the other field inserted is not already present in the $fields_labels_ar[$i]["select_options_field"] so we have to add it

									update_options($fields_labels_ar[$i], $field_name_temp, $_POST_2[$field_name_other_temp]);

									// re-get the array containg label ant other information about the fields changed with the above instruction
									$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");
								} // end if
							} // end else
						} // end if
						else{
							$sql .= "'".$_POST_2[$field_name_temp]."', "; // add the field value to the sql statement
						} // end else
						break;
					default: // textual field and select single
						if (unescape($_POST_2[$fields_labels_ar[$i]["name_field"]]) == $fields_labels_ar[$i]["prefix_field"]){ // the field contain just the prefix
							$_POST_2[$fields_labels_ar[$i]["name_field"]] = "";
						} // end if
						$sql .= "'".$_POST_2[$fields_labels_ar[$i]["name_field"]]."', "; // add the field value to the sql statement
						break;
				} // end switch
			} // end else
		} // end if
		elseif ($fields_labels_ar[$i]["type_field"] == "insert_date" or $fields_labels_ar[$i]["type_field"] == "update_date"){ // if the field is not in the form but need to be inserted because it's an insert data or an update data
			//$sql .= "'".date("Y-m-d H:i:s")."', "; // add the field name to the sql statement
			$sql .= format_date_for_dbms(date("Y-m-d")).", "; // add the field name to the sql statement

		} // end elseif
		elseif ($fields_labels_ar[$i]["type_field"] == "ID_user"){ // if the field is not in the form but need to be inserted because it's an ID_user
			$sql .= "'".escape($current_user)."', "; // add the field name to the sql statement
		} // end elseif
		elseif ($fields_labels_ar[$i]["type_field"] == "unique_ID"){ // if the field is not in the form but need to be inserted because it's a password record
			$pass = md5(uniqid(microtime(), 1)).getmypid();
			$sql .= "'".$pass."', "; // add the field name to the sql statement
		} // end elseif
		elseif ($fields_labels_ar[$i]["default_value_field"] != '') {
			if (substr_custom($fields_labels_ar[$i]["default_value_field"], 0, 4) === 'SQL:'){
				//$sql_temp_ar = explode(';', substr_custom($fields_labels_ar[$i]["default_value_field"], 4, strlen_custom($fields_labels_ar[$i]["default_value_field"])));
				
				$sql_temp = substr_custom($fields_labels_ar[$i]["default_value_field"],4,strlen_custom($fields_labels_ar[$i]["default_value_field"]));
				
				
				/*
				$j = 0;
				
				for ($j=0;$j<(count($sql_temp_ar)-1);$j++){ // execute n-1 statements
				
					$res_temp = execute_db($sql_temp_ar[$j], $conn);
				}
				
				$res_temp = execute_db($sql_temp_ar[$j], $conn); // use the last to take the value
				*/
				
				$res_temp = execute_db($sql_temp, $conn);
				
				$row_temp = fetch_row_db($res_temp);
				
				$sql .= "'".escape($row_temp[0])."', ";
			}
			else{
				$sql .= "'".escape($fields_labels_ar[$i]["default_value_field"])."', ";
			}
		}
	} // end for

	$sql = substr_custom($sql, 0, (strlen_custom($sql)-2));

	$sql .= ")";
	/////////////////////////////
	// end build the insert statement
	
	display_sql($sql);
	
	// insert the record
	$res_insert = execute_db($sql, $conn);
	
	$last_inserted_ID = get_last_ID_db();
	
	return $last_inserted_ID;
	
	//complete_trans_db();
	
} // end function insert_record

function update_record($_FILES_2, $_POST_2, $fields_labels_ar, $table_name, $table_internal_name, $where_field, $where_value, $update_type)
// goal: insert a new record in the main database
// input $_FILES_2 (needed for the name of the files), $_POST_2 (the array containing all the values inserted in the form, $fields_labels_ar, $table_name, $table_internal_name, $where_field, $where_value, $update_type (internal or external)
// output: nothing
// global: $ext_updated_field, the field in which we set if a field has been updated
{
	global $conn, $ext_updated_field, $quote, $upload_directory, $null_checkbox_prefix, $year_field_suffix, $month_field_suffix, $day_field_suffix, $hours_field_suffix, $minutes_field_suffix, $seconds_field_suffix, $prefix_internal_table;
	$uploaded_file_names_count = 0;

	/*
	// get the post variables of the form
	reset ($_POST_2);
	while (list($key, $value) = each ($_POST_2)){
		$$key = $value;
	} // end while
	*/
	
	switch($update_type){
		case "internal":
			//$field_to_check = "present_insert_form_field";
			$field_to_check = "present_edit_form_field";
		break;
		case "external":
			$field_to_check = "present_ext_update_form_field";
		break;
	} // end switch

	// build the update statement
	/////////////////////////////
	$sql = "";
	$sql .= "UPDATE ".$quote.$table_name.$quote." SET ";
	
	$count_temp = count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++){
		$field_name_temp = $fields_labels_ar[$i]["name_field"];
		// I use isset for select_multiple because could be unset
		//if ($fields_labels_ar[$i][$field_to_check] == "1" && isset($_POST_2[$field_name_temp]) or $fields_labels_ar[$i]["type_field"] == "update_date"){ // if the field is in the form or need to be inserted because it's an update data

		// no, since I don't handle select_multiple anymore, I delete isset, and anyway it was not correct because for a file $_POST_2[$field_name_temp] is not set
		if ($fields_labels_ar[$i][$field_to_check] == "1" or $fields_labels_ar[$i]["type_field"] == "update_date"){ // if the field is in the form or need to be inserted because it's an update data

			if (isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1') { // NULL checkbox selected
				$sql .= $quote.$field_name_temp.$quote." = NULL, "; // add the NULL value to the sql statement
			} // end if
			else {
				switch ($fields_labels_ar[$i]["type_field"]){
					case "generic_file":
					case "image_file":
						$file_name = unescape($_FILES_2[$field_name_temp]['name']);
						if ( $file_name != '') { // the user has selected a new file to upload
							
							$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement

							$file_name = get_valid_name_uploaded_file($file_name, 0);

							$sql .= "'".escape($file_name)."', "; // add the field value to the sql statement
							$uploaded_file_names_count++;

							// rename the temp name of the uploaded file
							copy ($upload_directory.'dadabik_tmp_file_'.$file_name, $upload_directory.$file_name);
							unlink($upload_directory.'dadabik_tmp_file_'.$file_name);

							if (isset($_POST_2[$field_name_temp.'_file_uploaded_delete'])) { // the user want to delete a file previoulsy uploaded
								unlink($upload_directory.unescape($_POST_2[$field_name_temp.'_file_uploaded_delete']));
							} // end if
						}
						elseif (isset($_POST_2[$field_name_temp.'_file_uploaded_delete'])) { // the user want to delete a file previoulsy uploaded
							$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
							$sql .= "'', "; // add the field value to the sql statement
							unlink($upload_directory.unescape($_POST_2[$field_name_temp.'_file_uploaded_delete']));
						}
						break;
					case "select_multiple_menu":
					case "select_multiple_checkbox":
						$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
						$sql .= "'";
						$count_temp_2 = count($_POST_2[$field_name_temp]);
						for ($j=0; $j<$count_temp_2; $j++){
							$sql .= $fields_labels_ar[$i]["separator_field"].$_POST_2[$field_name_temp][$j];// add the field value to the sql statement
						} // end for
						$sql .= $fields_labels_ar[$i]["separator_field"]; // add the last separator
						$sql .= "', ";
						break;
					case "update_date":
						$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
						//$sql .= "'".date("Y-m-d H:i:s")."', "; // add the field name to the sql statement
						$sql .= format_date_for_dbms(date("Y-m-d")).", "; // add the field name to the sql statement
						break;
					case "date":
						$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
						$field_name_temp = $field_name_temp;
						$year_field = $field_name_temp.$year_field_suffix;
						$month_field = $field_name_temp.$month_field_suffix;
						$day_field = $field_name_temp.$day_field_suffix;
						

						$mysql_date_value = $_POST_2[$year_field]."-".$_POST_2[$month_field]."-".$_POST_2[$day_field];
						//$sql .= "'".$mysql_date_value."', "; // add the field value to the sql statement
						$sql .= format_date_for_dbms($mysql_date_value).", "; // add the field value to the sql statement

						break;
					case "date_time":
						$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
						$field_name_temp = $field_name_temp;
						$year_field = $field_name_temp.$year_field_suffix;
						$month_field = $field_name_temp.$month_field_suffix;
						$day_field = $field_name_temp.$day_field_suffix;
						
						$hours_field = $field_name_temp.$hours_field_suffix;
						$minutes_field = $field_name_temp.$minutes_field_suffix;
						$seconds_field = $field_name_temp.$seconds_field_suffix;

						$mysql_date_time_value = $_POST_2[$year_field]."-".$_POST_2[$month_field]."-".$_POST_2[$day_field]." ".$_POST_2[$hours_field].":".$_POST_2[$minutes_field].":".$_POST_2[$seconds_field];
						//$sql .= "'".$mysql_date_value."', "; // add the field value to the sql statement
						$sql .= format_date_time_for_dbms($mysql_date_time_value).", "; // add the field value to the sql statement

						break;
					case 'select_single':
						$field_name_other_temp = $field_name_temp."_other____";

						if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST_2[$field_name_temp] == "......" and $_POST_2[$field_name_other_temp] != ""){ // insert the "other...." choice
							
							$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
							if ($primary_key_field_field != ""){
								$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $fields_labels_ar[$i]["linked_fields_field"]);

								$primary_key_field_field = insert_other_field($fields_labels_ar[$i]["primary_key_db_field"], $fields_labels_ar[$i]["primary_key_table_field"], $linked_fields_ar[0], $_POST_2[$field_name_other_temp]);
								/*
								if (substr_custom($foreign_key_temp, 0, 4) != "SQL:"){ // with arbitrary sql statement the insert in the primary key table is not supported yet

									insert_other_field($foreign_key_temp, $_POST_2[$field_name_other_temp]);

								} // end if
								*/
								$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
								$sql .= "'".$primary_key_field_field."', "; // add the field value to the sql statement
							} // end if ($foreign_key_temp != "")
							else{ // no foreign key field
								$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
								$sql .= "'".$_POST_2[$field_name_other_temp]."', "; // add the field value to the sql statement
								if (strpos_custom($fields_labels_ar[$i]["select_options_field"], $fields_labels_ar[$i]["separator_field"].unescape($_POST_2[$field_name_other_temp]).$fields_labels_ar[$i]["separator_field"]) === false){ // the other field inserted is not already present in the $fields_labels_ar[$i]["select_options_field"] so we have to add it

									update_options($fields_labels_ar[$i], $field_name_temp, $_POST_2[$field_name_other_temp]);

									// re-get the array containg label ant other information about the fields changed with the above instruction
									$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");
								} // end if
							} // end else
						} // end if
						else{
							$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
							$sql .= "'".$_POST_2[$field_name_temp]."', "; // add the field value to the sql statement
						} // end else
						
						break;
					default: // textual field
						$sql .= $quote.$field_name_temp.$quote." = "; // add the field name to the sql statement
						$sql .= "'".$_POST_2[$field_name_temp]."', "; // add the field value to the sql statement
						break;
				} // end switch
			} // end else
		} // end if
	} // end for
	$sql = substr_custom($sql, 0, -2); // delete the last two characters: ", "
	$sql .= " where ".$quote.$where_field.$quote." = '".$where_value."'";
	/////////////////////////////
	// end build the update statement
	
	display_sql($sql);
	
	// hack for static pages
	if ($table_name === $prefix_internal_table.'static_pages' && $_POST_2['is_homepage_static_page'] === 'y'){
		
		$sql_2 = "update ".$quote.$prefix_internal_table."static_pages".$quote." set is_homepage_static_page = 'n'";
		
		$res_update = execute_db($sql_2, $conn);
	}
	
	// update the record
	$res_update = execute_db($sql, $conn);
	
	if ($update_type == "external"){
		
		$sql = "UPDATE ".$quote.$table_name.$quote." SET ".$quote.$ext_updated_field.$quote." = '1' WHERE ".$quote.$where_field.$quote." = '".$where_value."'";

		display_sql($sql);
		
		// update the record
		$res_update = execute_db($sql, $conn);
	} // end if
} // end function update_record
/*
function build_select_query($_POST_2, $table_name, $table_internal_name)
{
	global $conn, $db_name, $quote;
	// get the post variables of the form
	reset ($_POST_2);
	while (list($key, $value) = each ($_POST_2)){
		$$key = $value;
	} // end while
	
	$sql = "";
	$select = "";
	$where = "";
	$select = "select * from ".$quote."$table_name".$quote."";
	
	// get the array containg label and other information about the fields
	$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

	// build the where clause
	for ($i=0; $i<count($fields_labels_ar); $i++){
		$field_type_temp = $fields_labels_ar[$i]["type_field"];
		$field_name_temp = $fields_labels_ar[$i]["name_field"];
		$field_separator_temp = $fields_labels_ar[$i]["separator_field"];
		$field_select_type_temp = $fields_labels_ar[$i]["select_type_field"];

		if ($fields_labels_ar[$i]["present_search_form_field"] == "1"){
			switch ($field_type_temp){
				case "select_multiple_menu":
				case "select_multiple_checkbox":
					if (isset($$field_name_temp)){
						for ($j=0; $j<count(${$field_name_temp}); $j++){ // for each possible check
							if (${$field_name_temp}[$j] != ""){
								$where .= $quote.$field_name_temp."".$quote." like '%".$field_separator_temp."".${$field_name_temp}[$j].$field_separator_temp."%'";
								$where .= " $operator ";
							} // end if
						} // end for
					} // end if
					break;
				case "date":
				case "insert_date":
				case "update_date":
					$operator_field_name_temp = $field_name_temp."_operator";				
					if ($$operator_field_name_temp != ""){
						$year_field = $field_name_temp."_year";
						$month_field = $field_name_temp."_month";
						$day_field = $field_name_temp."_day";

						$mysql_date_value = $$year_field."-".$$month_field."-".$$day_field;

						$where .= "DATE_FORMAT(".$quote."$field_name_temp".$quote.", '%Y-%m-%d') ".$$operator_field_name_temp." '".$mysql_date_value."' $operator ";;
					} // end if				
					break;
				default:
					if ($$field_name_temp != ""){ // if the user has filled the field
						$select_type_field_name_temp = $field_name_temp."_select_type";
						if ($$select_type_field_name_temp != ""){ 
							switch ($$select_type_field_name_temp){								
								case "like":
									$where .= $quote.$field_name_temp."".$quote." like '%".${$field_name_temp}."%'";
									break;
								case "exactly":
									$where .= "".$quote."".$field_name_temp."".$quote." = '".${$field_name_temp}."'";
									break;
								default:
									$where .= $quote.$field_name_temp."".$quote." ".$$select_type_field_name_temp." '".${$field_name_temp}."'";			
									break;
							} // end switch
						} // end if
						else{
							switch ($field_select_type_temp){									
								case "like":
									$where .= $quote.$field_name_temp."".$quote." like '%".${$field_name_temp}."%'";
									break;
								case "exactly":
									$where .= $quote.$field_name_temp."".$quote." = '".${$field_name_temp}."'";
									break;
								default:
									$where .= $quote.$field_name_temp."".$quote." $field_select_type_temp '".${$field_name_temp}."'";
									break;
							} // end switch
						} // end else
						$where .= " $operator ";
					} // end if
					break;
			} //end switch
		} // end if
	} // end for ($i=0; $i<count($fields_labels_ar); $i++)

	$where = substr_custom($where, 0, strlen_custom($where) - (strlen_custom($operator)+2)); // delete the last " and " or " or "
	if ($where != ""){
			$where = " where ".$where;
	} // end if

	// compose the entire sql statement
	$sql = $select.$where;

	return $sql;
} // end function build_select_query
*/

function build_filters_ar($_POST_2, $fields_labels_ar, $table_name)
{
	global $select_type_select_suffix, $year_field_suffix, $month_field_suffix, $day_field_suffix, $hours_field_suffix, $minutes_field_suffix, $seconds_field_suffix;
	
	$filters_ar = array();
	
	foreach ($fields_labels_ar as $field_element){
		
		if ($field_element['present_filter_form_field'] == '1'){
			$field_name_temp = $field_element['name_field'];
			
			if (isset($_POST_2[$field_name_temp.$select_type_select_suffix]) && isset($_POST_2[$field_name_temp])){ // could be not set if the search is from search form (not filter form) and a field is in the filter form but not in the search form
			
				$filters_ar['select_types'][$field_name_temp] = $_POST_2[$field_name_temp.$select_type_select_suffix];
			
				switch ($field_element['type_field']){
					case "date":
					case "insert_date":
					case "update_date":
						
						$year_field = $field_name_temp.$year_field_suffix;
						$month_field = $field_name_temp.$month_field_suffix;
						$day_field = $field_name_temp.$day_field_suffix;
						
						$filters_ar['values'][$year_field] = unescape($_POST_2[$year_field]);
						$filters_ar['values'][$month_field] = unescape($_POST_2[$month_field]);
						$filters_ar['values'][$day_field] = unescape($_POST_2[$day_field]);
						
						break;
					case "date_time":
						
						$year_field = $field_name_temp.$year_field_suffix;
						$month_field = $field_name_temp.$month_field_suffix;
						$day_field = $field_name_temp.$day_field_suffix;
						$hours_field = $field_name_temp.$hours_field_suffix;
						$minutes_field = $field_name_temp.$minutes_field_suffix;
						$seconds_field = $field_name_temp.$seconds_field_suffix;
						
						$filters_ar['values'][$year_field] = unescape($_POST_2[$year_field]);
						$filters_ar['values'][$month_field] = unescape($_POST_2[$month_field]);
						$filters_ar['values'][$day_field] = unescape($_POST_2[$day_field]);
						
						$filters_ar['values'][$hours_field] = unescape($_POST_2[$hours_field]);
						$filters_ar['values'][$minutes_field] = unescape($_POST_2[$minutes_field]);
						$filters_ar['values'][$seconds_field] = unescape($_POST_2[$seconds_field]);
						
						break;
					default:
						$filters_ar['values'][$field_name_temp] = unescape($_POST_2[$field_name_temp]);
						break;
				}
			}
			
		} // end if
		
	} // end foreach
	
	return $filters_ar;
}

function build_where_clause($_POST_2, $fields_labels_ar, $table_name, $search_from_filter)
// goal: build the where clause of a select sql statement e.g. "field_1 = 'value' AND field_2 LIKE '%value'"
// input: $_POST_2, $fields_labels_ar, $table_name
{
	global $quote, $conn, $select_type_select_suffix, $year_field_suffix, $month_field_suffix, $day_field_suffix, $hours_field_suffix, $minutes_field_suffix, $seconds_field_suffix;

	/*
	// get the post variables of the form
	reset ($_POST_2);
	while (list($key, $value) = each ($_POST_2)){
		$$key = $value;
	} // end while
	*/

	$where_clause = "";

	$count_temp = count($fields_labels_ar);
	// build the where clause
	for ($i=0; $i<$count_temp; $i++){
		$field_type_temp = $fields_labels_ar[$i]["type_field"];
		$field_name_temp = $fields_labels_ar[$i]["name_field"];
		$field_separator_temp = $fields_labels_ar[$i]["separator_field"];
		$field_select_type_temp = $fields_labels_ar[$i]["select_type_field"];
		
		$field_to_check = 'present_search_form_field';
		if ($search_from_filter === 1) {
			$field_to_check = 'present_filter_form_field';
		}

		if ($fields_labels_ar[$i][$field_to_check] == "1"){
			if ($_POST_2[$field_name_temp.$select_type_select_suffix] === 'is_null') { // is_null listbox option selected
				$where_clause .= $quote.$table_name.$quote.".".$quote.$field_name_temp.$quote." IS NULL"; // add the IS NULL value to the sql statement

				$where_clause .= " ".$_POST_2["operator"]." ";
			} // end if
			else if ($_POST_2[$field_name_temp.$select_type_select_suffix] === 'is_not_null') { // is_not_null listbox option selected
				$where_clause .= $quote.$table_name.$quote.".".$quote.$field_name_temp.$quote." IS NOT NULL"; // add the IS NOT NULL value to the sql statement

				$where_clause .= " ".$_POST_2["operator"]." ";
			} // end if
			else if ($_POST_2[$field_name_temp.$select_type_select_suffix] === 'is_empty') { // is_empty listbox option selected
			
				if ($field_name_temp === 'date' || $field_name_temp === 'insert_date' || $field_name_temp === 'update_date' || $field_name_temp === 'date_time'){
				
					$where_clause = array();
					$where_clause['error'] = '<p>Unhandled error: the search operator you chose is not available for date/date time fields.</p>';
				}
				else{
					$where_clause .= $quote.$table_name.$quote.".".$quote.$field_name_temp.$quote." = ''"; // add the = '' value to the sql statement

					$where_clause .= " ".$_POST_2["operator"]." ";
				}
			} // end if
			else if ($_POST_2[$field_name_temp.$select_type_select_suffix] === 'is_not_empty') { // is_not_empty listbox option selected
				
			
				if ($field_name_temp === 'date' || $field_name_temp === 'insert_date' || $field_name_temp === 'update_date' || $field_name_temp === 'date_time'){
				
					$where_clause = array();
					$where_clause['error'] = '<p>Unhandled error: the search operator you chose is not available for date/date time fields.</p>';
				}
				else{
					$where_clause .= $quote.$table_name.$quote.".".$quote.$field_name_temp.$quote." <> ''"; // add the = '' value to the sql statement
	
					$where_clause .= " ".$_POST_2["operator"]." ";
				}
			} // end if
			else{
				switch ($field_type_temp){
					case "select_multiple_menu":
					case "select_multiple_checkbox":
						if (isset($_POST_2[$field_name_temp])){
							$count_temp_2 = count($_POST_2[$field_name_temp]);
							for ($j=0; $j<$count_temp_2; $j++){ // for each possible check
								if ($_POST_2[$field_name_temp][$j] != ""){
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." LIKE '%".$field_separator_temp.$_POST_2[$field_name_temp][$j].$field_separator_temp."%'";
									$where_clause .= " AND ";
								} // end if
							} // end for
						} // end if
						break;
					case "date":
					case "insert_date":
					case "update_date":
						$select_type_field_name_temp = $field_name_temp.$select_type_select_suffix;
						if ($_POST_2[$select_type_field_name_temp] != "" || $_POST_2[$select_type_field_name_temp] === 'is_null'){
							$year_field = $field_name_temp.$year_field_suffix;
							$month_field = $field_name_temp.$month_field_suffix;
							$day_field = $field_name_temp.$day_field_suffix;

							$mysql_date_value = $_POST_2[$year_field]."-".$_POST_2[$month_field]."-".$_POST_2[$day_field];

							switch ($_POST_2[$select_type_field_name_temp]){
								case "is_equal":
									//$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." = '".$mysql_date_value."'";
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." = ".format_date_for_dbms($mysql_date_value);
									break;
								case "is_different":
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." <> ".format_date_for_dbms($mysql_date_value);
									break;
								case "greater_than":
									//$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." > '".$mysql_date_value."'";
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." > ".format_date_for_dbms($mysql_date_value);
									break;
								case "less_then":
									//$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." < '".$mysql_date_value."'";
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." < ".format_date_for_dbms($mysql_date_value);
									break;
								default:
									$where_clause = array();
									$where_clause['error'] = '<p>Unhandled error: the search operator you chose is not available for date fields.</p>';
							} // end switch

							//$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." ".$_POST_2[$select_type_field_name_temp]." '".$mysql_date_value."' ".$_POST_2["operator"]." ";

							if (!is_array($where_clause)) $where_clause .= " ".$_POST_2["operator"]." ";
						} // end if
						break;
					case "date_time":
						$select_type_field_name_temp = $field_name_temp.$select_type_select_suffix;
						if ($_POST_2[$select_type_field_name_temp] != "" || $_POST_2[$select_type_field_name_temp] === 'is_null'){
							$year_field = $field_name_temp.$year_field_suffix;
							$month_field = $field_name_temp.$month_field_suffix;
							$day_field = $field_name_temp.$day_field_suffix;
							
							$hours_field = $field_name_temp.$hours_field_suffix;
							$minutes_field = $field_name_temp.$minutes_field_suffix;
							$seconds_field = $field_name_temp.$seconds_field_suffix;

							$mysql_date_time_value = $_POST_2[$year_field]."-".$_POST_2[$month_field]."-".$_POST_2[$day_field]." ".$_POST_2[$hours_field]."-".$_POST_2[$minutes_field]."-".$_POST_2[$seconds_field];

							switch ($_POST_2[$select_type_field_name_temp]){
								case "is_equal":
									//$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." = '".$mysql_date_value."'";
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." = ".format_date_time_for_dbms($mysql_date_time_value);
									break;
								case "is_different":
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." <> ".format_date_time_for_dbms($mysql_date_time_value);
									break;
								case "greater_than":
									//$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." > '".$mysql_date_value."'";
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." > ".format_date_time_for_dbms($mysql_date_time_value);
									break;
								case "less_then":
									//$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." < '".$mysql_date_value."'";
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." < ".format_date_time_for_dbms($mysql_date_time_value);
									break;
								default:
									$where_clause = array();
									$where_clause['error'] = '<p>Unhandled error: the search operator you chose is not available for datetime fields.</p>';
							} // end switch

							//$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." ".$_POST_2[$select_type_field_name_temp]." '".$mysql_date_value."' ".$_POST_2["operator"]." ";

							if (!is_array($where_clause)) $where_clause .= " ".$_POST_2["operator"]." ";
						} // end if
						break;
					case 'select_single':
						$select_type_field_name_temp = $field_name_temp.$select_type_select_suffix;
						if ($_POST_2[$field_name_temp] != "" || $_POST_2[$select_type_field_name_temp] === 'is_null'){ // if the user has filled the field or has selected "is_null" as operator
							switch ($_POST_2[$select_type_field_name_temp]){
								case "is_equal":
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." = '".$_POST_2[$field_name_temp]."'";
									break;
								case "is_different":
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." <> '".$_POST_2[$field_name_temp]."'";
									break;
								default:
									$where_clause = array();
									$where_clause['error'] = '<p>Unhandled error: the search operator you chose is not available for select_single fields.</p>';
							} // end switch
							//} // end else
							if (!is_array($where_clause)) $where_clause .= " ".$_POST_2["operator"]." ";
						} // end if
						break;
					default:
						$select_type_field_name_temp = $field_name_temp.$select_type_select_suffix;
						if ($_POST_2[$field_name_temp] != "" || $_POST_2[$select_type_field_name_temp] === 'is_null'){ // if the user has filled the field or has selected "is_null" as operator
							switch ($_POST_2[$select_type_field_name_temp]){
								case "is_equal":
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." = '".$_POST_2[$field_name_temp]."'";
									break;
								case "is_different":
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." <> '".$_POST_2[$field_name_temp]."'";
									break;
								case "contains":
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." LIKE '%".$_POST_2[$field_name_temp]."%'";
									break;
								case "starts_with":
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." LIKE '".$_POST_2[$field_name_temp]."%'";
									break;
								case "ends_with":
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." LIKE '%".$_POST_2[$field_name_temp]."'";
									break;
								case "greater_than":
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." > '".$_POST_2[$field_name_temp]."'";
									break;
								case "less_then":
									$where_clause .= $quote.$table_name.$quote.'.'.$quote.$field_name_temp.$quote." < '".$_POST_2[$field_name_temp]."'";
									break;
							} // end switch
							//} // end else
							if (!is_array($where_clause)) $where_clause .= " ".$_POST_2["operator"]." ";
						} // end if
						break;
				} //end switch
			} // end else
		} // end if
		
		if (is_array($where_clause)){ // an error occured
			break;
		}
	} // end for ($i=0; $i<count($fields_labels_ar); $i++)

	if (!is_array($where_clause) && $where_clause !== '') {
		$where_clause = substr_custom($where_clause, 0, -(strlen_custom($_POST_2["operator" ])+2)); // delete the last " and " or " or "
	} // end if

	return $where_clause;
} // end function build_where_clause

function get_field_correct_displaying($field_value, $field_type, $field_content, $field_separator, $display_mode, $fields_labels_ar_element, $template_infos_ar=0)
// get the correct mode to display a field, according to its content (e.g. format data, display select multiple in different rows without separator and so on
// input: $field_value, $field_type, $field_content, $field_separator, $display_mode (results_table|details_page|plain_text)
// output: $field_to_display, the field value ready to be displayed
// global: $word_wrap_col, the column at which a string will be wrapped in the results
{
	global $word_wrap_col, $enable_word_wrap_cut, $upload_relative_url, $null_word, $htmlawed_config;
	$field_to_display = "";

	if (is_null($field_value)) {
		$field_to_display = $null_word;
	} // end if
	else {
	
	
		
		
		// enterprise
		// pro
		if ($fields_labels_ar_element["custom_formatting_function_field"] !== '' && $display_mode !== 'plain_text'){
		
			$field_to_display = call_user_func($fields_labels_ar_element["custom_formatting_function_field"], $field_value);
		}
		else{
		// end enterprise
		// end pro
	
	
	
			switch ($field_type){
				case "generic_file":
					if ( $field_value != '') {
						if ($display_mode === 'plain_text') {
							$field_to_display = $field_value;
						} // end if
						else {
							// I don't use htmlspecialchars on the first because e.g. if a filename contains &, &amp; doesn't work in the link
							$field_to_display = "<a href=\"".$upload_relative_url.rawurlencode($field_value)."\">".htmlspecialchars($field_value)."</a>";
						} // end else
					}
					break;
				case "image_file":
					$field_value = htmlspecialchars($field_value);
					if ( $field_value != '') {
						if ($display_mode === 'plain_text') {
							$field_to_display = $field_value;
						} // end if
						else {
							$field_to_display = "<img src=\"".$upload_relative_url.$field_value."\">";
						} // end else
					}
					break;
				case "select_multiple_menu":
				case "select_multiple_checkbox":
					$field_value = htmlspecialchars($field_value);
					$field_value = substr_custom($field_value, 1, -1); // delete the first and the last separator
					$select_values_ar = explode($field_separator,$field_value);
					$count_temp = count($select_values_ar);
					for ($i=0; $i<$count_temp; $i++){
						if ( $display_mode == "results_table") {
							$field_to_display .= nl2br(wordwrap($select_values_ar[$i], $word_wrap_col))."<br>";
						}
						else{
							$field_to_display .= nl2br($select_values_ar[$i])."<br>";
						}
					} // end for
					break;
				case "insert_date":
				case "update_date":
				case "date":
					$field_value = htmlspecialchars($field_value);
					if ($field_value != '0000-00-00 00:00:00' && $field_value != '0000-00-00'){
						$field_to_display = format_date($field_value);
					} // end if
					else{
						$field_to_display = "";
					} // end else
					break;
				case "date_time":
					$field_value = htmlspecialchars($field_value);
					if ($field_value != '0000-00-00 00:00:00' && $field_value != '0000-00-00'){
						$field_to_display = format_date_time($field_value);
					} // end if
					else{
						$field_to_display = "";
					} // end else
					break;
					
				case "rich_editor":
					$field_to_display = htmLawed($field_value, $htmlawed_config);
					break;
					
				default: // e.g. text, textarea and select sinlge
					if ($display_mode === 'plain_text') {
						$field_to_display = $field_value;
					} // end if
					else{
						if ($field_content !== 'html') {
							$field_value = htmlspecialchars($field_value);
	
							if ( $display_mode == "results_table" && (!is_array($template_infos_ar) || $template_infos_ar['enable_template_table'] != '1')){
								$enable_word_wrap_cut = 0;
								$displayed_part = wordwrap($field_value, $word_wrap_col, "\n", $enable_word_wrap_cut);
							} // end if
							else{
								$displayed_part = $field_value;
							} // end else
	
						} // end if
						else {
							$displayed_part = htmLawed($field_value, $htmlawed_config);
						} // end else
	
						if ($field_content == "email"){
							$field_to_display = "<a href=\"mailto:".$field_value."\">".$displayed_part."</a>";
						} // end if
						elseif ($field_content == "url"){
							$field_to_display = "<a href=\"".$field_value."\">".$displayed_part."</a>";
						} // end elseif
						elseif ($field_content == "timestamp"){
							$date_time_temp = date('Y-m-d H:i:s', $field_value);
							$temp_ar = explode(' ', $date_time_temp);
							$field_to_display = format_date($temp_ar[0]).' '.$temp_ar[1];
						} // end elseif
						else {
							$field_to_display = nl2br($displayed_part);
						} // end else
					} // end else
					break;
			} // end switch
		
				
		// enterprise
		// pro
		}
		// end enterprise
		// end pro
	} // end else
	
	return $field_to_display;
} // function get_field_correct_displaying

function get_field_correct_csv_displaying($field_value, $field_type, $field_content, $field_separator, $fields_labels_ar_element)
// get the correct mode to display a field in a csv, according to its content (e.g. format data, display select multiple in different rows without separator and so on
// input: $field_value, $field_type, $field_content, $field_separator
// output: $field_to_display, the field value ready to be displayed
{
	$field_to_display = "";
	
	
	// enterprise
	// pro
	if ($fields_labels_ar_element["custom_csv_formatting_function_field"] !== ''){
	
		$field_to_display = call_user_func($fields_labels_ar_element["custom_csv_formatting_function_field"], $field_value);
	}
	else{
	// end enterprise
	// end pro
	
		switch ($field_type){
			case "select_multiple_menu":
			case "select_multiple_checkbox":
				$field_value = substr_custom($field_value, 1, -1); // delete the first and the last separator
				$select_values_ar = explode($field_separator,$field_value);
				$count_temp = count($select_values_ar);
				for ($i=0; $i<$count_temp; $i++){
					$field_to_display .= $select_values_ar[$i]."\n";
				} // end for
				break;
			case "insert_date":
			case "update_date":
			case "date":
				if ($field_value != '0000-00-00 00:00:00' && $field_value != '0000-00-00'){
					$field_to_display = format_date($field_value);
				} // end if
				else{
					$field_to_display = "";
				} // end else
				break;
				
			case "date_time":
			
				if ($field_value != '0000-00-00 00:00:00' && $field_value != '0000-00-00'){
					$field_to_display = format_date_time($field_value);
				} // end if
				else{
					$field_to_display = "";
				} // end else
				break;
			default:
				$field_to_display = str_replace("\r", '', $field_value);
		} // end switch
	
	// enterprise
	// pro
	}
	// end enterprise
	// end pro
	
	
	return $field_to_display;
} // function get_field_correct_csv_displaying

function build_enabled_features_ar_2($table_name)
{
	$enabled_features_ar_2['insert'] = user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $table_name, '4');
		
	$permissions_temp = user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $table_name, '3');
	
	switch ($permissions_temp){
		case '1':
			$enabled_features_ar_2['update_authorization']  = 0;
			$enabled_features_ar_2['edit']  = '1';
			break;
		case '0':
			$enabled_features_ar_2['update_authorization']  = 0;
			$enabled_features_ar_2['edit']  = '0';
			break;
		case '2':
			$enabled_features_ar_2['update_authorization']  = 1;
			$enabled_features_ar_2['edit']  = '1';
			break;
	}
	
	$permissions_temp = user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $table_name, '2');
	
	switch ($permissions_temp){
		case '1':
			$enabled_features_ar_2['delete_authorization']  = 0;
			$enabled_features_ar_2['delete']  = '1';
			break;
		case '0':
			$enabled_features_ar_2['delete_authorization'] = 0;
			$enabled_features_ar_2['delete']  = '0';
			break;
		case '2':
			$enabled_features_ar_2['delete_authorization'] = 1;
			$enabled_features_ar_2['delete']  = '1';
			break;
	}
	
	$permissions_temp = user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $table_name, '5');
	
	switch ($permissions_temp){
		case '1':
			//$enabled_features_ar_2['details_authorization'] = 0;
			$enabled_features_ar_2['details'] = '1';
			break;
		case '0':
			//$enabled_features_ar_2['details_authorization'] = 0;
			$enabled_features_ar_2['details'] = '0';
			break;
		case '2':
			//$enabled_features_ar_2['details_authorization'] = 1;
			$enabled_features_ar_2['details'] = '1';
			break;
	}
	
	
	$permissions_temp = user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $table_name, '1');
	
	switch ($permissions_temp){
		case '1':
			$enabled_features_ar_2['browse_authorization'] = 0;
			$enabled_features_ar_2['browse'] = '1';
			break;
		case '0':
			$enabled_features_ar_2['browse_authorization'] = 0;
			$enabled_features_ar_2['browse'] = '0';
			break;
		case '2':
			$enabled_features_ar_2['browse_authorization'] = 1;
			$enabled_features_ar_2['browse'] = '1';
			break;
	}
	
	return $enabled_features_ar_2;
} // end function build_enabled_features_ar_2


function build_results_table($fields_labels_ar, $table_name, $res_records, $results_type, $name_mailing, $page, $action, $where_clause, $page, $order, $order_type, $master_table_name, $master_table_function, $master_table_where_field , $master_table_where_value, $is_items_table, $template_infos_ar)
// goal: build an HTML table for basicly displaying the results of a select query or show a check mailing results
// input: $table_name, $res_records, the results of the query, $results_type (search, possible_duplication......), $name_mailing, the name of the current mailing, $page, the results page (useful for check mailing), $action (e.g. index.php or mail.php), $where_clause, $page (o......n), $order, $order_type, $master_table_name, $master_table_function, $master_table_where_field , $master_table_where_value, $is_items_table
// output: $results_table, the HTML results table
// global: $submit_buttons_ar, the array containing the values of the submit buttons, $edit_target_window, the target window for edit/details (self, new......), $delete_icon, $edit_icon, $details_icon (the image files to use as icons), $enable_edit, $enable_delete, $enable_details (whether to enable (1) or not (0) the edit, delete and details features 
{
	global $submit_buttons_ar, $normal_messages_ar, $edit_target_window, $delete_icon, $edit_icon, $details_icon, $enable_edit, $enable_delete, $enable_details, $conn, $quote, $ask_confirmation_delete, $word_wrap_col, $word_wrap_fix_width, $alias_prefix, $dadabik_main_file, $enable_row_highlighting, $prefix_internal_table, $enable_granular_permissions, $enable_authentication;
	
	global $fields_to_display_ar, $details_link, $edit_link, $delete_link;
	
	if ($action == "mail.php"){
		$function = "check_form";
	} // end if
	else{
		$function = "search";
	} // end elseif

	$unique_field_name = get_unique_field_db($table_name);

	// build the results HTML table
	///////////////////////////////

	$results_table = "";
	$template_results_table = '';
	
	if ($is_items_table == '1'){
		$template_results_table .= '<br/>';
	}
	
	$results_table .= "<table class=\"results\" cellpadding=\"5\" cellspacing=\"0\">";

	// build the table heading
	$results_table .= "<tr class=\"tr_results_header\">";

	$results_table .= "\n<!--[if !lte IE 9]><!-->\n";
	$results_table .= "<th class=\"results\">\n";
	$results_table .= "<!--<![endif]-->\n";
	$results_table .= "<!--[if lte IE 9]>\n";
	$results_table .= "<th class=\"results_ie9\">\n";
	$results_table .= "<![endif]-->\n";

	$results_table .= "&nbsp;</th>"; // skip the first column for edit, delete and details

	$count_temp = count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++){
		if ($fields_labels_ar[$i]["present_results_search_field"] == "1"){ // the user want to display the field in the basic search results page

			$label_to_display = $fields_labels_ar[$i]["label_field"].'&nbsp;&nbsp;';

			if ($word_wrap_fix_width === 1){
			
				$spaces_to_add = $word_wrap_col-strlen_custom($label_to_display)-2;

				if ( $spaces_to_add > 0) {
					for ($j=0; $j<$spaces_to_add; $j++) {
						$label_to_display .= '&nbsp;';
					}
				}
			} // end if
			
			
			
			$results_table .= "\n<!--[if !lte IE 9]><!-->\n";
	$results_table .= "<th class=\"results\">\n";
	$results_table .= "<!--<![endif]-->\n";
	$results_table .= "<!--[if lte IE 9]>\n";
	$results_table .= "<th class=\"results_ie9\">\n";
	$results_table .= "<![endif]-->\n";

			$field_is_current_order_by = 0;

			if ( $results_type == "search") {
				if ($order != $fields_labels_ar[$i]["name_field"]){ // the results are not ordered by this field at the moment
					$link_class="order_link";
					$new_order_type = "ASC";
				}
				else{
					$field_is_current_order_by = 1;
					$link_class="order_link_selected";
					if ( $order_type == "DESC") {
						$new_order_type = "ASC";
					}
					else{
						$new_order_type = "DESC";
					}
				} // end else if ($order != $fields_labels_ar[$i]["name_field"])

				if ($is_items_table === 1) {
					$results_table .= "<a class=\"".$link_class."\" href=\"".$action."?table_name=". urlencode($master_table_name)."&function=".$master_table_function."&items_table_name=".$table_name."&where_field=".urlencode($master_table_where_field)."&where_value=".urlencode(unescape($master_table_where_value))."&where_clause=".urlencode($where_clause)."&page=".$page."&order=".urlencode($fields_labels_ar[$i]["name_field"])."&order_type=".$new_order_type."&store_session_table_infos_for_items_table=1\">";
				} // end if
				else {
					$results_table .= "<a class=\"".$link_class."\" href=\"".$action."?table_name=". urlencode($table_name)."&function=".$function."&where_clause=".urlencode($where_clause)."&page=".$page."&order=".urlencode($fields_labels_ar[$i]["name_field"])."&order_type=".$new_order_type."\">";
				} // end else
				
				

				if ($field_is_current_order_by === 1) {
					if ($order_type === 'ASC') {
						$results_table .= '<span class="arrow">&uarr;</span> ';
					} // end if
					else {
						$results_table .= '<span class="arrow">&darr;</span> ';
					} // end if
				} // end if
				
				$results_table .= $label_to_display."</a></th>"; // insert the linked name of the field in the <th>
			}
			else{
				$results_table .= $label_to_display."</th>"; // insert the  name of the field in the <th>
			} // end if

		} // end if
	} // end for
	$results_table .= "</tr>";

	$tr_results_class = 'tr_results_1';
	$td_controls_class = 'controls_1';

	// build the table body
	while ($records_row = fetch_row_db($res_records)){

		if ($tr_results_class === 'tr_results_1') {
			$td_controls_class = 'controls_2';
			$tr_results_class = 'tr_results_2';
		} // end if
		else {
			$td_controls_class = 'controls_1';
			$tr_results_class = 'tr_results_1';
		} // end else

		// set where clause for delete and update
		///////////////////////////////////////////
		if ($unique_field_name != "" && $unique_field_name != NULL){ // exists a unique number
			$where_field = $unique_field_name;
			$where_value = $records_row[$unique_field_name];
		} // end if
		///////////////////////////////////////////
		// end build where clause for delete and update

		//$results_table .= "<tr>";
		if ($enable_row_highlighting === 1) {
			$results_table .= "<tr class=\"".$tr_results_class."\" onmouseover=\"if (this.className!='tr_highlighted_onclick'){this.className='tr_highlighted_onmouseover'}\" onmouseout=\"if (this.className!='tr_highlighted_onclick'){this.className='".$tr_results_class."'}\" onclick=\"if (this.className == 'tr_highlighted_onclick'){ this.className='".$tr_results_class."';}else{ this.className='tr_highlighted_onclick';}\">";
		} // end if
		else {
			$results_table .= "<tr class=\"".$tr_results_class."\">";
		} // end else
		
		$results_table .= "<td class=\"".$td_controls_class."\">";

		$edit_link = '';
		$details_link = '';
		$delete_link = '';
		
		//elseif ($unique_field_name != "" and ($results_type == "search" or $results_type == "possible_duplication")){ // exists a unique number: edit, delete, details make sense
		if ($unique_field_name != "" && $unique_field_name != NULL and ($results_type == "search" or $results_type == "possible_duplication")){ // exists a unique number: edit, delete, details make sense
		
			if ($is_items_table === 1){
				
				// re-build the enabled features and permissions, the ones coming from global are the ones related to the master table, here we need the ones related to the items table
				
				if ($enable_granular_permissions === 1 && $enable_authentication === 1){

					$enabled_features_ar_2 = build_enabled_features_ar_2($table_name);
					
					$enable_edit = $enabled_features_ar_2['edit'];
					$enable_insert = $enabled_features_ar_2['insert'];
					$enable_delete = $enabled_features_ar_2['delete'];
					$enable_details = $enabled_features_ar_2['details'];
					
				}
				elseif ($enable_granular_permissions === 0 || $enable_authentication === 0){
					
					$enabled_features_ar = build_enabled_features_ar($table_name);
					
					$enable_edit = $enabled_features_ar['edit'];
					$enable_insert = $enabled_features_ar['insert'];
					$enable_delete = $enabled_features_ar['delete'];
					$enable_details = $enabled_features_ar['details'];
				}
				else{
					echo "<p>An error occured";
				} // end else
				
				
			} // end if

			if ($enable_edit == "1" && $master_table_function !== 'details'){ // display the edit icon 
			
				$edit_link = $dadabik_main_file."?table_name=".urlencode($table_name)."&function=edit&where_field=".urlencode($where_field)."&where_value=".urlencode($where_value);
				
				$results_table .= "<a class=\"onlyscreen\" target=\"_".$edit_target_window."\" href=\"".$dadabik_main_file."?table_name=".urlencode($table_name)."&function=edit&where_field=".urlencode($where_field)."&where_value=".urlencode($where_value);

				if ($is_items_table === 1) {
					$results_table .= "&master_table_name=".urlencode($master_table_name)."&master_table_function=".$master_table_function."&master_table_where_field=".urlencode($master_table_where_field)."&master_table_where_value=".urlencode(unescape($master_table_where_value))."&is_items_table=1";
					
					$edit_link .= "&master_table_name=".urlencode($master_table_name)."&master_table_function=".$master_table_function."&master_table_where_field=".urlencode($master_table_where_field)."&master_table_where_value=".urlencode(unescape($master_table_where_value))."&is_items_table=1";
				} // end if

				$results_table .= "\"><img border=\"0\" src=\"".$edit_icon."\" alt=\"".$submit_buttons_ar["edit"]."\" title=\"".$submit_buttons_ar["edit"]."\"></a>";
			} // end if

			if ($enable_delete == "1" && $master_table_function !== 'details'){ // display the delete icon
				$results_table .= "<a class=\"onlyscreen\"";
				if ( $ask_confirmation_delete == 1) {
					$results_table .= " onclick=\"if (!confirm('".str_replace('\'', '\\\'', $normal_messages_ar['confirm_delete?'])."')){ return false;}\"";
				}
				$results_table .= " href=\"".$dadabik_main_file."?table_name=".urlencode($table_name)."&function=delete";
				
				$delete_link = $dadabik_main_file."?table_name=".urlencode($table_name)."&function=delete";
				
				if ($is_items_table === 1) {
					$results_table .= "&master_table_name=".urlencode($master_table_name)."&master_table_function=".$master_table_function."&master_table_where_field=".urlencode($master_table_where_field)."&master_table_where_value=".urlencode(unescape($master_table_where_value))."&is_items_table=1";
					
					$delete_link .= "&master_table_name=".urlencode($master_table_name)."&master_table_function=".$master_table_function."&master_table_where_field=".urlencode($master_table_where_field)."&master_table_where_value=".urlencode(unescape($master_table_where_value))."&is_items_table=1";
				} // end if

				$results_table .= "&where_field=".urlencode($where_field)."&where_value=".urlencode($where_value)."\"><img border=\"0\" src=\"".$delete_icon."\" alt=\"".$submit_buttons_ar["delete"]."\" title=\"".$submit_buttons_ar["delete"]."\"></a>";
				
				$delete_link .= "&where_field=".urlencode($where_field)."&where_value=".urlencode($where_value);
				
			} // end if

			if ($enable_details == "1"){ // display the details icon
				
				$details_link = $dadabik_main_file."?table_name=".urlencode($table_name)."&function=details&where_field=".urlencode($where_field)."&where_value=".urlencode($where_value);
				
				$results_table .= "<a class=\"onlyscreen\" target=\"_".$edit_target_window."\" href=\"".$dadabik_main_file."?table_name=".urlencode($table_name)."&function=details&where_field=".urlencode($where_field)."&where_value=".urlencode($where_value);

				if ($is_items_table === 1) {
					$results_table .= "&master_table_name=".urlencode($master_table_name)."&master_table_function=".$master_table_function."&master_table_where_field=".urlencode($master_table_where_field)."&master_table_where_value=".urlencode(unescape($master_table_where_value))."&is_items_table=1";
					
					$details_link .= "&master_table_name=".urlencode($master_table_name)."&master_table_function=".$master_table_function."&master_table_where_field=".urlencode($master_table_where_field)."&master_table_where_value=".urlencode(unescape($master_table_where_value))."&is_items_table=1";
				} // end if
				
				$results_table .= "\"><img border=\"0\" src=\"".$details_icon."\" alt=\"".$submit_buttons_ar["details"]."\" title=\"".$submit_buttons_ar["details"]."\"></a>"; 
			} // end if

		} // end if
		$results_table .= "</td>";
		
		
		
		
		
		
		
		
		for ($i=0; $i<$count_temp; $i++){
			if ($fields_labels_ar[$i]["present_results_search_field"] == "1"){ // the user want to display the field in the search results page
				//$results_table .= "<td class=\"".$td_results_class."\">"; // start the cell
				$results_table .= "<td>"; // start the cell
				
				$field_name_temp = $fields_labels_ar[$i]["name_field"];
				$field_type = $fields_labels_ar[$i]["type_field"];
				$field_content = $fields_labels_ar[$i]["content_field"];
				$field_separator = $fields_labels_ar[$i]["separator_field"];

				// hack for mssql, an empty varchar ('') is returned as ' ' by the driver, see http://bugs.php.net/bug.php?id=26315
				// could be not set if it's a foreign key
				if (isset($records_row[$field_name_temp]) && $records_row[$field_name_temp] === ' ') {
					$records_row[$field_name_temp] = '';
				} // end if

				//$foreign_key_temp = $fields_labels_ar[$i]["foreign_key_field"];

				$field_values_ar = array(); // reset the array containing values to display, otherwise for each loop I have the previous values

				$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
				if ($primary_key_field_field != "" && $primary_key_field_field != NULL){
					
					$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
					$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
					$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
					$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
					$alias_suffix_field = $fields_labels_ar[$i]["alias_suffix_field"];
					$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
					
					// get the list of all the installed tables
					$tables_names_ar = build_tables_names_array(0);

					// if the linked table is installed I can get type content and separator of the linked field
					if (in_array($primary_key_table_field, $tables_names_ar)) {
						$linked_table_installed = 1;

						$fields_labels_linked_field_ar = build_fields_labels_array($prefix_internal_table.$primary_key_table_field, "1");
					} // end if
					else {
						$linked_table_installed = 0;
					} // end else

					for ($j=0;$j<count($linked_fields_ar);$j++) {
						////*$field_values_ar[$j] = $records_row[$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
						$field_values_ar[$j] = $records_row[$primary_key_table_field.$alias_prefix.$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
						
					} // end for
				}
				else{
					//$field_value = $records_row[$field_name_temp];
					$field_values_ar[0] = $records_row[$field_name_temp];

				}

				// enterprise
				// pro
				if ($fields_labels_ar[$i]['custom_formatting_function_field'] !== ''){
					$field_to_display = implode(' ', $field_values_ar);
					$linked_field_type = '';
					$linked_field_content = '';
					$linked_field_separator = '';
					$field_to_display = get_field_correct_displaying($field_to_display, $linked_field_type, $linked_field_content, $linked_field_separator, "results_table", $fields_labels_ar[$i], $template_infos_ar);
					$results_table .= $field_to_display;
					
				}
				else{
				// end enterprise
				// end pro
				
				
					$count_temp_2 = count($field_values_ar);
					for ($j=0; $j<$count_temp_2; $j++) {
	
						// if it's a linked field and the linked table is installed, get the correct $field_type $field_content $field_separator
						if ($primary_key_field_field != "" && $primary_key_field_field != NULL && $linked_table_installed === 1){
	
							foreach ($fields_labels_linked_field_ar as $fields_labels_linked_field_ar_element){
								if ($fields_labels_linked_field_ar_element['name_field'] === $linked_fields_ar[$j]) {
									$linked_field_type = $fields_labels_linked_field_ar_element['type_field'];
									$linked_field_content = $fields_labels_linked_field_ar_element['content_field'];
									$linked_field_separator = $fields_labels_linked_field_ar_element['separator_field'];
									
								} // end if
							} // end foreach
	
							reset($fields_labels_linked_field_ar);
							
							$field_to_display = get_field_correct_displaying($field_values_ar[$j], $linked_field_type, $linked_field_content, $linked_field_separator, "results_table", $fields_labels_ar[$i], $template_infos_ar); // get the correct display mode for the field
						} // end if
						else {
							$field_to_display = get_field_correct_displaying($field_values_ar[$j], $field_type, $field_content, $field_separator, "results_table", $fields_labels_ar[$i], $template_infos_ar); // get the correct display mode for the field
						} // end else
	
						if ( $field_to_display == "") {
							$field_to_display .= "&nbsp;";
						}
	
						$results_table .= $field_to_display."&nbsp;"; // at the field value to the table
					}
					$results_table = substr_custom($results_table, 0, -6); // delete the last &nbsp;
				
				// enterprise
				// pro
				}
				// end enterprise
				// end pro
				
				
				
				$results_table .= "</td>"; // end the cell
				
				
				
				$fields_to_display_ar[$field_name_temp] = $field_to_display;

				
				
				
				
			} // end if
		} // end for
		
		if (is_array($template_infos_ar) && $template_infos_ar['enable_template_table'] == '1'){
			
			// performance
			foreach ($fields_to_display_ar as $fields_to_display_ar_el){
				if (strpos_custom($fields_to_display_ar_el, 'dadabik_field') === false && strpos_custom($fields_to_display_ar_el, 'dadabik_link') === false){
				}
				else{
					echo 'Error';
					exit;
					break;
				}
			}
			
			$template_results_table .= parse_template($template_infos_ar);
			
		}
				
		
		$results_table .= "</tr>";
	} // end while
	$results_table .= "</table>";
	
	if ($template_infos_ar['enable_template_table'] == '1'){
		return $template_results_table;
	}
	else{
		return $results_table;
	}

} // end function build_results_table


function parse_template($template_infos_ar)
{



/*$row = preg_replace_callback(
    '/\^([^\^]+)\^/', create_function ('$m', 'global $fields_to_display_ar; return $fields_to_display_ar{$m[1]};'),
    $template_infos_ar['template_table']
);
*/
$row = preg_replace_callback(
    '/dadabik_field (.+?) dadabik_field/', create_function ('$m', 'global $fields_to_display_ar; if (isset($fields_to_display_ar{$m[1]})) return $fields_to_display_ar{$m[1]};'),
    $template_infos_ar['template_table']
); // isset because if the field has not permissions is not set

/*
$row = preg_replace_callback(
    '/\@([^\@]+)\@/', create_function ('$m', 'global $edit_link, $details_link, $delete_link; return ${$m[1]};'),
    $row
);
*/
$row = preg_replace_callback(
    '/dadabik_link (.+?) dadabik_link/', create_function ('$m', 'global $edit_link, $details_link, $delete_link; return ${$m[1]};'),
    $row
);


$row = preg_replace_callback(
    '/dadabik_hide (.+?) dadabik_hide/', create_function ('$m', 'global $edit_link, $details_link, $delete_link ; $temp = $m[1]; $temp_2 = preg_match("/dadabik_groups (.+?) dadabik_groups/", $temp, $m2); $temp_3_ar = explode("|", $m2[1]); if (in_array($_SESSION["logged_user_infos_ar"]["id_group"], $temp_3_ar)) { return "";} else { return substr_custom($m[1], strlen_custom($m2[0])); }'),
    $row
);

    return $row;

}



function build_csv($res_records, $fields_labels_ar)
// build a csv, starting from a recordset
// input: $res_record, the recordset, $fields_labels_ar
{
	global $csv_separator, $alias_prefix;
	$csv = "";
	$count_temp = count($fields_labels_ar);

	// write heading
	for ($i=0; $i<$count_temp; $i++) {
		if ( $fields_labels_ar[$i]["present_results_search_field"] == "1") {
			$csv .= "\"".str_replace("\"", "\"\"", $fields_labels_ar[$i]["label_field"])."\"".$csv_separator;
		}
	}
	$csv = substr_custom($csv, 0, -1); // delete the last ","
	$csv .= "\n";

	// write other rows
	while ($records_row = fetch_row_db($res_records)) {
		for ($i=0; $i<$count_temp; $i++) {
			if ( $fields_labels_ar[$i]["present_results_search_field"] == "1") {

				$field_name_temp = $fields_labels_ar[$i]["name_field"];
				$field_type = $fields_labels_ar[$i]["type_field"];
				$field_content = $fields_labels_ar[$i]["content_field"];
				$field_separator = $fields_labels_ar[$i]["separator_field"];

				// hack for mssql, an empty varchar ('') is returned as ' ' by the driver, see http://bugs.php.net/bug.php?id=26315
				// could be not set if it's a foreign key
				if (isset($records_row[$field_name_temp]) && $records_row[$field_name_temp] === ' ') {
					$records_row[$field_name_temp] = '';
				} // end if
	
				$field_values_ar = array(); // reset the array containing values to display, otherwise for each loop I have the previous values

				$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
				if ($primary_key_field_field != ""){
					
					$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
					$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
					$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
					$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
					$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
					$alias_suffix_field = $fields_labels_ar[$i]["alias_suffix_field"];
					
					
					for ($j=0;$j<count($linked_fields_ar);$j++) {
						////*$field_values_ar[$j] .= $records_row[$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
						$field_values_ar[$j] = $records_row[$primary_key_table_field.$alias_prefix.$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
					} // end for

					/*
					$linked_field_values_ar = build_linked_field_values_ar($row_records[$field_name_temp], $primary_key_field_field, $primary_key_table_field, $primary_key_db_field, $linked_fields_ar);
					*/
					
					/*
					if (substr_custom($foreign_key_temp, 0, 4) == "SQL:"){
						$sql = substr_custom($foreign_key_temp, 4, strlen_custom($foreign_key_temp)-4);
					} // end if
					else{
					*/

					//$field_values_ar = $linked_field_values_ar;
				}
				else{
					$field_values_ar[0] = $records_row[$field_name_temp];
				}
				$csv .= "\"";

				$count_temp_2 = count($field_values_ar);
				for ($j=0; $j<$count_temp_2; $j++) {
					
					$field_to_display = get_field_correct_csv_displaying($field_values_ar[$j], $field_type, $field_content, $field_separator, $fields_labels_ar[$i]);

					$csv .= str_replace("\"", "\"\"", $field_to_display)." ";
				}
				$csv = substr_custom($csv, 0, -1); // delete the last space
			$csv .= "\"".$csv_separator;
			}
		} // end for
		$csv = substr_custom($csv, 0, -1); // delete the last ","
		$csv .= "\n";
	}
	
	return $csv;
} // end function build_csv

function build_details_table($fields_labels_ar, $res_details, $table_name, $is_items_table, $just_inserted, $master_table_name, $master_table_function, $master_table_where_field, $master_table_where_value)
// goal: build an html table with details of a record
// input: $fields_labels_ar $res_details (the result of the query)
// ouptut: $details_table, the html table
{
	global $conn, $quote, $alias_prefix, $prefix_internal_table, $submit_buttons_ar, $normal_messages_ar, $dadabik_main_file;

	// build the table
	$details_table = "";
	
	$max_number_fields_row = get_max_number_fields_row($fields_labels_ar, 'present_details_form_field', 'details_new_line_after_field');

	$details_table .= "<table cellpadding=\"5\">";

	while ($details_row = fetch_row_db($res_details)){ // should be just one
	
		$open_new_line = 1;
    
		$fields_in_row_counter = 0;

		$count_temp = count($fields_labels_ar);
		for ($i=0; $i<$count_temp; $i++){
			if ($fields_labels_ar[$i]["present_details_form_field"] == "1"){
				$field_name_temp = $fields_labels_ar[$i]["name_field"];

				// hack for mssql, an empty varchar ('') is returned as ' ' by the driver, see http://bugs.php.net/bug.php?id=26315
				// could be not set if it's a foreign key
				if (isset($details_row[$field_name_temp]) && $details_row[$field_name_temp] === ' ') {
					$details_row[$field_name_temp] = '';
				} // end if

				$field_values_ar = array(); // reset the array containing values to display, otherwise for each loop if I don't call build_linked_field_values_ar I have the previous values

				$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
				if ($primary_key_field_field != ""){
					$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
					$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
					$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
					$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
					$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
					$alias_suffix_field = $fields_labels_ar[$i]["alias_suffix_field"];

					// get the list of all the installed tables
					$tables_names_ar = build_tables_names_array(0);

					// if the linked table is installed I can get type content and separator of the linked field
					if (in_array($primary_key_table_field, $tables_names_ar)) {
						$linked_table_installed = 1;

						$fields_labels_linked_field_ar = build_fields_labels_array($prefix_internal_table.$primary_key_table_field, "1");
					} // end if
					else {
						$linked_table_installed = 0;
					} // end else

					for ($j=0;$j<count($linked_fields_ar);$j++) {
						////*$field_values_ar[$j] = $details_row[$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
						$field_values_ar[$j] = $details_row[$primary_key_table_field.$alias_prefix.$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];

					} // end for
					
					/*
					$linked_field_values_ar = build_linked_field_values_ar($details_row[$field_name_temp], $primary_key_field_field, $primary_key_table_field, $primary_key_db_field, $linked_fields_ar);
					*/
					/*
					if (substr_custom($foreign_key_temp, 0, 4) == "SQL:"){
						$sql = substr_custom($foreign_key_temp, 4, strlen_custom($foreign_key_temp)-4);
					} // end if
					else{
					*/
					//$field_values_ar = $linked_field_values_ar;
				}
				else{
					$field_values_ar[0] = $details_row[$field_name_temp];
				}

				$count_temp_2 = count($field_values_ar);
				
				if ($open_new_line === 1){
					$details_table .= "<tr>";
				}
				
				$fields_in_row_counter++;
            
				$details_table .= "<td class=\"td_label_details\"><b>".$fields_labels_ar[$i]["label_field"]."</b></td><td class=\"td_value_details\">";
				
				
				// enterprise
				// pro
				if ($fields_labels_ar[$i]['custom_formatting_function_field'] !== ''){
					$field_to_display = implode(' ', $field_values_ar);
					$linked_field_type = '';
					$linked_field_content = '';
					$linked_field_separator = '';
					$details_table .= get_field_correct_displaying($field_to_display, $linked_field_type, $linked_field_content, $linked_field_separator, "details_table", $fields_labels_ar[$i]);
				}
				else{
				// end enterprise
				// end pro
				
					for ($j=0; $j<$count_temp_2; $j++) {
	
						// if it's a linked field and the linked table is installed, get the correct $field_type $field_content $field_separator
						if ($primary_key_field_field != "" && $primary_key_field_field != NULL && $linked_table_installed === 1){
	
							foreach ($fields_labels_linked_field_ar as $fields_labels_linked_field_ar_element){
								if ($fields_labels_linked_field_ar_element['name_field'] === $linked_fields_ar[$j]) {
									$linked_field_type = $fields_labels_linked_field_ar_element['type_field'];
									$linked_field_content = $fields_labels_linked_field_ar_element['content_field'];
									$linked_field_separator = $fields_labels_linked_field_ar_element['separator_field'];
								} // end if
							} // end foreach
	
							reset($fields_labels_linked_field_ar);
							
							$field_to_display = get_field_correct_displaying($field_values_ar[$j], $linked_field_type, $linked_field_content, $linked_field_separator, "details_table", $fields_labels_ar[$i]); // get the correct display mode for the field
						} // end if
						else {
							$field_to_display = get_field_correct_displaying($field_values_ar[$j], $fields_labels_ar[$i]["type_field"], $fields_labels_ar[$i]["content_field"], $fields_labels_ar[$i]["separator_field"], "details_table", $fields_labels_ar[$i]); // get the correct display mode for the field
						} // end else
	
						$details_table .= $field_to_display."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; // at the field value to the table
					}
					$details_table = substr_custom($details_table, 0, -6); // delete the last &nbsp;
					$details_table .= "</td>";
					
				// enterprise
				// pro
				} // end else
					
				// end enterprise
				// end pro
				
				
				
				
				
				
			if ($fields_labels_ar[$i]['details_new_line_after_field'] === '1'){
                $open_new_line = 1;
               
                for ($j=0;$j<($max_number_fields_row-$fields_in_row_counter);$j++){
                	$details_table .= '<td class="td_label_details"></td><td class="td_value_details"></td>';
                    
                }
                $details_table .= "</tr>";
                 $fields_in_row_counter = 0;
                
            }
            else{
                $open_new_line = 0;
            }
				
				
				
				
				
				//$field_to_display = get_field_correct_displaying($details_row[$field_name_temp], $fields_labels_ar[$i]["type_field"], $fields_labels_ar[$i]["content_field"], $fields_labels_ar[$i]["separator_field"], "details_table"); // get the correct display mode for the field

				//$details_table .= "<tr><td class=\"td_label_details\"><b>".$fields_labels_ar[$i]["label_field"]."</b></td><td class=\"td_value_details\">".$field_to_display."</td></tr>";
			} // end if
		} // end for
	} // end while

	$details_table .= "</table>";
	
	if ($is_items_table === 1 && $just_inserted === 0){
		
		$details_table .= '<br/><br/><form class="css_form"><input type="button" class="button_form" value="'.$normal_messages_ar['back_to_the_main_table'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?table_name='.urlencode($master_table_name).'&function='.$master_table_function.'&where_field='.urlencode($master_table_where_field).'&where_value='.urlencode(unescape($master_table_where_value)).'\'"></form>';
	}
	elseif ($just_inserted === 0){
	
		$details_table .= '<br/><br/><form class="css_form"><input type="button" class="button_form" value="'.$submit_buttons_ar['go_back'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?function=search&table_name='.urlencode($table_name).'\'"></form>';
	}

	return $details_table;
} // end function build_details_table

function build_insert_update_notice_email_record_details($fields_labels_ar, $res_details)
// goal: build the detail information about the record just inserted or updated, to use in the insert or update notice email
// input: $fields_labels_ar $res_details (the recordset produced by the SELECT query on the record just inserted or just updated)
// ouptut: $details_table, the html table
{
	global $conn, $quote, $alias_prefix, $normal_messages_ar;

	$notice_email = '';

	$count_temp = count($fields_labels_ar);

	while ($details_row = fetch_row_db($res_details)){ // should be just one

		$notice_email .= $normal_messages_ar['details_of_record']."\n";
		$notice_email .= "--------------------------------------------\n\n";

		for ($i=0; $i<$count_temp; $i++){

			if ($fields_labels_ar[$i]['present_details_form_field'] === '1'){
				$field_name_temp = $fields_labels_ar[$i]['name_field'];

				$field_values_ar = array(); // reset the array containing values to display, otherwise for each loop if I don't call build_linked_field_values_ar I have the previous values

				$primary_key_field_field = $fields_labels_ar[$i]['primary_key_field_field'];

				if ($primary_key_field_field != ''){ // it is a foreign key field

					$primary_key_table_field = $fields_labels_ar[$i]['primary_key_table_field'];
					$linked_fields_field = $fields_labels_ar[$i]['linked_fields_field'];
					$linked_fields_ar = explode($fields_labels_ar[$i]['separator_field'], $linked_fields_field);
					$alias_suffix_field = $fields_labels_ar[$i]['alias_suffix_field'];

					for ($j=0; $j<count($linked_fields_ar); $j++) {
						$field_values_ar[$j] = $details_row[$primary_key_table_field.$alias_prefix.$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
					} // end for
				} // end if
				else{
					$field_values_ar[0] = $details_row[$field_name_temp];
				} // end else

				$count_temp_2 = count($field_values_ar);

				$notice_email .= $fields_labels_ar[$i]["label_field"].':'; // add the label

				for ($j=0; $j<$count_temp_2; $j++) {
					$field_to_display = get_field_correct_displaying($field_values_ar[$j], $fields_labels_ar[$i]['type_field'], $fields_labels_ar[$i]['content_field'], $fields_labels_ar[$i]['separator_field'], 'plain_text', $fields_labels_ar[$i]); // get the correct display mode for the field

					$notice_email .= ' '.$field_to_display; // add the field value
				} // end for

				$notice_email .= "\n"; // add the return

			} // end if
		} // end for

		$notice_email .= "\n\n--------------------------------------------\nPowered by DaDaBIK - http://www.dadabik.org/";

	} // end while

	return $notice_email;
} // end function build_insert_update_notice_email_record_details()

function build_navigation_tool($where_clause, $pages_number, $page, $action, $name_mailing, $order, $order_type, $master_table_name, $master_table_function, $master_table_where_field , $master_table_where_value, $is_items_table)
// goal: build a set of link to go forward and back in the result pages
// input: $where_clause, $pages_number (total number of pages), $page (the current page 0....n), $action, the action page (e.g. index.php or mail.php), $name_mailing, the name of the current mailing, $order, the field used to order the results, $order_type, $master_table_name, $master_table_function, $master_table_where_field , $master_table_where_value, $is_items_table
// output: $navigation_tool, the html navigation tool
{
	global $table_name, $quote;

	if ($action == "mail.php"){
		$function = "check_form";
	} // end if
	else{
		$function = "search";
	} // end elseif
	$navigation_tool = "";

	$page_group = (int)($page/10); // which group? (from 0......n) e.g. page 12 is in the page_group 1 
	$total_groups = ((int)(($pages_number-1)/10))+1; // how many groups? e.g. with 32 pages 4 groups
	$start_page = $page_group*10; // the navigation tool start with $start_page, end with $end_page
	if ($start_page+10 > $pages_number){
		$end_page = $pages_number;
	} // end if
	else{
		$end_page = $start_page+10;
	} // end else

	if ($is_items_table === 1) {
		$variables_to_pass = 'table_name='. urlencode($master_table_name).'&function='.$master_table_function.'&items_table_name='.urlencode($table_name).'&where_field='.urlencode($master_table_where_field).'&where_value='.urlencode(unescape($master_table_where_value)).'&where_clause='.urlencode($where_clause).'&order='.urlencode($order).'&order_type='.urlencode($order_type).'&store_session_table_infos_for_items_table=1';
	} // end if
	else {
		$variables_to_pass = 'table_name='. urlencode($table_name).'&function='.$function.'&where_clause='.urlencode($where_clause).'&order='.urlencode($order).'&order_type='.urlencode($order_type);
	} // end else
	
	if ($page_group > 1){
		$navigation_tool .= "<a class=\"navig\" href=\"".$action."?".$variables_to_pass."&page=0\" title=\"1\">&lt;&lt;</a> ";
	} // end if
	if ($page_group > 0){
		$navigation_tool .= "<a class=\"navig\" href=\"".$action."?".$variables_to_pass."&page=".((($page_group-1)*10)+9)."\" title=\"".((($page_group-1)*10)+10)."\">&lt;</a> ";
	} // end if

	for($i=$start_page; $i<$end_page; $i++){
		if ($i != $page){
			$navigation_tool .= "<a class=\"navig\" href=\"".$action."?".$variables_to_pass."&page=".$i."\">".($i+1)."</a> ";
		} // end if
		else{
			$navigation_tool .= "<font class=\"navig\">".($i+1)."</font> ";
		} //end else
	} // end for

	if(($page_group+1) < ($total_groups)){
		$navigation_tool .= "<a class=\"navig\" href=\"".$action."?".$variables_to_pass."&page=".(($page_group+1)*10)."\" title=\"".((($page_group+1)*10)+1)."\">&gt;</a> ";
	} // end elseif
	if (($page_group+1) < ($total_groups-1)){
		$navigation_tool .= "<a class=\"navig\" href=\"".$action."?".$variables_to_pass."&page=".($pages_number-1)."\" title=\"".$pages_number."\">&gt;&gt;</a> ";
	} // end if
	return $navigation_tool;
} // end function build_navigation_tool

function build_are_you_sure_send_form($name_mailing)
// goal: build a form with a confirmation message and buttons yes/no before sending a mailing
// input: $mailing_name, the name of the mailing
// output: $are_you_sure_send_form, the form with the buttons
// global $submit_buttons_ar, the array containing the value of submit buttons
{
global $submit_buttons_ar;

$are_you_sure_send_form = "";

$are_you_sure_send_form .= "<table><tr>";
$are_you_sure_send_form .= "<td><form method=\"GET\" action=\"mail.php\"><input type=\"hidden\" name=\"function\" value=\"send\"><input type=\"hidden\" name=\"send_sure\" value=\"1\"><input type=\"hidden\" name=\"name_mailing\" value=\"".$name_mailing."\"><input type=\"submit\" value=\"".$submit_buttons_ar["yes"]."\"></form></td>";
$are_you_sure_send_form .= "<td><form><input type=\"button\" onclick=\"javascript:history.back(-1)\" value=\"".$submit_buttons_ar["no"]."\"></form></td>";
$are_you_sure_send_form .= "</tr></table>";

return $are_you_sure_send_form;
} // end function build_are_you_sure_send_form

/*
function build_remove_all_form($name_mailing)
// goal: build a form with a button remove all for the check mailing page
// input: $name_mailing, the name of the mailing
// output: $remove_all_form, the form
// global: $submit_buttons_ar, the array containing the value of submit buttons
{
	global $submit_buttons_ar;
	$remove_all_form = "<form action=\"mail.php\" method=\"post\">";
	$remove_all_form .= "<input type=\"hidden\" name=\"remove_type\" value=\"all\">";
	$remove_all_form .= "<input type=\"hidden\" name=\"name_mailing\" value=\"$name_mailing\">";
	$remove_all_form .= "<input type=\"hidden\" name=\"function\" value=\"remove_contact\">";
	$remove_all_form .= "<input type=\"submit\" value=\"".$submit_buttons_ar["remove_all_from_mailing"]."\">";
	$remove_all_form .= "</form>";
	return $remove_all_form;
} // end function build_remove_all_form
*/

function delete_record ($table_name, $where_field, $where_value, $fields_labels_ar)
// goal: delete one record
{
	global $conn, $quote, $upload_directory, $delete_files_when_delete_record, $groups_table_name, $users_table_name, $users_table_id_group_field;
	
	$sql_delete = "DELETE FROM ".$quote.$table_name.$quote." WHERE ".$quote.$where_field.$quote." = '".$where_value."'";
	display_sql($sql_delete);
	
	if ($delete_files_when_delete_record === 1){
	
		$fields_containing_files_ar = array(); // the names of the fields which contain files, if any
	
		$files_to_delete_ar = array(); // here the name of the uploaded files to delete, if any
	
		foreach ($fields_labels_ar as $fields_labels_ar_element){
			if ($fields_labels_ar_element['type_field'] === 'generic_file' || $fields_labels_ar_element['type_field'] === 'image_file'){ // check if the field is a file field
				$fields_containing_files_ar[] = $fields_labels_ar_element['name_field'];
			} // end if
		} // end foreach
	
		$fields_count = count($fields_containing_files_ar);
		
		if ($fields_count > 0){
			$sql = "SELECT ";
			foreach ($fields_containing_files_ar as 		$fields_containing_files_ar_element){
				$sql .= $fields_containing_files_ar_element.", ";
			} // end foreach
			$sql = substr_custom($sql, 0, -2);
			$sql .= " FROM ".$quote.$table_name.$quote." WHERE ".$quote.$where_field.$quote." = '".$where_value."'";
		
			// execute the delete query
			$res = execute_db($sql, $conn);
		
			$row = fetch_row_db($res);
		
			for ($i=0; $i<$fields_count; $i++){
				$files_to_delete_ar[] = $row[$i];
			} // end for
	
			foreach($files_to_delete_ar as $files_to_delete_ar_element){				
				if (is_file($upload_directory.$files_to_delete_ar_element)){ // for security
					unlink($upload_directory.$files_to_delete_ar_element); // delete the file
				} // end if
			} // end foreach
		
		} // end if
		
	} // end if
	
	begin_trans_db();

	// execute the delete query
	$res_delete = execute_db($sql_delete, $conn);
	
	
	if ($table_name === $groups_table_name){
		$sql_delete = "DELETE FROM ".$quote.$users_table_name.$quote." WHERE ".$quote.$users_table_id_group_field.$quote." = ".$where_value;
		
		// execute the delete query
		$res_delete = execute_db($sql_delete, $conn);
	}
	
	complete_trans_db();
	

} // end function delete_record


function delete_multiple_records ($table_name, $where_clause, $ID_user_field_name, $fields_labels_ar)
// goal: delete a group of record according to a where clause
// input: $table_name, $where_clause, $ID_user_field_name (if it is not false, delete only the records that the current user owns), $fields_labels_ar
{
	global $conn, $quote, $current_user, $enable_authentication, $enable_delete_authorization, $upload_directory, $delete_files_when_delete_record;

	$sql_delete = '';
	$sql_delete .= "DELETE FROM ".$quote.$table_name.$quote;
	if ($where_clause !== '') {
		$sql_delete .= " WHERE ".$where_clause;
	} // end if
	display_sql($sql_delete);
	
	if ($delete_files_when_delete_record === 1){
	
		$fields_containing_files_ar = array(); // the names of the fields which contain files, if any
	
		$files_to_delete_ar = array(); // here the name of the uploaded files to delete, if any
	
		foreach ($fields_labels_ar as $fields_labels_ar_element){
			if ($fields_labels_ar_element['type_field'] === 'generic_file' || $fields_labels_ar_element['type_field'] === 'image_file'){ // check if the field is a file field
				$fields_containing_files_ar[] = $fields_labels_ar_element['name_field'];
			} // end if
		} // end foreach
	
		$fields_count = count($fields_containing_files_ar);
		
		if ($fields_count > 0){
			$sql = "SELECT ";
			foreach ($fields_containing_files_ar as 		$fields_containing_files_ar_element){
				$sql .= $fields_containing_files_ar_element.", ";
			} // end foreach
			$sql = substr_custom($sql, 0, -2);
			$sql .= " FROM ".$quote.$table_name.$quote." WHERE ".$where_clause;
		
			// execute the delete query
			$res = execute_db($sql, $conn);
		
			while ($row = fetch_row_db($res)){;
		
				for ($i=0; $i<$fields_count; $i++){
					$files_to_delete_ar[] = $row[$i];
				} // end for
				
			} // end while
	
			foreach($files_to_delete_ar as $files_to_delete_ar_element){
				if (is_file($upload_directory.$files_to_delete_ar_element)){ // for security
					unlink($upload_directory.$files_to_delete_ar_element); // delete the file
				} // end if
			} // end foreach
		
		} // end if
		
	} // end if

	// execute the delete query
	$res_delete = execute_db($sql_delete, $conn);

} // end function delete_multiple_records

function required_field_present($fields_labels_ar)
// goal: check if there are at least one required field
// input: $fields_labels_ar
// output: true or false
{
	
	$i=0;
	$found = 0;
	$count_temp = count($fields_labels_ar);
	while ($i<$count_temp && $found == 0) {
		if ( $fields_labels_ar[$i]["required_field"] == "1") {
			$found = 1;
		}
		$i++;
	}
	if ( $found == 1 ){
		return true;
	}
	else{
		return false;
	}
} // end function required_field_present


function create_internal_table($table_internal_name)
// goal: drop (if present) the old internal table and create the new one.
// input: $table_internal_name
{
	global $conn, $dbms_type, $prefix_internal_table, $autoincrement_word;
	
	if (true || $dbms_type === 'sqlite' || $dbms_type === 'sqlite2'){
	
		drop_table_db($conn, $table_internal_name);
	
		$fields = "
		id_field ".$autoincrement_word.",
		name_field varchar(50),
		label_field varchar(500) DEFAULT '' NOT NULL,
		type_field varchar(50) DEFAULT 'text' NOT NULL,
		custom_validation_function_field varchar(500) default '' NOT NULL,
		custom_formatting_function_field varchar(500) default '' NOT NULL,
		custom_csv_formatting_function_field varchar(500) default '' NOT NULL,
		content_field varchar(50) DEFAULT 'alphanumeric' NOT NULL,
		present_search_form_field varchar(1) DEFAULT '1' NOT NULL,
		present_results_search_field varchar(1) DEFAULT '1' NOT NULL,
		present_details_form_field varchar(1) DEFAULT '1' NOT NULL,
		present_insert_form_field varchar(1) DEFAULT '1' NOT NULL,
		present_edit_form_field varchar(1) DEFAULT '1' NOT NULL,
		present_filter_form_field varchar(1) DEFAULT '0' NOT NULL,
		present_ext_update_form_field varchar(1) DEFAULT '1' NOT NULL,
		required_field varchar(1) DEFAULT '0' NOT NULL,
		check_duplicated_insert_field varchar(1) DEFAULT '0' NOT NULL,
		other_choices_field varchar(1) DEFAULT '0' NOT NULL,
		select_options_field varchar(500) DEFAULT '' NOT NULL,
		primary_key_field_field varchar(500) DEFAULT '' NOT NULL,
		primary_key_table_field varchar(500) DEFAULT '' NOT NULL,
		primary_key_db_field varchar(50) DEFAULT '' NOT NULL,
		linked_fields_field varchar(500) DEFAULT '' NOT NULL,
		linked_fields_order_by_field varchar(500) DEFAULT '' NOT NULL,
		linked_fields_order_type_field varchar(100) DEFAULT '' NOT NULL,
		where_clause_field varchar(500) DEFAULT '' NOT NULL,
		select_type_field varchar(200) DEFAULT 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty' NOT NULL,
		items_table_names_field varchar(500) DEFAULT '' NOT NULL,
		items_table_fk_field_names_field varchar(500) DEFAULT '' NOT NULL,
		prefix_field varchar(500) DEFAULT '' NOT NULL,
		default_value_field varchar(500) DEFAULT '' NOT NULL,
		width_field varchar(500) DEFAULT '' NOT NULL,
		height_field varchar(500) DEFAULT '' NOT NULL,
		maxlength_field varchar(500) DEFAULT '100' NOT NULL,
		hint_insert_field varchar(500) DEFAULT '' NOT NULL,
		tooltip_field varchar(500) DEFAULT '' NOT NULL,
		order_form_field integer NOT NULL,
		separator_field varchar(500) DEFAULT '~' NOT NULL,
		details_new_line_after_field varchar(1) default '1' NOT NULL,
		search_new_line_after_field varchar(1) default '1' NOT NULL,
		insert_new_line_after_field varchar(1) default '1' NOT NULL,
		edit_new_line_after_field varchar(1) default '1' NOT NULL,
		insert_separator_before_field varchar(500) default '' NOT NULL,
		edit_separator_before_field varchar(500) default '' NOT NULL,
		search_separator_before_field varchar(500) default '' NOT NULL,
		details_separator_before_field varchar(500) default '' NOT NULL,
		table_name varchar(255) NOT NULL";
		
		if ($dbms_type === 'postgres'){
  			$fields .= ",
  			PRIMARY KEY (\"id_field\")";
  		}
  		
		$fields .= ")
	";

		
		create_table_db($conn, $prefix_internal_table.'forms', $fields);
	}
	else{
	
		$data_dictionary = NewDataDictionary($conn);

		drop_table_db($conn, $data_dictionary, $table_internal_name);
		
		$fields = "
		id_field I NOTNULL PRIMARY AUTOINCREMENT,
		name_field C(50),
		label_field C(255) DEFAULT '' NOTNULL,
		type_field C(50) DEFAULT 'text' NOTNULL,
		content_field C(50) DEFAULT 'alphanumeric' NOTNULL,
		present_search_form_field C(1) DEFAULT '1' NOTNULL,
		present_results_search_field C(1) DEFAULT '1' NOTNULL,
		present_details_form_field C(1) DEFAULT '1' NOTNULL,
		present_insert_form_field C(1) DEFAULT '1' NOTNULL,
		present_edit_form_field C(1) DEFAULT '1' NOTNULL,
		present_filter_form_field C(1) DEFAULT '0' NOTNULL,
		present_ext_update_form_field C(1) DEFAULT '1' NOTNULL,
		required_field C(1) DEFAULT '0' NOTNULL,
		check_duplicated_insert_field C(1) DEFAULT '0' NOTNULL,
		other_choices_field C(1) DEFAULT '0' NOTNULL,
		select_options_field X DEFAULT '' NOTNULL,
		primary_key_field_field C(255) DEFAULT '' NOTNULL,
		primary_key_table_field C(255) DEFAULT '' NOTNULL,
		primary_key_db_field C(50) DEFAULT '' NOTNULL,
		linked_fields_field X DEFAULT '' NOTNULL,
		linked_fields_order_by_field X DEFAULT '' NOTNULL,
		linked_fields_order_type_field X DEFAULT '' NOTNULL,
		select_type_field C(100) DEFAULT 'is_equal/contains/starts_with/ends_with/greater_than/less_then/is_null/is_empty' NOTNULL,
		items_table_names_field C(255) DEFAULT '' NOTNULL,
		items_table_fk_field_names_field C(255) DEFAULT '' NOTNULL,
		prefix_field X DEFAULT '' NOTNULL,
		default_value_field DEFAULT '' X NOTNULL,
		width_field C(5) DEFAULT '' NOTNULL,
		height_field C(5) DEFAULT '' NOTNULL,
		maxlength_field C(5) DEFAULT '100' NOTNULL,
		hint_insert_field C(255) DEFAULT '' NOTNULL,
		order_form_field I NOTNULL,
		separator_field C(2) DEFAULT '~' NOTNULL,
		table_name C(255) NOTNULL,
		details_new_line_after_field C(1) default '1' NOTNULL,
		search_new_line_after_field C(1) default '1' NOTNULL,
		insert_new_line_after_field C(1) default '1' NOTNULL
		)
		";
		
		create_table_db($conn, $data_dictionary, $prefix_internal_table.'forms', $fields);
	}

} // end function create_internal_table

function create_table_list_table()
// goal: drop (if present) the old table list and create the new one.
{
	global $conn, $table_list_name, $dbms_type;
	
	if (true || $dbms_type === 'sqlite' || $dbms_type === 'sqlite2'){
	
		drop_table_db($conn, $table_list_name);
	
		$fields = "
		name_table varchar(255) NOT NULL default '',
		allowed_table varchar(1) NOT NULL default '',
		enable_insert_table varchar(1) NOT NULL default '',
		enable_edit_table varchar(1) NOT NULL default '',
		enable_delete_table varchar(1) NOT NULL default '',
		enable_details_table varchar(1) NOT NULL default '',
		enable_list_table varchar(1) NOT NULL default '',
		enable_delete_authorization_table varchar(1) NOT NULL default '0',
		enable_update_authorization_table varchar(1) NOT NULL default '0',
		enable_browse_authorization_table varchar(1) NOT NULL default '0',
		alias_table varchar(255) NOT NULL default '',
		enable_template_table varchar(1) NOT NULL default '0',
		template_table text NOT NULL default '',
		pk_field_table varchar(500) NOT NULL default ''";
		
		if ($dbms_type === 'postgres'){
  			$fields .= ",
  			PRIMARY KEY (\"name_table\")";
  		}
  		
		$fields .= ")
		";
		
		create_table_db($conn, $table_list_name, $fields);
	}
	else{

		$data_dictionary = NewDataDictionary($conn);

		drop_table_db($conn, $data_dictionary, $table_list_name);
	
	
		$fields = "
		name_table C(255) NOTNULL default '' PRIMARY,
		allowed_table C(1) NOTNULL default '',
		enable_insert_table C(1) NOTNULL default '',
		enable_edit_table C(1) NOTNULL default '',
		enable_delete_table C(1) NOTNULL default '',
		enable_details_table C(1) NOTNULL default '',
		enable_list_table C(1) NOTNULL default '',
		alias_table C(255) NOTNULL default '',
		enable_template_table c(1) NOT NULL default '0',
		template_table X NOT NULL default ''
		)
		";
		
		create_table_db($conn, $data_dictionary, $table_list_name, $fields);
	}

} // end function create_table_list_table

function create_users_table()
// goal: drop (if present) the old users table and create the new one.
{
	global $conn, $users_table_name, $quote, $dbms_type, $generate_portable_password_hash, $autoincrement_word;
	
	drop_table_db($conn, $users_table_name);

	$fields = "
	id_user ".$autoincrement_word.",
	id_group integer NOT NULL,
	first_name_user varchar(100),
	last_name_user varchar(100),
	email_user varchar(100),
	username_user varchar(50) NOT NULL UNIQUE,
	password_user varchar(255) NOT NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_user\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}

	create_table_db ($conn, $users_table_name, $fields);
	
	
	$t_hasher = new PasswordHash(8, $generate_portable_password_hash);
	
	$encrypted = $t_hasher->HashPassword('letizia');
	
	if (strlen_custom($encrypted) < 20){
		echo 'Error';
		exit();
	}

	$sql = "INSERT INTO ".$quote.$users_table_name.$quote." (id_user, id_group, username_user, password_user) VALUES (1, 1, 'root', '".$encrypted."')";

	$res_table = execute_db($sql, $conn);
	
	
	$t_hasher = new PasswordHash(8, $generate_portable_password_hash);
	$encrypted = $t_hasher->HashPassword('letizia');
	
	if (strlen_custom($encrypted) < 20){
		echo 'Error';
		exit();
	}

	$sql = "INSERT INTO ".$quote.$users_table_name.$quote." (id_user, id_group, username_user, password_user) VALUES (2, 2, 'alfonso', '".$encrypted."')";

	$res_table = execute_db($sql, $conn);

} // end function create_users_table

function create_groups_table()
// goal: drop (if present) the old users table and create the new one.
{
	global $conn, $groups_table_name, $quote, $dbms_type, $autoincrement_word;
	
	drop_table_db($conn, $groups_table_name);

	$fields = "
	id_group ".$autoincrement_word.",
	name_group varchar(100) NOT NULL UNIQUE";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_group\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}

	create_table_db ($conn, $groups_table_name, $fields);
	
	$sql = "INSERT INTO ".$quote.$groups_table_name.$quote." (id_group, name_group) VALUES (1, 'admin')";

	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$groups_table_name.$quote." (id_group, name_group) VALUES (2, 'default')";

	$res_table = execute_db($sql, $conn);

} // end function create_groups_table


function create_permissions_tables()
// goal: drop (if present) the old users table and create the new one.
{
	global $conn, $quote, $dbms_type, $autoincrement_word, $prefix_internal_table;
	
	drop_table_db($conn, $prefix_internal_table."permissions");

	$fields = "
	id_permission ".$autoincrement_word.",
	subject_type_permission varchar(50) NOT NULL,
	id_subject integer NOT NULL,
	object_type_permission varchar(50) NOT NULL,
	object_permission varchar(255) NOT NULL,
	id_permission_type integer,
	value_permission integer NOT NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_permission\")";
	}
	
	$fields .= ")";

	create_table_db ($conn, $prefix_internal_table."permissions", $fields);
	
	drop_table_db($conn, $prefix_internal_table."permission_options");

	$fields = "
	id_permission_option ".$autoincrement_word.",
	label_permission_option varchar(50) NOT NULL,
	value_permission_option integer NOT NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_permission_option\")";
	}
	
	$fields .= ")";
	
	create_table_db ($conn, $prefix_internal_table."permission_options", $fields);
	
	$sql = "INSERT INTO ".$prefix_internal_table."permission_options  (label_permission_option, value_permission_option) VALUES ('Yes', 1)";

	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$prefix_internal_table."permission_options  (label_permission_option, value_permission_option) VALUES ('No', 0)";

	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$prefix_internal_table."permission_options  (label_permission_option, value_permission_option) VALUES ('My', 2)";

	$res_table = execute_db($sql, $conn);
	
	drop_table_db($conn, $prefix_internal_table."permission_types");

	$fields = "
	id_permission_type ".$autoincrement_word.",
	type_permission_type varchar(50) NOT NULL,
	object_type_permission_type varchar(50) NOT NULL,
	options_permission_type varchar(50) NOT NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_permission_type\")";
	}
	
	$fields .= ")";

	create_table_db ($conn, $prefix_internal_table."permission_types", $fields);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (1, 'read', 'table', '0,1,2')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (2, 'delete', 'table', '0,1,2')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (3, 'edit', 'table', '0,1,2')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (4, 'create', 'table', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (5, 'details', 'table', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (7, 'results', 'field', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (8, 'edit', 'field', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (9, 'create', 'field', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (10, 'details', 'field', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (11, 'quick search', 'field', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (12, 'search', 'field', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	
} // end function create_groups_table

function create_locks_table()
// goal: drop (if present) the old locks table and create the new one.
{
	global $conn, $prefix_internal_table, $quote, $dbms_type, $autoincrement_word;
	
	if (true || $dbms_type === 'sqlite' || $dbms_type === 'sqlite2'){
		drop_table_db($conn, $prefix_internal_table."locked_records");
	
		$fields = "
		id_locked_record ".$autoincrement_word.",
		table_locked_record varchar(50) NOT NULL,
		pk_locked_record varchar(50) NOT NULL,
		value_locked_record varchar(100) NOT NULL,
		username_user varchar(255) NOT NULL,
		timestamp_locked_record integer NOT NULL,
		UNIQUE (table_locked_record,pk_locked_record,value_locked_record)";
		
		if ($dbms_type === 'postgres'){
  			$fields .= ",
  			PRIMARY KEY (\"id_locked_record\")";
  		}
  		
		$fields .= ")
	";
	
		create_table_db ($conn, $prefix_internal_table."locked_records", $fields);
	
	}
	else{
	
	$data_dictionary = NewDataDictionary($conn);

	drop_table_db($conn, $data_dictionary, $prefix_internal_table."locked_records");

	
	$fields = "
		id_locked_record I NOTNULL PRIMARY AUTOINCREMENT,
		table_locked_record C(50) NOTNULL,
		pk_locked_record C(50) NOTNULL,
		value_locked_record C(100) NOTNULL,
		username_user C(255) NOTNULL,
		timestamp_locked_record I NOTNULL
		)
	";
	
	create_table_db ($conn, $data_dictionary, $prefix_internal_table."locked_records", $fields);
	
	$index_name = 'record_index';
	$index_fields = 'table_locked_record,pk_locked_record,value_locked_record';
	$options_ar[0] = 'UNIQUE';
	create_index_db ($conn, $data_dictionary, $prefix_internal_table."locked_records", $index_name, $index_fields, $options_ar);
	
	}
	

} // end function create_locks_table


function create_static_pages_table()
// goal: drop (if present) the old static_pages table and create the new one.
{
	global $conn, $prefix_internal_table, $quote, $dbms_type, $table_list_name, $autoincrement_word;
	
	if (true || $dbms_type === 'sqlite' || $dbms_type === 'sqlite2'){
		drop_table_db($conn, $prefix_internal_table."static_pages");
	
		$fields = "
		id_static_page ".$autoincrement_word.",
		link_static_page varchar(255) NOT NULL,
		content_static_page text NOT NULL,
		is_homepage_static_page char(1) NOT NULL";
		
		if ($dbms_type === 'postgres'){
  			$fields .= ",
  			PRIMARY KEY (\"id_static_page\")";
  		}
  		
		$fields .= ")
	";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}
	
		create_table_db ($conn, $prefix_internal_table."static_pages", $fields);
		
		$sql = "INSERT INTO ".$quote.$prefix_internal_table."static_pages".$quote." (id_static_page, link_static_page, content_static_page, is_homepage_static_page) VALUES (1, 'Home', '<p>Congratulations! This is the homepage of the DaDaBIK application you have created.</p>\r\n<p>If you login as administrator, you can customize this homepage choosing \"Show items\" and then \"Static pages\" from the tables list.</p>\r\n<p>You can customize this application choosing \"Administration\".</p>', 'y')";
		
		$res = execute_db($sql, $conn);
		
		
	
	}
	else{
	
	$data_dictionary = NewDataDictionary($conn);

	drop_table_db($conn, $data_dictionary, $prefix_internal_table."static_pages");

	
	$fields = "
		id_static_page I NOTNULL PRIMARY AUTOINCREMENT,
		link_static_page C(255) NOTNULL,
		content_static_page X NOTNULL,
		is_homepage_static_page C(1) NOTNULL
		)
	";
	
	create_table_db ($conn, $data_dictionary, $prefix_internal_table."static_pages", $fields);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (name_field, label_field, type_field, content_field, present_search_form_field, present_results_search_field, present_details_form_field, present_insert_form_field, present_edit_form_field, present_filter_form_field, present_ext_update_form_field, required_field, check_duplicated_insert_field, other_choices_field, select_options_field, primary_key_field_field, primary_key_table_field, primary_key_db_field, linked_fields_field, linked_fields_order_by_field, linked_fields_order_type_field, select_type_field, items_table_names_field, items_table_fk_field_names_field, prefix_field, default_value_field, width_field, height_field, maxlength_field, hint_insert_field, order_form_field, separator_field, table_name, search_new_line_after_field, insert_new_line_after_field, details_new_line_after_field) VALUES('content_static_page', 'Content', 'rich_editor', 'html', '1', '1', '1', '1', '1', '0', '1', '1', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '', '', 3, '~', 'dadabik_static_pages', '1', '1', '1')";
	
	$res = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (name_field, label_field, type_field, content_field, present_search_form_field, present_results_search_field, present_details_form_field, present_insert_form_field, present_edit_form_field, present_filter_form_field, present_ext_update_form_field, required_field, check_duplicated_insert_field, other_choices_field, select_options_field, primary_key_field_field, primary_key_table_field, primary_key_db_field, linked_fields_field, linked_fields_order_by_field, linked_fields_order_type_field, select_type_field, items_table_names_field, items_table_fk_field_names_field, prefix_field, default_value_field, width_field, height_field, maxlength_field, hint_insert_field, order_form_field, separator_field, table_name, search_new_line_after_field, insert_new_line_after_field, details_new_line_after_field) VALUES('link_static_page', 'Link text', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '0', '1', '1', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '', '', 2, '~', 'dadabik_static_pages', '1', '1', '1')";
	
	$res = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (name_field, label_field, type_field, content_field, present_search_form_field, present_results_search_field, present_details_form_field, present_insert_form_field, present_edit_form_field, present_filter_form_field, present_ext_update_form_field, required_field, check_duplicated_insert_field, other_choices_field, select_options_field, primary_key_field_field, primary_key_table_field, primary_key_db_field, linked_fields_field, linked_fields_order_by_field, linked_fields_order_type_field, select_type_field, items_table_names_field, items_table_fk_field_names_field, prefix_field, default_value_field, width_field, height_field, maxlength_field, hint_insert_field, order_form_field, separator_field, table_name, search_new_line_after_field, insert_new_line_after_field, details_new_line_after_field) VALUES('id_static_page', 'id_static_page', 'text', 'alphanumeric', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 1, '~', 'dadabik_static_pages', '1', '1', '1')";
	
	$res = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (name_field, label_field, type_field, content_field, present_search_form_field, present_results_search_field, present_details_form_field, present_insert_form_field, present_edit_form_field, present_filter_form_field, present_ext_update_form_field, required_field, check_duplicated_insert_field, other_choices_field, select_options_field, primary_key_field_field, primary_key_table_field, primary_key_db_field, linked_fields_field, linked_fields_order_by_field, linked_fields_order_type_field, select_type_field, items_table_names_field, items_table_fk_field_names_field, prefix_field, default_value_field, width_field, height_field, maxlength_field, hint_insert_field, order_form_field, separator_field, table_name, search_new_line_after_field, insert_new_line_after_field, details_new_line_after_field) VALUES('is_homepage_static_page', 'Make this page the home page', 'select_single', 'alphanumeric', '1', '1', '1', '1', '1', '0', '1', '1', '0', '0', '~y~n~', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 4, '~', 'dadabik_static_pages', '1', '1', '1')";
	
	$res = execute_db($sql, $conn);
	
	
	
	
	}
	

} // end function create_static_pages_table

function create_installation_table()
// goal: create the installation table
{
	global $conn, $prefix_internal_table, $quote, $dbms_type, $date_time_word;
	
	if (true || $dbms_type === 'sqlite' || $dbms_type === 'sqlite2'){
	
	drop_table_db($conn, $prefix_internal_table.'installation_tab');
	
	$fields = "
		id_installation integer,
		code_installation varchar(19),
		date_time_installation ".$date_time_word." NOT NULL,
		dbms_type_installation varchar(50) NOT NULL,
		dadabik_version_installation varchar(10) NOT NULL,
		dadabik_version_2_installation varchar(10) NOT NULL,
		other_infos_installation varchar(255)
		)
	";

	
	create_table_db ($conn, $prefix_internal_table.'installation_tab', $fields);
	}
	else{
	
	$data_dictionary = NewDataDictionary($conn);
	
	drop_table_db($conn, $data_dictionary, $prefix_internal_table.'installation_tab');

	$data_dictionary = NewDataDictionary($conn);

	
	$fields = "
		id_installation I,
		code_installation C(19),
		date_time_installation T NOTNULL,
		dbms_type_installation C(50) NOTNULL,
		dadabik_version_installation C(10) NOTNULL,
		other_infos_installation C(255)
		)
	";
	
	create_table_db ($conn, $data_dictionary, $prefix_internal_table.'installation_tab', $fields);
	
	}

} // end function create_installation_table

function create_logs_table()
// goal: create the logs table
{
	global $conn, $prefix_internal_table, $dbms_type, $quote, $date_time_word, $autoincrement_word;
	
	$fields = "
	id_log ".$autoincrement_word.",
	timestamp_log integer NOT NULL,
	operation_log varchar(50) NOT NULL,
	sql_log text NOT NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_log\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}
	
	drop_table_db($conn, $prefix_internal_table.'logs');
	
	create_table_db ($conn, $prefix_internal_table.'logs', $fields);
	

} // end function create_logs_table


function table_allowed($table_name)
// goal: check if a table is allowed to be managed by DaDaBIK
// input: $table_name
// output: true or false
{
	global $conn, $table_list_name, $quote;
	if (table_exists($table_list_name)){
		$sql = "SELECT ".$quote."allowed_table".$quote." FROM ".$quote.$table_list_name.$quote." WHERE ".$quote."name_table".$quote." = '".$table_name."'";
		$res_allowed = execute_db($sql, $conn);
		if (get_num_rows_db($res_allowed) == 1){
			$row_allowed = fetch_row_db($res_allowed);
			$allowed_table = $row_allowed[0];
			if ($allowed_table == "0"){
				return false;
			} // end if
			else{
				return true;
			} // end else
		} // end if
		elseif (get_num_rows_db($res_allowed) == 0){ // e.g. I have an empty table or the table is not installed
			return false;
		} // end elseif
		else{
			exit;
		} // end else
	} // end if
	else{
		return false;
	} // end else
} // end function table_allowed()

function build_enabled_features_ar($table_name, $all_table=0)
// goal: build an array containing "0" or "1" according to the fact that a feature (insert, edit, delete, details) is enabled or not
// input: $table_name
// output: $enabled_features_ar, the array
{
	global $conn, $table_list_name, $quote;
	
	if ($all_table === 1 ){
		$sql = "SELECT name_table, ".$quote."enable_insert_table".$quote.", ".$quote."enable_edit_table".$quote.", ".$quote."enable_delete_table".$quote.", ".$quote."enable_details_table".$quote.", ".$quote."enable_list_table".$quote.", ".$quote."enable_delete_authorization_table".$quote.", ".$quote."enable_update_authorization_table".$quote.", ".$quote."enable_browse_authorization_table".$quote." FROM ".$quote.$table_list_name.$quote;
		$res_enable = execute_db($sql, $conn);
		
		while ($row_enable = fetch_row_db($res_enable)){
			$enabled_features_ar[$row_enable["name_table"]]["insert"] = $row_enable["enable_insert_table"];
			$enabled_features_ar[$row_enable["name_table"]]["edit"] = $row_enable["enable_edit_table"];
			$enabled_features_ar[$row_enable["name_table"]]["delete"] = $row_enable["enable_delete_table"];
			$enabled_features_ar[$row_enable["name_table"]]["details"] = $row_enable["enable_details_table"];
			$enabled_features_ar[$row_enable["name_table"]]["list"] = $row_enable["enable_list_table"];
			$enabled_features_ar[$row_enable["name_table"]]["delete_authorization"] = $row_enable["enable_delete_authorization_table"];
			$enabled_features_ar[$row_enable["name_table"]]["update_authorization"] = $row_enable["enable_update_authorization_table"];
			$enabled_features_ar[$row_enable["name_table"]]["browse_authorization"] = $row_enable["enable_browse_authorization_table"];
			
			
		}
		
		return $enabled_features_ar;
			
	}
	else{
		$sql = "SELECT ".$quote."enable_insert_table".$quote.", ".$quote."enable_edit_table".$quote.", ".$quote."enable_delete_table".$quote.", ".$quote."enable_details_table".$quote.", ".$quote."enable_list_table".$quote.", ".$quote."enable_delete_authorization_table".$quote.", ".$quote."enable_update_authorization_table".$quote.", ".$quote."enable_browse_authorization_table".$quote." FROM ".$quote.$table_list_name.$quote." WHERE ".$quote."name_table".$quote." = '".$table_name."'";
		$res_enable = execute_db($sql, $conn);
		if (get_num_rows_db($res_enable) == 1){
			$row_enable = fetch_row_db($res_enable);
			$enabled_features_ar["insert"] = $row_enable["enable_insert_table"];
			$enabled_features_ar["edit"] = $row_enable["enable_edit_table"];
			$enabled_features_ar["delete"] = $row_enable["enable_delete_table"];
			$enabled_features_ar["details"] = $row_enable["enable_details_table"];
			$enabled_features_ar["list"] = $row_enable["enable_list_table"];
			$enabled_features_ar["delete_authorization"] = $row_enable["enable_delete_authorization_table"];
			$enabled_features_ar["update_authorization"] = $row_enable["enable_update_authorization_table"];
			$enabled_features_ar["browse_authorization"] = $row_enable["enable_browse_authorization_table"];
	
			return $enabled_features_ar;
		} // end if
		else{
			db_error($sql);
		} // end else
	}
} // end function build_enabled_features_ar($table_name)

function build_enable_features_checkboxes($table_name)
// goal: build the form that enable features
// input: name of the current table
// output: the html for the checkboxes
{
	$enabled_features_ar = build_enabled_features_ar($table_name);

	$enable_features_checkboxes = "Permissions: ";
	
	
	$enable_features_checkboxes .= "<input type=\"checkbox\" name=\"enable_list\" value=\"1\"";
	if ($enabled_features_ar["list"] == "1"){
		$enable_features_checkboxes .= " checked";
	} // end if
	$enable_features_checkboxes .= ">Read <a href=\"javascript:show_admin_help('Read permission:', 'If you disable the read permission, the table will not be displayed in the change table menu of the application. Remember, however, that users can still read its records if the table is used as source of a select_single field or as items table in a master/details view.');\"><img alt=\"Help\" title=\"Help\" border=\"0\" src=\"images/help.png\" /></a>";
	
	$enable_features_checkboxes .= "&nbsp;&nbsp;<input type=\"checkbox\" name=\"enable_insert\" value=\"1\"";
	$enable_features_checkboxes .= "";
	if ($enabled_features_ar["insert"] == "1"){
		$enable_features_checkboxes .= " checked";
	} // end if
	$enable_features_checkboxes .= ">Create ";
	
	$enable_features_checkboxes .= "&nbsp;&nbsp;<input type=\"checkbox\" name=\"enable_edit\" value=\"1\"";
	if ($enabled_features_ar["edit"] == "1"){
		$enable_features_checkboxes .= " checked";
	} // end if
	$enable_features_checkboxes .= ">Edit ";
	$enable_features_checkboxes .= "&nbsp;&nbsp;<input type=\"checkbox\" name=\"enable_delete\" value=\"1\"";
	if ($enabled_features_ar["delete"] == "1"){
		$enable_features_checkboxes .= " checked";
	} // end if
	$enable_features_checkboxes .= ">Delete ";
	$enable_features_checkboxes .= "&nbsp;&nbsp;<input type=\"checkbox\" name=\"enable_details\" value=\"1\"";
	if ($enabled_features_ar["details"] == "1"){
		$enable_features_checkboxes .= " checked";
	} // end if
	$enable_features_checkboxes .= ">Details ";
	
	
	
	$enable_features_checkboxes .= "<br/>Owner permissions: <input type=\"checkbox\" name=\"enable_delete_authorization\" value=\"1\"";
	if ($enabled_features_ar["delete_authorization"] == "1"){
		$enable_features_checkboxes .= " checked";
	} // end if
	$enable_features_checkboxes .= ">Delete authorization <a href=\"javascript:show_admin_help('Delete authorization:', 'Only who inserted a record can delete it.');\"><img alt=\"Help\" title=\"Help\" border=\"0\" src=\"images/help.png\" /></a>&nbsp;&nbsp;";
	$enable_features_checkboxes .= "<input type=\"checkbox\" name=\"enable_update_authorization\" value=\"1\"";
	if ($enabled_features_ar["update_authorization"] == "1"){
		$enable_features_checkboxes .= " checked";
	} // end if
	$enable_features_checkboxes .= ">Update authorization <a href=\"javascript:show_admin_help('Update authorization:', 'Only who inserted a record can update it.');\"><img alt=\"Help\" title=\"Help\" border=\"0\" src=\"images/help.png\" /></a>&nbsp;&nbsp;";
	$enable_features_checkboxes .= "<input type=\"checkbox\" name=\"enable_browse_authorization\" value=\"1\"";
	if ($enabled_features_ar["browse_authorization"] == "1"){
		$enable_features_checkboxes .= " checked";
	} // end if
	$enable_features_checkboxes .= ">Browse autorization <a href=\"javascript:show_admin_help('Browse authorization:', 'Only who inserted a record can view it.');\"><img alt=\"Help\" title=\"Help\" border=\"0\" src=\"images/help.png\" /></a>&nbsp;&nbsp;";
	
	$enable_features_checkboxes .= '<br/>(the owner permissions have effect only with DaDaBIK ENTERPRISE VERSION)';

	return $enable_features_checkboxes;
} // end function build_enable_features_checkboxes

function build_change_field_select($fields_labels_ar, $field_position)
// goal: build an html select with all the field names
// input: $fields_labels_ar, $field_position (the current selected option)
// output: the select
{
	global $conn, $table_name;

	$change_field_select = "";
	$change_field_select .= "<select name=\"field_position\" onchange=\"javascript:document.change_field_form.submit()\">";
	$count_temp = count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++){
		$change_field_select .= "<option value=\"".$i."\"";
		if ($i == $field_position){
			$change_field_select .= " selected";
		} // end if
		$change_field_select .= ">".$fields_labels_ar[$i]["name_field"]."</option>";
	} // end for
	$change_field_select .= "</select>";

	return $change_field_select;
} // end function build_change_field_select

function build_int_table_field_form($field_position, $int_fields_ar, $fields_labels_ar, $_POST_2, $error)
// goal: build a part of the internal table manager form relative to one field
// input: $field_position, the position of the field in the internal form, $int_field_ar, the array of the field of the internal table (with labels and properties), $fields_labels_ar, the array containing the fields labels and other information about fields
// output: the html form part
{
	global $enable_granular_permissions, $enable_authentication;
	
	$int_table_form = "";
	$int_table_form .= "<table border=\"0\" cellpadding=\"6\"><tr bgcolor=\"#F0F0F0\"><td><table>";
	
	$count_temp = 0;
	foreach ($int_fields_ar as $int_fields_ar_el){
	
	
	
		if (isset($int_fields_ar_el[5])){
			if ($count_temp !== 0) $int_table_form .= '<tr><td colspan="2">&nbsp;</td></tr>';
			$int_table_form .= '<tr class="tr_form_separator"><td colspan="2">'.$int_fields_ar_el[5].'</td></tr>';
		}
		
		$int_table_form .= '<tr';
	
		if (isset($int_fields_ar_el[7])){
		$int_table_form .= ' id="'.$int_fields_ar_el[7].'"';
		}
		
		$int_table_form .= '>';
		if (isset($int_fields_ar_el[7]) && $int_fields_ar_el[7] == 17 && $enable_granular_permissions === 1 && $enable_authentication === 1){
			$int_table_form .= '<tr><td colspan="2"><strong>Granular permissions are enabled in config.php, you must use the permissions manager to control the form presence</strong></td></tr>';
		}
		
		$int_field_name_temp = $int_fields_ar_el[1];
		$int_table_form .= "<td>".$int_fields_ar_el[0];
		if ($count_temp>0){		
			$int_table_form .= ' <a href="javascript:show_admin_help(\''.$int_fields_ar_el[0].'\', \''.$int_fields_ar_el[4].'\');"><img alt="Help" title="Help" border="0" src="images/help.png" /></a>';
		}
		$int_table_form .= "</td><td>";
		if ($count_temp == 0){ // it's the name of the field, no edit needed, just show the name
			$int_table_form .= $fields_labels_ar[$field_position][$int_field_name_temp];
		} // end if
		else{
			switch ($int_fields_ar_el[2]){
				case "text":
					if ($error === ''){
						$int_table_form .= "<input maxlength=\"500\" type=\"text\" name=\"".$int_field_name_temp."_".$field_position."\" value=\"".htmlspecialchars($fields_labels_ar[$field_position][$int_field_name_temp])."\" size=\"".$int_fields_ar_el[3]."\">";
					}
					else{
						$int_table_form .= "<input maxlength=\"500\" type=\"text\" name=\"".$int_field_name_temp."_".$field_position."\" value=\"".htmlspecialchars(unescape($_POST_2[$int_field_name_temp."_".$field_position]))."\" size=\"".$int_fields_ar_el[3]."\">";
					}
					break;
				case "select_yn":
					$int_table_form .= "<select name=\"".$int_field_name_temp."_".$field_position."\">";
					$int_table_form .= "<option value=\"1\"";
					if ($error === ''){
						if ($fields_labels_ar[$field_position][$int_field_name_temp] == "1"){
							$int_table_form .= " selected";
						} // end if
					}
					else{
						if (unescape($_POST_2[$int_field_name_temp."_".$field_position]) == "1"){
							$int_table_form .= " selected";
						} // end if
					}
					$int_table_form .= ">Y</option>";
					$int_table_form .= "<option value=\"0\"";
					
					if ($error === ''){
						if ($fields_labels_ar[$field_position][$int_field_name_temp] == "0"){
							$int_table_form .= " selected";
						} // end if
					}
					else{
						if (unescape($_POST_2[$int_field_name_temp."_".$field_position]) == "0"){
							$int_table_form .= " selected";
						} // end if
					}
					$int_table_form .= ">N</option>";
					$int_table_form .= "</select>";
					break;
				case "select_custom":
					$int_table_form .= "<select";
					
					$first_temp = 1;
		
	
					if (isset($int_fields_ar_el[8])){
					$int_table_form .= ' onchange="javascript:'.$int_fields_ar_el[8].'"';
					}
					if (isset($int_fields_ar_el[9])){
					$int_table_form .= ' id="'.$int_fields_ar_el[9].'"';
					}
								
					$int_table_form .= " name=\"".$int_field_name_temp."_".$field_position."\">";
					$temp_ar = explode("/", $int_fields_ar_el[3]);
					$count_temp_2 = count($temp_ar);
					for ($j=0; $j<$count_temp_2; $j++){
						
						$temp_2_ar = explode('~', $temp_ar[$j]);
						
						if (count($temp_2_ar) === 2){
							
							$value_temp = $temp_2_ar[1];
							
							if ($first_temp === 0){
								$int_table_form .= '</optgroup>';
							}
							else{ // first option, don't close the group
								$first_temp = 0;
								
							}
							$int_table_form .= '<optgroup label="'.$temp_2_ar[0].'">';
						}
						else{
							$value_temp = $temp_2_ar[0];
						}
						
						$int_table_form .= "<option value=\"".$value_temp."\"";
						
						if ($error === ''){
							if ($fields_labels_ar[$field_position][$int_field_name_temp] == $value_temp){
								$int_table_form .= " selected";
							} // end if
						}
						else{
							if (unescape($_POST_2[$int_field_name_temp."_".$field_position]) == $value_temp){
								$int_table_form .= " selected";
							} // end if
						}
						
						
						if ($int_field_name_temp === 'content_field' && $value_temp === 'html' || $int_field_name_temp === 'type_field' && $value_temp === 'rich_editor'){
							$int_table_form .= ">".$value_temp." --> THIS COULD LEAD TO SECURITY PROBLEMS. READ THE DOCUMENTATION BEFORE USING!</option>";
						}
						else{
							$int_table_form .= ">".$value_temp."</option>";
						}					
						
					} // end for
					$int_table_form .= "</select>";
					break;
			} // end switch
		} // end else
		$int_table_form .= "</td>";
		$int_table_form .= "</tr>"; // end of the row
		$count_temp++;
	} // end foreach
	$int_table_form .= "</table></td></tr></table><br/>"; // end of the row

	return $int_table_form;
} // end function build_int_table_field_form($field_position, $int_fields_ar, $fields_labels_ar)

function get_valid_name_uploaded_file ($file_name, $check_temp_files)
{
// goal: get a valid name (not already existant) for an uploaded file, e.g. if I upload a file with the name file.txt, and a file with the same name already exists, return file_2.txt, or file_3.txt.....; if .$check_temp_files is 1 check also the dadabik_tmp_file_ corresponding file names
// input: $file_name, $check_temp_files
// a valid name

	global $upload_directory;
	$valid_file_name = $file_name;
	$valid_name_found = 0;

	$dot_position = strpos_custom($file_name, '.');

	$i = 2;
	do{
		if ( file_exists($upload_directory.$valid_file_name) || file_exists($upload_directory.'dadabik_tmp_file_'.$valid_file_name) && $check_temp_files === 1) { // a file with the same name is already present or a temporary file that will get the same name when the insert/update function will be executed is already present (and I need to check temp files)
			if ($dot_position === false) { // the file doesn't have an exension
				$valid_file_name = $file_name.'_'.$i; // from pluto to pluto_2
			}
			else{
				$valid_file_name = substr_custom($file_name, 0, $dot_position).'_'.$i.substr_custom($file_name, $dot_position); // from pluto.txt to pluto_2.txt
			}
			$i++;
		} // end if
		else{
			$valid_name_found = 1;
		}
	} while ( $valid_name_found==0 );

	return $valid_file_name;

} // end function get_valid_name_uploaded_file ($file_name)

function insert_other_field($primary_key_db, $primary_key_table, $field_name, $field_value_other)
// goal: insert in the primary key table the other.... field
// input: $primary_key_table, $primary_key_db, $linked_fields, $field_value_other
// outpu: the ID of the record inserted
{
	global $conn, $quote, $prefix_internal_table, $current_user;

	if (!table_contains($primary_key_db, $primary_key_table, $field_name, $field_value_other)){ // check if the table doesn't contains the value inserted as other
		
		
		$fields_labels_ar_linked = build_fields_labels_array($prefix_internal_table.$primary_key_table, '1');
		
		$ID_user_field_name = get_ID_user_field_name($fields_labels_ar_linked);

		if ($ID_user_field_name === false) {
			$sql_insert_other = "INSERT INTO ".$quote.$primary_key_table.$quote." (".$quote.$field_name.$quote.") VALUES ('".$field_value_other."')";
		} // end if
		else {
			$sql_insert_other = "INSERT INTO ".$quote.$primary_key_table.$quote." (".$quote.$field_name.$quote.", ".$quote.$ID_user_field_name.$quote.") VALUES ('".$field_value_other."', '".$current_user."')";
		}
		
		display_sql($sql_insert_other);
		
		// insert into the table of other
		$res_insert = execute_db($sql_insert_other, $conn);
		
		$last_ID_db = get_last_ID_db();

		return $last_ID_db;
	} // end if
} // end function insert_other_field($foreign_key, $field_value_other)

function update_options($fields_labels_ar_i, $field_name, $field_value_other)
// goal: upate the options of a field when a user select other....
// input: $fields_labels_ar_i (fields_labels_ar specific for a field), $field_name, $field_value_other
{
	global $conn, $quote, $table_internal_name, $prefix_internal_table;
	$select_options_field_updated = escape($fields_labels_ar_i["select_options_field"].unescape($field_value_other).$fields_labels_ar_i["separator_field"]);
	
	$table_name = substr_custom($table_internal_name, strlen_custom($prefix_internal_table));
	
	$sql_update_other = "UPDATE ".$quote.$prefix_internal_table."forms".$quote." SET ".$quote."select_options_field".$quote." = '".$select_options_field_updated."' WHERE ".$quote."name_field".$quote." = '".$field_name."' and table_name = '".$table_name."'";
	
	
	display_sql($sql_update_other);
	
	// update the internal table
	$res_update = execute_db($sql_update_other, $conn);
} // end function update_options($fields_labels_ar_i, $field_name, $field_value_other)

function build_linked_field_values_ar($field_value, $primary_key_field_field, $primary_key_table_field, $primary_key_db_field, $linked_fields_ar)
// goal: build the array containing the linked field values starting from a field value
// input: $primary_key_field_field, $primary_key_table_field, $primary_key_db_field, $linked_fields_ar
// output: linked_field_values_ar
{
	global $conn, $quote;

	$sql = "SELECT ";

	$count_temp = count($linked_fields_ar);
	for ($i=0; $i<$count_temp; $i++) {
		$sql .= $quote.$linked_fields_ar[$i].$quote.", ";
	} // end for
	$sql = substr_custom($sql, 0, -2); // delete the last ", "
	$sql .= " FROM ".$quote.$primary_key_table_field.$quote." WHERE ".$quote.$primary_key_field_field.$quote." = '".$field_value."'";

	// execute the select query
	$res_linked_fields = execute_db($sql, $conn);
	
	$row_linked_fields = fetch_row_db($res_linked_fields);

	$count_temp = num_fields_db($res_linked_fields);
	for ($i=0; $i<$count_temp; $i++){
		$linked_field_values_ar[] = $row_linked_fields[$i];
	} // end for

	return $linked_field_values_ar;
} // end function build_linked_field_values_ar()


function build_select_part($fields_labels_ar, $table_name)
// goal: build the select part of a search query e.g.SELECT table_1.field_1, table_2.field2 from table_1 LEFT JOIN table_2 ON table_1.field_3 = table2.field_3
// input: $fields_labels_ar, $table_name
// output: the query
{
	global $quote, $alias_prefix, $dbms_type;

	// get the primary key
	$unique_field_name = get_unique_field_db($table_name);

	$sql_fields_part = '';
	$sql_from_part = '';

	foreach($fields_labels_ar as $field){
		if ($field['present_results_search_field'] === '1' || $field['present_details_form_field'] === '1' || $field['name_field'] === $unique_field_name) { // include in the select stataments just the fields present in results or the primary key (useful to pass to the edit form)

			// if the field has linked fields, include each linked fields in the select statement and the corresponding table (wiht join) in the from part. Use alias for all in order to mantain name unicity, each field has is own alias_suffix_field so it is easy
			if ($field['primary_key_field_field'] !== '' && $field['primary_key_field_field'] !== NULL) {
				$linked_fields_ar = explode($field['separator_field'], $field['linked_fields_field']);
				
				foreach ($linked_fields_ar as $linked_field){
					////*$sql_fields_part .= $quote.$field['primary_key_table_field'].$alias_prefix.$field['alias_suffix_field'].$quote.'.'.$quote.$linked_field.$quote.' AS '.$quote.$linked_field.$alias_prefix.$field['alias_suffix_field'].$quote.', ';

					$sql_fields_part .= $quote.$field['primary_key_table_field'].$alias_prefix.$field['alias_suffix_field'].$quote.'.'.$quote.$linked_field.$quote.' AS '.$quote.$field['primary_key_table_field'].$alias_prefix.$linked_field.$alias_prefix.$field['alias_suffix_field'].$quote.', ';
				} //end foreach

				// hack for oracle
				if ($dbms_type === 'oci8po') {
					$sql_from_part .= ' LEFT JOIN '.$quote.$field['primary_key_table_field'].$quote.' '.$quote.$field['primary_key_table_field'].$alias_prefix.$field['alias_suffix_field'].$quote;
				} // end if
				else {
					$sql_from_part .= ' LEFT JOIN '.$quote.$field['primary_key_table_field'].$quote.' AS '.$quote.$field['primary_key_table_field'].$alias_prefix.$field['alias_suffix_field'].$quote;
				} // end else

				$sql_from_part .= ' ON ';
				$sql_from_part .= $quote.$table_name.$quote.'.'.$quote.$field['name_field'].$quote.' = '.$quote.$field['primary_key_table_field'].$alias_prefix.$field['alias_suffix_field'].$quote.'.'.$quote.$field['primary_key_field_field'].$quote;
			} // end if
			// if the field has not linked fiel, include just the field in the select statement
			else {
				$sql_fields_part .= $quote.$table_name.$quote.'.'.$quote.$field['name_field'].$quote.', ';
			} // end else
		} // end if
	} // end foreach

	$sql_fields_part = substr_custom($sql_fields_part, 0, -2); // delete the last ', '

	// compose the final statement
	$sql = "SELECT ".$sql_fields_part." FROM ".$quote.$table_name.$quote.$sql_from_part ;
	
	return $sql;
} // end function build_select_part()

function build_records_per_page_form($records_per_page, $table_name, $master_table_name, $master_table_function, $master_table_where_field , $master_table_where_value, $is_items_table)
// goal: build the listbox that allows the user to choose the number of result record per page on the fly
// input: $records_per_page, the current number of record per page, $table_name, the current table, $master_table_name, $master_table_function, $master_table_where_field , $master_table_where_value, $is_items_table
// output: the form
{
	global $dadabik_main_file, $records_per_page_ar, $normal_messages_ar;

	$records_per_page_form = '';
	
	$records_per_page_form .= "\n<!--[if !lte IE 9]><!-->\n";
	$records_per_page_form .= '<form class="css_form" name="records_per_page_form" action="'.$dadabik_main_file.'" method="GET">'."\n";
	$records_per_page_form .= "<!--<![endif]-->\n";
	$records_per_page_form .= "<!--[if lte IE 9]>\n";
	$records_per_page_form .= '<form name="records_per_page_form" action="'.$dadabik_main_file.'" method="GET">'."\n";
	$records_per_page_form .= "<![endif]-->\n";

	if ($is_items_table === 1) {
		$records_per_page_form .= '<input type="hidden" name="table_name" value="'.$master_table_name.'">';
		$records_per_page_form .= '<input type="hidden" name="function" value="'.$master_table_function.'">';
		$records_per_page_form .= '<input type="hidden" name="store_session_table_infos_for_items_table" value="1">';
		$records_per_page_form .= '<input type="hidden" name="items_table_name" value="'.$table_name.'">';
		$records_per_page_form .= '<input type="hidden" name="where_field" value="'.urlencode($master_table_where_field).'">';
		$records_per_page_form .= '<input type="hidden" name="where_value" value="'.urlencode(unescape($master_table_where_value)).'">';
	} // end if
	else {
		$records_per_page_form .= '<input type="hidden" name="table_name" value="'.$table_name.'">';
		$records_per_page_form .= '<input type="hidden" name="function" value="search">';
	} // end else

	$records_per_page_form .= '<div class="select_element select_element_records_per_page"><select class="select_records_per_page" name="records_per_page" onchange="javascript:document.records_per_page_form.submit()">';

	foreach ($records_per_page_ar as $records_per_page_item){
		$records_per_page_form .= '<option value="'.$records_per_page_item.'"';
		if ($records_per_page_item === $records_per_page) {
			$records_per_page_form .= ' selected';
		} // end if
		$records_per_page_form .= '>'.$records_per_page_item.'</option>';
	} // foreach

	$records_per_page_form .= '</select></div></form></td>';
	
	$records_per_page_form .= "\n<!--[if !lt IE 9]><!-->\n";
	$records_per_page_form .= "<td>\n";
	$records_per_page_form .= "<!--<![endif]-->\n";
	$records_per_page_form .= "<!--[if lt IE 9]>\n";
	$records_per_page_form .= "<td valign=\"top\">\n";
	$records_per_page_form .= "<![endif]-->\n";

	
	$records_per_page_form .= '&nbsp;&nbsp;'.$normal_messages_ar['records_per_page'].'</form>';

	return $records_per_page_form;
} // end function build_records_per_page_form()

function get_table_alias($table_name)
{
	global $conn, $table_list_name, $quote;
	
	$sql = "SELECT alias_table FROM ".$quote.$table_list_name.$quote." WHERE name_table = '".$table_name."'";

	$res = execute_db($sql, $conn);
	$row = fetch_row_db($res);
	
	return $row['alias_table'];
}

function get_template_infos_ar($table_name)
{
	global $conn, $table_list_name, $quote;
	
	$sql = "SELECT enable_template_table, template_table FROM ".$quote.$table_list_name.$quote." WHERE name_table = '".$table_name."'";

	$res = execute_db($sql, $conn);
	$row = fetch_row_db($res);
	
	$template_infos_ar['enable_template_table'] = $row['enable_template_table'];
	$template_infos_ar['template_table'] = $row['template_table'];
	
	return $template_infos_ar;
}

function build_installed_table_infos_ar($only_include_allowed, $exclude_users_tab_if_not_admin)
// goal: build an an array containing infos about dadabik installed tables
// input: $only_include_allowed (0|1), 1 the table must be enabled, 2 must be enabled and list enabled (option 2 is not available any more), $exclude_users_tab_if_not_admin(0|1)
// output: the array
{
	global $table_list_name, $users_table_name, $conn, $quote, $current_user_is_administrator, $prefix_internal_table, $groups_table_name;
	
	$sql = "SELECT name_table, alias_table, enable_template_table, template_table FROM ".$quote.$table_list_name.$quote;

	if ($only_include_allowed === 1) {
		$sql .= " WHERE allowed_table = '1'";
	} // end if
	$sql .= " order by alias_table";

	$res = execute_db($sql, $conn);

	$i=0;
	
	$installed_table_infos_ar = array();

	while ($row = fetch_row_db($res)) {
		if ($current_user_is_administrator === 1 || ( ($row['name_table'] !== $users_table_name || $exclude_users_tab_if_not_admin === 0) && ($row['name_table'] !== $prefix_internal_table.'static_pages' || $exclude_users_tab_if_not_admin === 0)  && ($row['name_table'] !== $groups_table_name || $exclude_users_tab_if_not_admin === 0) )) {
		 	
		 	//if ($row['name_table'] !== $users_table_name || $exclude_users_tab_if_not_admin !== 2){
				$installed_table_infos_ar[$i]['name_table'] = $row['name_table'];
				$installed_table_infos_ar[$i]['alias_table'] = $row['alias_table'];
				$installed_table_infos_ar[$i]['enable_template_table'] = $row['enable_template_table'];
				$installed_table_infos_ar[$i]['template_table'] = $row['template_table'];
				$i++;
			//}
		} // end if
	} // end while

	return $installed_table_infos_ar;

} // end function build_installed_table_infos_ar()

function manage_other_infos_installation($function, $var_name, $var_value='')
// goal: select or update a var value from the field "other_infos_intallation of the installation table, according to its format var_name=var_value~var_name_2=var_value_2
// input: the function ('select'|'update') the variable name and the variable value (the last one just for update)
// output: the var value if select, void if update
{
	global $conn, $prefix_internal_table;
	$sql = "SELECT other_infos_installation FROM ".$prefix_internal_table."installation_tab";
	$res = execute_db($sql, $conn);
	
	$num_rows= get_num_rows_db($res);

	if ($num_rows === 1){ // just one record should be available
		$row = fetch_row_db($res);
		$other_infos_installation = $row['other_infos_installation'];
		$other_infos_installation_new = '';
		
		if ($other_infos_installation == '') { // this is the first variable
			if ($function === 'select'){
				return '';
			} // end if	
			$other_infos_installation_new = $var_name.'='.$var_value;
		} // end if
		else{
			$other_infos_installation_ar = explode('~', $other_infos_installation); // get the var_name=var_value couples
			$count_temp  = count($other_infos_installation_ar);
			
			$var_already_present = 0;
			for ($i=0;$i<$count_temp;$i++){ // for earch couple
				$equals_position = strpos_custom($other_infos_installation_ar[$i], '=');
				$var_name_registered = substr_custom($other_infos_installation_ar[$i], 0, $equals_position); // get the var name
				if ($var_name_registered === $var_name){
				
					if ($function === 'select'){
						return substr_custom($other_infos_installation_ar[$i], $equals_position+1); // return the var value
					} // end if	
					
					$other_infos_installation_ar[$i] = $var_name.'='.unescape($var_value);
					$var_already_present = 1;
				} // end if
			} // end for
			if ($var_already_present === 0){
		
				if ($function === 'select'){ // if here, the variable has not been found
					return '';
				}
				$other_infos_installation_ar[$count_temp] = $var_name.'='.unescape($var_value);
			} // end if
		
		foreach($other_infos_installation_ar as $other_infos_installation_ar_element){
			$other_infos_installation_new .= $other_infos_installation_ar_element.'~';
		} // end foreach
		
		$other_infos_installation_new = substr_custom($other_infos_installation_new, 0, strlen_custom($other_infos_installation_new)-1);
			
		} // end else
		
		$sql = "UPDATE ".$prefix_internal_table."installation_tab SET other_infos_installation = '".escape($other_infos_installation_new)."'";
		
		$res = execute_db($sql, $conn);
	} // end if
	else{
		echo "<p>An error occured";
	} // end else
	
	
} // end function manage_other_infos_installation

function build_permission_types_ar()
{
	global $conn, $prefix_internal_table, $quote;
	
	$permission_types_ar = array();
	$option_labels_ar = array();
	
	$sql = "SELECT label_permission_option, value_permission_option FROM ".$quote.$prefix_internal_table."permission_options".$quote;
	
	$res = execute_db($sql, $conn);
	
	while ($row = fetch_row_db($res)){
		$option_labels_ar[$row['value_permission_option']] = $row['label_permission_option'];
	}
	
	$sql = "SELECT id_permission_type, type_permission_type, object_type_permission_type, options_permission_type FROM ".$quote.$prefix_internal_table."permission_types".$quote;
	
	$res = execute_db($sql, $conn);
	
	while ($row = fetch_row_db($res)){
	
		$permission_types_ar[$row['id_permission_type']]['id_permission_type'] = $row['id_permission_type'];
		$permission_types_ar[$row['id_permission_type']]['type_permission_type'] = $row['type_permission_type'];
		$permission_types_ar[$row['id_permission_type']]['object_type_permission_type'] = $row['object_type_permission_type'];
		
		$temp_ar = explode(',', $row['options_permission_type']);
		
		foreach($temp_ar as $temp_ar_el){
			$permission_types_ar[$row['id_permission_type']]['options_permission_type'][$temp_ar_el] = $option_labels_ar[$temp_ar_el];
		}
	}
	
	return $permission_types_ar;
}

function build_permission_options_select($permission_types_ar, $id_subject, $id_permission_type, $value_permission, $object)
{
	
	foreach ($permission_types_ar as $permission_types_ar_el){
		if ($permission_types_ar_el['id_permission_type'] == $id_permission_type){
			$permission_options_select = '<select id="'.$object.'_'.$id_subject.'_'.$id_permission_type.'" name="'.$id_subject.'_'.$id_permission_type.'">';
			
			foreach ($permission_types_ar_el['options_permission_type'] as $option_value => $option_label){
				$permission_options_select .= '<option value="'.$option_value.'"';
				
				if ($option_value == $value_permission){
					$permission_options_select .= ' selected';
				}
				
				$permission_options_select .= '>'.$option_label.'</option>';
			}
			$permission_options_select .= '</select>';
			break;
		}
	}
	return $permission_options_select;
}

function build_subjects_select($subject)
{
	global $conn, $quote, $users_table_name, $users_table_username_field, $groups_table_name, $groups_table_name_field, $groups_table_id_field;
	
	$subjects_select = '<select name="subject">';
	$subjects_select .= '<option value=""></option>';
	
	/*
	$subjects_select .= '<optgroup label="Users">';
	
	$sql = "SELECT id_user, ".$quote.$users_table_username_field.$quote." FROM ".$quote.$users_table_name.$quote;
	
	$res = execute_db($sql, $conn);
	
	while ($row = fetch_row_db($res)){
		$subjects_select .= '<option value="user_'.$row['id_user'].'"';
		
		if ($subject == 'user_'.$row['id_user'] ){
			$subjects_select .= ' selected';
		}
		
		$subjects_select .= '>'.$row[$users_table_username_field].'</option>';
	}
	
	$subjects_select .= '</optgroup>';
	
	
	$subjects_select .= '<optgroup label="Groups">';
	*/
	$sql = "SELECT ".$quote.$groups_table_id_field.$quote.", ".$quote.$groups_table_name_field.$quote."  FROM ".$quote.$groups_table_name.$quote;
	
	$res = execute_db($sql, $conn);
	
	while ($row = fetch_row_db($res)){
		$subjects_select .= '<option value="group_'.$row[$groups_table_id_field].'"';
		
		if ($subject == 'group_'.$row[$groups_table_id_field] ){
			$subjects_select .= ' selected';
		}
		
		$subjects_select .= '>'.$row[$groups_table_name_field].'</option>';
	}
	
	/*
	$subjects_select .= '</optgroup>';
	*/
	/*
	$subjects_select .= '<optgroup label="Other">';
	$subjects_select .= '<option value="all_users"';
	
	if ($subject == 'all_users' ){
		$subjects_select .= ' selected';
	}
	
	$subjects_select .= '>All users</option>';
	
	$subjects_select .= '<option value="all_groups"';
	
	if ($subject == 'all_groups' ){
		$subjects_select .= ' selected';
	}
	$subjects_select .= '>All groups</option>';
	
	
	$subjects_select .= '</optgroup>';
	*/
	$subjects_select .= '</select>';
	
	return $subjects_select;
}

function build_objects_select($object)
{
	global $prefix_internal_table, $users_table_name, $groups_table_name;
	
	$tables_ar = build_tables_names_array();
	
	$objects_select = '<select name="object">';
	$objects_select .= '<option value=""></option>';
	
	$objects_select_2 = '';
	
	foreach ($tables_ar as $tables_ar_el){
	
		if ($tables_ar_el !== $prefix_internal_table.'static_pages' && $tables_ar_el !== $users_table_name && $tables_ar_el !== $groups_table_name){
	
			$objects_select .= '<option value="'.$tables_ar_el.'"';
			
			if ($object == $tables_ar_el ){
				$objects_select .= ' selected';
			}
				
			$objects_select .= '>'.$tables_ar_el.'</option>';
		}
		else{
			if ($objects_select_2 === ''){
				$objects_select_2 .= '<optgroup label="admin tables:">';
			}
			$objects_select_2 .= "<option value=\"".$tables_ar_el."\"";
			if ($object == $tables_ar_el){
				$objects_select_2 .= " selected";
			}
			$objects_select_2 .= ">".$tables_ar_el."</option>";
		
		}
		
	}	
			
	if ($objects_select_2 !== ''){
		$objects_select .= $objects_select_2.'</optgroup>';
	}
	
	
	$objects_select .= '</select>';
	
	return $objects_select;
}

function build_group_infos_ar($id_group)
{
	global $conn, $prefix_internal_table, $quote, $groups_table_name, $groups_table_name_field, $groups_table_id_field;
	
	$group_infos_ar = array();
	
	$sql = "SELECT ".$quote.$groups_table_id_field.$quote.", ".$quote.$groups_table_name_field.$quote." FROM ".$quote.$groups_table_name.$quote." WHERE ".$quote.$groups_table_id_field.$quote." = ".$id_group;
	
	$res = execute_db($sql, $conn);
	
	$row = fetch_row_db($res);
	
	$group_infos_ar['id_group'] = $row[$groups_table_id_field];
	$group_infos_ar['name_group'] = $row[$groups_table_name_field];
	
	$sql = "select subject_type_permission, object_type_permission, object_permission, id_permission_type, value_permission FROM ".$quote.$prefix_internal_table."permissions".$quote. " WHERE subject_type_permission = 'group' AND id_subject = '".$id_group."'";
	
	$res = execute_db($sql, $conn);
	
	$group_infos_ar['permissions'] = array();
	
	$cnt = 0;

	while ($row = fetch_row_db($res)) {
		$group_infos_ar['permissions'][$cnt]['object_type_permission'] = $row['object_type_permission'];
		$group_infos_ar['permissions'][$cnt]['object_permission'] = $row['object_permission'];
		$group_infos_ar['permissions'][$cnt]['id_permission_type'] = $row['id_permission_type'];
		$group_infos_ar['permissions'][$cnt]['value_permission'] = $row['value_permission'];
		
		$cnt++;
	
	}
	
	return $group_infos_ar;
	
	
}


?>
