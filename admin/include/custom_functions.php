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

// custom validation functions

// custom validation function template
// each custom validation function receives in input an array $parameters containing all the values coming from the form posted, including the fields values
// each custom validation function must return the boolean true or the boolean false.
// in this simple exeample we just check if the field quantity_product value is between 0 and 30
// you also have to specify the error message in your language file (see /include/languages), using as a key nameofthefunction_not_valid; in this example, you would add a sentence to the $normal_messages_ar array having key = dadabik_validate_quantity_product_not_valid 
// why you have in inputs all the $_POST values? Because the validation rule of a field could depend on the value of the others
// please note that only NOT NULL and NOT empty field values are validated, if you are looking for a method to check requiredness, you can simply set a field as required in the form configurator.   

/*
function dadabik_validate_quantity_product($parameters_ar){
	
	
	if ($parameters_ar['quantity_product'] >= 0 && $parameters_ar['quantity_product'] <= 30){
		return true;
	}
	else{
		return false;
	}
	
}
*/


// custom formatting functions

// custom formatting function template
// each custom formatting function receives in input the value to display $value
// each custom validation function must return the formatted value
// in this simple exeample we just format the field last_name_customer to be displayed in red
/*
function dadabik_format_last_name_customer($value){

	return '<span style="color:red">'.$value.'</span>';
	
	
}
*/

function dadabik_format_user_photos_userid($value){
	global $user_photos_userid;
	$user_photos_userid = $value;
	return $value;
}

function dadabik_format_user_photos_filename($value){
	global $user_photos_userid;
	return '<img src="'.WEBSITE_URL."/userpics/{$user_photos_userid}/{$value}\" ".' style="max-width:200px;max-height:200px;">';
}

function dadabik_format_img_maxwidthheight_200($value){
	return " <img src='../images/{$value}' style='max-width:200px;max-height:200px;'>";
}

function dadabik_format_userid_link($value){
	dadabik_format_user_photos_userid($value);
	return '<a href="index.php?table_name=users&function=details&where_field=userid&where_value='.$value.'"> '.$value.' </a>';
}

    function dadabik_format_json_span($value){
        return "<span class='jsonHoldingSpan'>{$value}</span>";
    }



?>