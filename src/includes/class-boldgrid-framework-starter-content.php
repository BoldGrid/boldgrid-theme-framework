<?php
/**
 * Class: BoldGrid_Framework_Starter_Content
 *
 * This is used for the starter content functionality in the BoldGrid Theme Framework.
 *
 * @since    2.0.0
 * @category Customizer
 * @package  Boldgrid_Framework
 * @author   BoldGrid <support@boldgrid.com>
 * @link     https://boldgrid.com
 */

/**
 * BoldGrid_Framework_Starter_Content
 *
 * Responsible for the starter content import functionality in the BoldGrid Theme Framework.
 *
 * @since 2.0.0
 */
class BoldGrid_Framework_Starter_Content {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    string    $configs The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Default value for starter content.
	 *
	 * @since 2.0.0
	 * @access protected
	 * @var    mixed     Value of default.
	 */
	protected $default = '';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.0.0
	 *
	 * @param string $configs The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Get the default palette array.
	 *
	 * @since 2.0.0
	 */
	public function get_default() {
		return ! empty( $this->default ) ? $this->default : $this->set_default( $this->configs );
	}

	/**
	 * Default palette to use for palette selector controls.
	 *
	 * @since 2.0.0
	 *
	 * @param  array $config  BGTFW Configuration.
	 * @return mixed $default Default Configiguraton.
	 */
	public function set_default( $config ) {

		$palette = new Boldgrid_Framework_Compile_Colors( $this->configs );
		$active = $palette->get_active_palette();
		if ( ! empty( $active ) ) {
			$colors = [];
			foreach ( $active as $key => $value ) {
				if ( strpos( $key, 'neutral' ) === false ) {
					$colors[] = $value;
				}
			}
			$default = array(
				array(
					'default' => true,
					'format' => 'palette-primary',
					'colors' => $colors,
				),
			);

			if ( ! empty( $active['palette-primary-neutral-color'] ) ) {
				$default[0]['neutral-color'] = $active['palette-primary-neutral-color'];
			}
		} else {

			// Default palette to use for palette selector controls.
			$default = array_filter( $config['customizer-options']['colors']['defaults'], function( $palette ) {
				return ! empty( $palette['default'] );
			} );
		}

		// Convert default colors to RGBs if alternate format was passed in configs.
		foreach ( $default[0]['colors'] as $index => $color ) {
			$default[0]['colors'][ $index ] = ariColor::newColor( $color )->toCSS( 'rgb' );
		}

		// Convert neutral color to RGB if alternate format was passed in configs.
		if ( isset( $default[0]['neutral-color'] ) ) {
			$default[0]['neutral-color'] = ariColor::newColor( $default[0]['neutral-color'] )->toCSS( 'rgb' );
		}

		return $this->default = $default;
	}

