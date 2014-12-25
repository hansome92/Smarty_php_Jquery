/*
 * 07/10/2012 Helmut.
 */
var canvas, fabric, page_zoom, isDelay;
var g_selBackground = 1;
var moveDistancex = [];
var moveDistancey = [];
var book_width = 1050;
var book_height = 600;
var book_posx = 425;
var book_posy = 315;
var default_zoom = 0.6;
var g_trinketsInDiv, g_fontInDiv;
var g_UndoObject = null;
var bookPageImagePath = "images/book.png";
var bookFrontCoverPath = "images/book_cover.png";
var bookBorder = "images/wallpaper_border.png";
var save_string = "Do you want to save changes to this page?";
var SELECT_MSG = "You must select something before you can send it to the back.";
var save_filed_string = "save failed...";
var saveurl = "save.php";
var issaved = false;
var isPreview = false;
var jcrop;

$( document ).ready( function() {
    Cufon.now();

    g_UndoObject = new Undo();

    $( '#cropBtn' ).click( function() {
        launchCropTool();
    } );

    // Prevent text selection
    $( '*' ).live( 'selectstart', function( e ) {
        if( $( '#addTextBox' ).is( ":focus" ) ) {
            return true;
        }

        return false;
    } );
    //keep the page scrolled up to the top
    $( 'body' ).scroll( function() {
        currentScrollTop = $( "#div" ).scrollTop();
    } );

    //initialize font editor components
    $( '#text-align-container' ).find( '.text-align-span' ).live( 'click', function() {
        $( '#text-align-container' ).find( '.text-align-span' ).each( function() {
            $( this ).removeClass( 'text-align-selected' );
            var src = $( this ).css( 'background-image' );
            if( src.indexOf( '_selected' ) >= 0 ) {
                $( this ).css( 'background-image', src.replace( '_selected.png', '.png' ) );
            }
        } );
        $( this ).css( 'background-image', $( this ).css( 'background-image' ).replace( '.png', '_selected.png' ) ).addClass( 'text-align-selected' );

        var id = $( this ).attr( 'id' );
        var text_aligns = id.split( '-' );

        $( '#txtFont' ).css( 'text-align', text_aligns[1] );
        $( '#editorVerticalFonts' ).find( '.fonts' ).each( function() {
            $( this ).find( 'textarea' ).css( 'text-align', text_aligns[1] );
        } );

        //add Canvas text
        $( '#addTextBox' ).css( 'text-align', text_aligns[1] );
        var sel_text = canvas.getActiveObject();
        if( sel_text.type == "text" ) {
            if( text_aligns[1] == "justify" ) {
                text_aligns[1] = "left";
            }
            sel_text.textAlign = text_aligns[1].toLowerCase();
            canvas.renderAll();
        }
    } );
    $( 'textarea#txtFont' ).live( 'click',function() {
        if( $( this ).val().toUpperCase() == ('Type your text here...').toUpperCase() ) {
            $( this ).val( '' );
        }
    } ).live( 'blur', function() {
                  if( $( this ).val() == '' ) {
                      $( this ).val( 'Type your text here...' );
                  }
              } );
    $( '#fontColorSelectorLabel' ).live( 'click', function() {
        $( '#fontColorSelector' ).click();
    } );

    $( '#backgroundColorSelectorLabel' ).live( 'click', function() {
        $( '#backgroundColorSelector' ).click();
    } );

    if( $( '#editorScrapbookCanvas' ).html() == null ) {
        return;
    }
    canvas = new fabric.Canvas( 'editorScrapbookCanvas' );
    canvas.selection = false;
    canvas.backgroundColor = 'rgba(48,62,71,1)';
    canvas.renderAll();

    //allow deleting canvas objects with the delete key
    $( 'html' ).keyup( function( e ) {
        if( e.keyCode == 46 || e.keyCode == 8 ) {
            if( $( '#addTextBox' ).is( ":focus" ) ) {
                return false;
            }
            var selected = canvas.getActiveObject();
            if( selected != g_selBackground ) {
                g_UndoObject.action = "delete-object";
                g_UndoObject.curItem = null;
                canvas.remove( selected );
            }
        }
    } );

    $( '#txtFont' ).live( 'focus', function() {
        canvas.deactivateAll();
    } );

    canvas.observe( 'object:selected', function( e ) {
        g_UndoObject.setUndoItem( e.target );
        if( e.target != g_selBackground ) {
            canvas.bringToFront( e.target );
        }

        if( g_selBackground == e.target ) {
            getDistances();
            g_UndoObject.undoItem = '{"left":"' + g_selBackground.get( 'left' ) + '",';
            g_UndoObject.undoItem += '"top":"' + g_selBackground.get( 'top' ) + '"}';
        }
        $( '#addTextBox' ).hide();
        if( e.target.type == "text" ) {
            positionTextBox( e.target );
            var colpick = $( '#' + $( '#fontColorSelector' ).data( 'colorpickerId' ) );
            colpick.find( '.colorpicker_current_color, .colorpicker_new_color' ).add( '#fontColorSelector div' ).css( 'background-color', e.target.fill );
            $( '#addTextBox' ).show();
        }

    } );

    canvas.observe( 'object:modified', function( e ) {
        dropped = e.target;
        droppedLeft = dropped.get( 'left' );
        droppedTop = dropped.get( 'top' );

        droppedWidth = 0 - dropped.getWidth() / 4;
        droppedHeight = 0 - dropped.getHeight() / 4;

        canvasHeight = canvas.getHeight();
        canvasWidth = canvas.getWidth();

        g_UndoObject.action = "move-object";
        if( e.target == g_selBackground ) {
            g_UndoObject.action = "move-background";
        }
        g_UndoObject.curItem = e.target;
        if( droppedLeft > canvasWidth || droppedLeft < droppedWidth || droppedTop > canvasHeight || droppedTop < droppedHeight ) {
            if( e.target != g_selBackground ) {
                //g_UndoObject.setUndoItem(e.target);
                g_UndoObject.action = "delete-object";
                g_UndoObject.curItem = null;
                canvas.remove( e.target );
            }
        }
        issaved = true;

    } );
    canvas.observe( 'object:added', function( e ) {
        // undo/redo
        if( g_UndoObject.action != "" ) {
            g_UndoObject.curItem = e.target;
        }

        if( e.target != g_selBackground ) {
            e.target.hasRotatingPoint = true;
        }
        $( '#addTextBox' ).hide();
    } );
    // default load book.png loaded
    loadPage();

    g_trinketsInDiv = false;
    g_fontInDiv = false;

    canvas.observe( 'object:moving', function( e ) {
        issaved = true;
        if( g_selBackground == e.target ) {
            setDistances();
        }
        if( e.target.type == "text" ) {
            $( '#addTextBox' ).hide();
        }
    } );
    canvas.observe( 'mouse:down', function( e ) {
        try {
            if( e.target.type == "text" ) {
                positionTextBox( e.target );
                $( '#addTextBox' ).show();
            }
        } catch( e ) {
            $( '#addTextBox' ).hide();
        }
    } );
    // search tager on canvas
    canvas.findTarget = (function( originalFn ) {
        return function() {
            var target = originalFn.apply( this, arguments );
            if( target ) {
                if( this._hoveredTarget !== target ) {
                    canvas.fire( 'object:over', {target: target} );
                    if( this._hoveredTarget ) {
                        canvas.fire( 'object:out', {target: this._hoveredTarget} );
                    }
                    this._hoveredTarget = target;
                }
            } else if( this._hoveredTarget ) {
                canvas.fire( 'object:out', {target: this._hoveredTarget} );
                this._hoveredTarget = null;
            }
            return target;
        };
    })( canvas.findTarget );

    //allow editing of text thats already been added
    $( '#addTextBox' ).keyup( function( event ) {
        var obj = canvas.getActiveObject();
        if( obj.type == "text" ) {
            obj.setText( $( '#addTextBox' ).val() );
        }
        canvas.renderAll();
    } );

    // Top menu buttons
    $( '#saveProgress' ).click( function() {
        savePage();
    } );
    $( '.closeEditor' ).click( function() {
        savePage();
        window.parent.$( '#cboxClose' ).click();
    } );
    $( '#inEditorPreview' ).click( function() {
        addPreview( $( '#book_id' ).val() );
    } );

    initializeTrinkets();
    initializeFilters();
    initializeBorders();
    initializeFonts();
    initializeWallpapers();
    initializeZoomScrollbar();

    //hopefully ensure that fonts are already loaded and correctly initialized
    Cufon.CSS.ready( function() {
        canvas.renderAll();
        setTimeout( function() {
            canvas.renderAll();
        }, 300 );
    } );

    $( '#send_to_back' ).click( function() {
        var selObj = canvas.getActiveObject();
        if( selObj == null || selObj == g_selBackground ) {
            selObj = canvas.getActiveGroup();
        }
        if( selObj == null || selObj == g_selBackground ) {
            alert( SELECT_MSG );
            return;
        }
        canvas.sendToBack( selObj );
        canvas.sendToBack( g_selBackground );
    } );

    $( '#scrapbookPageNumber' ).change( function() {
        loadPage();
    } );

    $( '#undoBtn' ).click( function() {
        g_UndoObject.runUndo();
    } );
    $( '#redoBtn' ).click( function() {
        g_UndoObject.runRedo();
    } );

} );
// Prevent the backspace key from navigating back.
$( document ).unbind( 'keydown' ).bind( 'keydown', function( event ) {
    var doPrevent = false;
    if( event.keyCode === 8 ) {
        var d = event.srcElement || event.target;
        if( (d.tagName.toUpperCase() === 'INPUT' && (d.type.toUpperCase() === 'TEXT' || d.type.toUpperCase() === 'PASSWORD')) || d.tagName.toUpperCase() === 'TEXTAREA' ) {
            doPrevent = d.readOnly || d.disabled;
        } else {
            doPrevent = true;
        }
    }
    if( doPrevent ) {
        event.preventDefault();
    }
} );

