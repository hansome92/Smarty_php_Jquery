<?php
    require_once 'mysqlDatabase.php';

    class item extends mysqlDatabase {
        public $user;
        public $subcategories = array();

        function __construct() {
            parent::__construct();
        }

        function getItemsByCategory( $cat_id = 0 ) {
            if( !is_numeric( $cat_id ) || $cat_id < 0 ) {
                return array();
            }
            $result = array();
            switch( $cat_id ) {
                case 1:
                    //this returns all trinket
                    $query = ' SELECT * FROM items left join category on items.cat_id = category.id where `type` = \'trinket\' ';
                    break;
                case 7:
                    //this returns all border
                    $query = ' SELECT * FROM items left join category on items.cat_id = category.id where `type` = \'border\' ';
                    break;
                case 56:
                    //this returns all font
                    $query = ' SELECT * FROM items left join category on items.cat_id = category.id where `type` = \'font\' ';
                    break;
                case 57:
                    //this returns all wallpaper
                    $query = ' SELECT * FROM items left join category on items.cat_id = category.id where `type` = \'wallpaper\' ';
                    break;
                case 58:
                    //this returns all treatment
                    $query = ' SELECT * FROM items left join category on items.cat_id = category.id where `type` = \'treatment\' ';
                    break;
                default:
                    $query = "SELECT * FROM items WHERE cat_id in (SELECT c1.id from category c1 where parent_id = {$cat_id} union select {$cat_id}) ";
                    break;
            }

            $res = $this->query( $query );
            //$res = $this->query( "SELECT * FROM items WHERE cat_id=$cat_id" );
            if( $res->num_rows > 0 ) {
                while( $sl = $res->fetch_assoc() ) {
                    $result[] = $sl;
                }
            }
            return $result;

        }
    }
