/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var INPUT_BOOKS = "Please input a valid, unique name";
var PROMPT_BOOKS = "Please enter a name for the book";
var addurl = "editor/save.php";

// find book name
function isExistsAndcheckName( name ) {
    var isold = false;
    $( '#books' ).find( '.item_book' ).each( function( index, obj ) {
        var curname = $( obj ).find( 'div' ).find( '.book_title' ).html();
        curname = curname.trim();
        if( curname == name ) {
            isold = true;
            return;
        }
    } );
    return isold;
}

function addPopup( id, page ) {
    if( isNull( id ) ) {
        return;
    }
    var xheight = 800, xwidth = 1100;

    var preview_offset = 40;
    if( page == undefined ) {
        page = "editor.php";
    }

    if( page.toLowerCase() == "view.php" ) {
        xheight = 610;
    }

    var ceditor = '<iframe id="popupEditor" width="' + xwidth + '" height="' + xheight + '" src="' + url_prefix + 'editor/' + page + '?bookId=' + id + '"  scrolling="no" frameborder="0" ></iframe>';
    $( document ).scrollTop( 0 );
    $( 'body' ).css( 'overflow', 'hidden' );
    $.colorbox( {
                    html: ceditor,
                    innerHeight: xheight,
                    innerWidth: xwidth,
                    overlayClose: false,
                    escKey: false,
                    arrowKey: false,
                    fastIframe: false,
                    opacity: 1,
                    top: 30,
                    fixed: true,
                    scrolling: false,
                    onLoad: function() {
                    },
                    onComplete: function() {
                        var scrollx = $( document ).scrollTop();
                        $( document ).scrollTop( scrollx + xheight / 50 );
                    },
                    onCleanup: function() {
                        $( 'body' ).css( 'overflow', 'visible' );
                    }
                } );
}

function view( obj ) {
    var book_id = $( obj ).attr( "bookid" );
    $( '#sel_book_name' ).val( $.trim( book_id ) );
    addPopup( $.trim( book_id ) );
}

function show( obj ) {
    var book_id = $( obj ).attr( "bookid" );
    $( '#sel_book_name' ).val( $.trim( book_id ) );
    addPopup( $.trim( book_id ), "view.php" );
}
function rename( obj ) {
    var parent = $( obj ).parent().parent();
    var renameurl = url_prefix + addurl;
    var bookname = $( parent ).find( '.book_title' ).html();
    var book_id = $( obj ).attr( 'bookid' );

    bookname = $.trim( bookname );
    var newname = prompt( PROMPT_BOOKS, bookname );
    if( newname ) {
        var isold = isExistsAndcheckName( newname );
        if( isold ) {
            alert( INPUT_BOOKS );
            return;
        }
        $.ajax( {
                    type: "POST",
                    url: renameurl,
                    cache: false,
                    data: {method: 'update', bookId: book_id, newName: newname},
                    success: function( msg ) {
                        var data = $.parseJSON( msg );
                        if( data.error == 0 ) {
                            $( parent ).find( '.book_title' ).html( newname );
                            alert( 'Successfully renamed your scrapbook!' );
                        } else {
                            alert( data.msg );
                        }
                    }
                } );
    }
}
function deleteBook( obj ) {
    var parent = $( obj ).parent();
    var bookId = $( obj ).attr( 'bookid' );
    var bookname = $( parent ).parent().find( '.book_title' ).html();
    var deleteUrl = url_prefix + addurl;
    if( confirm( 'Are you sure you want to delete the following book? ' + bookname ) ) {
        $.ajax( {
                    type: "POST",
                    url: deleteUrl,
                    cache: false,
                    data: {method: 'delete', bookId: bookId},

                    success: function( msg ) {
                        var data = $.parseJSON( msg );
                        if( data.error == 0 ) {
                            $( parent ).parent().parent().remove();
                            alert( 'Your scrapbook has been deleted!' );
                        } else {
                            alert( data.msg );
                        }
                    }
                } );
    }
}

function addBook( name, id, scraplookCoverUrl ) {
    scraplookCoverUrl = scraplookCoverUrl ? scraplookCoverUrl : 'defaultScraplookCover.jpg';
    newBook =
    '<div class="item_book" bookname="' + name + '" bookid="' + id + '"><div class="bookIconHolder"><img src="' + scraplookCoverUrl + '" onclick="show(this)" bookid="' + id + '"><div class="book_icon"></div></div><div class="view-book">';
    newBook = newBook + '<div class="book_title" onclick="rename(this);" bookid="' + id + '">' + name + '</div><div class="scrapbooksEditBook">Add to your scrapbook at any time!<br>';
    newBook = newBook + '<a bookid="' + id + '" onclick="view(this);">Edit my scrapbook &gt;&gt;</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
    newBook = newBook + '<a class="lb-terms" bookid="' + id + '" onclick="deleteBook(this);">(delete)</a></div>';
    newBook = newBook + '<div class="scrapbookButtonHolder"><img src="' + url_prefix + 'images/viewYourBook.png" onclick="show(this);" bookid="' + id + '">';
    newBook = newBook + '<a href="' + url_prefix + 'scrapshare/"><img src="' + url_prefix + 'images/shareYourBook.png"></a></div></div>';
    newBook = newBook + '<div><div class="vrd"> <input type="hidden" value="' + id + '"> </div> </div> </div>';

    $( '#books' ).append( newBook );
}

function createNewScrapbook( scraplook ) {
    var newname = prompt( PROMPT_BOOKS, "" );
    if( newname ) {
        var isold = isExistsAndcheckName( newname );
        if( isold ) {
            alert( INPUT_BOOKS );
            return;
        }

        $.ajax( {
                    type: "POST",
                    url: url_prefix + "saveProfile.php",
                    cache: false,
                    data: {section: 'scrapbooks', content: newname, scraplook: scraplook},
                    success: function( data ) {
                        data = $.parseJSON( data );
                        if( data.error > 0 ) {
                            alert( data.message );
                            return;
                        }
                        var bookid = data.bookid;
                        $( '#sel_book_name' ).val( bookid );
                        addBook( newname, bookid, data.scraplookCoverUrl );
                        addPopup( bookid );
                    }
                } );
    }
}

$( document ).ready( function() {
    $( '#scrapbooksButton' ).add( '#scraproomButton' ).click( function() {
        createNewScrapbook( 'default' );
    } );
} );
