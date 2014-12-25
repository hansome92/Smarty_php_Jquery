<?php

    require_once( 'pp/ipnlistener.php' );
    require_once( 'include.php' );

    /**
     * File: pay.php
     * Created: 10/25/12  8:11 PM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */

    if( !isset( $_POST['payment_status'] ) || !isset( $_POST['mc_currency'] ) || !isset( $_POST['receiver_email'] ) || !isset( $_POST['custom'] ) || !isset( $_POST['txn_id'] ) ) {
        die();
    }

    $listener = new IpnListener();
    $listener->force_ssl_v3 = FALSE;
    $listener->use_sandbox = TRUE;

    $verified = NULL;
    try {
        $verified = $listener->processIpn();
    } catch( Exception $e ) {
        switch( $e->getCode() ) {
            case 1:
                echo 'There was an error processing your request, please contact the administrator to get credited if you have been billed: '.ADMIN_EMAIL;
                break;

        }
        // fatal error trying to process IPN.
        // echo $e->xdebug_message;
        echo debug( $listener->getTextReport() );
        echo debug( $e->getMessage() );
        echo debug( $e->xdebug_message );
        echo debug( $e );
        exit( 0 );
    }

    mail( ADMIN_EMAIL, 'Paypal IPN Update:', $listener->getTextReport() );
    exit( 0 );

    if( $verified ) {

        $errmsg = ''; // stores errors from fraud checks

        // 1. Make sure the payment status is "Completed"
        if( $_POST['payment_status'] != 'Completed' ) {
            // simply ignore any IPN that is not completed
            exit( 0 );
        }

        // 2. Make sure seller email matches your primary account email.
        if( $_POST['receiver_email'] != PAYPAL_EMAIL ) {
            $errmsg .= "'receiver_email' does not match: ";
            $errmsg .= $_POST['receiver_email']."\n";
        }

        // 4. Make sure the currency code matches
        if( $_POST['mc_currency'] != 'USD' ) {
            $errmsg .= "'mc_currency' does not match: ";
            $errmsg .= $_POST['mc_currency']."\n";
        }


        require_once( 'user.php' );
        $user = new user();
        // 5. make sure the userid is a plausible one
        $userid = isset( $_POST['custom'] ) ? $_POST['custom'] : 'NULL';
        if( !$user->validUserId( $userid ) ) {
            $errmsg .= "'userid' does not seem to be valid: ";
            $errmsg .= $userid."\n";
        }


        $txnid = $user->sql->escapeString( $_POST['txn_id'] );
        $res = $user->sql->query( "SELECT * FROM orders WHERE txnid = '{$txnid}'" );
        if( $res->num_rows > 0 ) {
            $errmsg .= "'txn_id' has already been processed: ".$_POST['txn_id']."\n";
        }

        //Check for duplicate txn_id
        if( !empty( $errmsg ) ) {
            // manually investigate errors from the fraud checking
            $body = "IPN failed fraud checks: \n$errmsg\n\n";
            $body .= $listener->getTextReport();
            mail( ADMIN_EMAIL, 'IPN Fraud Warning', $body );
        } else {
            //store the order information
            $payerEmail = $user->sql->escapeString( $_POST['payer_email'] );
            $amount = $user->sql->escapeString( $_POST['mc_gross'] );
            $useridC = $user->sql->escapeString( $userid );
            $user->sql->query( $sql = "INSERT INTO orders(`txnid`, `userid`, `email`, `amount`) VALUES ( '{$txnid}', '{$useridC}', '{$payerEmail}', {$amount})" );
            //make the user a paid user
            $user->setUserAsPaidAccount( $userid );
        }


    } else {
        // manually investigate the invalid IPN
        mail( ADMIN_EMAIL, 'Invalid IPN', $listener->getTextReport() );
    }

?>