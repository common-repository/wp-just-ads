<?php
function create_ads(){    
    $categories = get_terms( 'ads_tax', array( 'hide_empty'    => FALSE ) );
    ?>
    <form name="ads-form" id="ads-form" >

        <fieldset>
            <div class="col-50" >
                <input name="ads-title" id="ads-title" placeholder="<?php _e( "Ad Title", "jads"); ?>" />            
                <input name="ads-name" id="ads-name" placeholder="<?php _e( "Contact Name", "jads"); ?>" />            
                <input name="ads-phone" id="ads-phone" placeholder="<?php _e( "Contact Phone", "jads"); ?>" />  
            </div>
            <div class="col-50" >
                <input name="ads-location" id="ads-location" placeholder="<?php _e( "Ad Location", "jads"); ?>" />            
                <input type="email" name="ads-email" id="ads-email" pattern="[a-zA-Z\.0-9_\-]+@[a-zA-Z0-9]+\.[a-z]+" placeholder="<?php _e( "Contact/Application email (private)", "jads"); ?>" />            
                <input type="number" name="ads-price" id="ads-price" placeholder="<?php _e( "Ad Price", "jads"); ?>" />  
            </div>
        </fieldset>
        <fieldset>
            <select name="ads-category" id="ads-category" >
                <?php foreach ( $categories as $category ): ?>
                <option value="<?php echo $category->term_id; ?>" ><?php echo ucfirst( $category->name ); ?></option>
                <?php endforeach; ?>
            </select>
        </fieldset>
        
        <fieldset>            
            <div id="plupload-upload-ui" >
                <div id="drag-drop-area">
                    <div class="drag-drop-inside">
                        <p class="drag-drop-info"></p>                        
                        <p class="drag-drop-buttons">
                            <input id="plupload-browse-button" class="button" type="button" value="<?php esc_attr_e( "Upload Image" ); ?>" />
                            <span id="uploaded"></span>
                            <p id="progressbar"></p> 
                       </p>
                    </div>
                </div>                
            </div>
        </fieldset>
        
        <div class="clearfix"></div>        
        <fieldset>
             <label><?php _e("Description", "jads") ?></label>
             <textarea rows="5" cols="30" name="ads-content" id="ads-content"></textarea>  
             
             <?php $captcha = new JADS_Captcha(); $captcha->createImage(); ?>
             <img id="captcha-refresh" width="30" height="30" style="vertical-align: top; cursor:pointer;" src="<?php echo plugins_url( '/images/refresh.png', JADS_BASEFILE ); ?>" />
             <input type="text" name="confirm" id="ads-confirm" />
        </fieldset>

        <fieldset>            
            <input type="submit" name="send-ads" id="send-ads" value="Add ad" />
            <span class="ads-error-message" style="display:none;position:absolute;right:90px;font-weight:bold;" ></span>
        </fieldset>       
    </form>
    <?php
}


