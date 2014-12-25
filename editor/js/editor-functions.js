var previouslyClickedType = '';
$( document ).ready( function() {
    //setup previous/next page functionality
    $( '.goToPreviousPage' ).click( function() {
        var page = $( '#scrapbookPageNumber' );
        var currentPage = parseInt( page.val() );
        savePage();
        if( currentPage > 0 ) {
            page.val( currentPage - 1 );
            gotoPage();
        }
    } );
    $( '.goToNextPage' ).click( function() {
        var page = $( '#scrapbookPageNumber' );
        var currentPage = parseInt( page.val() );
        savePage();
        page.val( currentPage + 1 );
        gotoPage();
    } );

    $( 'a.top_parent, .parent a, .child a' ).click( function() {
        var cat_id = this.id.substr( 4 );
        var cat_type = this.id.substr( 0, 4 );
        var type = $( this ).closest( '.root_cat' ).attr( 'data-type' );
        //dont load a category if its already loaded
        if( previouslyClickedType == type ) {
            return false;
        }
        $( '#menuh' ).find( '.selectedMenu' ).removeClass( 'selectedMenu' );
        $( this ).addClass( 'selectedMenu' );
        if( type == 'photo' ) {

            if( cat_type == 'cat_' ) {
                clickAlbums( 'allphotos' );
            } else {
                clickAlbums( cat_id );
            }
        } else {
            $.ajax( {
                        url: url_prefix + 'items/',
                        type: 'POST',
                        data: {cat_id: cat_id, type: type },
                        success: function( html ) {
                            $( '#editorVerticalContentHolder' ).find( '.content' ).each( function() {
                                $( this ).hide();
                            } );
                            switchToEditorFunction( type, html );
                        }
                    } );
        }
    } );

} );

// check if there is an overlap between two elements
function checkOverlap( a, b ) {
    function getPositions( elem ) {
        var pos, width, height;
        pos = $( elem ).position();
        width = $( elem ).width();
        height = $( elem ).height();
        return [
            [ pos.left, pos.left + width ],
            [ pos.top, pos.top + height ]
        ];
    }

    function comparePositions( p1, p2 ) {
        var r1, r2;
        r1 = p1[0] < p2[0] ? p1 : p2;
        r2 = p1[0] < p2[0] ? p2 : p1;
        return r1[1] > r2[0] || r1[0] === r2[0];
    }

    function doTheCheck( a, b ) {
        var pos1 = getPositions( a ), pos2 = getPositions( b );
        return comparePositions( pos1[0], pos2[0] ) && comparePositions( pos1[1], pos2[1] );
    }

    return doTheCheck( a, b );
}

// apply image filter
function applyFilter( index, filter ) {
    applyFilter( index, filter, null );
}

// apply image filter
function applyFilter( index, filter, target ) {
    var obj = canvas.getActiveObject();
    if( target != null ) {
        obj = target;
    }
    if( obj == null ) {
        return;
    }
    obj.filters[index] = filter;
    obj.applyFilters( canvas.renderAll.bind( canvas ) );
}

function removeFilter( target ) {
    var obj = canvas.getActiveObject();
    if( target != null ) {
        obj = target;
    }
    if( obj == null ) {
        return;
    }
    for( var i = 0; i < 17; i++ ) {
        obj.filters[i] = 0;
    }
    obj.filters.length = 0;
    obj.applyFilters( canvas.renderAll.bind( canvas ) );
}

// get to background from objects
function getDistances() {
    if( g_selBackground == null ) {
        return;
    }
    var items = canvas.getObjects();
    var idx;
    var cnt = items.length;
    var px = g_selBackground.get( 'left' );
    var py = g_selBackground.get( 'top' );
    var dx, dy;

    for( idx = 0; idx < cnt; idx++ ) {
        if( items[idx] == g_selBackground ) {
            continue;
        }
        dx = items[idx].get( 'left' );
        dy = items[idx].get( 'top' );
        moveDistancex[idx] = px - dx;
        moveDistancey[idx] = py - dy;
    }
}

// set distance to background from objects.
function setDistances() {
    if( moveDistancex.length == 0 ) {
        return;
    }
    var items = canvas.getObjects();
    var cnt = items.length;
    for( var idx = 0; idx < cnt; idx++ ) {
        if( items[idx] == g_selBackground ) {
            continue;
        }
        items[idx].set( 'left', g_selBackground.get( 'left' ) - moveDistancex[idx] );
        items[idx].set( 'top', g_selBackground.get( 'top' ) - moveDistancey[idx] );
    }
}

// set filters
function setFilters( selObj, index ) {
    switch( parseInt( index ) ) {
        case( 0 ):
            applyFilter( index, new f.jarques(), selObj );
            break;
        case( 1 ):
            applyFilter( index, new f.lomo(), selObj );
            break;
        case( 2 ):
            applyFilter( index, new f.love(), selObj );
            break;
        case( 3 ):
            applyFilter( index, new f.nostalgia(), selObj );
            break;
        case( 4 ):
            applyFilter( index, new f.oldBoot(), selObj );
            break;
        case( 5 ):
            applyFilter( index, new f.orangePeel(), selObj );
            break;
        case( 6 ):
            applyFilter( index, new f.pinhole(), selObj );
            break;
        case( 7 ):
            applyFilter( index, new f.sinCity(), selObj );
            break;
        case( 8 ):
            applyFilter( index, new f.sunrise(), selObj );
            break;
        case( 9 ):
            applyFilter( index, new f.vintage(), selObj );
            break;
        case( 10 ):
            applyFilter( index, new f.herMajesty(), selObj );
            break;
        case( 11 ):
            applyFilter( index, new f.hemingway(), selObj );
            break;
        case( 12 ):
            applyFilter( index, new f.hazyDays(), selObj );
            break;
        case( 13 ):
            applyFilter( index, new f.grungy(), selObj );
            break;
        case( 14 ):
            applyFilter( index, new f.glowingSun(), selObj );
            break;
        case( 15 ):
            applyFilter( index, new f.crossProcess(), selObj );
            break;
        case( 16 ):
            applyFilter( index, new f.clarity(), selObj );
            break;
    }
}

function switchToEditorFunction( type, html ) {
    switch( type ) {
        case 'trinket':
            $( "#editorVerticalContentScroller" ).show().html( html );
            initializeTrinkets();
            break;
        case 'border':
            $( '#editorVerticalborders' ).show().html( html );
            initializeBorders();
            break;
        case 'treatment':
            $( '#editorVerticalImageFilter' ).show().html( html );
            initializeFilters();
            break;
        case 'wallpaper':
            $( '#editorVerticalwallpaper' ).show().html( html );
            initializeWallpapers();
            break;
        case 'font':
            $( "#editorVerticalFonts" ).show().html( html );
            initializeFonts();
            break;
        case 'photo':
            $( "#editorVerticalAlbums" ).show().html( html );
            initializePhotos();
            break;
    }
}
