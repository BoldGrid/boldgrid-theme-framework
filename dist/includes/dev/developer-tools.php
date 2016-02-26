<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Boldgrid_Seo
 * @subpackage Boldgrid_Seo/includes
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

class BoldGrid_Theme_Developer_Tools {

	public function boldgrid_tools( $wp_admin_bar ) {

		$this->transient_flusher( $wp_admin_bar );

	}

	/*** Clear all transients with one click ***/

	private function transient_flusher( $wp_admin_bar ) {

		if( ! is_admin(  ) || ! current_user_can( 'manage_options' ) )

			return;

		global $wpdb;

		if( isset( $_GET['clear-transients'] ) && 1 == $_GET['clear-transients'] ) {

			$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_%') OR `option_name` LIKE ('_transient_timeout_%')" );

		}

		$count = $wpdb->query( "SELECT `option_name` FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_%')" );

		$args = array(

			'id' => 'clear-transients',
			'title' => 'Clear Transients (' . $count . ')',
			'parent' => 'site-name',
			'href' => get_admin_url(  ) . '?clear-transients=1'

		);

		$wp_admin_bar->add_node( $args );

	}

}