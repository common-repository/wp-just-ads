<?php

function jads_initialize_scripts(){
    wp_enqueue_script( 'jads-js', plugins_url('js/justads.min.js', JADS_BASEFILE ), array( 'jquery', 'handlersjs', 'hashchange' ), '1.0', true );  
    wp_enqueue_script( 'formatter', plugins_url('js/jquery.formatter.min.js', JADS_BASEFILE ), array( 'jquery', 'jads-js' ), '1.0', true );  
    wp_enqueue_script( 'handlersjs', '/wp-includes/js/plupload/handlers.min.js', array( 'jquery', 'wp-plupload' ), '1.0', true );
    wp_enqueue_script( 'hashchange', plugins_url('js/hashchange.min.js', JADS_BASEFILE ), array( 'jquery' ), '1.0', true );
    
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-progressbar' );
    wp_enqueue_script( 'wp-plupload' );   
    
    
    $plupload = array(
        'runtimes' => 'html5,silverlight,flash,html4',
        'browse_button' => 'plupload-browse-button',
        'container' => 'plupload-upload-ui',
        'drop_element' => 'drag-drop-area',
        'file_data_name' => 'async-upload',
        'multiple_files' => FALSE,
        'max_file_size' => wp_max_upload_size().'b',
        'url' => admin_url('admin-ajax.php'),
        'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
        'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
        'filters' => array(
                        array( 'title' => __("Allowed files" , "jads" ), 'extensions' => 'jpeg,jpg,png,gif' ),                           
                     ),
        'multipart' => TRUE,
        'urlstream_upload' => TRUE,
        'multipart_params' => array(
                                 '_ajax_nonce' => wp_create_nonce('featured_upload'),
                                 'action' => 'jads_upload_image'
                              ),
    );
       
    
    $jads_vars = array(
                'url' => admin_url('admin-ajax.php'),			
                'nonce' => wp_create_nonce('jads-nonce'),
                'plugins_url' => plugins_url('.', JADS_BASEFILE ),
                'successfull_send' => __("The ads sending was succesfull", "jads"),
                'homeUrl' => home_url('/'),
                'plupload' => $plupload
	);
    
    wp_localize_script( 'jads-js', 'jads_vars', $jads_vars );
}

function jads_initialize_stylesheets(){
    wp_enqueue_style('jads-css', plugins_url('stylesheets/jads.min.css' , JADS_BASEFILE ), array('jquery-ui-css'), false, 'all' );
    wp_enqueue_style('jquery-ui-css', plugins_url('stylesheets/jquery-ui-1.10.3.custom.css' , JADS_BASEFILE ), array(), false, 'all' );    
}

function jads_initialize_admin_scripts(){
    wp_enqueue_style(
            'jads_icons',
            plugins_url( 'stylesheets/icons.css', JADS_BASEFILE ),
            array(),
            '1.0' );
    
    wp_enqueue_style(
            'jads_admin_style',
            plugins_url( 'stylesheets/jads_admin.min.css', JADS_BASEFILE ),
            array( 'jads_icons' ),
            '1.0' );
    
    if( defined( 'MP6' ) || get_bloginfo( 'version' ) > 3.7 ){        
        $css = "
            .menu-icon-jads .wp-menu-image img {
                display: none;
            }
            ";
        wp_add_inline_style( 'jads_admin_style' , $css );
    }
}

function jads_createAds(){
    $captcha = new JADS_Captcha();
    $successfull_message = __("The Ad created!", "jads");
    $warning = __("No naughty business please", "jads");
    
    //Security testing
    $check_nonce = wp_verify_nonce($_POST['nonce'], "jads-nonce" );
    $check_captcha = isset( $_POST['ads_confirm'] ) && $captcha->check_validation_code( $_POST['ads_confirm'] );
    
    
    if( !$check_nonce || !$check_captcha )
        exit( $warning );    
    
    //Set post parametres
    $terms = array();
    if( isset( $_POST['ads_category'] )){
        array_push( $terms, $_POST['ads_category'] );
    }
    
    $taxonomies = array( 'ads_tax' => $terms );
    $user_id = 2; 
    
    if(is_user_logged_in() ){
        global $current_user;
        $user_id = $current_user->ID;
    }
    
    $post = array(                
                "post_title" => $_POST["ads_title"],
                "post_content" => html_entity_decode( wp_kses ( $_POST["ads_content"],  wp_kses_allowed_html( 'post' ), null ) ),
                "post_type" => 'jads',
                "post_status" => 'pending',
                "post_author" => $user_id,
                'tax_input'   => $taxonomies
    );	

    //Insert post
    $error = false;
    $result = wp_insert_post( $post, $error );
    
    jads_attach_image_to_post( $result );
        
    $meta = isset( $_POST["ads_price"] ) &&
            isset( $_POST["ads_email"] ) &&
            isset( $_POST["ads_location"] ) &&
            isset( $_POST["ads_contact_name"] ) &&
            isset( $_POST["ads_phone_number"] );
    
    if( $result && $meta ){
        update_post_meta($result, 'ads_price', is_currency( $_POST["ads_price"] ) );	
        update_post_meta($result, 'ads_email',  $_POST["ads_email"]  );	
        update_post_meta($result, 'ads_location',  $_POST["ads_location"] );	
        update_post_meta($result, 'ads_contact_name',  $_POST["ads_contact_name"] );	
        update_post_meta($result, 'ads_phone_number',  $_POST["ads_phone_number"] );
        
        die( $successfull_message );
    }
    elseif ( $result ) {
        die( $successfull_message );
    }
    

    die( __( 'Message', 'jads' ).': '.$error );	
    
}

