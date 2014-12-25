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

$page_name = 'permissions_manager';


// variables:
// GET
// $table_name
// POST
// 
// $function from this page ("delete_records", "refresh_table",......)

// get the array containing the names of the tables installed
$installed_tables_ar = build_tables_names_array(0, 1, 1);


if (isset($_POST["object"])){
	$table_name = $_POST["object"]; // just for passing over
} // end if
else{
	if (isset($_GET["table_name"])){
		$table_name = $_GET["table_name"];
	} // end if
	elseif (count($installed_tables_ar)>0){
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

if (isset($_GET["function"])){
	$function = $_GET["function"];
} // end if
elseif (isset($_POST["function"])){
	$function = $_POST["function"];
} // end if
else{
	$function = '';
} // end if

// e.g. 'customers'
if (isset($_POST["object"])){
	$object = $_POST["object"];
} // end if

// e.g. 'user_12' or 'group_1'
if (isset($_POST["subject"])){
	$subject = $_POST["subject"];
} // end if

if (isset($_POST["change_subject_object"])){
	$change_subject_object = (int)$_POST["change_subject_object"];
} // end if

if (isset($_POST["object_to_set"])){
	$object_to_set = $_POST["object_to_set"];
} // end if

if (isset($_POST["object_type_to_set"])){
	$object_type_to_set = $_POST["object_type_to_set"];
} // end if

if (isset($_POST["subject_to_set"])){
	$subject_to_set = $_POST["subject_to_set"];
} // end if

if (isset($_POST["set_permissions"])){
	$set_permissions = (int)$_POST["set_permissions"];
} // end if

if (isset($_POST["enable_insert"])){
	$enable_insert = $_POST["enable_insert"];
} // end if
if (isset($_POST["enable_edit"])){
	$enable_edit = $_POST["enable_edit"];
} // end if
if (isset($_POST["enable_delete"])){
	$enable_delete = $_POST["enable_delete"];
} // end if
if (isset($_POST["enable_details"])){
	$enable_details = $_POST["enable_details"];
} // end if
if (isset($_POST["enable_list"])){
	$enable_list = $_POST["enable_list"];
} // end if
if (isset($_POST["enable_delete_authorization"])){
	$enable_delete_authorization = $_POST["enable_delete_authorization"];
} // end if
if (isset($_POST["enable_update_authorization"])){
	$enable_update_authorization = $_POST["enable_update_authorization"];
} // end if
if (isset($_POST["enable_browse_authorization"])){
	$enable_browse_authorization = $_POST["enable_browse_authorization"];
} // end if

$confirmation_message = "";

if (isset($table_name)){
	// build the select with all installed table
	$change_table_select = build_change_table_select(0, 1);
	$table_internal_name = $prefix_internal_table.$table_name;
} // end if


include ("./include/header.php");
?>



<?php

if ($enable_granular_permissions === 1 && $enable_authentication === 1 ){

	
	// id, type (read, delete, etc), object_type (table|field), options (0,1,...)
	$permission_types_ar = build_permission_types_ar();

	$missing_required_fields = 0;
	if (isset($change_subject_object) && $change_subject_object === 1 && ($object === '' || $subject === '')){
		echo '<p style="color:red">Please select both the table and the group</p>';
		
		$missing_required_fields = 1;
	}
	
	if (isset($set_permissions) && $set_permissions === 1){
	
	
		if (substr_custom($subject_to_set, 0, 5) === 'user_'){
			$subject_type_permission = 'user';
			$id_subject = substr_custom($subject_to_set, 5);
		}
		elseif(substr_custom($subject_to_set, 0, 6) === 'group_'){
			$subject_type_permission = 'group';
			
			$id_subject = substr_custom($subject_to_set, 6);
			
			$sql = "SELECT ".$quote.$users_table_id_field.$quote." from ".$quote.$users_table_name.$quote." WHERE ".$quote.$users_table_id_group_field.$quote." = ".$id_subject;
			
			$res = execute_db($sql, $conn);
			
			$users_belonging_ar = array();
			
			while ($row = fetch_row_db($res)){
				$users_belonging_ar[] = $row[$users_table_id_field];
			}
		}
		else{
			die('General error subject prefix');
		}
		
		begin_trans_db();
		
		$sql = "DELETE FROM ".$quote.$prefix_internal_table."permissions".$quote." WHERE subject_type_permission = '".$subject_type_permission."' AND id_subject = '".$id_subject."' AND object_permission = '".$object_to_set."'";

		$res = execute_db($sql, $conn);
		
		if (isset($users_belonging_ar)){
		foreach ($users_belonging_ar as $users_belonging_ar_el){
			$sql = "DELETE FROM ".$quote.$prefix_internal_table."permissions".$quote." WHERE subject_type_permission = 'user' AND id_subject = '".$users_belonging_ar_el."' AND object_permission = '".$object_to_set."'";

			$res = execute_db($sql, $conn);
		}
		}
		
		foreach($permission_types_ar as $permission_types_ar_el){
			
			if ($permission_types_ar_el['object_type_permission_type'] == $object_type_to_set){
					
				$sql = "INSERT INTO  ".$quote.$prefix_internal_table."permissions".$quote." (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) VALUES ('".$subject_type_permission."', ".$id_subject.", '".$object_type_to_set."', '".$object_to_set."', ".$permission_types_ar_el['id_permission_type'].", '".$_POST[$id_subject.'_'.$permission_types_ar_el['id_permission_type']]."')";
					
				$res = execute_db($sql, $conn);
				if (isset($users_belonging_ar)){
				foreach ($users_belonging_ar as $users_belonging_ar_el){
					$sql = "INSERT INTO  ".$quote.$prefix_internal_table."permissions".$quote." (subject_type_permission,id_subject,object_type_permission,object_permission,id_permission_type,value_permission) VALUES ('user', ".$users_belonging_ar_el.", '".$object_type_to_set."', '".$object_to_set."', ".$permission_types_ar_el['id_permission_type'].", '".$_POST[$id_subject.'_'.$permission_types_ar_el['id_permission_type']]."')";

					$res = execute_db($sql, $conn);
				}
				}
				
			}
		}
		
		
		complete_trans_db();
		
		echo '<p><img src="images/spunta.png"> PERMISSIONS UPDATED</p>';
		
	}

	if (isset($object)){
	
		$object = unescape($object);
		
		$objects_select = build_objects_select($object);
		
	}
	else{
		$objects_select = build_objects_select($table_name);
	}
	

	if (isset($subject)){
	
		
		$subject = unescape($subject);
		
		$subjects_select = build_subjects_select($subject);
		
	}
	else{
		$subjects_select = build_subjects_select('');
	}
	
	echo '<p><font size="+1">Configure the permissions of the table <b>'.$table_name.'</b></font></p>';
	echo '<form method="POST" action="permissions_manager.php?table_name='.urlencode($table_name).'"><input type="hidden" name="change_subject_object" value="1"><p>Choose a table: '.$objects_select.'&nbsp;&nbsp;&nbsp;&nbsp; choose a group: '.$subjects_select.' <input type="submit" value="Show current permissions"></form>';
	
	if (isset($object) && isset($subject) && $missing_required_fields === 0){
	
		echo '<p>For table permissions READ, DELETE and EDIT you also have the option MY, which enable the owner permissions.<br/>(the owner permissions have effect only with DaDaBIK ENTERPRISE VERSION)</p>';
	
		
		echo '<table><tr class="tr_form_separator"><td colspan="8">Set permissions for the table</td></tr>';
		
		if (substr_custom($subject, 0, 5) === 'user_'){
			$id_user = substr_custom($subject, 5);
							
			$user_infos_ar = get_user_infos_ar_from_username_password($id_user, '', 1, 1);
			
			
			$subjects_ar[0]['name'] = $user_infos_ar['username_user'];
			$subjects_ar[0]['id'] = $user_infos_ar['id_user'];
			
			$subjects_ar[0]['permissions'] = $user_infos_ar['permissions']['user'];
			
			
		}
		elseif(substr_custom($subject, 0, 6) === 'group_'){
			$id_group = substr_custom($subject, 6);
							
			$group_infos_ar = build_group_infos_ar($id_group);
			
			
			$subjects_ar[0]['name'] = $group_infos_ar['name_group'];
			$subjects_ar[0]['id'] = $group_infos_ar['id_group'];
			
			$subjects_ar[0]['permissions'] = $group_infos_ar['permissions'];
		}
		/*
		elseif(substr_custom($subject, 0, 10) === 'all_groups'){
			
			$all_groups_infos_ar = build_group_infos_ar('');
			
			$sql = ""
			
			
			
		}
		elseif(substr_custom($subject, 0, 9) === 'all_users'){
		}
		*/
		else{
			die('General error subject prefix');
		}
		
		echo '<form method="POST" action="permissions_manager.php?table_name='.urlencode($table_name).'">';
		echo '<input type="hidden" name="object_type_to_set" value="table">';
		echo '<input type="hidden" name="object" value="'.$object.'">';
		echo '<input type="hidden" name="subject" value="'.$subject.'">';
		echo '<input type="hidden" name="object_to_set" value="'.$object.'">';
		echo '<input type="hidden" name="subject_to_set" value="'.$subject.'">';
		echo '<input type="hidden" name="set_permissions" value="1">';
		
		
		echo '<tr><td></td>';
		foreach($permission_types_ar as $permission_types_ar_el){
			if ($permission_types_ar_el['object_type_permission_type'] == 'table'){
				echo '<td align="left" style="padding:0 10px 0 10px;">'.strtoupper_custom($permission_types_ar_el['type_permission_type']);
				
				if ($permission_types_ar_el['id_permission_type'] == '1'){
					echo " <a href=\"javascript:show_admin_help('Read permission:', 'If you disable the read permission, the table will not be displayed in the change table menu of the application (except from Users, Groups and Static pages tables for admin users). Remember, however, that users can still read its records if the table is used as source of a select_single field or as items table in a master/details view.');\"><img alt=\"Help\" title=\"Help\" border=\"0\" src=\"images/help.png\" /></a>";
				}
				echo '</td>';
				
			}
		}
		echo '<td></td><td></td>';
		echo '</tr>';
		foreach($subjects_ar as $subjects_ar_el){
			$js_yes_all = '';
			$js_no_all = '';
			echo '<tr>';
			echo '<td><b>'.$subjects_ar_el['name'].'</b></td>';
			
			foreach($permission_types_ar as $permission_types_ar_el){
		
				if ($permission_types_ar_el['object_type_permission_type'] == 'table'){
					$permission_info_available = 0;
				
				
					foreach($subjects_ar_el['permissions'] as $temp){ // for each permission of the subject
						
						
						if ($temp['id_permission_type'] == $permission_types_ar_el['id_permission_type'] && $temp['object_permission'] == $object){
						
							$value_permission = $temp['value_permission'];
							$permission_info_available = 1;
							break;
						}
					}
					
					if ($permission_info_available === 0){
						$value_permission = 0;
					}
					
					
					echo '<td align="left" style="padding:0 10px 0 10px;">'.build_permission_options_select($permission_types_ar, $subjects_ar_el['id'], $permission_types_ar_el['id_permission_type'], $value_permission, $object).'</td>';
					
					$js_yes_all .= 'document.getElementById(\''.$object.'_'.$subjects_ar_el['id'].'_'.$permission_types_ar_el['id_permission_type'].'\').value=\'1\';';
					$js_no_all .= 'document.getElementById(\''.$object.'_'.$subjects_ar_el['id'].'_'.$permission_types_ar_el['id_permission_type'].'\').value=\'0\';';
				}
			}
			
			
			echo '<td><div style="cursor:pointer;color:#66603c;text-decoration:underline;" onclick="javascript:'.$js_yes_all.'">Yes for all</div></td><td><div style="cursor:pointer;color:#66603c;text-decoration:underline;" onclick="javascript:'.$js_no_all.'">No for all</div></td></tr>';
		}
		echo '</table>';
		
		echo '<input value="save permissions for '.$object.'" type="submit"></form>';
		
		
		
		echo '<table><tr><td colspan="9">&nbsp;</td></tr><tr><td colspan="9">&nbsp;</td></tr><tr class="tr_form_separator"><td colspan="9">Set permissions for each field</td></tr>';
		
		
		//$fields_ar = get_fields_list($object);
		
		
		$fields_labels_ar = build_fields_labels_array($prefix_internal_table.$object, 1);
		
		foreach($fields_labels_ar as $fields_labels_ar_el){
		
		$fields_ar_el = $fields_labels_ar_el['name_field'];
		
		echo '<tr><td colspan="9"><b>Field: </b>'.$fields_ar_el.'</td></tr>';
		echo '<form method="POST" action="permissions_manager.php?table_name='.urlencode($table_name).'">';
		echo '<input type="hidden" name="object" value="'.$object.'">';
		echo '<input type="hidden" name="subject" value="'.$subject.'">';
		echo '<input type="hidden" name="object_to_set" value="'.$object.'.'.$fields_ar_el.'">';
		echo '<input type="hidden" name="object_type_to_set" value="field">';
		echo '<input type="hidden" name="subject_to_set" value="'.$subject.'">';
		echo '<input type="hidden" name="set_permissions" value="1">';
		
		echo '<tr><td></td>';
		foreach($permission_types_ar as $permission_types_ar_el){
			if ($permission_types_ar_el['object_type_permission_type'] == 'field'){
				echo '<td align="left" style="padding:0 10px 0 10px;">'.strtoupper_custom($permission_types_ar_el['type_permission_type']).'</td>';
			}
		}
		echo '<td></td><td></td>';
		echo '</tr>';
		foreach($subjects_ar as $subjects_ar_el){
			$js_yes_all = '';
			$js_no_all = '';
			echo '<tr>';
			echo '<td><b>'.$subjects_ar_el['name'].'</b></td>';
			
			foreach($permission_types_ar as $permission_types_ar_el){
		
				if ($permission_types_ar_el['object_type_permission_type'] == 'field'){
					$permission_info_available = 0;
						
					foreach($subjects_ar_el['permissions'] as $temp){
						
						if ($temp['id_permission_type'] == $permission_types_ar_el['id_permission_type'] && $temp['object_type_permission'] == 'field' && $temp['object_permission'] == $object.'.'.$fields_ar_el){
						
							$value_permission = $temp['value_permission'];
							$permission_info_available = 1;
							break;
						}
					}
					
					if ($permission_info_available === 0){
						$value_permission = 0;
					}
					
					
					echo '<td align="left" style="padding:0 10px 0 10px;">'.build_permission_options_select($permission_types_ar, $subjects_ar_el['id'], $permission_types_ar_el['id_permission_type'], $value_permission, $fields_ar_el).'</td>';
					
					$js_yes_all .= 'document.getElementById(\''.$fields_ar_el.'_'.$subjects_ar_el['id'].'_'.$permission_types_ar_el['id_permission_type'].'\').value=\'1\';';
					$js_no_all .= 'document.getElementById(\''.$fields_ar_el.'_'.$subjects_ar_el['id'].'_'.$permission_types_ar_el['id_permission_type'].'\').value=\'0\';';
				}
			}
			echo '<td><div style="cursor:pointer;color:#66603c;text-decoration:underline;" onclick="javascript:'.$js_yes_all.'">Yes for all</div></td><td><div style="cursor:pointer;color:#66603c;text-decoration:underline;" onclick="javascript:'.$js_no_all.'">No for all</div></td></tr>';
		}
		echo '</table>';
		
		echo '<input value="save permissions for '.$fields_ar_el.'" type="submit"></form><br/>';
		echo '<table>';
		echo '<tr><td colspan="9">&nbsp;</td></tr><tr><td colspan="9">&nbsp;</td></tr>';
		}
	}
}
elseif ($enable_granular_permissions === 0 || $enable_authentication === 0){
	switch($function){
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
	
			if (!isset($enable_delete_authorization)){
				$enable_delete_authorization = "0";
			} // end if
	
			if (!isset($enable_update_authorization)){
				$enable_update_authorization = "0";
			} // end if
	
			if (!isset($enable_browse_authorization)){
				$enable_browse_authorization = "0";
			} // end if
	
			// save the configuration about features enabled
			$sql = "update ".$quote."$table_list_name".$quote." set ".$quote."enable_insert_table".$quote." = '".$enable_insert."', ".$quote."enable_edit_table".$quote." = '".$enable_edit."', ".$quote."enable_delete_table".$quote." = '".$enable_delete."', ".$quote."enable_details_table".$quote." = '".$enable_details."', ".$quote."enable_list_table".$quote." = '".$enable_list."', ".$quote."enable_delete_authorization_table".$quote." = '".$enable_delete_authorization."', ".$quote."enable_update_authorization_table".$quote." = '".$enable_update_authorization."', ".$quote."enable_browse_authorization_table".$quote." = '".$enable_browse_authorization."' where ".$quote."name_table".$quote." = '$table_name'";
	
			// execute the update
			$res_update = execute_db($sql, $conn);
	
			$confirmation_message .= "Changes correctly saved.";
			break;
	}
}

?>


<?php if($enable_granular_permissions === 0 || $enable_authentication === 0){

if ($confirmation_message != ""){
	echo "<p><b><font color=\"#ff0000\">$confirmation_message</font></b></p>";
} // end if
 ?>
<p><font size="+1">Configure the permissions of the table <b><?php echo $table_name; ?></b></font></p>
<p>For a granular configuration, i.e. setting the permissions for each table/usergroup and field/usergroup couple, you need to enable authentication and  granular permissions in <strong>/include/config.php</strong></p>

<?php
if ($change_table_select != ""){
?>
<form method="get" action="permissions_manager.php" name="change_table_form"><input type="hidden" name="function" value="change_table">
<?php echo $change_table_select; ?>
<?php
if (  false && $autosumbit_change_table_control == 0) {
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

$enable_features_checkboxes = build_enable_features_checkboxes($table_name);

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

<br/><p><form method="post" action="permissions_manager.php?table_name=<?php echo urlencode($table_name); ?>"><input type="hidden" name="function" value="enable_features"><b>For this table enable:</b><br/><br/><?php echo $enable_features_checkboxes ?><br/><input type="submit" value="Enable/disable"></form>


<?php } ?>



<?php
// include footer
include ("./include/footer.php");
?>
