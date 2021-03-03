<?php
/**
 * Customizer Partial Refresh.
 *
 * @link http://www.boldgrid.com
 *
 * @since SINCEVERSION
 *
 * @package Boldgrid_Theme_Framework_Customizer
 */


/**
 * Class: Boldgrid_Framework_Customizer_Partial_Refresh
 *
 * Renders various parts of pages for partial refresh in customizer preview.
 *
 * @since      SINCEVERSION
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
	 * @since SINCEVERSION
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
	 * @since SINCEVERSION
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

	/**
	 * Header.
	 *
	 * Handles header partial refreshes.
	 *
	 * @since SINCEVERSION
	 */
	public function fixed_header() {
		$preset              = get_theme_mod( 'bgtfw_fixed_header_preset' );

		$fixed_header_markup = BoldGrid::dynamic_sticky_header( $preset );
		return $fixed_header_markup;
	}
}
