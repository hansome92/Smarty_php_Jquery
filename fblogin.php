<?php
    require_once 'include.php';
    require_once 'login/facebook.php';
    require_once 'login/login.php';


    $facebook = new Facebook( array( 'appId' => FACEBOOK_APPID, 'secret' => FACEBOOK_APPSECRET ) );

    // Get User ID
    $userid = $facebook->getUser();
    // We may or may not have this data based on whether the user is logged in.
    // If we have a $user id here, it means we know the user is logged into Facebook, but we don't know if the access token is valid. An access token is invalid if the user logged out of Facebook.
    if( $userid ) {
        try {
            // Proceed knowing you have a logged in user who's authenticated.
            //die( var_dump( $user ) );
            if( isset( $_SESSION['loggedIn'] ) && $_SESSION['loggedIn'] && isset( $_SESSION['redirectingForPhotos'] ) && $_SESSION['redirectingForPhotos'] ) {
                //are we actually logged in and just going through the fb thing just to be able to pull in pics from it?
                $_SESSION['redirectingForPhotos'] = FALSE;
                header( 'Location: '.WEBSITE_URL.'fbpics/fbpics.php' );
            } else {
                //log the user in and redirect to profile page
                $user = $facebook->api( '/me', 'GET' );
                $login = new login( $user, 'facebook' );
                header( 'Location: '.WEBSITE_URL.'profile/' );
            }

        } catch( FacebookApiException $e ) {
            error_log( $e );
            $_SESSION['loggedIn'] = FALSE;
            error_log( $e->getType() );
            error_log( $e->getMessage() );
            $user = NULL;
        }
    } else {
        //we're not logged in
        $authUrl = $facebook->getLoginUrl( array( 'scope' => 'email,user_photos,friends_photos', 'redirect_uri' => WEBSITE_URL.'fblogin.php' ) );
        header( "Location: {$authUrl}" );
    }




?>