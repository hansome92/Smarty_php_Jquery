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
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>DaDaBIK database front-end - www.dadabik.org</title>
<link rel="stylesheet" href="css/styles_screen.css" type="text/css" media="screen">
<link rel="stylesheet" href="css/styles_print.css" type="text/css" media="print">
<meta name="Generator" content="DaDaBIK 5.0 - http://www.dadabik.org/">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="include/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	mode : "specific_textareas",
	theme : "advanced",
	editor_selector : "rich_editor"
});
</script>

<script language="Javascript">
function show_admin_help(title, content)
{
    if (document.all) {
        topoffset=document.body.scrollTop;
        leftoffset=document.body.scrollLeft;
        WIDTH=document.body.clientWidth;
        HEIGHT=document.body.clientHeight;
    } else {
        topoffset=pageYOffset;
        leftoffset=pageXOffset;
        WIDTH=window.innerWidth;
        HEIGHT=window.innerHeight;
    }

    newtop=((HEIGHT-200)/2)+topoffset;

    if (document.all) {
        newleft=150;
    } else {
        newleft=((WIDTH-400)/2)+leftoffset;
    }

    document.getElementById('div_help').style.left=newleft;
    document.getElementById('div_help').style.top=newtop;

    document.getElementById('div_help_content_title').innerHTML = '<div align="right"><a href="javascript:hide_help();" style="text-decoration:none;color:#ff7700">X</a></div>'+title;
    document.getElementById('div_help_content_text').innerHTML = content;
    document.getElementById('div_help').style.display = 'block';
}

function hide_help()
{
    document.getElementById('div_help').style.display = 'none';
    document.getElementById('div_help_content_title').innerHTML = "";
    document.getElementById('div_help_content_text').innerHTML = "";
}


function show_template_instructions(title)
{
    if (document.all) {
        topoffset=document.body.scrollTop;
        leftoffset=document.body.scrollLeft;
        WIDTH=document.body.clientWidth;
        HEIGHT=document.body.clientHeight;
    } else {
        topoffset=pageYOffset;
        leftoffset=pageXOffset;
        WIDTH=window.innerWidth;
        HEIGHT=window.innerHeight;
    }

    newtop=((HEIGHT-200)/2)+topoffset;

    if (document.all) {
        newleft=150;
    } else {
        newleft=((WIDTH-400)/2)+leftoffset;
    }

    document.getElementById('div_template_instructions').style.left=newleft;
    document.getElementById('div_template_instructions').style.top=newtop;

    document.getElementById('div_template_instructions_content_title').innerHTML = '<div align="right"><a href="javascript:hide_template_instructions();" style="text-decoration:none;color:#ff7700">X</a></div>'+title;
    document.getElementById('div_template_instructions_content_text').style.display = 'block';
    document.getElementById('div_template_instructions').style.display = 'block';
}

function hide_template_instructions()
{
    document.getElementById('div_template_instructions').style.display = 'none';
    //document.getElementById('div_template_instructions_content_title').innerHTML = "";
    //document.getElementById('div_template_instructions_content_text').innerHTML = "";
}

</script>

<?php if ($page_name === 'interface_configurator'){ ?>
<script language="Javascript" src="include/hide_show_interface_configurator_fields.js"></script>
<?php } ?>

<script language="Javascript">
//opens a js popup with customizable options. Popup will close and open
//again upon call from pwd-generator link
var mywindow;
function generic_js_popup(url,name,w,h){
	if (mywindow!=null && !mywindow.closed){
	mywindow.close();
	}
var options;
options = "resizable=yes,toolbar=0,status=1,menubar=0,scrollbars=1, width=" + w + ",height=" + h + ",left="+(screen.width-w)/2+",top="+(screen.height-h)/6;
mywindow = window.open(url,name,options);
mywindow.focus();
}

