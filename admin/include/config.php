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

<?php require '..'.DIRECTORY_SEPARATOR.'configuration.php'; ?>

<?php
// this is the configuration file
// to install DaDaBIK you have to specify *at least* $dbms_type, $host, $db_name, $user, $pass, $site_url, $site_path, $timezone

///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
// required installation parameters: please specify at least the following 8 parameters

// dbms type ('mysql' or 'postgres' or 'sqlite')
$dbms_type = 'mysql';

// DBMS server host
$host = SERVER_HOST; // the name or the IP of the host where the DBMS is running (e.g. '127.0.0.1' if the DBMS and the DaDaBIK scripts are in the same host) please use '127.0.0.1' instead of 'localhost' because 'localhost' can give problems

// database name
$db_name = MYSQL_DB; // for sqlite not only the name but 1) the full path is also needed (e.g. '/my_databases/music_databases/songs.db') and 1) write permissions on the database file is needed for the Web server

// database user
$user = MYSQL_USER; // this user must have select, insert, update, delete permissions, create and drop permissions are also needed for installation, upgrade and administration area e.g. 'root'; for SQLite this parameter is not needed

// database password
$pass = MYSQL_PW; // for sqlite this parameter is not needed

// DaDaBIK complete url (e.g. http://www.mysite.com/john/dadabik/)
$site_url = WEBSITE_URL.'admin/';

// DaDaBIK url path (e.g. if $site_url is http://www.mysite.com/john/dadabik/ this should be /john/dadabik/, put slashes at the beginning and at the end; put just one slash if DaDaBIK is installed in the root of a Website, e.g. a $site_url like http://www.mysite.com)
$site_path = URL_PREFIX.'admin/';

// timezone, specify here your timezone (a list of available timezone here: http://php.net/manual/en/timezones.php)
$timezone = 'America/New_York';

// additional installation parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// prefix to use for the name of the tables created by DaDaBIK, all the tables created by DaDaBIK use this prefix
$prefix_internal_table = 'dadabik_'; // you can safety leave this option as is, you *must* leave this option as is after the installation

// language parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// choose the front-end language ('english', 'italian', 'german', 'dutch', 'spanish', 'french', 'portuguese', 'croatian', 'polish', 'catalan', 'estonian', 'rumanian', 'hungarian', 'slovak', 'swedish', 'russian' or 'finnish')
$language = 'english';

// authentication parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable the users authentication (0|1). If you set 1, you have to login to use DaDaBIK
// by default DaDaBIK create an administrator user (username: root password: letizia) and a normal user (username: alfonso password: letizia); for security reasons please change both the passwords as soon as possible and never publish a DaDaBIK application having the default passwords
$enable_authentication = 1;

// enable granular permissions (0|1). Granular permissions allow you to set, for each users group, which operations (read, delete, update, create, details, search and quick search) are allowed on each form and field.
$enable_granular_permissions = 0;

// leave FALSE for better security in passowrd storage, change to TRUE for maximum application portability on old systems (see http://www.openwall.com/phpass/ for further details)
$generate_portable_password_hash = FALSE;

// deletion parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable delete all feature (delete operations must be enabled too, from the administration interface) (0|1)
$enable_delete_all_feature = 1;

// when a record is deleted, delete also the uploaded files related to that record (0|1)
$delete_files_when_delete_record = 0;

// ask confirmation before deleting a record? (0|1); note that this works just for the standard data grid, not for template data grids
$ask_confirmation_delete = 1;

// CSV parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable export to CSV for excel feature (0|1)
$export_to_csv_feature = 1;

// CSV separator
$csv_separator = ",";

// CSV file creation time limit (in seconds), in order to use it uncomment the line below by removing // and set the number of seconds. This feature makes use of the set_time_limit() function and has no effect when PHP is running in safe mode
//$csv_creation_time_limt = 30;

// null handling parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// set to 0 if you don't want the NULL checkbox in the insert/update form. WARNING: if you set to 0 there is no way to insert a NULL value as a field value of a record
// note that, if the record you are editing contains a NULL value for one or more fields, the NULL checboxes are displayed anyway, even if $null_checkbox is set to 0.
$null_checkbox = 1;

// the word used to display a NULL content (could also be a blank string '')
$null_word = '';

// Upload parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// relative URL from the DaDaBIK root dir to the upload folder; you can leave the default one, remember to put a slash (/) at the end if you change it
$upload_relative_url = '..'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR;

