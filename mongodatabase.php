<?php
    require_once 'include.php';

    class mongodatabase {
        public $m;
        /** @var $db MongoDB */
        public $db;
        /** @var $fs MongoGridFS */
        public $fs;

        function __construct() {
            global $mongodbconnection;
            if( !is_null( $mongodbconnection ) ) {
                $this->m = $mongodbconnection;
            } else {
                $this->m = new MongoClient ( SERVER_HOST ); // connect
            }
            /** @var $this->db MongoDB */
            $this->db = $this->m->selectDB( "trinketlily" );
        }

        /**@return MongoGridFS */
        function getGridFS( $namespace ) {
            $this->fs = $this->db->getGridFs( $namespace );
            return $this->fs;
        }

        function setGridFS( $gridFs ) {
            $this->fs = $gridFs;
        }

        function uploadFileIfDoesntExist( $domInputTagName, $metaInformationArray, $gridFs ) {
            /**@var MongoGridFS $gridFs */
            $id = NULL;
            $md5 = md5_file( $_FILES[$domInputTagName]['tmp_name'] );
            $file = $gridFs->findOne( array( 'md5' => $md5 ) );
            if( $file ) {
                /** @var MongoId $mongoId */
                $mongoId = $file->file['_id'];
                $id = $mongoId->{'$id'};
            } else {
                $id = $gridFs->storeUpload( $domInputTagName, $metaInformationArray );
            }
            return $id;
        }

        function sanitizeArrayKeys( $input ) {
            //#TODO: THIS NEEDS TO SANITIZE INPUT
            return $input;
        }
    }
