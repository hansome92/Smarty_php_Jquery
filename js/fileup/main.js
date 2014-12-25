/*
 * jQuery File Upload Plugin JS Example 6.7
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*jslint nomen: true, unparam: true, regexp: true */
/*global $, window, document */
var cur = 0;
$( function() {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $( '#fileupload' ).fileupload();

    $( '#fileupload' ).fileupload( 'option', { url: url_prefix + 'fileuploader/'
    } ).bind( 'fileuploadsubmit',function( e, data ) {
                  cur++;
              } ).bind( 'fileuploaddone', function( e, data ) {
                            cur--;
                            if( cur <= 0 ) {
                                $( '.unfile-album' ).click().children().click();
                                cur = 0;
                            }
                        } );

    // Enable iframe cross-domain access via redirect option:
    // $( '#fileupload' ).fileupload( 'option', 'redirect', window.location.href.replace( /\/[^\/]*$/, '/cors/result.html?%s' ) );

    // Load existing files:
    $( '#fileupload' ).each( function() {
        var that = this;
        $.getJSON( this.action, function( result ) {
            if( result && result.length ) {
                $( that ).fileupload( 'option', 'done' ).call( that, null, {result: result} );
            }
        } );
    } );

    // $( '.unfile-album' ).click();

} );
