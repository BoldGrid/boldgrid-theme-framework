<?php
/**
 * Class: BoldGrid_Framework_Styles
 *
 * This contains the CSS styles that a theme will enqueue.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Styles
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: BoldGrid_Framework_Styles
 *
 * This contains the CSS styles that a theme will enqueue to the
 * front end of the site.
 *
 * @since      1.0.0
 */
class BoldGrid_Framework_Styles {

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
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     1.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Get the google fonts url
	 *
	 * @since     1.0.0
	 */
	public function get_fonts_url() {
		return BoldGrid::add_fonts(
			$this->configs['font']['types'],
			$this->configs['font']['translators']
		);
	}

	/**
	 * Return a list of the editor styles that will be applied that are actually contained
	 * with the theme
	 *
	 * @return array
	 * @since 1.0.3
	 */
	public function get_local_editor_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		return array(
			'style.css',
			'editor-style.css',
			$this->configs['framework']['css_dir'] . 'bootstrap/bootstrap.min.css',
			$this->configs['framework']['css_dir'] . 'boldgrid-theme-framework' . $suffix . '.css',
			$this->configs['framework']['css_dir'] . 'font-awesome/font-awesome' . $suffix . '.css',
			Boldgrid_Framework_Customizer_Colors::get_colors_uri( $this->configs ),
		);
	}

	/**
	 * Enqueue the styles for our BoldGrid Theme.
	 *
	 * @since     1.0.0
	 */
	public function boldgrid_enqueue_styles() {
		$configs = $this->configs;
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		/* Load Custom Google Fonts */
		wp_enqueue_style(
			'boldgrid-google-fonts',
			$this->get_fonts_url(),
			array(),
			null
		);

		/* Font Awesome */
		wp_enqueue_style(
			'font-awesome',
			$this->configs['framework']['css_dir'] . 'font-awesome/font-awesome' . $suffix . '.css',
			array(),
			'4.5.0'
		);

		/* Underscores */
		wp_enqueue_style(
			'underscores-styles',
			$this->configs['framework']['css_dir'] . 'underscores/underscores' . $suffix . '.css',
			array(),
			'4.5.0'
		);

		/* Bootstrap */
		wp_enqueue_style(
			'bootstrap-styles',
			$this->configs['framework']['css_dir'] . 'bootstrap/bootstrap.min.css',
			array(),
			'3.3.1'
		);

		/* Framework Base Styles */
		wp_enqueue_style(
			'boldgrid-theme-framework',
			$this->configs['framework']['css_dir'] . 'boldgrid-theme-framework' . $suffix . '.css',
			array(),
			$this->configs['version']
		);

		/* If using a child theme, auto-load the parent theme style. */
		if ( is_child_theme( ) ) {
			wp_enqueue_style(
				'parent-style',
				trailingslashit( get_template_directory_uri() ) . 'style.css',
				array(
					'bootstrap-styles',
					'font-awesome',
				),
				null
			);
		}

		// Add animate.css for animation effects if a theme requests it.
		if ( true === $this->configs['scripts']['animate-css'] ) {
			wp_enqueue_style(
				'boldgrid-animate-css',
				$this->configs['framework']['css_dir'] . 'animate-css/animate' . $suffix . '.css',
				array(),
				$this->configs['version']
			);
		}

		// Add offcanvas styles.
		if ( true === $this->configs['scripts']['offcanvas-menu'] ) {
			wp_enqueue_style(
				'boldgrid-offcanvas-css',
				$this->configs['framework']['css_dir'] . 'offcanvas' . $suffix . '.css',
				array(),
				$this->configs['version']
			);
		}

		/* Always load active theme's style.css. */
		wp_enqueue_style(
			'style',
			get_stylesheet_uri(),
			array(
				'bootstrap-styles',
				'font-awesome',
				'boldgrid-google-fonts',
			),
			null
		);
	}

	/**
	 * Given an array of css rules creates css string
	 *
	 * @since     1.0.0
	 *
	 * @param array  $css_rules Array of CSS rules to apply.
	 * @param string $id ID to give to style rule.
	 * @return    string CSS to apply.
	 */
	public static function convert_array_to_css( $css_rules, $id ) {
		// Convert array to css.
		$css = '';
		foreach ( $css_rules as $rule => $definitions ) {

			$def = '';
			foreach ( $definitions as $prop => $definition ) {
				$def .= $prop . ':' . $definition . ';';
			}

			$css .= sprintf( '%s { %s }', $rule, $def );
		}

		return "<style id='{$id}' type='text/css'>{$css}</style>";
	}

	/**
	 * Add styles to the TinyMCE Editor to make it more WYSIWYG.
	 *
	 * @since     1.0.0
	 */
	public function add_editor_styling() {
		$local_files = $this->get_local_editor_styles();
		$local_files[] = $this->get_fonts_url();

		apply_filters( 'boldgrid_theme_framework_editor_styles', $local_files );
		add_editor_style( $local_files );
	}

	/**
	 * Add query string cache busting for color palette.css
	 * Uses the file last mod time for arg
	 *
	 * @since     1.0.3
	 *
	 * @param string $css CSS file to add cache bust string to.
	 */
	public function add_cache_busting( $css ) {
		$color_palette_css_name = $this->configs['customizer-options']['colors']['settings']['output_css_name'];
		$color_palette_css_path = get_stylesheet_directory() . DIRECTORY_SEPARATOR . $color_palette_css_name;

		if ( empty( $css ) || ! $color_palette_css_name || ! file_exists( $color_palette_css_path ) ) {
			return $css;
		}

		$styles = explode( ',',  $css );

		$mce_css = array();
		foreach ( $styles as $style ) {

			if ( false !== strpos( $style, $color_palette_css_name ) ) {

				$added_query_arg = add_query_arg( 'framework-time', filemtime( $color_palette_css_path ), $style );
				if ( $added_query_arg ) {
					$style = $added_query_arg;
				}
			}

			$mce_css[] = $style;
		}

		return implode( ',', $mce_css );
	}
}
