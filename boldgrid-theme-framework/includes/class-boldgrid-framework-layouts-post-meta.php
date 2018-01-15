<?php
/**
 * Class: Boldgrid_Framework_Layouts_Post_Meta
 *
 * @since 2.0.0
 * @package Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Layouts
 * @author BoldGrid <support@boldgrid.com>
 * @link https://boldgrid.com
 */

/**
 * Class: Boldgrid_Framework_Layouts_Post_Meta
 *
 * Responsible for the layouts post meta box
 *
 * @since 2.0.0
 */
class Boldgrid_Framework_Layouts_Post_Meta {

	/**
	 * Global Framework configurations
	 *
	 * @var array $configs
	 * @since 2.0.0
	 */
	protected $configs;

	/**
	 * Pass in configs
	 *
	 * @param array $configs Array of bgtfw configuration options.
	 * @since 2.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Adds the meta box to the page screen.
	 *
	 * @since 2.0.0
	 *
	 * @param String $post_type The post type being modified.
	 */
	public function add_meta_box( $post_type ) {

		// remove the default
		remove_meta_box( 'pageparentdiv', array( 'page', 'post' ), 'side' );

		// add our own
		add_meta_box(
			'bgtfw-attributes-meta-box',
			'page' == $post_type ? __( 'Page Attributes', 'bgtfw' ) : __( 'Post Attributes', 'bgtfw' ),
			array( $this, 'meta_box_callback' ),
			array( 'page', 'post' ),
			'side',
			'low'
		);
	}

	/**
	 * Renders the page/post layout radio selection controls in the metabox.
	 *
	 * @since 2.0.0
	 */
	public function layout_selection( $post_type = 'page' ) {
		$templates = get_page_templates( null, $post_type );
		ksort( $templates );

		/* Get current post/entry layout */
		$post_layout = get_page_template_slug( get_queried_object_id() );

		$div_class = 'post-layout theme-layouts-thumbnail-wrap';

		if ( ! empty( $post_layout ) ) {
			$div_class .= ' post-layout-selected';
		}
		wp_nonce_field( basename( __FILE__ ), 'theme-layouts-nonce' ); ?>
		<div id="post-layout" class="<?php echo esc_attr( $div_class ); ?>">
			<div id="customize-control-bgtfw_layout_page" class="post-layout-wrap customize-control customize-control-kirki customize-control-kirki-radio">
			<?php
				/**
				 * Filters the title of the default page template displayed in the drop-down.
				 *
				 * @since 4.1.0
				 *
				 * @param string $label   The display value for the default page template title.
				 * @param string $context Where the option label is displayed. Possible values
				 *                        include 'meta-box' or 'quick-edit'.
				 */
				$default_title = apply_filters( 'default_page_template_title',  __( 'Global Theme Default', 'bgtfw' ), 'meta-box' );
				$type = 'page' === $post_type ? $post_type : 'blog';
				$global_template = get_theme_mod( 'bgtfw_layout_' . $type, $default_title );
				$checked = ! in_array( $post_layout, $templates ) ? 'checked="checked"' : '';
				$title = $default_title;
				$subtitle = '';

				if ( $global_template !== $default_title ) {
					$k = array_search( $global_template, $templates );
					$title = '<div class="template-name">' . esc_html( $k ) . '</div>';
					$subtitle = '<div class="template-subtitle">' . esc_html( $default_title ) . '</div>';
				}
			?>
			<label class="theme-layout-label layout-default layout-selected">
				<input type="radio" name="page_template" class="theme-layout-input" value="default" <?php echo $checked; ?> />
				<?php echo $title; ?>
			</label>
			<?php echo $subtitle; ?>
		<?php
		foreach ( array_keys( $templates ) as $template ) {

			/* Set empty value for Layout Global/Default */
			$layout_value = $templates[ $template ];
			if ( 'default' === $template ) {
				$layout_value = '';
			}

			/* Label class */
			$label_class = 'theme-layout-label';
			if ( 'default' === $templates[ $template ] ) {
				$label_class .= ' layout-default'; // hide it!
			}
			if ( $post_layout === $templates[ $template ] ) {
				$label_class .= ' layout-selected';
			}

			/* Label */
			$layout_label = $template;
			?>
			<label class="<?php echo esc_attr( $label_class ); ?>">
				<input type="radio" name="page_template" class="theme-layout-input" value="<?php echo esc_attr( $templates[ $template ] ); ?>" <?php checked( $post_layout, $layout_value ); ?> />
				<?php echo $template; ?>
			</label>
	<?php }?>
	</div>
	</div>
	<?php
	}

