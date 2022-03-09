<?php
/**
 * File: Boldgrid_Framework_Customizer_Generic
 *
 * Add generic css controls to the customizer.
 *
 * @since      1.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer_Generic
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Add generic css controls to the customizer.
 *
 * @since 2.0.0
 */
class Boldgrid_Framework_Customizer_Generic {

	/**
	 * Device Widths used for creating mobile styles.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public static $device_sizes = array(

		// Phone size from 0 to 767px.
		'phone'   => 767,

		// Tablet size from 768 to 991.
		'tablet'  => 991,

		// Desktop from 992 to 1199.
		'desktop' => 1199,

		// Large 1200 +++.
	);

	/**
	 * Range configuration.
	 *
	 * @var $range_config
	 */
	protected $range_config;

	/**
	 * BGTFW Configs
	 *
	 * @var array $configs
	 */
	protected $configs;

	/**
	 * Setup the boldgrid configs.
	 *
	 * @since 2.0.0
	 * @param array $configs Configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;

		$this->range_config = $this->create_range_configuration();
	}

	/**
	 * Add styles for all boldgrid generic controls.
	 *
	 * @since 2.0.0
	 */
	public function add_styles() {
		foreach ( $this->configs['customizer']['controls'] as $control ) {
			$name = ! empty( $control['choices']['name'] ) ? $control['choices']['name'] : null;
			if ( 'boldgrid_controls' === $name ) {
				if ( false !== strpos( $control['settings'], 'container_max_width' ) ) {
					continue;
				}
				$style_id      = $control['settings'] . '-bgcontrol';
				$theme_mod_val = get_theme_mod( $control['settings'] );

				// If theme mod is set, use it to create styles.
				if ( $theme_mod_val && ! empty( $theme_mod_val['media'] ) ) {
					$css = ! empty( $theme_mod_val['css'] ) ? $theme_mod_val['css'] : false;

				// If theme mod is not set, try to generate styles from default settings.
				} else {
					$css = $this->get_default_styles( $control );
				}

				// Enqueue any css if applicable.
				if ( $css ) {
					self::add_inline_style( $style_id, wp_specialchars_decode( $css, $quote_style = ENT_QUOTES ) );
				}
			}
		}
	}

	/**
	 * Sanitize a set of css values.
	 *
	 * @since 2.0.0
	 *
	 * @param string $value Theme mode value.
	 * @return array        Sanitization Output.
	 */
	public static function sanitize( $value ) {
		return sanitize_post( $value, 'raw' );
	}

	/**
	 * Get the default styles for a control.
	 *
	 * If the user has not customized this control, we will use any values defined in the
	 * defaults to generate css.
	 *
	 * @since 2.0.0
	 *
	 * @param  array $control Controls Configuration.
	 * @return string         Added ability to define device visibility defaults.
	 */
	public function get_default_styles( $control ) {
		$css = '';

		if ( 'DeviceVisibility' === $control['choices']['type'] ) {
			$css .= $this->device_visibility_styles( $control ) ?: '';
		} elseif ( 'ColWidth' === $control['choices']['type'] ) {
			return $css;
		} else {
			$css .= $this->directional_control_styles( $control ) ?: '';
		}

		return $css;
	}

	/**
	 * Create a set of default rules for a directional control with its defaults.
	 *
	 * @since 2.0.0
	 *
	 * @param  array $control Configs for a control.
	 * @return string         CSS for the control.
	 */
	public function directional_control_styles( $control ) {
		$defaults = get_theme_mod( $control['settings'] );
		$defaults = is_array( $defaults ) ? $defaults : [];

		$css      = '';
		$selector = implode( ',', $control['choices']['settings']['control']['selectors'] );
		foreach ( $defaults as $config_set ) {
			foreach ( $config_set['media'] as $media_device ) {
				$media_prefix = $this->create_media_prefix( $media_device );
				$control_css  = $this->get_directional_css( $control, $config_set );
				$control_css  = $control_css ? "${selector} { ${control_css} }" : '';
				$control_css  = $media_prefix && $control_css ? "${media_prefix} { ${control_css} }" : $control_css;

				$css .= $control_css;
			}
		}

		return $css;
	}

	/**
	 * Get the css for a single directional control setting.
	 *
	 * @since 2.0.0
	 *
	 * @param  array $control Configs for a control.
	 * @param  array $config  A single set of control configs.
	 * @return string          CSS for directional control.
	 */
	public function get_directional_css( $control, $config ) {
		$css = '';
		foreach ( $config['values'] as $direction => $value ) {
			$unit             = ! empty( $config['unit'] ) ? $config['unit'] : 'px';
			$control_property = $this->get_control_property( $control['choices']['type'], $direction );
			if ( 'ContainerWidth' === $control['choices']['type'] && 'max-width' === $control_property && '%' === $unit ) {
				$css .= "${control_property}: calc( {$value}{$unit} + 30px );";
			} else {
				$css .= "${control_property}: ${value}{$unit};";
			}
		}

		/**
		 * Override the CSS for a control.
		 *
		 * @since 2.0.0
		 *
		 * @param  string $css    CSS to add to media device.
		 * @param  array $control Control Configs.
		 * @param  array $config  Device Settings.
		 */
		$css = apply_filters( 'bgtfw_generic_css_' . $control['choices']['type'], $css, $control, $config );

		return $css;
	}

