<?php

    include '../include.php';
    include '../login/facebook.php';

    $facebook = new Facebook( array( 'appId'  => FACEBOOK_APPID,
                                     'secret' => FACEBOOK_APPSECRET
                              ) );

    $userid = $facebook->getUser();

    if( $userid ) {

        $albumQuery = ' SELECT aid, object_id, name, description, link, cover_object_id, photo_count FROM album WHERE owner=me() and photo_count > 0  and type !=\'wall\' and type !=\'friends_walls\'  ';
        $albumCoversQuery = ' SELECT album_object_id, src, src_width, src_height FROM photo WHERE object_id IN (SELECT cover_object_id FROM #albums) ';

        $albumMultiquery = array( 'albums' => $albumQuery, 'albumCovers' => $albumCoversQuery );

        $albumMultiqueryResults = $facebook->api( array(
                                                       'method'  => 'fql.multiquery',
                                                       'queries' => json_encode( $albumMultiquery )
                                                  ) );

        //make sure the results are what we think they are, and assign albums/albumcovers vars
        if( $albumMultiqueryResults[0]['name'] == 'albums' ) {
            $albums = $albumMultiqueryResults[0]['fql_result_set'];
            $albumCovers = $albumMultiqueryResults[1]['fql_result_set'];
        } else {
            $albumCovers = $albumMultiqueryResults[0]['fql_result_set'];
            $albums = $albumMultiqueryResults[1]['fql_result_set'];
        }

        // merging the two arrays returned from fb without breaking things
        $mergedAlbumArray = Array();
        Foreach( $albums as $album ) {
            //none of the album object id's should be set yet, and there should be no duplicates
            $mergedAlbumArray[$album['object_id']] = Array();
            $mergedAlbumArray[$album['object_id']]['album'] = $album;
        }
        Foreach( $albumCovers as $albumCover ) {
            //#TODO: is this check necessary? --> if( isset( $mergedAlbumArray[ $albumCover['album_object_id'] ] ) )
            $mergedAlbumArray[$albumCover['album_object_id']]['coverPhoto'] = $albumCover;
        }

        ?>

<html>
    <head>
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
        <title>Facebook Page Album</title>

        <!--    jQuery and Plugins    -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
        <script src="<?=URL_PREFIX?>js/jquery.bpopup-0.7.0.min.js" type="text/javascript"></script>

        <!--    modal plugin testing    -->
        <script src="<?=URL_PREFIX?>fbpics/fancybox/jquery.fancybox.js" type="text/javascript"></script>
        <link href="<?=URL_PREFIX?>fbpics/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" charset="utf-8">

        <!--    FacebookPics JS and CSS    -->
        <script src="fbpics.js" type="text/javascript"></script>
        <link href="fbpics.css" rel="stylesheet" type="text/css" charset="utf-8">

        <style type='text/css'>

        </style>

        <script type="text/javascript">
            var url_prefix = '<?=URL_PREFIX?>';
        </script>

    </head>

<body>

    <div id="modalHolder" style=""></div>

    <div id="fbWrapper">

        <div id="g-content" class="yui-g ib">
            <div class="ib topExplanation" style="">
                Click on an album to select photos to import
            </div>
            <br>

            <div class="ib topExplanation" style="font-size:18px;margin-top:5px;">
                <a href='#' onclick='parent.$.colorbox.close(); return false;'>Click here when finished</a>
            </div>

            <ul id="g-album-grid" class="ui-helper-clearfix">

                <?php

                foreach( $mergedAlbumArray as $objectId => $album ) {
                    /* START OF THE code to loop through below stuff to create LI */
                    ?>

                    <li id="<?=$objectId?>" class="g-item g-album fbAlbum" style="height: 200px; position: relative; top: 0px; left: 0px; ">
                        <div class="g-valign" style="margin-top: 2.5px; ">
                            <a href="<?=$album['album']['link']?>" target="_blank">
                                <img class="g-thumbnail" src="<?=$album['coverPhoto']['src']?>" alt="<?=$album['album']['name']?>" width="<?=$album['coverPhoto']['src_width']?>"
                                     height="<?=$album['coverPhoto']['src_height']?>">
                            </a>

                            <h2>
                                <a href="<?=$album['album']['link']?>" target="_blank"><?=$album['album']['name']?></a>
                            </h2>
                            <ul class="g-metadata">
                                <?php
                                /*
                                if( strlen($album['album']['description']) > 0 ) {
                                    if( strlen($album['album']['description']) > 50 ) {
                                        echo '<li>Description: '.substr($album['album']['description'], 0, 49).'...</li>';
                                    } else {
                                        echo '<li>Description: '.$album['album']['description'].'</li>';
                                    }
                                }
                                */
                                echo '<li>Photos: '.$album['album']['photo_count'].'</li>';
                                ?>
                            </ul>
                        </div>
                    </li>

                    <?php
                    /* END OF THE code to loop through below stuff to create LI */
                    // var_dump($mergedAlbumArray);
                }

                ?>

            </ul>

        </div>

        <?php
    } else {
        $_SESSION['redirectingForPhotos'] = TRUE;
        // var_dump( $facebook );
        // var_dump( $_SESSION );
        // die('YOU FOUND THE ERROR!');
        header( 'Location: '.WEBSITE_URL.'fblogin.php' );
    }
?>
    </div>
        <div class="modalPleaseWait"></div>
    <script type="text/javascript">

        alert( url_prefix );

        function getSelectedImages() {
            /*
            var checkboxList = $('input:checkbox[name=selectedimage]:checked');
            $.each(checkboxList, function(key, data){   imgList[key] = $(data).val();   });
            */
            var checkboxList = $( 'li.selectedPictures' );
            var imgList = [];
            $.each( checkboxList, function( key, data ) {
                imgList[key] = data.id;
            } );

            $.ajax( {
                        type: 'POST',
                        url: url_prefix + 'fbpics/storeSelectedPics.php',
                        data: {'pictures': imgList},
                        success: function( data ) {
                            $.fancybox.close();
                        }
                    } );
        }
    </script>
</body>
</html>