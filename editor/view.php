<!DOCTYPE html>
<html>
    <head>
        <?php
        $notDirect = true;
        include 'head.php';
        ?>
        <style type="text/css">
            body,html{
                margin:0;
                padding:0;
                width:1100px;
                height:600px;
                overflow: hidden;
                border-radius: 20px;
            }
            #book {
                width: 800px;
                height: 610px;
                margin: 10px 40px;
            }


            #book .cover {
                background: #333;
            }

            #book .cover h1 {
                color: white;
                text-align: center;
                font-size: 50px;
                line-height: 500px;
                margin: 0;
            }

            #book .loader {
                background-image: url(images/loading.gif);
                width: 24px;
                height: 24px;
                display: block;
                position: absolute;
                top: 238px;
                left: 188px;
            }

            #book .data {
                text-align: center;
                font-size: 40px;
                color: #999;
                line-height: 500px;
            }

            #controls {
                width: 800px;
                text-align: center;
                margin: 20px 0px;
                font: 30px arial;
            }

            #controls input, #controls label {
                font: 30px arial;
            }


        </style>

        <script src="js/turn.js<?=$cacheBust?>"></script>
        <script src="js/view.js<?=$cacheBust?>"></script>

    </head>

    <body>
        <?php
        $bookId = isset( $_GET['bookId'] ) ? cleanAlphaNum( $_GET['bookId'] ) : '';

        if( $access->currentUserCanViewBook( $bookId ) ) {
            $user = new user();
            $userId = $user->getCurrentUsersId();
            ?>
            <input type="hidden" id="user_id" value="<?= $userId; ?>" />
            <input type="hidden" id="book_id" value="<?= $bookId;  ?>" />
            <div id="closeEditor" class="btn closeEditor" title="Close"></div>
            <div id="book">
                <div id="cover-page">
                    <!-- <canvas id='Page0' width="400" height="500"></canvas> -->
                </div>

            </div>

            <div id="loading-canvas">
                <div id="loading-div">
                    <img src="images/loading.gif" alt="Loading.." />
                </div>
            </div>

            <?php } else { ?>

            <script type="text/javascript ">
                $( document ).ready( function() {
                    $( '#closeEditor' ).click( function() {
                        window.parent.$( '#cboxClose' ).click();
                    } );
                } );
            </script>
            <div id="closeEditor" class="btn closeEditor" title="Close"></div>
            <?php
            //$access->outputAccessDenial( 'You don\'t have access to this scrapbook.' );
            $access->outputMostRecentDenial();
        } ?>
    </body>
    </body>
</html>
