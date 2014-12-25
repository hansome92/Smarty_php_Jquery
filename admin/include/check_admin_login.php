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
if ($enable_authentication === 1) {
	if ( !isset($_SESSION['logged_user_infos_ar']) ) {
		header ('Location: '.$site_url.$dadabik_login_file.'?function=show_login_form');
		die();
	} // end if
	
	// get the current user
	$current_user = $_SESSION['logged_user_infos_ar']['username_user'];
	
	if ($_SESSION['logged_user_infos_ar']['id_group'] == $id_admin_group) {
			$current_user_is_administrator = 1;
		} // end if
	else {
		header ('Location: '.$site_url.$dadabik_login_file.'?function=show_login_form');
		die();
	} // end else
} // end if
else {
	// set the username to 'nobody' if the authentication is disabled (useful if there are some ID_user fields)
	$current_user = 'nobody';
	$current_user_is_administrator = 0;
} // end else

?>