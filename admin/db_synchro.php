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

$page_name = 'db_synchro';


// variables:
// GET
// $table_name
// POST
// 
// $function from this page ("delete_records", "refresh_table",......)

// get the array containing the names of the tables installed
$installed_tables_ar = build_tables_names_array(0, 1, 1);

// get the table name to use in the second part of the administration
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

if (!in_array($table_name, $installed_tables_ar)){
	header('Location:'.$site_url.'permissions_manager.php');
}

if (isset($_POST["function"])){
	$function = $_POST["function"];
} // end if
elseif (isset($_GET["function"])){
	$function = $_GET["function"];
} // end if
else{
	$function = "";
} // end else

include ("./include/header.php");
?>

<?php



if (isset($_POST["deleted_fields_ar"])){
	$deleted_fields_ar = $_POST["deleted_fields_ar"];
} // end if
if (isset($_POST["field_to_change_name"])){
	$field_to_change_name = $_POST["field_to_change_name"];
} // end if
if (isset($_POST["field_to_change_name"])){
	$field_to_change_name = $_POST["field_to_change_name"];
} // end if
if (isset($_POST["field_to_change_new_position"])){
$field_to_change_new_position = $_POST["field_to_change_new_position"];
} // end if
if (isset($_POST["old_field_name"])){
	$old_field_name = $_POST["old_field_name"];
} // end if
if (isset($_POST["new_field_name"])){
	$new_field_name = $_POST["new_field_name"];
} // end if
if (isset($_POST["new_field_name"])){
	$new_field_name = $_POST["new_field_name"];
} // end if


$confirmation_message = "";

if (isset($table_name)){
	// build the select with all installed table
	$change_table_select = build_change_table_select(0, 1);
	$table_internal_name = $prefix_internal_table.$table_name;
} // end if



