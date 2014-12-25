<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: atlete
     * Date: 10.08.12
     * Time: 17:05
     */
    include_once '../include.php';
    include_once( "phpFlickr.php" );
    $f = new phpFlickr( FLICKR_KEY, FLICKR_SECRET );
    $f->auth( "read" );
    $answer = array( 'error'=> 0 );
    if( isset( $_POST['pictures'] ) ) {
        // check user directory
        $userDir = realpath( dirname( __FILE__ ) ).'/../userpics/'.$_SESSION['id'];
        if( !is_dir( realpath( dirname( __FILE__ ) ).'/../userpics/'.$_SESSION['id'] ) ) {
            // if not exists - create it
            mkdir( realpath( dirname( __FILE__ ) ).'/../userpics/'.$_SESSION['id'], 0777 );
        }
        require_once '../pendingDownload.php';
        $pd = new PendingDownload();
        foreach( $_POST['pictures'] as $photoId ) {
            $cphoto = $f->photos_getSizes( $photoId );
            $cphoto = $cphoto[count( $cphoto ) - 1];
            $pd->addUrl( $_SESSION['id'], $cphoto['source'], $cphoto['height'], $cphoto['width'] );
        }
    } else {
        $answer['error'] = 1;
    }
    echo json_encode( $answer );