	/**
	 * Callback function for our meta box.  Echos out the content.
	 *
	 * @since 2.0.0
	 */
	public function meta_box_callback( $post ) {
		if ( count( get_page_templates( $post ) ) > 0 && get_option( 'page_for_posts' ) != $post->ID ) :
			$template = ! empty( $post->page_template ) ? $post->page_template : false;
			?>
			<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="page_template"><?php _e( 'Template' ); ?></label><?php
				/**
				 * Fires immediately after the label inside the 'Template' section
				 * of the 'Page Attributes' meta box.
				 *
				 * @since 4.4.0
				 *
				 * @param string  $template The template used for the current post.
				 * @param WP_Post $post     The current post.
				 */
				do_action( 'page_attributes_meta_box_template', $template, $post );
			?></p>

			<?php $this->layout_selection( $post->post_type ); ?>
		<?php endif; ?>

		<?php
			if ( is_post_type_hierarchical( $post->post_type ) ) : ?>
				<div class="advanced-toggle dashicons-before dashicons-admin-tools">
					<p>Advanced Options</p>
					<span class="toggle-indicator" aria-hidden="true"></span>
				</div>
				<div class="post-attributes-advanced-wrap hide-if-js"><?php
				$dropdown_args = array(
					'post_type'        => $post->post_type,
					'exclude_tree'     => $post->ID,
					'selected'         => $post->post_parent,
					'name'             => 'parent_id',
					'show_option_none' => __( '(no parent)' ),
					'sort_column'      => 'menu_order, post_title',
					'echo'             => 0,
				);

				/**
				 * Filters the arguments used to generate a Pages drop-down element.
				 *
				 * @since 2.0.0
				 *
				 * @see wp_dropdown_pages()
				 *
				 * @param array   $dropdown_args Array of arguments used to generate the pages drop-down.
				 * @param WP_Post $post          The current WP_Post object.
				 */
				$dropdown_args = apply_filters( 'page_attributes_dropdown_pages_args', $dropdown_args, $post );
				$pages = wp_dropdown_pages( $dropdown_args );
				if ( ! empty( $pages ) ) : ?>
					<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="parent_id"><?php _e( 'Parent' ); ?></label></p>
					<?php echo $pages; ?>
					<?php
				endif; // end empty pages check
			endif;  // end hierarchical check.
		?>
		<?php if ( post_type_supports( $post->post_type, 'page-attributes' ) ) : ?>
		<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="menu_order"><?php _e( 'Order' ); ?></label></p>
		<input name="menu_order" type="text" size="4" id="menu_order" value="<?php echo esc_attr( $post->menu_order ); ?>" />
		<?php if ( 'page' == $post->post_type && get_current_screen()->get_help_tabs() ) : ?>
		<p><?php _e( 'Need help? Use the Help tab above the screen title.' ); ?></p>
		<?php endif; ?>
		</div>
		<?php endif; ?>
	<?php
	}