// absolute path of the upload dir - please put slash (/) at the end
// e.g. 'c:\\data\\web\\dadabik\\uploads\\' on windows systems
// e.g. '/home/my/path/dadabik/uploads/' on unix systems
// make sure the webserver can write in this folder and in the temporary upload folder used by PHP (see php.ini for details)
$upload_directory = getcwd().'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR;

// max allowed size for the uploaded files (in bytes)
$max_upload_file_size = 20000000;

// allowed file extensions (users will be able to upload only files having these extensions); you can add new extensions and/or delete the default ones
$allowed_file_exts_ar[0] = 'jpg';
$allowed_file_exts_ar[1] = 'gif';
$allowed_file_exts_ar[2] = 'png';
$allowed_file_exts_ar[3] = 'js';
$allowed_file_exts_ar[3] = 'ttf';
$allowed_file_exts_ar[3] = 'otf';
/*
$allowed_file_exts_ar[2] = 'tif';
$allowed_file_exts_ar[3] = 'tiff';
$allowed_file_exts_ar[5] = 'txt';
$allowed_file_exts_ar[6] = 'rtf';
$allowed_file_exts_ar[7] = 'doc';
$allowed_file_exts_ar[8] = 'xls';
$allowed_file_exts_ar[9] = 'htm';
$allowed_file_exts_ar[10] = 'html';
$allowed_file_exts_ar[11] = 'csv';
$allowed_file_exts_ar[12] = 'pdf';
*/

$allowed_all_files = 0; // set to 1 if you want to allow all extensions, and files without extension too

// Duplication check parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// maximum number of records to be displayed as duplicated during insert
$number_duplicated_records = 30;

// select similarity percentage used by duplication insert check
$percentage_similarity = 90;

// debug parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// display the main sql statements of insert/search/edit/detail operations for debugging (0|1)
// note that the insert sql statement is will be displayed only if $insert_again_after_insert is set to 1 and $show_details_after_insert = 0
$display_sql = 1;

// display all the sql statements and the MySQL error messages in case of DB error for debugging (0|1)
$debug_mode = 0;

// display the "I think that x is similar to y......" statements during duplication check (0|1)
$display_is_similar = 0;

// search parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable the quick search feature, which provides a quick search row at the top of the results
$enable_quick_search_feature = 1;

// allow the "and/or" choice directly in the form during the search (0|1)
$select_operator_feature = 1;

// default operator (or/and), if the previous is set to 0
$default_operator = 'and';

// choose if you want to show the change table dropdown menu in the search form (1) or not (0)
$show_tables_menu_in_search = 1;

// data grid parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// items of the menu used to choose the number of result records displayed per page, you can add new numbers and/or delete the default ones
$records_per_page_ar[0] = 10;
$records_per_page_ar[1] = 20;
$records_per_page_ar[2] = 50;
$records_per_page_ar[3] = 100;

// target window for details/edit, 'self' is the same window, 'blank' a new window; this works just for the standard data grid, not for template data grids
$edit_target_window = 'self';

// coloumn at which a text, textarea and select_single field will be wrapped in the results page, this value also determines the width of the coloumn in the results table (standard data grid) if $word_wrap_fix_width is 1
$word_wrap_col = '25';

// allow that $word_wrap_col value also determines the width of the coloumn in the results table (standard data grid) (0|1)
$word_wrap_fix_width = 0;

// always wrap words at the $word_wrap_col column, even if it is necessary to cut them (0|1)
// FEATURE NO LONGER AVAILABLE!
$enable_word_wrap_cut = 1;

// image files to use as icons for delete, edit, details
// you can use both relative or absoulute url here
$delete_icon = 'images/delete.png';
$edit_icon = 'images/edit.png';
$details_icon = 'images/details.png';

// enable results table row highlighting (0|1)
$enable_row_highlighting = 1;

// dates parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// format used to display date fields ('literal_english', 'latin' or 'numeric_english')
// 'literal_english': May 31, 2010
// 'latin': 31-5-2010
// 'numeric_english': 5-31-2010
// note that, depending on your system, you can have problem displaying dates prior to 01-01-1970 or after 19-01-2038 if you use the literal english format; in particular, it is known that this problem affects windows systems
$date_format = 'numeric_english';

// date field separator (divides day, month and year; used only with latin and numeric_english date format)
$date_separator = "-";

