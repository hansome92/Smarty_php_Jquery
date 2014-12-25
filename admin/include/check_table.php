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
if ($enable_authentication === 0 || isset($_SESSION['logged_user_infos_ar']) && (!isset($page_name) || $page_name !== 'login')) {


	if ($enable_granular_permissions === 1 && $enable_authentication === 1){
	
		
		$only_include_allowed = 1;
		$temp_ar = build_installed_table_infos_ar($only_include_allowed, 1); // excluding admin tables if not admin
		
		
		$tables_names_ar = array();
		
		
		foreach($temp_ar as $temp_ar_el){
			
			if ( user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $temp_ar_el['name_table'], '1') === 1 || user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $temp_ar_el['name_table'], '1') === 2 ||  (isset($_GET['master_table_name']) && user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $_GET['master_table_name'], '1') === 1) || (isset($_GET['master_table_name']) && user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $_GET['master_table_name'], '1') === 2) || $temp_ar_el['name_table'] === $users_table_name || $temp_ar_el['name_table'] === $groups_table_name || $temp_ar_el['name_table'] === $prefix_internal_table.'static_pages'){
				$tables_names_ar[] = $temp_ar_el['name_table'];
			}
		}
		
	}
	elseif($enable_granular_permissions === 0 || $enable_authentication === 0){
		
		
		// installed and allowed tables
		$tables_names_ar = build_tables_names_array();
		
		
		
		$only_include_allowed = 1;
		$temp_ar = build_installed_table_infos_ar($only_include_allowed, 1); // excluding admin tables if not admin
		
		$tables_names_ar = array();
		
		//$temp_ar = build_tables_names_array();
		//$tables_names_ar = array();
		
		$enabled_features_ar_all = build_enabled_features_ar('', 1);
		
		foreach($temp_ar as $temp_ar_el){
		
			
			if ( $enabled_features_ar_all[$temp_ar_el['name_table']]['list'] === '1' || isset($_GET['master_table_name']) && $enabled_features_ar_all[$_GET['master_table_name']]['list'] === '1' || $temp_ar_el['name_table'] === $users_table_name || $temp_ar_el['name_table'] === $groups_table_name || $temp_ar_el['name_table'] === $prefix_internal_table.'static_pages' ){
				$tables_names_ar[] = $temp_ar_el['name_table'];
			}
			
		}
	
	
	}
	else{
		echo "<p>An error occured";
	} // end else
	
	if (count($tables_names_ar) == 0){ // no tables installed and allowed
		echo "<p><b>[04] Error:</b> it is impossible to run DaDaBIK, probably because you have decided to disable all the tables or the current user doesn't have any permissions set; go to the <a href=\"admin.php\">administration interface</a> and include some tables / set some permissions.";
		exit;
	} // end
	else{
		if (!isset($_GET["table_name"])){
			
			// get the first one
			$table_name = $tables_names_ar[0];
			
			// override if there is at least one non-admin
			foreach ($tables_names_ar as $table_names_ar_el){
				if ($table_names_ar_el !== $users_table_name && $table_names_ar_el !== $groups_table_name && $table_names_ar_el !== $prefix_internal_table.'static_pages' ){
					$table_name = $table_names_ar_el;
					break;
				}
			}
		} // end if
		else{
			$table_name = $_GET["table_name"];
			if ( !in_array($table_name, $tables_names_ar) ) { // someone try to manage a not-allowed table by changing the url
				echo "<p><b>[05] Error:</b> you are attemping to manage a not allowed table.";
				exit;
			}
			/*
			if (!table_allowed($conn, $table_name)){ // someone try to manage a not-allowed table by changing the url
				exit;
			} // end if
			*/
		} // end else
		
	
		if ($enable_granular_permissions === 1 && $enable_authentication === 1){
		
			$enabled_features_ar_2 = build_enabled_features_ar_2($table_name);
			
			$enable_insert = $enabled_features_ar_2["insert"];
			$enable_edit = $enabled_features_ar_2["edit"];
			$enable_delete = $enabled_features_ar_2["delete"];
			$enable_details = $enabled_features_ar_2["details"];
			$enable_browse = $enabled_features_ar_2["browse"];
			$enable_browse_authorization = 0;
			$enable_delete_authorization = 0;
			$enable_update_authorization = 0;
			
		}
		elseif ($enable_granular_permissions === 0 || $enable_authentication === 0){
			
			$enabled_features_ar = build_enabled_features_ar($table_name);
			
			$enable_insert = $enabled_features_ar["insert"];
			$enable_edit = $enabled_features_ar["edit"];
			$enable_delete = $enabled_features_ar["delete"];
			$enable_details = $enabled_features_ar["details"];
			$enable_browse = $enabled_features_ar["list"];
			$enable_browse_authorization = 0;
			$enable_delete_authorization = 0;
			$enable_update_authorization = 0;
		}
		else{
			echo "<p>An error occured";
		} // end else
	
		$table_internal_name = $prefix_internal_table.$table_name;
	} // end else
}
?>