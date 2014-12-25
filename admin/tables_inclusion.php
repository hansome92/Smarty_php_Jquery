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
include ("./include/functions.php");
include ("./include/common_start.php");
include ("./include/check_installation.php");
include ("./include/check_admin_login.php");

$page_name = 'tables_inclusion';


// variables:
// GET
// $table_name_2
// POST
// 
// $function from this page ("delete_records", "refresh_table",......)

// get the array containing the names of the tables installed
$installed_tables_ar = build_tables_names_array(0, 1, 1);

// get the table name to use in the second part of the administration
// the table used in this script (tables_inclusion.php) is table_name_2, why not table_name? table_name is just to take the table we are using in the admin and I don't want to change it in this page; I should have used sessions
if (isset($_GET["table_name"])){
	$table_name = $_GET["table_name"];
} // end if
else{
	if (count($installed_tables_ar)>0){
		// get the first table
		$table_name = $installed_tables_ar[0];
		
		// override if there is at least one non admin table
		foreach ($installed_tables_ar as $installed_tables_ar_el){
			if ($installed_tables_ar_el !== $users_table_name && $installed_tables_ar_el !== $groups_table_name && $installed_tables_ar_el !== $prefix_internal_table.'static_pages' ){
				$table_name = $installed_tables_ar_el;
				break;
			}
		}
		
		
	} // end if
} // end else

if (isset($_POST["function"])){
	$function = $_POST["function"];
} // end if
elseif (isset($_GET["function"])){
	$function = $_GET["function"];
} // end if
else{
	$function = "";
} // end else

// the table used in this script is table_name_2, why not table_name? table_name is just to take the table we are using in the admin and I don't want to change it in this page; I should have used sessions

if (isset($_GET["table_name_2"])){
	$table_name_2 = $_GET["table_name_2"];
} // end if

include ("./include/header.php");


?>

<?php

if (isset($_POST["allow_table_ar"])){
	$allow_table_ar = $_POST["allow_table_ar"];
}

if (isset($_POST["pk_field_table_ar"])){
	$pk_field_table_ar = $_POST["pk_field_table_ar"];
}

$confirmation_message = "";

if (isset($table_name_2)){
	$table_internal_name = $prefix_internal_table.$table_name_2;
} // end if

// this is useful to display the tables that could be installed
$temp_ar = build_tables_names_array(0, 0, 1);

// get all the tables except users groups and static
foreach ($temp_ar as $temp_ar_element) {
	if ($temp_ar_element !== $users_table_name && $temp_ar_element !== $groups_table_name && $temp_ar_element !== $prefix_internal_table."static_pages"){
		$complete_tables_names_ar[]=$temp_ar_element;
	}
}

