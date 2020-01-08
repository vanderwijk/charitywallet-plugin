<?php if ( ! function_exists('charity_post_type') ) {

	// Register Custom Post Type
	function charity_post_type() {

		$labels = array(
			'name'                  => _x( 'Charities', 'Post Type General Name', 'chawa' ),
			'singular_name'         => _x( 'Charity', 'Post Type Singular Name', 'chawa' ),
			'menu_name'             => __( 'Charities', 'chawa' ),
			'name_admin_bar'        => __( 'Charity', 'chawa' ),
			'archives'              => __( 'Charity Archives', 'chawa' ),
			'attributes'            => __( 'Charity Attributes', 'chawa' ),
			'parent_item_colon'     => __( 'Parent Charity:', 'chawa' ),
			'all_items'             => __( 'All Charities', 'chawa' ),
			'add_new_item'          => __( 'Add New Charity', 'chawa' ),
			'add_new'               => _x( 'Add New', 'add new charity', 'chawa' ),
			'new_item'              => __( 'New Charity', 'chawa' ),
			'edit_item'             => __( 'Edit Charity', 'chawa' ),
			'update_item'           => __( 'Update Charity', 'chawa' ),
			'view_item'             => __( 'View Charity', 'chawa' ),
			'view_items'            => __( 'View Charity', 'chawa' ),
			'search_items'          => __( 'Search Charity', 'chawa' ),
			'not_found'             => __( 'Not found', 'chawa' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'chawa' ),
			'featured_image'        => __( 'Featured Image', 'chawa' ),
			'set_featured_image'    => __( 'Set featured image', 'chawa' ),
			'remove_featured_image' => __( 'Remove featured image', 'chawa' ),
			'use_featured_image'    => __( 'Use as featured image', 'chawa' ),
			'insert_into_item'      => __( 'Insert into charity', 'chawa' ),
			'uploaded_to_this_item' => __( 'Uploaded to this charity', 'chawa' ),
			'items_list'            => __( 'Charities list', 'chawa' ),
			'items_list_navigation' => __( 'Charities list navigation', 'chawa' ),
			'filter_items_list'     => __( 'Filter charities list', 'chawa' ),
		);
		$rewrite = array(
			'slug'                  => _x( 'charity', 'permalink for single charity', 'chawa' ),
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'label'                 => __( 'Charity', 'chawa' ),
			'description'           => __( 'Charities', 'chawa' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'taxonomies'            => array( 'sector' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-groups',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => _x( 'charities', 'permalink for charities archive', 'chawa' ),
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
		);
		register_post_type( 'charity', $args );

	}
	add_action( 'init', 'charity_post_type', 0 );

}

if ( ! function_exists( 'sector_taxonomy' ) ) {

	// Register Custom Taxonomy
	function sector_taxonomy() {
	
		$labels = array(
			'name'                       => _x( 'Sectors', 'Taxonomy General Name', 'chawa' ),
			'singular_name'              => _x( 'Sector', 'Taxonomy Singular Name', 'chawa' ),
			'menu_name'                  => __( 'Sectors', 'chawa' ),
			'all_items'                  => __( 'All Sectors', 'chawa' ),
			'parent_item'                => __( 'Parent Sector', 'chawa' ),
			'parent_item_colon'          => __( 'Parent Sector:', 'chawa' ),
			'new_item_name'              => __( 'New Sector Name', 'chawa' ),
			'add_new_item'               => __( 'Add New Sector', 'chawa' ),
			'edit_item'                  => __( 'Edit Sector', 'chawa' ),
			'update_item'                => __( 'Update Sector', 'chawa' ),
			'view_item'                  => __( 'View Sector', 'chawa' ),
			'separate_items_with_commas' => __( 'Separate sectors with commas', 'chawa' ),
			'add_or_remove_items'        => __( 'Add or remove sectors', 'chawa' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'chawa' ),
			'popular_items'              => __( 'Popular Sectors', 'chawa' ),
			'search_items'               => __( 'Search Sectors', 'chawa' ),
			'not_found'                  => __( 'Not Found', 'chawa' ),
			'no_terms'                   => __( 'No sectors', 'chawa' ),
			'items_list'                 => __( 'Sectors list', 'chawa' ),
			'items_list_navigation'      => __( 'Sectors list navigation', 'chawa' ),
		);
		$rewrite = array(
			'slug'                       => _x( 'sector', 'permalink for sector taxonomy', 'chawa' ),
			'with_front'                 => true,
			'hierarchical'               => false,
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'rewrite'                    => $rewrite,
		);
		register_taxonomy( 'sector', array( 'charity' ), $args );
	
	}
	add_action( 'init', 'sector_taxonomy', 0 );

}