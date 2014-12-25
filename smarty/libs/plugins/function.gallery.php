<?php
    /*
     * return an categories with sub categories.
     */
    require_once 'user.php';
    require_once 'access.php';
    
    function smarty_function_gallery( $params, $smarty ) {

        $userObject = new user();       
        $accessObject = new access();

        $user = $userObject->noUser;
      if( isset( $_GET['user'] ) && !is_array( $_GET['user'] ) ) {

            $foundUser = $userObject->getUserByDisplayName( $_GET['user'] );
            $i = 0;        
            foreach ($foundUser['scrapbooks'] as $book) {
                if ($accessObject->userCanViewBook($foundUser['_id']->{'$id'}, $book["id"])) {                
                    $i += 1;
                    $row[] = $book; 
                }
                
                if (isset($row) && ($i % 3) == 0 ) {
                    $scrapbooks[] = $row;
                    unset($row);
                    $i = 0;
                 }
            }
            
            if ($i != 0) {
                $scrapbooks[] = $row;
            }
            
        }
        if (empty($scrapbooks) ){
            $scrapbooks = null;
        }
        $smarty->assign($params['assign'], $scrapbooks);        
      
    }

    /*
    * in template:
    * {login assign="login"}
    * <div class="login">welcome, {$login.username}</div>
    */

?>