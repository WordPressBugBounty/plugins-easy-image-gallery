<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Add meta boxes to selected post types
 *
 * @since 1.0
 */
function easy_image_gallery_add_meta_box() {

	$post_types = easy_image_gallery_allowed_post_types();

	if ( ! $post_types ) {
		return;
	}

	foreach ( $post_types as $post_type => $status ) {
		add_meta_box( 'easy_image_gallery', apply_filters( 'easy_image_gallery_meta_box_title', __( 'Image Gallery', 'easy-image-gallery' ) ), 'easy_image_gallery_metabox', $post_type, apply_filters( 'easy_image_gallery_meta_box_context', 'normal' ), apply_filters( 'easy_image_gallery_meta_box_priority', 'low' ) );
	}

}
add_action( 'add_meta_boxes', 'easy_image_gallery_add_meta_box' );


/**
 * Render gallery metabox
 *
 * @since 1.0
 */
function easy_image_gallery_metabox() {

	global $post;

	$old_meta_structure = get_post_meta( $post->ID, '_easy_image_gallery' );
	$new_meta_structure = get_post_meta( $post->ID, '_easy_image_gallery_v2' );
	?>
	<div id="dx-eig-gallery">
		<div class="repeat">
			<div class="eig_repeat_container">
				<div class="buttons">
					<span class="button button-primary button-large eig-add"><?php esc_html_e( 'Add new gallery', 'easy-image-gallery' ); ?></span>
				</div>
				<div class="eig_repeat_body">
					<?php
					if ( ! empty( $old_meta_structure ) && empty( $new_meta_structure ) ) {
						?>
						<div class="alert-db-danger">
							You are currently using an old version of Database structure for this page (or post). Once you update the page (post), you will need to use the <strong>SHORTCODE</strong> in order to display the gallery. Otherwise, you will not be able to see the gallery in the front-end part of the page (post).
						</div>
						<?php
					}
					?>
					<div class="eig-template dx-eig-gallery-row row">
						<div class="dx-eig-gallery-row-heading move">
							<input type="text" hidden="" class="row_count" data-count="{{row-count-placeholder}}">
							<input type="text" hidden="" id="attachment_ids_{{row-count-placeholder}}" name="image_gallery[{{row-count-placeholder}}][DATA]" value="">
							<span class="name">Gallery</span>
							<a href="#" class="dx-eig-gallery-add-images button" data-count="{{row-count-placeholder}}"><?php esc_html_e( 'Add images to the gallery', 'easy-image-gallery' ); ?></a>
							<span class="eig-remove"><img src="<?php echo esc_url( EASY_IMAGE_GALLERY_URL . 'includes/fonts/close.png' ); ?>" alt=""></span>
							<a href="#" class="button button-primary button-small dx-eig-insert-shortcode">Insert this shortcode in the content</a>
							<input type="text" class="dx-eig-shortcode" name="image_gallery[{{row-count-placeholder}}][SHORTCODE]" value="" hidden>
							<input type="text" class="dx-eig-shortcode-show" readonly="" value="">
							<div class="link-image-to-l">
								<label for="easy_image_gallery_link_images_{{row-count-placeholder}}">
									<input type="checkbox" id="easy_image_gallery_link_images_{{row-count-placeholder}}" value="on" name="image_gallery[{{row-count-placeholder}}][OPEN_IMAGES]" checked="checked"/> <?php esc_html_e( 'Link images to larger sizes', 'easy-image-gallery' ); ?>
								</label>
							</div>
							<div class="dx-eig-clear"></div>
						</div>
						<div class="dx-eig-gallery-row-content" id="gallery-{{row-count-placeholder}}">
							<p class="no-images-message"><?php esc_html_e( 'Please add images in this gallery', 'easy-image-gallery' ); ?></p>
						</div>
					</div>
					<?php
					// START GALLERIES LOOP

					// CHECK FOR OLD DB

					if ( isset( $new_meta_structure ) && $new_meta_structure != null ) {
						$get_galleries = $new_meta_structure;
					} else {
						$get_gallery_attachments = $old_meta_structure;
						if ( isset( $get_gallery_attachments[0] ) ) {
							$get_gallery_old_data = explode( ',', $get_gallery_attachments[0] );
						} else {
							$get_gallery_old_data = null;
						}

						$get_open_images = get_post_meta( $post->ID, '_easy_image_gallery_link_images' );
						if ( isset( $get_open_images ) && ! empty( $get_open_images ) ) {
							$get_open_images = $get_open_images;
						} else {
							$get_open_images = null;
						}

						$get_galleries = array(
							array(
								array(
									'SHORTCODE'   => (string) wp_rand( 100000, 999999999 ),
									'DATA'        => $get_gallery_old_data,
									'OPEN_IMAGES' => $get_open_images[0],
								),
							),
						);
					}

					$gallery_count = -1;
					if ( isset( $get_galleries ) && ! empty( $get_galleries ) ) {
						$existing_shortcodes = array();

						foreach ( $get_galleries[0] as $gallery ) {
							$gallery_count   = $gallery_count + 1;

							$gallery_shortcode = easy_image_gallery_sanitize_gallery_id( $gallery['SHORTCODE'] );
							if ( '' === $gallery_shortcode || in_array( $gallery_shortcode, $existing_shortcodes, true ) ) {
								do {
									$candidate = (string) wp_rand( 100000, 999999999 );
								} while ( in_array( $candidate, $existing_shortcodes, true ) );

								$gallery_shortcode = $candidate;
							}

							$existing_shortcodes[] = $gallery_shortcode;
							$get_attachments = $gallery['DATA'];

							// Convert attachements to string
							$attachments_string = '';
							$attachemnnts_count = 0;
							if ( isset( $get_attachments ) && $get_attachments != null ) {
								foreach ( $get_attachments  as $attachment ) {
									$attachemnnts_count = $attachemnnts_count + 1;

									if ( $attachemnnts_count == 1 ) {
										$attachments_string .= $attachment;
									} else {
										$attachments_string .= ',' . $attachment;
									}
								}
							} else {
								$attachments_string = null;
							}
							?>
							<div class="dx-eig-gallery-row row">
								<div class="dx-eig-gallery-row-heading move">
									<input type="text" hidden="" class="row_count" data-count="<?php echo esc_attr( (string) $gallery_count ); ?>">
									<input type="text" hidden="" id="attachment_ids_<?php echo esc_attr( (string) $gallery_count ); ?>" name="image_gallery[<?php echo esc_attr( (string) $gallery_count ); ?>][DATA]" value="<?php echo esc_attr( (string) ( $attachments_string ?? '' ) ); ?>">
									<span class="name">Gallery</span>
									<a href="#" class="dx-eig-gallery-add-images button" data-count="<?php echo esc_attr( (string) $gallery_count ); ?>"><?php esc_html_e( 'Add images to the gallery', 'easy-image-gallery' ); ?></a>
									<span class="eig-remove"><img src="<?php echo esc_url( EASY_IMAGE_GALLERY_URL . 'includes/fonts/close.png' ); ?>" alt=""></span>
									<a href="#" class="button button-primary button-small dx-eig-insert-shortcode">Insert this shortcode in the content</a>
									<input type="text" class="dx-eig-shortcode" name="image_gallery[<?php echo esc_attr( (string) $gallery_count ); ?>][SHORTCODE]" value="<?php echo esc_attr( $gallery_shortcode ); ?>" hidden>
									<input type="text" class="dx-eig-shortcode-show" readonly="" value="<?php echo esc_attr( '[easy_image_gallery gallery="' . $gallery_shortcode . '"]' ); ?>">
									<div class="link-image-to-l">
										<label for="easy_image_gallery_link_images_<?php echo esc_attr( (string) $gallery_count ); ?>">
											<input type="checkbox" id="easy_image_gallery_link_images_<?php echo esc_attr( (string) $gallery_count ); ?>" value="on" name="image_gallery[<?php echo esc_attr( (string) $gallery_count ); ?>][OPEN_IMAGES]" <?php checked( isset( $gallery['OPEN_IMAGES'] ) ? $gallery['OPEN_IMAGES'] : '', 'on' ); ?> /> <?php esc_html_e( 'Link images to larger sizes', 'easy-image-gallery' ); ?>
										</label>
									</div>
									<div class="dx-eig-clear"></div>
								</div>
								<div class="dx-eig-gallery-row-content" id="gallery-<?php echo esc_attr( (string) $gallery_count ); ?>">
									<?php
									if ( isset( $get_attachments ) && $get_attachments != null ) {
										?>
									<p class="no-images-message" style="display: none;"><?php esc_html_e( 'Please add images in this gallery', 'easy-image-gallery' ); ?></p>
									<ul class="gallery_images">
										<div class="dx-eig-images sortable">
										<?php
										foreach ( $get_attachments as $attachemnt ) {
											echo '<li class="image attachment details" data-attachment_id="' . esc_attr( (string) $attachemnt ) . '" data-gallery="' . esc_attr( (string) $gallery_count ) . '">
                                                <div class="attachment-preview">
                                                    <div class="thumbnail">
                                                        ' . wp_get_attachment_image( $attachemnt, 'thumbnail' ) . '
                                                    </div>
                                                   <a href="#" class="delete_dx_image check" title="Remove Image"><div class="media-modal-icon"></div></a>
                                                </div>
                                            </li>';
										}
										?>
										</div>
										<div class="dx-eig-clear"></div>
									</ul>
										<?php
									} else {
										echo '<p class="no-images-message">';
										esc_html_e( 'Please add images in this gallery', 'easy-image-gallery' );
										echo '</p>';
									}
									?>
								</div>
							</div>
							<?php
						} // END GALLERIES LOOP
					}
					?>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		jQuery(function() {
			jQuery('.repeat').each(function() {
				jQuery(this).repeatable_fields({
					wrapper: '.eig_repeat_container',
					container: '.eig_repeat_body',
					template: '.eig-template',
					add: '.eig-add',
					remove: '.eig-remove',
				});
			});
		});
		function eig_sortable() {
			jQuery( function() {
				jQuery(".sortable").sortable({
					revert       : true,
					stop         : function(event,ui){
						var gallery = jQuery(this);
						var gallery_selector = jQuery(this).parent().parent();
						var get_gallery_id = gallery_selector.find('.image').attr('data-gallery');

						var items = gallery_selector.find('.gallery_images').find('.dx-eig-images').children();
						var attachments_ids = [];
						for (i = 0; i < items.length; i++) {
							var attachment_id = items[i].attributes[1].value;
							attachments_ids.push(attachment_id);
						}


						if (attachments_ids.length === 0){
							gallery.find('p.no-images-message').show();
						}

						jQuery('#attachment_ids_'+get_gallery_id+'').attr('value', attachments_ids);
					}
				});
			} );
		}

		jQuery(document).on( 'click', '.dx-eig-gallery-add-images', function(e) {
			var _id = jQuery( this ).attr( 'data-count' );
			var attachment_ids = null;
			e.preventDefault();

			var image = wp.media({
				title: 'Select images for your gallery',
				multiple: true,
			}).open();

			image.on( 'select', function() {
				var selection = image.state().get('selection');

				selection.map( function( attachment ) {

					attachment = attachment.toJSON();

					if ( attachment.id ) {
						attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

						var gallery = jQuery('#gallery-'+ _id +'');

						if (gallery.find('p.no-images-message') && gallery.find('p.no-images-message').css('display') === 'block'){
							gallery.find('p.no-images-message').css('display', 'none');
						}

						if (gallery.find('ul.gallery_images').length < 1){
							gallery.append(
								'<ul class="gallery_images">'+
								'<div class="dx-eig-images sortable"></div>'+
								'<div class="dx-eig-clear"></div>'+
								'</ul>'
							);

							eig_sortable();
						}

						attachment_url = '';
						if(typeof attachment.sizes.thumbnail !== 'undefined'){
							attachment_url = attachment.sizes.thumbnail.url;
						}else{
							attachment_url = attachment.url;
						}

						gallery.find('ul.gallery_images .dx-eig-images').append('\
						<li class="image attachment details" data-attachment_id="' + attachment.id + '" data-gallery="'+_id+'">\
							<div class="attachment-preview">\
								<div class="thumbnail">\
									<img src="' + attachment_url + '" />\
								</div>\
							   <a href="#" class="delete_dx_image check" title="<?php esc_attr_e( 'Remove image', 'easy-image-gallery' ); ?>"><div class="media-modal-icon"></div></a>\
							</div>\
						</li>');
					}

				} );

				var attachments_selector = jQuery('#attachment_ids_'+ _id +'');
				var current_image_ids = attachments_selector.attr('value');
				if (current_image_ids.length > 0){
					current_image_ids = current_image_ids + ',';
				}
				attachments_selector.attr('value',current_image_ids + attachment_ids);
			});
		});

		jQuery(document).on( 'click', '.delete_dx_image', function(e) {
			//Get info
			var info = jQuery(this).parent().parent();
			var gallery = info.attr('data-gallery');
			var gallery_selector = jQuery('#gallery-'+gallery+'');

			//Remove the item
			info.remove();

			//Save new items
			var items = gallery_selector.find('.gallery_images').find('.dx-eig-images').children();
			var attachments_ids = [];
			for (i = 0; i < items.length; i++) {
				var attachment_id = items[i].attributes[1].value;

				attachments_ids.push(attachment_id);
			}

			if (attachments_ids.length === 0){
				gallery_selector.find('p.no-images-message').show();
			}

			jQuery('#attachment_ids_'+gallery+'').attr('value', attachments_ids);

			return false;
		});

		jQuery(document).on( 'click', '.dx-eig-insert-shortcode', function(e) {
			e.preventDefault();

			var id = jQuery(this).parent().find('.dx-eig-shortcode').val(),
				shortcode = '[easy_image_gallery gallery="'+ id +'"]';

			// Gutenberg
			if ( typeof wp.blocks !== 'undefined' ) {
				var block = wp.blocks.createBlock( 'core/shortcode' );
				block.attributes.text = shortcode;
				wp.data.dispatch( 'core/editor' ).insertBlocks( block );

			// Classic Editor
			} else if ( typeof tinymce !== 'undefined' ) {
				var editor = tinymce.get('content'),
					content = editor.getContent();

				content += "\n\n" + shortcode;

				editor.setContent( content );
			}
		});

		eig_sortable();
	</script>
	<?php
}


