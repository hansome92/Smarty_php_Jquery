/**
 * Date: 11/14/12
 * Time: 2:27 PM
 */


function Undo() {
    this.undoItem = null;
    this.curItem = null;
    this.action = "";
    this.isUndo = false;
    this.undoIndex = 0;

    this.runUndo = function() {
        var option = this.action.split( '-' );
        if( option.length == 2 ) {
            switch( option[0] ) {
                case "add":
                    this.undoAdd( option[1], "-R" );
                    break;
                case "delete":
                    this.undoDelete( option[1], "-R" );
                    break;
                case "move":
                    this.undoMove( option[1], "-R" );
                    break;
                default:
                    break;
            }
        }
    };

    this.runRedo = function() {
        var option = this.action.split( '-' );
        if( option.length == 3 ) {
            switch( option[0] ) {
                case "add":
                    this.undoAdd( option[1] );
                    break;
                case "delete":
                    this.undoDelete( option[1] );
                //#TODO: should there be a break here? I put it in, needs to be tested.
                 break;
                case "move":
                    this.undoMove( option[1] );
                    break;
            }
        }
    };

    this.undoMove = function( action, undoKey ) {
        if( isNull( undoKey ) ) {
            undoKey = "";
        }
        this.action = "move-" + action + undoKey;
        var json;
        if( action == "background" ) {
            json = JSON.parse( this.undoItem );
            this.undoItem = '{"left":"' + g_selBackground.get( 'left' ) + '",';
            this.undoItem += '"top":"' + g_selBackground.get( 'top' ) + '"}';
            g_selBackground.set( 'left', parseFloat( json.left ) );
            g_selBackground.set( 'top', parseFloat( json.top ) );
            setDistances();
        } else {
            json = JSON.parse( this.undoItem );
            this.setUndoItem( this.curItem );
            this.curItem.set( 'left', parseFloat( json.left ) * default_zoom / page_zoom + g_selBackground.get( 'left' ) );
            this.curItem.set( 'top', parseFloat( json.top ) * default_zoom / page_zoom + g_selBackground.get( 'top' ) );
            this.curItem.set( 'scaleX', parseFloat( json.scaleX ) * default_zoom / page_zoom );
            this.curItem.set( 'scaleY', parseFloat( json.scaleY ) * default_zoom / page_zoom );
            this.curItem.setAngle( parseFloat( json.angle ) );
        }
        canvas.renderAll();
    };

    this.undoDelete = function( action, undoKey ) {
        if( isNull( undoKey ) ) {
            undoKey = "";
        }
        this.action = "delete-" + action + undoKey;
        var json;
        // redo
        if( undoKey == "" ) {
            this.setUndoItem( this.curItem );
            canvas.remove( this.curItem );
        }
        //undo
        else {
            json = JSON.parse( this.undoItem );
            this.loadData( json );
        }
    };

    this.undoAdd = function( action, undoKey ) {
        if( isNull( undoKey ) ) {
            undoKey = "";
        }
        this.action = "add-" + action + undoKey;
        var json;
        if( action == "text" || action == "trinket" ) {
            json = JSON.parse( this.undoItem );
            $( '#addTextBox' ).hide();
            if( undoKey == "" ) {
                this.loadData( json );
                this.undoItem = "{}";
            } else {
                this.setUndoItem( this.curItem );
                canvas.remove( this.curItem );
            }
        } else if( action == "border" ) {
            json = JSON.parse( this.undoItem );
            this.setUndoItem( this.curItem );
            canvas.remove( this.curItem );
            this.loadData( json );
        } else if( action == "filter" ) {
            var selObj = this.curItem;
            json = JSON.parse( this.undoItem );
            this.setUndoItem( this.curItem );
            var filter;
            if( this.curItem.type == "group" ) {
                var items = selObj.getObjects();
                selObj = items[1];
                filter = json.items[1].filter;
            } else {
                filter = json.filter;
            }
            removeFilter( selObj );
            setFilters( selObj, filter );
        } else if( action == "background" ) {
            json = JSON.parse( this.undoItem );
            this.setUndoItem( g_selBackground );
            canvas.remove( g_selBackground );
            g_selBackground = null;
            json.type = "group";
            this.curItem = this.loadData( json );
        } else if( action == "backgroundColor" ) {
            canvas.backgroundColor = g_UndoObject.undoItem;
            g_UndoObject.undoItem = g_UndoObject.curItem;
            g_UndoObject.curItem = canvas.backgroundColor;
            canvas.renderAll();
        } else if( action == "backgroundImage" ) {
            canvas.setBackgroundImage( g_UndoObject.undoItem, canvas.renderAll.bind( canvas ) );
            g_UndoObject.undoItem = g_UndoObject.curItem;
            g_UndoObject.curItem = canvas.backgroundImage.src;
        }
    };

    this.loadData = function( item ) {
        var back_posx = g_selBackground.get( 'left' );
        var back_posy = g_selBackground.get( 'top' );
        if( item.type == "text" ) {
            var text = new fabric.Text( unescape( item.text ), {
                fontFamily: item.fontFamily,
                fill: item.fill,
                left: parseFloat( item.left ) * default_zoom / page_zoom + back_posx,
                top: parseFloat( item.top ) * default_zoom / page_zoom + back_posy,
                angle: item.angle,
                scaleX: parseFloat( item.scaleX ) * default_zoom / page_zoom,
                scaleY: parseFloat( item.scaleY ) * default_zoom / page_zoom
            } );
            text.textAlign = item.textAlign;
            canvas.insertAt( text, this.undoIndex );
            $( '#addTextBox' ).hide();
        } else if( item.type == "image" ) {
            var imgObj = null;
            var index = this.undoIndex;
            fabric.Image.fromURL( item.src, function( obj ) {
                obj.set( 'left', parseFloat( item.left ) * default_zoom / page_zoom + back_posx );
                obj.set( 'top', parseFloat( item.top ) * default_zoom / page_zoom + back_posy );
                obj.set( 'scaleX', parseFloat( item.scaleX ) * default_zoom / page_zoom );
                obj.set( 'scaleY', parseFloat( item.scaleY ) * default_zoom / page_zoom );
                obj.setAngle( item.angle );
                setFilters( obj, item.filter );
                canvas.insertAt( obj, index );
            } );
        } else if( item.type == "group" ) {
            var objarray = [];
            imgObj = loadGroup( objarray, 0, item, this.undoIndex );
        }
        return canvas.item( this.undoIndex );
    };
    this.setUndoItem = function( item ) {
        if( isNull( item ) ) {
            this.undoItem = "";
            return;
        }
        var type = item.type;
        if( type == "image" ) {
            this.undoItem = getImageToJson( item );
        } else if( type == "text" ) {
            this.undoItem = getTextToJson( item );
        } else if( type == "group" ) {
            this.undoItem = getGroupToJson( item );
        }
        this.undoIndex = canvas.getObjects().indexOf( item );
    };
    this.setCurItem = function( item ) {
        this.curItem = item;
    };
}
