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

		/**
		 * If you need to include Kirki in your theme,
		 * then you may want to consider adding the translations here
		 * using your textdomain.
		 */

		$strings = array(
			'background-color' => __( 'Background Color', 'boldgrid' ),
			'background-image' => __( 'Background Image', 'boldgrid' ),
			'no-repeat' => __( 'No Repeat', 'boldgrid' ),
			'repeat-all' => __( 'Repeat All', 'boldgrid' ),
			'repeat-x' => __( 'Repeat Horizontally', 'boldgrid' ),
			'repeat-y' => __( 'Repeat Vertically', 'boldgrid' ),
			'inherit' => __( 'Inherit', 'boldgrid' ),
			'background-repeat' => __( 'Background Repeat', 'boldgrid' ),
			'cover' => __( 'Cover', 'boldgrid' ),
			'contain' => __( 'Contain', 'boldgrid' ),
			'background-size' => __( 'Background Size', 'boldgrid' ),
			'fixed' => __( 'Fixed', 'boldgrid' ),
			'scroll' => __( 'Scroll', 'boldgrid' ),
			'background-attachment' => __( 'Background Attachment', 'boldgrid' ),
			'left-top' => __( 'Left Top', 'boldgrid' ),
			'left-center' => __( 'Left Center', 'boldgrid' ),
			'left-bottom' => __( 'Left Bottom', 'boldgrid' ),
			'right-top' => __( 'Right Top', 'boldgrid' ),
			'right-center' => __( 'Right Center', 'boldgrid' ),
			'right-bottom' => __( 'Right Bottom', 'boldgrid' ),
			'center-top' => __( 'Center Top', 'boldgrid' ),
			'center-center' => __( 'Center Center', 'boldgrid' ),
			'center-bottom' => __( 'Center Bottom', 'boldgrid' ),
			'background-position' => __( 'Background Position', 'boldgrid' ),
			'background-opacity' => __( 'Background Opacity', 'boldgrid' ),
			'ON' => __( 'ON', 'boldgrid' ),
			'OFF' => __( 'OFF', 'boldgrid' ),
			'all' => __( 'All', 'boldgrid' ),
			'cyrillic' => __( 'Cyrillic', 'boldgrid' ),
			'cyrillic-ext' => __( 'Cyrillic Extended', 'boldgrid' ),
			'devanagari' => __( 'Devanagari', 'boldgrid' ),
			'greek' => __( 'Greek', 'boldgrid' ),
			'greek-ext' => __( 'Greek Extended', 'boldgrid' ),
			'khmer' => __( 'Khmer', 'boldgrid' ),
			'latin' => __( 'Latin', 'boldgrid' ),
			'latin-ext' => __( 'Latin Extended', 'boldgrid' ),
			'vietnamese' => __( 'Vietnamese', 'boldgrid' ),
			'serif' => _x( 'Serif', 'font style', 'boldgrid' ),
			'sans-serif' => _x( 'Sans Serif', 'font style', 'boldgrid' ),
			'monospace' => _x( 'Monospace', 'font style', 'boldgrid' ),
		);

		$args = array(
			'url_path'     => $url,
			'logo_image'   => $logo,
			'textdomain'   => 'boldgrid',
			'i18n'         => $strings,
		);

		return $args;

	}
}
