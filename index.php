<?php

    //#TODO: remove this and make it it's own cron job!!!
    require_once 'uploadImages.php';

    require_once 'include.php';


    if( isset( $_GET['p'] ) ) {
        $page = strtolower( cleanAlphaNum( $_GET['p'], TRUE ) );
    } else {
        $page = 'home';
    }


    if( $page == 'contact' ) {
        require_once( 'smarty/libs/SmartyBC.class.php' );
        $smarty = new SmartyBC();
    } else {
        require_once( 'smarty/libs/Smarty.class.php' );
        $smarty = new Smarty;
    }

    $_SESSION['loggedIn'] = ( isset( $_SESSION['loggedIn'] ) ? $_SESSION['loggedIn'] : FALSE );

    $cache = FALSE;
    $debug = FALSE;
    $clife = 3600;


    $smarty->debugging = $debug;
    $smarty->force_compile = !$cache;
    $smarty->caching = $cache;
    //$smarty->caching = 2;
    $smarty->cache_lifetime = $clife;

    $smarty->assign( 'cacheBust', $cacheBust );
    $smarty->assign( 'cacheBustVar', '&'.$cacheBustVar );

    $smarty->assign( 'page', $page );
    $smarty->assign( 'loggedIn', ( $_SESSION['loggedIn'] ? 'true' : 'false' ) );
    $smarty->assign( 'urlPrefix', URL_PREFIX );
    $smarty->assign( 'name', $page, TRUE );
    $smarty->assign( 'title', 'Trinketlily - '.ucwords( $page ).' - Online Scrapbooking', TRUE );

    $ajaxQueryPage = FALSE;

    switch( $page ) {
        case( 'items' ):
            $smarty->assign( 'category_exists', FALSE );
            if( isset( $_POST['cat_id'] ) && is_numeric( $_POST['cat_id'] ) ) {
                $type = ( isset( $_POST['type'] ) && !preg_match( ' /[^a-z]/i', $_POST['type'] ) && $_POST['type'] != '' ) ? $_POST['type'] : 'trinket';
                $smarty->assign( 'type', $type );
                $smarty->assign( 'cat_id', $_POST['cat_id'] );
                $smarty->assign( 'category_exists', TRUE );
                //#TODO: cache this extra long if its not going to be changing often, and isnt a users albums, i.e if it's trinkets or wallpapers, etc
                $smarty->display( 'items/items.tpl', $page.'_'.$_POST['cat_id'] );
            }
            $ajaxQueryPage = TRUE;
            break;
        case( 'editor.js' ):
            header( "content-type: application/javascript" );
            $smarty->force_compile = TRUE;
            $smarty->display( 'sitejs/editor.tpl' );
            $ajaxQueryPage = TRUE;
            break;
        case( 'editor.css' ):
            header( "content-type: text/css" );
            $smarty->display( 'sitecss/editor.tpl' );
            $ajaxQueryPage = TRUE;
            break;
        case( 'main.js' ):
            header( "content-type: application/javascript" );
            $smarty->display( 'sitejs/main.tpl' );
            $ajaxQueryPage = TRUE;
            break;
        case( 'main.css' ):
            header( "content-type: text/css" );
            $smarty->display( 'sitecss/main.tpl' );
            $ajaxQueryPage = TRUE;
            break;
        case( 'profile.js' ):
            header( "content-type: application/javascript" );
            $smarty->display( 'sitejs/main_profile.tpl' );
            $ajaxQueryPage = TRUE;
            break;
        case( 'album.js' ):
            header( "content-type: application/javascript" );
            $smarty->display( 'sitejs/main_album.tpl' );
            $ajaxQueryPage = TRUE;
            break;
        case( 'tlcommon.js' ):
            header( "content-type: application/javascript" );
            $smarty->display( 'sitejs/common.tpl' );
            $ajaxQueryPage = TRUE;
            break;
        case( 'tlcommon.css' ):
            header( "content-type: text/css" );
            $smarty->display( 'sitecss/common.tpl' );
            $ajaxQueryPage = TRUE;
            break;
        case( 'trinketlily_editor.js' ):
            header( "content-type: application/javascript" );
            //#TODO: change the cache lifetime for js + css to something larger
            //$smarty->force_compile = FALSE;
            //$smarty->caching = TRUE;
            //$smarty->cache_lifetime = 600;
            $smarty->force_compile = TRUE;
            $smarty->display( 'trinketlilyeditorjs/trinketlilyeditorjs.tpl' );
            $ajaxQueryPage = TRUE;
            break;
        case( 'trinketlily_editor.css' ):
            header( "content-type: text/css" );
            //#TODO: change the cache lifetime for js + css to something larger
            $smarty->force_compile = FALSE;
            $smarty->caching = TRUE;
            $smarty->cache_lifetime = 600;
            $smarty->force_compile = TRUE;
            $smarty->display( 'trinketlilyeditorcss/trinketlilyeditorcss.tpl' );
            $ajaxQueryPage = TRUE;
            break;
        case( 'fonts.js' ):
            header( "content-type: application/javascript" );
            //#TODO: change the cache lifetime for js + css to something larger
            $smarty->force_compile = FALSE;
            $smarty->caching = TRUE;
            $smarty->cache_lifetime = 600;
            $smarty->force_compile = TRUE;
            $smarty->display( 'fontsjs/fontsjs.tpl' );
            $ajaxQueryPage = TRUE;
            break;
        case( 'fonts.css' ):
            header( "content-type: text/css" );
            //#TODO: change the cache lifetime for js + css to something larger
            $smarty->force_compile = FALSE;
            $smarty->caching = TRUE;
            $smarty->cache_lifetime = 600;
            $smarty->force_compile = TRUE;
            $smarty->display( 'fontscss/fontscss.tpl' );
            $ajaxQueryPage = TRUE;
            break;
        /* pages without complex settings */
        case( 'scraplooks' ):
            break;
        case( 'contact' ):
            break;
        case( 'home' ):
            break;
        case( 'aboutus' ):
            break;
        case( 'album' ):
            $smarty->caching = FALSE;
            break;
        case( 'scrapbooks' ):
            $smarty->caching = FALSE;
            break;
        case( 'profile' ):
            $smarty->caching = FALSE;
            break;
        case ( 'gallery' ):
            $smarty->caching = FALSE;
            break;
        case ( 'scrapshare' ):
            $smarty->caching = FALSE;
            break;
        case ( 'view' ):
            $smarty->cache_lifetime = 3600;
            if( isset( $_GET['user'] ) ) {
                $page .= $_GET['user'];
            }
            break;

        default:
            $ajaxQueryPage = TRUE;
            header( 'Location: '.URL_PREFIX );
            break;
    }


    if( !$ajaxQueryPage ) {
        $smarty->display( 'siteMain.tpl', $page );
    }




?>