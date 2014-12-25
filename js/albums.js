function addAlbum() {
    var albumName = $( '#albumName' ).val();
    if( albumName.length < 3 ) {
        alert( 'Your album name is too short!' );
        return;
    }
    $.ajax( {
                type: 'POST',
                url: url_prefix + 'albums/addAlbum.php',
                context: this,
                dataType: "json",
                data: {name: albumName},
                success: function( data ) {
                    var albumName = $( '#albumName' ).val();
                    if( data.error == 0 ) {
                        $( '#albumForm' ).append( newAlbumString( data.albumId, '/images/unfiledPicturesAlbum.png', albumName ) );
                        addAlbumListener();
                    }
                }
            } );
}

function getPicsByAlbum( albumid ) {
    if( albumid == 'default' ) {
        showFreePhoto( userId );
        return;
    }

    $.ajax( {
                type: 'POST',
                url: url_prefix + 'albums/getPictures.php',
                context: this,
                data: {albumId: albumid},
                dataType: "json",
                success: function( data ) {
                    $( '#freePhotoList' ).html( '' );
                    $.each( data.picList, function( k, photo ) {
                        $( '#freePhotoList' ).append( newPhotoString( photo.photoid, photo.userid, photo.filename ) );
                    } );
                    addDragDrop();
                }
            } );
}

function addOnePhotoToAlbum( picid, albumid ) {
    var picids = [];
    picids[0] = picid;
    addPhotosToAlbum( picids, albumid );
}

function addPhotosToAlbum( picids, albumid ) {
    $.ajax( {
                type: 'POST',
                url: url_prefix + 'albums/addPics.php',
                data: {picList: picids, albumId: albumid},
                dataType: "json",
                success: function( data ) {
                }
            } );
}

function deletePictureFromAlbum( closeBtn ) {
    var picids = [], albumid = getCurrentAlbumid();
    if( isNull( albumid ) || albumid == 'default' ) {
        albumid = 0;
    }
    picids[0] = $( closeBtn ).attr( 'photoid' );
    if( window.confirm( 'Are you sure you want to delete this photo?' ) ) {
        $.ajax( {
                    type: 'POST',
                    url: url_prefix + 'albums/deletePics.php',
                    data: {picList: picids, albumId: albumid},
                    dataType: "json",
                    success: function( data ) {
                        $( closeBtn ).parent().parent().remove();
                    }
                } );
    }
}

function deleteAlbumFromAlbums( closeBtn ) {
    var albumids = [], $album;
    albumids[0] = $( closeBtn ).attr( 'albumid' );
    $album = $( '#album_' + albumids[0] );
    if( window.confirm( 'Are you sure you want to delete "' + $album.find( '.album-name span' ).html() + '"?' ) ) {
        $.ajax( {
                    type: 'POST',
                    url: url_prefix + 'albums/deleteAlbums.php',
                    data: {albumList: albumids},
                    dataType: "json",
                    success: function( data ) {
                        $album.remove();
                    }
                } );
    }
}

function showFreePhoto( userId ) {
    if( !isNull( userId ) ) {
        $.ajax( {
                    type: 'POST',
                    url: url_prefix + 'albums/freePhoto.php',
                    data: {},
                    dataType: "json",
                    success: function( data ) {
                        if( data.error == 0 ) {
                            $( '#freePhotoList' ).html( '' );
                            $.each( data.freePhotoList, function( i, photo ) {
                                if( i == 0 ) {
                                    $( '#album_default img' ).attr( 'src', url_prefix + 'userpics/' + photo.userid + '/' + photo.filename );
                                }
                                $( '#freePhotoList' ).append( newPhotoString( photo.photoid, photo.userid, photo.filename ) );
                            } );
                            addDragDrop();
                        }
                    }
                } );
    }
}

var selected = $( [] ), offset = {top: 0, left: 0};
function addDragDrop() {
    makeImagesFit( $( '.photo_div' ) );
    $( '#freePhotoList' ).selectable( { filter: '.photo_div' } );
    $( '#freePhotoList .photo_div' ).css( 'z-index', '99' ).draggable( {
                                                                           containment: '#albums',
                                                                           helper: 'original',
                                                                           opacity: 0.5,
                                                                           revert: true,
                                                                           stack: '.photo_div',
                                                                           cursor: 'move',
                                                                           stop: function( e, ui ) {
                                                                           }
                                                                       } );
}

