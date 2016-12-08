<?php
/**
 * Class: Boldgrid_Framework_Customizer_Cta
 *
 * This is used load the CTA Widget.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage BoldGrid_Framework_Device_Preview
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * BoldGrid_Framework_Device_Preview Class
 *
 * Responsible for the device preview/resize buttons in the
 * customizer.
 *
 * @since 1.0.0
 */
class Boldgrid_Framework_Customizer_Bstw {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

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
	 * Initialize.
	 *
	 * @since 1.3.5
	 */
	public function init() {
		// Load black studio tinymce widget if framework check passes..
		if ( $this->theme_mod() ) $this->load_bstw();
	}

	/**
	 * Checks the theme to see if it should load Black Studio TinyMCE Widget.
	 *
	 * @since 1.3.5
	 *
	 * @return Boolean $load Should theme load BSTW.
	 */
	public function theme_mod() {
		return get_theme_mod( 'bstw_enabled' );
	}

	/**
	 * Loads the Black Studio TinyMCE Widget plugin.
	 *
	 * @since 1.3.5
	 */
	public function load_bstw() {
		require_once $this->configs['framework']['includes_dir'] . 'black-studio-tinymce-widget/black-studio-tinymce-widget.php';
	}

	/**
	 * Check if any BSTW widgets are in the boldgrid_widgets_created option.
	 *
	 * Created widgets by BoldGrid should be stored in this option.  We can use
	 * use this to check if there's possibly some widgets already existing in used
	 * for a current user.
	 *
	 * @since 1.3.5
	 *
	 * @return bool Does boldgrid_widgets_created contain any bstw.
	 */
	public function widgets_created() {
		// Widgets created by BoldGrid are stored in option.
		$widgets = get_option( 'boldgrid_widgets_created' );

		// If we have widgets stored, check for bstw widgets.
		$widgets && $this->bstw_widgets( $widgets );

		return $widgets;
	}

	public function bstw_widgets( $widgets ) {
		array_filter( $widgets, function( $value, $key ) {
			return strpos( $value, 'black-studio-tinymce' ) !== false;
		}, ARRAY_FILTER_USE_BOTH );

		return !! $widgets;
	}
	/**
	 * Check to see if theme has any BSTW stored in sidebars.
	 *
	 * @since 1.3.5
	 *
	 * @return bool Does theme have bstw stored in theme mod.
	 */
	public function sidebars_widgets() {
		$widgets = get_theme_mod( 'sidebars_widgets' );
		$bstw = false;
		if ( $widgets ) {
			foreach ( $widgets['data'] as $data ) {
				if ( $data === 'wp_inactive_widgets' ) continue;
				foreach ( $data as $key => $value ) {
					if ( strpos( $value, 'black-studio-tinymce' ) !== false ) {
						$bstw = true;
						break;
					}
				}
				// Exit loop if we found any bstw widgets stored.
				if ( $bstw ) break;
			}
		}
		return $bstw;
	}
}
