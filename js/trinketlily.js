var alphaDir = 1, dateDir = 1;

$( document ).ready( function() {
    fixBorderHeights();
    openid.init( 'openid_identifier' );
    //  album.getPublicAlbums();

    $( window ).resize( function() {
        fixBorderHeights();
    } );

    $( "#loginButton" ).click( function() {
        $( '#loginHolder' ).bPopup( {
                                        position: ['auto', 100],
                                        fadeSpeed: 'slow', //can be a string ('slow'/'fast') or int
                                        followSpeed: 1500, //can be a string ('slow'/'fast') or int
                                        modalColor: '#ffffff',
                                        opacity: 0.4
                                    } );
    } );
    $( '#closeOpenIdPopup' ).click( function() {
        $( '#loginHolder' ).bPopup().close();
    } );

    $( '#sortAlpha' ).click( function() {
        $( '.item_book' ).sortElements( function( a, b ) {
            return $.trim( $( a ).attr( 'bookname' ).toLowerCase() ) > $.trim( $( b ).attr( 'bookname' ).toLowerCase() ) ? alphaDir : -1 * alphaDir;
        } );
        alphaDir = alphaDir * -1;
    } );
    $( '#sortDate' ).click( function() {
        $( '.item_book' ).sortElements( function( a, b ) {
            return $.trim( $( a ).attr( 'bookid' ).toLowerCase() ) > $.trim( $( b ).attr( 'bookid' ).toLowerCase() ) ? dateDir : -1 * dateDir;
        } );
        dateDir = dateDir * -1;
    } );
} );

function fixBorderHeights() {
    var height = $( "#siteInner" ).height();
    $( "#leftBorder" ).height( height );
    $( "#rightBorder" ).height( height );
}

function doFacebookConnect() {
    window.location = url_prefix + 'fblogin.php';
}

jQuery.fn.sortElements = (function() {
    var sort = [].sort;
    return function( comparator, getSortable ) {
        getSortable = getSortable || function() {
            return this;
        };
        var placements = this.map( function() {
            var sortElement = getSortable.call( this ), parentNode = sortElement.parentNode, // Since the element itself will change position, we store its original DOM position. The easiest way is to have a 'flag' node:=,
                nextSibling = parentNode.insertBefore( document.createTextNode( '' ), sortElement.nextSibling );
            return function() {
                if( parentNode === this ) {
                    throw new Error( "You can't sort elements if any one is a descendant of another." );
                }
                // Insert before flag:
                parentNode.insertBefore( this, nextSibling );
                // Remove flag:
                parentNode.removeChild( nextSibling );
            };
        } );
        return sort.call( this, comparator ).each( function( i ) {
            placements[i].call( getSortable.call( this ) );
        } );
    };
})();