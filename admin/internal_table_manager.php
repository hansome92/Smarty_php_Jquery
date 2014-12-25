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


// get the array containing the names of the tables installed
$installed_tables_ar = build_tables_names_array(0, 1, 1);

// variables:
// GET
// $table_name from admin.php and internal_table_manager.php


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

$table_internal_name = $prefix_internal_table.$table_name;

$page_name = 'interface_configurator';

include ("./include/header.php");

 // POST
// $save (1 if the user submitted the form) from this file
// $show_all_fields (1 if the user want to show all the fields) from this file
// $field_position (the position in the internal table of the field the user want to show) from this file


if (!isset($_POST["show_all_fields"])){
	$show_all_fields = "";
} // end if
else{
	$show_all_fields = $_POST["show_all_fields"];
} // end else

// the position of the field the user wants to manage
if (!isset($_POST["field_position"])){
	$field_position = "";
} // end if
else{
	$field_position = $_POST["field_position"];
} // end else

// I need this the first time I load the page, $save is unset
if (isset($_POST["save"])){
	$save = $_POST["save"];
} // end if
else{
	$save = "0";
} // end if

/*
reset ($_POST);
while (list($key, $value) = each ($_POST)){
	$$key = $value;
} // end while
*/
// include internal table fields definition
include ("./include/internal_table.php");

// get the array containg label ant other information about the fields
$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

if ($field_position == "" and $show_all_fields != "1"){
	$field_position = 0; // set the $field_name to the first field
} // end if

$error = '';

if ($save == "1"){

	
	
	
	// enterprise
	// pro
	// check for errors
	for ($i=0; $i<count($fields_labels_ar); $i++){
		if (isset($_POST[$int_fields_ar[1][1]."_".$i])){ // if isset the variable (it means that this field was in the form){

			for ($j=1; $j<count($int_fields_ar); $j++){ // from 1 because the first is the name of the field ".${$int_fields_ar[$j][1]."_".$i};
				
				
				if ($int_fields_ar[$j][1] == 'custom_validation_function_field' && $_POST[$int_fields_ar[$j][1]."_".$i] !== '' &&  substr_custom($_POST[$int_fields_ar[$j][1]."_".$i], 0, 8) !== 'dadabik_'){
				
					$error = 'Error: you have specified a custom validation function name which doesn\'t start with "dadabik_" ';
					break;
				}
				
				
				if ($int_fields_ar[$j][1] == 'custom_formatting_function_field' && $_POST[$int_fields_ar[$j][1]."_".$i] !== '' &&  substr_custom($_POST[$int_fields_ar[$j][1]."_".$i], 0, 8) !== 'dadabik_'){
				
					$error = 'Error: you have specified a custom formatting function name which doesn\'t start with "dadabik_" ';
					break;
				}
				
				
				if ($int_fields_ar[$j][1] == 'custom_csv_formatting_function_field' && $_POST[$int_fields_ar[$j][1]."_".$i] !== '' &&  substr_custom($_POST[$int_fields_ar[$j][1]."_".$i], 0, 8) !== 'dadabik_'){
				
					$error = 'Error: you have specified a custom CSV formatting function name which doesn\'t start with "dadabik_" ';
					break;
				}
				
				if ($int_fields_ar[$j][1] == 'default_value_field' && $_POST[$int_fields_ar[$j][1]."_".$i] !== '' &&  substr_custom($_POST[$int_fields_ar[$j][1]."_".$i], 0, 4) == 'SQL:' &&  substr_custom($_POST[$int_fields_ar[$j][1]."_".$i], 0, 10) !== 'SQL:SELECT'){
				
					$error = 'Error: you have specified a custom SQL default value which doesn\'t start with "SELECT" ';
					break;
				}
				
			} // end for
		} // end if
		
		if ($error !== '') break;
	} // end for
	// enterprise
	// pro
	
	if ($error !== ''){
		echo "<p><b><font color=\"#ff0000\">$error</font></b></p>";
	}
	else{
		// save the configuration of the internal table
		for ($i=0; $i<count($fields_labels_ar); $i++){
			if (isset($_POST[$int_fields_ar[1][1]."_".$i])){ // if isset the variable (it means that this field was in the form){
	
				$sql = "";
				$sql .= "update ".$quote.$prefix_internal_table.'forms'.$quote." set ";
	
				for ($j=1; $j<count($int_fields_ar); $j++){ // from 1 because the first is the name of the field ".${$int_fields_ar[$j][1]."_".$i};
					$sql .= $quote.$int_fields_ar[$j][1]."".$quote." = '".$_POST[$int_fields_ar[$j][1]."_".$i]."', ";
				} // end for
				$sql = substr_custom($sql, 0, strlen_custom($sql)-2);
	
				$sql .= " where ".$quote."name_field".$quote." = '".$fields_labels_ar[$i]["name_field"]."' AND ".$quote."table_name".$quote." = '".substr_custom($table_internal_name, strlen_custom($prefix_internal_table))."'";
				
				// execute the update select
				$res_update = execute_db($sql, $conn);
			} // end if
		} // end for
	
		
		echo '<p><img src="images/spunta.png"> CONFIGURATION CORRECTLY SAVED</p>';
	}
} // end if

