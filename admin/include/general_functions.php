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

function strlen_custom($string){
	if (function_exists('mb_strlen')){
		return mb_strlen($string, 'UTF-8');
	}
	else{
		return strlen($string);
	}
} // end function strlen_custom

function strpos_custom(){
	$parameters_ar = func_get_args();
	
	if (!isset($parameters_ar[2])){
		$parameters_ar[2] = 0;
	}
	
	if (function_exists('mb_strpos')){
		return mb_strpos($parameters_ar[0], $parameters_ar[1], $parameters_ar[2], 'UTF-8');
	}
	else{
		return strpos($parameters_ar[0], $parameters_ar[1], $parameters_ar[2]);
	}
} // end function strpos_custom

function strtolower_custom($string){
	if (function_exists('mb_strtolower')){
		return mb_strtolower($string, 'UTF-8');
	}
	else{
		return strtolower($mb_strlen);
	}
} // end function strtolower_custom

function strtoupper_custom($string){
	if (function_exists('mb_strtoupper')){
		return mb_strtoupper($string, 'UTF-8');
	}
	else{
		return strtoupper($mb_strlen);
	}
} // end function strtolower_custom

function substr_custom(){
	$parameters_ar = func_get_args();
	
	if (function_exists('mb_substr')){
		if (isset($parameters_ar[2])){
			return mb_substr($parameters_ar[0], $parameters_ar[1], $parameters_ar[2], 'UTF-8');
		}
		else{
			return mb_substr($parameters_ar[0], $parameters_ar[1], mb_strlen($parameters_ar[0]), 'UTF-8');
		}
		
	}
	else{
		if (isset($parameters_ar[2])){
			return substr($parameters_ar[0], $parameters_ar[1], $parameters_ar[2]);
		}
		else{
			return substr($parameters_ar[0], $parameters_ar[1]);
		}
	}
} // end function substr_custom




function mail_custom($to, $subject, $message, $header = '') { 
  $header_ = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n"; 
  mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $header_ . $header); 
} // end function


function format_date($date)
// from "2000-12-15" to "15 Dec 2000"
{
	global $date_format, $date_separator;
	$temp_ar=explode("-",$date);
	$temp_ar[2] = substr_custom($temp_ar[2], 0, 2); // e.g. from 11 00:00:00 to 11 if the field is datetime
	switch ($date_format){
		case "literal_english":
			$date=@date("M j, Y",mktime(0,0,0,$temp_ar[1],$temp_ar[2],$temp_ar[0]));
			break;
		case "latin":
			$date = $temp_ar[2].$date_separator.$temp_ar[1].$date_separator.$temp_ar[0];
			break;
		case "numeric_english":
			$date = $temp_ar[1].$date_separator.$temp_ar[2].$date_separator.$temp_ar[0];
			break;
	} // end switch
	return $date;
}
	
function format_date_time($date_time)
// from "2000-12-15" to "15 Dec 2000"
{
	global $date_format, $date_separator;
	$temp_ar=explode(" ",$date_time);
	
	$temp_2_ar=explode("-",$temp_ar[0]);
	$temp_3_ar=explode(":",$temp_ar[1]);
	
	switch ($date_format){
		case "literal_english":
			$date=@date("M j, Y",mktime(0,0,0,$temp_2_ar[1],$temp_2_ar[2],$temp_2_ar[0])).' '.$temp_3_ar[0].':'.$temp_3_ar[1].':'.$temp_3_ar[2];
			break;
		case "latin":
			$date = $temp_2_ar[2].$date_separator.$temp_2_ar[1].$date_separator.$temp_2_ar[0].' '.$temp_3_ar[0].':'.$temp_3_ar[1].':'.$temp_3_ar[2];
			break;
		case "numeric_english":
			$date = $temp_2_ar[1].$date_separator.$temp_2_ar[2].$date_separator.$temp_2_ar[0].' '.$temp_3_ar[0].':'.$temp_3_ar[1].':'.$temp_3_ar[2];
			break;
	} // end switch
	return $date;
}

