<?php

function display_active_categories(){
    $categories = get_terms( 'ads_tax' );
    $output = '';   
    
    foreach ( $categories as $category ){
//        $posts = query_posts( 'post_type=jads&taxonomy='.$category->taxonomy.'&term='.$category->slug );  
        $posts = query_posts( array(
            'post_type' => 'jads',
            'taxonomy' => $category->taxonomy,
            'term' => $category->slug,
            'date_query' => array(
                'after' => jads_get_limit_date()
            )
        ) );
             
        $output .= '    <li>';
        $output .= '      <a href="#" class="ads-category" id="show_category" data-category="'.$category->slug.'" >'.$category->name.' ('.count( $posts ).') </a>';
        $output .= '    </li>';
        wp_reset_query();
    } 
    
    if( $categories ){
        echo '<ul class="ads-categories">';
        echo $output;
        echo '</ul>';
    }
}

