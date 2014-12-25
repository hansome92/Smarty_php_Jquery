/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
  
 
 
$(document).ready(function () {

          if ($('#previewScreen').html() == null){
              return;
          }
          var sheight = $(window).height();
          var swidth = $(window).width();          
          var preview = '<canvas id="previewCanvas" width="' + swidth + '" height="' + sheight + '" ></canvas>';

          $(preview).insertAfter('#avpw_zoom_container');
          canvas = new fabric.Canvas('previewCanvas');
          canvas.backgroundColor = 'rgba(48,62,71,1)';
          canvas.renderAll();  
          f = fabric.Image.filters;
            
          canvas.observe('object:selected', function (e) {
               if (g_selBackground == e.target){            
                    getDistances();
                }
          });
          
          canvas.observe('object:moving', function(e){ 
              issaved = true;
              if (g_selBackground == e.target){
                  setDistances();
              }             
          });                  
          book_posx = Math.round(swidth / 2);
          book_posy = Math.round(sheight / 2);
          
          default_zoom = 1;          
          isPreview = true;
          
          $('#realPage').hide();        
          loadPreview();
          
         $('#avpw_zoom_handle').draggable({
             containment:'.avpw_zoom_slider_container',
             stack:"#avpw_zoom_handle",  
             cursor:'pointer',
             axis: "x",
             appendTo:'body',
             stop:function(e, ui){

                var pos = $(this).offset();
                var con_pos = $('.avpw_zoom_slider_container').offset();
                var posX = pos.left - con_pos.left;
                if (posX < 0)
                    posX = 0;
                var width = parseInt($('#avpw_zoom_slider').css('width'));
                var zoom = default_zoom + Math.round(posX * 10 / width) / 10;    
                zoomCanvas(zoom);

             }             
     });
      $('#scrapbookPageNumber').change(function(){             
          loadPreview();
      });
      $('#previewPage').mouseover(function(e){
         
           $('#realPage').show();
      });
      
      $('#previewPage').mouseout(function(e){

            $('#realPage').hide();
      });
      
      $('.previousPage').click(function () {
        var page = $('#scrapbookPageNumber');
        var currentPage = parseInt(page.val());
        if (currentPage > 0) {          
            page.val(currentPage - 1);
        }    
        loadPreview();
    });

    $('.nextPage').click(function () {
        var page = $('#scrapbookPageNumber');
        var currentPage = parseInt(page.val());
        page.val(currentPage + 1);    
        loadPreview();
    });
    
      $('.avpw_zoom_slider_container').click(function(e){
          var left = parseInt($('#avpw_zoom_slider').css('left'));          
          var width = parseInt($('#avpw_zoom_slider').css('width'));
          var right =  width + left;
          var pos = $('.avpw_zoom_slider_container').offset();
          var px = e.pageX - parseInt(pos.left);
          if (px < left) {
              px = left;
          }
          if (px > right) {
              px = right;
          }
          var posX = px - 10;
          $('#avpw_zoom_handle').css('left', posX );
          var zoom = default_zoom + Math.round(posX * 10 / width) / 10;            
          zoomCanvas(zoom);          
      });    
          
});

function loadPreview(){         
       $.ajax({
                type: "POST",
                url: saveurl,                
                cache: false,
                data:{method:'load', page:$('#scrapbookPageNumber').val(), bookId:$("#book_id").val()},
                success: function(msg){
                    if (msg == "null") {
                        clearCanvas();
                        preLoadBackground();
                        return;
                    }    
                    var json = JSON.parse(msg);    
                    clearCanvas();                    
                    loadBackground(json);                   // loadData(json);
                    
                    setTimeout(function(){loadData(json, false);},200);                     
                    issaved = false;
                }
              });
 }