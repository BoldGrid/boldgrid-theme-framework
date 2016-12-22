<?php
/**
 * Class: Boldgrid_Framework_Customizer_Bstw
 *
 * This is used load the CTA Widget.
 *
 * @since      1.3.6
 * @package    Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework_Customizer
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Boldgrid_Framework_Customizer_Bstw
 *
 * Responsible for the bstw tinymce widgets appearing on site.
 *
 * @since 1.3.6
 */
class Boldgrid_Framework_Customizer_Bstw {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since 1.3.6
	 * @access protected
	 * @var string $configs The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.3.6
	 *
	 * @access public
	 *
	 * @param string $configs The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Initialize.
	 *
	 * @since 1.3.6
	 *
	 * @access public
	 */
	public function init() {
		// Load black studio tinymce widget if framework check passes..
		if ( $this->theme_mod() && ! $this->sidebars_widgets() ) {
			$this->load_bstw();
		}
	}

	/**
	 * Check if the Call To Action is disabled by the theme.
	 *
	 * This is checked because not all themes have a CTA, so
	 * we can enabled the Contact Blocks and disable BSTW by default.
	 * The default in the theme framework for the CTA is 'none' already.
	 *
	 * @since 1.3.6
	 *
	 * @access public
	 *
	 * @return bool Is the call to action disabled or not.
	 */
	public function is_cta_disabled( $configs ) {
		return 'none' === $configs['template']['call-to-action'] ? true : false;
	}

	/**
	 * Checks the theme to see if it should load Black Studio TinyMCE Widget.
	 *
	 * @since 1.3.6
	 *
	 * @access public
	 *
	 * @return Boolean $load Should theme load BSTW.
	 */
	public function theme_mod() {
		return get_theme_mod( 'bstw_enabled' );
	}

	/**
	 * Check to see if theme has any BSTW stored in sidebars.
	 *
	 * @since 1.3.6
	 *
	 * @access public
	 *
	 * @return bool Does theme have bstw stored in theme mod.
	 */
	public function sidebars_widgets() {
		$widgets = get_theme_mod( 'sidebars_widgets' );
		$bstw = false;
		if ( $widgets ) {
			foreach ( $widgets['data'] as $data ) {
				if ( 'wp_inactive_widgets' === $data ) {
					continue;
				}
				foreach ( $data as $key => $value ) {
					if ( strpos( $value, 'black-studio-tinymce' ) !== false ) {
						$bstw = true;
						break 2;
					}
				}
			}
		}

		return $bstw;
	}

	/**
	 * Loads the Black Studio TinyMCE Widget plugin.
	 *
	 * @since 1.3.6
	 *
	 * @access public
	 */
	public function load_bstw() {
		require_once $this->configs['framework']['includes_dir'] . 'black-studio-tinymce-widget/black-studio-tinymce-widget.php';
	}
}
