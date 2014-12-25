<?php
    /*
    * jQuery File Upload Plugin PHP Example 5.7
    * https://github.com/blueimp/jQuery-File-Upload
    *
    * Copyright 2010, Sebastian Tschan
    * https://blueimp.net
    *
    * Licensed under the MIT license:
    * http://www.opensource.org/licenses/MIT
    */

    error_reporting( E_ALL | E_STRICT );
    include_once '../include.php';
    include_once '../photoAlbum.php';
    include_once 'imageFileValidator.php';
    include_once 'upload.class.php';

    if( isset( $_SESSION['id'] ) ) {
        $upload_handler = new UploadHandler( $_SESSION['id'] );

        header( 'Pragma: no-cache' );
        header( 'Cache-Control: no-store, no-cache, must-revalidate' );
        header( 'Content-Disposition: inline; filename="files.json"' );
        header( 'X-Content-Type-Options: nosniff' );
        header( 'Access-Control-Allow-Origin: *' );
        header( 'Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE' );
        header( 'Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size' );

        switch( $_SERVER['REQUEST_METHOD'] ) {
            case 'OPTIONS':
                break;
            case 'HEAD':
            case 'GET':
                //$upload_handler->get();
                break;
            case 'POST':
                $validator = new imageFileValidator();
                if( isset( $_REQUEST['_method'] ) && $_REQUEST['_method'] === 'DELETE' ) {
                    $upload_handler->delete();
                } else {
                    $ds = '\\';
                    $userDir = realpath( dirname( __FILE__ ) ).$ds.'..'.$ds.'userpics'.$ds.$_SESSION['id'].$ds;
                    ob_start();
                    $upload_handler->post();
                    $json_result = json_decode( ob_get_contents(), TRUE );
                    //ob_get_contents();
                    if( json_last_error() > 0 ) {
                        //on macs, seems to throw json error 4, but the files are already saved.
                      echo json_last_error_string();
                        die( 'stop that' );
                    }

                    ob_end_clean();
                    if( !is_dir( $userDir ) ) {
                        mkdir( $userDir, 0777 );
                    }
                    $pa = new photoAlbum();

                    $numRes = count( $json_result );
                    for( $i = 0; $i < $numRes; $i++ ) {
                        $res = $validator->validateFile( $json_result[$i], $userDir.$json_result[$i]['name']  );
                        if( $res['valid'] ) {
                            $oldName = $json_result[$i]['name']; // preg_replace( '[^a-zA-Z0-9\.]', '', $json_result[$i]['name'] );
                            $json_result[$i]['name'] = $pa->addPhotoNew( $_SESSION['id'], $oldName, 0, 0, 0 );

                            $url = isset( $json_result[$i]['url'] ) ? $json_result[$i]['url'] : '';
                            $json_result[$i]['url'] = str_replace( rawurlencode( $oldName ), $json_result[$i]['name'], $url );

                            if( file_exists( $userDir.$oldName ) ) {
                                rename( $userDir.$oldName, $userDir.$json_result[$i]['name'] );
                                $validator->resizePhoto($userDir.$json_result[$i]['name'], 800, 800);
                            } else {
                                $json_result[$i]['error'] = 'Please rename and check this file and try again.';
                                $json_result[$i]['url'] = ' - ';
                            }

                        } else {
                            if( file_exists( $userDir.$json_result[$i]['name'] ) ) {
                                unlink( $userDir.$json_result[$i]['name'] );
                            }
                            $json_result[$i]['error'] = $res['error'];
                            $json_result[$i]['url'] = ' - ';
                        }

                    }
                    echo json_encode( $json_result );
                }
                break;
            case 'DELETE':
                $upload_handler->delete();
                break;
            default:
                header( 'HTTP/1.1 405 Method Not Allowed' );
        }
    } else {
        header( 'HTTP/1.1 405 Method Not Allowed' );
    }

function json_last_error_string(){
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
            break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
            break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
            break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
            break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
        default:
            echo ' - Unknown error';
            break;
    }
}






