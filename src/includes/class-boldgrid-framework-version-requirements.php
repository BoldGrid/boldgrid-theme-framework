<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Framework
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrod.com>
 */

/**
 * Boldgrid Framework Version Requirements
 *
 * Responsible for loading framework if PHP version passes.
 *
 * @since 2.1.1
 */
class Boldgrid_Framework_Version_Requirements {

	/**
	 * Add Hooks
	 *
	 * @since 2.1.1
	 */
	public function add_hooks() {
		add_action( 'after_switch_theme', array( $this, 'deactivate' ), 0, 2 );
	}

	/**
	 * Deactivate
	 *
	 * Handles theme deactivation.
	 *
	 * @since 2.1.1
	 */
	public function deactivate( $theme_name, $wp_theme ) {
		add_action( 'admin_notices', array( $this, 'show_notice' ) );
		switch_theme( $wp_theme->stylesheet );
		return false;
	}

	/**
	 * Show Notice
	 *
	 * Responsible for displaying WP admin notice to user.
	 *
	 * @since 2.1.1
	 */
	public function show_notice() {
		?>
		<div class="update-nag">
			<p>
				<?php esc_html_e( 'You need to update your PHP version to use this theme!', 'bgtfw' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Your current version is:', 'bgtfw' ) ?> <strong><?php echo esc_html( phpversion() ); ?></strong>, <?php esc_html_e( 'and this theme requires 5.6.0', 'bgtfw' ) ?>
			</p>
		</div>
		<?php
	}
}
