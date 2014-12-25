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


$page_name = 'admin';

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

if (isset($_GET["function"])){
	$function = $_GET["function"];
} // end if
else{
	$function = "show_admin_home";
} // end else

include ("./include/header.php");

echo '<table><tr><td valign="top">';

switch($function){
	case "show_admin_home":
		
		require './include/forms/admin_home.php';
		break;
	case "show_help":
		
		require './include/forms/help.php';
		break;
	case "show_users":
		
		require './include/forms/users.php';
		break;
	case "show_static_pages":
		
		require './include/forms/static_pages.php';
		break;
	case "show_about":
		
		$temp = get_current_version();
		$current_version_infos_ar =  explode(',', $temp);
		$current_release_version = $current_version_infos_ar[0];
		$current_release_version_2 = $current_version_infos_ar[2];
		$current_release_date = $current_version_infos_ar[1];
		
		
		$temp = file_get_contents('http://www.dadabik.org/last_release.php');
		$last_release_infos_ar = explode(',', $temp);
		$last_release_version = $last_release_infos_ar[0];
		$last_release_date = $last_release_infos_ar[1];
		
		if ($function === 'show_about'){
			require './include/forms/about.php';
		}
		else{
			require './include/forms/check_upgrade.php';
		}
		break;
	case "show_hotscripts_feedback":
		
		require './include/forms/hotscripts_feedback.php';
		break;
		
}



echo '</td></tr></table>';




// include footer
include ("./include/footer.php");
?>
