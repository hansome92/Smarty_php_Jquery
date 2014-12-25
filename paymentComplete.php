<?php
    /**
     * File: paymentComplete.php
     * Created: 12/12/12  4:12 PM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */

    require_once 'include.php';
    require_once( 'user.php' );
    set_include_path( get_include_path().PATH_SEPARATOR.'pp'.DIRECTORY_SEPARATOR.'lib' );
    require_once( 'services/PayPalAPIInterfaceService/PayPalAPIInterfaceServiceService.php' );
    $logger = new PPLoggingManager( 'SetExpressCheckout' );

    $user = new user();
    if( $user->currentUserIsPaid() || !( isset( $_SESSION['loggedIn'] ) && $_SESSION['loggedIn'] == TRUE ) ) {
        header( "Location: ".URL_PREFIX );
        exit;
    }

    if( !( isset( $_REQUEST['token'] ) || isset( $_REQUEST['Token'] ) ) || !( isset( $_REQUEST['PayerID'] ) || $_REQUEST['payerID'] ) ) {
        die( 'PAYPAL ERROR- IF YOU HAVE BEEN CHARGED, PLEASE CONTACT THE ADMIN: '.ADMIN_EMAIL );
    }

    $token = urlencode( ( isset( $_REQUEST['token'] ) ? $_REQUEST['token'] : $_REQUEST['Token'] ) );
    $payerId = urlencode( ( isset( $_REQUEST['PayerID'] ) ? $_REQUEST['PayerID'] : $_REQUEST['payerID'] ) );

    // Get information and status from PayPal about this order
    $getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType( $token );
    $getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();
    $getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;

    $paypalService = new PayPalAPIInterfaceServiceService();
    try {
        $getECResponse = $paypalService->GetExpressCheckoutDetails( $getExpressCheckoutReq );
        $ECResponse = $getECResponse->GetExpressCheckoutDetailsResponseDetails;
    } catch( Exception $ex ) {
        handlePaypalError( $ex );
        exit;
    }

    // Now build the system for actually finalizing the payment and storing it
    $userid = $ECResponse->Custom;
    $payerEmail = $ECResponse->PayerInfo->Payer;
    $payerId = $ECResponse->PayerInfo->PayerID;
    $fname = $ECResponse->PayerInfo->PayerName->FirstName;
    $lname = $ECResponse->PayerInfo->PayerName->LastName;
    $payerStatus = $ECResponse->PayerInfo->PayerStatus;
    $payerCountry = $ECResponse->PayerInfo->PayerCountry;

    $orderTotal = new BasicAmountType();
    $orderTotal->currencyID = $ECResponse->PaymentDetails[0]->OrderTotal->currencyID;
    $orderTotal->value = $ECResponse->PaymentDetails[0]->OrderTotal->value;

    $PaymentDetails = new PaymentDetailsType();
    $PaymentDetails->OrderTotal = $orderTotal;
    $PaymentDetails->NotifyURL = WEBSITE_URL.'pay.php';

    $DoECRequestDetails = new DoExpressCheckoutPaymentRequestDetailsType();
    $DoECRequestDetails->PayerID = $payerId;
    $DoECRequestDetails->Token = $token;
    $DoECRequestDetails->PaymentAction = $ECResponse->PaymentDetails[0]->PaymentAction;
    $DoECRequestDetails->PaymentDetails[0] = $PaymentDetails;

    $DoECRequest = new DoExpressCheckoutPaymentRequestType();
    $DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;

    $DoECReq = new DoExpressCheckoutPaymentReq();
    $DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;

    try {
        /* wrap API method calls on the service object with a try catch */
        $DoECResponse = $paypalService->DoExpressCheckoutPayment( $DoECReq );
    } catch( Exception $ex ) {
        handlePaypalError( $ex );
    }

    //handle a succesful response from paypal
    if( isset( $DoECResponse ) && strtolower( trim( $DoECResponse->Ack ) ) == 'success' && isset( $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo ) ) {
        $ECResponse = $DoECResponse->DoExpressCheckoutPaymentResponseDetails;
        mail( ADMIN_EMAIL, 'New Paypal payment with status: '.$ECResponse->PaymentInfo[0]->PaymentStatus, "UserId: {$userid}<br><br>\n\n".print_r( $DoECResponse, TRUE ) );

        if( !( $ECResponse->PaymentInfo[0]->PaymentStatus == 'Failed' || $ECResponse->PaymentInfo[0]->PaymentStatus == 'Reversed' ) ) {
            //make the user a paid user
            $user->setUserAsPaidAccount( $userid );
            //may as well store the order here
            $pinfo = $ECResponse->PaymentInfo[0];
            $txnid = $user->sql->escapeString( $pinfo->TransactionID );
            $userid = $user->sql->escapeString( $userid );
            $payerEmail = $user->sql->escapeString( $payerEmail );
            $fname = $user->sql->escapeString( $fname );
            $lname = $user->sql->escapeString( $lname );
            $payerStatus = $user->sql->escapeString( $payerStatus );
            $payerCountry = $user->sql->escapeString( $payerCountry );
            $payerID = $user->sql->escapeString( $payerId );
            $amount = $user->sql->escapeString( $pinfo->GrossAmount->value );
            $fee = $user->sql->escapeString( $pinfo->FeeAmount->value );
            $pendingReason = $user->sql->escapeString( $pinfo->PendingReason );
            //$fullResponse = $user->sql->escapeString( var_export( $DoECResponse, TRUE ) );
            $fullResponse = $user->sql->escapeString( json_encode( (array)$DoECResponse ) );
            $user->sql->query( $sql = "INSERT INTO orders(`txnid`,`token`, `userid`, `email`,`payerid`, `fname`, `lname`, `payerStatus`, `payerCountry`, `amount`, `fee`, `pendingreason`, `fullResponse`) VALUES
             ( '{$txnid}', '{$token}', '{$userid}', '{$payerEmail}','{$payerID}','{$fname}','{$lname}','{$payerStatus}','{$payerCountry}', {$amount}, {$fee}, '{$pendingReason}', '{$fullResponse}')" );

        }

    }

    header( "Location: ".WEBSITE_URL.'profile/' );


    function handlePaypalError( $ex ) {
        if( isset( $ex ) && !is_null( $ex ) ) {
            $ex_detailed_message = '';
            $ex_message = $ex->getMessage();
            $ex_type = get_class( $ex );
            if( $ex instanceof PPConnectionException ) {
                $ex_detailed_message = "Error connecting to: ".$ex->getUrl();
            } else if( $ex instanceof PPMissingCredentialException || $ex instanceof PPInvalidCredentialException ) {
                $ex_detailed_message = $ex->errorMessage();
            } else if( $ex instanceof PPConfigurationException ) {
                $ex_detailed_message = "Invalid configuration. ";
            }
            mail( ADMIN_EMAIL, 'PAYPAL PAYMENT PROCESSING ERROR', $ex_detailed_message."<br><br> Error Class: {$ex_type} <br><br> Error Message: {$ex_message}" );
            exit;
        }
    }


