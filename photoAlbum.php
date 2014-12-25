<?php
    /**
     * File: photoAlbum.php
     * Created: 7/3/12  3:27 PM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */
    require_once 'user.php';
    class photoAlbum extends user {

        function __construct() {
            parent::__construct();
        }

        private function getExtension( $source ) {
            $i = strlen( $source ) - 1;
            $default_ext = "jpg";
            $ext = "";
            while( $source[$i] != "." && $i > 0 ) {
                $ext .= $source[$i];
                $i--;
                if( $i < 0 ) {
                    break;
                }
            }
            if( strlen( $ext ) > 5 ) {
                return $default_ext;
            } else {
                return strrev( $ext );
            }
        }

        /**
         * Add new album
         * @param $userId
         * @param $albumName
         */
        function addAlbum( $userId, $albumName ) {
            $albumName = sanitize( $albumName );
            $albumName = $this->sql->escapeString( $albumName );
            /*
            // this checks to make sure someone isn't making multiple albums with the same name- if they really want to, why not let them...
            $selectAlbum = "SELECT * FROM `photoalbums` WHERE `userId` = '{$userId}' AND `name` = '{$albumName}' and `deleted` = 0";
            $album = $this->sql->query( $selectAlbum );
            if( $album->num_rows < 1 ) {
                return -1;
            }
            */
            $insertAlbum = "INSERT INTO `photoalbums`(`userId`,`name`) VALUES('{$userId}','{$albumName}')";
            $this->sql->query( $insertAlbum );
            $aId = $this->sql->sql->insert_id;
            $this->sql->commit();
            return $aId;
        }

        /**
         * Remove album by album ID
         * @param $userId
         * @param $albumId
         */
        function removeAlbumFromAlbums( $userId, $albumIdarr ) {
            $albumIdarr = cleanAlphaNum( $albumIdarr );
            $albums = $this->sql->implodeArrayToSql( $albumIdarr );
            $albumQ = "UPDATE `photoalbums` SET `deleted`=1 WHERE `userId`='{$userId}' AND `albumId` in ({$albums}) ";
            $this->sql->query( $albumQ );
            $photoQ = "UPDATE `photos` SET `deleted`=1 WHERE `userId`='{$userId}' AND `albumid` in ({$albums})";
            $this->sql->query( $photoQ );
            $this->sql->commit();
            return $albumIdarr;
        }


        /**
         * Edit album name
         * @param $userId
         * @param $albumId
         * @param $name
         */
        function editAlbum( $userId, $albumId, $name ) {
            $userId = $this->sql->escapeString( $userId );
            $albumId = $this->sql->escapeString( $albumId );
            $name = $this->sql->escapeString( $name );
            $editAlbum = "UPDATE `photoalbums` SET `name`='{$name}' WHERE `userId`='{$userId}' AND `albumId`='{$albumId}'  AND `deleted`=0";
            $this->sql->query( $editAlbum );
            $this->sql->commit();
        }

        /**
         * Add photos to Album
         * @param       $userId
         * @param       $albumId
         * @param array $photoList
         */
        function movePhotoToAlbum( $userId, $albumId, $photoList = array() ) {
            //#TODO: rewrite this mess to be much more efficient
            $userId = $this->sql->escapeString( $userId );
            $albumId = $this->sql->escapeString( $albumId );
            $photoIds = $this->sql->implodeArrayToSql( $photoList );
            $qUpdatePhoto = "UPDATE `photos` SET `albumId`={$albumId} WHERE `photoId` in ({$photoIds}) AND `userId`='{$userId}'";
            $this->sql->query( $qUpdatePhoto );
            $this->sql->commit();

            return $photoList;
        }

        /**
         * Add photos to Album
         * @param       $userId
         * @param       $albumId
         * @param array $photoList
         */
        function removePhotoFromAlbums( $userId, $albumId, $photoList = array() ) {
            $userId = $this->sql->escapeString( $userId );
            $albumId = $this->sql->escapeString( $albumId );
            $photos = $this->sql->implodeArrayToSql( $photoList );
            // remove photo
            $deletePhoto = "UPDATE `photos` SET `deleted`=1 WHERE `albumId`={$albumId} AND `photoId` in ({$photos}) AND `userId`='{$userId}'";
            $this->sql->query( $deletePhoto );
            $this->sql->commit();

            return $photoList;
        }

        function addPhotoNew( $userId, $source, $albumId, $height, $width ) {
            $userId = $this->sql->escapeString( $userId );
            $source = $this->sql->escapeString( $source );
            $albumId = $this->sql->escapeString( $albumId );
            $height = $this->sql->escapeString( $height );
            $width = $this->sql->escapeString( $width );
            $insertPhoto = "INSERT INTO `photos`(`userId`,`albumId`,`source`,`height`,`width`) VALUES ('{$userId}',{$albumId},'{$source}',{$height},{$width})";
            $albumRes = $this->sql->query( $insertPhoto );
            $filename = md5( $this->sql->insert_id() ).".".$this->getExtension( $source );
            $updatePhoto = "UPDATE `photos` SET `filename`='{$filename}' WHERE `photoId`={$this->sql->insert_id()}";
            $this->sql->query( $updatePhoto );
            return $filename;
        }


    }
