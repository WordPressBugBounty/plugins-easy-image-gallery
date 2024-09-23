=== Easy Image Gallery ===
Contributors: devrix, nofearinc
Tags: image gallery, image, galleries, simple, easy, devrix
Requires at least: 3.5
Tested up to: 6.1
Stable tag: 1.5.1
Requires PHP: 7.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily create an image gallery on your posts, pages or any custom post type

== Description ==
This plugin allows you to easily create an image gallery on any post, page or custom post type. 
Images are can be added and previewed from the metabox. Images can be re-ordered by drag and drop.

About:  

There comes a time when you need more flexibility than the standard WP gallery offers, That’s when this plugin steps in.
This plugin’s goal is to make it easy to create a gallery and place it wherever you need.
A perfect example would be to create a product gallery for an ecommerce website and then 
have the flexibility to position it where you wanted to match your theme’s design.

Features:

1. Added the possibility to add two or more different galleries on one page
1. Drag and drop re-ordering
1. Add gallery to any post, page or custom post type
1. If more than one image is added to the gallery, the images become grouped in the lightbox so you can easily view the next one
1. CSS and JS are only loaded on pages where needed
1. Images link to larger versions (can be turned off)
1. Fully Localized (translation ready) with .mo and .po files
1. Add multiple images to the gallery at once
1. Uses the thumbnail size specified in Settings -> Media
1. Custom webfont icon for hover effect

= Whats' New =
1. Added Gutenberg Support
1. Added New Gallery - Luminous

= Usage =

Use the shortcode or the function to show the gallery.

= Shortcode Usage =

Use the following shortcode anywhere in the content area to display the gallery

	[easy_image_gallery gallery="XXX"]
	
Where "XXX" is the ID of the gallery item.

= Template Tag Usage =

The following template tag is available to display the gallery

	if( function_exists( 'easy_image_gallery' ) ) {
		echo easy_image_gallery( "XXX" );
	}
	
Where "XXX" is the ID of the gallery item.

If you use the template tag above, you will need to remove the default content filter:

= Developer Friendly =

1. Modify the gallery HTML using filters
1. Developed with WP Coding Standards
1. Easily add your preferred lightbox script via hooks and filters
1. Easily unhook CSS and add your own styling
1. Pass in a different image size for the thumbnails via filter
1. Minimalistic markup and styling

**Stay up to date**

