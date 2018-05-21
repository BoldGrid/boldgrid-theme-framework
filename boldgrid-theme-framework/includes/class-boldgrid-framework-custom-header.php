<?php
/**
 * Class: BoldGrid_Framework_Custom_Header
 *
 * The class responsible for comment display.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Custom header implementation
 *
 * @link https://codex.wordpress.org/Custom_Headers
 * @since 2.0.0
 */
class Boldgrid_Framework_Custom_Header {

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
	 * Set up the WordPress core custom header feature.
	 *
	 * @uses header_style()
	 */
	public function custom_header_setup() {

		/**
		 * Filter BoldGrid Theme Framework custom-header support arguments.
		 *
		 * @since 2.0.0
		 *
		 * @param array $args {
		 *     An array of custom-header support arguments.
		 *
		 *     @type string $default-image     		Default image of the header.
		 *     @type string $default_text_color     Default color of the header text.
		 *     @type int    $width                  Width in pixels of the custom header image. Default 954.
		 *     @type int    $height                 Height in pixels of the custom header image. Default 1300.
		 *     @type string $wp-head-callback       Callback function used to styles the header image and text
		 *                                          displayed on the blog.
		 *     @type string $flex-height     		Flex support for height of header.
		 * }
		 */
		add_theme_support(
			'custom-header',
			apply_filters(
				'bgtfw_custom_header_args',
				array(
					'default-image'      => '',
					'width'              => 2000,
					'height'             => 1200,
					'flex-height'        => true,
					'video'              => true,
					'wp-head-callback'   => array( $this, 'header_style' ),
				)
			)
		);

		// Check that the default-image has been passed for custom-header before registering default headers.
		$default_image = get_theme_support( 'custom-header', 'default-image' );

		// If it's not a string 'remove-header', then register the default header image based on main image.
		if ( 'remove-header' !== $default_image && ! empty( $default_image ) ) {

			// Get the relative path passed for url and thumbnail_url to use.
			$default_image = str_replace( get_template_directory_uri(), '', $default_image );

			register_default_headers(
				array(
					'default-image' => array(
						'url'           => '%s' . $default_image,
						'thumbnail_url' => '%s' . $default_image,
						'description'   => __( 'Default Header Image', 'bgtfw' ),
					),
				)
			);
		} else {
			register_default_headers( array() );
		}
	}

	/**
	 * Add colors color classes to header items.
	 *
	 * @since 2.0.0
	 *
	 * @param array $configs BGTFW Configs.
	 */
	public function add_color_classes( $configs ) {
		$title_color = get_theme_mod( 'bgtfw_site_title_color',
			$configs['customizer']['controls']['bgtfw_site_title_color']['default'] );

		if ( $title_color ) {
			$color = BoldGrid::get_color_classes( $title_color, array( 'color' ) );
			$configs['template']['site-title-classes'] .= ' ' . implode( ' ', $color );
		}

		$tagline_color = get_theme_mod( 'bgtfw_tagline_color',
			$configs['customizer']['controls']['bgtfw_tagline_color']['default'] );

		if ( $tagline_color ) {
			$color = BoldGrid::get_color_classes( $tagline_color, array( 'color' ) );
			$configs['template']['tagline-classes'] .= ' ' . implode( ' ', $color );
		}

		return $configs;
	}

	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see custom_header_setup().
	 */
	public function header_style() {
		$header_text_color = get_header_textcolor();

		// If no custom options for text are set, let's bail.
		// get_header_textcolor() options: add_theme_support( 'custom-header' ) is default, hide text (returns 'blank') or any hex value.
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style id="bgtfw-custom-header-styles" type="text/css">
		<?php
			// Has the text been hidden?
			if ( 'blank' === $header_text_color ) :
		?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}
		<?php
			// If the user has set a custom color for the text use that.
			else :
		?>
			.site-title a,
			.colors-dark .site-title a,
			.colors-custom .site-title a,
			body.has-header-image .site-title a,
			body.has-header-video .site-title a,
			body.has-header-image.colors-dark .site-title a,
			body.has-header-video.colors-dark .site-title a,
			body.has-header-image.colors-custom .site-title a,
			body.has-header-video.colors-custom .site-title a,
			.site-description,
			.colors-dark .site-description,
			.colors-custom .site-description,
			body.has-header-image .site-description,
			body.has-header-video .site-description,
			body.has-header-image.colors-dark .site-description,
			body.has-header-video.colors-dark .site-description,
			body.has-header-image.colors-custom .site-description,
			body.has-header-video.colors-custom .site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}

	/**
	 * Customize video play/pause button in the custom header.
	 *
	 * @param array $settings Video settings.
	 * @return array The filtered video settings.
	 */
	public function video_controls( $settings ) {
		$settings['l10n']['play'] = '<span class="screen-reader-text">' . __( 'Play background video', 'bgtfw' ) . '</span>';
		$settings['l10n']['pause'] = '<span class="screen-reader-text">' . __( 'Pause background video', 'bgtfw' ) . '</span>';
		return $settings;
	}
}
