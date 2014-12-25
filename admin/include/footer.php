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
<?php
if ($page_name === 'main' || $page_name === 'admin' || $page_name === 'interface_configurator' || $page_name === 'permissions_manager' || $page_name === 'tables_inclusion' || $page_name === 'db_synchro' || $page_name === 'datagrid_configurator'){
	echo '<br><br>';
	$powered_alignment = 'right';
}
else{
	$powered_alignment = 'center';
}
	
?>

</td>
</tr>
</table>

</td>
</tr>
</table>


<br><br>
<div class="powered_by_dadabik" align="<?php echo $powered_alignment; ?>">Powered by: <a href="http://www.dadabik.org/">DaDaBIK database front-end</a></div>
</td>
</tr>
</table>

</body>
</html>