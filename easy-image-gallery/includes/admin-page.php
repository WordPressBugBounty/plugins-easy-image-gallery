<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add EIG Plugin Options Page
 *
 * @since 1.4.1
 */
function add_eig_plugin_page() {
	add_menu_page(
		__( 'Easy Image Gallery Settings', 'easy-image-gallery' ),
		__( 'Easy Image Gallery', 'easy-image-gallery' ),
		'manage_options',
		EASY_IMAGE_GALLERY_DIR . 'includes/view/admin-page-view.php',
		'',
		'dashicons-images-alt2',
	);
}
add_action( 'admin_menu', 'add_eig_plugin_page' );
