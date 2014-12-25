<?php
    /*
     * return an categories with sub categories.
     */
    function smarty_function_categories( $params, $smarty ) {
        require_once 'category.php';
        $category = new category();

        /**@var $smarty Smarty*/
        $smarty->assign( $params['assign'], $category->rootCategories );
        $smarty->assign( 'subcategories', $category->subcategories );
    }

    /*
    * in template:
    * {login assign="login"}
    * <div class="login">welcome, {$login.username}</div>
    */

?>