function enable_disable_input_box_insert_edit_form(null_checkbox_prefix, year_field_suffix, month_field_suffix, day_field_suffix, hours_field_suffix, minutes_field_suffix, seconds_field_suffix)
// goal: set the status (disabled|enabled) of each input element of the insert|edit form, depending on the status (checked|not checked) of the corresponding null value checkbox (if it exists)
// input: null_checkbox_prefix, year_field_suffix, month_field_suffix, day_field_suffix
{
	var count = document.getElementById('dadabik_main_form').length;
	var null_checkbox_prefix_length = null_checkbox_prefix.length;

	// for each element of the form
	for (i=0;i<count;i++)
	{
		// if the element is a null value checkbox element
		if (document.getElementById('dadabik_main_form').elements[i].name.substr(0,null_checkbox_prefix_length) == null_checkbox_prefix){

			// check if the field is a datetime field type

			var hours_field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length) + hours_field_suffix;

			var b = new Array;
			b = document.getElementsByName(hours_field_name);

			// check if the field is a date field type

			var year_field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length) + year_field_suffix;

			var a = new Array;
			a = document.getElementsByName(year_field_name);

			var field_type_is_date = 0;
			var field_type_is_date_time = 0;


			if (b[0]){ // if the relative hours field exists
				field_type_is_date_time = 1;
			} // end if
			else if (a[0]){ // if the relative year field exists
				field_type_is_date = 1;
			} // end if


			if (field_type_is_date == 1 || field_type_is_date_time == 1){
				// get the name of the relative input controls

				var month_field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length) + month_field_suffix;

				var day_field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length) + day_field_suffix;

				if (field_type_is_date_time == 1){

					var minutes_field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length) + minutes_field_suffix;

					var seconds_field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length) + seconds_field_suffix;
				}

				// and set the relative input controls enabled/disabled depending on the null value checkbox status (checked|not checked)
				var a = new Array;
				a = document.getElementsByName(year_field_name);

				var b = new Array;
				b = document.getElementsByName(month_field_name);

				var c = new Array;
				c = document.getElementsByName(day_field_name);

				if (field_type_is_date_time == 1){

				var d = new Array;
				d = document.getElementsByName(hours_field_name);

				var e = new Array;
				e = document.getElementsByName(minutes_field_name);

				var f = new Array;
				f = document.getElementsByName(seconds_field_name);

				}


				if (document.getElementById('dadabik_main_form').elements[i].checked == true){
					a[0].disabled = true;
					b[0].disabled = true;
					c[0].disabled = true;

					if (field_type_is_date_time == 1){

						d[0].disabled = true;
						e[0].disabled = true;
						f[0].disabled = true;

					}


				} // end if
				else{
					a[0].disabled = false;
					b[0].disabled = false;
					c[0].disabled = false;

					if (field_type_is_date_time == 1){
						d[0].disabled = false;
						e[0].disabled = false;
						f[0].disabled = false;

					}

				} // end else
			} // end if
			else {
				// get the name of the relative input control
				var field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(null_checkbox_prefix_length);

				// and set the relative input control enabled/disabled depending on the null value checkbox status (checked|not checked)
				var a = new Array;
				a = document.getElementsByName(field_name);

				if (document.getElementById('dadabik_main_form').elements[i].checked == true){
					a[0].disabled = true;
				} // end if
				else{
					a[0].disabled = false;
				} // end else
			} // end else
		} // end if
	} // end for
} // end function

