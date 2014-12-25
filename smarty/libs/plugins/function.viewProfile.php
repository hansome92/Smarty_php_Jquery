<?php
    /*
     * return an array with true or false, and some user data, depending on if a user is logged in or not.
     */
    function smarty_function_viewProfile( $params, $smarty ) {
        require_once 'user.php';
        $userObject = new user();
        $user = $userObject->noUser;

        if( isset( $_GET['user'] ) && !is_array( $_GET['user'] ) ) {

            $foundUser = $userObject->getUserByDisplayName( $_GET['user'] );
            $ds = DIRECTORY_SEPARATOR;
            if( !is_null( $foundUser ) && $foundUser['userFound'] ) {
                $user = Array( 'login'       => $foundUser['authentications']['login'],
                               'content'     => $foundUser['authentications']['content'],
                               'scrapbooks'  => $foundUser['scrapbooks'],
                               'photoAlbums' => $foundUser['photoAlbums'], //#TODO: Remove this maybe? no photo albums will ever be public- but maybe some other pages load it?
                               'profilePic'  => realpath( dirname( __FILE__ ) )."{$ds}..{$ds}..{$ds}..{$ds}".'userpics'.$ds.$foundUser['_id']->{'$id'}.$ds.'profile.png',
                               'paidUser'    => isset( $foundUser['paidUser'] ) ? $foundUser['paidUser'] : FALSE,
                               'id'          => $foundUser['_id']->{'$id'},
                               'userFound'   => TRUE
                );
            }
        }

        // assign data directly to template var passed in
        /**@var $smarty Smarty */
        $smarty->assign( $params['assign'], $user );
    }

    /*
    * in template:
    * {login assign="login"}
    * <div class="login">welcome, {$login.username}</div>
    */

?>