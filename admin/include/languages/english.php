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
// submit buttons
$submit_buttons_ar = array (
	"insert"    => "Insert a new item",
	"quick_search"    => "Quick search",
	"search/update/delete" => "Search/update/delete items",
	"insert_short"    => "Create new",
	"search_short" => "Search",
	"insert_anyway"    => "Insert anyway",
	"search"    => "Search for items",
	"update"    => "Save",
	"ext_update"    => "Update your profile",
	"yes"    => "Yes",
	"no"    => "No",
	"go_back" => "Go back",
	"edit" => "Edit",
	"delete" => "Delete",
	"details" => "Details",
	"change_table" => "Change table"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this item, the item is locked by user: ",
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this item, it is not safe to update, please start again the edit",
	"insert_item" => "Create new item",
	"show_all_records" => "Show all items",
	"show_records" => "Show items",
	"remove_search_filter" => "remove search filter",
	"logout" => "Logout",
	"top" => "Top",
	"last_search_results" => "Last search results",
	"show_all" => "Show all",
	"home" => "Home",
	"select_operator" => "Select the operator:",
	"all_conditions_required" => "All the conditions required",
	"any_conditions_required" => "Any of the conditions required",
	"all_contacts" => "All contacts",
	"removed" => "removed",
	"please" => "Please",
	"and_check_form" => "and check the form.",
	"and_try_again" => "and try again.",
	"none" => "none",
	"are_you_sure" => "Are you sure?",
	"delete_all" => "delete all",
	"really?" => "Really?",
	"delete_are_you_sure" => "You are going to delete the item below, are you sure?",
	"required_fields_missed" => "You haven't filled out some required fields.",
	"alphabetic_not_valid" => "You have inserted a/some number/s into an alphabetic field.",
	"numeric_not_valid" => "You have inserted a/some non-numeric characters into a numeric field.",
	"email_not_valid" => "The e-mail address/es you have inserted is/are not valid.",
	"timestamp_not_valid" => "The timestamp/s you have inserted is/are not valid.",
	"url_not_valid" => "The url/s you have inserted is/are not valid.",
	"phone_not_valid" => "The phone number/s you have inserted is/are not valid.<br>Please use the \"+(country code)(area code)(number)\" format e.g. +390523599318.",
	"date_not_valid" => "You have inserted one or more not valid dates.",
	"similar_records" => "The items below seem similar to the one you want to insert.<br>What do you want to do?",
	"no_records_found" => "No items found.",
	"records_found" => "items found",
	"number_records" => "Number of items: ",
	"details_of_record" => "Details of the item",
	"details_of_record_just_inserted" => "Details of the item just inserted",
	"edit_record" => "Edit the item",
	"back_to_the_main_table" => "Back to the main table",
	"previous" => "Previous",
	"next" => "Next",
	"edit_profile" => "Update your profile information",
	"i_think_that" => "I think that ",
	"is_similar_to" => " is similar to ",
	"page" => "Page ",
	"of" => " of ",
	"records_per_page" => "items per page",
	"day" => "Day",
	"month" => "Month",
	"year" => "Year",
	"administration" => "Administration",
	"create_update_internal_table" => "Create or update internal table",
	"other...." => "other....",
	"insert_record" => "Insert a new item",
	"search_records" => "Search for items",
	"exactly" => "exactly",
	"like" => "like",
	"required_fields_red" => "Required fields are in red.",
	"insert_result" => "Insert result:",
	"record_inserted" => "item correctly inserted.",
	"update_result" => "Update result:",
	"record_updated" => "Item correctly updated.",
	"profile_updated" => "Your profile has been correctly updated.",
	"delete_result" => "Delete result:",
	"record_deleted" => "Item correctly deleted.",
	"duplication_possible" => "Duplication is possible",
	"fields_max_length" => "You have inserted too much text in one or more field.",
	"current_upload" => "Current file",
	"delete" => "delete",
	"total_records" => "Total items",
	"confirm_delete?" => "Confirm delete?",
	"is_equal" => "is equal to",
	"is_different" => "is not equal to",
	"is_not_null" => "is not null",
	"is_not_empty" => "is not empty",
	"contains" => "contains",
	"starts_with" => "starts with",
	"ends_with" => "ends with",
	"greater_than" => ">",
	"less_then" => "<",
	"export_to_csv" => "Export to CSV",
	"new_insert_executed" => "New insert executed",
	"new_update_executed" => "New update executed",
	"null" => "Null",
	"is_null" => "is null",
	"is_empty" => "is empty",
	"continue" => "continue"
	);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Error, the internal database is empty.",
	"get" => "Error in get variables.",
	"no_functions" => "Error, no functions selected<br>Please go back to the homepage.",
	"no_unique_key" => "Error, you haven't any primary key in your table.",	
	"upload_error" => "An error occurred when uploading a file.",
	"no_authorization_update_delete" => "You don't have the authorization to modify/delete this item.",
	"no_authorization_view" => "You don't have the authorization to view this item.",
	"deleted_only_authorizated_records" => "Only the items on which you have the authorization have been deleted.",
	"record_from_which_you_come_no_longer_exists" => "The item from which you come no longer exists.",
	"date_not_representable" => "A date value in this item can't be represented with DaDaBIK day-month-year listboxes, the value is: ",
	"this_record_is_the_last_one" => "This item is the last one.",
	"this_record_is_the_first_one" => "This item is the first one."
	);

//login messages
$login_messages_ar = array(
	"username" => "username",
	"password" => "password",
	"please_authenticate" => "You need to be identified to continue",
	"login" => "login",
	"username_password_are_required" => "Username and password are required",
	"pwd_gen_link" => "create password",
	"incorrect_login" => "Username or password incorrect",
	"pwd_explain_text" =>"Insert a word to be used as password and press <b>Crypt it!</b>.",
	"pwd_explain_text_2" =>"Press <b>Register</b> to write it in the below form",
	"pwd_suggest_email_sending"=>"You may want to send yourself a mail to remeber the password",
	"pwd_send_link_text" =>"send mail!",
	"pwd_encrypt_button_text" => "Cript it!",
	"pwd_register_button_text" => "Register password and exit"
);
?>