// start and end year for date field, used to build the year combo box for date and date_time fields
$start_year = 2012;
$end_year = 2020;

// insertion parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// choose if, after an insert, you want DaDaBIK to display again the insert form (1) or not (0)
$insert_again_after_insert = 0;

// choose if, after an insert, you want DaDaBIK to show you a details page of the record just inserted
$show_details_after_insert = 0;

// choose if you want to show the change table dropdown menu in the insert form (1) or not (0)
$show_tables_menu_in_insert = 1;

// record locking parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable the record lock feature when a user is editing a record
$enable_record_lock_feature = 1;

// number of seconds after which a record is automatic unlocked (useful to avoid a record being locked forever when, for example, a user enters the record in edit mode and then left his workstation); choose a value >= 60
$seconds_after_automatic_unlock = 240;

// forms settings
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// show update and search submit buttons also at the top of the forms, not just at the bottom (0|1)
$show_top_buttons = 0;

// email notices parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable_insert_notice_email_sending: when a new record is inserted an e-mail containing the record details will be sent to the addresses below (0|1)
// this feature requires an "auto increment" primary key in the relative table
$enable_insert_notice_email_sending = 0;

// set here the recipient addresses of the insert notice e-mail
// Here is an example
// $insert_notice_email_to_recipients_ar[0] = 'my_account_1@my_provider.com';
// $insert_notice_email_to_recipients_ar[1] = 'my_account_2@my_provider.com';
// $insert_notice_email_cc_recipients_ar[0] = 'my_account_3@my_provider.com';
// $insert_notice_email_bcc_recipients_ar[0] = 'my_account_4@my_provider.com';

$insert_notice_email_to_recipients_ar[0] = '';

$insert_notice_email_cc_recipients_ar[0] = '';

// please note that on some PHP versions, probably only on MS Windows, the mail() function doesn't work fine: the messages are not send to the bcc addresses and the bcc addresses are shown in clear!!!
$insert_notice_email_bcc_recipients_ar[0] = '';

// enable_update_notice_email_sending: when a record is updated an e-mail containing the new record details will be sent to the addresses below (0|1)
$enable_update_notice_email_sending = 0;

// set here the recipient addresses of the update notice e-mail
// Here is an example
// $update_notice_email_to_recipients_ar[0] = 'my_account_1@my_provider.com';
// $update_notice_email_to_recipients_ar[1] = 'my_account_2@my_provider.com';
// $update_notice_email_cc_recipients_ar[0] = 'my_account_3@my_provider.com';
// $update_notice_email_bcc_recipients_ar[0] = 'my_account_4@my_provider.com';

$update_notice_email_to_recipients_ar[0] = '';

$update_notice_email_cc_recipients_ar[0] = '';

// please note that on some PHP versions, probably only on MS Windows, the mail() function doesn't work fine: the messages are not send to the bcc addresses and the bcc addresses are shown in clear!!!
$update_notice_email_bcc_recipients_ar[0] = '';


// advanced configuration settings, you can safety leave the following settings as they are
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// the name of the main file of DaDaBIK, you can safety leave this option as is unless you need to rename index.php to something else
$dadabik_main_file = 'index.php';

// the name of the login page of DaDaBIK, you can safety leave this option as is unless you need to rename login.php to something else
$dadabik_login_file = 'login.php';

// prefix of the NULL checkbox name
$null_checkbox_prefix = 'null_value__';

// suffix of the select type (contains, is_null....) listbox
$select_type_select_suffix = '__select_type';

// suffix of the date time types
$year_field_suffix = '__year';
$month_field_suffix = '__month';
$day_field_suffix = '__day';
$hours_field_suffix = '__hours';
$minutes_field_suffix = '__minutes';
$seconds_field_suffix = '_seconds';

// htmLawed configuration, you shouldn't change this parameter, changing this parameter can lead to security problems, read documentation for more information
$htmlawed_config = array('safe'=>1, 'deny_attribute'=>'style, class');

// choose the correct db library; you shouldn't change this parameter
$db_library = 'pdo';

// perform a check about mbstring extension installation; this parameter should be always set to 1
$mbstring_check = 1;

// alias_prefix
$alias_prefix = '__'; // you can safety leave this option as is

// table_list_name name
$table_list_name = $prefix_internal_table."table_list"; // this parameter is here for historical reasons, don't change it

?>