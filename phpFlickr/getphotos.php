<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: atlete
     * Date: 10.08.12
     * Time: 15:55
     */
    function getNSID( $nsidurl ) {
        $nsiddata = substr( $nsidurl, 0, strlen( $nsidurl ) - 1 );
        $nsid = "";
        for( $i = strlen( $nsiddata ) - 1; $i > 0; $i-- ) {
            if( $nsiddata[$i] == '/' ) {
                break;
            } else {
                $nsid .= $nsiddata[$i];
            }

        }
        return strrev( $nsid );
    }

    include_once '../include.php';
    include_once( "phpFlickr.php" );
    $f = new phpFlickr( FLICKR_KEY, FLICKR_SECRET );
    $f->auth( "read" );
    $nsidurl = $f->urls_getUserProfile();
    $nsid = getNSID( $nsidurl );
    $photoList = $f->people_getPhotos( $nsid );
?>
<html>
    <head>
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
        <title>Flickr Albums</title>

        <!--    jQuery and Plugins    -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
        <script src="<?=URL_PREFIX?>js/jquery.bpopup-0.7.0.min.js" type="text/javascript"></script>

        <!--    modal plugin testing    -->
        <script src="<?=URL_PREFIX?>/fbpics/fancybox/jquery.fancybox.js" type="text/javascript"></script>
        <link href="<?=URL_PREFIX?>/fbpics/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" charset="utf-8">

        <!--    FacebookPics JS and CSS    -->
        <script src="<?=URL_PREFIX?>/fbpics/fbpics.js" type="text/javascript"></script>
        <link href="<?=URL_PREFIX?>/fbpics/fbpics.css" rel="stylesheet" type="text/css" charset="utf-8">

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
                <input type="button" class="ib makeFacebookSelection" style="text-align:center;" onclick="getSelectedImages();" value="Import from Flickr" />
                <ul id="g-album-grid" class="ui-helper-clearfix" style="list-style: none;">
                    <?php foreach( $photoList['photos']['photo'] as $k=> $photo ):
                    $cphoto = $f->photos_getSizes( $photo['id'] );
                    ?>
                    <li>
                        <img src="<?php echo $cphoto[0]['source']?>" alt=""><br>
                        <input name="selectedimage" type="checkbox" value="<?php echo $photo['id']?>">
                    </li>
                    <?php endforeach;?>
                </ul>
                <div class="ib" STYLE="margin-top:30px;font-size:20px;font-weight:bold;"><a href="http://www.upgradeyourdeveloper.com/elance/trinketlily/album/">Back To Editor</a></div>
            </div>
        </div>
        <script type="text/javascript">
            function getSelectedImages() {
                var imgList = [];

                var checkboxList = $( 'input:checkbox[name=selectedimage]:checked' );
                //var checkboxList = $( 'li.selectedPictures' );
                $.each( checkboxList, function( key, data ) {
                    //imgList[key] = $('#img_'+$(data).val()).attr('src');
                    imgList[key] = $( data ).val();
                } );
                /*
                                $.each( checkboxList, function( key, data ) {
                                    imgList[key] = data.id;
                                } );
                */
                $.ajax( {
                            type:    'POST',
                            url: url_prefix + 'phpFlickr/storeSelectedImages.php',
                            data:    {'pictures': imgList},
                            success: function( data ) {
                                var res = $.parseJSON( data );
                                alert( res['error'] == 0 ? 'Success!' : 'Error!' );
                            }
                        } );

            }
        </script>
    </body>
</html>