	/**
	 * Handle custom_logo for get_theme_starter_content filter.
	 *
	 * @since  2.0.3
	 *
	 * @param  array $content Processed content.
	 * @param  array $config  Starter content config.
	 *
	 * @return array $content Modified $content.
	 */
	public function add_custom_logo( $content, $config ) {
		// Set empty arrays if not set.
		if ( ! isset( $config['attachments'] ) || ! is_array( $config['attachments'] ) ) {
			$config['attachments'] = [];
		}

		// Loop through each post and assign metadata.
		foreach ( $config['attachments'] as $post => $configs ) {
			if ( isset( $configs['meta_input'] ) && isset( $configs['meta_input']['_custom_logo'] ) ) {

				// Only add a custom_logo if the user hasn't set their own logo ( still replace other starter content logos ).
				$custom_logo = get_theme_mod( 'custom_logo' );

				if ( empty( $custom_logo ) || ! get_post_meta( $custom_logo, '_custom_logo', true ) ) {
					$parent_post_id = 0;
					$file = get_parent_theme_file_path( $config['attachments'][ $post ]['file'] );
					$filename = basename( $file );
					$fs = new Boldgrid_Framework_Wp_Fs();
					$upload_file = wp_upload_bits( $filename, null, $fs->get_contents( $file ) );

					if ( ! $upload_file['error'] ) {
						$wp_filetype = wp_check_filetype( $filename, null );

						$attachment = [
							'post_mime_type' => $wp_filetype['type'],
							'post_parent' => $parent_post_id,
							'post_title' => $config['attachments'][ $post ]['post_title'],
							'post_content' => '',
							'post_status' => 'inherit',
						];

						$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );

						if ( ! is_wp_error( $attachment_id ) ) {

							// Add any custom postmeta keys from configs.
							foreach ( $config['attachments'][ $post ]['meta_input'] as $key => $value ) {
								update_post_meta( $attachment_id, $key, $value );
							}

							// Update attachment metadata.
							if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
								require_once( ABSPATH . 'wp-admin/includes/image.php' );
							}

							$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
							wp_update_attachment_metadata( $attachment_id, $attachment_data );

							// Add custom_logo theme_mod.
							set_theme_mod( 'custom_logo', $attachment_id );
						}
					}
				}
			}
		}

		return $content;
	}

	/**
	 * Handle custom_logo for get_theme_starter_content filter.
	 *
	 * @since  2.0.3
	 *
	 * @param  array $content Processed content.
	 * @param  array $config  Starter content config.
	 *
	 * @return array $content Modified $content.
	 */
	public function remove_custom_logo( $content, $config ) {
		if ( isset( $content['attachments'] ) ) {

			// Loop through each post and assign metadata.
			foreach ( $content['attachments'] as $post => $configs ) {
				if ( isset( $configs['meta_input'] ) ) {
					$content['posts'][ $post ]['meta_input'] = $configs['meta_input'];
				}
			}
		}

		return $content;
	}

	/**
	 * Adds post meta to get_theme_starter_content filter.
	 *
	 * @since  2.0.0
	 *
	 * @param  array $content Processed content.
	 * @param  array $config  Starter content config.
	 *
	 * @return array $content Modified $content.
	 */
	public function add_post_meta( $content, $config ) {

		// Set empty arrays if not set.
		if ( ! isset( $config['attachments'] ) || ! is_array( $config['attachments'] ) ) {
			$config['attachments'] = [];
		}
		if ( ! isset( $config['posts'] ) || ! is_array( $config['posts'] ) ) {
			$config['post'] = [];
		}

		// Merge posts and attachments for loop.
		$posts = array_merge( $config['posts'], $config['attachments'] );

		// Loop through each post and assign metadata.
		foreach ( $posts as $post => $configs ) {
			if ( isset( $configs['meta_input'] ) ) {
				if ( isset( $configs['meta_input']['_custom_logo'] ) ) {

					// Remove attachment from content so it's not uploaded twice.
					unset( $content[ $post ] );
				}

				$content['posts'][ $post ]['meta_input'] = $configs['meta_input'];
			}
		}

		return $content;
	}

	/**
	 * Convert HTML closures into markup.
	 *
	 * This allows us to defer starter content processes until needed
	 * instead of each page load.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $content Content.
	 * @return array           Configuration.
	 */
	public function post_content_callbacks( $content ) {
		foreach ( $content['posts'] as &$post ) {
			if ( ! empty( $post['post_content'] ) && is_callable( $post['post_content'] ) ) {
				$post['post_content'] = call_user_func( $post['post_content'] );
			}
		}

		return $content;
	}

	/**
	 * Declares theme support for starter content using the array of starter
	 * content found in the BGTFW configurations.
	 *
	 * @since 2.0.0
	 */
	public function add_theme_support() {
		if ( ! empty( $this->configs['starter-content'] ) ) {
			add_theme_support( 'starter-content', $this->configs['starter-content'] );
		}
	}

	/**
	 * Sets the starter content defaults that haven't been satisfied for a proper
	 * presentation of the starter content.
	 *
	 * @since 2.0.0
	 *
	 * @param array $config Array of BGTFW configuration options.
	 *
	 * @return array $config Array of BGTFW configuration options.
	 */
	public function set_configs( $config ) {
		foreach ( $config['customizer']['controls'] as $index => $control ) {
			if ( strpos( $index, 'sidebar_meta' ) !== false ) {
				continue;
			}

			$config = $this->set_menus( $config, $index, $control );
			$config = $this->set_colors( $config, $index, $control );
		}

		return $config;
	}

	/**
	 * Sets dynamic menu configs.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $config  Array of BGTFW configuration options.
	 * @param string $index   Index of control in configuration.
	 * @param array  $control Control settings from configuration.
	 *
	 * @return array $config Array of BGTFW configuration options.
	 */
	public function set_menus( $config, $index, $control ) {
		if ( isset( $control['settings'] ) &&
			strpos( $control['settings'], 'bgtfw_menu_' ) !== false && ( strpos( $control['settings'], 'main' ) !== false && strpos( $control['settings'], 'sticky-main' ) === false ) ) {
			$menus = $config['menu']['locations'];

			foreach ( $menus as $location => $description ) {

				if ( 'main' !== $location ) {

					// Add controls based on main menu's.
					$new_key = str_replace( 'main', $location, $control['settings'] );

					$toggle_default = null;

					if ( isset( $config['customizer']['controls'][ $new_key ] ) ) {

						// Check if a default has been set for the toggle control.
						if ( strpos( $new_key, '_toggle' ) !== false && isset( $config['customizer']['controls'][ $new_key ]['default'] ) ) {
							if ( true === $config['customizer']['controls'][ $new_key ]['default'] ) {
								$toggle_default = true;
							}
						}

						// Set the passed in configs before merging in main menu dynamic configs.
						$config['customizer']['controls'][ $new_key ] = array_merge( $control, $config['customizer']['controls'][ $new_key ] );
					} else {

						// Set the defaults for this control to main menus.
						$config['customizer']['controls'][ $new_key ] = $control;
					}

					// Update main menu config defaults to location config defaults.
					array_walk_recursive( $config['customizer']['controls'][ $new_key ], function ( &$value ) use ( $location ) {
						if ( is_string( $value ) ) {

							// If used in CSS replace underscores with hyphens.
							if ( strpos( $value, 'sticky-main' ) === false ) {
								if ( strpos( $value, '#main' ) !== false ) {
									$value = str_replace( 'main', str_replace( '_', '-', $location ), $value );
								} else {
									$value = str_replace( 'main', $location, $value );
								}
							}
						}
					} );

					// Only enable hamburgers on main menu unless otherwise explicitly set in configs.
					if ( strpos( $new_key, '_toggle' ) !== false && true !== $toggle_default ) {
						$config['customizer']['controls'][ $new_key ]['default'] = false;
					}
				}
			}
		}

		return $config;
	}

	/**
	 * Sets the default colors for palette selector controls.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $config  Array of BGTFW configuration options.
	 * @param string $index   Index of control in configuration.
	 * @param array  $control Control settings from configuration.
	 *
	 * @return array $config Array of BGTFW configuration options.
	 */
	public function set_colors( $config, $index, $control ) {
		if ( empty( $this->default ) ) {
			$this->default = $this->set_default( $config );
		}

		if ( isset( $control['type'] ) && 'bgtfw-palette-selector' === $control['type'] && isset( $control['default'] ) && strpos( $control['default'], ':' ) === false ) {

			// Allow designers to set 'color-1' instead of needing to know the actual color for each control.
			if ( preg_match( '/^color-([\d]|neutral)/', $control['default'], $color ) ) {
				if ( 'neutral' === $color[1] ) {
					$config['customizer']['controls'][ $index ]['default'] = $control['default'] . ':' . $this->default[0]['neutral-color'];
				} else {
					$config['customizer']['controls'][ $index ]['default'] = $control['default'] . ':' . $this->default[0]['colors'][ $color[1] - 1 ];
				}
			}

			if ( empty( $control['default'] ) ) {
				// Headings default.
				if ( strpos( $control['settings'], 'headings' ) !== false ) {
					$config['customizer']['controls'][ $index ]['default'] = 'color-2:' . $this->default[0]['colors'][1];

				// Links default.
				} elseif ( strpos( $control['settings'], 'links' ) !== false ) {
					$config['customizer']['controls'][ $index ]['default'] = 'color-3:' . $this->default[0]['colors'][2];

				// Main page background color set to neutral if default palette has it.
				} elseif ( 'boldgrid_background_color' === $control['settings'] && isset( $this->default[0]['neutral-color'] ) ) {
					$config['customizer']['controls'][ $index ]['default'] = 'color-neutral:' . $this->default[0]['neutral-color'];

				// All other background color defaults.
				} else {
					$config['customizer']['controls'][ $index ]['default'] = 'color-1:' . $this->default[0]['colors'][0];
				}
			}
		}

		return $config;
	}

	/**
	 * Sets the starter content defaults that haven't been satisfied for a proper
	 * presentation of the starter content.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $config  Array of BGTFW configuration options.
	 * @param string $index   Index of control in configuration.
	 * @param array  $control Control settings from configuration.
	 *
	 * @return array $config Array of BGTFW configuration options.
	 */
	public function set_defaults( $config, $index, $control ) {
		if ( empty( $config['starter-content']['theme_mods'][ $control['settings'] ] ) && 'custom' !== $control['type'] ) {
			$config['starter-content']['theme_mods'][ $control['settings'] ] = $config['customizer']['controls'][ $index ]['default'];
		}

		return $config;
	}

	/**
	 * Adds default values for get_theme_mod calls if no value is
	 * passed.
	 *
	 * @since 2.0.0
	 */
	public function dynamic_theme_mod_filter() {
		global $boldgrid_theme_framework;
		$config = $boldgrid_theme_framework->get_configs();
		$config = $this->set_configs( $config );

		foreach ( $config['customizer']['controls'] as $index => $control ) {
			if ( strpos( $index, 'sidebar_meta' ) !== false || ! isset( $control['settings'] ) ) {
				continue;
			}

			$settings = $control['settings'];

			add_filter( "theme_mod_{$settings}", function( $setting ) use ( $control ) {
				if ( false === $setting && isset( $control['default'] ) ) {
					if ( is_bool( $control['default'] ) ) {

						// Check stored theme_mods in db.
						$slug = get_option( 'stylesheet' );
						$stored = get_option( "theme_mods_{$slug}", array() );
						if ( ! isset( $stored[ $control['settings'] ] ) ) {
							$setting = $control['default'];
						}
					} else {
						if ( 'bgtfw-sortable-accordion' === $control['type'] ) {
							$uid = $control['location'][0];

							foreach ( $control['default'] as $key => $section ) {
								$base = $uid . $key;
								if ( ! empty( $section['items'] ) ) {
									foreach ( $section['items'] as $k => $v ) {
										if ( empty( $v['uid'] ) ) {
											$control['default'][ $key ]['items'][ $k ]['uid'] = $base . $k;
										}
									}
								}
							}
						}

						$setting = $control['default'];
					}
				}
				return $setting;
			} );
		}

		// Loop through any theme mods that have not been defaulted by a control config.
		foreach ( $config['theme-mods'] as $name => $default ) {
			if ( empty( $config['customizer']['controls'][ $name ]['settings'] ) ) {
				add_filter( "theme_mod_{$name}", function( $setting ) use ( $default ) {
					return ( false === $setting ) ? $default : $setting;
				} );
			}
		}
	}

	/**
	 * Add post preview link changeset.
	 *
	 * @since 2.0.0
	 *
	 * @param  string  $link Link.
	 * @param  WP_Post $post Post.
	 * @return string
	 */
	public function add_post_preview_link_changeset( $link, $post ) {

		// Short circuit if link already has changeset uuid applied.
		if ( strpos( $link, 'customize_changeset_uuid' ) === false && ( 'draft' === $post->post_status || 'auto-draft' === $post->post_status ) ) {
			$changeset_uuid = get_post_meta( $post->ID, '_customize_changeset_uuid', true );
			if ( ! empty( $changeset_uuid ) ) {
				$link = esc_url( add_query_arg( 'customize_changeset_uuid', $changeset_uuid, $link ) );
			}
		}

		return $link;
	}
}
