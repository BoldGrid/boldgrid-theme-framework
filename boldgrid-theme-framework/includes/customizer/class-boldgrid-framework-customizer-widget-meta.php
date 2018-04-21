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

/*
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
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.0.0
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
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

			$wp_customize->selective_refresh->add_partial( $title_setting->id, array(
				'type' => 'sidebar_meta_title',
				'settings' => array( $title_setting->id ),
				'selector' => sprintf( '[data-customize-partial-id="%s"]', $title_setting->id ),
				'render_callback' => function() use ( $section ) {
					render_sidebar_title( $section->sidebar_id );
				},
			) );

			$background_color_setting = $wp_customize->add_setting( sprintf( 'sidebar_meta[%s][background_color]', $section->sidebar_id ), array(
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport' => 'postMessage',
				'default' => '',
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
				'transport' => 'postMessage',
				'default' => '',
			) );

			$selectors = array();

			foreach ( $this->configs['customizer-options']['typography']['selectors'] as $selector => $options ) {
				if ( 'headings' === $options['type'] ) {
					$selectors[] = ".dynamic-sidebar.%s {$selector}";
				}
			}

			$selectors = empty( $selectors ) ? '' : implode( ', ', $selectors );

			// Note that this partial has no render_callback because it is purely for JS previews.
			$wp_customize->selective_refresh->add_partial( $headings_color_setting->id, array(
				'type' => 'sidebar_meta_headings_color',
				'settings' => array( $headings_color_setting->id ),
				'selector' => str_replace( '%s', sanitize_title( $section->sidebar_id ), $selectors ),
			) );

			// Handle previewing of late-created settings.
			if ( did_action( 'customize_preview_init' ) ) {
				$title_setting->preview();
				$background_color_setting->preview();
				$headings_color_setting->preview();
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

		$palette = new Boldgrid_Framework_Compile_Colors( $this->configs );
		$active_palette = $palette->get_active_palette();
		$formatted_palette = $palette->color_format( $active_palette );

		$data = array(
			'l10n' => array(
				'title_label' => __( 'Title:', 'bgtfw' ),
				'background_color_label' => __( 'Background Color:', 'bgtfw' ),
				'headings_color_label' => __( 'Headings Color:', 'bgtfw' ),
			),
			'choices' => array(
				'colors' => $formatted_palette,
				'size' => $palette->get_palette_size( $formatted_palette ),
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

		$handle = 'bgtfw-customizer-widget-meta-background-color-partial';
		$src = $this->configs['framework']['js_dir'] . 'customizer/widget-meta/background-color-partial.js';
		$deps = array( 'customize-preview', 'customize-selective-refresh' );
		wp_enqueue_script( $handle, $src, $deps );

		$handle = 'bgtfw-customizer-widget-meta-headings-color-partial';
		$src = $this->configs['framework']['js_dir'] . 'customizer/widget-meta/headings-color-partial.js';
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
		printf( '<div class="dynamic-sidebar %s">', sanitize_title( $sidebar_id ) );
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
		$container_attributes = '';
		if ( is_customize_preview() ) {
			$container_attributes .= sprintf( ' data-customize-partial-id="%s"', "sidebar_meta[$sidebar_id][title]" );
			if ( $is_empty_title ) {
				$container_attributes .= ' hidden';
			}
		}

		$rendered_title = wptexturize( $title );
		$rendered_title = convert_smilies( $rendered_title );

		printf( '<h2 %s>%s</h2>', $container_attributes, esc_html( $rendered_title ) );
	}

	/**
	 * Render the sidebar start element.
	 *
	 * Note the priority is 5 so that it will output the start element before the title and before the "milestone" comment.
	 *
	 * @see WP_Customize_Widgets::end_dynamic_sidebar()
	 *
	 * @param string $sidebar_id Sidebar ID.
	 */
	public function render_sidebar_end_tag( $sidebar_id ) {
		printf( '</div><!-- / .dynamic-sidebar.%s -->', sanitize_title( $sidebar_id ) );
	}

	/**
	 * Add sidebar inline styles.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $css The CSS being filtered.
	 *
	 * @return string $css The modified CSS.
	 */
	public function add_sidebar_styles( $css ) {
		global $wp_registered_sidebars;

		if ( empty( $wp_registered_sidebars ) ) {
			return;
		}

		$sidebar_meta = get_theme_mod( 'sidebar_meta' );

		foreach ( $wp_registered_sidebars as $sidebar ) {
			$sidebar_id = $sidebar['id'];
			if ( is_active_sidebar( $sidebar_id ) || ! empty( $sidebar_meta[ $sidebar_id ]['title'] ) ) {
				$headings_color = empty( $sidebar_meta[ $sidebar_id ]['headings_color'] ) ? '' : $sidebar_meta[ $sidebar_id ]['headings_color'];
				$headings_color = explode( ':', $headings_color );
				$headings_color = array_pop( $headings_color );

				$selectors = array();

				foreach ( $this->configs['customizer-options']['typography']['selectors'] as $selector => $options ) {
					if ( 'headings' === $options['type'] ) {
						$selectors[] = ".dynamic-sidebar.{$sidebar_id} {$selector}";
					}
				}

				$selectors = empty( $selectors ) ? '' : implode( ', ', $selectors );
				$css .= "{$selectors} {color:{$headings_color};}";
			}
		}

		return $css;
	}
}
