<?php

    /*
    * To change this template, choose Tools | Templates
    * and open the template in the editor.
    */

    /**
     * Description of book
     *
     * @author Administrator
     */

    require_once 'user.php';
    require_once 'access.php';
    require_once 'scraplooks.php';

    class book extends user {
        public $booksCollection;
        public $access;
        public $scraplooks;

        function __construct() {
            parent::__construct();
            $this->booksCollection = $this->db->selectCollection( "books" );
            $this->scraplooks = new scraplooks();
            $this->access = new access();
        }

        function getBooksByUserIdAndName( $options = Array() ) {
            $sql = array_merge( array( 'userid' => $this->getCurrentUsersId() ), $options );
            return $this->booksCollection->findOne( $sql );
        }

        function getBooksByBookId( $options = Array() ) {
            if( !is_array( $options ) ) {
                $options = array( 'bookId' => $options );
            }
            $book = $this->booksCollection->findOne( $options );
            return $book;

        }

        function findBookByUserIdAndName( $userid, $bookname ) {
            return $this->usersCollection->findOne( array( '_id' => new MongoId( $userid ), 'scrapbooks' => array( '$elemMatch' => array( 'name' => $bookname ) ) ) );
        }

        function findCurrentUsersBookByName( $bookname ) {
            return $this->findBookByUserIdAndName( $this->getCurrentUsersId(), $bookname );
        }

        function getBooksByBookIdToJson( $options = Array() ) {
            $books = $this->getBooksByBookId( $options );
            $json_data = 'null';
            if( $books != NULL ) {
                $json_data = json_encode( $books );
            }
            return $json_data;
        }

        function getBooksToJson( $options = Array() ) {
            $books = $this->getBooksByUserIdAndName( $options );
            $json_data = 'null';
            if( $books != NULL ) {
                $json_data = json_encode( $books );
            }
            return $json_data;
        }

        function saveBooks( $newData, $options = Array() ) {
            //die($newData['bookId']);
            $useridArray = array( 'userid' => $this->getCurrentUsersId() );
            if( $this->access->currentUserIsAdmin() ) {
                $book = $this->getBooksByBookId( $newData['bookId'] );
                if( $book['userid'] != $useridArray['userid'] ) {
                    $useridArray['userid'] = $book['userid'];
                }
            }
            $sql = array_merge( $useridArray, $options );
            $data = array_merge( $useridArray, $newData );
            $f = $this->booksCollection->findOne( $sql );
            try {
                if( $f != NULL ) {
                    $this->booksCollection->update( array( '_id' => $f["_id"] ), $data );
                } else {
                    $this->booksCollection->insert( $data );
                }
                return TRUE;
            } catch( Exception $e ) {
                return $e;
            }
        }

        /*
        * returns the boolean value of isPublic, or NULL if there was an error
        */
        function changeBookAccess( $bookId ) {
            $bookId = cleanAlphaNum( $bookId );
            $res = $this->usersCollection->findOne( array( 'scrapbooks.id' => $bookId ) );
            if( !is_null( $res ) && sizeof( $res ) > 0 ) {
                $isPublic = NULL;
                foreach( $res['scrapbooks'] as $sb ) {
                    if( strtolower( $sb['id'] ) == strtolower( $bookId ) ) {
                        if( isset( $sb['isPublic'] ) ) {
                            $isPublic = ( $sb['isPublic'] == 'TRUE' ? 'FALSE' : 'TRUE' );
                        } else {
                            $isPublic = 'TRUE';
                        }
                        break;
                    }
                }
                if( is_null( $isPublic ) ) {
                    return NULL;
                }
                $res = $this->usersCollection->update(
                    array( 'scrapbooks.id' => $bookId ),
                    array( '$set' => array( 'scrapbooks.$.isPublic' => $isPublic ) ),
                    array( 'safe' => TRUE )
                );

                return ( $isPublic == 'TRUE' ? TRUE : FALSE );
            }
            return NULL;
        }

        function saveBooksFromJson( $jsondata, $bookId ) {
            $data = json_decode( $jsondata, TRUE );
            if( $bookId == $data['bookId'] ) {
                $options = array( 'bookId' => $data['bookId'],
                                  'pages'  => $data["pages"]
                );
                if( !isset( $data['isPublic'] ) ) {
                    $data['isPublic'] = FALSE;
                }
                return $this->saveBooks( $data, $options );
            }
            return FALSE;
        }

        function deleteBook( $bookId ) {
            $sql = array( 'userid' => $this->getCurrentUsersId(),
                          'bookId' => $bookId
            );
            $f = $this->booksCollection
                ->find( $sql )
                ->fields( array( '_id' => 1 ) );

            foreach( $f as $ids ) {
                $this->booksCollection->remove( array( '_id' => $ids['_id'] ), TRUE );
            }
            $this->deleteScrapBook( $bookId );
        }

        function findBook( $bookId ) {
            $f = $this->booksCollection->find( array( 'bookId' => $bookId ) );
            $f->sort( array( 'pages' => 1 ) );

            $data = Array();
            while( $f->hasNext() ) {
                $data[] = $f->getNext();
            }

            return $data;
        }

        function findBookToJson( $bookId ) {
            $data = $this->findBook( $bookId );
            $json_data = json_encode( $data );
            return $json_data;
        }

        function updateBook( $options ) {
            if( isset( $options['bookId'] ) && isset( $options['newName'] ) ) {
                $options['bookId'] = cleanAlphaNum( $options['bookId'] );
                $this->usersCollection->update(
                    array( "_id"           => $this->getCurrentUsersMongoId(),
                           'scrapbooks.id' => $options['bookId']
                    ),
                    array( '$set' => array( 'scrapbooks.$.name' => sanitize( $options['newName'] ) ) )
                );
            }
        }


        function deleteScrapBook( $bookId ) {
            $uid = $this->getCurrentUsersMongoId();
            $user_data = $this->usersCollection->findOne( array( '_id' => $uid ) );
            $tbook = array();
            foreach( $user_data['scrapbooks'] as $book ) {
                if( $book['id'] == $bookId ) {
                    continue;
                }
                $tbook[] = $book;
            }
            $this->usersCollection->update( array( "_id" => $uid ),
                                            array( '$set' => array( 'scrapbooks' => $tbook ) ),
                                            array( 'upsert' => TRUE )
            );

        }

        function findBookcasesToJson( $bookIds ) {
            //     $uid = $this->getCurrentUsersMongoId();
            $bookIds = explode( "%", $bookIds );
            $books = array();
            foreach( $bookIds as $bookId ) {
                if( !$this->access->currentUserCanViewBook( $bookId ) ) {
                    continue;
                }

                $query = array( 'userid' => $this->getCurrentUsersId(),
                                'bookId' => $bookId,
                                'pages'  => '0' );
                $book = $this->booksCollection->findOne( $query );
                $books[] = array( 'id'   => $bookId,
                                  'book' => $book );
            }

            $json_data = json_encode( $books );
            return $json_data;
        }

    }

?>