// convert canvas to json files
function getCanvasToJson() {
    var obj = {
        bookId: $( '#book_id' ).val(),
        pages: $( "#scrapbookPageNumber" ).val(),
        zoom: default_zoom,
        backgroundImage: canvas.backgroundImage.src,
        backgroundcolor: canvas.backgroundColor,
        images: getCanvasObjectsToJson()
    };
    return JSON.stringify( obj );
}

function getCanvasObjectsToJson() {
    var items = canvas.getObjects();
    var temp = [];
    $.each( items, function( index, item ) {
        console.log( item );
        var type = item.type;
        if( type == "image" ) {
            temp[temp.length] = getImageToJson( item )
        } else if( type == "text" ) {
            temp[temp.length] = getTextToJson( item )
        } else if( type == "group" ) {
            temp[temp.length] = getGroupToJson( item )
        }
    } );
    return temp;
}

function getGroupToJson( obj ) {
    var px = (obj.get( 'left' ) - g_selBackground.get( 'left' )) / page_zoom * default_zoom;
    var py = (obj.get( 'top' ) - g_selBackground.get( 'top' )) / page_zoom * default_zoom;
    var ajaxObj = {
        type: (obj == g_selBackground) ? 'background' : 'group',
        left: px,
        top: py,
        scaleX: obj.get( 'scaleX' ) / page_zoom * default_zoom,
        scaleY: obj.get( 'scaleY' ) / page_zoom * default_zoom,
        angle: obj.getAngle(),
        items: []
    };
    var items = obj.getObjects();
    $.each( items, function( index, item ) {
        ajaxObj.items[ajaxObj.items.length] = getImageToJson( item, true );
    } );
    return ajaxObj;
}

function getImageToJson( img, isGroup ) {
    var j = img.toJSON();
    var px = (j.left - g_selBackground.get( 'left' )) / page_zoom * default_zoom;
    var py = (j.top - g_selBackground.get( 'top' )) / page_zoom * default_zoom;
    var filter = -1;
    for( var i = 0; i < 17; i++ ) {
        if( j.filters[i] != 0 && !isNull( j.filters[i] ) ) {
            filter = i;
            break;
        }
    }
    var obj = {
        type: j.type,
        left: px,
        top: py,
        src: j.src,
        scaleX: isGroup ? (j.scaleX) : (j.scaleX / page_zoom * default_zoom),
        scaleY: isGroup ? (j.scaleY) : (j.scaleY / page_zoom * default_zoom),
        angle: j.angle,
        filter: filter
    };
    return obj;
}

function getTextToJson( text ) {
    var json = text.toJSON();
    var px = (json.left - g_selBackground.get( 'left' )) / page_zoom * default_zoom;
    var py = (json.top - g_selBackground.get( 'top' )) / page_zoom * default_zoom;
    var obj = {
        type: json.type,
        left: px,
        top: py,
        text: json.text,
        scaleX: (json.scaleX) / page_zoom * default_zoom,
        scaleY: (json.scaleY) / page_zoom * default_zoom,
        angle: json.angle,
        fontFamily: json.fontFamily,
        fill: json.fill,
        textAlign: json.textAlign
    };
    return obj;
}

function clearCanvas() {
    canvas.clear();
    if( g_selBackground != null ) {
        delete g_selBackground;
        g_selBackground = null;
    }
    page_zoom = default_zoom;
    $( '#zoom_handle' ).css( 'left', 0 );
}

