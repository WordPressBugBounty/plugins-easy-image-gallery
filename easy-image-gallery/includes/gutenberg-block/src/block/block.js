/**
 * BLOCK: easy-image-gallery-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n

// Import registerBlockType() from wp.blocks
const {
	BlockControls,
	registerBlockType
} = wp.blocks;

//Import MediaUpload from wp.editor
const {
	MediaUpload,
	InspectorControls
} = wp.editor;

 //Import Button from wp.components
const {
	PanelBody,
	PanelRow,
	Button,
	FormToggle,
	SelectControl
} = wp.components;

const {
	Fragment
} = wp.element;

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
let ID = function () {
	// Math.random should be unique because of its seeding algorithm.
	// Convert it to base 36 (numbers + letters), and grab the first 9 characters
	// after the decimal.
	return '_' + Math.random().toString(36).substr(2, 9);
};

let uniqueNumber = function () {
	// Math.random should be unique because of its seeding algorithm.
	// Convert it to base 36 (numbers + letters), and grab the first 9 characters
	// after the decimal.
	return Math.floor(100 + Math.random() * 900);
};

registerBlockType( 'devrix/easy-image-gallery-block', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: 'Easy Image Gallery', // Block title.
	icon: 'format-gallery', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'easy-image-gallery-block' ),
		__( 'Easy Image Gallery' ),
	],
	attributes: { //Attributes
		images : { //Images array
			type: 'array',
		},
		id : {
			type: 'string',
		},
		unique_number : {
			type: 'number',
		},
		link_images : {
			type: 'boolean',
			default: true
		},
		lightbox_option : {
			type: 'string',
		},
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit({ attributes, className, setAttributes, isSelected }) {
		//Destructuring the images array attribute
		const { images = [], link_images, lightbox_option, unique_number } = attributes;

		// This removes an image from the gallery
		const removeImage = (removeImage) => {
			//filter the images
			const newImages = images.filter( (image) => {
				//If the current image is equal to removeImage the image will be returnd
				if(image.id != removeImage.id) {
					return image;
				}
			});

			//Saves the new state
			setAttributes({
				images:newImages,
			})
		}

		if( typeof lightbox_option == 'undefined'){
			setAttributes( { lightbox_option: 'fancybox' } );			
		}

		const toggleLinkImages = () => {
			setAttributes( { link_images: !link_images } );
			setAttributes( { unique_number: uniqueNumber() } );
		};

		const setLightbox = (lightbox_option) => {
			setAttributes( { unique_number: uniqueNumber() } );
			setAttributes( { lightbox_option: lightbox_option } );
		};

		//Displays the images
		const displayImages = (images) => {
			return (
				//Loops throug the images
				images.map( (image) => {
					return (
					<div className="gallery-item-container">
							<img className='gallery-item' src={image.url} key={ images.id } />
							{ isSelected && ( <div className='remove-item'><span class="dashicons dashicons-trash" onClick={() => removeImage(image)}></span></div> ) }
					</div>
					)
				})

			)
		}
		let _id = ""
		if( attributes['id'] != undefined ) {
			_id = attributes['id'];
		} else {
			_id = ID();
		}
		setAttributes({id : _id,})

		//JSX to return
		return (
			<Fragment>
				<InspectorControls>
					<PanelBody>
						<PanelRow>
							<label
								htmlFor="link-images-form-toggle"
							>
								{ __( 'Link images to larger sizes', 'dx-link-images' ) }
							</label>
							<FormToggle
								id="link-images-form-toggle"
								label={ __( 'Link images to larger sizes', 'dx-link-images' ) }
								checked={ link_images }
								onChange={ toggleLinkImages }
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={ __( 'Lightbox:' ) }
								value={ lightbox_option }
								onChange={ setLightbox }
								options={ [
									{ value: 'fancybox', label: 'fancyBox' },
									{ value: 'pretty-photo', label: 'prettyPhoto' },
									{ value: 'luminous', label: 'Luminous' },
								] }
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
				<div>
					<ul className="image-gallery thumbnails-4 linked" data-total-slides={images.length}>{ displayImages(images) }</ul>
					{ isSelected && ( <MediaUpload
							onSelect={(media) => {setAttributes({images: [...images, ...media]});}}
							type="image"
							multiple={true}
							value={images}
							render={({open}) => (
								<Button className="select-images-button is-button is-default is-large" onClick={open}>
									Add images
								</Button>
							)}
						/> ) }
					
				</div>
			</Fragment>

		);
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save({attributes}) {
		//Destructuring the images array attribute
		const { images, link_images, lightbox_option, unique_number } = attributes;

		if(typeof images === 'undefined'){
			return false;
		}

		//let rel = "rel=\"prettyphoto[" + attributes['id'] + "]\""
		let data_fancybox = 'gallery';

		// Displays the images
		const displayImages = (images) => {

			return (

				images.map( (image,index) => {
					let imgSize = 'thumbnail';
					let imgHrefSize  = 'large';

					if(typeof image.sizes['thumbnail'] === 'undefined'){
						imgSize = 'full';
					}

					if(typeof image.sizes['large'] === 'undefined'){
						imgHrefSize = 'full';
					}

					const imageWidth = image.sizes[imgSize]['width'];
					const imageHeight = image.sizes[imgSize]['height'];
					const imageThumb = image.sizes[imgSize]['url'];
					const imageHrefUrl = image.sizes[imgHrefSize]['url'];

					const lightbox_attr_data = {
						'fancybox' : { 'key': 'data-fancybox', 'value': data_fancybox + unique_number, },
						'pretty-photo' : { 'key': 'rel', 'value': 'prettyPhoto[group-'+unique_number+']', },
						'luminous' : { 'key': 'rel', 'value': 'luminous[group-'+unique_number+']', },
					}

					return (
						<li>
					{ link_images && (
						<a href={imageHrefUrl} {...{[lightbox_attr_data[lightbox_option]['key']]: lightbox_attr_data[lightbox_option]['value']}} data-caption={image.caption} className='eig-popup'>
						<i className="icon-view"></i><span className="overlay"></span>
						<img
							className='attachment-thumbnail size-thumbnail'
							key={ images.id }
							src={ imageThumb }
							data-slide-no={ index }
							alt={ image.alt }
							width={ imageWidth }
							height={ imageHeight }
						/>
						</a>
						)
					}

					{ ! link_images && (
								<img
									className='attachment-thumbnail size-thumbnail'
									key={ images.id }
									src={ imageThumb }
									data-slide-no={ index }
									alt={ image.alt }
									width={ imageWidth }
									height={ imageHeight }
								/> ) }
							
						</li>
					)
				})
			)
		}

		//JSX to return
		return (
			<ul className="easy-image-gallery thumbnails-4 linked" data-total-slides={images.length}>{ displayImages(images) }
					{ 'luminous' == lightbox_option && (
						<script>new LuminousGallery(document.querySelectorAll("a[rel='luminous[group-{unique_number}]']"));</script>
						)
					}
			</ul>

		);
	},
} );
