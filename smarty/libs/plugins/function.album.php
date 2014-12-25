<?php


    function smarty_function_album( $params, $smarty ) {
        /**@var $smarty Smarty */
        if( isset( $_SESSION['id'] ) ) {
            require_once 'photoAlbum.php';
            $pa = new photoAlbum();
            $smarty->assign( "userId", $pa->getCurrentUsersId() );
            $albumList = $pa->getCurrentUsersAlbums();
            $smarty->assign( "albums", $albumList );
        }

       // $smarty->assign( $params['assign'], $items );
    }


?>