function preLoadBackground() {
    // default load book.png loaded
    bookPageImagePath = "images/book.png";
    if( $( '#scrapbookPageNumber' ).val() == "0" || $( '#scrapbookPageNumber' ).val() == 0 ) {
        bookPageImagePath = bookFrontCoverPath;
    }

    fabric.Image.fromURL( bookPageImagePath, function( obj ) {
        obj.hasRotatingPoint = false;
        obj.set( 'left', book_posx );
        obj.set( 'top', book_posy );

        var scalex = book_width / obj.get( 'width' ) * default_zoom;
        var scaley = book_height / obj.get( 'height' ) * default_zoom;

        obj.set( 'scaleX', scalex );
        obj.set( 'scaleY', scaley );
        var objArray = [];
        fabric.Image.fromURL( bookBorder, function( bg_border ) {
            bg_border.hasRotatingPoint = false;
            bg_border.set( 'left', book_posx );
            bg_border.set( 'top', book_posy );
            bg_border.set( 'scaleX', scalex );
            bg_border.set( 'scaleY', scaley );
            objArray.push( obj );
            objArray.push( bg_border );
            g_selBackground = new fabric.Group( objArray );
            if( $( '#scrapbookPageNumber' ).val() == "0" ) {
                g_selBackground.set( 'scaleX', 0.5 );
            }
            g_selBackground.hasBorder = g_selBackground.hasControls = false;
            canvas.add( g_selBackground );
        } );
    } );
    // load background color
    var backgroundColor = 'rgba(48,62,71,1)';
    $( '#backgroundColorSelector' ).find( 'div' ).css( 'background-color', backgroundColor );
    canvas.backgroundColor = backgroundColor;
    canvas.renderAll();
    g_UndoObject = new Undo();
}

// savePage
function savePage() {
    if( issaved ) {
        if( confirm( save_string ) ) {
            var strData = getCanvasToJson();
            $.ajax( {
                        type: "POST",
                        url: saveurl,
                        cache: false,
                        data: {method: 'save', data: strData, bookId: $( "#book_id" ).val()},
                        success: function( msg ) {
                            if( msg == "error" ) {
                                alert( save_filed_string );
                            } else {
                                alert( msg );
                                issaved = false;
                            }
                        },
                        error: function( msg ) {
                            alert( save_filed_string );
                        }
                    } );
        }
    }
}

// load page
function loadPage() {
    $( document ).ajaxStart( function() {
        $( '#loading-canvas' ).show();
    } );
    $( document ).ajaxStop( function() {
        $( '#loading-canvas' ).hide();
    } );
    $.ajax( {
                type: "POST",
                url: saveurl,
                cache: false,
                data: {method: 'load', page: $( "#scrapbookPageNumber" ).val(), bookId: $( "#book_id" ).val()},
                success: function( msg ) {
                    if( msg != 'Access Denied' ) {
                        if( msg == "null" ) {
                            clearCanvas();
                            preLoadBackground();
                            return;
                        }
                        var json = JSON.parse( msg );
                        clearCanvas();
                        loadBackground( json );
                        setTimeout( function() {
                            loadData( json );
                            $( '#menuh' ).find( '.root_cat:first' ).find( 'a' ).each( function() {
                                $( this ).click();
                            } );
                        }, 200 );
                        issaved = false;
                    }
                }
            } );
}

// goto page
function gotoPage() {
    issaved = false;
    loadPage();
    $( '#pictures' ).click();
}
// load data and draw canvas
function loadData( json ) {
    var items = json.images;
    $.each( items, function( index, item ) {
        if( item.type == "text" ) {
            delay( 100 );
            setTimeout( function() {
                var text = new fabric.Text( unescape( item.text ), {
                    fontFamily: item.fontFamily,
                    fill: item.fill,
                    left: parseFloat( item.left ) * default_zoom / page_zoom + book_posx,
                    top: parseFloat( item.top ) * default_zoom / page_zoom + book_posy,
                    angle: item.angle,
                    scaleX: parseFloat( item.scaleX ) * default_zoom / page_zoom,
                    scaleY: parseFloat( item.scaleY ) * default_zoom / page_zoom
                } );
                text.lockUniScaling = true;
                text.textAlign = item.textAlign;
                if( isPreview ) {
                    text.selectable = false;
                }
                canvas.insertAt( text, index );
                isDelay = false;
                setTimeout( function() {
                    canvas.renderAll();
                }, 300 );
            }, 300 );
        } else if( item.type == "image" ) {
            delay( 200 );
            fabric.Image.fromURL( item.src, function( obj ) {
                obj.set( 'left', parseFloat( item.left ) * default_zoom / page_zoom + book_posx );
                obj.set( 'top', parseFloat( item.top ) * default_zoom / page_zoom + book_posy );
                obj.set( 'scaleX', parseFloat( item.scaleX ) * default_zoom / page_zoom );
                obj.set( 'scaleY', parseFloat( item.scaleY ) * default_zoom / page_zoom );
                obj.setAngle( item.angle );
                obj.lockUniScaling = true;
                setFilters( obj, item.filter );
                if( isPreview ) {
                    obj.selectable = false;
                }
                canvas.insertAt( obj, index );
                isDelay = false;
            } );
        } else if( item.type == "group" ) {
            delay( 200 );
            var objarray = [];
            loadGroup( objarray, 0, item, index );
        }
    } );

}

function loadGroup( subitems, index, item, zorder ) {
    try {
        var back_posx = book_posx;
        var back_posy = book_posy;
        if( g_selBackground != null ) {
            back_posx = g_selBackground.get( 'left' );
            back_posy = g_selBackground.get( 'top' );
        }
        var items = item.items;
        fabric.Image.fromURL( items[index].src, function( obj ) {
            obj.set( 'left', items[index].left );
            obj.set( 'top', items[index].top );
            obj.set( 'scaleX', parseFloat( items[index].scaleX ) );
            obj.set( 'scaleY', parseFloat( items[index].scaleY ) );
            obj.setAngle( items[index].angle );
            setFilters( obj, items[index].filter );
            subitems.push( obj );
            obj.lockUniScaling = true;
            if( index < items.length - 1 ) {
                index += 1;
                loadGroup( subitems, index, item, zorder );
            } else {
                var group = new fabric.Group( subitems );
                group.set( 'left', parseFloat( item.left ) * default_zoom / page_zoom + back_posx );
                group.set( 'top', parseFloat( item.top ) * default_zoom / page_zoom + back_posy );
                group.set( 'scaleX', parseFloat( item.scaleX ) * default_zoom / page_zoom );
                group.set( 'scaleY', parseFloat( item.scaleY ) * default_zoom / page_zoom );
                group.setAngle( item.angle );
                group.lockUniScaling = true;
                if( g_selBackground == null ) {
                    group.hasBorder = group.hasControls = false;
                    g_selBackground = group;
                } else {
                    if( isPreview ) {
                        group.selectable = false;
                    }
                }
                canvas.insertAt( group, zorder );
                return group;
                isDelay = false;
            }
        } );
    } catch( e ) {
        return;
    }
}
// load background
function loadBackground( json ) {
    // load zoom
    var width = parseInt( $( '#zoom_slider' ).css( 'width' ) );
    page_zoom = parseFloat( json.zoom );
    $( '#zoom_handle' ).css( 'left', 0 );

    // load background color
    var backgroundColor = json.backgroundcolor;
    var backgroundImage = json.backgroundImage;
    $( '#backgroundColorSelector' ).find( 'div' ).css( 'background-color', backgroundColor );
    canvas.backgroundColor = backgroundColor;
    if( !isNull( backgroundImage ) ) {
        canvas.setBackgroundImage( backgroundImage, canvas.renderAll.bind( canvas ) );
    }
    canvas.renderAll();
    g_UndoObject = new Undo();
    // book cover background image ?
    var items = json.images;
    $.each( items, function( index, item ) {
        if( item.type == "background" ) {
            var objarray = [];
            if( g_selBackground != null ) {
                delete g_selBackground;
            }
            g_selBackground = null;
            loadGroup( objarray, 0, item );
            return;
        }
    } );
}

