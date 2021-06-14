<?php
/**
 * Customizer Widget Meta Class
 *
 * This adds additional functionality to widget areas throughout
 * the theme.  Special thanks to Weston Ruter/XWP for the initial
 * idea on adding color controls to widget sidebars.
 *
 * @package Boldgrid_Framework_Customizer
 * @subpackage Boldgrid_Framework_Customizer_Widget_Meta
 */

/**
 * This file was originally inspired from XWP.
 * This is the license information under which the code was distributed:
 *
 * Copyright (c) 2017 XWP (https://xwp.co/)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

/**
 * Class: Boldgrid_Framework_Customizer_Widget_Meta
 *
 * This class is responsible for additional functionality
 * provided in widget areas.
 *
 * @since 2.0.0
 */
class Boldgrid_Framework_Customizer_Widget_Meta {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       array     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Reference to Boldgrid_Framework_Compile_Colors object.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    Object    $palette The BoldGrid Theme Framework configurations.
	 */
	protected $palette;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.0.0
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
		$this->palette = new Boldgrid_Framework_Compile_Colors( $configs );
	}

	/**
	 * Register settings for sidebars.
	 *
	 * See `WP_Customize_Widgets::schedule_customize_register()` for why settings are registered later at the wp action.
	 *
	 * @see WP_Customize_Widgets::schedule_customize_register()
	 * @param \WP_Customize_Manager $wp_customize Manager.
	 */
	public function customize_register( \WP_Customize_Manager $wp_customize ) {
		if ( empty( $wp_customize->widgets ) ) {
			return;
		}

		if ( is_admin() ) {
			$this->register_sidebar_meta_settings();
		} else {
			add_action( 'wp', array( $this, 'register_sidebar_meta_settings' ), 100 );
		}
	}

	/**
	 * Register meta settings for widget sidebars.
	 *
	 * @global \WP_Customize_Manager $wp_customize
	 */
	public function register_sidebar_meta_settings() {
		global $wp_customize;
		foreach ( $wp_customize->sections() as $section ) {
			if ( ! ( $section instanceof \WP_Customize_Sidebar_Section ) ) {
				continue;
			}

			$title_setting = $wp_customize->add_setting( sprintf( 'sidebar_meta[%s][title]', $section->sidebar_id ), array(
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport' => 'postMessage',
				'default' => '',
			) );

			$widget_meta = $this;

			$sanitize = new Boldgrid_Framework_Customizer_Color_Sanitize();

			$wp_customize->selective_refresh->add_partial( $title_setting->id, array(
				'type' => 'sidebar_meta_title',
				'settings' => array( $title_setting->id ),
				'selector' => sprintf( '[data-customize-partial-id="%s"]', $title_setting->id ),
				'render_callback' => function() use ( $widget_meta, $section ) {
					$widget_meta->render_sidebar_title( $section->sidebar_id );
				},
			) );

			$background_color_setting = $wp_customize->add_setting( sprintf( 'sidebar_meta[%s][background_color]', $section->sidebar_id ), array(
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
				'transport' => 'postMessage',
				'default' => $this->get_sidebar_defaults( $section->sidebar_id, 'background_color' ),
			) );

			// Note that this partial has no render_callback because it is purely for JS previews.
			$wp_customize->selective_refresh->add_partial( $background_color_setting->id, array(
				'type' => 'sidebar_meta_background_color',
				'settings' => array( $background_color_setting->id ),
				'selector' => sprintf( '.dynamic-sidebar.%s', sanitize_title( $section->sidebar_id ) ),
			) );

			$headings_color_setting = $wp_customize->add_setting( sprintf( 'sidebar_meta[%s][headings_color]', $section->sidebar_id ), array(
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
				'transport' => 'postMessage',
				'default' => $this->get_sidebar_defaults( $section->sidebar_id, 'headings_color' ),
			) );

			$selectors = array();

			foreach ( $this->configs['customizer-options']['typography']['selectors'] as $selector => $options ) {
				if ( 'headings' === $options['type'] ) {
					$selector_set = explode( ',', $selector );
					foreach ( $selector_set as $selector ) {
						$selectors[] = ".dynamic-sidebar.%s {$selector}";
					}
				}
			}

			$selectors = empty( $selectors ) ? '' : implode( ', ', $selectors );

			// Note that this partial has no render_callback because it is purely for JS previews.
			$wp_customize->selective_refresh->add_partial( $headings_color_setting->id, array(
				'type' => 'sidebar_meta_headings_color',
				'settings' => array( $headings_color_setting->id ),
				'selector' => str_replace( '%s', sanitize_title( $section->sidebar_id ), $selectors ),
			) );

			$links_color_setting = $wp_customize->add_setting( sprintf( 'sidebar_meta[%s][links_color]', $section->sidebar_id ), array(
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
				'transport' => 'postMessage',
				'default' => $this->get_sidebar_defaults( $section->sidebar_id, 'links_color' ),
			) );

			$wp_customize->selective_refresh->add_partial( $links_color_setting->id, array(
				'type' => 'sidebar_meta_links_color',
				'settings' => array( $links_color_setting->id ),
				'selector' => sprintf( '.dynamic-sidebar.%s', sanitize_title( $section->sidebar_id ) ),
			) );

			// Handle previewing of late-created settings.
			if ( did_action( 'customize_preview_init' ) ) {
				$title_setting->preview();
				$background_color_setting->preview();
				$headings_color_setting->preview();
				$links_color_setting->preview();
			}
		} // End foreach().
	}

	/**
	 * Enqueue script.
	 *
	 * @since 2.0.0
	 *
	 * @global \WP_Customize_Manager $wp_customize
	 */
	public function customize_controls_enqueue_scripts() {
		global $wp_customize;

		if ( empty( $wp_customize->widgets ) ) {
			return;
		}

		$handle = 'bgtfw-customizer-widget-meta-controls';
		$src = $this->configs['framework']['js_dir'] . 'customizer/widget-meta/controls.js';
		$deps = array( 'customize-widgets' );
		wp_enqueue_script( $handle, $src, $deps );

		$active_palette = $this->palette->get_active_palette();
		$formatted_palette = $this->palette->color_format( $active_palette );

		$data = array(
			'l10n' => array(
				'title_label' => __( 'Title:', 'bgtfw' ),
				'background_color_label' => __( 'Background Color:', 'bgtfw' ),
				'headings_color_label' => __( 'Headings Color:', 'bgtfw' ),
				'links_color_label' => __( 'Links Color:', 'bgtfw' ),
			),
			'backgroundChoices' => array(
				'colors' => $formatted_palette,
				'size' => $this->palette->get_palette_size( $formatted_palette, true ),
				'transparent' => true,
			),
			'choices' => array(
				'colors' => $formatted_palette,
				'size' => $this->palette->get_palette_size( $formatted_palette ),
			),
		);
		wp_add_inline_script( $handle, sprintf( 'CustomizeWidgetSidebarMetaControls.init( wp.customize, %s );', wp_json_encode( $data ) ) );
	}

	/**
	 * Print controls template.
	 *
	 * @link https://core.trac.wordpress.org/ticket/30738
	 */
	public function customize_controls_print_footer_scripts() {
		?>
		<script type="text/html" id="tmpl-customize-control-widget-sidebar-meta-title-content">
			<# var elementIdBase = String( Math.random() ); #>
			<label for="{{ elementIdBase + '[title]' }}" class="customize-control-title">{{ data.label }}</label>
			<input class="title widefat" type="text" id="{{ elementIdBase + '[title]' }}" data-customize-setting-link="{{ data.settings['default'] }}">
		</script>
		<?php
	}


	/**
	 * Enqueue frontend preview script.
	 *
	 * @since  2.0.0
	 *
	 * @global \WP_Customize_Manager $wp_customize
	 */
	public function customize_preview_init() {
		global $wp_customize;

		if ( empty( $wp_customize->widgets ) ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_preview_scripts' ), 999 );
	}

	/**
	 * Enqueue preview scripts.
	 *
	 * @since 2.0.0
	 */
	public function enqueue_preview_scripts() {
		$handle = 'bgtfw-customizer-widget-meta-title-partial';
		$src = $this->configs['framework']['js_dir'] . 'customizer/widget-meta/title-partial.js';
		$deps = array( 'customize-preview', 'customize-selective-refresh' );
		wp_enqueue_script( $handle, $src, $deps );
	}

	/**
	 * Render the sidebar start element if it is needed.
	 *
	 * Themes and plugins can prevent an additional container element by defining a
	 * `container_class` property when calling `register_sidebar()`. Note the priority
	 * is 5 so that it will output the start element before the title and before the "milestone" comment.
	 *
	 * @see WP_Customize_Widgets::start_dynamic_sidebar()
	 *
	 * @param string $sidebar_id Sidebar ID.
	 */
	public function render_sidebar_start_tag( $sidebar_id ) {
		printf( '<div class="dynamic-sidebar %s">', esc_attr( sanitize_title( $sidebar_id ) ) );
	}

	/**
	 * Render the sidebar title.
	 *
	 * Note the priority is 9 so that it will output the title before the "milestone" comment.
	 *
	 * @see WP_Customize_Widgets::start_dynamic_sidebar()
	 *
	 * @param string $sidebar_id Sidebar ID.
	 */
	public function render_sidebar_title( $sidebar_id ) {
		$sidebar_meta = get_theme_mod( 'sidebar_meta' );
		$is_empty_title = empty( $sidebar_meta[ $sidebar_id ]['title'] );

		if ( $is_empty_title && ! is_customize_preview() ) {
			return;
		}

		$title = $is_empty_title ? '' : $sidebar_meta[ $sidebar_id ]['title'];

		$rendered_title = wptexturize( $title );
		$rendered_title = convert_smilies( $rendered_title );

		printf( '<h2 %1$s>%2$s</h2>',
			is_customize_preview() ? 'data-customize-partial-id="' . esc_attr( "sidebar_meta[$sidebar_id][title]" ) . '"' : '',
			esc_html( $rendered_title )
		);
	}

	/**
	 * Add sidebar inline styles for customizer preview.
	 *
	 * @since 2.0.0
	 */
	public function add_customizer_sidebar_styles() {
		global $wp_registered_sidebars;

		if ( empty( $wp_registered_sidebars ) ) {
			return;
		}

		foreach ( $wp_registered_sidebars as $sidebar ) {
			$sidebar_id = $sidebar['id'];
			Boldgrid_Framework_Customizer_Generic::add_inline_style( "dynamic-sidebar-{$sidebar_id}", $this->generate_sidebar_styles( $sidebar_id ) );
		}
	}

	/**
	 * Add sidebar inline styles for frontend of site.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $css The CSS being filtered.
	 *
	 * @return string $css The modified CSS.
	 */
	public function add_frontend_sidebar_styles( $css ) {
		global $wp_registered_sidebars;

		if ( empty( $wp_registered_sidebars ) ) {
			return;
		}

		foreach ( $wp_registered_sidebars as $sidebar ) {
			$css .= $this->generate_sidebar_styles( $sidebar['id'] );
		}

		return $css;
	}

	/**
	 * Generates the inline CSS for a sidebar.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $sidebar_id The ID of the sidebar to apply styles for.
	 *
	 * @return string $css        The inline CSS to apply for the sidebar.
	 */
	public function generate_sidebar_styles( $sidebar_id ) {
		$css = '';
		$sidebar_meta = get_theme_mod( 'sidebar_meta' );
		if ( is_active_sidebar( $sidebar_id ) || ! empty( $sidebar_meta[ $sidebar_id ]['title'] ) || current_user_can( 'edit_theme_options' ) ) {
			$headings_color = empty( $sidebar_meta[ $sidebar_id ]['headings_color'] ) ? $this->get_sidebar_defaults( $sidebar_id, 'headings_color' ) : $sidebar_meta[ $sidebar_id ]['headings_color'];

			// Get sidebar heading colors as css variables.
			list( $headings_color ) = explode( ':', $headings_color );
			$headings_color = "var(--{$headings_color})";

			$selectors = array();

			foreach ( $this->configs['customizer-options']['typography']['selectors'] as $selector => $options ) {
				$exploded = explode( ',', $selector );
				foreach ( $exploded as $explode ) {
					$selectors[] = ".{$sidebar_id} {$explode}";
				}
			}

			$selectors = empty( $selectors ) ? '' : implode( ', ', $selectors );
			$css .= "{$selectors} {color:{$headings_color};}";
		}

		return $css;
	}

	/**
	 * Get defaults for registered sidebars when creating controls.
	 *
	 * @since 2.0.0
	 *
	 * @param string $sidebar_id ID of the sidebar.
	 * @param bool   $type       Whether to return a specific setting or full sidebar settings.
	 */
	public function get_sidebar_defaults( $sidebar_id, $type = false ) {
		global $boldgrid_theme_framework;
		$config = $boldgrid_theme_framework->get_configs();
		$settings = [];

		$default_sidebar_settings = array(
			'title' => '',
			'background_color' => '',
			'headings_color' => '',
			'links_color' => '',
		);
		// Check for defaults from configs.
		if ( ! empty( $config['customizer']['controls']['sidebar_meta'] ) && ! empty( $config['customizer']['controls']['sidebar_meta'][ $sidebar_id ] ) ) {
			$settings = $config['customizer']['controls']['sidebar_meta'];
			if ( ! empty( $settings[ $sidebar_id ] ) ) {
				$settings[ $sidebar_id ] = array_merge( $default_sidebar_settings, $settings[ $sidebar_id ] );
			}
		} else {
			$settings[ $sidebar_id ] = $default_sidebar_settings;
		}

		$active_palette = $this->palette->get_active_palette();
		$formatted_palette = $this->palette->color_format( $active_palette );

		// Check for defaults set from configs.
		foreach ( $settings[ $sidebar_id ] as $key => $value ) {
			if ( strpos( $key, '_color' ) !== false && strpos( $value, ':' ) === false && ! empty( $formatted_palette[ $value ] ) ) {
				$settings[ $sidebar_id ][ $key ] = $value . ':' . $formatted_palette[ $value ];
			}
		}

		// Header sidebars defaults.
		if ( strpos( $sidebar_id, 'header' ) !== false && empty( $settings[ $sidebar_id ]['background_color'] ) ) {
			$settings[ $sidebar_id ]['background_color'] = get_theme_mod( 'bgtfw_header_color', $this->get_control_default( 'bgtfw_header_color' ) );

		// Footer sidebars defaults.
		} elseif ( strpos( $sidebar_id, 'footer' ) !== false ) {
			if ( empty( $settings[ $sidebar_id ]['background_color'] ) ) {
				$settings[ $sidebar_id ]['background_color'] = get_theme_mod( 'bgtfw_footer_color', $this->get_control_default( 'bgtfw_footer_color' ) );
			}
			if ( empty( $settings[ $sidebar_id ]['links_color'] ) ) {
				$settings[ $sidebar_id ]['links_color'] = get_theme_mod( 'bgtfw_footer_links', $this->get_control_default( 'bgtfw_footer_links' ) );
			}
		}

		// All other sidebar defaults.
		$defaults = [];
		$defaults[ $sidebar_id ]['background_color'] = 'color-1:' . preg_replace( '/\s+/', '', $formatted_palette['color-1'] );
		$defaults[ $sidebar_id ]['headings_color'] = 'color-2:' . preg_replace( '/\s+/', '', $formatted_palette['color-2'] );
		$defaults[ $sidebar_id ]['links_color'] = 'color-3:' . preg_replace( '/\s+/', '', $formatted_palette['color-3'] );

		$settings[ $sidebar_id ] = array_filter( $settings[ $sidebar_id ] );
		$settings[ $sidebar_id ] = array_merge( $defaults[ $sidebar_id ], $settings[ $sidebar_id ] );

		return false !== $type ? $settings[ $sidebar_id ][ $type ] : $settings[ $sidebar_id ];
	}

	/**
	 * Get default value for customizer control.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $settings The settings ID associated with a control.
	 *
	 * @return mixed  $default  Default value for control from configs.
	 */
	public function get_control_default( $settings ) {
		$control = array_filter( $this->configs['customizer']['controls'], function( $setting ) use ( $settings ) {
			return isset( $setting['settings'] ) && $setting['settings'] === $settings;
		} );

		return isset( $control['default'] ) ? $control['default'] : false;
	}
}
