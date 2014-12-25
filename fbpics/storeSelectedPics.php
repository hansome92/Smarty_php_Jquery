<?php
    /**
     * File: storeSelectedPics.php
     * Created: 6/16/12  6:47 AM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */
    /*
        require_once '../include.php';
        require_once '../login/facebook.php';
        $facebook = new Facebook(array( 'appId'     => FACEBOOK_APPID,
                                        'secret'    => FACEBOOK_APPSECRET
                                 ));
        if( !(is_null($facebook->getUser()) || $facebook->getUser() == 0) ) {
            if( isset($_POST['pictures']) && is_array($_POST['pictures']) ) {

                $pictureQuery = ' select object_id, images from photo where object_id in ('.implode(', ', $_POST['pictures']).')';
                $pictureQueryResults = $facebook->api(array(
                                                           'method'  => 'fql.query',
                                                           'query'   => $pictureQuery
                                                      ));

                require_once '../photoAlbum.php';
                $pa = new photoAlbum();
                $fbAlbumId = $pa->getFacebookAlbumId($pa->getCurrentUsersId());
                $imageIds = Array();

                foreach( $pictureQueryResults as $pictureArray ) {

                    if( !$pa->objectIdExists($pictureArray['object_id']) ) {

                        $sizes = Array();
                        foreach( $pictureArray['images'] as $picture ) {
                            if( !isset($sizes[$picture['width']]) ) {

                                $sizes[$picture['width']] = $picture['height'];

                                $pictureAttributes = Array(
                                    'object_id' => $pictureArray['object_id'],
                                    'title'     => '',
                                    'tags'      => Array(),
                                    'source'    => $picture['source'],
                                    'height'    => $picture['height'],
                                    'width'     => $picture['width']
                                );

                                $imageId = $pa->savePhotoFromUrl($picture['source'], $pictureAttributes);
                                $res = $pa->addPhoto($imageId->{'$id'}, $fbAlbumId, $picture['height'], $picture['width'], $pictureArray['object_id']);
                                $imageResults[$imageId->{'$id'}]['sql'] = $res;
                            }
                        }
                    }
                }

                $res = TRUE;

                if( $res ) {
                    die("success: ".var_export($imageResults));
                    die("Successfully saved the pictures!");
                } else {
                    die(json_encode($pictureQueryResults));
                }
            } else {
                die(json_encode("NO ALBUM SELECTED"));
            }
        } else {
            header('Location: '.FACEBOOK_POSTAUTH_REDIRECTTO);
        }
    */
    require_once '../include.php';
    require_once '../login/facebook.php';
    $facebook = new Facebook( array( 'appId'     => FACEBOOK_APPID,
                                     'secret'    => FACEBOOK_APPSECRET
                              ) );
    if( !( is_null( $facebook->getUser() ) || $facebook->getUser() == 0 ) ) {
        //#TODO: ADD CHECK TO MAKE SURE USER IS LOGGED IN!
        $uid = $_SESSION['id'];

        // check user directory
        $userDir = realpath( dirname( __FILE__ ) ).'/../userpics/'.$uid;
        if( !is_dir( realpath( dirname( __FILE__ ) ).'/../userpics/'.$uid ) ) {
            // if not exists - create it
            mkdir( realpath( dirname( __FILE__ ) ).'/../userpics/'.$uid, 0777 );
        }

        $pictures = isset( $_POST['pictures'] ) ? cleanAlphaNum( $_POST['pictures'] ) : array();
        // create the query to get the full url's for them from fb
        if( $pictures ) {
            $pictureQuery = ' select object_id, images from photo where object_id in ('.join( ', ', $pictures ).')';

            $pictureQueryResults = $facebook->api( array(
                                                        'method'  => 'fql.query',
                                                        'query'   => $pictureQuery
                                                   ) );
            require_once '../pendingDownload.php';
            $pd = new PendingDownload();
            $x = 0;
            foreach( $pictureQueryResults as $k=> $img ) {
                $x++;
                $pd->addUrl( $uid, $img['images'][0]['source'], $img['images'][0]['height'], $img['images'][0]['width'] );
            }
            $success = 'Successfully added '.$x.' picture'.( $x != 1 ? 's' : '' ).'. ';
            $success = $success."\n\n".'It may take up to 5 minutes to finish importing them from Facebook, so don\'t panic if you can\'t see them quite yet!';
            die( ( $success ) );
        } else {
            die( "You haven't selected any pictures to add!" );
        }
    }
    die( " Danger, Will Robinson! " );

?>
