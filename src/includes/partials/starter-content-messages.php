<?php
/**
 * This file contains the containers needed to handle errors when installing starter content.
 *
 * It is included by both the "Welcome" and "Starter Content" pages, in the appropriate location where
 * errors should be displayed.
 *
 * @package Boldgrid_Theme_Framework
 * @since 2.0.0
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="starter-content-messages hidden">
	<p>
		<strong><?php esc_html_e( 'Unable to install Starter Content.', 'bgtfw' ); ?></strong>
	</p>
	<div class="starter-content-error">
		<div class="notice notice-error inline">
			<?php esc_html_e( 'An unknown error occurred when trying to install this Starter Content\'s required plugins.', 'bgtfw' ); ?>
		</div>
	</div>
</div>