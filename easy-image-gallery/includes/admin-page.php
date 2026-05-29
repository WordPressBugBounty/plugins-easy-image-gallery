<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Easy Image Gallery options page in the admin menu.
 *
 * @since 1.4.1
 */
function easy_image_gallery_add_plugin_page() {
	add_menu_page(
		__( 'Easy Image Gallery Settings', 'easy-image-gallery' ),
		__( 'Easy Image Gallery', 'easy-image-gallery' ),
		'manage_options',
		EASY_IMAGE_GALLERY_DIR . 'includes/view/admin-page-view.php',
		'',
		'dashicons-images-alt2',
	);
}
add_action( 'admin_menu', 'easy_image_gallery_add_plugin_page' );
