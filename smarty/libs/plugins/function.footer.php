<?php
    /**
     * File: function.footer.php
     * Created: 10/25/12  6:54 PM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */

    function smarty_function_footer( $params, $smarty ) {
        require_once 'contentLoader.php';
        $cl = new contentLoader();

        // assign data directly to template var passed in
        /**@var $smarty Smarty */
        $smarty->assign( 'footer', $cl->getFooter() );
    }