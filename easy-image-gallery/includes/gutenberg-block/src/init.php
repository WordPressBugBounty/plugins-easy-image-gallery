<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! defined( 'EASY_IMAGE_BLOCK_GALLERY_URL' ) ) {
	define( 'EASY_IMAGE_BLOCK_GALLERY_URL', trailingslashit( plugin_dir_url( __DIR__ ) ) );
}

/**
 * Asset version for block CSS/JS (matches main plugin when available).
 *
 * @return string
 */
function easy_image_gallery_block_asset_version() {
	return defined( 'EASY_IMAGE_GALLERY_VERSION' ) ? EASY_IMAGE_GALLERY_VERSION : '1.0.0';
}


/**
 * Hiding/Showing EIG Gutenberg Block depending on user choice to hide/show it
 * on post/pages
 * @uses {wp-editor} for WP editor styles.
 * @since 1.4.0
 */
function easy_image_gallery_hide( $post_id ) {
	$post_types = easy_image_gallery_allowed_post_types();
	$post_type  = get_post_type( $post_id );

	if ( ! array_key_exists( strval( $post_type ), $post_types ) ) {
		return true;
	}

	return false;
}

function easy_image_gallery_block_cgb_block_assets() { // phpcs:ignore
	global $post;

	if( empty( $post->ID ) ){
		return;
	}

	if ( true === easy_image_gallery_hide( $post->ID) ) {
		return;
	}

	// Styles.
	wp_enqueue_style(
		'easy_image_gallery_block-cgb-style-css',
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ),
		array( 'wp-editor' ),
		easy_image_gallery_block_asset_version(),
		'all'
	);

	if ( is_admin() ) {
		wp_register_script( 'pretty-photo-js', EASY_IMAGE_BLOCK_GALLERY_URL . 'dist/lib/prettyphoto/jquery.prettyPhoto.js', array( 'jquery' ), 1, true );
		wp_register_script( 'fancybox-js', EASY_IMAGE_BLOCK_GALLERY_URL . 'dist/lib/fancybox/jquery.fancybox-1.3.4.pack.js', array( 'jquery' ), 1, true );
	}

	// CSS
	// wp_register_style( 'pretty-photo', EASY_IMAGE_BLOCK_GALLERY_URL . '../dist/lib/prettyphoto/prettyPhoto.css', '', EASY_IMAGE_GALLERY_VERSION, 'screen' );
	// wp_register_style( 'fancybox', EASY_IMAGE_BLOCK_GALLERY_URL . '../dist/lib/fancybox/jquery.fancybox-1.3.4.css', '', EASY_IMAGE_GALLERY_VERSION, 'screen' );
	wp_enqueue_script( 'pretty-photo-js' );
	// wp_enqueue_script( 'fancybox-js' );

}

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'easy_image_gallery_block_cgb_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function easy_image_gallery_block_cgb_editor_assets() { // phpcs:ignore
	global $post;

	if ( true === easy_image_gallery_hide( $post->ID) ) {
		return;
	}

	wp_enqueue_script( 'easy_image_gallery_block-script-fe-js' );

	// Scripts.
	wp_enqueue_script(
		'easy_image_gallery_block-cgb-block-js',
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
		easy_image_gallery_block_asset_version(),
		true
	);
	// Styles.
	wp_enqueue_style(
		'easy_image_gallery_block-cgb-block-editor-css',
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ),
		array( 'wp-edit-blocks' ),
		easy_image_gallery_block_asset_version(),
		'all'
	);
}

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'easy_image_gallery_block_cgb_editor_assets' );
