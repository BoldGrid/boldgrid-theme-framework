<?php
/**
 * Boldgrid_Framework_Customizer_Background
 *
 * This adds the background functionality in the WordPress
 * customizer for a BoldGrid theme.
 *
 * Theme Mods Added :
 * - boldgrid_background_type
 * - boldgrid_background_vertical_position
 * - boldgrid_background_horizontal_position
 * - boldgrid_background_image_size
 * - boldgrid_background_pattern
 *
 * @since       1.0.0
 * @category    Customizer
 * @package     Boldgrid_Framework_Customizer
 * @subpackage  Boldgrid_Framework_Customizer_Background
 * @author      BoldGrid <support@boldgrid.com>
 * @link        https://boldgrid.com
 */

/**
 * Boldgrid_Framework_Customizer_Background Class.
 *
 * Class responsible for the background controls in customizer.
 *
 * @since 1.0.0
 */
class Boldgrid_Framework_Customizer_Background {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Value of the sanitized background attachment field.
	 *
	 * @since     1.3.1
	 * @access    protected
	 * @var       string     $sanitized_attachment_value      Value of the sanitized background attachment field.
	 */
	protected $sanitized_attachment_value;

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
	 * Get all of the patterns from the image directory
	 *
	 * Patterns by Subtle Patterns
	 *
	 * @link http://subtlepatterns.com/
	 *
	 * Transparent Versions of the patterns by Transparent Textures
	 * @link http://www.transparenttextures.com/
	 *
	 * @return    array
	 * @since     1.0.0
	 */
	public function get_pattern_files() {
		// Get patterns, sslverify is false for local env and self-signed SSLs on temp domains.
		$request = wp_remote_get( $this->configs['framework']['admin_asset_dir'] . 'json/patterns.json', [ 'sslverify' => false ] );

		// Check for errors with $request.
		if ( is_wp_error( $request ) ) {
			return $request;
		}

		$contents = json_decode( wp_remote_retrieve_body( $request ) );

		$patterns = [];
		foreach ( $contents->patterns as $pattern ) {
			$patterns[ $pattern->id ] = $pattern->formattedName; // @codingStandardsIgnoreLine
		}

		return $patterns;
	}

	/**
	 * Validate the attachment value. Set class prop to be used on later filter.
	 *
	 * @param string $value Value of background attachment field.
	 *
	 * @since 1.3.1
	 */
	public function pre_sanitize_attachment( $value ) {
		if ( in_array( $value, array( 'scroll', 'fixed', 'parallax' ) ) ) {
			$this->sanitized_attachment_value = $value;
		}
	}

	/**
	 * Overwrite the santization callback result, if we have already validated the value.
	 *
	 * @param string $result Result of the santize callback.
	 *
	 * @since 1.3.1
	 */
	public function post_sanitize_attachment( $result ) {
		if ( ! empty( $this->sanitized_attachment_value ) ) {
			$result = $this->sanitized_attachment_value;
		}
		return $result;
	}

	/**
	 * Add Boldgrid background attachment.
	 *
	 * This relicates the control as we dislayed it pre-4.7. Only loads on later versions.
	 *
	 * @param array $wp_customize WP_Customize object.
	 *
	 * @since 1.3.1
	 */
	public function boldgrid_background_attachment( $wp_customize ) {
		$wp_customize->remove_control( 'background_size' );
		$wp_customize->remove_control( 'background_position' );
		$wp_customize->remove_control( 'background_preset' );
		$wp_customize->remove_control( 'background_attachment' );

		$wp_customize->add_control(
			'boldgrid_background_attachment',
			array(
				'label' => __( 'Background Effects', 'bgtfw' ),
				'section' => 'background_image',
				'settings' => 'background_attachment',
				'priority' => 14,
				'type' => 'radio',
				'choices' => array(
					'parallax' => __( 'Parallax', 'bgtfw' ),
					'scroll' => __( 'Scroll', 'bgtfw' ),
					'fixed' => __( 'Fixed', 'bgtfw' ),
				),
			)
		);
	}

