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

// include config, functions, common and header
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

$page_name = 'upgrade';

include ("./include/check_installation.php");
require ('./include/PasswordHash.php');
include ("./include/header.php");

// variables:

if (isset($_POST["upgrade"])){
	$upgrade = (int)$_POST["upgrade"];
} // end if
else{
	$upgrade = "";
} // end else

if ($upgrade === 1){

	if ($dbms_type === 'sqlite'){	
		echo '<p>Due to SQLite limitations, the upgrade is available just for MySQL and PostgreSQL.</p>';
	}
	else{
	
		begin_trans_db();

		$temp_ar = get_current_version(0);
		
		$temp_ar_2 = explode(',', $temp_ar);
		
		$current_version = $temp_ar_2[0];
		
		switch ($current_version){
			case '4.3':
			case '4.4 beta':
			case '4.4 alpha':
			case '4.4':
			case '4.4_pl1':
			
				create_locks_table();
				
				create_internal_table ($prefix_internal_table.'forms');
				
				if ($dbms_type === 'sqlite'){
					$sql = "DELETE FROM ".$quote.$table_list_name.$quote." WHERE name_table = 'sqlite_sequence'";
					
					$res = execute_db($sql, $conn);
				}
				
				$tables_ar = build_tables_names_array($exclude_not_allowed = 0, $exclude_not_installed = 1, $inlcude_users_table = 1);
				
				foreach ($tables_ar as $table_name_temp){
					$sql = "select * from ".$quote.$prefix_internal_table.$table_name_temp.$quote;
					
					$res = execute_db($sql, $conn);
			
					while($row = fetch_row_db($res)){
	
						$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.") VALUES ('".escape($row['name_field'])."', '".escape($row['label_field'])."', '".escape($row['type_field'])."', '".escape($row['content_field'])."', '".escape($row['present_search_form_field'])."', '".escape($row['present_results_search_field'])."', '".escape($row['present_details_form_field'])."', '".escape($row['present_insert_form_field'])."', '".escape($row['present_ext_update_form_field'])."', '".escape($row['required_field'])."', '".escape($row['check_duplicated_insert_field'])."', '".escape($row['other_choices_field'])."', '".escape($row['select_options_field'])."', '".escape($row['primary_key_field_field'])."', '".escape($row['primary_key_table_field'])."', '".escape($row['primary_key_db_field'])."', '".escape($row['linked_fields_field'])."', '".escape($row['linked_fields_order_by_field'])."', '".escape($row['linked_fields_order_type_field'])."', '".escape($row['select_type_field'])."', '".escape($row['items_table_names_field'])."', '".escape($row['items_table_fk_field_names_field'])."', '".escape($row['prefix_field'])."', '".escape($row['default_value_field'])."', '".escape($row['width_field'])."', '".escape($row['height_field'])."', '".escape($row['maxlength_field'])."', '".escape($row['hint_insert_field'])."', '".escape($row['order_form_field'])."', '".escape($row['separator_field'])."', '".escape($table_name_temp)."')";
						
						$res_2 = execute_db($sql, $conn);
						
					}
						
					if (true || $dbms_type === 'sqlite' || $dbms_type === 'sqlite2'){
					
						drop_table_db($conn, $prefix_internal_table.$table_name_temp);
						
					}
					else{
						$data_dictionary = NewDataDictionary($conn);
	
						drop_table_db($conn, $data_dictionary, $prefix_internal_table.$table_name_temp);
					}
				}
			case '4.3':
			case '4.4 beta':
			case '4.4 alpha':
			case '4.4':
			case '4.4_pl1':
			case '4.5 beta':
			case '4.5':
			
				/*
				create_users_table();
				
				$link_popup = escape('\'pwd.php\',\'\',600,300');
				
				$sql = "UPDATE ".$quote.$prefix_internal_table."forms".$quote." SET label_field = 'Password (hash)', hint_insert_field = '<a href=\"javascript:void(generic_js_popup(".$link_popup."))\">crypter</a>' where table_name = '".$users_table_name."' AND name_field = 'password_user'";
				
				$res = execute_db($sql, $conn);
				
				if ($res !== 1){
					echo 'Error during labels change.';
					exit;
				}
				*/
			
			case '4.5_pl1':
			case '4.6_beta':
			
				/*
			
				if ($dbms_type === 'mysql'){
			
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  label_field  label_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  primary_key_field_field  primary_key_field_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  primary_key_table_field  primary_key_table_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  linked_fields_field  linked_fields_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  linked_fields_order_by_field  linked_fields_order_by_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  items_table_names_field  items_table_names_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  items_table_fk_field_names_field  items_table_fk_field_names_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  prefix_field  prefix_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  default_value_field  default_value_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  width_field  width_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  height_field  height_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  maxlength_field  maxlength_field VARCHAR( 500 ) NOT NULL DEFAULT  '100'";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  hint_insert_field  hint_insert_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." CHANGE  separator_field  separator_field VARCHAR( 500 ) NOT NULL DEFAULT  '~'";
					$res = execute_db($sql, $conn);
				}
				else{
			
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   label_field type varchar( 500 ) ";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   primary_key_field_field type varchar( 500 ) ";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   primary_key_table_field type varchar( 500 ) ";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   linked_fields_field type varchar( 500 ) ";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   linked_fields_order_by_field type varchar( 500 ) ";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   items_table_names_field type varchar( 500 ) ";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   items_table_fk_field_names_field type varchar( 500 ) ";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   prefix_field type varchar( 500 ) ";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   default_value_field type varchar( 500 ) ";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   width_field type varchar( 500 ) ";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   height_field type varchar( 500 ) ";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   maxlength_field type varchar( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   hint_insert_field type varchar( 500 ) ";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE  ".$quote.$prefix_internal_table."forms".$quote." alter column   separator_field type varchar( 500 )";
					$res = execute_db($sql, $conn);
				}
				*/
			case '4.6':
			
				$sql = "DROP table if exists users_tab";
				$res = execute_db($sql, $conn);
				
				$sql = "DELETE from ".$quote.$prefix_internal_table."forms".$quote." WHERE table_name = 'users_tab'";
				$res = execute_db($sql, $conn);
				
				$sql = "DELETE from ".$quote.$table_list_name.$quote." WHERE name_table = 'users_tab'";
				$res = execute_db($sql, $conn);
				
				create_users_table();
				
				create_groups_table();
				
				create_static_pages_table();
				
				create_permissions_tables();
				
				if ($prefix_internal_table !== 'dadabik_'){
				
					if ($dbms_type === 'mysql'){
					
						$sql = "RENAME TABLE dadabik_table_list TO ".$quote.$table_list_name.$quote;
					}
					else{
						$sql = "ALTER TABLE dadabik_table_list RENAME TO ".$quote.$table_list_name.$quote;
					}
					$res = execute_db($sql, $conn);
				}
				
				$sql = "ALTER TABLE ".$quote.$table_list_name.$quote." ADD  pk_field_table VARCHAR( 500 ) NOT NULL default ''";
				$res = execute_db($sql, $conn);
				
				
				$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." ADD COLUMN search_new_line_after_field varchar(1) NOT NULL default '1',  ADD COLUMN present_filter_form_field varchar(1)  default '0'";
				$res = execute_db($sql, $conn);
				
				
				$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." ADD COLUMN details_new_line_after_field VARCHAR(1) NOT NULL  default '1' , ADD COLUMN insert_new_line_after_field VARCHAR(1) NOT NULL default '1'";
				$res = execute_db($sql, $conn);
				
				
				$sql = "ALTER TABLE ".$quote.$table_list_name.$quote." ADD COLUMN enable_template_table VARCHAR(1) NOT NULL DEFAULT '0' , ADD COLUMN template_table TEXT NOT NULL default '' ";
				$res = execute_db($sql, $conn);
				
				$sql = "update ".$quote.$table_list_name.$quote." set enable_template_table = '0', template_table = ''";
				$res = execute_db($sql, $conn);
	
				$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." ADD  present_edit_form_field VARCHAR( 1 ) NOT NULL DEFAULT  '1' ";
				$res = execute_db($sql, $conn);
				
				$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." ADD  insert_separator_before_field VARCHAR( 100 ) NOT NULL DEFAULT  '' , ADD  edit_separator_before_field VARCHAR( 100 ) NOT NULL DEFAULT  ''  , ADD  details_separator_before_field VARCHAR( 100 ) NOT NULL DEFAULT  ''  , ADD  search_separator_before_field VARCHAR( 100 ) NOT NULL DEFAULT  '' ";
				$res = execute_db($sql, $conn);
				
				$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." ADD  edit_new_line_after_field VARCHAR( 1 ) NOT NULL DEFAULT  '1', ADD  tooltip_field VARCHAR( 500 ) NOT NULL  default '', ADD  where_clause_field VARCHAR( 500 ) NOT NULL default ''";
				$res = execute_db($sql, $conn);
				
				$sql = "select name_table from ".$quote.$table_list_name.$quote;
				$res = execute_db($sql, $conn);
				
				while($row = fetch_row_db($res)){
					$name_table = $row['name_table'];
					
					$unique_field_name = get_unique_field_db($name_table, 1);
					
					if (is_null($unique_field_name)){
						$pk_field_table = '';
					}
					else{
						$pk_field_table = $unique_field_name;
					}
					
					$sql = "update ".$quote.$table_list_name.$quote." SET pk_field_table = '".$pk_field_table."' WHERE name_table = '".$name_table."'";
					$res_2 = execute_db($sql, $conn);
					
				}
				
				
				$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." ADD  custom_validation_function_field VARCHAR( 255 ) NOT NULL  default ''";
				$res = execute_db($sql, $conn);
				
				$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." ADD  custom_formatting_function_field VARCHAR( 255 ) NOT NULL  default ''";
				$res = execute_db($sql, $conn);
				
				$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." ADD  custom_csv_formatting_function_field VARCHAR( 255 ) NOT NULL default ''";
				$res = execute_db($sql, $conn);
				
				if ($dbms_type === 'mysql'){
				
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  select_type_field  select_type_field VARCHAR( 200 ) NOT NULL DEFAULT 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty'";
					$res = execute_db($sql, $conn);
				}
				else{
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column select_type_field type VARCHAR( 200 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column select_type_field set default 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty'";
					$res = execute_db($sql, $conn);
				}
				
				
				
				
				if ($dbms_type === 'mysql'){
	
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  select_options_field  select_options_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  linked_fields_order_by_field  linked_fields_order_by_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  label_field  label_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  custom_validation_function_field  custom_validation_function_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  custom_formatting_function_field  custom_formatting_function_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  custom_csv_formatting_function_field  custom_csv_formatting_function_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  primary_key_field_field  primary_key_field_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  primary_key_table_field  primary_key_table_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  linked_fields_field  linked_fields_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  items_table_names_field  items_table_names_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  items_table_fk_field_names_field  items_table_fk_field_names_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  prefix_field  prefix_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  default_value_field  default_value_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  width_field  width_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  height_field  height_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  maxlength_field  maxlength_field VARCHAR( 500 ) NOT NULL DEFAULT  '100'";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  hint_insert_field  hint_insert_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  separator_field  separator_field VARCHAR( 500 ) NOT NULL DEFAULT  '~'";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  insert_separator_before_field  insert_separator_before_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  edit_separator_before_field  edit_separator_before_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  search_separator_before_field  search_separator_before_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." CHANGE  details_separator_before_field  details_separator_before_field VARCHAR( 500 ) NOT NULL DEFAULT  ''";
					$res = execute_db($sql, $conn);
				}
				else{
	
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  select_options_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  linked_fields_order_by_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  label_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  custom_validation_function_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  custom_formatting_function_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  custom_csv_formatting_function_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  primary_key_field_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  primary_key_table_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  linked_fields_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  items_table_names_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  items_table_fk_field_names_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  prefix_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  default_value_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  width_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  height_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  maxlength_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  hint_insert_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  separator_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  insert_separator_before_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  edit_separator_before_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  search_separator_before_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
					
					$sql = "ALTER TABLE ".$quote.$prefix_internal_table."forms".$quote." alter column  details_separator_before_field  type VARCHAR( 500 )";
					$res = execute_db($sql, $conn);
				}
				
				
				$sql = "ALTER TABLE  ".$quote.$table_list_name.$quote." ADD  enable_delete_authorization_table VARCHAR( 1 ) NOT NULL default '0', ADD  enable_update_authorization_table VARCHAR( 1 ) NOT NULL default '0', ADD  enable_browse_authorization_table VARCHAR( 1 ) NOT NULL default '0'";
				$res = execute_db($sql, $conn);
				
				$sql = "ALTER TABLE ".$quote.$prefix_internal_table."installation_tab".$quote." ADD dadabik_version_2_installation VARCHAR( 10 ) NOT NULL default ''";
				$res = execute_db($sql, $conn);
				
				$sql = "UPDATE ".$quote.$prefix_internal_table."installation_tab".$quote." SET dadabik_version_2_installation = 'PRO'";
				$res = execute_db($sql, $conn);
				
				
				if ( $users_table_name === $prefix_internal_table.'users' ) {
			
					$sql = "insert into ".$quote.$table_list_name.$quote." (".$quote."name_table".$quote.", ".$quote."allowed_table".$quote.", ".$quote."enable_insert_table".$quote.", ".$quote."enable_edit_table".$quote.", ".$quote."enable_delete_table".$quote.", ".$quote."enable_details_table".$quote.", ".$quote."enable_list_table".$quote.", ".$quote."alias_table".$quote.", pk_field_table) values ('".$users_table_name."', '1', '1', '1', '1', '1', '1', 'users', 'id_user')";
					$res_insert = execute_db($sql, $conn);
					
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('id_user', 'id_user', 'text', 'alphanumeric', '1', '1', '1', '0', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 1, '~', '".$users_table_name."', '1')";
					$res_insert = execute_db($sql, $conn);
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('username_user', 'Username', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '1', '1', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 3, '~', '".$users_table_name."', '0')";
					$res_insert = execute_db($sql, $conn);
					
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('first_name_user', 'First name', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 4, '~', '".$users_table_name."', '0')";
					$res_insert = execute_db($sql, $conn);
					
					
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('last_name_user', 'Last name', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 5, '~', '".$users_table_name."', '0')";
					$res_insert = execute_db($sql, $conn);
					
					
					
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('email_user', 'Email', 'text', 'email', '1', '1', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 6, '~', '".$users_table_name."', '0')";
					$res_insert = execute_db($sql, $conn);
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('id_group', 'Group', 'select_single', 'numeric', '1', '1', '1', '1', '1', '1', '1', '0', '0', '', 'id_group', '$groups_table_name', '', 'name_group', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 7, '~', '".$users_table_name."', '0')";
					$res_insert = execute_db($sql, $conn);
					
					$link_popup = escape('\'pwd.php\',\'\',600,300');
		
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('password_user', 'Password (hash)', 'text', 'alphanumeric', '0', '0', '1', '1', '1', '1', '1', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '<a href=\"javascript:void(generic_js_popup(".$link_popup."))\">crypter</a>', 8, '~', '".$users_table_name."', '0')";
					$res_insert = execute_db($sql, $conn);
				}
				
				if ( $groups_table_name === $prefix_internal_table.'groups' ) {
					$sql = "insert into ".$quote.$table_list_name.$quote." (".$quote."name_table".$quote.", ".$quote."allowed_table".$quote.", ".$quote."enable_insert_table".$quote.", ".$quote."enable_edit_table".$quote.", ".$quote."enable_delete_table".$quote.", ".$quote."enable_details_table".$quote.", ".$quote."enable_list_table".$quote.", ".$quote."alias_table".$quote.", pk_field_table) values ('".$groups_table_name."', '1', '1', '1', '1', '1', '1', 'groups', 'id_group')";
					$res_insert = execute_db($sql, $conn);
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('id_group', 'id_group', 'text', 'alphanumeric', '1', '1', '1', '0', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 1, '~', '".$groups_table_name."', '1')";
					$res_insert = execute_db($sql, $conn);
					
					$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('name_group', 'Group name', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '1', '1', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 2, '~', '".$groups_table_name."', '1')";
					$res_insert = execute_db($sql, $conn);
				}
				
					
				$sql = "insert into ".$quote.$table_list_name.$quote." (".$quote."name_table".$quote.", ".$quote."allowed_table".$quote.", ".$quote."enable_insert_table".$quote.", ".$quote."enable_edit_table".$quote.", ".$quote."enable_delete_table".$quote.", ".$quote."enable_details_table".$quote.", ".$quote."enable_list_table".$quote.", ".$quote."alias_table".$quote.", pk_field_table) values ('".$prefix_internal_table."static_pages', '1', '1', '1', '1', '1', '1', 'static pages', 'id_static_page')";
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('id_static_page', 'id_static_page', 'text', 'alphanumeric', '0', '0', '0', '0', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 1, '~', '".$prefix_internal_table."static_pages', '1')";
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('link_static_page', 'Link label', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '1', '1', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 2, '~', '".$prefix_internal_table."static_pages', '1')";
				
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('content_static_page', 'Content', 'rich_editor', 'html', '1', '1', '1', '1', '1', '1', '1', '1', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '5000', '', 3, '~', '".$prefix_internal_table."static_pages', '0')";
				
				$res_insert = execute_db($sql, $conn);
				
				$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.", ".$quote."present_filter_form_field".$quote.") VALUES ('is_homepage_static_page', 'Is homepage?', 'select_single', 'alphanumeric', '1', '1', '1', '1', '1', '1', '1', '1', '0', '~y~n~', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '1', '', 4, '~', '".$prefix_internal_table."static_pages', '0')";
				$res_insert = execute_db($sql, $conn);
				
				
				
				
				
				
				
				
				
				
				// insert table permissions just for users groups and static pages, and just for admin
				
				$table_names_ar_temp[0] = $users_table_name;
				$table_names_ar_temp[1] = $groups_table_name;
				$table_names_ar_temp[2] = $prefix_internal_table.'static_pages';
				
				foreach ($table_names_ar_temp as $table_name_temp){
				
					$fields_names_ar = build_fields_names_array($table_name_temp);
					
					if ($table_name_temp === $users_table_name && $users_table_name === $prefix_internal_table.'users' || $table_name_temp === $groups_table_name && $groups_table_name === $prefix_internal_table.'groups' || $table_name_temp === $prefix_internal_table.'static_pages') { // don't do for custom users/groups table
					
						// for admin
						for ($j=1; $j<=5; $j++){
							$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('group', 1, 'table', '".escape($table_name_temp)."', ".$j.", '1')";
							$res_insert = execute_db($sql, $conn);
							
							$sql = "INSERT INTO ".$quote.$prefix_internal_table."permissions".$quote."  (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) values ('user', 1, 'table', '".escape($table_name_temp)."', ".$j.", '1')";
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
						} // end for
					}
				}
				
				
				
				$date_time = date("Y-m-d H:i:s");
						
				if (isset($_POST['register']) && (int)$_POST['register'] === 1){
				
					$sql = "SELECT id_installation, code_installation FROM ".$quote.$prefix_internal_table.'installation_tab'.$quote;
					
					$res = execute_db($sql, $conn);
					
					$row = fetch_row_db($res);
					
					$id_installation = $row['id_installation'];
					$code_installation = $row['code_installation'];
						
					$upgrade_process_result = @file_get_contents('http://www.dadabik.org/process_upgrade.php?id_installation='.$id_installation.'&code_installation='.urlencode($code_installation).'&dbms_type='.$dbms_type.'&dadabik_version='.urlencode('5.0'));
						
					if ($upgrade_process_result === false){
						echo '<p>There are problems with the Internet connection, the on-line registration is not possible......</p>';
					} // end if
					else{
						echo '<p>On-line registration done......</p>';
					} // end else
				}
				
				manage_other_infos_installation('update', 'upgrade_version','5.0');
				manage_other_infos_installation('update', 'upgrade_time',time());
				manage_other_infos_installation('update', 'upgrade_dbms',$dbms_type);
				
				echo "<p>......DaDaBIK correctly upgraded!! You can now <a href=\"index.php\">start using it</a>.</p>";
				
				echo "<p>If you have granular permissions enabled (as it is by default on DaDaBIK PRO and ENTERPRISE) you should first go to the <a href=\"admin.php\">admin</a> section and set the permissions for each table, field and group, otherwise you will not see any table in your DaDaBIK application.</p>";
				
				echo "<p>If you prefer to have a look at the new DaDaBIK immediately, you can temporary disable granular permissions from the <b>config.php file</b>.</p>";
				
				break;
				
			case '5.0':
				echo '<p>This procedure provides an upgrade to version 5.0 but the version installed is already 5.0.</p>';
				break;
			default:
				echo '<p>Your current version is prior to 4.3 final, it is not possible to upgrade it using this procedure. You have to <a href="install.php">install</a> DaDaBIK from scratch.</p>';
				break;
		}
		
		complete_trans_db();
	}
} // end if ($upgrade === 1)
else{

	if ($dbms_type === 'sqlite'){	
		echo '<p>Due to SQLite limitations, the upgrade is available just for MySQL and PostgreSQL.</p>';
	}
	else{

		$temp_ar = get_current_version(0);
		
		$temp_ar_2 = explode(',', $temp_ar);
		
		$current_version = $temp_ar_2[0];
		
		echo '<p>Your current DaDaBIK version is <b>'.$current_version.'</b>';
		
		switch ($current_version){
			case '4.3':
			case '4.4 beta':
			case '4.4 alpha':
			case '4.4':
			case '4.4_pl1':
			case '4.5 beta':
			case '4.5':
			case '4.5_pl1':
			case '4.6_beta':
			case '4.6':
				echo '<p>This upgrade procedure allows you to upgrade DaDaBIK to version <b>5.0</b></p>';
				
				echo '<p>Before proceeding, you should have:<ul><li>Replaced all the old DaDaBIK files with the new ones (keep your upload folder if you want to save the uploaded files);</li><li>Updated your new /include/config.php file using your old configuration settings.</li></ul>If you are here, you have probably already done the first point and at least part of the second one.</p>';
				
				echo '<p>The upgrade will not affect your DaDaBIK configuration and your data but, just in case, please create a backup of your database before upgrading.</p>';
				
				echo '<p>Please note that, if there are some locked records (DaDaBIK record locking feature), the records could be unlocked by this procedure before upgrading.</p>';
				
				echo '<p><font color="red">Please note that, due to the change in users management in version 5.0 this procedure will delete your existing users table users_tab and create a new one (called '.$prefix_internal_table.'users) containing just an admin user (root, password "letizia") and a normal user (alfonso, password "letizia") and so after the upgrade YOUR APPLICATION WILL BE ACCESSIBLE VIA THE DEFAULT PASSWORDS, all the interface configurations about the users table will also be replaced with new ones.</font></p>';
				
				
				
				echo '<p>Please note that DaDaBIK 5.0 introduced a lot of changes, some of them could lead to unexpected results for DaDaBIK 4.x installations, including:
				<ul>
				<li>The default value defined in interface configurator (now forms configurator) is now used even if the corresponding field is set not to be present in the insert form.</li>
				<li>If a default value starts with SQL:, instead of taking a static default value, the following SELECT query (if any) is executed and the first selected field of the first row returned is used as default value.</li>
				<li>The field type "password" is not supported anymore, you should convert your "password" fields (if any) to the type "text" before upgrading to DaDaBIK 5.</li>
				</ul>
				Please read the <b>change log</b> to understand all the changes, fixes and new features.</p>';
				
				echo '<p>If you changed the default users table name (users_tab) or the default table_list table name (dadabik_table_list), you cannot use this upgrade procedure.</p>';
				
				
				echo '<form action="upgrade.php" method="post">';
				echo '<input type="hidden" name="upgrade" value="1">';
		
				echo "<input type=\"checkbox\" name=\"register\" value=\"1\" checked> Communicate this upgrade to www.dadabik.org<br>Just the DBMS type (e.g. MySQL) and the DaDaBIK version (e.g. 4.4) will be transmitted, NO PERSONAL INFORMATION. This is usefult to keep track of the DaDaBIK installation upgrades worldwide.<br><br>";
				echo '<input type="submit" value="Click this button AND WAIT to upgrade DaDaBIK">';
				echo '</form>';
				break;
			case '5.0':
				echo '<p>This procedure provides an upgrade to version 5.0 but the version installed is already 5.0.</p>';
				break;
			default:
				echo '<p>Your current version is prior to 4.3 final, it is not possible to upgrade it using this procedure. You have to <a href="install.php">install</a> DaDaBIK from scratch.</p>';
				break;
		}
	}
} // end else

// include footer
include ("./include/footer.php");
?>