	/**
	 * Create the css needed for border css.
	 *
	 * @since 2.0.0
	 *
	 * @param string $css    CSS to add to media device.
	 * @param array  $control Control Configs.
	 * @param array  $config  Device Settings.
	 * @return string        CSS for the border.
	 */
	public function border_css( $css, $control, $config ) {
		$color                  = get_theme_mod( $control['settings'] . '_color' ) ?: '';
		list( $color_variable ) = explode( ':', $color );
		$color_variable         = "var(--{$color_variable});";
		$css                   .= ! empty( $color_variable ) ? 'border-color: ' . $color_variable . ';' : '';
		$css                   .= ! empty( $config['type'] ) ? 'border-style: ' . $config['type'] . ';' : '';

		return $css;
	}

	/**
	 * Create the css needed for box shadows.
	 *
	 * @since 2.0.0
	 *
	 * @param string $css    CSS to add to media device.
	 * @param array  $control Control Configs.
	 * @param array  $config  Device Settings.
	 * @return string        CSS for the box shadow.
	 */
	public function box_shadow_css( $css, $control, $config ) {
		$val        = $config['values'];
		$properties = [];

		$get_val = function ( $prop, $default ) use ( $val ) {
			return ( ! empty( $val[ $prop ] ) ? $val[ $prop ] : $default ) . 'px';
		};

		if ( $val['horizontal-position'] || $val['vertical-position'] || $val['blur-radius'] || $val['spread-radius'] ) {
			$properties[] = isset( $config['type'] ) ? $config['type'] : '';
			$properties[] = $get_val( 'horizontal-position', 0 );
			$properties[] = $get_val( 'vertical-position', 0 );
			$properties[] = $get_val( 'blur-radius', 0 );
			$properties[] = $get_val( 'spread-radius', 0 );
			$properties[] = ! empty( $config['color'] ) ? $config['color'] : '#cccccc';
		}

		$css = implode( ' ', $properties );
		$css = $css ? 'box-shadow: ' . $css : $css;

		return $css;
	}

	/**
	 * For a given control and a slider name, get the css property.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $control_type Control Type name.
	 * @param  string $slider_name  Slider Name.
	 * @return string               Css property to set for a slider.
	 */
	protected function get_control_property( $control_type, $slider_name ) {
		$css_property = '';

		switch ( $control_type ) {
			case 'Margin':
			case 'Padding':
				$css_property = strtolower( $control_type ) . '-' . $slider_name;
				break;
			case 'BorderRadius':
				$css_property = 'border-' . $slider_name . '-radius';
				break;
			case 'Border':
				$css_property = 'border-' . $slider_name;
				break;
			default:
				$css_property = $slider_name;
				break;
		}

		return $css_property;
	}

	/**
	 * Create styles for device visibility.
	 *
	 * @since 2.0.0
	 *
	 * @param  array $control Control Configuration.
	 * @return string         CSS to display.
	 */
	public function device_visibility_styles( $control ) {
		$css      = '';
		$defaults = get_theme_mod( $control['settings'] );
		$defaults = is_array( $defaults ) ? $defaults : [];

		foreach ( $defaults as $device ) {
			$selector     = implode( ',', $control['choices']['settings']['control']['selectors'] );
			$media_prefix = $this->create_media_prefix( $device );
			$css         .= $media_prefix ? "${media_prefix} { ${selector} { display: none !important;} }" : '';
		}

		return $css;
	}

	/**
	 * Create a media query for a given device.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $device Device name (phone, tablet, desktop, large).
	 * @return string         Media prefix.
	 */
	public function create_media_prefix( $device ) {
		$prefix = '';
		$range  = ! empty( $this->range_config[ $device ] ) ? $this->range_config[ $device ] : null;
		if ( ! $range ) {
			return $prefix;
		}

		if ( ! empty( $range['min'] ) && ! empty( $range['max'] ) ) {
			$prefix = "@media only screen and (max-width: {$range['max']}px) and (min-width: {$range['min']}px)";
		} elseif ( ! empty( $range['min'] ) && empty( $range['max'] ) ) {
			$prefix = "@media only screen and ( min-width: {$range['min']}px )";
		} elseif ( empty( $range['min'] ) && ! empty( $range['max'] ) ) {
			$prefix = "@media only screen and ( max-width: {$range['max']}px )";
		}

		return $prefix;
	}

