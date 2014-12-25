<?php

    require_once 'mysqlDatabase.php';

    class PendingDownload extends mysqlDatabase {

        function __construct() {
            parent::__construct();
        }

        function addUrl( $userId, $source, $height, $width ) {
            $userId = cleanAlphaNum($userId);
            $insertDownload = "INSERT INTO `pendingDownload`(`userId`,`source`,`height`,`width`) VALUES ('{$userId}','{$source}',{$height},{$width})";
            $this->query( $insertDownload );
            $this->commit();
        }

        function getPendingList( $limit = 10 ) {
            $selectPending = "SELECT * FROM `pendingDownload` WHERE 1 LIMIT 0,{$limit}";
            $res = $this->query( $selectPending );
            $returnResult = array();
            while( $result = $res->fetch_assoc() ) {
                $returnResult[] = $result;
            }
            return $returnResult;
        }

        function removePendingElement( $pending_id ) {
            $this->query( "DELETE FROM `pendingDownload` WHERE `id`=".intval( $pending_id ) );
            $this->commit();
        }

        function uploadImage( $param ) {
            $userDir = realpath( dirname( __FILE__ ) )."/userpics/".$param['userId'];
            file_put_contents( $userDir."/".$param['filename'], file_get_contents( $param['source'] ) );
        }
    }