// delay function
function delay( millis ) {
    var date = new Date();
    var curDate = null;
    isDelay = true;
    do {
        curDate = new Date();
    } while( isDelay && (curDate - date < millis) );
}

// zoom canvas
function zoomCanvas( rate ) {
    if( page_zoom < default_zoom ) {
        page_zoom = default_zoom;
    }

    var px = g_selBackground.get( 'left' );
    var py = g_selBackground.get( 'top' );
    var items = canvas.getObjects();

    var idx;
    var cnt = items.length;
    var dx, dy;
    for( idx = 0; idx < cnt; idx++ ) {
        var scaleX = items[idx].get( 'scaleX' );
        var scaleY = items[idx].get( 'scaleY' );

        items[idx].set( 'scaleX', scaleX * rate / page_zoom );
        items[idx].set( 'scaleY', scaleY * rate / page_zoom );

        if( items[idx] == g_selBackground ) {
            continue;
        }
        dx = items[idx].get( 'left' );
        dy = items[idx].get( 'top' );

        var disX = (px - dx) * rate / page_zoom;
        var disY = (py - dy) * rate / page_zoom;
        items[idx].set( 'left', px - disX );
        items[idx].set( 'top', py - disY );
    }
    page_zoom = rate;
    canvas.renderAll();
}

function setPositionToTextBox( obj ) {
    var absCoords = canvas.getAbsoluteCoords( obj );
    $( '#addTextBox' ).css( 'left', absCoords.left );
    $( '#addTextBox' ).css( 'top', absCoords.top );
    var width = obj.width;
    if( width > 650 ) {
        width = 650;
    }
    if( width < 300 ) {
        width = 300
    }
    $( '#addTextBox' ).css( 'width', width );
}

function positionTextBox( obj ) {
    setPositionToTextBox( obj );
    if( obj && obj.type === 'text' ) {
        $( '#addTextBox' ).val( obj.text );
        if( !isNull( previouslyClickedType ) && previouslyClickedType == 'font' ) {
            alert( obj.fill );
        }
        $( '#addTextBox' ).focus();
    }
}

function addPreview( newname ) {
    //var xheight = Math.round( $( window ).height() * 0.99 );
    //var xwidth = Math.round( $( window ).width() * 0.99 );
    var xheight = 600;
    var xwidth = 1100;
    var ceditor = '<div><iframe width="' + xwidth + 'px" height="' + xheight + 'px" src="' + 'view.php?bookId=' + newname + '"  scrolling="no" frameborder="0" ></iframe></div>';
    $.colorbox( {
                    html: ceditor,
                    innerHeight: xheight,
                    innerWidth: xwidth,
                    overlayClose: false,
                    escKey: false,
                    arrowKey: false,
                    fastIframe: false,
                    opacity: .7,
                    top: 30,
                    fixed: true,
                    scrolling: false,
                    onLoad: function() {
                        $( '#cboxClose' ).css( {height: '0px', width: '0px', 'position': 'absolute'} );
                        $( '#previewBtn' ).remove();
                    },
                    onComplete: function() {
                        var scrollx = $( document ).scrollTop();
                        $( document ).scrollTop( scrollx + xheight / 50 );
                    },
                    onClosed: function() {
                        $( '#cboxClose' ).css( {height: '45px', width: '45px', 'position': 'absolute'} );
                        var scrollx = $( document ).scrollTop();
                        $( document ).scrollTop( scrollx - xheight / 50 );
                    }
                } );
}

function clickAlbums( id ) {
    clearVerticalPhotos();
    $( '#editorVerticalContentHolder' ).find( '.content' ).each( function() {
        $( this ).hide();
    } );
    $.ajax( {
                type: "POST",
                url: saveurl,
                cache: false,
                data: {method: 'photos', albumId: id, 'uid': $( '#user_id' ).val()},
                success: function( msg ) {
                    if( isNull( msg ) ) {
                        alert( 'connect failed' );
                        return;
                    }
                    loadPhotos( msg );
                },
                error: function( msg ) {
                    alert( 'connect failed! ' + msg );
                }
            } );
}

function oPhotoSize( photoid ) {
    var newImg = new Image();
    var oImage = $( 'img[photoid="' + photoid + '"]' );
    newImg.onload = function() {
        var height = newImg.height;
        var width = newImg.width;
        oImage.attr( 'oheight', height );
        oImage.attr( 'owidth', width );
    }
    newImg.src = oImage.attr( 'src' ); // this must be done AFTER setting onload
}

