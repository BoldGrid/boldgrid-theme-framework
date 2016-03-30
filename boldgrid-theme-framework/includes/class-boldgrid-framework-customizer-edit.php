<?php

/**
 * Functionality used within Customizer.
 *
 * @since 1.1
 * @link http://www.boldgrid.com.
 * @package Boldgrid_Inspiration.
 * @subpackage Boldgrid_Inspiration/includes.
 * @author BoldGrid <wpb@boldgrid.com>.
 */
class Boldgrid_Framework_Customizer_Edit {
	/**
	 * Add hooks.
	 *
	 * @since 1.1
	 */
	public function add_hooks() {
		add_action( 'wp_enqueue_scripts', array (
			$this,
			'wp_enqueue_scripts'
		) );

		add_action( 'wp_footer', array (
			$this,
			'wp_footer'
		) );
	}

	/**
	 * Add edit buttons to the customizer.
	 *
	 * @since 1.1
	 */
	public function wp_enqueue_scripts( $hook ) {
		if ( is_customize_preview() ) {

			wp_register_script( 'boldgrid-framework-customizer-edit-js',
				'/wp-content/themes/boldgrid-pavilion/inc/boldgrid-theme-framework/assets/js/customizer/edit.js' );
			$translation_array = array (
				'editPostLink' => get_edit_post_link( get_the_ID() )
			);
			wp_localize_script( 'boldgrid-framework-customizer-edit-js',
				'boldgridFrameworkCustomizerEdit', $translation_array );
			wp_enqueue_script( 'boldgrid-framework-customizer-edit-js' );

			wp_register_style( 'boldgrid-theme-framework--customizer-edit-css',
				'/wp-content/themes/boldgrid-pavilion/inc/boldgrid-theme-framework/assets/css/customizer/edit.css',
				array (), BOLDGRID_INSPIRATIONS_VERSION );
			wp_enqueue_style( 'boldgrid-theme-framework--customizer-edit-css' );

			wp_enqueue_style( 'dashicons' );

			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_script( 'jquery-ui-dialog' );
		}
	}

	/**
	 */
	public function wp_footer() {
		if ( is_customize_preview() ) {
			include 'partials/customizer-edit.php';
		}
	}
}

$customizer_edit = new Boldgrid_Framework_Customizer_Edit();
$customizer_edit->add_hooks();