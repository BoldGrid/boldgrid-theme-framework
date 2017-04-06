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
 * - boldgrid_background_color
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
		$pattern_dir = $this->configs['framework']['asset_dir'] . 'img/patterns/';
		$pattern_dir_uri = $this->configs['framework']['admin_asset_dir'] . 'img/patterns/';
		$patterns = scandir( $pattern_dir );
		$trimmed_patterns = array_diff( $patterns, array(
			'.',
			'..',
		) );

		$pattern_data = array();
		foreach ( $trimmed_patterns as $key => $pattern ) {
			$pattern_data[] = array(
				'uri' => $pattern_dir_uri . $pattern,
				'basename' => $pattern,
			);
		}

		return $pattern_data;
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since     1.0.0
	 */
	public function register_control_scripts() {
		wp_register_script( 'boldgrid-framework-customizer-background',
			$this->configs['framework']['js_dir'] . 'customizer/background-controls.js',
			array( 'jquery', 'jquery-ui-button' ),
		$this->configs['version'], true );
	}

	/**
	 * Add controls to change the background position
	 *
	 * @param     array $wp_customize WP_Customize object.
	 * @since     1.0.0
	 */
	public function add_position( $wp_customize ) {
		/* Custom Background */
		$wp_customize->add_setting( 'boldgrid_background_vertical_position',
			array( 'type' => 'theme_mod' )
		);

		$wp_customize->add_setting( 'boldgrid_background_horizontal_position',
			array( 'type' => 'theme_mod' )
		);

		$wp_customize->remove_control( 'background_position_x' );

		$configs = $this->configs;

		// Add Background Vertical Position Control.
		Kirki::add_field( '', array(
				'type' => 'slider',
				'settings' => 'boldgrid_background_vertical_position',
				'label' => __( 'Vertical Background Position', 'bgtfw' ),
				'section' => 'background_image',
				'transport' => 'postMessage',
				'default' => $configs['customizer-options']['background']['defaults']['boldgrid_background_vertical_position'],
				'priority' => 16,
				'choices' => array(
					'min' => - 100,
					'max' => 100,
					'step' => 1,
				),
			)
		);

		// Add Background Horizontal Position Control.
		Kirki::add_field( '', array(
				'type' => 'slider',
				'settings' => 'boldgrid_background_horizontal_position',
				'label' => __( 'Horizontal Background Position', 'bgtfw' ),
				'section' => 'background_image',
				'transport' => 'postMessage',
				'default' => $configs['customizer-options']['background']['defaults']['boldgrid_background_horizontal_position'],
				'priority' => 17,
				'choices' => array(
					'min' => - 100,
					'max' => 100,
					'step' => 1,
				),
			)
		);

	}

	/**
	 * Add controls to change the background size
	 *
	 * @param     array $wp_customize WP_Customize object.
	 * @since     1.0.0
	 */
	public function add_background_size( $wp_customize ) {
		$wp_customize->add_setting(
			'boldgrid_background_image_size',
			array(
				'default' => $this->configs['customizer-options']['background']['defaults']['boldgrid_background_image_size'],
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport' => 'postMessage',
			)
		);

		// Add controllers for settings.
		$wp_customize->add_control(
			'boldgrid_background_image_size',
			array(
				'label' => __( 'Background Image Size', 'bgtfw' ),
				'section' => 'background_image',
				'settings' => 'boldgrid_background_image_size',
				'priority' => 50,
				'type' => 'radio',
				'choices' => array(
					'cover' => __( 'Cover Page', 'bgtfw' ),
					'contain' => __( 'Scaled to Fit', 'bgtfw' ),
					'100% auto' => __( 'Full Width', 'bgtfw' ),
					'auto 100%' => __( 'Full Height', 'bgtfw' ),
					'inherit' => __( 'Default', 'bgtfw' ),
					'auto' => __( 'Do Not Resize', 'bgtfw' ),
				),
			)
		);
	}

	/**
	 * Add controls to change the background color
	 *
	 * @param     array $wp_customize WP_Customize object.
	 * @since     1.0.0
	 */
	public function add_color_picker( $wp_customize ) {
		$wp_customize->add_setting(
			'boldgrid_background_color',
			array(
				'type' => 'theme_mod',
			)
		);
		$configs = $this->configs;
		// Add Background Color Control to Pattern&Color of Background Image Section.
		Kirki::add_field( '', array(
			'type' => 'color',
			'settings' => 'boldgrid_background_color',
			'label' => __( 'Background Color', 'bgtfw' ),
			'section' => 'background_image',
			'transport' => 'postMessage',
			'default' => $configs['customizer-options']['background']['defaults']['boldgrid_background_color'],
			'priority' => 1,
			'choices' => array(),
			)
		);
	}

	/**
	 * Add scripts to the preview window
	 *
	 * @since     1.0.0
	 */
	public function add_preview_scripts() {
		$this->register_front_end_scripts();
		wp_enqueue_script( 'boldgrid-stellar-parallax' );
	}

	/**
	 * Add Controls to change background type
	 *
	 * @param array $wp_customize WP_Customize object.
	 * @since 1.0.0
	 */
	public function add_background_type( $wp_customize ) {
		add_action( 'wp_head', array( $this, 'add_preview_scripts' ) );

		require_once $this->configs['framework']['includes_dir'] .
			'control/class-boldgrid-framework-control-background-type.php';

		$wp_customize->add_setting(
			'boldgrid_background_type',
			array(
				'default' => $this->configs['customizer-options']['background']['defaults']['boldgrid_background_type'],
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport' => 'postMessage',
			)
		);

			$wp_customize->add_control(
				new Boldgrid_Framework_Control_Background_Type(
					$wp_customize,
					'boldgrid-background-type',
					array(
						'label' => __( 'Background Type', 'bgtfw' ),
						'section' => 'background_image',
						'settings' => 'boldgrid_background_type',
						'priority' => 0,
						'choices' => array(),
					)
				)
			);
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
		// Add Paralax.
		$cur_choices = $wp_customize->get_control( 'background_attachment' )->choices;
		$new_choices = array( 'parallax' => 'Parallax' );
		foreach ( $cur_choices as $key => $cur_choice ) {
			$new_choices[ $key ] = $cur_choice;
		}

		// Rearrange menu.
		$wp_customize->get_control( 'background_attachment' )->label = __( 'Background Effects', 'bgtfw' );
		$wp_customize->get_control( 'background_attachment' )->choices = $new_choices;
		$wp_customize->get_control( 'background_attachment' )->priority = 14;
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
	 * @return string Example: "url(https://domain.com/wp-content/themes/boldgrid-theme/
	 * 		inc/boldgrid-theme-framework/assets/img/patterns/60-lines.png)"
	 */
	public static function get_default_pattern_mod( $configs ) {
		return 'url(' . $configs['framework']['admin_asset_dir'] . 'img/patterns/'
			. $configs['customizer-options']['background']['defaults']['boldgrid_background_pattern'] . ')';
	}

	/**
	 * Add controls to handle the background pattern
	 *
	 * @since 1.0.0
	 * @param array $wp_customize WordPress Customizer Object.
	 */
	public function add_patterns( $wp_customize ) {
		require_once $this->configs['framework']['includes_dir'] . 'control/class-boldgrid-framework-control-pattern.php';

		$patterns = $this->get_pattern_files();

		$wp_customize->add_setting(
			'boldgrid_background_pattern',
			array(
				'default' => self::get_default_pattern_mod( $this->configs ),
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport' => 'postMessage',
			)
		);

			$wp_customize->add_control(
				new Boldgrid_Framework_Control_Pattern(
					$wp_customize,
					'boldgrid_background_pattern',
					array(
						'label' => __( 'Subtle Patterns', 'bgtfw' ),
						'section' => 'background_image',
						'settings' => 'boldgrid_background_pattern',
						'priority' => 3,
						'choices' => array(
							'patterns' => $patterns,
						),
					)
				)
			);
	}

	/**
	 * Register scripts used in the customizer, they are enqueued in customizer hooks
	 *
	 * @since 1.0.0
	 */
	public function register_front_end_scripts() {
		wp_register_script( 'boldgrid-stellar-parallax',
			$this->configs['framework']['js_dir'] . 'jquery-stellar/jquery.stellar.min.js',
			array( 'jquery' ),
		$this->configs['version'], true );
	}

	/**
	 * Add controls to hand the background pattern
	 *
	 * @since 1.0.0
	 */
	public function create_background_styles() {
		$theme_mods = get_theme_mods();
		$background_options = $this->configs['customizer-options']['background'];

		$bg_type = ! empty( $theme_mods['boldgrid_background_type'] ) ? $theme_mods['boldgrid_background_type'] : null;
		$bg_pattern = ! empty( $theme_mods['boldgrid_background_pattern'] ) ? $theme_mods['boldgrid_background_pattern'] : 'none';
		$bg_x_pos = isset( $theme_mods['boldgrid_background_horizontal_position'] ) ? $theme_mods['boldgrid_background_horizontal_position'] : null;
		$bg_y_pos = isset( $theme_mods['boldgrid_background_vertical_position'] ) ? $theme_mods['boldgrid_background_vertical_position'] : null;
		$bg_image_size = ! empty( $theme_mods['boldgrid_background_image_size'] ) ? $theme_mods['boldgrid_background_image_size'] : null;
		$bg_color = ! empty( $theme_mods['boldgrid_background_color'] ) ? $theme_mods['boldgrid_background_color'] : '';
		$bg_attach = ! empty( $theme_mods['background_attachment'] ) ? $theme_mods['background_attachment'] : null;

		/** Passing the defaults to the process that creates the css */
		if ( ! $bg_type ) {
			if ( ! empty( $background_options['defaults']['boldgrid_background_type'] ) ) {
				$bg_type = $background_options['defaults']['boldgrid_background_type'];
			}
		}

		if ( 'none' === $bg_pattern ) {
			if ( ! empty( $background_options['defaults']['boldgrid_background_pattern'] ) ) {
				$bg_pattern = self::get_default_pattern_mod( $this->configs );
			}
		}

		if ( ! $bg_color ) {
			if ( ! empty( $background_options['defaults']['boldgrid_background_color'] ) ) {
				$bg_color = $background_options['defaults']['boldgrid_background_color'];
			}
		}

		if ( ! $bg_y_pos ) {
			if ( ! empty( $background_options['defaults']['boldgrid_background_vertical_position'] ) ) {
				$bg_y_pos = $background_options['defaults']['boldgrid_background_vertical_position'];
			}
		}

		if ( ! $bg_x_pos ) {
			if ( ! empty( $background_options['defaults']['boldgrid_background_horizontal_position'] ) ) {
				$bg_x_pos = $background_options['defaults']['boldgrid_background_horizontal_position'];
			}
		}

		if ( ! $bg_image_size ) {
			if ( ! empty( $background_options['defaults']['boldgrid_background_image_size'] ) ) {
				$bg_image_size = $background_options['defaults']['boldgrid_background_image_size'];
			}
		}

		if ( ! $bg_attach ) {
			if ( ! empty( $background_options['defaults']['background_attachment'] ) ) {
				$bg_attach = $background_options['defaults']['background_attachment'];
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
					'pattern' => '/** Background pattern from subtlepatterns.com & http://www.transparenttextures.com/ **/',
				),
			);

			if ( ! empty( $bg_color ) ) {
				$css_rules['body.custom-background']['background-color'] = esc_attr( $bg_color );
			}
		} else {
			if ( false === is_null( $bg_x_pos ) || false === is_null( $bg_y_pos ) ) {
				// If the user has used the tool to position BG image use those cords even if they are 0.
				$bg_x_pos = $bg_x_pos ?: 0;
				$bg_y_pos = $bg_y_pos ?: 0;

				$css_rules = array(
					'body.custom-background' => array(
						'background-position' => ( $bg_x_pos * 5 ) . 'px ' . ($bg_y_pos * 5) . 'px',
					),
				);
			}

			if ( ! empty( $bg_color ) ) {
				$css_rules['body.custom-background']['background-color'] = esc_attr( $bg_color );
			}

			if ( 'parallax' === $bg_attach ) {

				$css_rules = array(
					'body.custom-background' => array(
						'background-attachment' => 'fixed',
					),
				);

				$boldgrid_filter_body_class = function ( $body_class ) {
					$body_class[] = 'boldgrid-customizer-parallax';
					return $body_class;
				};

				// Add the body class and enqueue the script library.
				add_filter( 'body_class', $boldgrid_filter_body_class );
				wp_enqueue_script( 'boldgrid-stellar-parallax' );
			}

			if ( $bg_image_size ) {
				$css_rules['body.custom-background']['background-size'] = esc_attr( $bg_image_size );
			}
		}

		if ( count( $css_rules ) ) {
			$custom_background = function ( $array ) {
				$array[] = 'custom-background';
				return $array;
			};

			add_filter( 'body_class', $custom_background );
		}

		return $css_rules;
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

		$wp_customize->add_control( new Boldgrid_Framework_Background_Crop( $wp_customize, 'background_image', array(
			'section'     => 'background_image',
			'label'       => __( 'Background Image', 'bgtfw' ),
			'priority'    => 9,
			'flex_width'  => true,
			'flex_height' => true,
			'width'       => $this->configs['customizer-options']['background']['defaults']['recommended_image_width'],
			'height'      => $this->configs['customizer-options']['background']['defaults']['recommended_image_height'],
		) ) );
	}
}