/**
 * Save function
 *
 * @since 1.0
 */
function easy_image_gallery_save_post( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	$post_id = (int) $post_id;
	if ( $post_id <= 0 ) {
		return;
	}

	$post_types = easy_image_gallery_allowed_post_types();
	if ( empty( $post_types ) || ! is_array( $post_types ) ) {
		return;
	}

	// Check user permissions (use stored post type, not $_POST — avoids unverified POST reads).
	$easy_image_gallery_current_post_type = get_post_type( $post_id );
	if ( $easy_image_gallery_current_post_type && ! array_key_exists( $easy_image_gallery_current_post_type, $post_types ) ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Require core post update nonce before any $_POST reads (incl. action / gallery fields).
	if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'update-post_' . $post_id ) ) {
		return;
	}

	if ( isset( $_POST['action'] ) && 'inline-save' === $_POST['action'] ) {
		return;
	}

	if ( isset( $_POST['image_gallery'] ) && ! empty( $_POST['image_gallery'] ) ) {
		$galleries = array();

		$easy_image_gallery_post = map_deep( wp_unslash( $_POST['image_gallery'] ), 'sanitize_text_field' );

		$existing_shortcodes = array();

		foreach ( $easy_image_gallery_post as $gallery ) {
			if ( ! is_array( $gallery ) ) {
				continue;
			}

			$gallery_data = isset( $gallery['DATA'] ) ? $gallery['DATA'] : '';

			if ( '' !== $gallery_data ) {
				$convert_to_arr = explode( ',', $gallery_data );
			} else {
				$convert_to_arr = null;
			}

			$gallery['DATA'] = $convert_to_arr;

			if ( isset( $gallery['SHORTCODE'] ) ) {
				$gallery['SHORTCODE'] = easy_image_gallery_sanitize_gallery_id( $gallery['SHORTCODE'] );

				if ( '' === $gallery['SHORTCODE'] || in_array( $gallery['SHORTCODE'], $existing_shortcodes, true ) ) {
					do {
						$candidate = (string) wp_rand( 100000, 999999999 );
					} while ( in_array( $candidate, $existing_shortcodes, true ) );

					$gallery['SHORTCODE'] = $candidate;
				}

				$existing_shortcodes[] = $gallery['SHORTCODE'];
			}

			$galleries[] = $gallery;
		}

		update_post_meta( $post_id, '_easy_image_gallery_v2', $galleries );
		delete_post_meta( $post_id, '_easy_image_gallery' );
	} elseif ( isset( $_POST['action'] ) && 'editpost' === $_POST['action'] ) {
		delete_post_meta( $post_id, '_easy_image_gallery_v2' );
	}

	// Link to larger images (legacy POST key).
	if ( isset( $_POST['easy_image_gallery_link_images'] ) ) {
		update_post_meta(
			$post_id,
			'_easy_image_gallery_link_images',
			sanitize_text_field( wp_unslash( $_POST['easy_image_gallery_link_images'] ) )
		);
	} else {
		update_post_meta( $post_id, '_easy_image_gallery_link_images', 'on' );
	}

	do_action( 'easy_image_gallery_save_post', $post_id );
}
add_action( 'save_post', 'easy_image_gallery_save_post' );
