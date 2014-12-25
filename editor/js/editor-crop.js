$( '#confirmCropButton' ).live( 'click', function() {
    var sel = jcrop.tellSelect(); //this gives top left coord, bottom right coord, height and width.
    var src = $( '.imageToBeCropped' ).attr( 'src' );
    //alert( sel.x + ' ' + sel.y + ' ' + sel.x2 + ' ' + sel.y2 + ' ' + sel.w + ' ' + sel.h + ' ' + src );
    if( sel.x - sel.x2 == 0 || sel.y - sel.y2 == 0 ) {
        alert( 'You haven\'t selected anything to crop!' );
        return false;
    }
    $.ajax( {  type: "POST",
                url: 'crop.php',
                cache: false,
                data: {'x1': sel.x, 'y1': sel.y, 'x2': sel.x2, 'y2': sel.y2, 'src': src },
                success: function( msg ) {
                    try {
                        msg = $.parseJSON( msg );
                        if( isNull( msg.valid ) || msg.valid == false || msg.valid == 0 || msg.valid == 'false' ) {
                            alert( msg );
                        } else {
                            alert( 'Image successfully cropped and saved!' );
                            $( '#pho_' + msg.albumid ).click();
                        }
                    } catch( e ) {
                        alert( msg );
                    }
                    return false;
                },
                error: function( msg ) {
                    alert( msg );
                    return false;
                }
            } );
} );

function launchCropTool() {
    var selectedObject = canvas.getActiveObject();
    if( isNull( selectedObject ) || selectedObject.type != "image" ) {
        alert( 'You must select a photo that you\'ve already added to your scrapbook' );
        return false;
    }

    var holderHtml = '<div id="imageToBeCroppedHolder">';
    holderHtml = holderHtml + '<div id="cropInstructions"><span class="instTop">Drag to select an area on the image, then click "crop" to crop.</span><br>';
    holderHtml = holderHtml + '<span class="instBot"> Both the new crop and the original image will still be saved.</span></div>';
    holderHtml = holderHtml + '<br><img src="' + selectedObject.getSrc() + '" class="imageToBeCropped">' + '<br>';
    holderHtml = holderHtml + '<img src="images/cropPopupCropButton.png" id="confirmCropButton">';
    holderHtml = holderHtml + '</div>';
    $( document ).bind( 'cbox_complete', function() {
        $( '.imageToBeCropped' ).Jcrop( {boxWidth: 400, boxHeight: 400}, function() {
            jcrop = this;
            $( '.jcrop-holder' ).css( 'display', 'inline-block' );
            var pos = $( '#imageToBeCroppedHolder' ).position();
            $( '#cboxClose' ).css( {'left': (pos.left + 10) + 'px', 'top': (-1 * pos.top - 20) + 'px'} );
            $.colorbox.resize();
        } );
    } );
    $.colorbox( {
                    html: holderHtml,
                    scrolling: true,
                    fastIframe: false,
                    onLoad: function() {
                        $( '#cboxClose' ).css( {height: '45px', width: '45px', 'position': 'absolute'} );
                    },
                    onComplete: function() {
                        jcrop.release();
                    },
                    onClose: function() {
                        jcrop.release();
                        jcrop.destroy();
                        $( '.imageToBeCropped' ).remove();
                        $( '.jcrop-holder' ).remove();
                        $( '#cboxClose' ).css( {height: 0, width: 0, 'position': 'relative'} );
                    },
                    onClosed: function() {
                        jcrop.release();
                        jcrop.destroy();
                        $( '.imageToBeCropped' ).remove();
                        $( '.jcrop-holder' ).remove();
                    }
                } );

}

// This function is bound to the onRelease handler...
// In certain circumstances (such as if you set minSize
// and aspectRatio together), you can inadvertently lose
// the selection. This callback re-enables creating selections
// in such a case. Although the need to do this is based on a
// buggy behavior, it's recommended that you in some way trap
// the onRelease callback if you use allowSelect: false
function releaseCheck() {
    jcrop.setOptions( { allowSelect: true } );
    $( '#can_click' ).attr( 'checked', false );
}