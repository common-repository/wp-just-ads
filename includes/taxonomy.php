<?php

// Register ADS Taxonomy
function ads_tax()  {

	$labels = array(
		'name'                       => _x( 'Categories', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Category', 'text_domain' ),
		'all_items'                  => __( 'All Categories', 'text_domain' ),
		'parent_item'                => __( 'Parent Category', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Category:', 'text_domain' ),
		'new_item_name'              => __( 'New Category Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Category', 'text_domain' ),
		'edit_item'                  => __( 'Edit Category', 'text_domain' ),
		'update_item'                => __( 'Update Category', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'text_domain' ),
		'search_items'               => __( 'Search categories', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove categories', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used categories', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'ads_tax', 'jads', $args );

}
