<?php
/**
 * Customizer Partial Refresh.
 *
 * @link http://www.boldgrid.com
 *
 * @since 2.7.0
 *
 * @package Boldgrid_Theme_Framework_Customizer
 */


/**
 * Class: Boldgrid_Framework_Customizer_Partial_Refresh
 *
 * Renders various parts of pages for partial refresh in customizer preview.
 *
 * @since      2.7.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */
class Boldgrid_Framework_Customizer_Partial_Refresh {
	/**
	 * BGTFW Configs.
	 *
	 * @var array
	 */
	public $configs = array();

	/**
	 * Class Constructor.
	 *
	 * @since 2.7.0
	 *
	 * @param array $configs BGTFW Configs Array.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}


	/**
	 * Header.
	 *
	 * Handles header partial refreshes.
	 *
	 * @since 2.7.0
	 */
	public function header() {
		$has_header_template = apply_filters( 'crio_premium_get_page_header', get_the_ID() );
		$has_header_template = get_the_ID() === $has_header_template ? false : $has_header_template;
		$template_has_title  = get_post_meta( $has_header_template, 'crio-premium-template-has-page-title', true );
		$preset              = get_theme_mod( 'bgtfw_header_preset' );

		if ( $has_header_template ) {
			// Invoking core hook for plugins to hook into header.
			do_action( 'get_header' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			do_action( 'crio_premium_remove_redirect' );
		} else {
			get_template_part( 'templates/header/header', $this->configs['template']['header'] );
		}
	}
}