	/**
	 * Add inline style without parent stylesheet.
	 *
	 * @since 2.0.0
	 *
	 * @param string $id  Desired ID, will have a suffix of -inline-style.
	 * @param string $css CSS to output.
	 */
	public static function add_inline_style( $id, $css ) {
		wp_register_style( $id, false );
		wp_enqueue_style( $id );
		wp_add_inline_style( $id, $css );
	}

	/**
	 * Create an object of ranges tpo be used for creating media queries.
	 *
	 * @since 2.0.0
	 *
	 * @return array Ranges per devices.
	 */
	protected function create_range_configuration() {
		$device_config = self::$device_sizes;

		$ranges = [
			'phone'   => [
				'max' => $device_config['phone'],
			],
			'tablet'  => [
				'min' => $device_config['phone'] + 1,
				'max' => $device_config['tablet'],
			],
			'desktop' => [
				'min' => $device_config['tablet'] + 1,
				'max' => $device_config['desktop'],
			],
			'large'   => [
				'min' => $device_config['desktop'] + 1,
			],
		];

		return $ranges;
	}

	/**
	 * Get Column Defaults
	 *
	 * These are the default column controls that will
	 * be added when none already exist in the theme mods,
	 * such as when loading starter content in the customizer.
	 *
	 * @since 2.2.3
	 *
	 * @return array Array of default header columns
	 */
	public function get_column_defaults() {
		$defaults = array(
			[
				'media'    => [ 'large', 'desktop' ],
				'unit'     => 'col',
				'isLinked' => false,
				'values'   => array(
					'default_branding' => 6,
					'default_menu'     => 6,
				),
			],
			[
				'media'    => [ 'phone', 'tablet' ],
				'unit'     => 'col',
				'isLinked' => false,
				'values'   => array(
					'default_branding' => 12,
					'default_menu'     => 12,
				),
			],
		);

		return $defaults;
	}

	/**
	 * Get width Defaults
	 *
	 * These are the default width controls that will
	 * be added when none already exist in the theme mods,
	 * such as when loading starter content in the customizer.
	 *
	 * @since 2.2.3
	 *
	 * @param string $width_type Width type.
	 *
	 * @return array Array of default header columns
	 */
	public function get_width_defaults( $width_type ) {
		$defaults = array(
			'width'     => array(
				array(
					'media'    => array( 'base' ),
					'unit'     => 'px',
					'isLinked' => true,
					'values'   => array(
						'width' => 1170,
					),
				),
			),
			'max-width' => array(
				array(
					'media'    => array( 'large' ),
					'unit'     => 'px',
					'isLinked' => true,
					'values'   => array(
						'maxWidth' => 1920,
					),
				),
			),
		);

		return $defaults[ $width_type ];
	}

	/**
	 * Get Header Columns
	 *
	 * @since 2.2.3
	 *
	 * @return array Array of header column slider configs.
	 */
	public function get_header_columns() {
		$default_layout = [
			[
				'container' => 'container',
				'items'     => [
					[
						'type'    => 'boldgrid_site_identity',
						'key'     => 'branding',
						'align'   => 'w',
						'display' => [
							[
								'selector' => '.custom-logo-link',
								'display'  => 'show',
								'title'    => __( 'Logo', 'bgtfw' ),
							],
							[
								'selector' => '.site-title',
								'display'  => 'show',
								'title'    => __( 'Title', 'bgtfw' ),
							],
							[
								'selector' => '.site-description',
								'display'  => 'show',
								'title'    => __( 'Tagline', 'bgtfw' ),
							],
						],
					],
					[
						'type'  => 'boldgrid_menu_main',
						'key'   => 'menu',
						'align' => 'e',
					],
				],
			],
		];

		// When loading starter content, the bgtfw_header_layout theme mod is not populated yet, so we have to use the default_layout array.
		$header_layout = get_theme_mod( 'bgtfw_header_layout', false ) ? get_theme_mod( 'bgtfw_header_layout', false ) : $default_layout;

		$sliders = array();

		// We have to loop through the items in the header layout to generate the sliders.
		foreach ( $header_layout as $section_index => $section ) {
			// Since the sections are indexed starting at 0, we add 1 to be more human readable.
			$section_label = (string) ( $section_index + 1 );
			foreach ( $section['items'] as $item_index => $item ) {
				// Widget areas are keyed as 'sidebar' but we want those to display as 'Widget Area' instead.
				if ( 'sidebar' === $item['key'] ) {
					$label = 'Row ' . $section_label . ' Widget Area';
				} else {
					$label = 'Row ' . $section_label . ' ' . ucfirst( $item['key'] );
				}
				$sliders[] = array(
					'name'        => isset( $item['uid'] ) ? $item['uid'] : 'default_' . $item['key'],
					'label'       => $label,
					'cssProperty' => 'width',
				);
			}
		}

		return $sliders;
	}
}