function split_date($date, &$day, &$month, &$year)
// goal: split a mysql date returning $day, $mont, $year
// input: $date, a MySQL date, &$day, &$month, &$year
// output: &$day, &$month, &$year
{
	$temp=explode("-",$date); 
	$day=$temp[2];
	$month=$temp[1];
	$year=$temp[0];
} // end function split_date

function split_date_time($date, &$day, &$month, &$year, &$hours, &$minutes, &$seconds)
// goal: split a mysql datetime returning $day, $mont, $year $hours $minutes $seconds
// input: $datetime, a MySQL datetime
// output: &$day, &$month, &$year...
{
	$temp=explode(" ",$date); 
	$temp_2 = explode("-",$temp[0]); 
	$temp_3 = explode(":",$temp[1]); 
	$day=$temp_2[2];
	$month=$temp_2[1];
	$year=$temp_2[0];
	
	$hours=$temp_3[0];
	$minutes=$temp_3[1];
	$seconds=$temp_3[2];
} // end function split_date

function build_date_select_type_select($field_name)
// goal: build a select with operators: nothing = > <
// input: $field_name
// output: $operator_select
{
	$operator_select = "<div class=\"select_element select_element_selec_type\">";
	$operator_select .= "<select name=\"".$field_name."\">";
	$operator_select .= "<option value=\"\"></option>";
	$operator_select .= "<option value=\"=\">=</option>";
	$operator_select .= "<option value=\">\">></option>";
	$operator_select .= "<option value=\"<\"><</option>";
	$operator_select .= "</select></div>";

	return $operator_select;
} // end function build_date_select_type_select

function display_sql($sql)
// goal: display a sql query
// input: $sql
// output: nothing
// global: $display_sql
{
	global $display_sql;
	if ($display_sql == "1"){
		//echo "<p><font color=\"#ff0000\"><b>Your SQL query (for debugging purpose): </b></font>".htmlentities($sql)."</p>";
		echo "<p><font color=\"#ff0000\"><b>Your SQL query (for debugging purpose): </b></font>".htmlspecialchars($sql)."</p>";
	} // end if
} // end function display_sql

function txt_out($message, $class="")
// goal: display text
// input: $message, $font_size, $font_color, $bold (1 if bold)
// output: nothing
{
	if ( $class != "") {
		$message = "<font class=\"".$class."\">".$message."</font>";
	}
	/*
	if ($bold == "1"){
		echo "<b>";
	} // end if
	if ($font_size != "" or $font_color != ""){
		echo "<font size=\"".$font_size."\" color=\"".$font_color."\">".$message."</font>";
	} // end if
	else{
		echo $message;
	} // end else 
	if ($bold == "1"){
		echo "</b>";
	} // end if
	*/
	echo $message;
} // end function txt_out

function get_pages_number($results_number, $records_per_page)
// goal: calculate the total number of pages necessary to display results
// input: $results_number, $records_per_page
// ouptut: $pages_number
{
	$pages_number = $results_number / $records_per_page;
	$pages_number = (int)($pages_number);
	if (($results_number % $records_per_page) != 0) $pages_number++; // if the reminder is greater than 0 I have to add a page because I have to round to excess

	return $pages_number;
} // end function get_pages_number

