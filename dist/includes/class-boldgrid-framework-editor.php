<?php
/**
 * Class: Boldgrid_Framework_Editor
 *
 * @since 1.0.6
 * @package Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Editor
 * @author BoldGrid <support@boldgrid.com>
 * @link https://boldgrid.com
 */
class Boldgrid_Framework_Editor {

	/**
	 * Global Framework configurations
	 *
	 * @var array $configs
	 * @since 1.0.6
	 */
	protected $configs;

	/**
	 * Pass in configs
	 *
	 * @param array $configs
	 * @since 1.0.6
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Save page title toggle via ajax call.
	 *
	 * @since 1.0.7
	 */
	public function update_page_title_toggle( $post_id, $post ) {
		$post_id = ! empty( $post_id ) ? $post_id : null;

		// If this is a revision, get real post ID
		if ( $parent_id = wp_is_post_revision( $post_id ) ) {
			$post_id = $parent_id;
		}

		$status = isset( $_POST['boldgrid-display-post-title'] ) ? intval( $_POST['boldgrid-display-post-title'] ) : null;
		if ( $post_id && false == is_null( $status ) ) {
			$post_meta = get_post_meta( $post_id );
			if ( ! empty( $post_meta ) ) {
				// save post meta
				update_post_meta( $post_id, 'boldgrid_hide_page_title', $status );
			}
		}

	}

	/**
	 * Add CSS to hide page title.
	 *
	 * @since 1.0.7
	 */
	public function hide_page_title() {
		global $post;
		$inline_css = null;
		if ( $post ) {
			$post_meta = get_post_meta( $post->ID );

			// This was updated to invert logic, from hide page title to display page title
			if ( empty( $post_meta['boldgrid_hide_page_title'][0] ) && isset( $post_meta['boldgrid_hide_page_title'] ) ) {
				// apply some inline styles.
				$inline_css = '#post-' . $post->ID . ' .entry-title { display: none; }' . '#page-id-' .
					 $post->ID . ' .entry-title { display: none; }';

				// Add body class .post-title-hidden.
				add_filter( 'body_class',
					function ( $classes ) {
						$classes[] = 'post-title-hidden';
						return $classes;
                } );
			}
		}

		if ( $inline_css ) {
			wp_add_inline_style( 'style', $inline_css );
		}
	}

