<?php

function user_ads(){
    if( is_user_logged_in() ){
        $user = wp_get_current_user();
        $output = '';
        $page = 0;
        
        if( isset( $_POST['page']) ){
            $page = $_POST['page'];
        }
        
        
        $posts_per_page = 10;
        if( get_option( 'number_of_posts_per_page' ) )
            $posts_per_page = get_option( 'number_of_posts_per_page' );
        $offset = $page * $posts_per_page;
        
        $posts = query_posts( array(
                'post_type' => 'jads',
                'taxonomy' => 'ads_tax',
                'post_status' => 'publish',
                'author' => $user->ID,
                'posts_per_page' => 9999
        ));
        
        $posts_count = count( $posts );
        wp_reset_query();
        
        query_posts( array(
                'post_type' => 'jads',
                'taxonomy' => 'ads_tax',
                'post_status' => 'publish',
                'author' => $user->ID,
                'posts_per_page' => $posts_per_page,
                'offset' => $offset
        ));
        
    //Echo the category current page for jquery use!
    ?><input id="ads-current-page" type="hidden" data-page="<?php echo $page ?>" /><?php    
        if( have_posts() ){
            $output = '';
            while( have_posts() ){ the_post();
                JADS_Helper::get_plugin_template('category_item');
            }
            $pages = ceil( $posts_count / $posts_per_page );
            if( $pages > 1 ){
                $output .= "<ul id='ads-navigation' >";
                for( $counter = 0; $counter < $pages; $counter++ ){ 
                    if( $counter != $page ){
                        $output .= "<li><a class='ads-nav-item' href='#' data-function='user_ads' data-page='$counter' >".($counter+1)."</a></li>";
                    }else {
                        $output .= "<li><span class='ads-nav-item current'>".($counter+1)."</span></li>";
                    }
                }
                $output .= "</ul>";
            }
            
            echo $output;
        }
        wp_reset_query();        
    }
    else {
        echo __("<div id='ads-not-logedin'>You are not logged in! Please login and try again...</div>", "jads" );
    }
}