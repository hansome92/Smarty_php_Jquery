/**
 * File: ${FILE_NAME}
 * Created: ${DATE}  ${TIME}
 * Upgrade, LLC: Upgrade Your Everything
 * www.upgradeyour.com       903-3CODERS
 */


    // Make the height of all items the same as the tallest item  within the set
$.fn.equal_heights = function() {
    var tallest_height = 0;
    $( this ).each( function() {
        if( $( this ).height() > tallest_height ) {
            tallest_height = $( this ).height();
        }
    } );
    return $( this ).height( tallest_height );
};

// Make the width of all items the same as the widest item within the set
$.fn.equal_width = function() {
    var widest_width = 0;
    $( this ).each( function() {
        if( $( this ).width() > widest_width ) {
            widest_width = $( this ).width();
        }
    } );
    return $( this ).width( widest_width );
};

// Vertically align a block element's content
$.fn.gallery_valign = function( container ) {
    return this.each( function( i ) {
        if( container == null ) {
            container = 'div';
        }
        var el = $( this ).find( ".g-valign" );
        if( !el.length ) {
            $( this ).html( "<" + container + " class=\"g-valign\">" + $( this ).html() + "</" + container + ">" );
            el = $( this ).children( container + ".g-valign" );
        }
        var elh = $( el ).height();
        var ph = $( this ).height();
        var nh = (ph - elh) / 2;
        if( nh < 1 ) {
            nh = 0;
        }
        $( el ).css( 'margin-top', nh );
    } );
};

// Get the viewport size
$.gallery_get_viewport_size = function() {
    return {
        width:  function() {
            return $( window ).width();
        },
        height: function() {
            return $( window ).height();
        }
    };
};

function alignAndFitPicturesToGallery( items ) {
    items.equal_heights().equal_width().gallery_valign();

    items.hover( function() {
        $( this ).addClass( "g-hover-item" );
        $( this ).find( '.wideFbPic' ).addClass( "widerFbPic" );
        $( this ).find( '.tallFbPic' ).addClass( "tallerFbPic" );
        $( this ).gallery_valign();

    }, function() {
        $( this ).removeClass( "g-hover-item" );
        $( this ).find( '.wideFbPic' ).removeClass( "widerFbPic" );
        $( this ).find( '.tallFbPic' ).removeClass( "tallerFbPic" );
        $( this ).gallery_valign();
    } );

}

$( document ).ready( function() {

    $( "body" ).ajaxStart( function() {
        $( this ).addClass( "loading" );
    } );

    $( "body" ).ajaxStop( function() {
        $( this ).removeClass( "loading" );
    } );

    alignAndFitPicturesToGallery( $( '.g-item' ) );

    /*********** SELECTED ALBUM **********/
    $( ".g-item" ).select( ".fbAlbum" ).click( function( e ) {
        e.preventDefault();
        albumId = this.id;
        $.ajax( {
                    type:    'POST',
                    url:     'getPicsFromAlbum.php',
                    data:    {'album': albumId},
                    success: function( data ) {
                        //$("#modalHolder").show().html(data)
                        $.fancybox( data, {
                            autoSize:   true,
                            scrolling:  'auto',
                            fitToView:  true,
                            content:    data,
                            afterShow:  function() {
                                alignAndFitPicturesToGallery( $( ".fbPic" ) );
                            },
                            onComplete: function() {
                                $( '.fancybox-inner' ).find( '.fbPic' ).each( function() {
                                    $( this ).click( function( e ) {
                                        e.preventDefault();
                                        //var chkbx = $( this ).find( 'input' );
                                        //chkbx.attr( "checked", !chkbx.attr( "checked" ) );
                                    } );
                                } );
                            }
                        } );

                    }
                } );
    } );

    $( ".fbPic" ).live( 'click', function( e ) {
        //e.preventDefault();
        $( this ).toggleClass( 'selectedPictures' );
    } );
    /*
     $("#selectPicturesForImport").live('click', function (e) {
     e.preventDefault();
     //serialize the selected pictures
     var selectedPictures = new Array();
     $('.selectedPictures').each(function (i, e) {
     selectedPictures.push(e.id);
     });
     //make ajax call to get db selected pictures
     $.ajax({
     type:'POST',
     url:'storeSelectedPics.php',
     data:{'pictures':selectedPictures},
     success:function (data) {
     alert(data);
     //  $('element_to_pop_up').bPopup({});
     }
     });
     });
     */

} );