	/**
	 * Display a post title display control on the page and post editor.
	 *
	 * @since 1.0.7
	 */
	public function add_post_title_toggle() {
		global $pagenow;

		$post_id = ! empty( $_REQUEST['post'] ) ? $_REQUEST['post'] : null;

		if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {

			if ( false == empty( $post_id ) ) {
				$post = get_post( $post_id );

				if ( ! $post ) {
					return;
				}

				// If the post type is not page or post that do not display.
				if ( false == in_array( $post->post_type, array( 'post', 'page' ) ) ) {
					return;
				}

				$post_meta = get_post_meta( $post->ID );
				$display_page_title = ! empty( $post_meta['boldgrid_hide_page_title'][0] ) || ! isset( $post_meta['boldgrid_hide_page_title'] );
				$template_file = get_post_meta( $post->ID, '_wp_page_template', true );

				// Dont allow modification on home page
				$disabled = '';
				if ( 'page_home.php' == $template_file ) {
					$display_page_title = false;
					$disabled = 'disabled="disabled"';
				}

				$post_type = 'page';
				if ( $post->post_type == 'post' ) {
					$post_type = 'post';
				}
			} else {
				$post_type = ! empty( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : null;
				if ( $post_type != 'page' ) {
					$post_type = 'post';
				}

				$display_page_title = true;
				$disabled = '';
			}

			add_action( 'edit_form_after_title',
				function () use ( $post_type, $display_page_title, $disabled ) {
					$checked = checked( $display_page_title, true, false );
					echo <<<HTML
						<div id="boldgrid-hide-post-title">
							<input style='display:none' type='checkbox' value='0' checked='checked' name='boldgrid-display-post-title'>
							<label>
							<input value="1" name="boldgrid-display-post-title" {$checked} {$disabled} type='checkbox'> Display  
							 $post_type  title </label><span class="dashicons dashicons-editor-help"></span> 
							<span class="spinner"></span>
							<div class='boldgrid-tooltip'>
								<div class="boldgrid-tooltip-arrow">
								</div>
								<div class="boldgrid-tooltip-inner">
									The {$post_type} title displays as a heading at the top of your {$post_type}. 
									Your BoldGrid theme supports this feature. 
								</div>
							</div>
						</div>				
HTML;
            } );
		}
	}

	/**
	 * Theme framework mce plugin responsible for adding inline styles to editor.
	 *
	 * @param array $plugin_array.
	 * @return string
	 * @since 1.0.6
	 */
	public function add_tinymce_plugin( $plugin_array ) {
		$mce_inline_styles = '';
		$mce_inline_styles = apply_filters( 'boldgrid_mce_inline_styles', $mce_inline_styles );

		wp_localize_script( 'mce-view', 'BOLDGRID_THEME_FRAMEWORK',
			array(
				'Editor' => array(
					'mce_inline_styles' => $mce_inline_styles,
				),
				'post_id' => ! empty( $_REQUEST['post'] ) ? $_REQUEST['post'] : null,
			) );

			$editor_js_file = $this->configs['framework']['admin_asset_dir'] . 'js/editor.js';

			$plugin_array['boldgrid_theme_framework'] = $editor_js_file;

			return $plugin_array;
	}

	public function kirki_google_link() {

		/**
		 * Get the array of fields from the Kirki object.
		 */
		$fields = Kirki::$fields;
		/**
		 * Early exit if no fields are found.
		 */
		if ( empty( $fields ) ) {
			return;
		}

		$fonts = array();
		/**
		 * Run a loop for our fields
		 */
		foreach ( $fields as $field ) {
			/**
			 * Sanitize the field
			 */
			$field = Kirki_Field_Sanitize::sanitize_field( $field );
			/**
			 * No reason to proceed any further if no 'output' has been defined
			 * or if it's not defined as an array.
			 */
			if ( ! isset( $field['output'] ) || ! is_array( $field['output'] ) ) {
				continue;
			}
			/**
			 * Run through each of our "output" items in the array separately.
			 */
			foreach ( $field['output'] as $output ) {
				$valid = false;
				/**
				 * If the field-type exists and is set to "typography"
				 * then we need some extra checks to figure out if we need to proceed.
				 */
				if ( isset( $field['type'] ) && 'typography' == $field['type'] ) {
					if ( isset( $field['choices'] ) && isset( $field['choices']['font-family'] ) && $field['choices']['font-family'] ) {
						$valid = true;
					}
				}
				/**
				 * Check if the "property" of this item is related to typography.
				 */
				if ( isset( $output['property'] ) && in_array( $output['property'], array( 'font-family', 'font-weight', 'font-subset' ) ) ) {
					$valid = true;
				}
				/**
				 * If the $valid var is not true, then we don't need to proceed.
				 * Continue to the next item in the array.
				 */
				if ( ! $valid ) {
					continue;
				}

				/**
		 		 * Get the value of this field
		 		 */
		 		$value = Kirki_Values::get_sanitized_field_value( $field );
				/**
				 * Typography fields arew a bit more complex than usual fields.
				 * We need to get the sub-items of the array
				 * and then base our calculations on these.
				 */
				if ( 'typography' == $field['type'] ) {
					/**
					 * Add the font-family to the array
					 */
					if ( isset( $value['font-family'] ) ) {
						$fonts[]['font-family'] = $value['font-family'];
					}
					/**
					 * Add the font-weight to the array
					 */
					if ( isset( $value['font-weight'] ) ) {
						$fonts[]['font-weight'] = $value['font-weight'];
					}
				} /**
				 * This is not a typography field so we can proceed.
				 * This is a lot simple. :)
				 */
				else {

					if ( 'font-family' == $output['property'] ) {
						/**
						  * Add the font-family to the array
						  */
						$fonts[]['font-family'] = $value;
					} else if ( 'font-weight' == $output['property'] ) {
						/**
						 * Add font-weight to the array
						 */
						$fonts[]['font-weight'] = $value;
					} else if ( 'font-subset' == $output['property'] ) {
						/**
						 * add font subsets to the array
						 */
						$fonts[]['subsets'] = $value;

					}
				}
			}
		}
		/**
		 * Start going through all the items in the $fonts array.
		 */
		foreach ( $fonts as $font ) {
			/**
			 * Do we have font-families?
			 */
			if ( isset( $font['font-family'] ) ) {
				$font_families   = ( ! isset( $font_families ) ) ? array() : $font_families;
				$font_families[] = $font['font-family'];
				/**
				 * Determine if we need to create a google-fonts link or not.
				 */
				if ( ! isset( $has_google_font ) ) {
					if ( Kirki_Toolkit::fonts()->is_google_font( $font['font-family'] ) ) {
						$has_google_font = true;
					}
				}
			}
			/**
			 * Do we have font-weights?
			 */
			if ( isset( $font['font-weight'] ) ) {
				$font_weights   = ( ! isset( $font_weights ) ) ? array() : $font_weights;
				$font_weights[] = $font['font-weight'];
			}
			/**
			 * Do we have font-subsets?
			 */
			if ( isset( $font['subsets'] ) ) {
				$font_subsets   = ( ! isset( $font_subsets ) ) ? array() : $font_subsets;
				$font_subsets[] = $font['subsets'];
			}
		}
		/**
		 * Make sure there are no empty values and define some sane defaults.
		 */
		$font_families = ( ! isset( $font_families ) || empty( $font_families ) ) ? false : $font_families;
		$font_weights  = ( ! isset( $font_weights ) || empty( $font_weights ) ) ? array( '400' ) : $font_weights;
		$font_subsets  = ( ! isset( $font_subsets ) || empty( $font_subsets ) ) ? array( 'all' ) : $font_subsets;
		/**
		 * Get rid of duplicate values
		 */
		if ( is_array( $font_families ) && ! empty( $font_families ) ) {
			$font_families = array_unique( $font_families );
		}
		if ( is_array( $font_weights ) && ! empty( $font_weights ) ) {
			$font_weights  = array_unique( $font_weights );
		}
		if ( is_array( $font_subsets ) && ! empty( $font_subsets ) ) {
			$font_subsets  = array_unique( $font_subsets );
		}

		if ( ! isset( $has_google_font ) || ! $has_google_font ) {
			$font_families = false;
		}

		// Return the font URL.
		return ( $font_families ) ? Kirki_Toolkit::fonts()->get_google_font_uri( $font_families, $font_weights, $font_subsets ) : false;

	}

	/**
	 * Enqueue Google fonts if necessary
	 */
	public function add_google_fonts() {

		$config = apply_filters( 'kirki/config', array() );

		/**
		 * If we have set $config['disable_google_fonts'] to true
		 * then do not proceed any further.
		 */
		if ( isset( $config['disable_google_fonts'] ) && true == $config['disable_google_fonts'] ) {
			return;
		}

		if ( $this->kirki_google_link() ) {
			$google_link = str_replace( '%3A', ':', $this->kirki_google_link() );
			add_editor_style( $google_link );
		}
	}
}
