<?php
    /*
     * return an array with true or false, and some user data, depending on if a user is logged in or not.
     */
    function smarty_function_myProfile( $params, $smarty ) {
        require_once 'user.php';
        require_once 'book.php';
        $userObject = new user();
        $foundUser = $userObject->getCurrentUsersArray();

        $photoAlbums = $userObject->getPhotoAlbums( $foundUser['_id']->{'$id'} );

        $albumsWithPictures = Array();
        foreach( $photoAlbums as $photoAlbum ) {
            $photoAlbum['photos'] = $userObject->getFacebookAlbumCover( $photoAlbum['albumid'] );
            $albumsWithPictures[] = $photoAlbum;
        }
        $ds = DIRECTORY_SEPARATOR;
        $sbks = array();
        $scraplooks = new scraplooks();
        foreach( $foundUser['scrapbooks'] as $sbook ) {
            $sbks[] = array_merge( array( 'coverUrl' => $scraplooks->getCoverUrlByName( $sbook['scraplook'] ) ), $sbook );
        }
        $user = Array( 'login'       => $foundUser['authentications']['login'],
                       'scrapbooks'  => $sbks,
                       'contacts'    => $userObject->getCurrentUsersContacts(),
                       'photoAlbums' => $albumsWithPictures,
                       'paidUser'    => isset( $foundUser['paidUser'] ) ? $foundUser['paidUser'] : FALSE,
                       'profilePic'  => realpath( dirname( __FILE__ ) )."{$ds}..{$ds}..{$ds}..{$ds}".'userpics'.$ds.$foundUser['_id']->{'$id'}.$ds.'profile.png',
                       'id'          => $foundUser['_id']->{'$id'},
                       'session_id'  => session_id()
        );

        // assign data directly to template var passed in
        /**@var Smarty $smarty */
        $smarty->assign( $params['assign'], $user );
    }

    /*
     * in template:
     * {login assign="login"}
     * <div class="login">welcome, {$login.username}</div>
     */

?>