	/**
	 * Rearrange general controls and sections in the customizer menu.
	 *
	 * @param array $wp_customize WP_Customize object.
	 * @since 1.0.0
	 */
	public function rearrange_menu( $wp_customize ) {
		$bg_attachment = $wp_customize->get_control( 'background_attachment' );
		$bg_attachment->label = __( 'Background Effects', 'bgtfw' );
		$bg_attachment->choices = $bg_attachment->choices + [ 'parallax' => 'Parallax' ];
		$bg_attachment->priority = 14;

		$wp_customize->get_control( 'boldgrid_background_image_size' )->priority = 15;
		$wp_customize->get_control( 'background_repeat' )->priority = 18;
		$wp_customize->get_section( 'background_image' )->title = __( 'Background', 'bgtfw' );
		$wp_customize->remove_control( 'background_color' );

		return $wp_customize;
	}

	/**
	 * Format an image string to a css property.
	 *
	 * @param array $configs BoldGrid Theme Framework config.
	 * @since 1.0.4
	 * @return string Example: "url(https://domain.com/wp-content/themes/boldgrid-theme/inc/boldgrid-theme-framework/assets/img/patterns/60-lines.png)"
	 */
	public static function get_default_pattern_mod( $configs ) {
		$default = 'none';

		if ( ! empty( $configs['customizer-options']['background']['defaults']['boldgrid_background_pattern'] ) ) {
			$default = 'url(' . $configs['framework']['admin_asset_dir'] . 'img/patterns/' . $configs['customizer-options']['background']['defaults']['boldgrid_background_pattern'] . ')';
		}

		return $default;
	}

	/**
	 * Add controls to handle the background pattern
	 *
	 * @since 1.0.0
	 * @param array $wp_customize WordPress Customizer Object.
	 */
	public function add_patterns( $wp_customize ) {
		require_once $this->configs['framework']['includes_dir'] . 'control/class-boldgrid-framework-control-pattern.php';

		$wp_customize->add_setting(
			'boldgrid_background_pattern',
			array(
				'default' => self::get_default_pattern_mod( $this->configs ),
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport' => 'postMessage',
				'sanitize_callback' => function( $value ) {
					if ( empty( $value ) || ! is_string( $value ) ) {
						return '';
					}

					preg_match( '/url\(\"(.+)\"\)/', $value, $matches );

					if ( ! empty( $matches ) ) {
						return 'url("' . $matches[1] . '")';
					} else {
						return '';
					}
				},
			)
		);

		$patterns = $this->get_pattern_files();

		$wp_customize->add_control(
			new Boldgrid_Framework_Control_Pattern(
				$wp_customize,
				'boldgrid_background_pattern',
				[
					'label' => __( 'Pattern', 'bgtfw' ),
					'section' => 'background_image',
					'settings' => 'boldgrid_background_pattern',
					'priority' => 3,
					'choices' => $patterns,
				]
			)
		);
	}

	/**
	 * Add controls to hand the background pattern
	 *
	 * @since 1.0.0
	 */
	public function create_background_styles() {
		$theme_mods = get_theme_mods();
		$background_options = $this->configs['customizer-options']['background'];

		$bg_image = get_theme_mod( 'background_image', $background_options['defaults']['background_image'] );
		$bg_type = get_theme_mod( 'boldgrid_background_type' );
		$bg_pattern = ! empty( $theme_mods['boldgrid_background_pattern'] ) ? $theme_mods['boldgrid_background_pattern'] : 'none';
		$bg_size = get_theme_mod( 'boldgrid_background_image_size' );
		$bg_attach = get_theme_mod( 'background_attachment', $background_options['defaults']['background_attachment'] );

		/** Passing the defaults to the process that creates the css */
		if ( 'none' === $bg_pattern ) {
			if ( ! empty( $background_options['defaults']['boldgrid_background_pattern'] ) ) {
				$bg_pattern = self::get_default_pattern_mod( $this->configs );
			}
		}

		$css_rules = array();

		if ( 'pattern' === $bg_type ) {
			$css_rules = array(
				'body.custom-background' => array(
					'background-image' => $bg_pattern,
					'background-size' => 'auto',
					'background-repeat' => 'repeat',
					'background-attachment' => 'scroll',
				),
			);
		} else {

			if ( $bg_image ) {
				$css_rules['body.custom-background']['background-image'] = $this->create_overlay_css( $bg_image );
			}

			if ( $bg_size ) {
				$css_rules['body.custom-background']['background-size'] = esc_attr( $bg_size );
			}
		}

		if ( ! empty( $css_rules ) ) {
			$custom_background = function ( $array ) {
				$array[] = 'custom-background';
				return $array;
			};

			add_filter( 'body_class', $custom_background );
		}

		return $css_rules;
	}