	/**
	 * Styles for page attributes metabox.
	 *
	 * @since 2.0.0
	 */
	public function styles() {
		?>
		<style id="bgtfw-page-attributes">
			/*--------------------------------------------------------------
			# Customizer Sidebar Controls
			--------------------------------------------------------------*/
			#customize-control-bgtfw_layout_blog.customize-control-kirki-radio label,
			#customize-control-bgtfw_layout_page.customize-control-kirki-radio label {
				display: flex;
				align-items: center;
				opacity: .8;
				-webkit-transition: opacity 200ms ease-out;
				-moz-transition: opacity 200ms ease-out;
				-o-transition: opacity 200ms ease-out;
				transition: opacity 200ms ease-out;
			}
			#customize-control-bgtfw_layout_blog.customize-control-kirki-radio label:hover,
			#customize-control-bgtfw_layout_page.customize-control-kirki-radio label:hover {
				opacity: 1;
				-webkit-transition: opacity 300ms ease-in;
				-moz-transition: opacity 300ms ease-in;
				-o-transition: opacity 300ms ease-in;
				transition: opacity 300ms ease-in;
			}
			#customize-control-bgtfw_layout_blog.customize-control-kirki-radio label input[type="radio"],
			#customize-control-bgtfw_layout_page.customize-control-kirki-radio label input[type="radio"] {
				min-width: 32px;
				min-height: 32px;
				font-size: inherit;
				border: none;
				border-radius: 3px;
				background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32'%3E%3Cpath fill='%239b9b9b' d='M2.12 29.96h27.84V2.12H2.12v27.84zM.04 1.26C.04.6.58.04 1.26.04H30.8c.7 0 1.24.54 1.24 1.22V30.8c0 .7-.55 1.24-1.23 1.24H1.28C.6 32.04.04 31.5.04 30.8V1.27z'/%3E%3Cpath fill='%239b9b9b' d='M22 1.6h8.4v28.8H22V1.6z'/%3E%3C/svg%3E");
				background-color: transparent;
				display: flex;
				margin: 0;
				outline: 0;
				-webkit-appearance: none;
				appearance: none;
				line-height: 1.1;
				margin: 4px 12px 4px 0;
			}
			#customize-control-bgtfw_layout_blog.customize-control-kirki-radio label input[type="radio"]:before,
			#customize-control-bgtfw_layout_page.customize-control-kirki-radio label input[type="radio"]:before {
				margin: 0;
			}
			#customize-control-bgtfw_layout_blog.customize-control-kirki-radio label input[type="radio"]:hover,
			#customize-control-bgtfw_layout_blog.customize-control-kirki-radio > label input[type="radio"]:focus,
			#customize-control-bgtfw_layout_blog.customize-control-kirki-radio > label input[type="radio"]:checked,
			#customize-control-bgtfw_layout_page.customize-control-kirki-radio label input[type="radio"]:hover,
			#customize-control-bgtfw_layout_page.customize-control-kirki-radio > label input[type="radio"]:focus,
			#customize-control-bgtfw_layout_page.customize-control-kirki-radio > label input[type="radio"]:checked {
				background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32'%3E%3Cpath fill='%23444' d='M2.12 29.96h27.84V2.12H2.12v27.84zM.04 1.26C.04.6.58.04 1.26.04H30.8c.7 0 1.24.54 1.24 1.22V30.8c0 .7-.55 1.24-1.23 1.24H1.28C.6 32.04.04 31.5.04 30.8V1.27z'/%3E%3Cpath fill='%23444' d='M22 1.6h8.4v28.8H22V1.6z'/%3E%3C/svg%3E");
			}
			#customize-control-bgtfw_layout_blog.customize-control-kirki-radio label input[type="radio"]:before,
			#customize-control-bgtfw_layout_page.customize-control-kirki-radio label input[type="radio"]:before {
				background-color: transparent;
				border-radius: 0;
				width: 0;
				height: 0;
				content: "";
				opacity: 1;
			}
			#customize-control-bgtfw_layout_blog.customize-control-kirki-radio label input[type="radio"]:checked:after,
			#customize-control-bgtfw_layout_page.customize-control-kirki-radio label input[type="radio"]:checked:after {
				content: "\f147";
				display: flex;
				font-family: dashicons;
				color: green;
				text-shadow: -1px -1px 0 #efefef, 1px -1px 0 #efefef, -1px 1px 0 #efefef, 1px 1px 0 #efefef;
				align-items: center;
				justify-content: center;
				font-size: 42px;
				min-width: 32px;
				min-height: 32px;
				margin-top: 2px;
			}
			#customize-control-bgtfw_layout_blog.customize-control-kirki-radio label input[type="radio"][value=left-sidebar],
			#customize-control-bgtfw_layout_page.customize-control-kirki-radio label input[type="radio"][value=left-sidebar] {
				transform: rotate(180deg);
				transform-origin: 50% 50%;
			}
			#customize-control-bgtfw_layout_blog.customize-control-kirki-radio label input[type="radio"][value=left-sidebar]:checked:after,
			#customize-control-bgtfw_layout_page.customize-control-kirki-radio label input[type="radio"][value=left-sidebar]:checked:after {
				transform: rotate(180deg);
				transform-origin: 50% 50%;
				margin-top: -2px;
			}
			#customize-control-bgtfw_layout_blog.customize-control-kirki-radio label input[type="radio"][value=no-sidebar],
			#customize-control-bgtfw_layout_page.customize-control-kirki-radio label input[type="radio"][value=no-sidebar] {
				background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32'%3E%3Cpath fill='%239b9b9b' d='M2.12 29.96h27.84V2.12H2.12v27.84zM.04 1.26C.04.6.58.04 1.26.04H30.8c.7 0 1.24.54 1.24 1.22V30.8c0 .7-.55 1.24-1.23 1.24H1.28C.6 32.04.04 31.5.04 30.8V1.27z'/%3E%3C/svg%3E");
			}
			#customize-control-bgtfw_layout_blog.customize-control-kirki-radio label input[type="radio"][value=no-sidebar]:hover, #customize-control-bgtfw_layout_page.customize-control-kirki-radio > label input[type="radio"][value=no-sidebar]:focus, #customize-control-bgtfw_layout_page.customize-control-kirki-radio > label input[type="radio"][value=no-sidebar]:checked,
			#customize-control-bgtfw_layout_page.customize-control-kirki-radio label input[type="radio"][value=no-sidebar]:hover, #customize-control-bgtfw_layout_page.customize-control-kirki-radio > label input[type="radio"][value=no-sidebar]:focus, #customize-control-bgtfw_layout_page.customize-control-kirki-radio > label input[type="radio"][value=no-sidebar]:checked {
				background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32'%3E%3Cpath fill='%23444' d='M2.12 29.96h27.84V2.12H2.12v27.84zM.04 1.26C.04.6.58.04 1.26.04H30.8c.7 0 1.24.54 1.24 1.22V30.8c0 .7-.55 1.24-1.23 1.24H1.28C.6 32.04.04 31.5.04 30.8V1.27z'/%3E%3C/svg%3E");
			}
			#bgtfw-attributes-meta-box .advanced-toggle:before {
				margin: 6px 6px 0 -6px;
				font-size: 20px;
				line-height: 1.5;
			}
			#bgtfw-attributes-meta-box .advanced-toggle p {
				display: inline-block;
			}
			#bgtfw-attributes-meta-box .advanced-toggle {
				opacity: .7;
				margin-top: 12px;
				margin-bottom: -12px;
				border-top: 1px solid #e5e5e5;
				text-align: center;
			}
			#bgtfw-attributes-meta-box .advanced-toggle:hover {
				cursor: pointer;
				opacity: 1;
			}
			.advanced-toggle .toggle-indicator:before {
				transition: 200ms ease-in-out;
				transform: rotate(180deg);
				position: relative;
				top: 6px;
			}
			.advanced-toggle.open .toggle-indicator:before {
				transition: 200ms ease-in-out;
				transform: rotate(0deg);
			}
			.template-name {
				width: 100%;
			}
			.layout-default > .template-name {
				margin-top: -12px;
			}
			.template-subtitle {
				font-size: 10px;
				font-style: italic;
				color: #a2a2a2;
				position: relative;
				margin-left: 42px;
				margin-top: -18px;
			}
			/* RTL Styles */
			.rtl #customize-control-bgtfw_layout_blog.customize-control-kirki-radio label input[type="radio"],
			.rtl #customize-control-bgtfw_layout_page.customize-control-kirki-radio label input[type="radio"] {
				margin: 4px 0 4px 12px;
			}
			.rtl .template-subtitle {
				margin-right: 42px;
			}
			.rtl #bgtfw-attributes-meta-box .advanced-toggle:before {
				margin: 6px -6px 0 6px;
			}
		</style>
		<?php
	}

	/**
	 * Scripts for page attributes meta box.
	 *
	 * @since 2.0.0
	 */
	public function scripts() {
		?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				$( '.theme-layout-input' ).click( function() {
					/* if it's already selected, remove it and select default. */
					if ( $( this ).parent( '.theme-layout-label' ).hasClass( 'layout-selected' ) ){
						$( '.layout-default .theme-layout-input' ).attr( 'checked', 'checked' );
						$( this ).parent( '.theme-layout-label' ).removeClass( 'layout-selected' );
					} else{
						$( this ).parent( '.theme-layout-label' ).siblings( '.theme-layout-label' ).removeClass( 'layout-selected' );
						$( this ).parent( '.theme-layout-label' ).addClass( 'layout-selected' );
					}
					/* if a layout is selected, add wrapper class */
					if ( $( '.layout-selected' ).length ) {
						$( '.post-layout' ).addClass( 'post-layout-selected' );
					} else {
						$( '.post-layout' ).removeClass( 'post-layout-selected' );
					}
				});
				$( '#bgtfw-attributes-meta-box .advanced-toggle' ).on( 'click', function() {
					$( '.post-attributes-advanced-wrap' ).slideToggle();
					$( this ).toggleClass( 'open' );

				});
			});
		</script>
		<?php
	}
}
