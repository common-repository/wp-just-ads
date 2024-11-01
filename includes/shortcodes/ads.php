<?php

function ads( $atts ){
    extract( shortcode_atts( array(
		'var' => 'varvar',
    ), $atts ) );
    ?>   
    <?php
    JADS_Helper::get_plugin_template( 'display_menu' );
    echo "<div class='ads-page-content' id='ads-page-content'>";
    JADS_Helper::get_plugin_template( 'display_active_categories' );
    echo "</div>";
 
}
