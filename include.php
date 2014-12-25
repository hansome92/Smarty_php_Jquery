<?php

    //start of timer
    DEFINE( 'START_TIME', microtime( TRUE ) );

    // Code for to workaround the Flash Player Session Cookie bug
    if( isset( $_POST["PHPSESSID"] ) ) {
        session_id( $_POST["PHPSESSID"] );
    } else if( isset( $_GET["PHPSESSID"] ) ) {
        session_id( $_GET["PHPSESSID"] );
    }
    session_start();


    /***  DEBUG/TEST CACHE BUSTING  ***/
    $cacheBustVar = 'cb'.rand( 100, 1000000 ).'='.rand( 100, 1000000 );
    $cacheBust = '?'.$cacheBustVar;

    /***  DEBUG/TEST SERVER SETTINGS  ***/
    require 'configuration.php';


    global $mysqlconnection;
    global $mongodbconnection;


    /***  FUNCTIONS  ***/

    function addPageView( $db ) {
        $pageViewCollection = new MongoCollection( $db, "pageViews" );
        $pageViewToAdd = Array( "page" => $_SERVER['SCRIPT_NAME'],
                                "ip"   => $_SERVER['REMOTE_ADDR'],
                                "time" => timestamp()
        );
        if( isset( $_SESSION['openid'] ) ) {
            $pageViewToAdd["user"] = $_SESSION['openid']['contact/email'];
        }
        $pageView[] = $pageViewToAdd;

        $pageViewCollection->insert( $pageView );
    }

    function timestamp() {
        $now = new DateTime();
        return $now->format( 'Y-m-d H:i:s' );
    }

    function sanitize( $string, $useSqlStringClean = FALSE ) {
        if( is_array( $string ) ) {
            $clean = Array();
            foreach( $string as $i => $k ) {
                $clean[$i] = sanitize( $k, $useSqlStringClean );
            }
            return $clean;
        } elseif( is_null( $string ) ) {
            return NULL;
        } elseif( !is_string( $string ) && !is_numeric( $string ) ) {
            return NULL;
        }
        if( !$useSqlStringClean ) {
            return htmlspecialchars( $string, ENT_QUOTES );
        } else {
            global $mysqlconnection;
            return $mysqlconnection->real_escape_string( $string );
        }
    }

    function cleanAlphaNum( $string, $allowPeriod = FALSE ) {

        if( is_array( $string ) ) {
            $clean = Array();
            foreach( $string as $i => $k ) {
                $clean[$i] = cleanAlphaNum( $k );
            }
            return $clean;
        } elseif( is_null( $string ) ) {
            return NULL;
        } elseif( is_double( $string ) ) {
            $allowPeriod = TRUE;
        } elseif( !is_string( $string ) && !is_numeric( $string ) ) {
            return NULL;
        }
        $regex = '/[^a-zA-Z0-9]/';
        if( $allowPeriod ) {
            $regex = '/[^a-zA-Z0-9\.]/';
        }
        return preg_replace( $regex, '', $string );
    }

    function runtime() {
        return microtime( TRUE ) - START_TIME;
    }

    function mySerialize( $obj ) {
        return base64_encode( gzcompress( serialize( $obj ) ) );
    }

    function myUnserialize( $txt ) {
        return unserialize( gzuncompress( base64_decode( $txt ) ) );
    }

    function debug( $message ) {
        if( !( is_string( $message ) || is_int( $message ) || is_integer( $message ) ) ) {
            $message = var_export( $message, TRUE );
        }
        echo '<br>'.$message.'</br>';
    }

    function encrypt( $string ) {
        $keymd5 = md5( SCRAPBOOK_SHARE_KEY );
        return base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, $keymd5, $string.SCRAPBOOK_SHARE_SALT, MCRYPT_MODE_CBC, md5( $keymd5 ) ) );
    }

    function decrypt( $encrypted_string ) {
        $keymd5 = md5( SCRAPBOOK_SHARE_KEY );
        return str_replace( SCRAPBOOK_SHARE_SALT, '', rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $keymd5, base64_decode( $encrypted_string ), MCRYPT_MODE_CBC, md5( $keymd5 ) ), "\0" ) );
    }

    /**@return String|null */
    function getCurrentUsersId() {
        if( !isset( $_SESSION['loggedIn'] ) || $_SESSION['loggedIn'] != TRUE || !isset( $_SESSION['id'] ) || is_null( $_SESSION['id'] ) || $_SESSION['id'] == '' ) {
            return NULL;
        }
        if( is_string( $_SESSION['id'] ) && strlen( $_SESSION['id'] ) > 10 ) {
            return $_SESSION['id'];
        } elseif( strlen( $_SESSION['id']->{'$id'} ) > 10 ) {
            return $_SESSION['id']->{'$id'};
        }
        return NULL;
    }


?>