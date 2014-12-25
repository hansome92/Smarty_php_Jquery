<?php
    /*
     * return an array with true or false, and some user data, depending on if a user is logged in or not.
     */
    function smarty_function_login( $params, $smarty ) {
        $login = Array();
        $login['isLoggedIn'] = FALSE;
        if( isset( $_SESSION['loggedIn'] ) && $_SESSION['loggedIn'] == TRUE ) {
            $login['isLoggedIn'] = TRUE;
            if( isset( $_SESSION['displayName'] ) && strlen( (string)$_SESSION['displayName'] ) > 2 ) {
                $name = $_SESSION['displayName'];
            } elseif( isset( $_SESSION['fname'] ) ) {
                $name = $_SESSION['fname'];
            } elseif( isset( $_SESSION['username'] ) ) {
                $name = $_SESSION['username'];
            } elseif( isset( $_SESSION['email'] ) ) {
                $name = $_SESSION['email'];
            } else {
                $name = ' -- ';
            }

            $login['userid'] = getCurrentUsersId();
            $login['profilepic'] = dirname( __DIR__ ).'\\..\\'.'\\..\\'.'userpics\\'.$login['userid'].'\\profile.png';

            $login['username'] = $name;
            $login['tlname'] = isset( $_SESSION['displayName'] ) ? $_SESSION['displayName'] : '';

            $login['logoutUrl'] = $_SESSION['logoutUrl'];
        }

        // assign data directly to template var passed in
        /**@var $smarty Smarty */
        $smarty->assign( $params['assign'], $login );
    }

    /*
     * in template:
     * {login assign="login"}
     * <div class="login">welcome, {$login.username}</div>
     */

?>