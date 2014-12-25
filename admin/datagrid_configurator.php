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

$page_name = 'datagrid_configurator';



// variables:
// GET
// $table_name
// POST
// 
// $function from this page ("delete_records", "refresh_table",......)

// get the array containing the names of the tables installed
$installed_tables_ar = build_tables_names_array(0, 1, 1);

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



if (isset($_POST["alias_table"])){
	$alias_table = $_POST["alias_table"];
} // end if
if (isset($_POST["field_to_change_name"])){
	$field_to_change_name = $_POST["field_to_change_name"];
} // end if
if (isset($_POST["field_to_change_new_position"])){
$field_to_change_new_position = $_POST["field_to_change_new_position"];
} // end if

$confirmation_message = "";

if (isset($table_name)){
	// build the select with all installed table
	$change_table_select = build_change_table_select(0, 1);
	$table_internal_name = $prefix_internal_table.$table_name;
} // end if

// this is useful to display the tables that could be installed
$complete_tables_names_ar = build_tables_names_array(0, 0, 1);



switch($function){
	case "change_position":
		// get the array containg label and other information about the fields
		$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

		// get the order_form_field of the field
		for ($i=0; $i<count($fields_labels_ar); $i++){
			if ($field_to_change_name == $fields_labels_ar[$i]["name_field"]){
				$field_to_change_old_position = $fields_labels_ar[$i]["order_form_field"];
			} // end if
		} // end for

		if ($field_to_change_new_position < $field_to_change_old_position){
			// increase the order_form_field of all the following record by one
			for ($i=$field_to_change_old_position-1; $i>=$field_to_change_new_position; $i--){
				$sql ="update ".$quote.$prefix_internal_table."forms".$quote." set ".$quote."order_form_field".$quote." = ".$quote."order_form_field".$quote."+1 where ".$quote."order_form_field".$quote." = '".$i."' and ".$quote."table_name".$quote." = '".$table_name."'";
				$res_update = execute_db($sql, $conn);
			} // end for
		} // end if
		else{
			// decrease the order_form_field of all the previous record by one
			for ($i=$field_to_change_old_position+1; $i<=$field_to_change_new_position; $i++){
				$sql ="update ".$quote.$prefix_internal_table."forms".$quote." set ".$quote."order_form_field".$quote." = ".$quote."order_form_field".$quote."-1 where ".$quote."order_form_field".$quote." = '".$i."' and ".$quote."table_name".$quote." = '".$table_name."'";
				$res_update = execute_db($sql, $conn);
			} // end for
		} // end if

		// change the order_form_field of the field selected
		$sql ="update ".$quote.$prefix_internal_table."forms".$quote." set ".$quote."order_form_field".$quote." = '".$field_to_change_new_position."' where ".$quote."name_field".$quote." = '".$field_to_change_name."' and ".$quote."table_name".$quote." = '".$table_name."'";
		$res_update = execute_db($sql, $conn);
		$confirmation_message .= "Field $field_to_change_name position correctly changed from $field_to_change_old_position to $field_to_change_new_position.";		
		break;

	
	case "enable_features":
		if (!isset($enable_insert)){
			$enable_insert = "0";
		} // end if

		if (!isset($enable_edit)){
			$enable_edit = "0";
		} // end if

		if (!isset($enable_delete)){
			$enable_delete = "0";
		} // end if

		if (!isset($enable_details)){
			$enable_details = "0";
		} // end if

		if (!isset($enable_list)){
			$enable_list = "0";
		} // end if

		// save the configuration about features enabled
		$sql = "update ".$quote."$table_list_name".$quote." set ".$quote."enable_insert_table".$quote." = '".$enable_insert."', ".$quote."enable_edit_table".$quote." = '".$enable_edit."', ".$quote."enable_delete_table".$quote." = '".$enable_delete."', ".$quote."enable_details_table".$quote." = '".$enable_details."', ".$quote."enable_list_table".$quote." = '".$enable_list."' where ".$quote."name_table".$quote." = '$table_name'";

		// execute the update
		$res_update = execute_db($sql, $conn);

		$confirmation_message .= "Changes correctly saved.";
		break;
	case "save_table_alias":

		// save the configuration about table alias
		$sql = "UPDATE ".$quote.$table_list_name.$quote." SET ".$quote."alias_table".$quote." = '".$alias_table."' where ".$quote."name_table".$quote." = '".$table_name."'";

		// execute the update
		$res_update = execute_db($sql, $conn);

		$confirmation_message .= "Changes correctly saved.";
		break;
	case 'save_template_options':
		
		if (!isset($_POST['enable_template_table'])){
			$enable_template_table = "0";
		} // end if
		else{
			$enable_template_table = $_POST['enable_template_table'];
		}
		
		$template_table = $_POST['template_table'];
		
		
		$sql ="update ".$quote.$table_list_name.$quote." set ".$quote."enable_template_table".$quote." = '".$enable_template_table."', ".$quote."template_table".$quote." = '".$template_table."' where ".$quote."name_table".$quote." = '".$table_name."'";
		$res_update = execute_db($sql, $conn);
		$confirmation_message .= "Template options correctly saved.";
		
		
		
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


<p><font size="+1">Configure the datagrid of the table <b><?php echo $table_name; ?></b></font></p>

<?php
if ($change_table_select != ""){
?>
<form method="get" action="datagrid_configurator.php" name="change_table_form"><input type="hidden" name="function" value="change_table">
<?php echo $change_table_select; ?>
<?php
if ( false && $autosumbit_change_table_control == 0) {
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



$only_include_allowed = 0;
$installed_table_infos_ar = build_installed_table_infos_ar($only_include_allowed, 1);

foreach ($installed_table_infos_ar as $installed_table_infos) {
	if ($installed_table_infos['name_table'] === $table_name) {
		$table_alias = $installed_table_infos['alias_table'];
		$enable_template_table = $installed_table_infos['enable_template_table'];
		$template_table = $installed_table_infos['template_table'];
	} // end if
} // end while

?>
<table>
<tr class="tr_form_separator"><td>Alias</td></tr>

<tr><td><form method="post" action="datagrid_configurator.php?table_name=<?php echo urlencode($table_name); ?>"><input type="hidden" name="function" value="save_table_alias">Table alias (this is what DaDaBIK will displays in the interface) <input type="text" name="alias_table" value="<?php echo $table_alias; ?>"> <input type="submit" value="Save alias"></form></td></tr>

<tr class="tr_form_separator"><td>Template</td></tr>
<tr><td><form method="post" action="datagrid_configurator.php?table_name=<?php echo urlencode($table_name); ?>"><input type="hidden" name="function" value="save_template_options">For this table <strong>enable results template</strong> <input type="checkbox" name="enable_template_table" value="1"<?php if ($enable_template_table === '1') echo ' checked'; ?>> this allows to show records according to a user-specified HTML template; if results template is disabled (default) a classic tabular datagrid view is used instead.<br/><br/>Results template: (specify here your HTML template) <a href="javascript:show_template_instructions('How to write a template:', '');"><img src="images/help.png"></a><br/><textarea name="template_table" rows="10" cols="80"><?php echo $template_table; ?></textarea> <input type="submit" value="Save Template options"></form>


<div id="div_template_instructions" style="width: 600px;">
<div id="div_template_instructions_content">
<div id="div_template_instructions_content_title"></div>

<div id="div_template_instructions_content_text" style="display:none;">
The following is a template example for a table representing books.<br/><br/>

<div style="color:black;background:white;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;">
dadabik_field title_book dadabik_field by &lt;span class=&quot;blue_text&quot;&gt;dadabik_field author_book dadabik_field&lt;br/&gt;<br/><br/>

dadabik_field publisher_book dadabik_field | dadabik_field year_book dadabik_field&lt;/span&gt; &lt;br/&gt;<br/><br/>

dadabik_field url_book dadabik_field &lt;br/&gt;<br/><br/>

&lt;a href=&quot;dadabik_link details_link dadabik_link&quot; class=&quot;link_no_underline&quot;&gt;More&lt;/a&gt; dadabik_hide dadabik_groups 2|3 dadabik_groups(&lt;a href=&quot;dadabik_link edit_link dadabik_link&quot;&gt;edit&lt;/a&gt; | &lt;a onclick=&quot;if (!confirm(\'Confirm delete?\')){ return false;}&quot; href=&quot;dadabik_link delete_link dadabik_link&quot;&gt;del&lt;/a&gt;) dadabik_hide &lt;br/&gt;&lt;br/&gt;<br/><br/>
</div>

<br/>
As you can see, you need to put the name of each field you want to show between two <i>dadabik_field</i> tags. Note that you need a blank space before and after the field name, exactly as in the example.<br/><br/>

You can also add details, edit and delete links, to get the corresponding URLs you need to use the <i>dadabik_link details_link dadabik_link</i>, <i>dadabik_link edit_link dadabik_link</i> and <i>dadabik_link delete_link dadabik_link</i> tags.<br/><br/>
Please note that the details, edit and delete links in a custom template do not follow the rules set by permissions (as they do in the classic data grid), if you want tho hide one or more links to some users, please use the <i>dadabik_hide</i> tag (explained in the followings).<br/><br/>

You can hide some parts to one or more user groups using the tag <i>dadabik_hide</i> and <i>dadabik_groups</i>: in the example, everything between <i>dadabik_hide</i> and <i>dadabik_hide</i> will not be displayed if the current user belongs to the group 2 or 3. Again, pay attention to blank spaces.<br/><br/>

PLEASE NOTE that your database table can't contain the keywords <i>dadabik_link</i>, <i>dadabik_field</i>, <i>dadabik_hide</i> or <i>dadabik_groups</i> as data, otherwise the parsing will fail.<br/><br/>

Remember that you are responsible for adding a javascript confirmation pop-up, if you need it, for the delete link.<br/><br/>

You can add as much HTML as you want in order to customize the style of your results page.<br/><br/>


</div>
</div>
</div>

 

<tr class="tr_form_separator"><td>Fields position</td></tr>
<tr><td>If you want to change the displaying order of a field in the DaDaBIK datagrid, you can do it by selecting the field from the following menu and specifying the new position. All the other field positions will be shifted correctly. This also affects the form order.<br/><br/>
		<form name="change_position_form" method="post" action="datagrid_configurator.php?table_name=<?php echo $table_name; ?>">
		<input type="hidden" name="function" value="change_position">
		Field name (position): 
        <select  name="field_to_change_name">
         <?php
		for ($i=0; $i<count($fields_labels_ar); $i++){
			echo "<option value=\"".$fields_labels_ar[$i]["name_field"]."\">".$fields_labels_ar[$i]["name_field"]." (".$fields_labels_ar[$i]["order_form_field"].")</option>";	
		} // end for
		?> 
        </select>
		 New position: 
		<select  name="field_to_change_new_position">
         <?php
		for ($i=0; $i<count($fields_labels_ar); $i++){
			echo "<option value=\"".$fields_labels_ar[$i]["order_form_field"]."\">".$fields_labels_ar[$i]["order_form_field"]."</option>";	
		} // end for
		?> 
        </select>
        <input type="submit" value="Change position" name="submit">
      </form></td></tr>
</td>


</tr></table>


<?php } // end if?>
<?php
// include footer
include ("./include/footer.php");
?>
