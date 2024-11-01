<?php

function search_ads(){   
   search_form();
    ?>
    <section id="ads-search-results">
        
    </section>
    <?php    
}

function search_form(){    
    $categories = get_terms( 'ads_tax' );
    $locations = jads_get_meta_existing_values( 'ads_location' );    
    ?>
    <form type="text" name="ads-seach-form" id="ads-seach-form" accept-charset="UTF-8">
        <input type="hidden" name="page" id="page" />
        <fieldset class="search-field" >
            <input type="text" name="ads-search" id="ads-search" placeholder="<?php _e("Search for ADS", "jads"); ?>" />
            <input type="submit" name="ads-search-button" id="ads-search-button" value="<?php _e("Search", "jads"); ?>" />
        </fieldset>
        <fieldset>
            <select name="ads-search-in-category" id="ads-search-in-category">
                <?php foreach ( $categories as $category ): 
                    $posted_category = jads_get_posted_value( 'category', 'no-category' );
                ?>                
                <option value="<?php echo $category->slug; ?>"<?php selected( $posted_category, $category->slug, true ); ?> ><?php echo $category->name; ?></option>
                <?php endforeach; ?>
            </select>
        </fieldset>
        <fieldset>
            <legend><?php  _e( 'Price', 'jads' ); ?></legend>
            
            <label><?php  _e( 'Min', 'jads' ); ?></label>
            <input type="number" name="ads-min-price" id="ads-min-price" min="0" />
            
            <label><?php  _e( 'Max', 'jads' ); ?></label>
            <input type="number" name="ads-max-price" id="ads-max-price" min="0" />
        </fieldset>
        
        <fieldset>
            <label><?php  _e( 'Search in Cities', 'jads' ); ?></label>
            <select multiple name="search-by-city" id="search-by-city" >
                <?php foreach ( $locations as $location ): ?>
                <option value="<?php echo $location ?>"><?php echo $location ?></option>
                <?php endforeach; ?>
            </select>
        </fieldset>
    </form>   
    <?php
}