switch($function){
	case "install_table":

		$unique_field_name = get_unique_field_db($table_name_2, 1);
		
		// get the array containing the names of the fields
		$fields_names_ar = build_fields_names_array($table_name_2);

		// delete the previous record about the table
		$sql = "delete from ".$quote.$table_list_name.$quote." where ".$quote."name_table".$quote." = '".$table_name_2."'";			
		$res_delete = execute_db($sql, $conn);
		
		$sql = "insert into ".$quote.$table_list_name.$quote." (".$quote."name_table".$quote.", ".$quote."allowed_table".$quote.", ".$quote."enable_insert_table".$quote.", ".$quote."enable_edit_table".$quote.", ".$quote."enable_delete_table".$quote.", ".$quote."enable_details_table".$quote.", ".$quote."enable_list_table".$quote.", ".$quote."alias_table".$quote.", pk_field_table) values ('".$table_name_2."', '1', '0', '0', '0', '0', '0', '".$table_name_2."', '".$unique_field_name."')";
		$res_insert = execute_db($sql, $conn);
		
		// delete info about the table (if already existent)
		$sql = "delete from ".$quote.$prefix_internal_table."forms".$quote." where ".$quote."table_name".$quote." = '".$table_name_2."'";
		
		$res_insert = execute_db($sql, $conn);
		
		/*
		if ($table_name_2 === $users_table_name) {
			$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_filter_form_field".$quote.",  ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.") VALUES ('id_user', 'id_user', 'text', 'alphanumeric', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 1, '~', '".$table_name_2."')";
			$res_insert = execute_db($sql, $conn);
			
			$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_filter_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.") VALUES ('user_type_user', 'User type', 'select_single', 'alphanumeric', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '~admin~normal~', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 2, '~', '".$table_name_2."')";
			$res_insert = execute_db($sql, $conn);
			
			$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_filter_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.") VALUES ('username_user', 'Username', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '', 3, '~', '".$table_name_2."')";
			$res_insert = execute_db($sql, $conn);
			
			$link_popup = escape('\'pwd.php\',\'\',600,300');

			$sql = "INSERT INTO ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."present_search_form_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_filter_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."table_name".$quote.") VALUES ('password_user', 'Password (hash)', 'text', 'alphanumeric', '0', '0', '1', '1', '1', '0', '1', '1', '0', '0', '', '', '', '', '', '', '', 'is_equal/is_different/contains/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty', '', '', '', '', '', '', '100', '<a href=\"javascript:void(generic_js_popup(".$link_popup."))\">crypter</a>', 4, '~', '".$table_name_2."')";
			$res_insert = execute_db($sql, $conn);
			
			
		} // end if
		else {
		*/
			for ($j=0; $j<count($fields_names_ar); $j++){
				// insert a new record in the internal table with the name of the field as name and label
				$sql = "insert into ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."table_name".$quote.") values ('".$fields_names_ar[$j]."', '".$fields_names_ar[$j]."', '".($j+1)."', '".$table_name_2."')";
				
				$res_insert = execute_db($sql, $conn);
			} // end for
		/*	
		} // end else
		*/
		
		$confirmation_message .= "Table ".$table_name_2." installed. You should now set the <a href=\"permissions_manager.php?table_name=".urlencode($table_name_2)."\">permissions</a> before using it.";

		if ($unique_field_name == ""){
			$confirmation_message .= "<p><b>Warning:</b> your table <b>".$table_name_2."</b> hasn't any primary keys set, if you don't set a primary key DaDaBIK won't show the edit/delete/details buttons.</p>";
		} // end if
		
		// re-get the array containing the names of the tables installed
		$installed_tables_ar = build_tables_names_array(0, 1, 1);
		
		// re-build the select with all installed table
		$change_table_select = build_change_table_select(0, 1);

		break;
	case "uninstall_table":
		
		begin_trans_db();
	
		// delete the table from table_list_name
		$sql = "DELETE FROM ".$quote.$table_list_name.$quote." WHERE name_table = '".$table_name_2."'";
		execute_db($sql, $conn);
		
		// delete info about the table
		$sql = "delete from ".$quote.$prefix_internal_table."forms".$quote." where ".$quote."table_name".$quote." = '".$table_name_2."'";
		execute_db($sql, $conn);
		
		// delete permissions for the table
		$sql = "DELETE FROM ".$quote.$prefix_internal_table."permissions".$quote." WHERE object_type_permission = 'table' AND object_permission = '".$table_name_2."'";
		$res = execute_db($sql, $conn);
		
		// delete permissions for the fields
		$sql = "DELETE FROM ".$quote.$prefix_internal_table."permissions".$quote." WHERE object_type_permission = 'field' AND object_permission like '".$table_name_2.".'";
		$res = execute_db($sql, $conn);
		
		
		complete_trans_db();
		

		$confirmation_message .= "Table ".$table_name_2." uninstalled.";

		// re-get the array containing the names of the tables installed
		$installed_tables_ar = build_tables_names_array(0, 1, 1);

		if (count($installed_tables_ar)>0){
			// get the first table
			$table_name_2 = $installed_tables_ar[0];
		} // end if

		if (isset($table_name_2)){
			// build the select with all installed table
			$change_table_select = build_change_table_select(0, 1);
			$table_internal_name = $prefix_internal_table.$table_name_2;
		} // end if

		break;
	case "update_pk_field":
		for ($i=0; $i<count($complete_tables_names_ar); $i++){
		} // end for

		//$installed_tables_ar = build_tables_names_array(0, 1); // reload to show the correct values

		$confirmation_message .= "Changes correctly saved.";

		break; // break case "include tables"
	case "include_tables":
		for ($i=0; $i<count($complete_tables_names_ar); $i++){
			if (in_array($complete_tables_names_ar[$i], $installed_tables_ar)){
			
				
			if (in_array($complete_tables_names_ar[$i], $installed_tables_ar)){
			
				$sql = "UPDATE ".$quote.$table_list_name.$quote." SET pk_field_table = '".$pk_field_table_ar[$i]."' WHERE name_table = '".$complete_tables_names_ar[$i]."'";
				
		
				execute_db($sql, $conn);
				
				
			}
			
			
			
				if (isset($allow_table_ar[$i])){
					if ($allow_table_ar[$i] == "1"){
						$sql = "update ".$quote."$table_list_name".$quote." set ".$quote."allowed_table".$quote." = '1' where ".$quote."name_table".$quote." = '".$complete_tables_names_ar[$i]."'";
					} // end if
				} // en if
				else{
					$sql = "update ".$quote."$table_list_name".$quote." set ".$quote."allowed_table".$quote." = '0' where ".$quote."name_table".$quote." = '".$complete_tables_names_ar[$i]."'";
				} // end else
			execute_db($sql, $conn);
			}
		} // end for

		//$installed_tables_ar = build_tables_names_array(0, 1); // reload to show the correct values

		$confirmation_message .= "Changes correctly saved.";

		break; // break case "include tables"
	default:
		break;
} // end switch
?>

