function isNull( obj ) {
    return (obj == undefined || obj == "undefined" || obj == null || obj == "null");
}

function getAspectRatioResize( width, height, max ) {
    width = parseInt( ''+width );
    height = parseInt( ''+height );
    max = parseInt( ''+max );
    if( height > max || width > max ) {
        if( height > width ) {
            width = width / (height / max);
            height = max;

        } else {
            height = height / (width / max);
            width = max;
        }
    }
    return {'height': height, 'width': width};
}
