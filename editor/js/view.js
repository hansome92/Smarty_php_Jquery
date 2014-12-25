/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var canvasArray = [];

$( document ).ready( function() {

    $( '#closeEditor' ).click( function() {
        window.parent.$( '#cboxClose' ).click();
    } );

    loadBooks();
} );

function loadBooks() {
    var saveurl = "save.php";
    $( document ).ajaxStart( function() {
        $( '#loading-canvas' ).show();
    } );
    $( document ).ajaxStop( function() {
        $( '#loading-canvas' ).hide();
    } );
    $.ajax( { type: "POST",
                url: saveurl,
                cache: false,
                data: {method: 'view', bookId: $( "#book_id" ).val(), s: (secureView.enabled ? secureView.hash : '')},
                success: function( msg ) {
                    if( msg == "null" && msg == "{}" ) {
                        alert( 'no data' );
                        return;
                    }

                    try {
                        var data = JSON.parse( msg );
                        loadData( data );
                    } catch( e ) {
                        alert( msg );
                    }
                }
            } );
}
var globdata;
function loadData( data ) {
    globdata = data;
    canvasArray.length = 0;
    canvasArray = [];

    var len = data.length * 2;
    var width = 520;
    var height = 590;

    var sheight = $( window ).height();
    var swidth = $( window ).width();

    $( '#cover-page' ).addClass( 'hard' ).append( '<canvas id="page0" width="' + width + '" height="' + height + '"></canvas>' );
    var startpos = 0;
    if( len > 0 ) {
        if( data[0].pages == 0 ) {
            startpos = 2;
        }
    }

    if( startpos == 2 ) {
        var page0 = new fabric.Canvas( "page0", {width: width, height: height} );
        loadPage( page0, data[0], width, height, -1 );
    }
    // create div + canvas
    var book = '';
    for( var i = startpos; i < len; i++ ) {
        var el = '<div ' + (i == startpos ? ' class="hard" ' : '') + '>';
        el += '<canvas id="A' + i + '" width="' + width + '" height="' + height + '"></canvas>';
        el += '</div>';
        book += el;
    }

    $( '#book' ).append( book );

    for( i = startpos; i < len; i += 2 ) {

        canvasArray[i] = new fabric.Canvas( "A" + i, { width: width, height: height} );
        var j = i % 2;
        loadPage( canvasArray[i], data[(i - j) / 2], width, height, j );
    }
    for( i = startpos + 1; i < len; i += 2 ) {
        canvasArray[i] = new fabric.Canvas( "A" + i, { width: width, height: height} );
        j = i % 2;
        loadPage( canvasArray[i], data[(i - j) / 2], width, height, j );
    }

    $( "#book" ).turn( {
                           width: width * 2,
                           height: height,
                           autoCenter: false
                       } );
    changeBackground( 1 );

    $( "#book" ).bind( "turning", function( event, page, pageObject ) {
        changeBackground( page );
    } );
}

function changeBackground( page ) {
    bgImageUrl = $( '.p' + page ).attr( 'backgroundimage' );
    bgColor = $( '.p' + page ).attr( 'backgroundcolor' );
    if( bgImageUrl == 'none' ) {
        $( 'html' ).css( {'background-color': bgColor, 'background-image': ''} );
    } else {
        $( 'html' ).css( {'background-color': 'transparent', 'background-image': 'url(' + bgImageUrl + ')'} );
    }
}

