<?php
    /**
     * File: getPicsFromAlbum.php
     * Created: 6/16/12  3:15 AM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */

    include '../include.php';
    include '../login/facebook.php';
    $facebook = new Facebook( array( 'appId'     => FACEBOOK_APPID,
                                     'secret'    => FACEBOOK_APPSECRET
                              ) );
    if( !( is_null( $facebook->getUser() ) || $facebook->getUser() == 0 ) ) {
        if( isset( $_POST['album'] ) && is_string( $_POST['album'] ) ) {

            $pictureQuery = ' select object_id, images from photo where album_object_id = '.$_POST['album'];
            $pictureQueryResults = $facebook->api( array(
                                                        'method'  => 'fql.query',
                                                        'query'   => $pictureQuery
                                                   ) );

            $pictures = Array();
            Foreach( $pictureQueryResults as $pictureArray ) {
                $mediumPicture = Array( 'height'=> 0,
                                        'width' => 0
                );
                $smallPicture = Array( 'height'=> 1000,
                                       'width' => 1000
                );
                Foreach( $pictureArray['images'] as $picture ) {
                    if( $picture['height'] < 400 && $picture['width'] < 400 ) {
                        //find biggest picture < 400pxx400px
                        if( $picture['height'] > $mediumPicture['height'] || $picture['width'] > $mediumPicture['width'] ) {
                            $mediumPicture = $picture;
                        }
                        //find smallest picture
                        if( $smallPicture['height'] > $picture['height'] || $smallPicture['width'] > $picture['width'] ) {
                            $smallPicture = $picture;
                        }
                    }
                }

                $pictures[$pictureArray['object_id']] = Array(
                    'medium'=> $mediumPicture,
                    'small' => $smallPicture
                );
            }


            ?>
        <div id="fbWrapper" style="width:860px;">
            <div id="g-content" class="yui-g">
                <div class="ib">
                    Click on pictures to select them, then click below to
                    <br>

                    <div id="selectPicturesForImport" onclick="getSelectedImages();" class="ib makeFacebookSelection">
                        Import the pictures into Trinketlily!
                    </div>
                </div>

                <ul id="g-album-grid" class="ui-helper-clearfix">

                    <?php

                    foreach( $pictures as $objectId => $pic ) {
                        /* START OF THE code to loop through below stuff to create LI */
                        $imgStyle = ( $pic['medium']['width'] > $pic['medium']['height'] ? 'wideFbPic' : 'tallFbPic' );
                        ?>

                        <li id="<?=$objectId?>" class="g-item g-album fbPic" style="position: relative; top: 0px; left: 0px; ">
                            <div class="g-valign" style="margin-top: 2.5px; ">
                                <p><img id="img_<?=$objectId?>" class="g-thumbnail <?=$imgStyle?>" src="<?=$pic['medium']['source']?>" alt="photo "></p>
                                <!-- width="<?=$pic['medium']['width']?>" height="<?=$pic['medium']['height']?>">    -->
                                <!-- p><input type="checkbox" name="selectedimage" value="<?=$objectId?>" /></p -->

                            </div>
                        </li>

                        <?php
                        /* END OF THE code to loop through below stuff to create LI */
                        // var_dump($mergedAlbumArray);
                    }

                    ?>

                </ul>

            </div>
        </div>
        <script type="text/javascript">
            $( '.fancybox-inner' ).find( '.fbPic' ).each( function() {
                $( this ).click( function( e ) {
                    e.preventDefault();
                    var chkbx = $( this ).find( 'input' );
                    chkbx.attr( "checked", !chkbx.attr( "checked" ) );
                } );
            } );
        </script>
        <?php
        } else {
            die( json_encode( "NO ALBUM SELECTED" ) );
        }
    } else {
        header( 'Location: '.WEBSITE_URL.'fblogin.php' );
    }
?>