<?php
    /**
     * File: editor.php
     * Created: 5/28/12  4:02 PM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */
?>
<html>

    <head>

        <?php
        $notDirect = TRUE;
        include 'head.php';
        ?>

        <style type="text/css">
            #openEditorAgain {
                margin-top: 250px;
                background-color: #228aff;
                border: 1px solid gray;
                display: inline-block;
                border-radius: 15px;
                padding: 1px 5px;
            }

            #cboxclose {
                height: 0;
                width: 0;
            }

            #cboxloadedcontent {
                border: none;
            }

            #cboxOverlay {
                background-color: #354D47;
            }

        </style>

        <script type="text/javascript">
            $( document ).ready( function() {
                if( $( window ).height() > 850 ) {
                    var top = $( window ).scrollTop();
                    var left = $( window ).scrollLeft();
                    $( 'body' ).add( '#editorHolder' ).css( 'overflow', 'hidden' );
                    $( window ).scroll( function() {
                        $( this ).scrollTop( top ).scrollLeft( left );
                    } );
                }
            } );

            $( document ).ready( function() {
            <?php //#TODO: put something here which clicks the first editor root category menu item ?>
            } );
        </script>

    </head>
    <body>
        <div style="text-align: center;">
        </div>
        <div id="editorHolder">
            <div id="editorInnerHolder">

                <div id="menuHolder">

                    <?php
                    $bookId = isset( $_GET['bookId'] ) ? cleanAlphaNum( $_GET['bookId'] ) : '';

                    if( $access->currentUserCanEditBook( $bookId ) ) {
                        $user = new category();
                        $res = $user->getCurrentUsersAlbums();
                        $userId = $user->getCurrentUsersId();

                        ?>
                        <input type="hidden" id="user_id" value="<?= $userId; ?>" />
                        <input type="hidden" id="book_id" value="<?= $bookId;  ?>" />

                        <span id="menuHolderInner">
                        <div id="menuTop">
                            <div id="closeEditor" class="btn closeEditor" title="Close Editor"></div>
                            <!-- zoom holder -->
                            <div id="zoom_container" class="zoom_visible zoom_open">
                                <div class="zoom_slider_container">
                                    <div id="zoom_slider">
                                        <a id="zoom_handle" style="left: 0%;"></a>
                                    </div>
                                </div>
                                <div id="zoom_button">
                                    <span id="zoom_icon">zoom</span>
                                </div>
                            </div>
                            <!-- Send back button -->
                            <div id="sendBackBtn">
                                <div class="toolbar_item">
                                    <a id="cropBtn" href="#" class="undoClass"> </a>
                                </div>
                                <div class="toolbar_item">
                                    <a id="undoBtn" href="#" class="undoClass"></a>
                                </div>
                                <div class="toolbar_item">
                                    <a id="redoBtn" href="#" class="undoClass"></a>
                                </div>
                                <div id="send_to_back" class="toolbar_item">
                                    <img src="<?=URL_PREFIX?>images/send_to_back.png" />
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                        </div>

                            <div id="menuCategories">
                                <div id="menuh-container">
                                    <div id="menuh">
                                        <?php
                                        $categories = $user->rootCategories;
                                        $subcategories = $user->subcategories;
                                        foreach( $categories as $category ): ?>
                                            <ul class="root_cat" data-type="<?=$category['type'];?>">
                                                <li><a href="#" id="cat_<?=$category['id']; ?>" class="top_parent"><?=$category['category_name']; ?></a>
                                                    <?php $cat_id = $category['id']; ?>
                                                    <?php if( isset( $subcategories['sub'][$cat_id] ) ): ?>
                                                        <ul>
                                                            <?php foreach( $subcategories['sub'][$cat_id] as $subcat1 ): ?>
                                                            <li class="parent <?=$subcat1['type'];?>">
                                                                <a href="#" id="<?=( $subcat1['type'] == 'photo' ? 'pho_' : 'cat_' ).$subcat1['id']; ?>"><?=$subcat1['category_name']; ?></a>
                                                                <?php if( isset( $subcategories['sub_sub'][$subcat1['id']] ) ): ?>
                                                                <ul>
                                                                    <?php foreach( $subcategories['sub_sub'][$subcat1['id']] as $subcat2 ): ?>
                                                                    <li class="parent <?=$subcat2['type'];?>" style="background-image: none;">
                                                                        <a href="#" id="cat_<?=$subcat2['id']; ?>" class="child"><?=$subcat2['category_name']; ?></a>
                                                                    </li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                                <?php endif; ?>
                                                            </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                        <?php endif; ?>
                                                </li>
                                            </ul>
                                            <?php endforeach; ?>
                                    </div>
                                </div>
                                <div id="saveProgress" class="btn" title="Save Your Changes"></div>
                                <div id="inEditorPreview" class="btn" title="Preview"></div>
                            </div>
</span>

                        <div id="editorMainHolder">

                            <div id="editorScrapbookWindowHolder">
                                <div id="editorScrapbookWindow">
                                    <canvas id="editorScrapbookCanvas" width="852" height="632"></canvas>
                                </div>

                                <div id="editorPagination">
                                    <img src="<?=URL_PREFIX?>images/greenArrowLeft.png" alt="Previous Page" id="goToPreviousPage" class="btn goToPreviousPage">

                                    <div id="editorPaginationPrevious" class="btn goToPreviousPage">previous</div>

                                    <div id="editorPaginationPageNumber">
                                        <label for="scrapbookPageNumber">page number</label>
                                        <input type="text" name="scrapbookPageNumber" id="scrapbookPageNumber" value="1">
                                    </div>

                                    <img src="<?=URL_PREFIX?>images/greenArrowRight.png" alt="Next Page" id="goToNextPage" class="btn goToNextPage">

                                    <div id="editorPaginationNext" class="btn goToNextPage">next</div>
                                </div>

                            </div>

                            <div id="editorVerticalContentHolder">

                                <div id="editorVerticalContentScroller" class="content">
                                </div>

                                <div id="editorVerticalImageFilter" class="content">
                                </div>

                                <textarea type="text" id="addTextBox"></textarea>

                                <div id="editorVerticalFonts" class="content">
                                    <!-- fonts go here -->
                                </div>

                                <div id="editorVerticalborders" class="content">
                                    <!-- borders go here -->
                                </div>

                                <div id="editorVerticalAlbums" class="content">
                                    <!-- user's photo albums go here -->
                                </div>

                                <div id="editorVerticalPhotos" class="content">
                                    <br />
                                    <!--span id="backBtn" class="button">Back</span-->
                                </div>

                                <div id="editorVerticalwallpaper" class="content">
                                </div>
                            </div>
                        </div>
                        <?php
                    } else {
                        //denied access to editing this book
                        ?>
                        <script type="text/javascript ">
                            $( document ).ready( function() {
                                $( '.closeEditor' ).click( function() {
                                    savePage();
                                    window.parent.$( '#cboxClose' ).click();
                                } );
                            } );
                        </script>
                        <div class="btn closeEditor" title="Close"></div>
                        <?php
                        $access->outputMostRecentDenial();
                        //$access->outputAccessDenial('You don\'t have access to this scrapbook.');
                    }
                    ?>
                </div>
                <div id="loading-canvas">
                    <div id="loading-div">
                        <img src="<?=URL_PREFIX?>images/loading.gif" alt="Loading.." />
                    </div>
                </div>
    </body>

</html>

<?php



?>