switch($function){

	case "change_field_name":
		// change the name of the field
		
		begin_trans_db();
		
		$sql = "update ".$quote.$prefix_internal_table.'forms'.$quote." set ".$quote."name_field".$quote." = '$new_field_name' where ".$quote."name_field".$quote." = '$old_field_name' and ".$quote."table_name".$quote." = '".$table_name."'";

		execute_db($sql, $conn);
		
		$sql = "update ".$quote.$prefix_internal_table.'permissions'.$quote." set ".$quote."object_permission".$quote." = '$table_name.$new_field_name' where object_type_permission = 'field' AND ".$quote."object_permission".$quote." = '$table_name.$old_field_name'";

		execute_db($sql, $conn);
		
		complete_trans_db();

		$confirmation_message .= "$old_field_name correctly changed to $new_field_name";
		
		break;
		
	case "delete_records":
		// get the array containg label and other information about the fields
		$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");
		
		if (isset($deleted_fields_ar)){
			for ($i=0; $i<count($deleted_fields_ar); $i++){
				// delete the record of the internal table
				$sql = "delete from ".$quote.$prefix_internal_table."forms".$quote." where ".$quote."name_field".$quote." = '".$deleted_fields_ar[$i]."' and ".$quote."table_name".$quote." = '".$table_name."'";
				$res_delete = execute_db("$sql", $conn);

				// get the order_form_field of the field
				for ($j=0; $j<count($fields_labels_ar); $j++){
					if ($deleted_fields_ar[$i] == $fields_labels_ar[$j]["name_field"]){
						$order_form_field_temp = $fields_labels_ar[$j]["order_form_field"];
					} // end if
				} // end for

				// re-get the array containg label and other information about the fields
				$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

				if (isset($order_form_field_temp)){ // otherwise I could have done a reload of a delete page
					// decrease the order_form_field of all the following record by one
					for ($j=($order_form_field_temp+1); $j<=(count($fields_labels_ar)+1); $j++){
						$sql ="update ".$quote.$prefix_internal_table."forms".$quote." set ".$quote."order_form_field".$quote." = order_form_field-1 where ".$quote."order_form_field".$quote." = $j and ".$quote."table_name".$quote." = '".$table_name."'";
						$res_update = execute_db($sql, $conn);
					} // end for
				} // end if
				
				//$sql = "DELETE FROM ".$quote.$prefix_internal_table."permissions".$quote." WHERE .$quote."name_field".$quote." = '".$deleted_fields_ar[$i]."'object_permission = '".$table_name."'";
				
				$sql = "DELETE FROM ".$quote.$prefix_internal_table."permissions".$quote." WHERE object_type_permission = 'field' AND object_permission = '".$table_name.".".$deleted_fields_ar[$i]."'";
	
				$res = execute_db($sql, $conn);

				// re-get the array containg label and other information about the fields
				$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");
			} // end for

			$confirmation_message .= "$i fields correctly deleted";
		} // end if
		else{
			$confirmation_message .= "Please select one or more fields to delete.";
		} // end else
		break;
	case "refresh_table":
		// get the array containing the names of the fields
		$fields_names_ar = build_fields_names_array($table_name);

		// get the array containg label ant other information about the fields
		$fields_labels_ar = build_fields_labels_array($table_internal_name, "2");

		// get the max order from the table
		$sql_max = "select max(order_form_field) from ".$quote.$prefix_internal_table."forms".$quote." where  ".$quote."table_name".$quote." = '".$table_name."'";
		$res_max = execute_db("$sql_max", $conn);
		while ($max_row = fetch_row_db($res_max)){
			$max_order_form = $max_row[0];
		} // end while

		// delete info about the table
		$sql = "delete from ".$quote.$prefix_internal_table."forms".$quote." where ".$quote."table_name".$quote." = '".$table_name."'";
		
		$res = execute_db($sql, $conn);

		$j = 0;  // set to 0 the counter for the $fields_labels_ar
		$new_fields_nr = 0; // set to 0 the counter for the number of new fields inserted

		for ($i=0; $i<count($fields_names_ar); $i++){
			if (isset($fields_labels_ar[$j]["name_field"]) and $fields_names_ar[$i] == $fields_labels_ar[$j]["name_field"]){



				// insert a previous present record in the internal table
				$name_field_temp = escape($fields_labels_ar[$j]["name_field"]);
				$present_insert_form_field_temp = escape($fields_labels_ar[$j]["present_insert_form_field"]);
				$present_edit_form_field_temp = escape($fields_labels_ar[$j]["present_edit_form_field"]);
				$present_filter_form_field_temp = escape($fields_labels_ar[$j]["present_filter_form_field"]);
				$present_search_form_field_temp = escape($fields_labels_ar[$j]["present_search_form_field"]);
				$required_field_temp = escape($fields_labels_ar[$j]["required_field"]);
				$present_results_search_field_temp = escape($fields_labels_ar[$j]["present_results_search_field"]);
				$present_details_form_field_temp = escape($fields_labels_ar[$j]["present_details_form_field"]);
				$check_duplicated_insert_field_temp = escape($fields_labels_ar[$j]["check_duplicated_insert_field"]);
				$type_field_temp = escape($fields_labels_ar[$j]["type_field"]);
				$content_field_temp = escape($fields_labels_ar[$j]["content_field"]);
				$separator_field_temp = escape($fields_labels_ar[$j]["separator_field"]);
				$items_table_names_field_temp = escape($fields_labels_ar[$j]["items_table_names_field"]);
				$items_table_fk_field_names_field_temp = escape($fields_labels_ar[$j]["items_table_fk_field_names_field"]);
				$select_options_field_temp = escape($fields_labels_ar[$j]["select_options_field"]);
				$select_type_field_temp = escape($fields_labels_ar[$j]["select_type_field"]);
				$prefix_field = escape($fields_labels_ar[$j]["prefix_field"]);
				$default_value_field = escape($fields_labels_ar[$j]["default_value_field"]);
				$label_field_temp = escape($fields_labels_ar[$j]["label_field"]);
				$width_field_temp = escape($fields_labels_ar[$j]["width_field"]);
				$height_field_temp = escape($fields_labels_ar[$j]["height_field"]);
				$maxlength_field_temp = escape($fields_labels_ar[$j]["maxlength_field"]);
				$hint_insert_field_temp = escape($fields_labels_ar[$j]["hint_insert_field"]);
				$order_form_field_temp = escape($fields_labels_ar[$j]["order_form_field"]);
				
				$other_choices_field_temp = escape($fields_labels_ar[$j]["other_choices_field"]);

				$primary_key_field_field_temp = escape($fields_labels_ar[$j]["primary_key_field_field"]);
				$primary_key_table_field_temp  = escape($fields_labels_ar[$j]["primary_key_table_field"]);
				$primary_key_db_field_temp = escape($fields_labels_ar[$j]["primary_key_db_field"]);

				$linked_fields_field_temp = escape($fields_labels_ar[$j]["linked_fields_field"]);
				$linked_fields_order_by_field_temp = escape($fields_labels_ar[$j]["linked_fields_order_by_field"]);
				$linked_fields_order_type_field_temp = escape($fields_labels_ar[$j]["linked_fields_order_type_field"]);
				
				$linked_fields_order_by_field_temp = escape($fields_labels_ar[$j]["linked_fields_order_by_field"]);
				$linked_fields_order_type_field_temp = escape($fields_labels_ar[$j]["linked_fields_order_type_field"]);
				
				$items_table_names_field_temp = escape($fields_labels_ar[$j]["items_table_names_field"]);
				$items_table_fk_field_names_field_temp = escape($fields_labels_ar[$j]["items_table_fk_field_names_field"]);
				
				$details_new_line_after_field_temp = escape($fields_labels_ar[$j]["details_new_line_after_field"]);
				$search_new_line_after_field_temp = escape($fields_labels_ar[$j]["search_new_line_after_field"]);
				$insert_new_line_after_field_temp = escape($fields_labels_ar[$j]["insert_new_line_after_field"]);
			

				$sql = "insert into ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."present_insert_form_field".$quote.", ".$quote."present_edit_form_field".$quote.", ".$quote."present_filter_form_field".$quote.",  ".$quote."present_search_form_field".$quote.", ".$quote."required_field".$quote.", ".$quote."present_results_search_field".$quote.", ".$quote."present_details_form_field".$quote.", ".$quote."present_ext_update_form_field".$quote.", ".$quote."check_duplicated_insert_field".$quote.", ".$quote."type_field".$quote.", ".$quote."content_field".$quote.", ".$quote."separator_field".$quote.", ".$quote."select_options_field".$quote.", ".$quote."select_type_field".$quote.", ".$quote."prefix_field".$quote.", ".$quote."default_value_field".$quote.", ".$quote."label_field".$quote.", ".$quote."width_field".$quote.", ".$quote."height_field".$quote.", ".$quote."maxlength_field".$quote.", ".$quote."hint_insert_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."other_choices_field".$quote.", ".$quote."primary_key_field_field".$quote.", ".$quote."primary_key_table_field".$quote.", ".$quote."primary_key_db_field".$quote.", ".$quote."linked_fields_field".$quote.", ".$quote."linked_fields_order_by_field".$quote.", ".$quote."linked_fields_order_type_field".$quote.", ".$quote."table_name".$quote.", ".$quote."items_table_names_field".$quote.", ".$quote."items_table_fk_field_names_field".$quote.", ".$quote."details_new_line_after_field".$quote.", ".$quote."search_new_line_after_field".$quote.", ".$quote."insert_new_line_after_field".$quote.") values ('$name_field_temp', '$present_insert_form_field_temp', '$present_edit_form_field_temp', '$present_filter_form_field_temp', '$present_search_form_field_temp', '$required_field_temp', '$present_results_search_field_temp', '$present_details_form_field_temp', '0', '$check_duplicated_insert_field_temp', '$type_field_temp', '$content_field_temp', '$separator_field_temp', '$select_options_field_temp', '$select_type_field_temp', '$prefix_field', '$default_value_field', '$label_field_temp', '$width_field_temp', '$height_field_temp', '$maxlength_field_temp', '$hint_insert_field_temp', '$order_form_field_temp', '$other_choices_field_temp', '$primary_key_field_field_temp', '$primary_key_table_field_temp', '$primary_key_db_field_temp', '$linked_fields_field_temp', '$linked_fields_order_by_field_temp', '$linked_fields_order_type_field_temp', '$table_name', '$items_table_names_field_temp', '$items_table_fk_field_names_field_temp', '$details_new_line_after_field_temp', '$search_new_line_after_field_temp', '$insert_new_line_after_field_temp')";

				$j++; // go to the next record in the internal table
			} // end if
			else{
				$max_order_form++;
				// insert a new record in the internal table with the name of the field
				$sql = "insert into ".$quote.$prefix_internal_table."forms".$quote." (".$quote."name_field".$quote.", ".$quote."label_field".$quote.", ".$quote."order_form_field".$quote.", ".$quote."table_name".$quote.") values ('$fields_names_ar[$i]', '$fields_names_ar[$i]', '$max_order_form', '$table_name')";
				
				$new_fields_ar[$new_fields_nr] = $fields_names_ar[$i]; // insert the name of the new field in the array to display it in the confirmation message
				$new_fields_nr++; // increment the counter of the $new_fields_ar array
			} // end else	
			$res_insert = execute_db($sql, $conn);
		} // end for
		$confirmation_message .= "Operation completed. $new_fields_nr field/s added";
		if ($new_fields_nr > 0){
			$confirmation_message .= " (";
			for ($i=0; $i<count($new_fields_ar); $i++){
				$confirmation_message .= $new_fields_ar[$i].", ";
			} // end for
			$confirmation_message = substr_custom($confirmation_message, 0, -2); // delete the last ", "
			$confirmation_message .= ")";
		} // end if
		$confirmation_message .= ".";
		
		if ($enable_granular_permissions === 1 && $enable_authentication === 1){
			$confirmation_message .= ' You should now set the <a href="permissions_manager.php?table_name='.urlencode($table_name).'">permissions</a> before using it/them.';
		}
		
		break;
		
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
<?php if (count($installed_tables_ar)>0){ // otherwise it means that no internal tables are installed

// get the array containg label and other information about the fields
$fields_labels_ar = build_fields_labels_array($table_internal_name, "1"); // because I need it for the display of the select in the form

?>


<p><font size="+1">Configure the DB Synchronization of the table <b><?php echo $table_name; ?></b></font></p>
<p>In this section you can synchronize your DaDaBIK application if you have modified the schema of your DB (i.e. if you add one or more fields, delete one or more fields, rename one or more fields of a table) </p>
<?php
if ($change_table_select != ""){
?>
<form method="get" action="db_synchro.php" name="change_table_form"><input type="hidden" name="function" value="change_table">
<?php echo $change_table_select; ?>
<?php
if ( false &&  $autosumbit_change_table_control == 0) {
?>
<input type="submit" value="Change table">
<?php
}
else{
?>
 Change table
<?php
}
?>

</form>
<?php
}
?>



<p><strong>Please follow these steps in the correct order:</strong>

<table border="0" cellpadding="6" width="100%">
  <tr class="tr_form_separator"> 
    <td><b>Step 1:</b></td></tr>
    <tr><td>
      If you have renamed some fields of <b><?php echo $table_name; ?></b> you 
      have to specify here the new names.</b>

	   <p>Select the field name you want to change and specify the new name:<br>
      <form name="change_field_name_form" method="post" action="db_synchro.php?table_name=<?php echo $table_name; ?>">
	  <input type="hidden" name="function" value="change_field_name">
        Old field name: <select name="old_field_name">
          <?php
for ($i=0; $i<count($fields_labels_ar); $i++){
	echo "<option value=\"".$fields_labels_ar[$i]["name_field"]."\">".$fields_labels_ar[$i]["name_field"]."</option>";	
} // end for
?> 
        </select>
		new field name: <input type="text" name="new_field_name">
		<input type="submit" value="Change">
		</form>
    </td>
  </tr>
</table>

<table border="0" cellpadding="6" width="100%">
  <tr class="tr_form_separator"> 
    <td><b>Step 2:</b></td></tr>
    <tr><td>
        If you have deleted some fields of <b><?php echo $table_name; ?></b> you 
        have to specify here which fields you have deleted
        by selecting it/them and clicking the delete button. 
      <p>Select the field/s you want to delete:<br>
        (multiple selection available) 
      <form name="deleted_fields_form" method="post" action="db_synchro.php?table_name=<?php echo $table_name; ?>">
	  <input type="hidden" name="function" value="delete_records">
        <select multiple name="deleted_fields_ar[]" size="10">
          <?php
for ($i=0; $i<count($fields_labels_ar); $i++){
	echo "<option value=\"".$fields_labels_ar[$i]["name_field"]."\">".$fields_labels_ar[$i]["name_field"]."</option>";	
} // end for
?> 
        </select>
        <input type="submit" value="Delete this/these field/fields" name="submit">
      </form>
    </td>
  </tr>
</table>


<table border="0" cellpadding="6" width="100%">
  <tr class="tr_form_separator"> 
    <td><b>Step 3:</b></td></tr>
    <tr><td>
        If you have added some fields to <b><?php echo $table_name; ?></b> you 
        have to press the <b>add fields</b> button, the fields will be automatically added. 
      <form name="refresh_form" method="post" action="db_synchro.php?table_name=<?php echo $table_name; ?>">
		<input type="hidden" name="function" value="refresh_table">
        <input type="submit" value="Add fields" name="submit">
      </form>
      <br>
      <br>
    </td>
  </tr>
</table>

</td>


</tr></table>


<?php } // end if?>
<?php
// include footer
include ("./include/footer.php");
?>
