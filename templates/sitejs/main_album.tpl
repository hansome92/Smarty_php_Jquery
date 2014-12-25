{fetch file='albums/tmpl.min.js'}
{fetch file='albums/load-image.min.js'}
{fetch file='albums/canvas-to-blob.min.js'}
{fetch file='albums/bootstrap.min.js'}
{fetch file='albums/bootstrap-image-gallery.min.js'}
{fetch file='js/fileup/jquery.iframe-transport.js'}
{fetch file='js/fileup/jquery.fileupload.js'}
{fetch file='js/fileup/jquery.fileupload-fp.js'}
{fetch file='js/fileup/jquery.fileupload-ui.js'}
{fetch file='js/fileup/locale.js'}
{fetch file='js/fileup/main.js'}

var userId;
$( document ).ready( function() {
userId = $( '#userId' ).val();
$( '.delete-button-album' ).live( 'click', function( e ) {
e.preventDefault();
e.stopImmediatePropagation();
deleteAlbumFromAlbums( $( this ).find( 'a' ) );
} );
$( '.delete-button-photo' ).live( 'click', function( e ) {
e.preventDefault();
e.stopImmediatePropagation();
deletePictureFromAlbum( $( this ).find( 'a' ) );
} );
showFreePhoto( userId );
addAlbumListener();
} );