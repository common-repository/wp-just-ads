<?php

function search_results(){
    include 'template.search_ads.php';    
    global $wp_query;
    
    //setup variables
    $meta_query = false;
    $category = '';
    
    //Get posted velues
    $page = jads_get_posted_value( 'page' );   
    $current_page = jads_get_posted_value( 'current_page' );
    $cities = jads_get_posted_value( 'cities' );
    $min_price = jads_get_posted_value( 'minimum_price' );
    $max_price = jads_get_posted_value( 'maximum_price' );
    $post_title = jads_get_posted_value( 'keyword' );
    
    //Setup basic query
    $expired_date = jads_get_limit_date();
    $args = array(
        'post_type' => 'jads',
        'taxonomy' => 'ads_tax',
        'date_query' => array(
            'after' => $expired_date
        )
    );
    
    //display form if a query has already executed
    if( $page !== false ){
        search_form();
    }
    
    if( $post_title ) {        
        $args[ 'post_title_like' ] = $post_title;
    }
   
    if( !isset( $_SESSION ) ){
        session_start();
    }
    
    if( isset( $_POST['category'] ) ){
        $category = $_POST['category'];
        $_SESSION['previous_category'] = $category;
    }else {
        $category = $_SESSION['previous_category'];
    }
    
    $posts_per_page = 10;
    if( get_option( 'number_of_posts_per_page' ) )
        $posts_per_page = get_option( 'number_of_posts_per_page' );

    $offset = $page * $posts_per_page;
    
    $args = array_merge( $args, array( 'term' => $category, 'posts_per_page' => 99999 ) );
    
    //Prepare meta queries
    if( $cities || $max_price || $min_price ){
         $meta_query = array();
    }
     
    if( $cities ){
        array_push( $meta_query, array(
            'key' => 'ads_location',
            'value' => $cities,
            'compare' => 'IN'
        ) );  
    }
    
    if( $max_price xor $min_price ){
       
        if( $max_price ){
            array_push( $meta_query, array(
                'key' => 'ads_price',
                'value' => $max_price,
                'type' => 'numeric',
                'compare' => '<='
            ) ); 
        }elseif ( $min_price ) {
            array_push( $meta_query, array(
                'key' => 'ads_price',
                'value' => $min_price,
                'type' => 'numeric',
                'compare' => '>='
            ) ); 
        }
    }
    else if ( $max_price && $min_price ) {
        array_push( $meta_query, array(
                'key' => 'ads_price',
                'value' => array( $min_price, $max_price ),
                'type' => 'numeric',
                'compare' => 'BETWEEN'
        ) ); 
    }
    
    //if meta_query has values, will be merged into query arguments
    if( $meta_query ){
        $args[ 'meta_query' ] = $meta_query;
    }
    
    //Count the number of posts for pagination
    $posts = query_posts( $args );    
    $posts_count = count( $posts );
    wp_reset_query();

    //Change the values of query for receive only one page
    $args = array_merge( $args, array( 'posts_per_page' => $posts_per_page, 'offset' => $offset  ) );
    query_posts( $args );
    
    //Echo the category current page for jquery use!
    if( $page !== false ){
        ?>
        <section id="ads-search-results">
        <?php
    }
    ?>
    <h4><?php  _e( 'Results', 'jads' ); ?></h4>
    <input id="ads-current-page" type="hidden" data-page="<?php echo $page ?>" /><?php    
    if( have_posts() ){
        $output = '';
        while( have_posts() ){ the_post();        
            JADS_Helper::get_plugin_template('category_item');
        } 
    if( $page !== false ){
        ?>
        </section>
        <?php
    }
    
    //Navigation
    $pages = ceil( $posts_count / $posts_per_page );
    if( $pages > 1 ){
        $output .= "<ul id='ads-navigation' >";
        for( $counter = 0; $counter < $pages; $counter++ ){
            if( $counter != $page ){
                $output .= "<li><a class='ads-nav-item' href='#' data-category='$category' data-function='search_results' data-page='$counter' >".($counter+1)."</a></li>";           
            }
            else {
                $output .= "<li><span class='ads-nav-item current'>".($counter+1)."</span></li>";
            }   
        }
        $output .= "</ul>";
    }
    echo $output;
    }else {
        echo "<h4>".__( 'No results to see!', 'jads' )."</h4>";
    }
    wp_reset_query();
}