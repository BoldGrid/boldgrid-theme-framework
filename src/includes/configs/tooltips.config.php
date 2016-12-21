<?php
/**
 * The tooltip configuration options for bgtfw.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since    1.1
 *
 * @return   array   An array of tooltip configs used in bgtfw.
 */

return array(
	array(
		'settings' => 'boldgrid-background-type',
		'help' => __( 'You can choose a background image or a Pattern/Color.', 'bgtfw' ),
	),
	array(
		'settings' => 'boldgrid_background_horizontal_position',
		'help' => __( 'Use this control to move your image left and right. This can be used to adjust visible portion of your image.', 'bgtfw' ),
	),
	array(
		'settings' => 'boldgrid_background_vertical_position',
		'help' => __( 'Use this control to move your image up and down. This can be used to adjust visible portion of your image.', 'bgtfw' ),
	),
	array(
		'settings' => 'boldgrid_background_color',
		'help' => __( 'Change the color of your background. This can affect the color behind images including the patterns below. Click clear within the color selection tool if you would like your current color palette to determine the background color instead.', 'bgtfw' ),
	),
	array(
		'settings' => 'boldgrid_background_pattern',
		'help' => __( 'Select a pattern to use as your background image. These patterns can inherit the color of your chosen palette or use the color you chose above. You can also remove the selected pattern to use a flat color instead.', 'bgtfw' ),
	),
	array(
		'settings' => 'boldgrid-color-palette',
		'help' => __( 'The BoldGrid Color Palette System allows you to create custom color palettes. Try changing the order of colors in a palette, or use "Suggest Palettes" to automatically generate new palettes.', 'bgtfw' ),
	),
	array(
		'settings' => 'boldgrid_logo_setting',
		'help' => __( 'You can either have your Site Title displayed or upload your Logo.  See our guide for best practices with Logos.', 'bgtfw' ),
	),
	array(
		'settings' => 'boldgrid_footer_widgets',
		'help' => __( 'This theme’s footer supports up to 4 columns of Widget Areas.  You can place as many Widgets as you like in each area.', 'bgtfw' ),
	),
	array(
		'settings' => 'boldgrid_header_widgets',
		'help' => __( 'This theme’s header supports up to 4 columns of Widget Areas.  You can place as many Widgets as you like in each area.', 'bgtfw' ),
	),
	array(
		'settings' => 'boldgrid_footer_html',
		'help' => __( 'You can add additional HTML that will appear at the bottom of your theme with this textbox.', 'bgtfw' ),
	),
	array(
		'settings' => 'boldgrid_header_html',
		'help' => __( 'You can add additional HTML that will appear at the top of your theme with this textbox.', 'bgtfw' ),
	),
	array(
		'settings' => 'custom_theme_css',
		'help' => __( 'The CSS you add to this textbox will be applied directly to your theme.  It will override CSS rules made by your theme creator.', 'bgtfw' ),
	),
	array(
		'settings' => 'custom_theme_js',
		'help' => __( 'The Javascript you add to this textbox will be appended to your themes existing Javascript.  In order to execute this code, you must refresh the page, or visit the site outside of the customizer.', 'bgtfw' ),
	),
	array(
		'settings' => 'boldgrid_header_html',
		'help' => __( 'You can add additional HTML that will appear at the top of your theme with this textbox.', 'bgtfw' ),
	),
	array(
		'settings' => 'boldgrid_background_image_size',
		'help' => __( 'The background size setting that best fits your theme, will vary based on your selected image. If the image does not appear to your liking, cycle through the options until you find a setting that best fits your needs.', 'bgtfw' ),
	),
	array(
		'settings' => 'background_attachment',
		'help' => __( 'Background Effects can be used to change the appearance of the background as you scroll down the page. The Parallax effect causes the image to move at a different speed from the scroll speed.', 'bgtfw' ),
	),
	array(
		'settings' => 'background_repeat',
		'help' => __( 'Background Repeat is commonly used with patterns and will give your image a tiled appearance.', 'bgtfw' ),
	),
	array(
		'settings' => 'background_image',
		'help' => __( 'To use a full size background image, we recommend a size of at least 1920 x 1080. The BoldGrid Connect Search, available when you select change image, will help you find large images that are suitable.', 'bgtfw' ),
	),
);
