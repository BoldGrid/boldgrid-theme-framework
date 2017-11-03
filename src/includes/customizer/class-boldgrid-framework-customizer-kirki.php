<?php
/**
 * Footer Customizer Functionality
 *
 * @link http://www.boldgrid.com
 * @since 1.0.0
 *
 * @package Boldgrid_Theme_Framework
 */

/**
 * Class: Boldgrid_Framework_Customizer_Kirki
 *
 * General Kirki settings to help generate WordPress customizer controls.
 *
 * @since      1.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer_Kirki
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */
class Boldgrid_Framework_Customizer_Kirki {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.0.0
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}


	/**
	 * Add Kirki Configs
	 *
	 * @param     array $controls    array of controls to pass to kirki.
	 * @return    array    $args        logo path and textdomain information.
	 */
	public function general_kirki_configs( $controls ) {
		$url  = $this->configs['framework']['root_uri'] . 'includes/kirki/';
		$logo = $this->configs['framework']['admin_asset_dir'] . 'img/boldgrid-logo.png';

		Kirki::$url = $url;

		$args = array(
			'url_path'     => $url,
			'logo_image'   => $logo,
			'textdomain'   => 'boldgrid',
		);

		return $args;

	}

	/**
	 * This method returns the language translation strings used in the
	 * BoldGrid Theme Framework Kirki implmentation.
	 *
	 * @since 2.0.0
	 */
	public function l10n( $l10n ) {
		$l10n['background-color']      = esc_attr__( 'Background Color', 'bgtfw' );
		$l10n['background-image']      = esc_attr__( 'Background Image', 'bgtfw' );
		$l10n['no-repeat']             = esc_attr__( 'No Repeat', 'bgtfw' );
		$l10n['repeat-all']            = esc_attr__( 'Repeat All', 'bgtfw' );
		$l10n['repeat-x']              = esc_attr__( 'Repeat Horizontally', 'bgtfw' );
		$l10n['repeat-y']              = esc_attr__( 'Repeat Vertically', 'bgtfw' );
		$l10n['inherit']               = esc_attr__( 'Inherit', 'bgtfw' );
		$l10n['background-repeat']     = esc_attr__( 'Background Repeat', 'bgtfw' );
		$l10n['cover']                 = esc_attr__( 'Cover', 'bgtfw' );
		$l10n['contain']               = esc_attr__( 'Contain', 'bgtfw' );
		$l10n['background-size']       = esc_attr__( 'Background Size', 'bgtfw' );
		$l10n['fixed']                 = esc_attr__( 'Fixed', 'bgtfw' );
		$l10n['scroll']                = esc_attr__( 'Scroll', 'bgtfw' );
		$l10n['background-attachment'] = esc_attr__( 'Background Attachment', 'bgtfw' );
		$l10n['left-top']              = esc_attr__( 'Left Top', 'bgtfw' );
		$l10n['left-center']           = esc_attr__( 'Left Center', 'bgtfw' );
		$l10n['left-bottom']           = esc_attr__( 'Left Bottom', 'bgtfw' );
		$l10n['right-top']             = esc_attr__( 'Right Top', 'bgtfw' );
		$l10n['right-center']          = esc_attr__( 'Right Center', 'bgtfw' );
		$l10n['right-bottom']          = esc_attr__( 'Right Bottom', 'bgtfw' );
		$l10n['center-top']            = esc_attr__( 'Center Top', 'bgtfw' );
		$l10n['center-center']         = esc_attr__( 'Center Center', 'bgtfw' );
		$l10n['center-bottom']         = esc_attr__( 'Center Bottom', 'bgtfw' );
		$l10n['background-position']   = esc_attr__( 'Background Position', 'bgtfw' );
		$l10n['background-opacity']    = esc_attr__( 'Background Opacity', 'bgtfw' );
		$l10n['on']                    = esc_attr__( 'ON', 'bgtfw' );
		$l10n['off']                   = esc_attr__( 'OFF', 'bgtfw' );
		$l10n['all']                   = esc_attr__( 'All', 'bgtfw' );
		$l10n['cyrillic']              = esc_attr__( 'Cyrillic', 'bgtfw' );
		$l10n['cyrillic-ext']          = esc_attr__( 'Cyrillic Extended', 'bgtfw' );
		$l10n['devanagari']            = esc_attr__( 'Devanagari', 'bgtfw' );
		$l10n['greek']                 = esc_attr__( 'Greek', 'bgtfw' );
		$l10n['greek-ext']             = esc_attr__( 'Greek Extended', 'bgtfw' );
		$l10n['khmer']                 = esc_attr__( 'Khmer', 'bgtfw' );
		$l10n['latin']                 = esc_attr__( 'Latin', 'bgtfw' );
		$l10n['latin-ext']             = esc_attr__( 'Latin Extended', 'bgtfw' );
		$l10n['vietnamese']            = esc_attr__( 'Vietnamese', 'bgtfw' );
		$l10n['hebrew']                = esc_attr__( 'Hebrew', 'bgtfw' );
		$l10n['arabic']                = esc_attr__( 'Arabic', 'bgtfw' );
		$l10n['bengali']               = esc_attr__( 'Bengali', 'bgtfw' );
		$l10n['gujarati']              = esc_attr__( 'Gujarati', 'bgtfw' );
		$l10n['tamil']                 = esc_attr__( 'Tamil', 'bgtfw' );
		$l10n['telugu']                = esc_attr__( 'Telugu', 'bgtfw' );
		$l10n['thai']                  = esc_attr__( 'Thai', 'bgtfw' );
		$l10n['serif']                 = _x( 'Serif', 'font style', 'bgtfw' );
		$l10n['sans-serif']            = _x( 'Sans Serif', 'font style', 'bgtfw' );
		$l10n['monospace']             = _x( 'Monospace', 'font style', 'bgtfw' );
		$l10n['font-family']           = esc_attr__( 'Font Family', 'bgtfw' );
		$l10n['font-size']             = esc_attr__( 'Font Size', 'bgtfw' );
		$l10n['font-weight']           = esc_attr__( 'Font Weight', 'bgtfw' );
		$l10n['line-height']           = esc_attr__( 'Line Height', 'bgtfw' );
		$l10n['font-style']            = esc_attr__( 'Font Style', 'bgtfw' );
		$l10n['letter-spacing']        = esc_attr__( 'Letter Spacing', 'bgtfw' );
		$l10n['top']                   = esc_attr__( 'Top', 'bgtfw' );
		$l10n['bottom']                = esc_attr__( 'Bottom', 'bgtfw' );
		$l10n['left']                  = esc_attr__( 'Left', 'bgtfw' );
		$l10n['right']                 = esc_attr__( 'Right', 'bgtfw' );
		$l10n['color']                 = esc_attr__( 'Color', 'bgtfw' );
		$l10n['add-image']             = esc_attr__( 'Add Image', 'bgtfw' );
		$l10n['change-image']          = esc_attr__( 'Change Image', 'bgtfw' );
		$l10n['remove']                = esc_attr__( 'Remove', 'bgtfw' );
		$l10n['no-image-selected']     = esc_attr__( 'No Image Selected', 'bgtfw' );
		$l10n['select-font-family']    = esc_attr__( 'Select a font-family', 'bgtfw' );
		$l10n['variant']               = esc_attr__( 'Variant', 'bgtfw' );
		$l10n['subsets']               = esc_attr__( 'Subset', 'bgtfw' );
		$l10n['size']                  = esc_attr__( 'Size', 'bgtfw' );
		$l10n['height']                = esc_attr__( 'Height', 'bgtfw' );
		$l10n['spacing']               = esc_attr__( 'Spacing', 'bgtfw' );
		$l10n['ultra-light']           = esc_attr__( 'Ultra-Light 100', 'bgtfw' );
		$l10n['ultra-light-italic']    = esc_attr__( 'Ultra-Light 100 Italic', 'bgtfw' );
		$l10n['light']                 = esc_attr__( 'Light 200', 'bgtfw' );
		$l10n['light-italic']          = esc_attr__( 'Light 200 Italic', 'bgtfw' );
		$l10n['book']                  = esc_attr__( 'Book 300', 'bgtfw' );
		$l10n['book-italic']           = esc_attr__( 'Book 300 Italic', 'bgtfw' );
		$l10n['regular']               = esc_attr__( 'Normal 400', 'bgtfw' );
		$l10n['italic']                = esc_attr__( 'Normal 400 Italic', 'bgtfw' );
		$l10n['medium']                = esc_attr__( 'Medium 500', 'bgtfw' );
		$l10n['medium-italic']         = esc_attr__( 'Medium 500 Italic', 'bgtfw' );
		$l10n['semi-bold']             = esc_attr__( 'Semi-Bold 600', 'bgtfw' );
		$l10n['semi-bold-italic']      = esc_attr__( 'Semi-Bold 600 Italic', 'bgtfw' );
		$l10n['bold']                  = esc_attr__( 'Bold 700', 'bgtfw' );
		$l10n['bold-italic']           = esc_attr__( 'Bold 700 Italic', 'bgtfw' );
		$l10n['extra-bold']            = esc_attr__( 'Extra-Bold 800', 'bgtfw' );
		$l10n['extra-bold-italic']     = esc_attr__( 'Extra-Bold 800 Italic', 'bgtfw' );
		$l10n['ultra-bold']            = esc_attr__( 'Ultra-Bold 900', 'bgtfw' );
		$l10n['ultra-bold-italic']     = esc_attr__( 'Ultra-Bold 900 Italic', 'bgtfw' );
		$l10n['invalid-value']         = esc_attr__( 'Invalid Value', 'bgtfw' );

		return $l10n;
	}
}

/**
 * Add the theme configuration
 */
Kirki::add_config(
	'bgtfw',
	array(
		'option_type' => 'theme_mod',
		'capability'  => 'edit_theme_options',
	)
);
