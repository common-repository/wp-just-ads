<?php
function display_menu(){    
    ?>
    <ul class="ads-menu">
        <li class="ads-item">
            <a href="#display_active_categories" id="display_active_categories" ><?php _e("Active Categories", "jads"); ?></a>
        </li>
        <li class="ads-item" >
            <a href="#create_ads" id="create_ads" ><?php _e("New ADS", "jads"); ?></a>
        </li>
        
        <?php if( is_user_logged_in() ): ?>
        <li class="ads-item" >
            <a href="#user_ads" id="user_ads" ><?php _e("My ADS", "jads"); ?></a>
        </li>
        <?php endif; ?>
        
        <li class="ads-item" >
            <a href="#search_ads" id="search_ads" ><?php _e("Search", "jads"); ?></a>
        </li>
    </ul>
    <?php
}

