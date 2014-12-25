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
// include business logic, db_functions and general_functions
include ("./include/business_logic.php");

if (true || $dbms_type === 'sqlite' || $dbms_type === 'sqlite2'){
	require ("./include/db_functions_pdo.php");
}
else{
	require ("./include/db_functions_adodb.php");	
}
include ("./include/general_functions.php");

// enterprise
// pro
require('./include/custom_functions.php')
// end enterprise
// end pro
?>