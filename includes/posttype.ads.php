<?php
function jads_ads() {

	$labels = array(
		'name'                => _x( 'Advertisements', 'Post Type General Name', 'jads' ),
		'singular_name'       => _x( 'Advertisement', 'Post Type Singular Name', 'jads' ),
		'menu_name'           => __( 'Advertisement', 'jads' ),
		'parent_item_colon'   => __( 'Parent Advertisement:', 'jads' ),
		'all_items'           => __( 'All Advertisements', 'jads' ),
		'view_item'           => __( 'View Advertisement', 'jads' ),
		'add_new_item'        => __( 'Add New Advertisement', 'jads' ),
		'add_new'             => __( 'New Advertisement', 'jads' ),
		'edit_item'           => __( 'Edit Advertisement', 'jads' ),
		'update_item'         => __( 'Update Advertisement', 'jads' ),
		'search_items'        => __( 'Search advertisement', 'jads' ),
		'not_found'           => __( 'No advertisements found', 'jads' ),
		'not_found_in_trash'  => __( 'No advertisements found in Trash', 'jads' ),
	);
	$args = array(
		'label'               => __( 'jads', 'jads' ),
		'description'         => __( 'Is for storing ads', 'jads' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'trackbacks', 'revisions', ),
		'taxonomies'          => array( 'ads_tax' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => plugins_url( '/images/ads.png', JADS_BASEFILE ),
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'jads', $args );

}