<?php
    /**
     * File: preview.php
     * Created: 7/30/12  4:02 PM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */

    //#todo: add access control to this
?>
<html>
<head>  
    <?php include 'head.php'        
     ?>
    <script src="js/preview.js" type="text/javascript"></script>
    <link href="css/preview.css" rel="stylesheet" type="text/css" charset="utf-8">  
</head>
     
     
    <body>
        <div id="main_container">  
                <?php 
   
                if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {                
                ?>
                   <input type="hidden" id="book_id" value="<?php if (isset($_GET['id'])) echo $_GET['id']; ?>"/>                   
                   
              <div id="previewScreen">
                 <div id="avpw_zoom_container" class="avpw_zoom_visible avpw_zoom_open">
                                <div id="avpw_zoom_button">
                                                    <a id="avpw_zoom_icon" href="#zoom"></a>
                                            </div>
                                <div class="avpw_zoom_slider_container avpw_isa_slider_container">
                                    <div id="avpw_zoom_slider">
                                        <a id="avpw_zoom_handle" style="left: 0%;"></a>
                                    </div>
                                </div>
                  </div>
              
            
              <div id="previewPage">
                    <div id="realPage">
                            <img src="images/greenArrowLeft.png" alt="Previous Page" id="goToPreviousPage" class="btn previousPage">

                            <div id="editorPaginationPrevious" class="btn previousPage">Previous</div>

                            <div id="editorPaginationPageNumber">
                                <label for="scrapbookPageNumber">Page Number</label>
                                <input type="text" name="scrapbookPageNumber" id="scrapbookPageNumber" value="1">
                            </div>

                            <img src="images/greenArrowRight.png" alt="Next Page" id="goToNextPage" class="btn nextPage">

                            <div id="editorPaginationNext" class="btn nextPage">Next</div>
                    </div>
              </div>
        </div>
                   
                  <?php } else { ?>
                    <a href="../" ><span>please log in</span></a>
                <?php } ?>
        </div>
    </body>
    
</html>