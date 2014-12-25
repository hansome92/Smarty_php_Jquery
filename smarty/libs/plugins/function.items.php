<?php
    /*
     * return an categories with sub categories.
     */
    function smarty_function_items( $params, $smarty ) {
        require_once 'item.php';
        $item = new item();
        $items = array();
        if( $params['cat_id'] == 59 ) {
            // the root photo album button cat_id
            require_once 'user.php';
            $user = new user();
            $res = $user->getAllCurrentUsersAlbums();
            $userId = $user->getCurrentUsersId();
            foreach( $res as $val ) {
                $path = './../userpics'.'/'.$userId.'/'.$val['filename'];
                $items[] = array(
                    'path' => $path,
                    'name' => $val['name'],
                    'id'   => $val['albumid']
                );
            }
        }/* elseif( $params['type'] == 'photo' ) {
            //get all of the photos for a given albumid(catid)
            require_once 'user.php';
            $user = new user();
            $items = $user->getPhotosByAlbumId( $params['cat_id'] );

        } */else {
            $items = $item->getItemsByCategory( $params['cat_id'] );
        }

        /**@var $smarty Smarty */
        $smarty->assign( $params['assign'], $items );
    }

    /*
    * in template:
    * {login assign="login"}
    * <div class="login">welcome, {$login.username}</div>
    */

?>