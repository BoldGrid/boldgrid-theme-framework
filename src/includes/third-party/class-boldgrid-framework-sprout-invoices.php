<?php
/**
 * File: class-boldgrid-framework-sprout-invoices.php
 *
 * This class is responsible for adding Sprout Invoices support to the BoldGrid Framework.
 *
 * @package Boldgrid_Framework
 *
 * @since 2.17.2
 */

/**
 * Class: Boldgrid_Framework_Sprout_Invoices
 *
 * This class is responsible for adding Sprout Invoices support to the BoldGrid Framework.
 *
 * @since 2.17.2
 */
class Boldgrid_Framework_Sprout_Invoices {
	/**
	 * Add hooks.
	 *
	 * @since 2.17.2
	 */
	public static function add_hooks() {
		add_filter( 'bgtfw_wrapper_templates', array( __CLASS__, 'bgtfw_wrapper_templates' ), 10, 3 );
	}

	/**
	 * BGTFW Wrapper Templates.
	 *
	 * Adjusts the templates used when various sprout invoices pages are loaded.
	 *
	 * @since 2.17.2
	 */
	public static function bgtfw_wrapper_templates( $templates, $base, $main_template ) {

		if ( self::is_si_pdf( $base, $main_template ) ) {
			$templates = array( 'si-pdf-base.php' );
		} elseif ( self::is_si_invoice() ) {
			$templates = array( 'si-invoice-base.php' );
		} elseif ( self::is_si_estimate() ) {
			$templates = array( 'si-estimate-base.php' );
		}

		return $templates;
	}

	/**
	 * Is this page an SI invoice?
	 *
	 * Determines if a page is a Sprout Invoices invoice
	 * based on the SI_Invoice::is_invoice_query() method.
	 *
	 * @since 2.17.2
	 *
	 * @return boolean
	 */
	public static function is_si_invoice() {
		if ( class_exists( 'SI_Invoice' ) ) {
			return SI_Invoice::is_invoice_query();
		} else {
			return false;
		}
	}

	/**
	 * Is this page an SI estimate?
	 *
	 * @since 2.17.2
	 *
	 * @return boolean
	 */
	public static function is_si_estimate() {
		global $post;

		if ( ! empty( $post ) && class_exists( 'SI_Invoice' ) ) {
			return 'sa_estimate' === $post->post_type;
		} else {
			return false;
		}
	}

	/**
	 * Is this page an SI PDF?
	 *
	 * @since 2.17.2
	 *
	 * @return boolean
	 */
	public static function is_si_pdf( $base, $main_template ) {
		global $post;

		// If Sprout is not active, we won't be viewing a sprout pdf.
		if ( ! class_exists( 'SI_Invoice' ) ) {
			return false;
		}

		// When viewing the sprout invoice, these two variables are always set.
		if ( ! $base || ! isset( $main_template ) ) {
			return false;
		}

		// If template doesn't contain 'sprout-invoices' in it's path, it's not a sprout pdf.
		if ( false === strpos( $main_template, 'sprout-invoices' ) ) {
			return false;
		}

		// At this point, we know its a sprout page. If it's a pdf redirected page, we know it's a pdf.
		if ( isset( $_SERVER['REDIRECT_QUERY_STRING'] ) && 'pdf=1' === $_SERVER['REDIRECT_QUERY_STRING'] ) {
			return true;
		}

		// If the base name contians '-pdf', then it's a sprout pdf.
		if ( false !== strpos( $base, '-pdf' ) ) {
			return true;
		}

		return false;
	}
}