function jads_get_ajax_template(){
    if( isset( $_POST['template'] ) ){
        echo JADS_Helper::get_plugin_template($_POST['template']);
    }
    die();
}

function is_currency( $num ){
    if( is_numeric( $num ) ){
        return $num;
    }
    return 0;
}

function jads_upload_image(){
    check_ajax_referer('featured_upload');
    $status = wp_handle_upload( $_FILES['async-upload'], array( 'test_form' => TRUE, 'action' => 'jads_upload_image' ) );
    die( json_encode( $status ) );
    exit;
}   

function jads_attach_image_to_post( $result ){
    if( $result && $_POST['ads_featured'] != '' ){
        
        $featured = $_POST['ads_featured'];
        if( is_string( $featured ) ){
            $featured = json_decode( $_POST['ads_featured'] );
        }
        
        $attachment = array(
           'guid' => $featured['url'], 
           'post_mime_type' => $featured['type'],
           'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $featured['file'] ) ),
           'post_content' => '',
           'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment( $attachment, $featured['file'], $result );
        set_post_thumbnail($result, $attach_id);
        
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata( $attach_id, $featured['file'] );
        wp_update_attachment_metadata( $attach_id, $attach_data );
    }
}

function jads_is_post_author( $post_id = FALSE, $author_id = FALSE ){
    $current_post = false;    
    if( !is_user_logged_in() ) return false;
    
    if( $post_id && is_numeric( $post_id ) ){        
        $current_post = get_post( $post_id );
    } else {
        global $post;
        $current_post = $post;
    }
    
    //If there wasn't pass the author by user
    if( $post_id == FALSE ){
        $author_id = get_current_user_id();         
    }
    
    if( $current_post->post_author == $author_id ){
        return true;
    }
    
    return false;
}

function jads_show_edit_post( $post_id = FALSE, $author_id = FALSE ){
    if( jads_is_post_author( $post_id, $author_id ) ){
        echo '<a href="'.get_edit_post_link( $post_id ).'" >'.__('Edit', 'jads').'</a>';
    }
}

function jads_get_meta_existing_values( $meta ){
    $list = array();
    $counter = 0;
    
    query_posts( array(
                'post_type' => 'jads',
                'taxonomy' => 'ads_tax',                
                'posts_per_page' => 99999,
    ));
    
    if( have_posts() ){
        while(have_posts() ){
            the_post();
            $meta_value = get_post_meta( get_the_ID(), $meta, true );
            if( $meta_value && $meta_value != '' && !in_array( $meta_value, $list ) ){
                $list[ $counter ] = $meta_value;
                $counter++;
            }            
        }        
    }
   wp_reset_query();
   
   //decide what to return
   if( $counter > 0 ) { return $list; }
   return null;
}

function jads_get_posted_value( $value, $default_value = false ){
    if( isset( $_POST[ $value ]) ){       
        return $_POST[ $value ];
    }
    return $default_value;
}

function jads_get_limit_date( $default_days = 365){
    $days_until_expired = get_option('days_until_ads_expired', $default_days );
    if( !is_numeric($days_until_expired) ){
        $days_until_expired = $default_days;
    }
    $datehelper = new JADS_DateHelper();
    $datehelper->gtm = true;
    
    $date = $datehelper->get_date_before( 0, 0, 0, $days_until_expired );
    return array(
        'year' => date( 'Y', $date ),
        'month' => date( 'm', $date ),
        'day' => date( 'd', $date ),
    );
}

function jads_title_like_posts_where( $where, &$wp_query ) {
    global $wpdb;
    if ( $post_title_like = $wp_query->get( 'post_title_like' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( like_escape( $post_title_like ) ) . '%\'';
    }
    return $where;
}
