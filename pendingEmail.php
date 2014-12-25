<?php
    /**
     * File: pendingEmail.php
     * Created: 10/24/12  11:33 AM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */
    require_once 'book.php';

    class pendingEmail extends book {

        function __construct() {
            parent::__construct();
        }

        function addEmail( $contacts, $scrapbooks ) {
            $shareJson = json_encode( array( 'contacts' => cleanAlphaNum( $contacts ), 'scrapbooks' => cleanAlphaNum( $scrapbooks ) ) );
            $shareJson = $this->sql->escapeString( $shareJson );
            $this->sql->query( "INSERT INTO `shares`(`json`) VALUES('{$shareJson}')" );
            $this->sql->commit();
        }

        function getPendingList( $limit = 10 ) {
            $res = $this->sql->query( "SELECT * FROM `shares` WHERE `sent`=0 LIMIT 0,{$limit}" );
            return $this->sql->sqlToArray( $res );
        }

        function removePendingElement( $pending_id ) {
            $pending_id = cleanAlphaNum( $pending_id );
            $this->sql->query( "UPDATE `shares` SET `sent` = 1 WHERE `shareid` = {$pending_id}" );
            $this->sql->commit();
        }

        function createEmails( $num = 10 ) {
            $emails = $this->getPendingList( $num );
            foreach( $emails as $email ) {
                $this->createEmail( $email['json'], $email['shareid'] );
            }
        }

        function createEmail( $emailJson, $shareid = NULL ) {
            $emailArr = json_decode( $emailJson, TRUE );
            $owner = $this->usersCollection->findOne( array( 'scrapbooks.id' => $emailArr['scrapbooks'][0] ) );
            if( !is_null( $owner ) && sizeof( $owner ) > 0 ) {
                $name = $owner['authentications']['login']['displayName'];
                $booknames = array();
                foreach( $owner['scrapbooks'] as $book ) {
                    $booknames[$book['id']] = $book['name'];
                }
                //var_dump( $owner );
                //#TODO: FORMAT AND CONTENT FOR THE EMAIL, MAKE IT LOOK NICE!
                $emailBody = "Your friend {$name} has shared some scrapbooks with you, you can check them out here: <ul> ";
                foreach( $emailArr['scrapbooks'] as $bookid ) {
                    $link = WEBSITE_URL.'editor/view.php?bookId='.$bookid.'&s='.rawurlencode( encrypt( $bookid ) );
                    $bookname = $booknames[$bookid];
                    $emailBody = $emailBody."<li> <a href='{$link}'>{$bookname}</a> </li> ";
                }
                $emailBody = $emailBody."</ul> <br><br> Sincerely,<br>Your friends at <a href='".WEBSITE_URL."'>TrinketLily</a> ";

                $this->sendEmails( $emailBody, "Your friend {$name} has shared some scrapbooks with you!", $emailArr['contacts'], $shareid );
            }
        }

        function sendEmails( $emailBody, $emailSubject, $contactIds, $shareid = NULL ) {
            $contacts = $this->getContactsByIds( $contactIds );
            foreach( $contacts as $contact ) {
                $this->sendEmail( $emailBody, $emailSubject, $contact['email'], $shareid );
            }
        }

        function sendEmail( $emailBody, $emailSubject, $emailAddress, $shareid = NULL ) {
            // die( debug( $emailBody.' SENT TO : '.$emailAddress ) );
            $headers = 'From: noreply@trinketlily'."\r\n".
                'Reply-To: noreply@trinketlily'."\r\n".
                'MIME-Version: 1.0'."\r\n".
                'Content-type: text/html; charset=iso-8859-1'."\r\n";
            $ret = mail( $emailAddress, $emailSubject, $emailBody, $headers );
            if( $ret == '' || $ret ) {
                if( !is_null( $shareid ) && is_numeric( $shareid ) ) {
                    $this->removePendingElement( $shareid );
                }
                //#todo: FINISH THE PENDINGEMAIL CLASS!
                echo( "Your scrapbooks have been successfully sent!" );
            } else {
                echo( "There was a problem sharing your scrapbooks!" );
            }
        }

        function getContactsByIds( $contactIds ) {
            $contacts = $this->sql->implodeArrayToSql( $contactIds );
            $contactRes = $this->sql->query( "SELECT * FROM `contacts` where `contactid` IN ({$contacts})" );
            return $this->sql->sqlToArray( $contactRes );
        }

    }

    $em = new pendingEmail();
    $em->createEmails( 1 );


