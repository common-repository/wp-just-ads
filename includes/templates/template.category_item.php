<?php

function category_item() {
    $price = JADS_Helper::get_meta_value( 'ads_price' );
    $location = JADS_Helper::get_meta_value( 'ads_location' );
    $phone = JADS_Helper::get_meta_value( 'ads_phone_number' );
    $contact_name = JADS_Helper::get_meta_value( 'ads_contact_name' );
    $views = JADS_Helper::get_meta_value( 'ads_views' );  
    ?> 
    <div class="ads-category-item" >
        <table id="ads-category-item-content-<?php the_ID(); ?>">
            <tbody>
                <tr>                   
                    <?php if( 1== get_option('jads_display_featured') ): ?>
                    <td rowspan="3" width="100" >                        
                        <?php if( has_post_thumbnail() ):
                                echo get_the_post_thumbnail( get_the_ID(), array( 100, 100 ), array( "class" => "adthumb") );                        
                             else: ?>
                        <img src="<?php echo  plugins_url( '/images/adhasnoimage.gif', JADS_BASEFILE ); ?>" width="100" height="100" class="adthumb" />
                        <?php endif; ?>                        
                    </td>
                    <?php endif; ?>
                    
                    <td class="description">
                        <table>
                            <tbody>
                                <tr>
                                    <th><a class="adtitle" id="<?php the_ID(); ?>"><?php the_title(); ?></a><span class="ads-edit-post"><?php jads_show_edit_post(); ?></span></th>
                                </tr>
                                <tr>
                                    <td class="ads-info">
                                        <?php if( $location ): ?>
                                        <span><?php echo $location; ?></span>|
                                        <?php endif; ?>                                   
                                        
                                        <span><?php echo get_the_date('d/m/y'); ?></span>|                                                                          
                                        
                                        <span><?php
                                                echo  __( "Views", "jads").": ";
                                                echo ($views) ? $views : 0;                                                 
                                        ?></span>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php JADS_Helper::sortText(get_the_content(), 150); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>                    
                    <td  rowspan="3" class="ads-price">
                        <?php if( $price ): ?>
                        <span><?php echo $price.'€'; ?></span>                        
                         <?php endif; ?>
                    </td>                    
                </tr>                
            </tbody>           
        </table>
    </div>
    <?php
}