<?php
if ($confirmation_message != ""){
	echo "<p><b><font color=\"#ff0000\">$confirmation_message</font></b></p>";
} // end if
?>

<table>
<tr><td valign="top">

<p><font size="+1">Which tables of the <strong><?php echo $db_name; ?></strong> database you want to use in this DaDaBIK application?</font></p>


<p>In order to use a table in a DaDaBIK application, the table must be <i>installed</i> and <i>enabled</i> in DaDaBIK. When you install DaDaBIK the first time, by default all the tables are installed and enabled.</p>

<ul>
<li>Uncheck <b>Enable</b> to temporarily exclude the table from DaDaBIK.</li>
<li>Click <b>Uninstall</b> to permanently remove the table from DaDaBKIK (you loose the DaDaBIK settings about that table).</li>
<li>For tables you don't have to set the primary key, DaDaBIK does it for you. For views, you have to do it.</li>
</ul>


<form name="include_tables_form" method="post" action="tables_inclusion.php?table_name=<?php echo urlencode($table_name); ?>">
<input type="hidden" name="function" value="include_tables">
<?php //if (count($installed_tables_ar) != 0){ ?>

<table cellpadding="3">
<tr>
<th style="text-align:left;padding: 0 80px 0 0">Table (or view) name</th>
<th style="text-align:left;padding: 0 80px 0 0">Installed</th>
<th style="text-align:left;padding: 0 80px 0 0">Enabled</th>
<th style="text-align:left;padding: 0 80px 0 0">Primary key</th>
</tr>
<?php


for ($i=0; $i<count($complete_tables_names_ar); $i++){
	echo "<tr><td>".$complete_tables_names_ar[$i]."</td>";
	
	echo "<td>";
	//echo "<td><input type=\"checkbox\" onclick=\"javascript:document.include_tables_form.submit()\" name=\"install_table_ar[$i]\" value=\"1\"";
	if (in_array($complete_tables_names_ar[$i], $installed_tables_ar)){
		echo "Yes (<a href=\"tables_inclusion.php?function=uninstall_table&table_name=".urlencode($table_name)."&table_name_2=".urlencode($complete_tables_names_ar[$i])."\">Uninstall</a>)</td>";
	} // end if
	else{
		echo "No (<a href=\"tables_inclusion.php?function=install_table&table_name=".urlencode($table_name)."&table_name_2=".urlencode($complete_tables_names_ar[$i])."\">Install</a>)</td>";
	}
	echo "<td>";
	if (in_array($complete_tables_names_ar[$i], $installed_tables_ar)){
	
		echo "<input type=\"checkbox\" onclick=\"javascript:document.include_tables_form.submit()\" name=\"allow_table_ar[$i]\" value=\"1\"";
		if (table_allowed($complete_tables_names_ar[$i])){
			echo " checked";
		} // end if	
		echo ">";
	}
	echo "</td>";
	
	echo "<td>";
	if (in_array($complete_tables_names_ar[$i], $installed_tables_ar)){
		$fields_ar = get_fields_list($complete_tables_names_ar[$i]);
		$pk_field = get_unique_field_db($complete_tables_names_ar[$i]);
		
		
		if (is_null($pk_field)){
			$pk_field = $fields_ar[0];
		}
		
		echo '<select name="pk_field_table_ar['.$i.']" onchange="javascript:document.include_tables_form.submit();">';
		echo '<option value=""></option>';
		foreach ($fields_ar as $fields_ar_el){
		
			echo '<option value="'.$fields_ar_el.'"';
			
			if ($pk_field === $fields_ar_el){
				echo ' selected';
			}
			
			echo '>'.$fields_ar_el.'</option>';
		}
		echo '</select>';
		echo "</td>";
	}
	else{
		echo "<td></td>";
	}
	
	echo "</tr>";
	
	
	
	
} // end for
?>
</table>


<br><!--<input type="submit" value="Save changes">-->

<?php
/* } // end if
else{	
	echo "No tables installed.";
} // end else
*/
?>

</form>



</td></tr></table>


<?php
// include footer
include ("./include/footer.php");
?>