function build_date_select ($field_name, $day, $month, $year, $update_function = 0, $for_filters = 0)
// goal: build three select to select a data (day, mont, year), if are set $day, $month and $year select them
// input: $field_name, the name of the date field, $day, $month, $year (or "", "", "" if not set), $update_function (1 if call from update, it means I have to check if the data is representable)
// output: $date_select, the HTML date select
// global $start_year, $end_year
{
	global $start_year, $end_year, $year_field_suffix, $month_field_suffix, $day_field_suffix;

	$date_select = "";
	$day_select = "";
	$month_select = "";
	$year_select = "";
	
	$day_select .= "<select name=\"".$field_name.$day_field_suffix."\">";
	$month_select .= "<select name=\"".$field_name.$month_field_suffix."\">";
	$year_select .= "<select name=\"".$field_name.$year_field_suffix."\">";
	
	$count_representable_field = 0;

	for ($i=1; $i<=31; $i++){
		$day_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($day != "" and $day == $i){
			$day_select .= " selected";
		} // end if
		if($day == $i){
			$count_representable_field++;
		} // end if
		
		$day_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	for ($i=1; $i<=12; $i++){
		$month_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($month != "" and $month == $i){
			$month_select .= " selected";
		} // end if
		if($month == $i){
			$count_representable_field++;
		} // end if
		$month_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	for ($i=$start_year; $i<=$end_year; $i++){
		$year_select .= "<option value=\"$i\"";
		if($year != "" and $year == $i){
			$year_select .= " selected";
		} // end if
		if($year == $i){
			$count_representable_field++;
		} // end if
		$year_select .= ">".$i."</option>";
	} // end for
	
	if ($update_function === 1 && $count_representable_field !== 3){
		return false;
	}

	$day_select .= "</select>";
	$month_select .= "</select>";
	$year_select .= "</select>";
	
	if ($for_filters === 1){
		$date_select = "<td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$day_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$month_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_yyyy\">".$year_select."</div></td>";
	}
	else{
		$date_select = "<td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$day_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$month_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_yyyy\">".$year_select."</div></td>";
	}

	

	return $date_select;

} // end function build_date_select

function build_date_time_select ($field_name, $day, $month, $year, $hours, $minutes, $seconds, $update_function = 0, $for_filters = 0)
// goal: build six dropdown mensu  to select a datetime
// input: $update_function (1 if call from update, it means I have to check if the data is representable)
// output: $date_time_select, the HTML date select
{
	global $start_year, $end_year, $year_field_suffix, $month_field_suffix, $day_field_suffix, $hours_field_suffix, $minutes_field_suffix, $seconds_field_suffix;

	$date_time_select = "";
	$day_select = "";
	$month_select = "";
	$year_select = "";
	$hours_select = "";
	$minutes_select = "";
	$seconds_select = "";
	
	$day_select .= "<select name=\"".$field_name.$day_field_suffix."\">";
	$month_select .= "<select name=\"".$field_name.$month_field_suffix."\">";
	$year_select .= "<select name=\"".$field_name.$year_field_suffix."\">";
	$hours_select .= "<select name=\"".$field_name.$hours_field_suffix."\">";
	$minutes_select .= "<select name=\"".$field_name.$minutes_field_suffix."\">";
	$seconds_select .= "<select name=\"".$field_name.$seconds_field_suffix."\">";
	
	$count_representable_field = 0;

	for ($i=1; $i<=31; $i++){
		$day_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($day != "" and $day == $i){
			$day_select .= " selected";
		} // end if
		if($day == $i){
			$count_representable_field++;
		} // end if
		
		$day_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	for ($i=1; $i<=12; $i++){
		$month_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($month != "" and $month == $i){
			$month_select .= " selected";
		} // end if
		if($month == $i){
			$count_representable_field++;
		} // end if
		$month_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	for ($i=$start_year; $i<=$end_year; $i++){
		$year_select .= "<option value=\"$i\"";
		if($year != "" and $year == $i){
			$year_select .= " selected";
		} // end if
		if($year == $i){
			$count_representable_field++;
		} // end if
		$year_select .= ">".$i."</option>";
	} // end for
	
	if ($update_function === 1 && $count_representable_field !== 3){
		return false;
	}
	

	for ($i=0; $i<=23; $i++){
		$hours_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($hours != "" and $hours == $i){
			$hours_select .= " selected";
		} // end if
		$hours_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for
	for ($i=0; $i<=59; $i++){
		$minutes_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($minutes != "" and $minutes == $i){
			$minutes_select .= " selected";
		} // end if
		$minutes_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for
	for ($i=0; $i<=59; $i++){
		$seconds_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($seconds != "" and $seconds == $i){
			$seconds_select .= " selected";
		} // end if
		$seconds_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	$day_select .= "</select>";
	$month_select .= "</select>";
	$year_select .= "</select>";
	$hours_select .= "</select>";
	$minutes_select .= "</select>";
	$seconds_select .= "</select>";
	
	if ($for_filters === 1){
		$date_time_select = "<td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$day_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$month_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_yyyy\">".$year_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$hours_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$minutes_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$seconds_select."</div></td>";
	}
	else{
		$date_time_select = "<td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$day_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$month_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_yyyy\">".$year_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$hours_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$minutes_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$seconds_select."</div></td>";
	}

	

	return $date_time_select;

} // end function build_date_select


function contains_numerics($string)
// goal: verify if a string contains numbers
// input: $string
// output: true if the string contains numbers, false otherwise
{
	$count_temp = strlen_custom($string);
	if(ereg("[0-9]+", $string)) {
		return true;
		
	}
	return false;
} // end function contains_numerics

function is_valid_email($email)
// goal: chek if an email address is valid, according to its syntax
// input: $email
// output: true if it's valid, false otherwise
{
   return (preg_match( 
        '/^[-!#$%&\'*+\\.\/0-9=?a-z^_`{|}~]+'.   // the user name 
        '@'.                                     // the ubiquitous at-sign 
        '([-0-9a-z]+\.)+' .                      // host, sub-, and domain names 
        '([0-9a-z]){2,4}$/',                    // top-level domain (TLD) 
        trim(strtolower_custom($email)))); 
} // end function is_valid_email

/*
function is_valid_url($url)
// goal: chek if an url address is valid, according to its syntax, supports 4 letters domaains (e.g. .info), http https ftp protcols and also port numbers
// input: $url
// output: true if it's valid, false otherwise
{
	return eregi("^((ht|f)tps*://)((([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4}))|(([0-9]{1,3}\.){3}([0-9]{1,3})))(:[0-9]{1,4})*((/|\?)[a-z0-9~#%&'_\+=:\?\.-]*)*)$", $url); 
} // end function is_valid_url
*/

function is_valid_url($url)
// goal: chek if an url address is valid, according to its syntax
// input: $url
// output: true if it's valid, false otherwise
{
	//return (preg_match("/^(http(s?):\/\/|ftp:\/\/{1})((\w+\.){1,})\w{2,}$/i", $url));
	
	return ( ! preg_match('/^(http|https|ftp):\/\/([a-z0-9][a-z0-9_-]*(?:\.[a-z0-9][a-z0-9_-]*)+):?(\d+)?\/?/', strtolower_custom($url))) ? FALSE : TRUE;
    


} // end function is_valid_url




function is_valid_phone($phone)
// goal: chek if a phone numbers is valid, according to its syntax (should be: "+390523599314")
// input: $phone
// output: true if it's valid, false otherwise
{
	if ($phone[0] != "+"){
		return false;
	} // end if
	else{
		$phone = substr_custom($phone, 1); // delete the "+"
		if (!is_numeric($phone)){
			return false;
		} // end if
	} // end else
	return true;
} // end function is_valid_phone

/* 4.0 */
/*
function get_unique_field($table_name)
// goal: get the name of the first uniqe field in a table
// input: $table_name
// output: $unique_field_name, the name of the first unique field in the table
{
	global $conn, $db_name;
	$unique_field_name = "";
	$fields = list_fields_db($db_name, $table_name, $conn);
	$columns = num_fields_db($fields);

	for ($i = 0; $i < $columns; $i++) {
		if (strpos_custom(field_flags_db($fields, $i), "primary_key")){ // if the flag contain the word "primary_key"
			$unique_field_name = field_name_db($fields, $i);
			break;
		} // end if
	}
	return $unique_field_name;
} // end function get_unique_field
*/

function db_error($sql)
// goal: exit the script
// input: $sql
// output: nothing
{
	exit;
} // end function db_error

?>