function enable_disable_input_box_search_form(field_name, select_type_select_suffix, year_field_suffix, month_field_suffix, day_field_suffix, hours_field_suffix, minutes_field_suffix, seconds_field_suffix)
// goal: set the status (disabled|enabled) of an input element of the search form, depending on the status of the corresponding select_type_select field
// input: field_name, select_type_select_suffix, year_field_suffix, month_field_suffix, day_field_suffix
{

	// check if the field is a date field type

	var year_field_name = field_name + year_field_suffix;
	var hours_field_name = field_name + hours_field_suffix;

	// check if the field is a datetime field type

	var b = new Array;
	b = document.getElementsByName(hours_field_name);

	// check if the field is a date field type

	var a = new Array;
	a = document.getElementsByName(year_field_name);

	var field_type_is_date = 0;
	var field_type_is_date_time = 0;


	if (b[0]){ // if the relative hours field exists
		field_type_is_date_time = 1;
	} // end if
	else if (a[0]){ // if the relative year field exists
		field_type_is_date = 1;
	} // end if


	if (field_type_is_date == 1 || field_type_is_date_time == 1){
		// get the name of the relative input controls

		var month_field_name = field_name + month_field_suffix;

		var day_field_name = field_name + day_field_suffix;

		if (field_type_is_date_time == 1){

			var minutes_field_name = field_name + minutes_field_suffix;

			var seconds_field_name = field_name + seconds_field_suffix;
		}

		// and set the relative input controls enabled/disabled depending on the null value checkbox status (checked|not checked)
		var a = new Array;
		a = document.getElementsByName(year_field_name);

		var b = new Array;
		b = document.getElementsByName(month_field_name);

		var c = new Array;
		c = document.getElementsByName(day_field_name);



		if (field_type_is_date_time == 1){

				var d = new Array;
				d = document.getElementsByName(hours_field_name);

				var e = new Array;
				e = document.getElementsByName(minutes_field_name);

				var f = new Array;
				f = document.getElementsByName(seconds_field_name);

		}

		var g = new Array;
		g = document.getElementsByName(field_name+select_type_select_suffix);

		if (g[0].value == 'is_null'){
			a[0].disabled = true;
			b[0].disabled = true;
			c[0].disabled = true;

					if (field_type_is_date_time == 1){

						d[0].disabled = true;
						e[0].disabled = true;
						f[0].disabled = true;

					}
		} // end if
		else{
			a[0].disabled = false;
			b[0].disabled = false;
			c[0].disabled = false;

					if (field_type_is_date_time == 1){

						d[0].disabled = false;
						e[0].disabled = false;
						f[0].disabled = false;

					}
		} // end else
	} // end if
	else{
		// set the relative input control enabled/disabled depending on the null value checkbox status (checked|not checked)
		var a = new Array;
		a = document.getElementsByName(field_name);

		var b = new Array;
		b = document.getElementsByName(field_name+select_type_select_suffix);

		if (b[0].value == 'is_null' || b[0].value == 'is_empty'){
			a[0].disabled = true;
		} // end if
		else{
			a[0].disabled = false;
		} // end else
	} // end else

} // end function

function show_hide_text_other()
// goal: show/hide the textbox close to select_single fields according to the "other" field selected or not
{
	var other_textbox_div_suffix =  '_other____';

	var count = document.getElementById('dadabik_main_form').length;
	var other_textbox_div_suffix_length = other_textbox_div_suffix.length;

	// for each element of the form
	for (i=0;i<count;i++)
	{


		var field_name_length = document.getElementById('dadabik_main_form').elements[i].name.length;



		// if the field is a other text field
		if (document.getElementById('dadabik_main_form').elements[i].name.substr(field_name_length-other_textbox_div_suffix_length) == other_textbox_div_suffix){



			// get the name of the relative input control
			var field_name = document.getElementById('dadabik_main_form').elements[i].name.substr(0, field_name_length-other_textbox_div_suffix_length);


			// and set the relative input control enabled/disabled depending on the null value checkbox status (checked|not checked)
			var a = new Array;
			a = document.getElementsByName(field_name);

			var div_name = 'other_textbox_'+field_name;

			if (a[0].value == '......'){


				 document.getElementById(div_name).style.visibility = 'visible';
			} // end if
			else{

				 document.getElementById(div_name).style.visibility = 'hidden';
			}

		} // end if

	} // end for
} // end function

</script>
</head>