function loadPhotos( photos ) {
    //if it's not a json, then it's probably an error message
    try {
        var json = JSON.parse( photos );
        var len = json.length;
    } catch( e ) {
        alert( photos );
    }

    var picPath = "./../userpics/" + $( '#user_id' ).val() + '/';

    for( var i = 0; i < len; i++ ) {
        var pic = '<div class="album_photo"><img photoid="' + json[i].photoid + '" src="' + picPath + json[i].filename + '" alt="photos" /></div>'; //class="album-title"
        $( pic ).insertBefore( $( '#editorVerticalPhotos' ).find( 'br' ) );
        oPhotoSize( json[i].photoid );
    }
    $( '#editorVerticalAlbums' ).hide();

    //initialize photos
    $( '#editorVerticalPhotos' ).find( 'div' ).each( function() {
        $( this ).css( 'z-index', '110' ).draggable( {
                                                         containment: '#editorMainHolder',
                                                         stack: ".placedTrinket",
                                                         helper: function( e ) {
                                                             var $target = $( e.target );
                                                             var src = $target.attr( 'src' );
                                                             if( isNull( src ) ) {
                                                                 $target = $target.find( 'img' );
                                                                 src = $target.attr( 'src' );
                                                             }
                                                             var size = getAspectRatioResize( $target.attr( 'owidth' ), $target.attr( 'oheight' ), 190 );
                                                             $( this ).draggable( "option", "cursorAt", { top: size.height / 2, left: size.width / 2  } );
                                                             return $( '<img src="' + src + '" height=' + size.height + ' width=' + size.width + '>' );
                                                         },
                                                         opacity: 0.5,
                                                         cursor: 'move',
                                                         cursorAt: {left: 100, top: 100},
                                                         iframeFix: true,
                                                         appendTo: 'body',
                                                         stop: function( e, ui ) {
                                                             if( g_trinketsInDiv ) {
                                                                 g_trinketsInDiv = false;
                                                                 return;
                                                             }
                                                             issaved = true;
                                                             var editorPos = $( '#editorScrapbookCanvas' ).offset();
                                                             var trinketPos = $( ui.helper ).offset();
                                                             var trinketWidth = $( ui.helper ).width();
                                                             var trinketHeight = $( ui.helper ).height();
                                                             fabric.Image.fromURL( $( this ).find( 'img' ).attr( 'src' ), function( obj ) {
                                                                 obj.hasRotatingPoint = true;
                                                                 obj.lockUniScaling = true;
                                                                 obj.set( 'scaleX', ( trinketWidth / obj.get( 'width' ) ) * page_zoom );
                                                                 obj.set( 'scaleY', ( trinketHeight / obj.get( 'height' ) ) * page_zoom );
                                                                 //obj.set( 'scaleX', obj.get( 'scaleX' ) * page_zoom );
                                                                 //obj.set( 'scaleY', obj.get( 'scaleY' ) * page_zoom );
                                                                 canvas.add( obj );
                                                                 canvas.setActiveObject( obj );
                                                             }, {
                                                                                       left: (trinketPos.left - editorPos.left) + trinketWidth / 2,
                                                                                       top: (trinketPos.top - editorPos.top) + trinketHeight / 2} );
                                                         }
                                                     } );
    } );
    $( '#editorVerticalPhotos' ).droppable( {
                                                'greedy': true,
                                                drop: function( event, ui ) {
                                                    g_trinketsInDiv = true;
                                                }
                                            } ).show();

    makeImagesFit( $( '#editorVerticalPhotos .album_photo' ) );
}

function initializeZoomScrollbar() {
    $( '#zoom_handle' ).draggable( {
                                       containment: '.zoom_slider_container',
                                       stack: "#zoom_handle",
                                       cursor: 'pointer',
                                       grid: [ 10, 0 ],
                                       axis: "x",
                                       appendTo: 'body',
                                       drag: function( e, ui ) {
                                           var pos = $( this ).offset();
                                           var con_pos = $( '.zoom_slider_container' ).offset();
                                           var posX = pos.left - con_pos.left + 6;
                                           posX = posX > 0 ? posX + 5 : 0;
                                           var width = parseInt( $( '#zoom_slider' ).css( 'width' ) );
                                           var zoom = default_zoom + Math.round( posX * 10 / width ) / 10;
                                           zoomCanvas( zoom );
                                           issaved = true;
                                       }
                                   } );

    $( '.zoom_slider_container' ).click( function( e ) {
        var left = parseInt( $( '#zoom_slider' ).css( 'left' ) );
        var width = parseInt( $( '#zoom_slider' ).css( 'width' ) );
        var right = width + left;
        var pos = $( '.zoom_slider_container' ).offset();
        var px = e.pageX - parseInt( pos.left );
        if( px < left ) {
            px = left;
        }
        if( px > right ) {
            px = right;
        }
        var posX = px - 10;
        posX = posX > 0 ? posX + 5 : 0;
        $( '#zoom_handle' ).css( 'left', posX );
        var zoom = default_zoom + Math.round( posX * 10 / width ) / 10;
        zoomCanvas( zoom );
        issaved = true;
    } );
}

// initialize photos dragdrop
function initializePhotos() {
    //#todo: IS THIS EVER USED ANYMORE?
    $( '#editorVerticalAlbums' ).find( 'div' ).each( function() {
        $( this ).click( function() {
            clickAlbums( $( this ).attr( 'id' ) );
        } );
    } );
    /*
     $( '#backBtn' ).click( function() {
     clearVerticalPhotos();
     $( '#editorVerticalPhotos' ).hide();
     $( '#editorVerticalAlbums' ).show();
     } );
     */
}

//initizalize trinkets dragdrop
function initializeTrinkets() {
    $( '#editorVerticalContentScroller' ).find( '.trinketContainer' ).each( function() {
        $( this ).find( '.trinket' ).load( function() {
            var vspace = 200 - $( this ).height();
            var hspace = 200 - $( this ).width();
            $( this ).css( {'padding-top': (vspace / 2), 'padding-bottom': (vspace / 2), 'padding-left': (hspace / 2), 'padding-right': (hspace / 2) } );
        } );
        var self = $( this );
        $( this ).parent().mousedown( function( event ) {
            self.trigger( "mousedown.draggable", [event] );
        } );

        $( this ).css( 'z-index', '110' ).draggable( {
                                                         containment: '#editorMainHolder',
                                                         stack: ".placedTrinket",
                                                         helper: function( e ) {
                                                             var size = getAspectRatioResize( $( e.target ).width(), $( e.target ).height(), 200 );
                                                             $( this ).draggable( "option", "cursorAt", { top: size.height / 2, left: size.width / 2  } );
                                                             return $( '<img src="' + $( e.target ).attr( 'src' ) + '" height=' + size.height + ' width=' + size.width + '>' );
                                                         },
                                                         opacity: 0.5,
                                                         cursor: 'move',
                                                         cursorAt: {left: 0, top: 0},
                                                         appendTo: 'body',
                                                         iframeFix: true,
                                                         stop: function( e, ui ) {
                                                             if( g_trinketsInDiv ) {
                                                                 g_trinketsInDiv = false;
                                                                 return;
                                                             }
                                                             issaved = true;
                                                             var editorPos = $( '#editorScrapbookCanvas' ).offset();
                                                             var trinketPos = $( ui.helper ).offset();
                                                             var trinketWidth = $( ui.helper ).width();
                                                             var trinketHeight = $( ui.helper ).height();

                                                             fabric.Image.fromURL( $( "img", this ).attr( 'src' ), function( obj ) {
                                                                 obj.hasRotatingPoint = true;
                                                                 var scaleX = obj.get( 'scaleX' ) * page_zoom;
                                                                 var scaleY = obj.get( 'scaleY' ) * page_zoom;
                                                                 obj.set( 'scaleX', scaleX );
                                                                 obj.set( 'scaleY', scaleY );
                                                                 obj.lockUniScaling = true;
                                                                 canvas.add( obj );
                                                                 g_UndoObject.setUndoItem( "{}" );
                                                                 g_UndoObject.action = "add-trinket";
                                                                 g_UndoObject.setCurItem( obj );
                                                                 canvas.setActiveObject( obj );
                                                             }, {
                                                                                       left: (trinketPos.left - editorPos.left) + trinketWidth / 2,
                                                                                       top: (trinketPos.top - editorPos.top) + trinketHeight / 2
                                                                                   } );
                                                         }
                                                     } );
    } );
    $( '#editorVerticalContentScroller' ).droppable( {
                                                         'greedy': true,
                                                         drop: function( event, ui ) {
                                                             g_trinketsInDiv = true;
                                                         }
                                                     } );
}