// re-get the array containg label ant other information about the fields
$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");


$change_table_select = build_change_table_select(0, 1);

$change_field_select = build_change_field_select($fields_labels_ar, $field_position);

$int_table_form = '<p><font size="+1">Configure the forms of the table <b>'.$table_name.'</b></font></p>';

$int_table_form .= "<table cellpadding=\"3\"><tr><td><form method=\"get\" action=\"internal_table_manager.php\" name=\"change_table_form\">".$change_table_select;

if (  false && $autosumbit_change_table_control == 0) {

$int_table_form .= " <input type=\"submit\" value=\"Change table\">";

}

$int_table_form .= "</form></td><td>&nbsp;&nbsp;</td><td><form method=\"post\" action=\"internal_table_manager.php?table_name=".urlencode($table_name)."\" name=\"change_field_form\">".$change_field_select;

//$int_table_form .= "</form></td><td>&nbsp;&nbsp;</td><td><form method=\"post\" action=\"internal_table_manager.php?table_name=".urlencode($table_name)."\"><input type=\"hidden\" name=\"show_all_fields\" value=\"1\"><input type=\"submit\" value=\"Show all fields of ".$table_name." in a page\"></form></td></tr></table><form method=\"post\" action=\"internal_table_manager.php?table_name=".urlencode($table_name)."\">";
$int_table_form .= "</form></td><td>&nbsp;&nbsp;</td><td></td></tr></table><form method=\"post\" action=\"internal_table_manager.php?table_name=".urlencode($table_name)."\">";


if ($show_all_fields == "1"){
	// main loop through each record of the internal table
	for ($i=0; $i<count($fields_labels_ar); $i++){
		$int_table_form .= build_int_table_field_form($i, $int_fields_ar, $fields_labels_ar, $_POST, $error);
		$int_table_form .= '<p></p><p></p>';
	} // end for
} // end if
else{
	$int_table_form .= build_int_table_field_form($field_position, $int_fields_ar, $fields_labels_ar, $_POST, $error);
} // end else

$int_table_form .= "<input type=\"hidden\" name=\"field_position\" value=\"".$field_position."\">";
$int_table_form .= "<input type=\"hidden\" name=\"show_all_fields\" value=\"".$show_all_fields."\">";
$int_table_form .= "<input type=\"submit\" value=\"Save configuration\">";
$int_table_form .= "<input type=\"hidden\" name=\"save\" value=\"1\">";
$int_table_form .= "</form>";

// display the tabled form
echo $int_table_form;
?>
<?php
// include footer
include ("./include/footer.php");
?>