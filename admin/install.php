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
include ("./include/config.php");

if ($db_library === 'adodb'){
	include('./include/adodb'.DADABIK_ADODB_VERSION.'/adodb.inc.php');
	
	// hack for oracle, all field names fetched in lower case
	if ($dbms_type === 'oci8po') {
		define('ADODB_ASSOC_CASE', 0); 
	} // end if
}
include ("./include/functions.php");
include ("./include/common_start.php");
require ('./include/PasswordHash.php');

$page_name = 'install';
include ("./include/header.php");

// variables:
// GET
// table_name form admin.php
if (isset($_GET["table_name"])){
	$table_name = urldecode($_GET["table_name"]);
} // end if
else{
	$table_name = "";
} // end else

// POST
// $install from install.php (set to 1 when user click on install)

if (isset($_POST["install"])){
	$install = $_POST["install"];
} // end if
else{
	$install = "";
} // end else

if ($install == "1"){
	if ($table_name != ""){
		$tables_names_ar[0] = $table_name;
		
		if (!table_exists($table_list_name)){
			// drop (if present) the old table list table and create the new one
			create_table_list_table();
		} // end if
	} // end if
	else{
		// drop (if present) the old users table, groups table, logs table and locks table and create the new ones
		
		if ($users_table_name === $prefix_internal_table.'users'){
			create_users_table();
		}
		
		if ($groups_table_name === $prefix_internal_table.'groups'){
			create_groups_table();
		}
		
		create_locks_table();
		
		create_logs_table();
		
		create_internal_table ($prefix_internal_table.'forms');
		
		create_static_pages_table();
		
		create_permissions_tables();
		
		//if (!table_exists($prefix_internal_table.'installation_tab')){
		
			create_installation_table();
			
			$date_time = date("Y-m-d H:i:s");
			
			//if (isset($_POST['register']) && (int)$_POST['register'] === 1){
			
				$id_code_installation = @file_get_contents('http://www.dadabik.org/get_installation_id_3.php?dbms_type='.$dbms_type.'&dadabik_version='.urlencode('5.0 PRO').'&os='.urlencode(php_uname('s')).'&php_version='.urlencode(phpversion()).'&date_time='.urlencode($date_time));
			
				if ($id_code_installation === false){
					echo '<p>There are problems with the Internet connection, the on-line registration is not possible......</p>';
					$id_installation = 'NULL';
					$code_installation = '0';
				}
				else{
					$id_code_installation_ar = explode('|', $id_code_installation);
					$id_installation = (int)$id_code_installation_ar[0];
					$code_installation = $id_code_installation_ar[1];
					echo '<p>On-line registration done......your installation code is: '.$code_installation.'</p>';
				}
			/*
			}
			else{
				$id_installation = 'NULL';
				$code_installation = '0';
			}
			*/
			
			$sql = "INSERT INTO ".$quote.$prefix_internal_table.'installation_tab'.$quote." (date_time_installation, id_installation, code_installation, dbms_type_installation, dadabik_version_installation, dadabik_version_2_installation) VALUES ('".$date_time."', ".$id_installation.", '".$code_installation."', '".$dbms_type."', '5.0', 'PRO')";						
				
			$res_insert = execute_db($sql, $conn);
			
		//} // end if

		// get the array containing the names of the tables (excluding "dadabik_" ones)
		$tables_names_ar = build_tables_names_array(0, 0, 1);

		// drop (if present) the old table list table and create the new one
		create_table_list_table();
	} // end else
	
	for ($i=0; $i<count($tables_names_ar); $i++){
	
		$filters_cnt = 0;
		
		$table_name_temp = $tables_names_ar[$i];
		
		
		$table_internal_name_temp = $prefix_internal_table.$table_name_temp;

		$unique_field_name = get_unique_field_db($table_name_temp, 1);
	
		// get the array containing the names of the fields
		$fields_names_ar = build_fields_names_array($table_name_temp);

		// delete the previous record about the table
		$sql = "delete from ".$quote.$table_list_name.$quote." where ".$quote."name_table".$quote." = '".$table_name_temp."'";			
		$res_delete = execute_db($sql, $conn);

		// add the table to the table list table and set allowed to 1
		//$sql = "insert into ".$quote.$table_list_name.$quote." (".$quote."name_table".$quote.", ".$quote."allowed_table".$quote.", ".$quote."enable_insert_table".$quote.", ".$quote."enable_edit_table".$quote.", ".$quote."enable_delete_table".$quote.", ".$quote."enable_details_table".$quote.") values ('".$table_name_temp."', '1', '1', '1', '1', '1')";
		
		if ($table_name_temp === $prefix_internal_table."static_pages"){
			$alias_temp = 'static pages';
		}
		elseif ($table_name_temp === $users_table_name){
			$alias_temp = 'users';
		}
		elseif ($table_name_temp === $groups_table_name){
			$alias_temp = 'groups';
		}
		else{
			$alias_temp = $table_name_temp;
		}
		
		if (is_null($unique_field_name)){
			$pk_field_table = '';
		}
		else{
			$pk_field_table = $unique_field_name;
		}
		
		// install and set permissions for all the tables except users and groups if they are custom
		
		if ( ($table_name_temp !== $users_table_name || $users_table_name === $prefix_internal_table.'users') &&( $table_name_temp !== $groups_table_name || $groups_table_name === $prefix_internal_table.'groups')) {
		
			$sql = "insert into ".$quote.$table_list_name.$quote." (".$quote."name_table".$quote.", ".$quote."allowed_table".$quote.", ".$quote."enable_insert_table".$quote.", ".$quote."enable_edit_table".$quote.", ".$quote."enable_delete_table".$quote.", ".$quote."enable_details_table".$quote.", ".$quote."enable_list_table".$quote.", ".$quote."alias_table".$quote.", pk_field_table) values ('".$table_name_temp."', '1', '1', '1', '1', '1', '1', '".$alias_temp."', '".$pk_field_table."')";
			$res_insert = execute_db($sql, $conn);
	
			if ($table_name_temp === $users_table_name) {
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('id_user', 'id_user', 'text', 'alphanumeric', '1', '1', '1', '0', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 1, '~', '".$table_name_temp."', '1')";
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('username_user', 'Username', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '1', '1', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 3, '~', '".$table_name_temp."', '0')";
				$res_insert = execute_db($sql, $conn);
				
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('first_name_user', 'First name', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 4, '~', '".$table_name_temp."', '0')";
				$res_insert = execute_db($sql, $conn);
				
				
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('last_name_user', 'Last name', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 5, '~', '".$table_name_temp."', '0')";
				$res_insert = execute_db($sql, $conn);
				
				
				
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('email_user', 'Email', 'text', 'email', '1', '1', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 6, '~', '".$table_name_temp."', '0')";
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('id_group', 'Group', 'select_single', 'numeric', '1', '1', '1', '1', '1', '1', '1', '0', '0', '', 'id_group', '$groups_table_name', '', 'name_group', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 7, '~', '".$table_name_temp."', '0')";
				$res_insert = execute_db($sql, $conn);
				
				$link_popup = escape('\'pwd.php\',\'\',600,300');
	
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('password_user', 'Password (hash)', 'text', 'alphanumeric', '0', '0', '1', '1', '1', '1', '1', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '<a href=\"javascript:void(generic_js_popup(".$link_popup."))\">crypter</a>', 8, '~', '".$table_name_temp."', '0')";
				$res_insert = execute_db($sql, $conn);
			} // end if
			elseif ($table_name_temp === $groups_table_name) {
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('id_group', 'id_group', 'text', 'alphanumeric', '1', '1', '1', '0', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 1, '~', '".$table_name_temp."', '1')";
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('name_group', 'Group name', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '1', '1', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 2, '~', '".$table_name_temp."', '1')";
				$res_insert = execute_db($sql, $conn);
				
			} // end if
			elseif ($table_name_temp === $prefix_internal_table."static_pages") {
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('id_static_page', 'id_static_page', 'text', 'alphanumeric', '0', '0', '0', '0', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 1, '~', '".$table_name_temp."', '1')";
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('link_static_page', 'Link label', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '1', '1', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 2, '~', '".$table_name_temp."', '1')";
				
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('content_static_page', 'Content', 'rich_editor', 'html', '1', '1', '1', '1', '1', '1', '1', '1', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '5000', '', 3, '~', '".$table_name_temp."', '0')";
				
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('is_homepage_static_page', 'Is homepage?', 'select_single', 'alphanumeric', '1', '1', '1', '1', '1', '1', '1', '1', '0', '~y~n~', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '1', '', 4, '~', '".$table_name_temp."', '0')";
				
				$res_insert = execute_db($sql, $conn);
				
			} // end if
			else {
				for ($j=0; $j<count($fields_names_ar); $j++){
					// insert a new record in the internal table with the name of the field as name and label
					
					if ($filters_cnt >= 2){
						$sql = "insert into ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."table_name".$quote.") values ('".$fields_names_ar[$j]."', '".$fields_names_ar[$j]."', '".($j+1)."', '".$table_name_temp."')";
					}
					else{
						$filters_cnt++;
						$sql = "insert into ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") values ('".$fields_names_ar[$j]."', '".$fields_names_ar[$j]."', '".($j+1)."', '".$table_name_temp."', 1)";
					}
					
					$res_insert = execute_db($sql, $conn);
				} // end for
			} // end else
		
		
			// insert table permissions
			
			// for admin
			for ($j=1; $j<=5; $j++){
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('group', 1, 'table', '".escape($table_name_temp)."', ".$j.", '1')";
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('user', 1, 'table', '".escape($table_name_temp)."', ".$j.", '1')";
				$res_insert = execute_db($sql, $conn);
			}
			
			// for default
			
			if ( $table_name_temp !== $users_table_name && $table_name_temp !== $groups_table_name && $table_name_temp !== $prefix_internal_table.'static_pages') {
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('group', 2, 'table', '".escape($table_name_temp)."', 1, '1')";
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('user', 2, 'table', '".escape($table_name_temp)."', 1, '1')";
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('group', 2, 'table', '".escape($table_name_temp)."', 5, '1')";
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('user', 2, 'table', '".escape($table_name_temp)."', 5, '1')";
				$res_insert = execute_db($sql, $conn);
			}
			
			// insert field permissions
			
			$filters_cnt = 0;
			for ($j=0; $j<count($fields_names_ar); $j++){
				
				// for admin
				for ($z=7; $z<=12; $z++){
				
					if ( 
					
					($table_name_temp === $users_table_name && $fields_names_ar[$j] === 'id_user' && $z === 8) ||
					($table_name_temp === $groups_table_name && $fields_names_ar[$j] === 'id_group' && $z === 8) ||
					($table_name_temp === $prefix_internal_table."static_pages" && $fields_names_ar[$j] === 'id_static_page' && $z === 8) ||
					($table_name_temp === $users_table_name && $fields_names_ar[$j] === 'id_user' && $z === 9) ||
					($table_name_temp === $groups_table_name && $fields_names_ar[$j] === 'id_group' && $z === 9) ||
					($table_name_temp === $prefix_internal_table."static_pages" && $fields_names_ar[$j] === 'id_static_page' && $z === 9)
					
					){
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('group', 1, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', ".$z.", '0')";
					$res_insert = execute_db($sql, $conn);
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('user', 1, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', ".$z.", '0')";
					$res_insert = execute_db($sql, $conn);
					}
					else{
						if ($j < 2 || $z !== 11){ // just two filters, the first ones
							$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('group', 1, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', ".$z.", '1')";
							$res_insert = execute_db($sql, $conn);
						
							$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('user', 1, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', ".$z.", '1')";
							$res_insert = execute_db($sql, $conn);
						}
						else{
							$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('group', 1, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', ".$z.", '0')";
							$res_insert = execute_db($sql, $conn);
						
							$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('user', 1, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', ".$z.", '0')";
							$res_insert = execute_db($sql, $conn);
						}
					}
				}
				
				// for default
				if ( $table_name_temp !== $users_table_name && $table_name_temp !== $groups_table_name && $table_name_temp !== $prefix_internal_table.'static_pages') {
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('group', 2, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', 7, '1')";
					$res_insert = execute_db($sql, $conn);
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('user', 2, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', 7, '1')";
					$res_insert = execute_db($sql, $conn);
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('group', 2, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', 10, '1')";
					$res_insert = execute_db($sql, $conn);
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('user', 2, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', 10, '1')";
					$res_insert = execute_db($sql, $conn);
					
					if ($j < 2){ // just two filters
					
						$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('group', 2, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', 11, '1')";
						$res_insert = execute_db($sql, $conn);
						
						$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('user', 2, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', 11, '1')";
						$res_insert = execute_db($sql, $conn);
					}
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('group', 2, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', 12, '1')";
					$res_insert = execute_db($sql, $conn);
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('user', 2, 'field', '".escape($table_name_temp).".".escape($fields_names_ar[$j])."', 12, '1')";
					$res_insert = execute_db($sql, $conn);
				}
			} // end for
			
			if ($unique_field_name == ""){
				echo "<p><b>Warning:</b> your table <b>".$table_name_temp."</b> hasn't any primary keys set, if you don't set a primary key DaDaBIK won't show the edit/delete/details buttons.";
			} // end if
		}
	} // end for
	
	
	$res = execute_db($sql, $conn);
	
	echo "<h2>DaDaBIK has been correctly installed</h2>";
	echo "<p>You can now <a href=\"".$dadabik_main_file."\">view the database application</a> you have just created with DaDaBIK or customize the application from the <a href=\"admin.php\">admin</a> area.</p>";
	
	echo "<p>Default admin user: root (password: letizia); default normal user: alfonso (password: letizia). Please change the default passwords as soon as you can.</p>";
	
	if ($export_to_csv_feature === 1){
		echo "<p>The \"export to CSV\" feature is enabled; if your DaDaBIK application is public, please consider disabling it from /include/config.php because robots, accessing the CSV export link, could consume an inordinate amount of processor time.</p>";
	}
} // end if ($install == "1")
else{
	echo "<p><form name=\"install_form\" action=\"install.php?table_name=".urlencode($table_name)."\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"install\" value=\"1\">";
	if ( $table_name != "") {
		echo "<input type=\"submit\" value=\"Click this button to install ".$table_name." table\">";
	} // end if
	if ( $table_name == "") {
		echo "<h2>Welcome to the installation procedure, click the install button to proceed</h2>";
		echo "<p>Please note that if DaDaBIK is already installed in the <b>".$db_name."</b> database, the installation will overwrite the previous DaDaBIK configuration tables. The installation will also overwrite, if existent, the tables <b>".$users_table_name."</b> and <b>".$groups_table_name."</b>.";
		if ($dbms_type === 'oci8po') {
			echo "<p>Please note that, if you are re-installing DaDaBIK int the <b>".$db_name." database, you should manually delete the SEQ_USERS_TAB sequence automatically created by DaDaBIK in the previous installation before proceeding this installation.";
		} // end if
		//echo "<br/><br/><input type=\"submit\" value=\"INSTALL >>\" style=\"font-size:20px;\"><br/><br/><input type=\"checkbox\" name=\"register\" value=\"1\" checked> Communicate to www.dadabik.org the following information for statistic purposes (no other info will be sent): <br/><br/>dbms_type = ".$dbms_type."<br/>dadabik_version = 4.5<br/>os = ".(php_uname('s'))."<br/>php_version = ".(phpversion())."<br/>date_time = ".date("Y-m-d H:i:s");
		echo "<br/><br/><input type=\"submit\" value=\"INSTALL >>\" style=\"font-size:20px;\"><br/><br/>The following info will be sent to  www.dadabik.org  (no other info will be sent): <br/><br/>dbms_type = ".$dbms_type."<br/>dadabik_version = 5.0 PRO<br/>os = ".(php_uname('s'))."<br/>php_version = ".(phpversion())."<br/>date_time = ".date("Y-m-d H:i:s");
		echo "<br/><br/>You will get an installation code, use it if you need support for this installation.";
	}
	echo "</form>";
} // end else

// include footer
include ("./include/footer.php");
?>