// filter dragdrop
function initializeFilters() {
    $( '#editorVerticalImageFilter' ).find( '.imageFilter' ).each( function() {
        $( this ).css( 'z-index', '111' ).draggable( {
                                                         containment: '#editorMainHolder',
                                                         stack: ".imagefilter",
                                                         helper: function( e ) {
                                                             return $( '<div id="' + $( e.target ).attr( 'id' ) + '" class="filterDragHelper">' );
                                                         },
                                                         cursorAt: {left: 9, top: 14},
                                                         opacity: 0.5,
                                                         cursor: 'move',
                                                         appendTo: 'body',

                                                         stop: function( e, ui ) {
                                                             issaved = true;
                                                             var filterName = $( this ).attr( 'id' );
                                                             var editorPos = $( '#editorScrapbookCanvas' ).offset();
                                                             var imagePos = $( ui.helper ).offset();
                                                             var imgx = imagePos.left - editorPos.left + $( ui.helper ).width() / 2;
                                                             var imgy = imagePos.top - editorPos.top + $( ui.helper ).height() / 2;

                                                             var min = 3000;
                                                             var selObj = null;
                                                             canvas.discardActiveObject();
                                                             canvas.discardActiveGroup();
                                                             var imageArray = canvas.getObjects();
                                                             $.each( imageArray, function( index, obj ) {
                                                                 if( obj.type != "text" && obj != g_selBackground ) {
                                                                     var cx = obj.get( 'left' );
                                                                     var cy = obj.get( 'top' );
                                                                     var cwidth = obj.get( 'width' ) * obj.scaleX;
                                                                     var cheight = obj.get( 'height' ) * obj.scaleY;
                                                                     var distance = Math.round( Math.sqrt( Math.pow( cwidth / 2, 2 ) + Math.pow( cheight / 2, 2 ) ) );

                                                                     var distX = Math.abs( imgx - cx );
                                                                     var distY = Math.abs( imgy - cy );
                                                                     var realdist = Math.round( Math.sqrt( Math.pow( distX, 2 ) + Math.pow( distY, 2 ) ) );

                                                                     if( realdist < distance ) {
                                                                         if( realdist < min ) {
                                                                             min = realdist;
                                                                             selObj = obj;
                                                                         }
                                                                     }
                                                                 }

                                                             } );
                                                             if( selObj != null ) {
                                                                 g_UndoObject.setUndoItem( selObj );
                                                                 g_UndoObject.action = "add-filter";
                                                                 g_UndoObject.setCurItem( selObj );
                                                                 if( selObj.type == "group" ) {
                                                                     var items = selObj.getObjects();
                                                                     selObj = items[0];
                                                                 }
                                                                 removeFilter( selObj );

                                                                 var namearr = filterName.split( '-' );
                                                                 switch( namearr[1] ) {
                                                                     case('jarques'):
                                                                         applyFilter( 0, new f.jarques(), selObj );
                                                                         break;
                                                                     case('lomo'):
                                                                         applyFilter( 1, new f.lomo(), selObj );
                                                                         break;
                                                                     case('love'):
                                                                         applyFilter( 2, new f.love(), selObj );
                                                                         break;
                                                                     case('nostalgia'):
                                                                         applyFilter( 3, new f.nostalgia(), selObj );
                                                                         break;
                                                                     case('oldBoot'):
                                                                         applyFilter( 4, new f.oldBoot(), selObj );
                                                                         break;
                                                                     case('orangePeel'):
                                                                         applyFilter( 5, new f.orangePeel(), selObj );
                                                                         break;
                                                                     case('pinhole'):
                                                                         applyFilter( 6, new f.pinhole(), selObj );
                                                                         break;
                                                                     case('sinCity'):
                                                                         applyFilter( 7, new f.sinCity(), selObj );
                                                                         break;
                                                                     case('sunrise'):
                                                                         applyFilter( 8, new f.sunrise(), selObj );
                                                                         break;
                                                                     case('vintage'):
                                                                         applyFilter( 9, new f.vintage(), selObj );
                                                                         break;
                                                                     case('herMajesty'):
                                                                         applyFilter( 10, new f.herMajesty(), selObj );
                                                                         break;
                                                                     case('hemingway'):
                                                                         applyFilter( 11, new f.hemingway(), selObj );
                                                                         break;
                                                                     case('hazyDays'):
                                                                         applyFilter( 12, new f.hazyDays(), selObj );
                                                                         break;
                                                                     case('grungy'):
                                                                         applyFilter( 13, new f.grungy(), selObj );
                                                                         break;
                                                                     case('glowingSun'):
                                                                         applyFilter( 14, new f.glowingSun(), selObj );
                                                                         break;
                                                                     case('crossProcess'):
                                                                         applyFilter( 15, new f.crossProcess(), selObj );
                                                                         break;
                                                                     case('clarity'):
                                                                         applyFilter( 16, new f.clarity(), selObj );
                                                                         break;
                                                                     case('removeEffects'):
                                                                         removeFilter( selObj );
                                                                         break;
                                                                 }
                                                             }
                                                         }
                                                     } );
    } );
}

function removeExistingColorPickers() {
    $( '.colorpicker' ).remove();
}