*Become a fan on Facebook*
[https://www.facebook.com/DevriXLtd](https://www.facebook.com/DevriXLtd "Facebook Devrix")

*Follow us on Twitter*
[https://twitter.com/wpdevrix](https://twitter.com/wpdevrix)

== Installation ==

1. Upload the entire `easy-image-gallery` folder to the `/wp-content/plugins/` directory, or just upload the ZIP package via 'Plugins > Add New > Upload' in your WP Admin
1. Activate Easy Image Gallery from the 'Plugins' page in WordPress
1. Configure the plugin's settings from Settings -> Media
1. Create a gallery on any post or page from the added 'Image Gallery' metabox.

== Screenshots ==

1. The plugin's simple configuration screen. Any existing custom post types will appear here
1. The plugin's simple metabox that is added to the publish/edit screens
1. You can select multiple images to insert into the gallery
1. The plugin's Image Gallery metabox after images have been inserted and the post has been saved
1. The front-end of the website showing the gallery which has been automatically appended to the content
1. Clicking on an image launches the lightbox. Here it's shown with prettyPhoto

== Frequently Asked Questions ==

= Why my galleries don't show up after an update to Version 1.3 or above? =

In Version 1.3 of the plugin, we've added a major update. Now, you have the ability to add more than one galleries to your posts or pages.

For that reason, we have to introduce Gallery ID as an argument for the shortcode. E.g. you need to use [easy_image_gallery gallery="XXX"], where XXX is the ID of the gallery you want to display.

Each of your galleries in the edit screen of your page or post will generate a new shortcode which you'll be able to use in the page or post editor.

= Where are the plugin's settings? =

In your WordPress admin under Settings -> Media

= How can I use a different thumbnail size for each post type? =

[Read This](https://devrix.com/resources/plugins/easy-image-gallery/ "Different thumbnail sizes for each post type with Easy Image Gallery")

== Upgrade Notice ==
The plugin ownership was transferred to DevriX. There are no functionality changes. We are going to work on a few version, adding some nice feature in the near feature, stay tuned! :)

== Changelog ==

= 1.5.1 =
* Change required PHP version
* Tested up with WordPress 6.1

= 1.5 =
* Has been added separate menu for the plugin
* Fix: Link to larger images

= 1.4.3 =
* Fix error on 404 pages about Trying to get property ‘ID’ of non-object

= 1.4.2 =
* Release date - May 14, 2020
* Fixed PHP warning about array_key_exists()

= 1.4.1 =
* Release date - January 22, 2020
* Fixing TwentyTwenty single post styles
* Fixing Add link to larger image by default
* Fixing prettyPhoto console error
* Fixing Insert this shordcode function 

= 1.4.0 =
* Release date - January 7, 2020
* Introduced "add to post" button in the gallery metabox for inserting shortcodes;
* Gutenberg compatibility - adding Easy Image Gallery Gutenberg block;
* "_blank" target for the image is added when the gallery is without lightbox, thus improving UX;
* Fancybox upgraded to v.3.5.7;
* Adding caption to the image for fancybox;
* Change the classes of the plugin with prefixes;
* Fixing large image size on a href click (PrettyPhoto);
* Fixing Fatal error on WP version 4.9.8;
* Fixing a couple of font references where not pointing to the right folder in blocks.style.build.css;
* Fixing gallery delete when edit the post with quick edit;
* Fixing gallery delete when the post is in the trash;
* Removing OceanWP theme scripts for proper plugin functionality;
* Added new gallery - Luminous

= 1.3.1 =
* Release date - May 07, 2018.
* Remove jquery-ui.min.js from the plugin and use the WordPress Core version of the library.
* Bugfix: add a fallback when the user is using the plugin's function in the code, instead of a shortcoe.

= 1.3 =
* Release date - January 19, 2018.
* Add the possibility to add two or more different galleries on one page/post.
* Improved the UI / UX

= 1.2 =
* Release date - January 26, 2017.
* The plugin ownership was transferred to DevriX. We are going to maintain and update the plugin for now on :)

= 1.1.5 =
* Update Easy Image Gallery author

= 1.1.4 =
* Tweak: Updated French translations, props fxbenard

= 1.1.3 =
* Tweak: Added text domain to plugin headers in accordance with new translation system

= 1.1.2 =
* Fix: Added esc_attr to title attribute. Captions that included quotes were getting cut off.
* Fix: Updated PrettyPhoto JavaScript file to v3.1.6

= 1.1.1 =
* Fix: Missing slash on path to CSS file when plugin's CSS is overridden from a child theme

= 1.1 =
* Tweak: fancybox 2 has been replaced with fancybox 1, as non GPL software is not allowed on the WP repo (fancyBox 2 is license under Creative Commons).

= 1.0.6 =
* Fix: Settings link on plugins page
* Fix PHP notice on Settings -> Media page
* Tweak: removed unneeded function

= 1.0.5 =

* Tweak: The plugin's options page has been moved to settings -> media
* Tweak: Renamed the 'thumbnail_image_size' filter name to be 'easy_image_gallery_thumbnail_image_size' so it's unique to the plugin
* Tweak: Renamed the 'linked_image_size' filter name to be 'easy_image_gallery_linked_image_size' so it's unique to the plugin

= 1.0.4 =

* Fix: Images now use the image's caption rather than title field
* New: Styling for image placeholder as images are being dragged

= 1.0.3 =

* Fix: jQuery script that calls light box was being loaded when there were no gallery images

= 1.0.2 =

* Tweak: Improved loading of scripts

= 1.0.1 =

* Tweak: Images now link to the "large" image size by default, rather than the original image
* Tweak: Removed "remove" links underneath each image and added a [-] on hover to be consistent with WordPress' media manager styling
* New: linked_image_size filter for specifying which image size the thumbnails should link to
* New: 2 new filters added for overriding the JS for prettyPhoto and fancyBox. easy_image_gallery_prettyphoto_js and easy_image_gallery_fancybox_js

= 1.0 =

* Initial release				
