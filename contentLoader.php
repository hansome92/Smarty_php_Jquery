<?php
    /**
     * File: contentLoader.php
     * Created: 9/17/12  10:01 PM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */
    require_once 'mysqlDatabase.php';
    class contentLoader extends mysqlDatabase {
        function __construct() {
            parent::__construct();
        }

        function getHome() {
            $res = $this->sql->query( 'select * from pages where section = \'home\'' );
            $home = array();
            while( $h = $res->fetch_assoc() ) {
                $home[$h['subsection']] = $h;
            }
            return $home;
        }

        function getScraplooks() {
            $res = $this->sql->query( 'select * from scraplooks' );
            $scrapLooks = array();
            while( $sl = $res->fetch_assoc() ) {
                $scrapLooks[] = Array( 'boardImage'  => $sl['scraplookImage'],
                                       'name'        => $sl['scraplookName'],
                                       'description' => $sl['scraplookText'],
                                       'videoUrl'    => $sl['scraplookVideoUrl'],
                                       'examplesUrl' => $sl['scraplookExamplesUrl'],
                                       'coverImage'  => $sl['scraplookCoverImage'],
                );
            }
            return $scrapLooks;
        }

        function getFooter() {
            $res = $this->sql->query( 'select * from pages where section = \'footer\'' );
            $foot = $res->fetch_assoc();
            return $foot['content'];
        }
    }