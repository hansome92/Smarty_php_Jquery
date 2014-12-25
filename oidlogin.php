<?php
    require_once 'include.php';
    require_once 'login/openid.php';
    require_once 'login/login.php';

    if( isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == TRUE ) {
        header('Location: '.URL_PREFIX);
    } else {
        try {
            $openid = new LightOpenID(WEBSITE_DOMAIN);
            if( !$openid->mode ) {
                if( isset($_POST['openid_identifier']) ) {
                    $openid->identity = $_POST['openid_identifier'];
                    $_SESSION['authSource'] = $_POST['openid_identifier'];
                    // The following two lines request email, full name, and a nickname from the provider.
                    $openid->required = array( 'contact/email', 'namePerson/friendly', 'namePerson', 'namePerson/first', 'namePerson/last' );
                    $openid->optional = array( 'pref/timezone' );
                    header('Location: '.$openid->authUrl());
                } else {
                    unset($_SESSION['authSource']);
                    die("NO OPENID PROVIDER SELECTED");
                }
            } elseif( $openid->mode == 'cancel' ) {
                unset($_SESSION['authSource']);
                header('Location: '.URL_PREFIX); // echo 'User has canceled authentication!';
            } else {
                if( $openid->validate() ) {
                    $user = $openid->getAttributes();
                    if( !isset($user["contact/email"]) ) {
                        unset($_SESSION['authSource']);
                        die('Could not get contact information/email from openid provider.');
                    }
                    $user['authSource'] = $_SESSION['authSource'];
                    $login = new login($user, 'oid');
                } else {
                    unset($_SESSION['authSource']);
                }
                //echo 'User '.($openid->validate() ? $openid->identity.' has ' : 'has not ').'logged in.';
                header('Location: '.URL_PREFIX);
            }
        }
        catch( ErrorException $e ) {
            echo $e->getMessage();
        }
    }
