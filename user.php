<?php
    require_once 'mongodatabase.php';
    require_once 'mysqlDatabase.php';

    class user extends mongodatabase {
        public $usersCollection;
        public $userId = NULL;
        public $sql;

        function __construct() {
            parent::__construct();
            $this->sql = new mysqlDatabase();
            $this->noUser = Array( 'userFound' => FALSE );
            $this->usersCollection = $this->db->selectCollection( "Users" );

            $this->getFromPhotos = ' photoid, userid, albumid, height, width, filename ';
            $this->userId = $this->getCurrentUsersId();
        }

        function getUserByDisplayName( $displayName ) {
            $displayNameRegex = new MongoRegex( '/'.preg_quote( (String)$displayName ).'/i' );
            $foundUser = $this->usersCollection->findOne( array( "authentications.login.displayName" => $displayNameRegex ) );

            if( isset( $foundUser['authentications'] ) ) {
                return $this->returnUser( $foundUser );
            } else {
                return $this->noUser;
            }
        }

        function addNewCurrentUserScrapbook( $bookname, $scraplook = 'default' ) {
            return $this->addNewUserScrapbook( $this->getCurrentUsersMongoId(), $bookname, $scraplook );
        }

        function addNewUserScrapbook( $userId, $bookname, $scraplook = 'default' ) {
            $bookname = sanitize( $bookname );
            $scraplook = sanitize( $scraplook, TRUE );
            $bookId = md5( $bookname.$userId );
            $this->usersCollection->update( array( "_id" => $userId ),
                                            array( '$push' => array( 'scrapbooks' => array( 'id'        => $bookId,
                                                                                            'name'      => $bookname,
                                                                                            'scraplook' => $scraplook,
                                                                                            'created'   => new MongoDate() ) )
                                            ) );
            return $bookId;
        }

        function currentUserIsPaid() {
            $uid = $this->getCurrentUsersId();
            if( !$this->validUserId( $uid ) ) {
                return FALSE;
            }
            return $this->userIsPaid( $uid );
        }

        function userIsPaid( $userId ) {
            $userId = cleanAlphaNum( $userId );
            $res = $this->usersCollection->findOne( array( "_id"      => new MongoId( $userId ),
                                                           'paidUser' => TRUE
                                                    ) );
            if( !is_null( $res ) && sizeof( $res ) > 0 ) {
                return TRUE;
            }
            return FALSE;
        }

        function validUserId( $userId ) {
            if( is_null( $userId ) || !is_string( $userId ) || $userId == '' ) {
                return FALSE;
            }
            return TRUE;
        }

        function setUserAsPaidAccount( $userId ) {
            $userId = $this->getUsersMongoId( $userId );
            $this->usersCollection->update( array( "_id" => $userId ), array( '$set' => array( 'paidUser' => TRUE, 'paymentDate' => new MongoDate() ) ) );
        }

        function setUserAsNormalAccount( $userId ) {
            $userId = $this->getUsersMongoId( $userId );
            $this->usersCollection->update( array( "_id" => $userId ), array( '$set' => array( 'paidUser' => FALSE ) ) );
        }

        function setExpiredAccountsToNormal() {
            $timeNow = new DateTime();
            $endTime = $timeNow->sub( new DateInterval( 'P1Y' ) );
            $end = new MongoDate( $endTime->getTimestamp() );
            $this->usersCollection->update( array( 'paymentDate' => array( '$lte' => $end ) ), array( '$set' => array( 'paidUser' => FALSE ) ) );
        }

        function updateCurrentUserProfile( $profile ) {
            $this->updateUserProfile( $this->getCurrentUsersMongoId(), $profile );
        }

        function updateUserProfile( $userId, $profile ) {
            $profile = sanitize( $profile );
            $queryArr = array();
            $qer = 'update users set username = username';
            foreach( $profile as $what => $value ) {
                switch( $what ) {
                    case 'fName':
                        $queryArr['authentications.login.fname'] = $value;
                        $qer = $qer.", fname = '{$this->sql->escapeString($queryArr['authentications.login.fname'])}'";
                        $_SESSION['fname'] = $value;
                        break;
                    case 'lName':
                        $queryArr['authentications.login.lname'] = $value;
                        $qer = $qer.", lname = '{$this->sql->escapeString($queryArr['authentications.login.lname'])}'";
                        $_SESSION['lname'] = $value;
                        break;
                    case 'email':
                        if( !$this->isValidEmail( $value ) ) {
                            die( 'Please enter a valid email address.' );
                        }
                        $queryArr['authentications.login.contactEmail'] = $value;
                        $qer = $qer.", contactEmail = '{$this->sql->escapeString($queryArr['authentications.login.contactEmail'])}'";
                        $_SESSION['contactEmail'] = $value;
                        break;
                    case 'displayName':
                        $value = str_replace( ' ', '', $value );
                        if( strlen( $value ) > 15 ) {
                            die( 'Your username is too long, it must be under 16 characters.' );
                        }
                        $queryArr['authentications.login.displayName'] = $value;
                        $qer = $qer.", displayName = '{$this->sql->escapeString($queryArr['authentications.login.displayName'])}'";
                        $_SESSION['displayName'] = $value;
                        break;
                    case 'uDescription':
                        $queryArr ['authentications.login.description'] = $value;
                        $qer = $qer.", description = '{$this->sql->escapeString($queryArr['authentications.login.description'])}'";
                        break;
                }
            }
            $this->usersCollection->update( array( "_id" => $userId ), array( '$set' => $queryArr ) );
            $qer = $qer." where userid = '{$userId}'";
            $this->sql->query( $qer );
        }


        function updateCurrentUser( $newData, $options = Array() ) {
            $userId = $this->getCurrentUsersMongoId();
            return $this->updateUser( $userId, $newData, $options );
        }

        function updateUser( $userId, $newData, $options = Array() ) {
            return $this->usersCollection->update( array( '_id' => $userId ), $newData, $options );
        }

        function getCurrentUsersMongoId() {
            if( !isset( $_SESSION['id'] ) || is_null( $_SESSION['id'] ) ) {
                return NULL;
            }
            return $this->getUsersMongoId( $this->getCurrentUsersId() );
        }

        function getUsersMongoId( $id ) {
            if( is_a( $id, 'MongoId' ) ) {
                return $id;
            }
            return new MongoId( $id );
        }


        function addCurrentUserContact( $name, $email ) {
            return $this->addContact( $this->getCurrentUsersId(), $name, $email );
        }

        function deleteCurrentUserContact( $contactid ) {
            return $this->deleteContact( $this->getCurrentUsersId(), $contactid );
        }

        function deleteContact( $userId, $contactid ) {
            if( !$this->validUserId( $userId ) || !is_numeric( $contactid ) ) {
                return -1;
            }
            $userId = $this->sql->escapeString( $userId );
            $contactid = $this->sql->escapeString( $contactid );

            $contactQ = "DELETE FROM `contacts` WHERE  `userid` = '{$userId}' AND `contactid` = '{$contactid}' ";
            $this->sql->query( $contactQ );
            if( $this->sql->sql->affected_rows > 0 ) {
                return 1;
            }
            return -1;
        }

        function addContact( $userId, $name, $email ) {
            if( !$this->isValidEmail( $email ) ) {
                return -1;
            }
            $userId = $this->sql->escapeString( $userId );
            $name = $this->sql->escapeString( sanitize( $name ) );
            $email = $this->sql->escapeString( $email );
            $contactQ = "SELECT * FROM `contacts` WHERE  `userid` = '{$userId}' AND `email` = '{$email}' ";
            $res = $this->sql->query( $contactQ );
            if( $res->num_rows < 1 ) {
                $contactQ = "INSERT INTO `contacts`( `userId`,`name`,`email`) VALUES( '{$userId}', '{$name}', '{$email}' )";
                $this->sql->query( $contactQ );
                return $this->sql->sql->insert_id;
            } else {
                return -2;
            }
        }

        function getUsersContacts( $userId ) {
            $userId = $this->sql->escapeString( $userId );
            $contactRes = $this->sql->query( " SELECT * FROM `contacts` WHERE `userid` = '{$userId}' " );
            return $this->sql->sqlToArray( $contactRes );
        }

        function getCurrentusersContacts() {
            return $this->getUsersContacts( $this->getCurrentUsersId() );
        }

        function getCurrentUsersId() {
            if( !is_null( $this->userId ) ) {
                return $this->userId;
            }
            return getCurrentUsersId();
        }

        function isValidEmail( $email ) {
            $regex = '/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+'. // the user name
                '@'. // the ubiquitous at-sign
                '([-0-9A-Z]+\.)+'. // host, sub-, and domain names
                '([0-9A-Z]){2,4}$/i'; // top-level domain (TLD)
            return ( preg_match( $regex, trim( $email ) ) );
        }

        function getPhotoAlbums( $userId ) {
            $userId = $this->sql->escapeString( $userId );
            $albumRes = $this->sql->query( "SELECT * FROM `photoalbums` WHERE `userId` = '{$userId}' AND `deleted` = 0" );
            return $this->sql->sqlToArray( $albumRes );
        }

        function getCurrentUsersUnfiledPhotos() {
            return $this->getUnfiledPhotos( $this->getCurrentUsersId() );
        }

        function getUnfiledPhotos( $userId ) {
            $userId = $this->sql->escapeString( $userId );
            $qFreePhoto = "SELECT {$this->getFromPhotos} FROM `photos` WHERE `userId` = '{$userId}' AND `albumId` = 0 AND `deleted` = 0";
            $freePhotosRes = $this->sql->query( $qFreePhoto );
            return $this->sql->sqlToArray( $freePhotosRes );
        }

        function getAllCurrentUsersAlbums() {
            return $this->getAllAlbumsByUserId( $this->getCurrentUsersId() );
        }

        function getAllAlbumsByUserId( $userId ) {
            $userId = $this->sql->escapeString( $userId );
            $leftJoin = "SELECT * FROM photos WHERE userid = '{$userId}' AND photoid IS NOT NULL AND deleted = 0 GROUP BY albumid ORDER BY photoid ASC";
            $union = "SELECT '0' AS albumid, 'Unfiled Pictures' AS `name`, p.height, p.width, p.filename AS filename FROM photos p
                        WHERE userid = '{$userId}' AND albumid = 0 AND photoid IS NOT NULL AND deleted = 0 LIMIT 0,1";
            $query = "SELECT pa.albumid AS albumid, pa.`name` AS `name`, p.height, p.width, p.filename AS filename FROM photoalbums pa LEFT JOIN( {$leftJoin} ) AS p ON pa.albumid = p.albumid
                        WHERE pa.userid = '{$userId}'AND pa.deleted = 0 AND pa.`name` IS NOT NULL GROUP BY pa.albumid UNION( $union ) ORDER BY albumid ASC";
            $albumRes = $this->sql->query( $query );
            //echo $query;
            //var_dump($albumRes);
            return $this->sql->sqlToArray( $albumRes );
        }

        function getCurrentUsersAlbums() {
            return $this->getAlbumsByUserId( $this->getCurrentUsersId() );
        }

        function getAlbumsByUserId( $userId ) {
            $userId = $this->sql->escapeString( $userId );
            $innerQuery = " select * from photos where userid = '{$userId}' and photoid is not NULL and deleted = 0 group by albumid order by photoid asc ";
            $query = "select pa.albumid as albumid, pa.name as name, pa.userid as userid, p.height, p.width, p.filename as filename  from photoalbums pa
                    LEFT join({$innerQuery}) as p on pa.albumid = p.albumid
                    where pa.userid = '{$userId}' and pa.deleted = 0 and pa.name is not NULL group by pa.albumid";
            $albumRes = $this->sql->query( $query );
            //var_dump($albumRes);
            return $this->sql->sqlToArray( $albumRes );
        }

        function getPhotosByAlbumId( $albumId, $userId = NULL ) {
            if( is_null( $userId ) ) {
                $userId = $this->getCurrentUsersId();
            }
            $userId = $this->sql->escapeString( $userId );
            $albumId = $this->sql->escapeString( $albumId );
            $query = "SELECT {$this->getFromPhotos} FROM `photos` WHERE `albumId` = '{$albumId}' AND `userId` = '{$userId}'  AND `deleted` = 0 ORDER BY photoid ASC ";
            $albumRes = $this->sql->query( $query );
            return $this->sql->sqlToArray( $albumRes );
        }

        function getAllCurrentUsersPhotos( $userId = NULL ) {
            if( is_null( $userId ) ) {
                $userId = $this->getCurrentUsersId();
            }
            $userId = $this->sql->escapeString( $userId );
            $query = "SELECT {$this->getFromPhotos} FROM `photos` WHERE  `userId` = '{$userId}'  AND `deleted` = 0 ORDER BY albumid ASC, photoid ASC";
            $albumRes = $this->sql->query( $query );
            return $this->sql->sqlToArray( $albumRes );
        }


        function getFacebookAlbumCover( $albumId ) {
            $albumId = $this->sql->escapeString( $albumId );
            $outerQuery = "SELECT {$this->getFromPhotos} FROM photos WHERE photos.albumid = '{$albumId}' and photos.height > 100 and photos.height < 500 ORDER BY photos.height DESC limit 1 ";
            //GROUP BY photos
            $albumRes = $this->sql->query( $outerQuery );
            if( !$albumRes ) {
                var_dump( $this->sql->error() );
                return '';
            }
            return $this->sql->sqlToArray( $albumRes );
        }

        function getCurrentUsersArray() {
            if( !isset( $_SESSION['id'] ) || is_null( $_SESSION['id'] ) ) {
                return NULL;
            }
            return $this->getUsersArray( $this->getCurrentUsersMongoId() );
        }

        function getUsersArray( $mongoId ) {
            return $this->returnUser( $this->usersCollection->findOne( array( '_id' => $mongoId ) ) );
        }


        private function returnUser( $userArray ) {
            return array_merge( $userArray, Array( 'userFound' => TRUE ) );
        }

    }
