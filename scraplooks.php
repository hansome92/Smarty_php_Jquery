<?php
    require_once 'mysqlDatabase.php';

    class scraplooks extends mysqlDatabase {
        public $defaultScrapbookCover;

        function __construct() {
            parent::__construct();
            $this->defaultScrapbookCover = 'scraplookDefaultCover.png';
        }


        function getCoverUrlByName( $scraplook ) {
            if( strtolower( trim( $scraplook ) ) == 'default' ) {
                return $this->defaultScrapbookCover;
            }
            $scraplook = $this->escapeString( $scraplook );
            $scraplookRes = $this->query( " SELECT `scraplookCoverImage` FROM `scraplooks` WHERE `scraplookName` = '{$scraplook}' " );
            $scraplookArr = $this->sqlToArray( $scraplookRes );
            if( empty( $scraplookArr ) ) {
                return $this->defaultScrapbookCover;
            }
            return $scraplookArr[0]['scraplookCoverImage'];
        }

        /*
                function getFacebookAlbumCover( $albumId ) {
                    $albumId = $this->escapeString( $albumId );
                    $outerQuery = "SELECT {$this->getFromPhotos} FROM photos WHERE photos.albumid = '{$albumId}' and photos.height > 100 and photos.height < 500 ORDER BY photos.height DESC limit 1 ";
                    //GROUP BY photos
                    $albumRes = $this->query( $outerQuery );
                    if( !$albumRes ) {
                        var_dump( $this->error() );
                        return '';
                    }
                    return $this->sqlToArray( $albumRes );
                }

        */

    }
