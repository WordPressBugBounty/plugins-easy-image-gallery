<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( 'Unauthorized user' );
}

if ( ! empty( $_POST ) && check_admin_referer( 'eig_admin_page_save', 'eig_admin_page' ) ) {
	if ( isset( $_POST['easy-image-gallery'] ) && is_array( $_POST['easy-image-gallery'] ) ) {
		$easy_image_gallery_submitted = map_deep( wp_unslash( $_POST['easy-image-gallery'] ), 'sanitize_text_field' );
		update_option( 'easy-image-gallery', $easy_image_gallery_submitted );
	}
}
?>
<div class="wrap">
	<form action='' method='post'>
		<h1><?php echo esc_html__( 'Easy Image Gallery Settings', 'easy-image-gallery' ); ?></h1>
		<table class="form-table" role="presentation">
			<tbody>
				<div class="dx-plugin-disclaimer">
					<h2><?php echo esc_html__( 'Add gallery to any post, page or custom post type', 'easy-image-gallery' ); ?></h2>
					<p>
						<strong><?php echo esc_html__( 'Disclaimer:', 'easy-image-gallery' ); ?></strong>
						<?php
						printf(
							'%1$s <strong>%2$s</strong> %3$s',
							esc_html__( 'Each generated gallery shortcode can', 'easy-image-gallery' ),
							esc_html__( 'only be used on the specific page', 'easy-image-gallery' ),
							esc_html__( 'it has been generated for.', 'easy-image-gallery' )
						);
						?>
					</p>
				</div>
				<?php
				// Default option when settings have not been saved.
				$easy_image_gallery_defaults['lightbox'] = 'prettyphoto';

				$easy_image_gallery_settings = (array) get_option( 'easy-image-gallery', $easy_image_gallery_defaults );
				$easy_image_gallery_lightbox = esc_attr( $easy_image_gallery_settings['lightbox'] );
				?>
				<tr>
					<th scope="row"><?php echo esc_html__( 'Lightbox', 'easy-image-gallery' ); ?></th>
					<td>
						<select name="easy-image-gallery[lightbox]">
							<?php foreach ( easy_image_gallery_lightbox() as $easy_image_gallery_key => $easy_image_gallery_label ) : ?>
								<option value="<?php echo esc_attr( $easy_image_gallery_key ); ?>" <?php selected( $easy_image_gallery_lightbox, $easy_image_gallery_key ); ?>><?php echo esc_html( $easy_image_gallery_label ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<?php
				// Post and page defaults.
				$easy_image_gallery_defaults['post_types']['post'] = 'on';
				$easy_image_gallery_defaults['post_types']['page'] = 'on';

				$easy_image_gallery_settings = (array) get_option( 'easy-image-gallery', $easy_image_gallery_defaults );
				?>
				<tr>
					<th scope="row"><?php esc_html_e( 'Post Types', 'easy-image-gallery' ); ?></th>
					<td>
						<?php
						foreach ( easy_image_gallery_get_post_types() as $easy_image_gallery_key => $easy_image_gallery_label ) :

								$easy_image_gallery_post_types = isset( $easy_image_gallery_settings['post_types'][ $easy_image_gallery_key ] ) ? esc_attr( $easy_image_gallery_settings['post_types'][ $easy_image_gallery_key ] ) : '';
							?>
							<p>
								<input type="checkbox" id="<?php echo esc_attr( $easy_image_gallery_key ); ?>" name="easy-image-gallery[post_types][<?php echo esc_attr( $easy_image_gallery_key ); ?>]" <?php checked( $easy_image_gallery_post_types, 'on' ); ?>/><label for="<?php echo esc_attr( $easy_image_gallery_key ); ?>"> <?php echo esc_html( $easy_image_gallery_label ); ?></label>
							</p>
						<?php endforeach; ?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field( 'eig_admin_page_save', 'eig_admin_page' ); ?>
		<?php submit_button(); ?>
	</form>
</div>
