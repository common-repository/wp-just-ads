<?php

function single_item() {
    if( isset( $_POST ) && isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) ){
        $post_id = $_POST['id'];
        $post = get_post( $_POST['id'] );
        $views = JADS_Helper::get_meta_value( 'ads_views', $post_id); 
        update_post_meta($post_id, 'ads_views', $views+1 );
        
        $price = JADS_Helper::get_meta_value('ads_price', $post_id);
        $location = JADS_Helper::get_meta_value('ads_location', $post_id);
        $phone = JADS_Helper::get_meta_value('ads_phone_number', $post_id);
        $contact_name = JADS_Helper::get_meta_value('ads_contact_name', $post_id);
        ?> 
        <div class="ads-single" >
            <h2><?php echo $post->post_title; ?></h2>
            <div class="col-60 ads-info">
                <h5><?php _e( "More information", "jads" ); ?></h5>
                <?php echo $post->post_content; ?>
            </div>
            <aside class="col-40 ads-info">
                <dl>
                    <?php if ($contact_name != null ): ?>
                    <dt><?php _e( "Name", "jads"); ?></dt>
                    <dd><?php echo $contact_name; ?></dd>
                    <?php endif; ?>

                    <?php if ($phone != null ): ?>
                    <dt><?php _e( "Phone", "jads"); ?></dt>
                    <dd><?php echo $phone; ?></dd>
                    <?php endif; ?>

                    <?php if ($location != null ): ?>
                    <dt><?php _e( "Location", "jads"); ?></dt>
                    <dd><?php echo $location; ?></dd>
                    <?php endif; ?>

                    <?php if ($price != null ): ?>
                    <dt class="ads-price"><?php _e( "Price", "jads"); ?></dt>
                    <dd><?php echo $price; ?></dd>
                    <?php endif; ?>
                </dl>

            </aside>
        </div>
    <?php
    }
}