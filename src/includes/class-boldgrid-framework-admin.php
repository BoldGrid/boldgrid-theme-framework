<?php
/**
 * Class: Boldgrid_Framework_Admin
 *
 * This class contains methods that affect the admin side.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Admin
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Responsible for Admin specific functionality.
 *
 * @since     1.0.0
 */
class Boldgrid_Framework_Admin {

	/**
	 * The theme's configs.
	 *
	 * @access    private
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 * @since     1.0.0
	 */
	private $configs;

	/**
	 * Getter for configs.
	 *
	 * @return    string     $configs       The BoldGrid Theme Framework configurations.
	 * @since     1.0.0
	 */
	public function get_configs() {
		return $this->configs;
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param      string $configs       The BoldGrid Theme Framework configurations.
	 * @since      1.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * After theme switch compile all SCSS.
	 *
	 * @since      1.0.0
	 */
	public function after_switch_theme() {
		$boldgrid_theme_helper_scss = new Boldgrid_Framework_SCSS( $this->configs );
		$boldgrid_theme_helper_scss->update_css( true );
	}

	/**
	 * Remove the metabox for featured images.
	 *
	 * @since      1.0.0
	 */
	public function remove_thumbnail_box() {
		remove_meta_box( 'postimagediv', 'page', 'side' );
	}

	/**
	 * Adding styles needed for administrative section. Not for customizer styles
	 *
	 * @since      1.0.7
	 */
	public function admin_enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.css' : '.min.css';

		wp_enqueue_style( 'boldgrid-theme-framework-admin',
			$this->configs['framework']['css_dir'] . 'admin' . $suffix,
		array(), $this->configs['version'] );

		$this->enqueue_editor_styles();
	}

	/**
	 * Enqueue Color Palettes file And Buttons file to the WordPress Admin Screen.
	 *
	 * Note: This does not enqueue into the editor, just the admin screen. This is needed for the editor controls.
	 *
	 * @global $pagenow Current page.
	 *
	 * @since 1.3
	 */
	public function enqueue_editor_styles() {
		global $pagenow;

		$valid_pages = array( 'post-new.php', 'post.php' );

		// Only Enqueue on these pages.
		if ( ! in_array( $pagenow, $valid_pages ) ) {
			return;
		}

		$bgtfw_styles = new BoldGrid_Framework_Styles( $this->configs );
		$bgtfw_styles->enqueue_buttons( array( 'editor-css-imhwpb' ) );
		$bgtfw_styles->enqueue_colors( array( 'editor-css-imhwpb' ) );
	}

	/**
	 * Remove hooks.
	 *
	 * Remove custom plugins_api hooks added by BoldGrid private update classes, as they have a
	 * conflict with the tgmpa plugin installer.
	 *
	 * @since 2.0.0
	 */
	public function remove_hooks() {
		if ( ! empty( $_GET['tgmpa-install'] ) ) {
			remove_all_filters( 'plugins_api', 11 );
		}
	}
}
