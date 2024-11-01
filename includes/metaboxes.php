<?php

class jads_metaboxes {
	public $options;
	
	public function __construct(){
		add_action('add_meta_boxes', array($this,'set_meta'));
        $this->save_meta();	
	}
	
	public function set_meta(){		
		add_meta_box('ads_price', __('Price'), array($this, 'ads_price_cb'), "jads", 'side');		
		add_meta_box('ads_email', __('Email'), array($this, 'ads_email_cb'), "jads", 'side');		
		add_meta_box('ads_location', __('Location'), array($this, 'ads_location_cb'), "jads", 'side');		
		add_meta_box('ads_contact_name', __('Contact Name'), array($this, 'ads_contact_name_cb'), "jads", 'side');		
		add_meta_box('ads_phone_number', __('Phone Number'), array($this, 'ads_phone_number_cb'), "jads", 'side');		
		add_meta_box('ads_views', __('Ad Views'), array($this, 'ads_views_cb'), "jads", 'side');		
	}	
	
	public function save_meta(){		 	
        add_action('save_post', array($this,'ads_price_save'));                  
        add_action('save_post', array($this,'ads_email_save'));                  
        add_action('save_post', array($this,'ads_location_save'));                  
        add_action('save_post', array($this,'ads_contact_name_save'));                  
        add_action('save_post', array($this,'ads_phone_number_save'));                  
        add_action('save_post', array($this,'ads_views_save'));                  
        return;
	}

	//Metaboxes, Blog Control Panel templates
	public function ads_price_cb($post){
		$content = get_post_meta($post->ID, 'ads_price', TRUE);
		?>
        <input name="ads-price" id="ads-price" value="<?php echo $content;  ?>" />€
        <?php
	}
	public function ads_email_cb($post){
		$content = get_post_meta($post->ID, 'ads_email', TRUE);
		?>
        <input name="ads_email" id="ads_email" value="<?php echo $content;  ?>" />
        <?php
	}
	public function ads_location_cb($post){
		$content = get_post_meta($post->ID, 'ads_location', TRUE);
		?>
        <input name="ads_location" id="ads_location" value="<?php echo $content;  ?>" />
        <?php
	}
	public function ads_contact_name_cb($post){
		$content = get_post_meta($post->ID, 'ads_contact_name', TRUE);
		?>
        <input name="ads_contact_name" id="ads_contact_name" value="<?php echo $content;  ?>" />
        <?php
	}
	public function ads_phone_number_cb($post){
		$content = get_post_meta($post->ID, 'ads_phone_number', TRUE);
		?>
        <input name="ads_phone_number" id="ads_phone_number" value="<?php echo $content;  ?>" />
        <?php
	}
	public function ads_views_cb($post){
		$content = get_post_meta($post->ID, 'ads_views', TRUE);
		?>
        <input name="ads_views" id="ads_views" value="<?php echo $content;  ?>" />
        <?php
	}
	
	//Metaboxes, save content functions
	public function ads_price_save($post){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
     		return;		
		
		//$this->check_for_credentials();
		$content = '';
		if( isset( $_POST['ads-price'] ) )
        {
            $content =  $_POST['ads-price'];		 			 			 
        }
        update_post_meta($post, 'ads_price', $content);		
	}
	public function ads_email_save($post){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
     		return;		
		
		//$this->check_for_credentials();
		$content = '';
		if( isset( $_POST['ads_email'] ) )
        {
            $content =  $_POST['ads_email'];		 			 			 
        }
        update_post_meta($post, 'ads_email', $content);		
	}
	public function ads_location_save($post){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
     		return;		
		
		//$this->check_for_credentials();
		$content = '';
		if( isset( $_POST['ads_location'] ) )
        {
            $content =  $_POST['ads_location'];		 			 			 
        }
        update_post_meta($post, 'ads_location', $content);		
	}
	public function ads_contact_name_save($post){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
     		return;		
		
		//$this->check_for_credentials();
		$content = '';
		if( isset( $_POST['ads_contact_name'] ) )
        {
            $content =  $_POST['ads_contact_name'];		 			 			 
        }
        update_post_meta($post, 'ads_contact_name', $content);		
	}
	public function ads_phone_number_save($post){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
     		return;		
              
		//$this->check_for_credentials();
		$content = '';
		if( isset( $_POST['ads_phone_number'] ) )
        {
            $content =  $_POST['ads_phone_number'];		 			 			 
        }
        update_post_meta($post, 'ads_phone_number', $content);		
	}
	public function ads_views_save($post){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
     		return;		
              
		//$this->check_for_credentials();
		$content = '';
		if( isset( $_POST['ads_views'] ) )
        {
            $content =  $_POST['ads_views'];		 			 			 
        }
        if(is_numeric( $content ) ){
            update_post_meta($post, 'ads_views', $content);
        }
	}	
       
}
