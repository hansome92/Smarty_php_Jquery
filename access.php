<?php
    /**
     * File: access.php
     * Created: 9/13/12  6:57 PM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */

    require_once 'user.php';

    class access extends user {
        public $mostRecentDenial = array();
        public $allDenials = array();
        protected $bookCollection = NULL;
        public $invalidUserOrBook = 'Invalid user or book ID. Try clearing your browser cache and logging in again.';

        function __construct() {
            parent::__construct();
            $this->bookCollection = $this->db->selectCollection( 'books' );
        }

        function currentUserCanViewBook( $bookId ) {
            $uid = $this->getCurrentUsersId();
            return $this->userCanViewBook( $uid, $bookId );
        }

        function userCanViewBook( $userId, $bookId ) {
            if( ( isset( $_GET['s'] ) && $this->validSecurityHash( $bookId, $_GET['s'] ) ) || ( isset( $_POST['s'] ) && $this->validSecurityHash( $bookId, $_POST['s'] ) ) ) {
                return TRUE;
            }
            if( $this->bookIsPublic( $bookId ) || $this->userIsAdmin( $userId ) ) {
                return TRUE;
            }
            if( !$this->validUserId( $userId ) ) {
                $this->addDenial( 'You are not logged in or there was a problem logging you in. Try clearing your browser cache and logging in again.', '/' );
            }
            if( !$this->validUserId( $bookId ) ) {
                $this->addDenial( $this->invalidUserOrBook, '/' );

            }
            if( $this->userCanEditBook( $userId, $bookId ) ) {
                return TRUE;
            }
            $this->addDenial( 'You do not have access to view this book.', '/' );
            return FALSE;
        }

        function validSecurityHash( $bookid, $hash ) {
            if( encrypt( $bookid ) == $hash ) {
                return TRUE;
            }
            return FALSE;
        }

        function userOwnsBook( $userId, $bookId ) {
            if( !$this->validUserId( $userId ) || !$this->validUserId( $bookId ) ) {
                $this->addDenial( $this->invalidUserOrBook, '/' );
                return FALSE;
            }
            $userId = cleanAlphaNum( $userId );
            $bookId = cleanAlphaNum( $bookId );

            $res = $this->usersCollection->findOne( array( "_id"           => new MongoId( $userId ),
                                                           'scrapbooks.id' => $bookId
                                                    ) );
            if( !is_null( $res ) && sizeof( $res ) > 0 ) {
                return TRUE;
            }
        }


        function currentUserOwnsBook( $bookId ) {
            $uid = $this->getCurrentUsersId();
            if( !$this->validUserId( $uid ) ) {
                return FALSE;
            }
            return $this->userOwnsBook( $uid, $bookId );
        }

        function userCanEditBook( $userId, $bookId ) {
            if( $this->currentUserIsAdmin() ) {
                return TRUE;
            }
            if( !$this->validUserId( $userId ) || !$this->validUserId( $bookId ) ) {
                $this->addDenial( $this->invalidUserOrBook, '/' );
                return FALSE;
            }

            $userId = cleanAlphaNum( $userId );
            $bookId = cleanAlphaNum( $bookId );

            //check if a user has editing permissions
            //#TODO: in future- no multi-user editing yet
            /*
            $res = $this->sql->query( "select * from `access` where userid = '{$userId}' and bookid = '{$bookId}'" );
            if( $res->num_rows > 0 || $this->userOwnsBook( $userId, $bookId ) ) {
                return TRUE;
            }
            */

            if( $this->userOwnsBook( $userId, $bookId ) ) {
                return TRUE;
            }
            return FALSE;
        }

        function currentUserCanEditBook( $bookId ) {
            $uid = $this->getCurrentUsersId();
            if( !$this->validUserId( $uid ) ) {
                $this->addDenial( 'Please log in.', '/' );
                return FALSE;
            }
            return $this->userCanEditBook( $uid, $bookId );
        }

        function userIsAdmin( $userId ) {
            $userId = cleanAlphaNum( $userId );
            $res = $this->usersCollection->findOne( array( "_id"       => new MongoId( $userId ),
                                                           'adminUser' => TRUE
                                                    ) );
            if( !is_null( $res ) && sizeof( $res ) > 0 ) {
                return TRUE;
            }
            return FALSE;
        }

        function currentUserIsAdmin() {
            $uid = $this->getCurrentUsersId();
            if( !$this->validUserId( $uid ) ) {
                $this->addDenial( 'Please log in', '/' );
                return FALSE;
            }
            return $this->userIsAdmin( $uid );
        }

        function bookIsPublic( $bookId ) {
            $bookId = cleanAlphaNum( $bookId );
            $res = $this->usersCollection->findOne( array( 'scrapbooks' => array( '$elemMatch' => array( 'id' => $bookId, 'isPublic' => 'TRUE' ) ) ) );
            if( !is_null( $res ) && sizeof( $res ) > 0 ) {
                return TRUE;
            }
            return FALSE;
        }

        function addDenial( $message, $url = '/' ) {
            $denial = array( 'm' => $message, 'url' => $url );
            $this->mostRecentDenial = $denial;
            $this->allDenials[] = $denial;
        }

        function outputAllDenials() {
            foreach( $this->allDenials as $denial ) {
                $this->outputAccessDenial( $denial['m'], $denial['url'] );
            }
        }


        function outputMostRecentDenial() {
            if( isset( $this->mostRecentDenial['m'] ) ) {
                $this->outputAccessDenial( $this->mostRecentDenial['m'], $this->mostRecentDenial['url'] );
            }
        }

        function outputAccessDenial( $message, $url = '/' ) {

            ?>
        <div style="background:#778A84;padding:40px 60px;margin: 20px 30px ;display:inline-block;border:2px solid white;">
            <a style="font-size:30px;color:white;font-weight:bold;" href="<?=$url?>"><?=$message?></a>
        </div>
        <?php
        }

    }