function makeImagesFit( $imageHolders ) {
    $imageHolders.each( function() {
        var $this = $( this ), $img = $this.find( 'img' );
        $img.load( function() {
            var $newSize = getAspectRatioResize( $img.width(), $img.height(), $this.width() );
            $img.width( $newSize.width ).height( $newSize.height );
            $img.css( 'margin-top', ($this.height() - $newSize.height) / 2 );
        } );
    } );
}

function getCurrentAlbumid() {
    return $( '.select-album' ).first().attr( 'albumid' );
}

function addAlbumListener() {
    makeImagesFit( $( '.album-img-area' ) );
    $( '#albums .each-album' ).click(function() {
        if( $( this ).hasClass( 'select-album' ) ) {
            return;
        }
        $( '.select-album' ).removeClass( 'select-album' );
        $( this ).addClass( 'select-album' );
        getPicsByAlbum( $( this ).attr( 'albumid' ) );
    } ).droppable( {
                       accept: ".photo_div",
                       activeClass: "drop-active",
                       hoverClass: "drop-hover",
                       drop: function( event, ui ) {
                           $( 'body' ).css( 'cursor', 'auto' );
                           var targetAlbumid = $( event.target ).attr( 'albumid' );
                           //trying to drop a photo into the album it's already in, or the default album
                           if( targetAlbumid == getCurrentAlbumid() || targetAlbumid == 'default' ) {
                               return;
                           }
                           var photoids = [];
                           photoids[0] = ui.draggable.attr( 'photoid' );
                           $( ".ui-selected" ).each( function() {
                               photoids.push( $( this ).attr( 'photoid' ) );
                               $( this ).remove();
                           } );
                           addPhotosToAlbum( photoids, targetAlbumid );
                           $( ui.draggable ).remove();
                       }
                   } );
}

function newAlbumString( albumId, filename, albumName ) {
    var albumString = '<div class="each-album" id="album_' + albumId + '" albumid="' + albumId + '"><div class="delete-button-album">';
    albumString = albumString + '<a class="remove" title="' + albumId + '" >&times;</a></div><div>';
    albumString = albumString + '<img class="album-img" alt="' + albumId + '" src="' + url_prefix + filename + '"/></div><div class="album-name" ><span>' + albumName + '</span></div>';
    return albumString;
}

function newPhotoString( photoId, userId, filename ) {
    var photoString = '<div class="photo_div " id="photo_' + photoId + '" photoid="' + photoId + '"> <div class="delete-button-photo">';
    photoString = photoString + '<a class="remove " ' + '" photoid="' + photoId + '">&times;</a></div>';
    photoString = photoString + '<img class="photo" src="' + url_prefix + 'userpics/' + userId + '/' + filename + '" photoid="' + photoId + '"  title=""/></div>';
    return photoString;
}

function openImportFromFlickr( url ) {
    // alert( ' Flickr Importer Event Fired ' );
    document.location = url;
    // alert( 'flickr - ' + url );
}

function openImportFromFacebook( url ) {
    var xheight = $( window ).height() * 80 / 100;
    var xwidth = $( window ).width() * 80 / 100;
    xwidth = ( xwidth >= 880 ? xwidth : 880 );

    var ceditor = '<div><iframe id="popupImportFacebook" width="' + xwidth + '" height="' + xheight + '" src="' + url + '"  scrolling="yes" frameborder="0" ></iframe></div>';

    $.colorbox( {
                    //href:       url,
                    html: ceditor,
                    onLoad: function() {
                    },
                    onComplete: function() {
                        var scrollx = $( document ).scrollTop();
                        $( document ).scrollTop( scrollx + xheight / 50 );
                    }
                } );

    $( document ).bind( 'cbox_closed', function() {
        document.location.reload();
        location.reload();

    } );
}