<?php
function show_category(){    
    global $wp_query;
    $category = '';
    $page = 0;
    $current_page = 0;
    
    if( !isset( $_SESSION ) ){
        session_start();
    }
    
    if( isset( $_POST['category'] ) ){
        $category = $_POST['category'];
        $_SESSION['previous_category'] = $category;
    }else {
        $category = $_SESSION['previous_category'];
    }
    
    if( isset( $_POST['page']) ){
        $page = $_POST['page'];
    }
    
    if( isset( $_POST['current_page'] ) ){
        $current_page = $_POST['current_page'];
    }
    
    
    $posts_per_page = 10;
    if( get_option( 'number_of_posts_per_page' ) )
        $posts_per_page = get_option( 'number_of_posts_per_page' );

    $offset = $page * $posts_per_page;
    $expired_date = jads_get_limit_date();
    
    $posts = query_posts( array(
                'post_type' => 'jads',
                'taxonomy' => 'ads_tax',
                'term' => $category,
                'posts_per_page' => 99999,
                'date_query' => array(
                    'after' => $expired_date
                )
        
    ));
    $posts_count = count( $posts );
    wp_reset_query();

    query_posts( array(
                'post_type' => 'jads',
                'taxonomy' => 'ads_tax',
                'term' => $category,
                'posts_per_page' => $posts_per_page,
                'offset' => $offset,
                'date_query' => array(
                    'after' => $expired_date
                )
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
                $output .= "<li><a class='ads-nav-item' href='#' data-category='$category' data-function='show_category' data-page='$counter' >".($counter+1)."</a></li>";           
            }
            else {
                $output .= "<li><span class='ads-nav-item current'>".($counter+1)."</span></li>";
            }   
        }
        $output .= "</ul>";
    }
    echo $output;
    }
    wp_reset_query();
}
?>