	/**
	 * Create a CSS rule for background image.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $image Image URL value from theme mod or config.
	 * @return string        A CSS rule for the background image.
	 */
	public function create_overlay_css( $image ) {
		$controls = $this->configs['customizer']['controls'];

		// Get the related theme mods.
		$enabled = get_theme_mod( 'bgtfw_background_overlay',
			$controls['bgtfw_background_overlay']['default'] );
		$color = get_theme_mod( 'bgtfw_background_overlay_color',
			$controls['bgtfw_background_overlay_color']['default'] );
		$alpha = get_theme_mod( 'bgtfw_background_overlay_alpha',
			$controls['bgtfw_background_overlay_alpha']['default'] );

		$rule = '';
		if ( $enabled && $color && $alpha ) {

			// Create an rgba given palette color and alpha.
			$color = explode( ':', $color );
			$color = array_pop( $color );
			$color_obj = ariColor::newColor( $color );
			$color_obj->alpha = $alpha;
			$color = esc_attr( $color_obj->toCSS( 'rgba' ) );

			$rule = 'linear-gradient(to right, ' . $color . ', ' . $color .
				' ), url("' . esc_attr( $image ) . '")';
		}

		return $rule;
	}

	/**
	 * Append BG styles to Head rules
	 *
	 * @param     array $cur_rules Current rules.
	 * @return    array    $css_rules    Merged rules.
	 * @since     1.0.0
	 */
	public function add_head_styles_filter( $cur_rules ) {
		$css_rules  = $this->create_background_styles();
		return array_merge( $cur_rules, $css_rules );
	}

	/**
	 * Add editor styles.
	 *
	 * @since  2.0.0
	 *
	 * @param  array $css CSS to add to editor.
	 *
	 * @return array $css Modified CSS to add to editor.
	 */
	public function add_editor_styles( $css ) {
		$pattern = get_theme_mod( 'boldgrid_background_pattern' );
		$styles = array();

		if ( 'pattern' === get_theme_mod( 'boldgrid_background_type' ) && ! empty( $pattern ) ) {
			$styles = $this->create_background_styles();
		}

		// Convert array to css.
		foreach ( $styles as $rule => $definitions ) {
			$def = '';
			foreach ( $definitions as $prop => $definition ) {
				$def .= $prop . ':' . $definition . ';';
			}

			$css .= sprintf( '%s { %s }', $rule, $def );
		}

		return $css;
	}

	/**
	 * Replace the core background image control with one that supports cropping.
	 *
	 * Functionality From: https://wordpress.org/plugins/background-image-cropper/
	 *
	 * @param     WP_Customize_Manager $wp_customize    Customizer manager object.
	 * @since     1.0.0
	 */
	public function add_background_crop( $wp_customize ) {
		// Include class for footer customization.
		require_once( $this->configs['framework']['includes_dir'] . 'control/class-boldgrid-framework-control-background-crop.php' );

		wp_register_script( 'boldgrid-background-image-cropper',
			$this->configs['framework']['js_dir'] . 'customizer/background-crop.js',
			array( 'jquery', 'customize-controls' )
		);

		$wp_customize->register_control_type( 'Boldgrid_Framework_Background_Crop' );

		$wp_customize->remove_control( 'background_image' );

		$wp_customize->add_control(
			new Boldgrid_Framework_Background_Crop(
				$wp_customize,
				'background_image',
				array(
					'section'     => 'background_image',
					'label'       => __( 'Background Image', 'bgtfw' ),
					'priority'    => 9,
					'flex_width'  => true,
					'flex_height' => true,
					'width'       => $this->configs['customizer-options']['background']['defaults']['recommended_image_width'],
					'height'      => $this->configs['customizer-options']['background']['defaults']['recommended_image_height'],
				)
			)
		);
	}
}