// font drag drop
function initializeFonts() {
    removeExistingColorPickers();
    canvas.renderAll();
    $( '#fontColorSelector' ).ColorPicker( {
                                               color: '#000000',
                                               onShow: function( colpkr ) {
                                                   canvas.renderAll();
                                                   var rgbcola = ( $( '#fontColorSelector div' ).css( 'background-color' ) ).match( /^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/ );
                                                   var col = ColorPicker.RGBToHSB( {r: rgbcola[1], g: rgbcola[2], b: rgbcola[3]} );
                                                   $( colpkr ).data( 'colorpicker' ).origColor = col;
                                                   $( colpkr ).data( 'colorpicker' ).color = col;
                                                   $( colpkr ).fadeIn( 500 );
                                                   return false;
                                               },
                                               onHide: function( colpkr ) {
                                                   $( colpkr ).fadeOut( 500 );
                                                   return false;
                                               },
                                               onChange: function( hsb, hex, rgb ) {
                                                   $( '#fontColorSelector div' ).css( 'backgroundColor', '#' + hex );
                                                   var selText = canvas.getActiveObject();
                                                   if( !isNull( selText ) && selText.type == "text" ) {
                                                       selText.set( 'fill', hex );
                                                       canvas.renderAll();
                                                   }
                                               }
                                           } );

    $( '#editorVerticalFonts' ).find( '.fonts' ).each( function() {
        $( this ).css( 'z-index', '111' ).draggable( {
                                                         cancel: '',
                                                         containment: '#editorMainHolder',
                                                         helper: function() {
                                                             var container = $( this ).clone();
                                                             container.find( 'textarea' ).val( $( '#txtFont' ).val() );
                                                             return container;
                                                         },
                                                         opacity: 0.5,
                                                         cursor: 'move',
                                                         appendTo: 'body',
                                                         stop: function( e, ui ) {
                                                             if( g_fontInDiv ) {
                                                                 g_fontInDiv = false;
                                                                 return;
                                                             }
                                                             var editorPos = $( '#editorScrapbookCanvas' ).offset();
                                                             var fontpos = $( ui.helper ).offset();
                                                             var obj = new fabric.Text( $( ui.helper ).find( 'textarea' ).val(), {
                                                                 fontFamily: $( e.target ).css( 'font-family' ),
                                                                 fill: $( '#fontColorSelector div' ).css( 'background-color' ),
                                                                 left: (fontpos.left - editorPos.left) + $( ui.helper ).width() / 2,
                                                                 top: (fontpos.top - editorPos.top) + $( ui.helper ).height() / 2
                                                             } );
                                                             obj.textAlign = $( ui.helper ).find( "textarea" ).css( 'text-align' ).toLowerCase();
                                                             obj.lockUniScaling = true;
                                                             // add font
                                                             g_UndoObject.setUndoItem( "" );
                                                             g_UndoObject.action = "add-text";
                                                             g_UndoObject.setCurItem( obj );
                                                             canvas.add( obj );
                                                             canvas.setActiveObject( obj );
                                                             issaved = true;
                                                         }
                                                     } );
    } );
    $( '#editorVerticalFonts' ).droppable( {
                                               'greedy': true,
                                               drop: function( event, ui ) {
                                                   g_fontInDiv = true;
                                               }
                                           } );

}

// wallpapers.
function initializeWallpapers() {
    removeExistingColorPickers();
    canvas.renderAll();
    $( '#backgroundColorSelector' ).ColorPicker( {
                                                     color: canvas.backgroundColor,
                                                     onShow: function( colpkr ) {
                                                         canvas.renderAll();
                                                         g_UndoObject.action = "add-backgroundColor";
                                                         g_UndoObject.undoItem = canvas.backgroundColor;
                                                         g_UndoObject.curItem = canvas.backgroundColor;
                                                         $( colpkr ).find( '.colorpicker_current_color, .colorpicker_new_color' ).css( 'background-color', canvas.backgroundColor );
                                                         $( colpkr ).data( 'colorpicker' ).origColor = ColorPicker.HexToHSB( canvas.backgroundColor );
                                                         $( colpkr ).data( 'colorpicker' ).color = ColorPicker.HexToHSB( canvas.backgroundColor );
                                                         $( colpkr ).fadeIn( 500 );
                                                         return false;
                                                     },
                                                     onHide: function( colpkr ) {
                                                         canvas.renderAll();
                                                         $( colpkr ).fadeOut( 500 );
                                                         issaved = true;
                                                         return false;
                                                     },
                                                     onChange: function( hsb, hex, rgb ) {
                                                         canvas.renderAll();
                                                         $( '#backgroundColorSelector div' ).css( 'background-color', canvas.backgroundColor );
                                                         canvas.backgroundColor = '#' + hex;
                                                         canvas.backgroundImage = '';
                                                         issaved = true;
                                                         return true;
                                                     }
                                                 } );
    $( '#backgroundColorSelector div' ).css( 'background-color', canvas.backgroundColor );

    $( '#editorVerticalwallpaper' ).find( 'div.wallpaperHolder' ).each( function() {
        $( this ).css( 'z-index', '111' ).draggable( {
                                                         containment: '#editorMainHolder',
                                                         stack: ".fonts",
                                                         helper: 'clone',
                                                         opacity: 0.5,
                                                         cursor: 'move',
                                                         cursorAt: {left: 85, top: 50},
                                                         appendTo: 'body',
                                                         stop: function( e, ui ) {
                                                             issaved = true;
                                                             var editorPos = $( '#editorScrapbookCanvas' ).offset();
                                                             var trinketPos = $( ui.helper ).offset();
                                                             var trinketWidth = $( ui.helper ).width();
                                                             var trinketHeight = $( ui.helper ).height();

                                                             var imagePos = $( ui.helper ).offset();
                                                             var imgx = imagePos.left - editorPos.left + $( ui.helper ).width() / 2;
                                                             var imgy = imagePos.top - editorPos.top + $( ui.helper ).height() / 2;

                                                             var cx = g_selBackground.get( 'left' );
                                                             var cy = g_selBackground.get( 'top' );
                                                             var cwidth = g_selBackground.get( 'width' ) * g_selBackground.scaleX;
                                                             var cheight = g_selBackground.get( 'height' ) * g_selBackground.scaleY;
                                                             var distX = Math.abs( imgx - cx ), distY = Math.abs( imgy - cy );
                                                             var src = $( this ).find( 'img' ).attr( 'src' );
                                                             if( (distX < cwidth / 2) && (distY < cheight / 2) ) {
                                                                 if( isNull( src ) ) {
                                                                     return;
                                                                 }
                                                                 //theres no book background on the cover page
                                                                 if( $( '#scrapbookPageNumber' ).val() == 0 ) {
                                                                     return;
                                                                 }
                                                                 //lets add an inner book background!
                                                                 fabric.Image.fromURL( $( this ).find( 'img' ).attr( 'src' ), function( obj ) {
                                                                     obj.lockUniScaling = true;
                                                                     obj.hasRotatingPoint = true;
                                                                     if( g_selBackground != null ) {
                                                                         g_UndoObject.setUndoItem( g_selBackground );
                                                                         g_UndoObject.action = "add-background";

                                                                         canvas.discardActiveGroup();
                                                                         var items = g_selBackground.getObjects();
                                                                         var px = g_selBackground.get( 'left' );
                                                                         var py = g_selBackground.get( 'top' );
                                                                         var scaleX = g_selBackground.get( 'scaleX' );
                                                                         var scaleY = g_selBackground.get( 'scaleY' );

                                                                         canvas.remove( g_selBackground );
                                                                         delete g_selBackground;

                                                                         obj.set( 'left', px );
                                                                         obj.set( 'top', py );
                                                                         obj.set( 'scaleX', items[1].get( 'scaleX' ) );
                                                                         obj.set( 'scaleY', items[1].get( 'scaleY' ) * 1.01 );
                                                                         items[1].set( 'left', px );
                                                                         items[1].set( 'top', py );
                                                                         items[0].set( 'left', px );
                                                                         items[0].set( 'top', py );

                                                                         g_UndoObject.setCurItem( g_selBackground );
                                                                         g_selBackground = new fabric.Group( [items[0], items[1], obj] );
                                                                         g_selBackground.set( 'scaleX', scaleX );
                                                                         g_selBackground.set( 'scaleY', scaleY );
                                                                         g_selBackground.hasBorder = g_selBackground.hasControls = false;
                                                                         canvas.add( g_selBackground );
                                                                         canvas.sendToBack( g_selBackground );
                                                                     }
                                                                 }, {left: (trinketPos.left - editorPos.left) + trinketWidth / 2,
                                                                                           top: (trinketPos.top - editorPos.top) + trinketHeight / 2} );
                                                                 canvas.renderAll();
                                                             } else {
                                                                 // add background;
                                                                 g_UndoObject.action = "add-backgroundImage";
                                                                 if( !isNull( canvas.backgroundImage ) ) {
                                                                     g_UndoObject.undoItem = canvas.backgroundImage.src;
                                                                 }
                                                                 g_UndoObject.curItem = src;
                                                                 canvas.setBackgroundImage( src, canvas.renderAll.bind( canvas ), {scaleX: 2, scaleY: 2} );
                                                             }
                                                             setTimeout( function() {
                                                                 canvas.renderAll();
                                                                 setTimeout( function() {
                                                                     canvas.renderAll();
                                                                 }, 100 );
                                                             }, 50 );

                                                             canvas.renderAll();
                                                         }
                                                     } );
    } );
}

