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
 * Boldgrid Framework Upgrade Class
 *
 * Responsible for performing any upgrade methods that
 * are version specific needs.
 *
 * @since 1.3.1
 */
class Boldgrid_Framework_Version_Requirements {

	public function add_hooks() {
		add_action( 'after_switch_theme', array( $this, 'deactivate' ), 0, 2 );
	}

	public function deactivate( $theme_name, $wp_theme ) {
		add_action( 'admin_notices', array( $this, 'show_notice' ) );
		switch_theme( $wp_theme->stylesheet );
		return false;
	}

	public function show_notice() {
		?>
		<div class="update-nag">
			<p>
				<?php _e( 'You need to update your PHP version to use this theme!', 'bgtfw' ); ?>
			</p>
			<p>
				<?php _e( 'Your current version is:', 'bgtfw' ) ?> <strong><?php echo phpversion(); ?></strong>, <?php _e( 'and this theme requires 5.4.0', 'bgtfw' ) ?>
			</p>
		</div>
		<?php
	}
}
