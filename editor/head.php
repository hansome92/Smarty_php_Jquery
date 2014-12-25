<?php

    if( !isset( $notDirect ) ) {
        die();
    }

    require './../user.php';
    require './../category.php';
    require '../access.php';

    $access = new access();
?>

<script type="text/javascript">
    var secureView = {'enabled': <?= isset( $_GET['s'] ) ? " true, 'hash': '{$_GET['s']}' " : 'false'; ?>};
</script>

<!--    jQuery and Plugins    -->

<!--[if IE]><script src="js/excanvas.js<?=$cacheBust?>" type="text/javascript"></script><![endif]-->

<script src="<?=URL_PREFIX?>editor.js<?=$cacheBust?>" type="text/javascript"></script>

<link href="<?=URL_PREFIX?>editor.css<?=$cacheBust?>" rel="stylesheet" type="text/css" charset="utf-8">