<body
<?php
if (isset($_GET["type_mailing"])){
	if ($_GET["type_mailing"] == "labels") {
		echo " leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" onload=\"javascript:alert('".$normal_messages_ar["print_warning"]."')\"";
	} // end if
} // end if
?>
<?php
if ($page_name === 'main' && ($function === 'insert' || $function === 'edit' || $function === 'update')) {
?>
onload="javascritp:enable_disable_input_box_insert_edit_form('<?php echo $null_checkbox_prefix.'\', \''.$year_field_suffix.'\', \''.$month_field_suffix.'\', \''.$day_field_suffix.'\', \''.$hours_field_suffix.'\', \''.$minutes_field_suffix.'\', \''.$seconds_field_suffix; ?>');show_hide_text_other()"
<?php
} // end if
if ($page_name === 'interface_configurator') {
?>
onload="javascritp:hide_show_interface_configurator_fields(document.getElementById('e1'))"
<?php
} // end if
?>
>
<div id="div_help" style="width: 600px;">
<div id="div_help_content">
<div id="div_help_content_title"></div>
<div id="div_help_content_text"></div>
</div>
</div>
<table class="main_table" cellpadding="10" >
<tr>
<td valign="middle" align="center">
<!--<h1 class="onlyscreen" align="center">DaDaBIK</h1>-->


<?php
if ($page_name === 'main' || $page_name === 'admin' || $page_name === 'permissions_manager'  || $page_name === 'interface_configurator' || $page_name === 'install' || $page_name === 'upgrade' || $page_name === 'tables_inclusion' || $page_name === 'db_synchro' || $page_name === 'datagrid_configurator') {
	$class_temp = 'table_interface_container';
}
else{
	$class_temp = 'table_interface_container_login';
}

?>
<table class="<?php echo $class_temp; ?>" cellspacing="0" >

<?php
if ($page_name === 'main' || $page_name === 'login') {
	echo '<tr class="table_interface_container_tr_logo"><td><img src="images/logo.png">';

}
elseif ($page_name === 'admin' || $page_name === 'interface_configurator' || $page_name === 'permissions_manager' || $page_name === 'tables_inclusion' || $page_name === 'db_synchro' || $page_name === 'datagrid_configurator') {
	echo '<tr class="table_interface_container_tr_logo_admin"><td><table width="100%" cellspacing="0" cellpadding="0"><tr><td><img src="images/logo.png">';
	echo '</td><td align="right"> <font size="10">ADMIN AREA</font>&nbsp;<br/>';

	echo '<a';

	if ($page_name === 'admin' && $function === 'show_help'){
		echo ' class="top_link_admin_selected"';
	}

	echo ' href="admin.php?function=show_help&table_name='.urlencode($table_name).'">&nbsp;Help&nbsp;</a> - ';

	echo '<a';

	if ($page_name === 'admin' && $function === 'show_hotscripts_feedback'){
		echo ' class="top_link_admin_selected"';
	}


	echo ' href="admin.php?function=show_hotscripts_feedback&table_name='.urlencode($table_name).'">&nbsp;Hotscripts Feedback&nbsp;</a> - ';


	echo '<a';

	if ($page_name === 'admin' && $function === 'show_about'){
		echo ' class="top_link_admin_selected"';
	}


	echo ' href="admin.php?function=show_about&table_name='.urlencode($table_name).'">&nbsp;About/Check upgrade&nbsp;</a> - <a href="'.$dadabik_main_file.'">Exit</a>';

	echo '</td></tr></table>';
}
elseif ($page_name === 'install') {
	//echo '<tr class="table_interface_container_tr_logo_admin"><td><tr><td><img src="images/logo.gif">';
	//echo '</td><td align="right"><font size="10">INSTALLATION</font>&nbsp;</td></tr></table>';

	echo '<tr class="table_interface_container_tr_logo_admin"><td><table width="100%" cellspacing="0" cellpadding="0"><tr><td><img src="images/logo.png">';
	echo '</td><td align="right"> <font size="10">INSTALLATION</font>&nbsp;<br/>';
	echo '</td></tr></table>';
}
elseif ($page_name === 'upgrade') {
	//echo '<tr class="table_interface_container_tr_logo_admin"><td><tr><td><img src="images/logo.gif">';
	//echo '</td><td align="right"><font size="10">UPGRADE</font>&nbsp;</td></tr></table>';

	echo '<tr class="table_interface_container_tr_logo_admin"><td><table width="100%" cellspacing="0" cellpadding="0"><tr><td><img src="images/logo.png">';
	echo '</td><td align="right"> <font size="10">UPGRADE<font>&nbsp;<br/>';
	echo '</td></tr></table>';
}
?>
</td>
</tr>

