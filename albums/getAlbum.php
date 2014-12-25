<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: atlete
     * Date: 07.08.12
     * Time: 14:24
     * To change this template use File | Settings | File Templates.
     */

    include_once '../include.php';
    $answer = array( "error"=> 0, "albumList"=> array() );
    if( isset( $_SESSION['id'] ) ) {
        include_once '../photoAlbum.php';
        $pa = new photoAlbum();
        $albumList = $pa->getCurrentUsersAlbums();
        $albums = array();
        foreach( $albumList as $item ) {
            $t_album['id'] = $item["albumid"];
            $t_album['name'] = $item["name"];
            $t_album['filename'] = 'userpics/'.$_SESSION['id']."/".$item['filename'];
            $albums[] = $t_album;
        }
        $answer['albumList'] = $albums;
    } else {
        $answer['error'] = 1;
    }
    echo json_encode( $answer );