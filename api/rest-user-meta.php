<?php 

// First Name
function slug_register_first_name() {
	register_rest_field( 'user',
		'first_name',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_first_name' );

// Last Name
function slug_register_last_name() {
	register_rest_field( 'user',
		'last_name',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_last_name' );

// Phone
function slug_register_phone() {
	register_rest_field( 'user',
		'user_phone',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_phone' );

// Address Street
function slug_register_address_street() {
	register_rest_field( 'user',
		'user_address_street',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_address_street' );

// Postcode
function slug_register_postcode() {
	register_rest_field( 'user',
		'user_address_postcode',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_postcode' );

// City
function slug_register_city() {
	register_rest_field( 'user',
		'user_address_city',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_city' );

// Country
function slug_register_country() {
	register_rest_field( 'user',
		'user_address_country',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_country' );

// Get user meta
function slug_get_user_meta( $data, $field_name, $request ) {
	return get_user_meta( $data['id'], $field_name, false );
}

// Update user meta
function slug_update_user_meta( $value, $object, $field_name ) {
	return update_user_meta( $object->id, $field_name, wp_slash( $value ) );
}

// Restrict endpoint to only users who have the read capability
function chawa_users_persmission_callback() {

	if ( ! current_user_can( 'read' ) ) {
		return new WP_Error( 'rest_forbidden', esc_html__( 'You can not view private data.', 'chawa' ), array( 'status' => 401 ) );
	}

	return true;
}