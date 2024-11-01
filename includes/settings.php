<?php
// create custom plugin settings menu
add_action('admin_menu', 'jads_create_menu');

function jads_create_menu() {
	
    //create submenu page
    add_submenu_page( 'edit.php?post_type=jads', 'JADS Settings', 'Settings', 'administrator', 'jads_settings_page', 'jads_settings_page' );
        
	//call register settings function
	add_action( 'admin_init', 'jads_register_mysettings' );
}


function jads_register_mysettings() {
	//register our settings
	register_setting( 'jads-settings-group', 'jads_display_featured' );
	register_setting( 'jads-settings-group', 'number_of_posts_per_page' );	
	register_setting( 'jads-settings-group', 'days_until_ads_expired' );	
}

function jads_settings_page() {
?>
<div class="wrap">
<h2>JustADS Settings Page</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'jads-settings-group' ); ?>
    <?php do_settings_sections( 'jads-settings-group' ); ?>
    
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Display Featured Image</th>
        <td><input type="checkbox" name="jads_display_featured" value="1" <?php checked(TRUE, get_option('jads_display_featured') ); ?> /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Posts per page</th>
        <td><input type="number" name="number_of_posts_per_page" min="1" value="<?php echo get_option('number_of_posts_per_page'); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Days until an ads expired</th>
        <td><input type="number" name="days_until_ads_expired" min="1" value="<?php echo get_option('days_until_ads_expired'); ?>" /></td>
        </tr>   
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>