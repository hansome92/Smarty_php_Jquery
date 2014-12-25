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


<br>
<!--[if !lte IE 9]><!-->
<form method="post" class="css_form" action="<?php echo $dadabik_login_file; ?>?function=check_login">
<!--<![endif]-->

<!--[if lte IE 9]>
<form method="post" action="<?php echo $dadabik_login_file; ?>?function=check_login">
<![endif]-->

<table>
<tr><td class="td_label_login_form"><?php echo $login_messages_ar['username']; ?></td><td><input type="text" name="username_user" class="input_login_form"></td></tr>
<tr><td class="td_label_login_form"><?php echo $login_messages_ar['password']; ?></td><td><input type="password" name="password_user" class="input_login_form"></td></tr>
<tr><td colspan="2" align="right"><input type="submit" class="login_button" value="<?php echo $login_messages_ar['login']; ?> >>"></td></tr>
</table>

</form>