// load Page
function loadPage( canvas, data, width, height, isleft ) {
    //canvas.backgroundColor = 'rgba(48,62,71,1)';
    if( isNull( data.backgroundImage ) ) {
        if( !isNull( data.backgroundcolor ) ) {
            $( canvas.getElement() ).parent().parent().attr( 'backgroundcolor', data.backgroundcolor ).attr( 'backgroundimage', 'none' );
            //canvas.backgroundColor= data.backgroundcolor;
        }
    } else {
        //canvas.setBackgroundImage( data.backgroundImage, canvas.renderAll.bind( canvas ) );
        $( canvas.getElement() ).parent().parent().attr( 'backgroundcolor', 'transparent' ).attr( 'backgroundimage', data.backgroundImage )
    }

    var page_zoom = parseFloat( data.zoom );
    var book_posx = width;
    var default_zoom = 1;
    if( isleft == 1 ) {
        book_posx = 0;
    }
    if( isleft == -1 ) {
        book_posx = book_posx / 2;
    }
    var book_posy = height / 2;

    var items = data.images;
    $.each( items, function( index, item ) {
        if( item.type == "text" ) {
            var text = new fabric.Text( decodeURIComponent( (item.text + '').replace( /\+/g, '%20' ) ), {
                fontFamily: item.fontFamily,
                fill: item.fill,
                left: parseFloat( item.left ) * default_zoom / page_zoom + book_posx,
                top: parseFloat( item.top ) * default_zoom / page_zoom + book_posy,
                angle: item.angle,
                scaleX: parseFloat( item.scaleX ) * default_zoom / page_zoom,
                scaleY: parseFloat( item.scaleY ) * default_zoom / page_zoom
            } );
            text.textAlign = item.textAlign;
            text.selectable = false;
            canvas.insertAt( text, index, true );

        } else if( item.type == "image" ) {
            fabric.Image.fromURL( item.src, function( obj ) {
                obj.set( 'left', parseFloat( item.left ) * default_zoom / page_zoom + book_posx );
                obj.set( 'top', parseFloat( item.top ) * default_zoom / page_zoom + book_posy );
                obj.set( 'scaleX', parseFloat( item.scaleX ) * default_zoom / page_zoom );
                obj.set( 'scaleY', parseFloat( item.scaleY ) * default_zoom / page_zoom );
                obj.setAngle( item.angle );
                setFilters( canvas, obj, item.filter );
                obj.selectable = false;
                canvas.insertAt( obj, index, true );
            } );

        } else if( item.type == "group" || item.type == "background" ) {
            var objarray = [];
            loadGroup( canvas, objarray, 0, item, index, {
                default_zoom: default_zoom,
                page_zoom: page_zoom,
                left: book_posx,
                top: book_posy
            } );
        }

    } );

    canvas.renderAll();
}

function loadGroup( canvas, subitems, index, item, zorder, settings ) {

    try {
        var items = item.items;
        fabric.Image.fromURL( items[index].src, function( obj ) {

            obj.set( 'left', items[index].left );
            obj.set( 'top', items[index].top );
            obj.set( 'scaleX', parseFloat( items[index].scaleX ) );
            obj.set( 'scaleY', parseFloat( items[index].scaleY ) );

            obj.setAngle( items[index].angle );
            setFilters( canvas, obj, items[index].filter );
            subitems.push( obj );

            if( index < items.length - 1 ) {
                index += 1;
                loadGroup( canvas, subitems, index, item, zorder, settings );
            } else {

                var group = new fabric.Group( subitems );

                group.set( 'left', parseFloat( item.left ) * settings.default_zoom / settings.page_zoom + settings.left );
                group.set( 'top', parseFloat( item.top ) * settings.default_zoom / settings.page_zoom + settings.top );
                group.set( 'scaleX', parseFloat( item.scaleX ) * settings.default_zoom / settings.page_zoom );
                group.set( 'scaleY', parseFloat( item.scaleY ) * settings.default_zoom / settings.page_zoom );
                group.setAngle( item.angle );
                group.selectable = false;

                canvas.insertAt( group, zorder, true );
            }
        } );
    } catch( e ) {
        alert( e );
        return;
    }
}

// set filters
function setFilters( canvas, selObj, index ) {
    var f = fabric.Image.filters;
    if( index == 0 ) {
        applyFilter( canvas, index, new f.jarques(), selObj );
    } else if( index == 1 ) {
        applyFilter( canvas, index, new f.lomo(), selObj );
    } else if( index == 2 ) {
        applyFilter( canvas, index, new f.love(), selObj );
    } else if( index == 3 ) {
        applyFilter( canvas, index, new f.nostalgia(), selObj );
    } else if( index == 4 ) {
        applyFilter( canvas, index, new f.oldBoot(), selObj );
    } else if( index == 5 ) {
        applyFilter( canvas, index, new f.orangePeel(), selObj );
    } else if( index == 6 ) {
        applyFilter( canvas, index, new f.pinhole(), selObj );
    } else if( index == 7 ) {
        applyFilter( canvas, index, new f.sinCity(), selObj );
    } else if( index == 8 ) {
        applyFilter( canvas, index, new f.sunrise(), selObj );
    } else if( index == 9 ) {
        applyFilter( canvas, index, new f.vintage(), selObj );
    } else if( index == 10 ) {
        applyFilter( canvas, index, new f.herMajesty(), selObj );
    } else if( index == 11 ) {
        applyFilter( canvas, index, new f.hemingway(), selObj );
    } else if( index == 12 ) {
        applyFilter( canvas, index, new f.hazyDays(), selObj );
    } else if( index == 13 ) {
        applyFilter( canvas, index, new f.grungy(), selObj );
    } else if( index == 14 ) {
        applyFilter( canvas, index, new f.glowingSun(), selObj );
    } else if( index == 15 ) {
        applyFilter( canvas, index, new f.crossProcess(), selObj );
    } else if( index == 16 ) {
        applyFilter( canvas, index, new f.clarity(), selObj );
    }

}

function applyFilter( canvas, index, filter, target ) {

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