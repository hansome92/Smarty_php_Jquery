<?php
    /**
     * File: scrapshare.php
     * Created: 10/23/12  8:38 PM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */


    if( isset( $_POST['action'] ) ) {
        switch( $_POST['action'] ) {
            case 'share':
                if( isset( $_POST['scrapbooks'] ) && isset( $_POST['contacts'] ) ) {
                    require_once 'pendingEmail.php';
                    $email = new pendingEmail();
                    $email->addEmail( $_POST['contacts'], $_POST['scrapbooks'] );
                }
                //die( var_export( $_POST['scrapbooks'], TRUE ).'Reached end of share case.' );
                //die( '<br><br><br>DEBUG: Reached end of share case.' );
                break;
            case 'addContact':
                $failMessage = array( 'error' => 1, 'message' => "Please enter a valid email and name for the contact. " );
                if( isset( $_POST['name'] ) && strlen( $_POST['name'] ) > 1 && isset( $_POST['email'] ) && strlen( $_POST['email'] ) > 5 ) {
                    require_once 'user.php';
                    $user = new user();
                    $id = $user->addCurrentUserContact( $_POST['name'], $_POST['email'] );
                    if( $id > 0 ) {
                        die( json_encode( array( 'error' => 0, 'contactid' => $id ) ) );
                    }
                    if( $id == -2 ) {
                        $failMessage['message'] = 'You have already added this email to your contacts. ';
                    }
                }
                die( json_encode( $failMessage ) );
                break;
            case 'delete':
                $failMessage = "Could not delete contact. ";
                if( isset( $_POST['contactid'] ) && strlen( $_POST['contactid'] ) > 0 ) {
                    require_once 'user.php';
                    $user = new user();
                    $del = $user->deleteCurrentUserContact( $_POST['contactid'] );
                    if( $del > 0 ) {
                        $failMessage = 'Contact deleted successfully. ';
                    }
                }
                die( $failMessage );
                break;
            default:
                die( 'Not a valid action.' );
                break;
        }
    } else {
        die( 'Invalid commands or options.' );
    }
