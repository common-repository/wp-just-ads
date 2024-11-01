;(function($){    
   var loader = '<div id="ads-loader"><div class="left stick"><span></span></div><div class="right stick"><span></span></div></div>';
   var previous_event = Array(); 

   $( '.ads-item a' ).on( 'click' , loadPage );
   $( 'a.adtitle' ).on( 'click' , loadSingle );
   $( '.ads-categories a' ).on( 'click' , loadCategory );
   $( '#ads-seach-form' ).submit( loadSearchResults );
   $( '#ads-search-button' ).on( 'click', loadSearchResults );
   $( '#captcha-refresh').on( 'click', refreshCaptcha );
   $( '.ads-nav-item').not('.current').on( 'click', loadCategory );
   $( window ).hashchange( function( evt ){
       loadFromHistory( evt );       
   });
   
   function re_init( evt, data, preventReloading ){
        previous_event.push( evt );        
        
        //calculate special occassion doing search         
        if( preventReloading === undefined ){
            $('.ads-page-content').html( data );
        } else {
            $('#ads-search-results').html( data );
        }
        
        $('.ads-categories a').on( 'click' , loadCategory ); 
        $('a.adtitle').on( 'click' , loadSingle );
        $( '#ads-seach-form' ).submit( loadSearchResults );
        $( '#ads-search-button' ).on( 'click', loadSearchResults );
        $( '#captcha-refresh').on( 'click', refreshCaptcha );
        $( '.ads-nav-item').not('.current').on( 'click', loadCategory );
        bindform();
        ListenToForm();
        initFormatter();
        correctCategoryItems();
        initUploader();
   }
   
   function goBack(){
      var elem = previous_event[ previous_event.length - 1 ].currentTarget;
      elem.click();
   }
   
   function bindform(){
        $('.ads-error-message').fadeOut( 300 );
        $('#ads-form').submit(function( event ){
           var title = $('#ads-title').val(),           
               content = $('#ads-content').val(),
               price = $('#ads-price').val(),
               contactName = $('#ads-name').val(),
               phoneNum = $('#ads-phone').val(),
               location = $('#ads-location').val(),
               email = $('#ads-email').val(),
               category = $('#ads-category').val(),               
               captcha = $('#ads-confirm').val(),               
               $that = $(this);
                   
            if ( !ValidateBasicFields( title, content, price, contactName, phoneNum, location, email, category, captcha ) ) { 
                $('.ads-error-message').html("Validation Error").fadeIn( 300 );
                return false;
            }
            
            $that.html( loader ); 
            $.ajax({  
                    type: "post",  
                    url: jads_vars.url,
                    data: { action : "jads_createAds",
                                nonce : jads_vars.nonce,            		
                                ads_title : title,
                                ads_content : content,
                                ads_price : price,
                                ads_email : email,
                                ads_location : location,
                                ads_contact_name : contactName,
                                ads_phone_number : phoneNum,
                                ads_category : category,
                                ads_confirm : captcha,
                                ads_featured : JSON.parse( JADS_FEATURED_IMAGE.response )
                        },  
                    success: function(data){                       
                       $that.html( '<div class="jads-message"><h3>'+data+'</h3></div>' );          
                    }
             });

           event.preventDefault();
        });   
    }
    
    function loadPage( evt ){
        var template = $(this).attr('id');
        window.history.pushState( {"html": $( document ).html() },"", clearUrlFromHash( window.location.href )+'#'+template );
        
        $('.ads-page-content').html( loader );
        $.ajax({  
                type: "post",  
                url: jads_vars.url,
                data: { 
                        action : "jads_get_ajax_template",                                    		
                        template : template    
                    },  
                success: function(data){                     
                   re_init( evt, data );
                }
         });
        evt.preventDefault();
    }
    
    function loadFromHistory( evt ){
        var template = (window.location.hash).replace('#','');
        $('.ads-page-content').html( loader );
        $.ajax({  
                type: "post",  
                url: jads_vars.url,
                data: { 
                        action : "jads_get_ajax_template",                                    		
                        template : template    
                    },  
                success: function(data){                     
                   re_init( evt, data );
                }
         });
    }
    
    function loadCategory( evt ){
        var template = $(this).attr('id'),
            category = $(this).data('category'),
            page = $( evt.currentTarget ).data( 'page' ),
            current_page = $( '#ads-current-page' ).data( 'page' );       
        
        if( !template ){
            template = $( evt.currentTarget ).data( 'function' );
            category = $( evt.currentTarget ).data( 'category' );            
        }       
        
        function proceed( template, category, page, current_page ){            
            window.history.pushState({},"", clearUrlFromHash( window.location.href )+'#'+template );
            
            if( $('#ads-search-results').length == 0 ){
                $('.ads-page-content').html( loader );
            }
            else {
                $('#ads-search-results').html( loader );
            }
            
            $.ajax({  
                    type: "post",  
                    url: jads_vars.url,
                    data: { 
                            action : "jads_get_ajax_template", 
                            template: template,
                            category : category,
                            page : page,
                            current_page : current_page 
                        },  
                    success: function(data){                     
                       re_init( evt, data );
                    }
             });
        }        
        proceed( template, category, page, current_page );
        evt.preventDefault();
    }
    
    function loadSearchResults( evt ){
        var category = $('#ads-search-in-category').val(),
            keyword = $( '#ads-search' ).val(),
            minimumPrice = $( '#ads-min-price' ).val(),
            maximumPrice = $( '#ads-max-price').val(),
            cities = $( '#search-by-city' ).val(),
            page = $( evt.currentTarget ).data( 'page' ),
            current_page = $( '#ads-current-page' ).data( 'page' );         
           
            
        $('#ads-search-results').html( loader );
        $.ajax({  
                type: "post",  
                url: jads_vars.url,
                data: { 
                        'action'    : "jads_get_ajax_template", 
                        'template'  : 'search_results',
                        'category'  : category,
                        'keyword'   : keyword,
                        'minimum_price' : minimumPrice,
                        'maximum_price' : maximumPrice,
                        'cities'    : cities,
                        'page'  : page,
                        'current_page'  : current_page
                    },  
                success: function(data){                     
                   re_init( evt, data, true );
                }
         });
         
        evt.preventDefault();
    }
    
    function loadSingle( evt ){
        var id = $(this).attr('id'),
            template = 'single_item';
        window.history.pushState({},"", clearUrlFromHash( window.location.href )+'#'+template );
        $('.ads-page-content').html( loader );
        $.ajax({  
                type: "post",  
                url: jads_vars.url,
                data: { 
                        action : "jads_get_ajax_template", 
                        template: template,
                        id : id
                    },  
                success: function(data){                     
                   re_init( evt, data );
                }
         });
        
        evt.preventDefault();
    }
    
    function refreshCaptcha(){
        $.ajax({  
                type: "post",  
                url: jads_vars.url,
                data: { 
                        action : "jads_captcha"                       
                    },  
                success: function(data){  
                   data = data.replace('/>0', '/>');
                   $( '.ads-captcha' ).replaceWith( data );
                   console.log( data );
                }
         });
    }
    
    function clearUrlFromHash( url ){
        firstOccur = url.indexOf( '#' );
        return url.substr( 0, firstOccur );
    } 
    
    function initFormatter(){
        $('#ads-phone').formatter({
                    'pattern': '{{999}}-{{999}} {{999}} {{9999}}',
                    'persistent': false              
        });        
    }
    
    function ListenToForm(){
        $('#ads-form input').on('keyup' ,function( evt ){
            
            switch( $(this).attr('type') ){
                case 'number': validateNumber( evt, $(this) ); break;
                case 'email': validateEmail( evt, $(this) ); break;
            }
        });
        
        function validateEmail( evt, elem ){
            setTimeout(function(){
                var regex = new RegExp ('[a-zA-Z\.0-9_\-]+@[a-zA-Z0-9]+\.[a-z]+');
                if( !regex.test( elem.val() ) ){
                    elem.addClass('error');
                     setTimeout(validateEmail,10000);
                }
            },10000);
        }
        
        function validateNumber( evt, elem ){      
           
            if ( isLetterOrSymbol( evt.keyCode ) && !isCodeFromNumpad( evt.keyCode ) ){
                elem.val(0);
            }
        }
        
        function isCodeFromNumpad ( code ){
            if( code >= 97 && code <=105){
                return true;
            }
            return false;
        }
        
        function isLetterOrSymbol( code ){
            if( (code > 64 && code < 91) || (code > 185 && code < 193) || (code > 218 && code < 223) ){
                return true;
            }
            return false;
        }
    }
    function ValidateBasicFields(){
        if( arguments.length  > 0 ){
           for( var cnt=0; cnt < arguments.length; cnt++ ){
               if( arguments[cnt] === '' ) return false; 
           }
        }
        return true;
    }
    
    function correctCategoryItems(){
        var categoryItems = $('table[id^=ads-category-item-content-] > tbody > tr:not(:has(.ads-price))');        
        
        $.each( categoryItems ,function( cnt, val ){ 
            $( val ).html( $(val).html() + '<td class="ads-price" rowspan="3"></td>');            
        });
    }
    
    function initUploader(){
        var uploader = new plupload.Uploader( jads_vars.plupload );
        
        uploader.bind( 'Init', function(up){
            var uploadDiv = $('#plupload-upload-ui');
            if( up.features.dragdrop ){
                uploadDiv.addClass( 'drag-drop' );
                $('#drag-drop-area')
                        .bind( 'dragover.wp-uploader', function(){ uploadDiv.addClass( 'drag-over' ); })
                        .bind( 'dragleave.wp-uploader.drop.wp-uploader', function(){ uploadDiv.removeClass('drag-over'); });
            }
            else { 
                uploadDiv.removeClass('drag-drop');
                $('drag-drop-area').unbind('.wp-uploader'); }       
                resetProgressBar();
        });
        uploader.init();
        
        
        uploader.bind( 'FilesAdded', function( up, files ){
            resetProgressBar();
            var tenmb = 10 * 1024 * 1024,
                max = parseInt( up.settings.max_file_size, 10 );
              
                
            plupload.each( files, function( file ){
               if( max > tenmb && file.size > tenmb && up.runtime != 'html5' ){
                   console.log( 'file size problem' );
               } else {
                 console.log( file );
                 $('.drag-drop-buttons #uploaded').html( file.name );
               }               
            });
           
            up.refresh();
            up.start();
        });
        
        
        uploader.bind('UploadProgress', function(up, file) {
            var progressBarValue = up.total.percent;            
            $('#progressbar').fadeIn().progressbar({
                value: progressBarValue
            });           
        });
        
        uploader.bind( 'FileUploaded', function( up, file, response ){
            window.JADS_FEATURED_IMAGE = response;
            console.log( 'sended' );
        });
        
        function resetProgressBar(){
            $('#progressbar').fadeIn().progressbar({
                value: 0
            });             
        }
    }
})(jQuery);