<?php
if ($page_name === 'main') {
?>

<tr class="table_interface_container_tr_menu">
<td>
	<!--[if !lte IE 9]><!-->
	<table class="table_interface_container_table_menu">
	<!--<![endif]-->

	<!--[if lte IE 9]>
	<table class="table_interface_container_table_menu_ie9">
	<![endif]-->

	<tr>

	<?php

	if ($home_available === 1){

	?>
	
	<td><a class="home" href="<?php echo $home_url; ?>">&nbsp;&nbsp;<?php echo $normal_messages_ar["home"]; ?>&nbsp;&nbsp;</a>

	<?php

	}

	?>





 <?php
if (!isset($is_items_table) || $is_items_table !== 1){ // if it's an item table no insert search show results and show all links

?>



  <?php
if ($function === 'search'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

 <td style="white-space: nowrap;"><a class="<?php echo $class_temp; ?>" href="<?php $dadabik_main_file; ?>?function=search&table_name=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo $normal_messages_ar["show_records"]; ?>&nbsp;&nbsp;</a></td>



<?php
if ($function === 'show_search_form'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

 </td><td style="white-space: nowrap;"><a class="<?php echo $class_temp; ?>" href="<?php $dadabik_main_file; ?>?function=show_search_form&table_name=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo $submit_buttons_ar["search_short"]; ?>&nbsp;&nbsp;</td>






<?php
if ($function === 'show_insert_form' || $function === 'insert'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>
<?php
if ($enable_insert == "1"){
?>
	 </td><td style="white-space: nowrap;"><a class="<?php echo $class_temp; ?>" href="<?php $dadabik_main_file; ?>?function=show_insert_form&table_name=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo $submit_buttons_ar["insert_short"]; ?>&nbsp;&nbsp;</a>
<?php
}
?>






<?php
/*
if ( $table_name === $users_table_name && ($function === 'edit' || $function === 'show_insert_form' || $function === 'insert')){
?>
 </td><td nowrap><a class="bottom_menu" href="javascript:void(generic_js_popup('pwd.php','',600,300))">&nbsp;&nbsp;<?php echo $login_messages_ar['pwd_gen_link']; ?>&nbsp;&nbsp;</a>
 <?php
}
*/
}
?>
<?php
if ($enable_authentication === 1){
?>
	 </td><td style="white-space: nowrap;"><a class="bottom_menu" href="<?php echo $dadabik_login_file; ?>?function=logout">&nbsp;&nbsp;<?php echo $normal_messages_ar["logout"]; ?>&nbsp;&nbsp;</a>
<?php
}
if ($current_user_is_administrator === 1) {
?>
 </td><td style="width:*"><a class="bottom_menu" href="admin.php">&nbsp;&nbsp;<?php echo $normal_messages_ar["administration"]; ?>&nbsp;&nbsp;</a>
 <?php
} // end if
?>
 </td>
 <td style="width:100%"></td>

<?php


foreach ($static_pages_ar as $static_page){
if ($static_page['is_homepage_static_page'] == 'n'){


if ($function === 'show_static_page' && isset($id_static_page) && (int)$id_static_page === (int)$static_page['id_static_page']){
	$class_temp = 'static_pages_menu_active';
}
else{
	$class_temp = 'static_pages_menu';
}
?>

 <td style="white-space: nowrap;"><a class="<?php echo $class_temp; ?>" href="<?php $dadabik_main_file; ?>?function=show_static_page&id_static_page=<?php echo $static_page['id_static_page'] ?>">&nbsp;&nbsp;<?php echo $static_page['link_static_page']; ?>&nbsp;&nbsp;</a></td>


<?php
}
}
?>




</tr>
</table>

</td>
</tr>
<?php
}
?>



<?php
// this is just for the admin area
if ($page_name === 'admin' || $page_name === 'interface_configurator' || $page_name === 'permissions_manager' || $page_name === 'tables_inclusion' || $page_name === 'db_synchro' || $page_name === 'datagrid_configurator') {
?>

<tr class="table_interface_container_tr_menu_admin_area">
<td>
	<!--[if !lte IE 9]><!-->
	<table class="table_interface_container_table_menu_admin_area">
	<!--<![endif]-->

	<!--[if lte IE 9]>
	<table class="table_interface_container_table_menu_admin_area_ie9">
	<![endif]-->

	<tr>


  <?php
if ($page_name === 'admin' && $function === 'show_admin_home'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

 <td style="white-space: nowrap;"><a class="<?php echo $class_temp; ?>" href="admin.php?table_name=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Home'; ?>&nbsp;&nbsp;</a></td>


    <?php
if ($page_name === 'tables_inclusion'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

 <td style="white-space: nowrap;"><a class="<?php echo $class_temp; ?>" href="tables_inclusion.php?table_name=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Tables inclusion'; ?>&nbsp;&nbsp;</a></td>




    <?php
if ($page_name === 'datagrid_configurator'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

 <td style="white-space: nowrap;"><a class="<?php echo $class_temp; ?>" href="datagrid_configurator.php?table_name=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Datagrids configurator'; ?>&nbsp;&nbsp;</a></td>



   <?php
if ($page_name === 'interface_configurator'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

 <td style="white-space: nowrap;"><a class="<?php echo $class_temp; ?>" href="internal_table_manager.php?table_name=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Forms configurator'; ?>&nbsp;&nbsp;</a></td>



      <?php
if ($page_name === 'permissions_manager'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td style="white-space: nowrap;"><a class="<?php echo $class_temp; ?>" href="permissions_manager.php?table_name=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Permissions'; ?>&nbsp;&nbsp;</a></td>

    <?php
if ($page_name === 'db_synchro'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

 <td style="white-space: nowrap;"><a class="<?php echo $class_temp; ?>" href="db_synchro.php?table_name=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'DB Synchro'; ?>&nbsp;&nbsp;</a></td>

      <?php
if ($page_name === 'admin' && $function === 'show_users'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td style="white-space: nowrap;"><a class="<?php echo $class_temp; ?>" href="admin.php?function=show_users&table_name=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Users'; ?>&nbsp;&nbsp;</a></td>


      <?php
if ($page_name === 'admin' && $function === 'show_static_pages'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

 <td style="white-space: nowrap;"><a class="<?php echo $class_temp; ?>" href="admin.php?function=show_static_pages&table_name=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Static pages'; ?>&nbsp;&nbsp;</a></td>



 <td style="width:100%"></td>
</tr>
</table>

</td>
</tr>
<?php
}
?>

<?php
// this is just for the install page
if ($page_name === 'install') {
?>

<tr class="table_interface_container_tr_menu_admin_area">
<td>
	<table class="table_interface_container_table_menu_admin_area"><tr>
 <td style="width:100%"></td>
</tr>
</table>

</td>
</tr>
<?php
}
?>

<?php
// this is just for the upgrade page
if ($page_name === 'upgrade') {
?>

<tr class="table_interface_container_tr_menu_admin_area">
<td>
	<table class="table_interface_container_table_menu_admin_area"><tr>
 <td style="width:100%"></td>
</tr>
</table>

</td>
</tr>
<?php
}
?>










<tr height="100%">
<td>
<?php
if ($page_name === 'main' || $page_name === 'admin' || $page_name === 'permissions_manager' || $page_name === 'interface_configurator' || $page_name === 'install' || $page_name === 'tables_inclusion' || $page_name === 'db_synchro' || $page_name === 'datagrid_configurator'){
	echo '<table cellpadding="5" height="100%" >';
}
else{ // login
	echo '<table cellpadding="5" class="table_login_form">';
}
?>
<tr>
<td valign="top" align="left"><br/>