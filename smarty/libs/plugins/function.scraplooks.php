<?php
    /*
     * return all of the scraplooks
     */
    function smarty_function_scraplooks( $params, $smarty ) {
        require_once 'contentLoader.php';
        $cl = new contentLoader();
        // assign data directly to template var passed in
        /**@var $smarty Smarty */
        $smarty->assign( $params['assign'], $cl->getScraplooks() );
    }


?>