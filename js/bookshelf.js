/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function Bookself() {
    
        this.loadPage = function ( canvas, data, width, height, isleft ) {
                canvas.backgroundColor = 'rgba(48,62,71,1)';
                canvas.setBackgroundImage(data.backgroundImage, canvas.renderAll.bind(canvas));

                var page_zoom = parseFloat( data.zoom );
                var book_posx = width;
                var default_zoom = 0.22;
                
                book_posx = book_posx / 2;               
                var book_posy = height / 2;
                
                var items = data.images;
                var superClass = this;
                $.each( items, function( index, item ) {
                    
                    if( item.type == "text" ) {
                        var text = new fabric.Text(unescape(item.text), {
                            fontFamily: item.fontFamily,
                            fill:       item.fill,
                            left: parseFloat( item.left ) * default_zoom / page_zoom + book_posx,
                            top:  parseFloat( item.top ) * default_zoom / page_zoom + book_posy,
                            angle:      item.angle,
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
                            superClass.setFilters( canvas, obj, item.filter );
                            obj.selectable = false;
                            canvas.insertAt( obj, index, true );
                        } );
                    } else if( item.type == "group" || item.type == "background" ) {
                       
                        var objarray = [];
                        superClass.loadGroup( canvas, objarray, 0, item, index, {
                            default_zoom: default_zoom,
                            page_zoom:    page_zoom,
                            left:         book_posx,
                            top:          book_posy
                        } );
                    }
                } );                
                canvas.renderAll();
        };

       this.loadGroup =   function ( canvas, subitems, index, item, zorder, settings ) {

            try {
                var superClass = this;
                
                var items = item.items;
                      
                fabric.Image.fromURL( items[index].src, function( obj ) {
                    obj.set( 'left', items[index].left );
                    obj.set( 'top', items[index].top );
                    obj.set( 'scaleX', parseFloat( items[index].scaleX ) );
                    obj.set( 'scaleY', parseFloat( items[index].scaleY ) );
                  
                    obj.setAngle( items[index].angle );
                    superClass.setFilters( canvas, obj, items[index].filter );
                    subitems.push( obj );
                   
                    if( index < items.length - 1 ) {
                        index += 1;
                        superClass.loadGroup( canvas, subitems, index, item, zorder, settings );
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
                return;
            }
        };

        // set filters
       this.setFilters =  function ( canvas, selObj, index ) {
            var f = fabric.Image.filters;
            if( index == 0 ) {
                this.applyFilter( canvas, index, new f.jarques(), selObj );
            } else if( index == 1 ) {
                this.applyFilter( canvas, index, new f.lomo(), selObj );
            } else if( index == 2 ) {
                this.applyFilter( canvas, index, new f.love(), selObj );
            } else if( index == 3 ) {
                this.applyFilter( canvas, index, new f.nostalgia(), selObj );
            } else if( index == 4 ) {
                this.applyFilter( canvas, index, new f.oldBoot(), selObj );
            } else if( index == 5 ) {
                this.applyFilter( canvas, index, new f.orangePeel(), selObj );
            } else if( index == 6 ) {
                this.applyFilter( canvas, index, new f.pinhole(), selObj );
            } else if( index == 7 ) {
                this.applyFilter( canvas, index, new f.sinCity(), selObj );
            } else if( index == 8 ) {
                this.applyFilter( canvas, index, new f.sunrise(), selObj );
            } else if( index == 9 ) {
                this.applyFilter( canvas, index, new f.vintage(), selObj );
            } else if( index == 10 ) {
                this.applyFilter( canvas, index, new f.herMajesty(), selObj );
            } else if( index == 11 ) {
                this.applyFilter( canvas, index, new f.hemingway(), selObj );
            } else if( index == 12 ) {
                this.applyFilter( canvas, index, new f.hazyDays(), selObj );
            } else if( index == 13 ) {
                this.applyFilter( canvas, index, new f.grungy(), selObj );
            } else if( index == 14 ) {
                this.applyFilter( canvas, index, new f.glowingSun(), selObj );
            } else if( index == 15 ) {
                this.applyFilter( canvas, index, new f.crossProcess(), selObj );
            } else if( index == 16 ) {
                this.applyFilter( canvas, index, new f.clarity(), selObj );
            }

        };

      this.applyFilter =  function ( canvas, index, filter, target ) {

            var obj = canvas.getActiveObject();
            if( target != null ) {
                obj = target;
            }
            if( obj == null ) {
                return;
            }

            obj.filters[index] = filter;
            obj.applyFilters( canvas.renderAll.bind( canvas ) );
        };
        
     this.loadData =  function (data, section) {
          
                var width = 92;
                var height = 115;
          
                $('#' + section).append('<canvas id="c' + section + '" width="' + width + '" height="' + height + '"></canvas>');             
              
                 var page0 = new fabric.Canvas("c" + section, {width:width, height:height});  
                 
                 this.loadPage(page0, data, width, height, -1);                               
        };
        
       this.loadBooks =  function () {
            var saveurl = url_prefix + "editor/save.php";
            var bookids = '';
            var isFirst = false;
            
            $('div.bookshelf').find('div.thumb1').each(function() {
                if (isFirst) 
                    bookids += "%";
                
                bookids += $(this).attr('id');                
                isFirst = true;
            });
            var curObject = this;
              $.ajax( { type:      "POST",
                url:     saveurl,
                cache:   false,
                data:    {method: 'bookcase', bookId: bookids},
                success: function( msg ) {
                    
                    if (msg == "null") {
                        return;
                    }
                    else {
                        var json = JSON.parse(msg);
                        for (var len = json.length, i = 0; i < len; i++) {   
                       
                            if (json[i].book != null){                              
                                curObject.loadData(json[i].book, json[i].id);
                            }
                        }
                    }
                }
            } );
        };
        
        this.viewBook = function(bookid) {
             var xheight = $( window ).height() * 99 / 100;
             var xwidth = $( window ).width() * 99 / 100;

             var preview_offset = 40;           
             var page = "view.php";
   
            var ceditor = '<div><iframe id="popupEditor" width="' + xwidth + '" height="' + xheight + '" src="' + url_prefix + 'editor/' + page + '?bookId=' + bookid + '"  scrolling="no" frameborder="0" ></iframe></div>';

            $.colorbox( {
                            html:       ceditor,
                            onLoad:     function() {
                            },
                            onComplete: function() {
                                var scrollx = $( document ).scrollTop();
                                $( document ).scrollTop( scrollx + xheight / 50 );                            
                            }
                        } );
        };
        
        this.addEvent = function() {
            var superClass = this;
            $('div.shelf').find('div.thumb1').each(function() {
                $(this).click(function(){
                    superClass.viewBook($(this).attr('id'));
                });                
            });          
            $('div.shelf').find('div.title').each(function() {
                $(this).click(function(){
                    superClass.viewBook($(this).attr('action'));
                });                
            });      
        };
};

