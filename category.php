<?php
    require_once 'user.php';

    class category extends user {
        public $userid;
        public $rootCategories = Array();
        public $subcategories = array();

        function __construct() {
            parent::__construct();
            $this->userid = $this->getCurrentUsersId();
            $this->getCompleteCategoryArray();
        }

        function getCompleteCategoryArray() {
            $query = 'SELECT c1.*, c2.id id1, c2.parent_id parent_id1, c2.category_name category_name1, c2.type type1, c3.id id2, c3.parent_id parent_id2, c3.category_name category_name2, c3.type type2 '.
                ' FROM category c1 left join category c2 on c2.parent_id = c1.id left join category c3 on c3.parent_id = c2.id WHERE c1.parent_id=0 ORDER BY `order` ASC';
            $res = $this->sql->query( $query );
            if( $res->num_rows > 0 ) {
                while( $sl = $res->fetch_assoc() ) {

                    $root = array( 'id'            => $sl['id'],
                                   'parent_id'     => $sl['parent_id'],
                                   'category_name' => $sl['category_name'],
                                   'type'          => $sl['type'],
                    );

                    $sub1 = array( 'id'            => $sl['id1'],
                                   'parent_id'     => $sl['parent_id1'],
                                   'category_name' => $sl['category_name1'],
                                   'type'          => $sl['type1'],
                    );

                    $sub2 = array( 'id'            => $sl['id2'],
                                   'parent_id'     => $sl['parent_id2'],
                                   'category_name' => $sl['category_name2'],
                                   'type'          => $sl['type2'],
                    );

                    $this->rootCategories[$root['id']] = $root;

                    if( !is_null( $sub1['id'] ) ) {
                        $this->subcategories['sub'][$root['id']][$sub1['id']] = $sub1;
                        if( !is_null( $sub2['id'] ) ) {
                            $this->subcategories['sub_sub'][$sub1['id']][$sub2['id']] = $sub2;
                        }
                    }

                    if( $sl['type'] == 'photo' && !is_null( $this->userid ) ) {
                        $albums = $this->getAllCurrentUsersAlbums();
                        foreach( $albums as $album ) {
                            $this->subcategories['sub'][$root['id']][$album['albumid']] = array( 'id'            => $album['albumid'],
                                                                                                 'parent_id'     => $sl['id'],
                                                                                                 'category_name' => $album['name'],
                                                                                                 'type'          => 'photo' );
                        }
                    }
                }
            }

        }

        function getRootCategories() {
            $res = $this->query( "SELECT * FROM category WHERE parent_id=0" );
            if( mysql_num_rows( $res ) > 0 ) {
                while( $sl = mysql_fetch_assoc( $res ) ) {
                    $this->rootCategories[$sl['id']] = $sl;
                }
            }
        }

        function getSubCategories( $catId, $key = 'sub' ) {
            if( is_numeric( $catId ) && $catId > 0 ) {
                $res = $this->query( "SELECT * FROM category WHERE parent_id=$catId" );
                if( mysql_num_rows( $res ) > 0 ) {
                    while( $sl = mysql_fetch_assoc( $res ) ) {
                        $this->subcategories[$key][$catId][$sl['id']] = $sl;
                        $this->getSubCategories( $sl['id'], $key.'_sub' );
                    }
                }
            }
        }

        function getCompleteCategoryTree() {
            if( sizeof( $this->rootCategories ) < 1 ) {
                $this->getRootCategories();
            }
            foreach( $this->rootCategories as $rootCategory ) {
                $this->getSubCategories( $rootCategory['id'] );
            }
        }
    }
