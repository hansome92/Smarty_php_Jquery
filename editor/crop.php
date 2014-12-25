<?php
    /**
     * File: crop.php
     * Created: 11/18/12  10:22 PM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */


    $defaultSuccess = FALSE;
    if( isset( $_POST['x1'] ) && isset( $_POST['y1'] ) && isset( $_POST['x2'] ) && isset( $_POST['y2'] ) && isset( $_POST['src'] ) ) {
        // print_r( $_POST );
        if( preg_match( '/[^0-9]/', $_POST['x1'].$_POST['y1'].$_POST['x2'].$_POST['y2'] ) || ( $_POST['x1'] - $_POST['x2'] == 0 ) || ( $_POST['y1'] - $_POST['y2'] == 0 ) ) {
            die( 'There was a problem with your submission. Please try again.' );
        }

        require_once '../fileuploader/imageFileValidator.php';
        require_once '../user.php';
        $user = new user();

        $ds = DIRECTORY_SEPARATOR;
        $userid = $user->getCurrentUsersId();
        if( is_null( $userid ) ) {
            die();
        }
        $newFilename = md5( $userid.time().'crop' );
        $targetPath = realpath( dirname( __FILE__ ) ).$ds.'..'.$ds.'userpics'.$ds.$userid.$ds;
        $fullPath = strstr( $_POST['src'], '/userpics/' );
        $albumid = 0;
        if( $fullPath == FALSE ) {
            //this is a trinket
            $fullPath = strstr( $_POST['src'], '/images/' );
            if( $fullPath == FALSE ) {
                die( 'Photo Source Error.' );
            }
            $pathArray = explode( '/', $fullPath );
            $fileName = $pathArray[2];
            $imagePath = realpath( dirname( __FILE__ ) ).$ds.'..'.$ds.'images'.$ds.$fileName;
        } else {
            //this is a userpic
            $pathArray = explode( '/', $fullPath );
            $fileName = cleanAlphaNum( $pathArray[3], TRUE );
            $res = $user->sql->query( "select * from photos where userid = \"{$userid}\" and filename=\"{$fileName}\" and deleted=0 " );
            if( $photo = $res->fetch_assoc() ) {
                $albumid = $photo['albumid'];
                $imagePath = realpath( dirname( __FILE__ ) ).$ds.'..'.$ds.'userpics'.$ds.$userid.$ds.$fileName;
            } else {
                die( 'That is not your picture.' );
            }
        }

        $validator = new imageFileValidator();
        $newFilename = $newFilename.'.'.$validator->getExtension( $fileName );
        $targetPath = $targetPath.$newFilename;
        $res = $validator->cropPhoto( $_POST['x1'], $_POST['y1'], $_POST['x2'], $_POST['y2'], $imagePath, $targetPath );
        $fullPath = sanitize( $fullPath );
        $user->sql->query( "INSERT INTO photos(userid, albumid, source, filename) values (\"{$userid}\", {$albumid}, \"{$fullPath}\", \"{$newFilename}\") " );
        $res['albumid'] = $albumid;
        echo json_encode( $res );
    } else {
        echo json_encode( 'Error uploading profile picture. Try using a modern browser.' );
    }