// image borders
function initializeBorders() {
    $( '#editorVerticalborders' ).find( 'img' ).each( function() {
        $( this ).css( 'z-index', '111' ).draggable( {
                                                         containment: '#editorMainHolder',
                                                         stack: ".img",
                                                         helper: 'clone',
                                                         opacity: 0.5,
                                                         cursor: 'move',
                                                         appendTo: 'body',
                                                         cursorAt: {left: 90, top: 60},
                                                         stop: function( e, ui ) {
                                                             issaved = true;
                                                             var editorPos = $( '#editorScrapbookCanvas' ).offset();
                                                             var imagePos = $( ui.helper ).offset();
                                                             var imgx = imagePos.left - editorPos.left + $( ui.helper ).width() / 2;
                                                             var imgy = imagePos.top - editorPos.top + $( ui.helper ).height() / 2;

                                                             var min = 10000;
                                                             var selObj = null;
                                                             var selObjectIndex;

                                                             var imageArray = canvas.getObjects();
                                                             canvas.discardActiveGroup();
                                                             canvas.discardActiveObject();
                                                             $.each( imageArray, function( index, obj ) {
                                                                 if( obj.type != "text" && obj != g_selBackground ) {
                                                                     var cx = obj.get( 'left' );
                                                                     var cy = obj.get( 'top' );
                                                                     var cwidth = obj.get( 'width' ) * obj.scaleX;
                                                                     var cheight = obj.get( 'height' ) * obj.scaleY;
                                                                     var distance = Math.round( Math.sqrt( Math.pow( cwidth / 2, 2 ) + Math.pow( cheight / 2, 2 ) ) );

                                                                     var distX = Math.abs( imgx - cx ), distY = Math.abs( imgy - cy ), realdist = Math.round( Math.sqrt( Math.pow( distX,
                                                                                                                                                                                   2 ) + Math.pow( distY,
                                                                                                                                                                                                   2 ) ) );

                                                                     if( realdist < distance ) {
                                                                         if( realdist < min ) {
                                                                             min = realdist;
                                                                             selObj = obj;
                                                                             selObjectIndex = index;
                                                                         }
                                                                     }
                                                                 }
                                                             } );

                                                             if( selObj != null ) {
                                                                 // add editor
                                                                 g_UndoObject.setUndoItem( selObj );
                                                                 g_UndoObject.action = "add-border";
                                                                 // 
                                                                 if( selObj.type == "image" ) {
                                                                     var px = selObj.get( "left" );
                                                                     var py = selObj.get( "top" );
                                                                     var width = selObj.get( "width" );
                                                                     var height = selObj.get( "height" );
                                                                     var sx = selObj.scaleX;
                                                                     var sy = selObj.scaleY;
                                                                     var angle = selObj.getAngle();
                                                                     var zorder = imageArray.indexOf( selObj );

                                                                     fabric.Image.fromURL( $( this ).attr( 'src' ), function( img ) {
                                                                         img.set( {left: px, top: py} );
                                                                         var cwidth = img.get( 'width' );
                                                                         var cheight = img.get( 'height' );
                                                                         var csx = width / cwidth * sx * 1.2;
                                                                         var csy = height / cheight * sy * 1.2;
                                                                         img.setAngle( angle );
                                                                         img.set( 'scaleX', csx );
                                                                         img.set( 'scaleY', csy );
                                                                         canvas.remove( selObj );
                                                                         var imgGroup = new fabric.Group( [selObj, img] );

                                                                         // add Insert;
                                                                         g_UndoObject.setCurItem( imgGroup );
                                                                         canvas.insertAt( imgGroup, zorder );
                                                                     } );
                                                                 } else {

                                                                     zorder = imageArray.indexOf( selObj );
                                                                     var items = selObj.getObjects();
                                                                     var itemImg = items[0];
                                                                     var gsclx = selObj.get( 'scaleX' );
                                                                     var gscly = selObj.get( 'scaleY' );
                                                                     px = selObj.get( "left" );
                                                                     py = selObj.get( "top" );
                                                                     width = itemImg.get( "width" );
                                                                     height = itemImg.get( "height" );
                                                                     sx = itemImg.scaleX;
                                                                     sy = itemImg.scaleY;
                                                                     var selAngle = items[1].getAngle();
                                                                     angle = selObj.getAngle();

                                                                     items.forEach( function( object ) {
                                                                         canvas.remove( object );
                                                                         delete object;
                                                                     } );

                                                                     canvas.remove( selObj );
                                                                     delete selObj;

                                                                     fabric.Image.fromURL( $( this ).attr( 'src' ), function( img ) {
                                                                         img.set( {left: px, top: py} );
                                                                         var cwidth = img.get( 'width' );
                                                                         var cheight = img.get( 'height' );
                                                                         var csx = width / cwidth * sx * 1.2;
                                                                         var csy = height / cheight * sy * 1.2;

                                                                         img.set( 'scaleX', csx );
                                                                         img.set( 'scaleY', csy );
                                                                         img.setAngle( selAngle );
                                                                         itemImg.set( {left: px, top: py} );

                                                                         var imgGroup = new fabric.Group( [itemImg, img] );
                                                                         imgGroup.setAngle( angle );
                                                                         imgGroup.set( 'scaleX', gsclx );
                                                                         imgGroup.set( 'scaleY', gscly );
                                                                         // add Insert;
                                                                         g_UndoObject.setCurItem( imgGroup );
                                                                         canvas.insertAt( imgGroup, zorder );

                                                                     } );

                                                                 }
                                                             }
                                                         }
                                                     } );
    } );
}

function clearVerticalPhotos() {
    $( '#editorVerticalPhotos' ).find( 'div' ).each( function() {
        $( this ).remove();
    } );
}

