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




<p><b>You are using DaDaBIK version <?php echo $current_release_version ?>, installed on <?php echo format_date($current_release_date); ?>, the last version of DaDaBIK is <?php echo $last_release_version; ?> released on <?php echo format_date($last_release_date); ?></b></p>

<?php
if ($current_release_version != $last_release_version){

echo '<p>You are not running the last release of DaDaBIK, the release you are running might have bugs and security holes, see the official <a href="http://www.dadabik.org/index.php?function=show_changelog">change log</a> for further information. You can upgrade DaDaBIK <a href="http://www.dadabik.org/download/">here</a>.';

}
else{
echo '<p>You are runnning the last release of DaDaBIK</p>';
}
?>
