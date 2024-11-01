<?php

/*
Plugin Name: WP JustADS
Plugin URI: http://www.xtnd.it/marketplace
Description: Create, Display and Search for ADS within your site!
Version: 1.11
Author: Konstantinos Tsatsarounos
Author URI: http://www.infogeek.gr
License: GPLv2 or later
*/
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


if( !defined( 'JADS' ) )
    define( 'JADS', true);

//Constants
define( 'JADS_PATH', realpath( dirname( __FILE__ ) ) );
define( 'JADS_BASEFILE', __FILE__ );

//Load Language
function load_lang() {
 $plugin_lang_dir = basename( dirname(__FILE__) ).'/language'; 
 load_plugin_textdomain( 'jads', false, $plugin_lang_dir );
}


//Require files
require plugin_dir_path(__FILE__).'includes/taxonomy.php';
require plugin_dir_path(__FILE__).'includes/posttype.ads.php';
require plugin_dir_path(__FILE__).'includes/metaboxes.php';
require plugin_dir_path(__FILE__).'includes/settings.php';
require plugin_dir_path(__FILE__).'includes/classes/Helper.php';
require plugin_dir_path(__FILE__).'includes/classes/captcha.php';
require plugin_dir_path(__FILE__).'includes/classes/DateHelper.php';
require plugin_dir_path(__FILE__).'includes/classes/ajaxed_status.php';
require plugin_dir_path(__FILE__).'includes/functions.php';
require plugin_dir_path(__FILE__).'includes/shortcodes/hooks.php';


//Actions
add_action( 'init', 'jads_ads', 0 );
add_action( 'init', 'ads_tax', 0 );
add_action( 'wp_enqueue_scripts', 'jads_initialize_scripts' );
add_action( 'wp_enqueue_scripts', 'jads_initialize_stylesheets' );
add_action( 'admin_enqueue_scripts', 'jads_initialize_admin_scripts' );
add_action( 'plugins_loaded', 'load_lang' );

//handle ajax ads creation
add_action('wp_ajax_nopriv_jads_createAds', 'jads_createAds');
add_action('wp_ajax_jads_createAds', 'jads_createAds');

//handle ajax template loading
add_action( 'wp_ajax_nopriv_jads_get_ajax_template', 'jads_get_ajax_template' );
add_action( 'wp_ajax_jads_get_ajax_template', 'jads_get_ajax_template' );

//handle ajax image uploading
add_action( 'wp_ajax_nopriv_jads_upload_image', 'jads_upload_image' );
add_action( 'wp_ajax_jads_upload_image', 'jads_upload_image' );

//handle ajax captcha
$captcha = new JADS_Captcha();
add_action( 'wp_ajax_nopriv_jads_captcha', array( $captcha, 'createImage' ) );
add_action( 'wp_ajax_jads_captcha', array( $captcha, 'createImage' ) );

//Filters
add_filter( 'posts_where', 'jads_title_like_posts_where', 10, 2 );

//Shortcodes
add_shortcode( 'ads-form', 'ads' );

//Code
new jads_metaboxes();
