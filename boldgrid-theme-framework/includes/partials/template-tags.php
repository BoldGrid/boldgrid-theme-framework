<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package BoldGrid
 */

if ( ! function_exists( 'boldgrid_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function boldgrid_paging_nav() {
	global $wp_query;
	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	do_action( 'bgtfw_pagination_display' );
}
endif;

if ( ! function_exists( 'boldgrid_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function boldgrid_post_nav() {
	global $boldgrid_theme_framework;
	$configs = $boldgrid_theme_framework->get_configs();

	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	$nav_classes = $configs['template']['post_navigation']['post_nav_classes'];

	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="sr-only"><?php _e( 'Post navigation', 'bgtfw' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="' . $nav_classes['previous'] . '">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'bgtfw' ) );
				next_post_link( '<div class="' . $nav_classes['next'] . '">%link</div>',     _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link',     'bgtfw' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'boldgrid_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function boldgrid_posted_on() {

	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	// Posted on date format.
	$format = is_single() ? get_theme_mod( 'bgtfw_posts_meta_format' ) : get_theme_mod( 'bgtfw_blog_post_header_meta_format' );

	if ( 'timeago' === $format ) {
		$posted_on = sprintf(
			_x( 'Posted %s ago', '%s = human-readable time difference', 'bgtfw' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . '</a>'
		);
	}

	if ( 'date' === $format ) {
		$posted_on = sprintf(
			_x( 'Posted on %s', 'post date', 'bgtfw' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);
	}

	$byline = sprintf(
		_x( 'by %s', 'post author', 'bgtfw' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on ' . esc_attr( $format ) . '">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';
}
endif;

if ( ! function_exists( 'boldgrid_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function boldgrid_entry_footer() {

	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {

		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( ', ' );
		$categories_count = count( explode( ', ', $categories_list ) );
		if ( $categories_list && boldgrid_categorized_blog() ) {
			$class = 'singular';

			$icon = is_single() ? get_theme_mod( 'bgtfw_posts_cat_icon' ) : get_theme_mod( 'bgtfw_blog_post_cat_icon' );
			$icon = '<i class="fa fa-fw fa-' . $icon . '" aria-hidden="true"></i>';

			if ( $categories_count > 1 ) {
				$icon = is_single() ? get_theme_mod( 'bgtfw_posts_cats_icon' ) : get_theme_mod( 'bgtfw_blog_post_cats_icon' );
				$icon = '<i class="fa fa-fw fa-' . $icon . '" aria-hidden="true"></i>';
				$class = 'multiple';
			}

			printf( '<span class="cat-links %1$s">%2$s %3$s</span>', $class, $icon, $categories_list );
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', ', ' );

		if ( $tags_list ) {
			$icon = is_single() ? get_theme_mod( 'bgtfw_posts_tag_icon' ) : get_theme_mod( 'bgtfw_blog_post_tag_icon' );
			$icon = '<i class="fa fa-fw fa-' . $icon . '" aria-hidden="true"></i>';
			$class = 'singular';
			$tags_count = count( explode( ', ', $tags_list ) );

			if ( $tags_count > 1 ) {
				$icon = is_single() ? get_theme_mod( 'bgtfw_posts_tags_icon' ) : get_theme_mod( 'bgtfw_blog_post_tags_icon' );
				$icon = '<i class="fa fa-fw fa-' . $icon . '" aria-hidden="true"></i>';
				$class = 'multiple';
			}

			printf( '<span class="tags-links %1$s">%2$s %3$s</span>', $class, $icon, $tags_list );
		}
	}

	$comment_count = get_comments_number();

	if ( ! is_single() && ! post_password_required() && ( comments_open() || $comment_count ) ) {

		$icon = '';
		$class = 'comments-link';

		if ( $comment_count > 1 ) {
			$icon = '<i class="fa fa-fw fa-' . get_theme_mod( 'bgtfw_blog_post_comments_icon' ) . '" aria-hidden="true"></i> ';
			$class .= ' multiple';
		} else {
			$icon = '<i class="fa fa-fw fa-' . get_theme_mod( 'bgtfw_blog_post_comment_icon' ) . '" aria-hidden="true"></i> ';
			$class .= ' singular';
		}

		echo '<span class="' . $class . '">';

		echo $icon;

		comments_popup_link( __( 'Leave a comment', 'bgtfw' ), __( '1 Comment', 'bgtfw' ), __( '% Comments', 'bgtfw' ) );
		echo '</span>';
	}

	bgtfw_edit_post_link();
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function boldgrid_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'boldgrid_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'boldgrid_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so boldgrid_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so boldgrid_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in boldgrid_categorized_blog.
 */
function boldgrid_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'boldgrid_categories' );
}
add_action( 'edit_category', 'boldgrid_category_transient_flusher' );
add_action( 'save_post',     'boldgrid_category_transient_flusher' );

if ( ! function_exists( 'bgtfw_edit_post_link' ) ) {

	/**
	 * Custom edit post links used.
	 */
	function bgtfw_edit_post_link() {

		global $boldgrid_theme_framework;
		$configs = $boldgrid_theme_framework->get_configs();

		// Check that there is an edit post link.
		if ( get_edit_post_link() ) {

			// Check configs for the edit post link buttons to be enabled.
			if ( true === $configs['edit-post-links']['enabled'] ) {

				// Customized post links.
				edit_post_link(

					/* translators: %s: Name of current post. */
					sprintf( __( 'Click to edit %s.', 'bgtfw' ), get_the_title() ),
					'<span class="bgtfw-edit-link">',
					'</span>'
				);
			} else {

				// Default post links.
				edit_post_link(
					sprintf(
						wp_kses(

							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'bgtfw' ),
							array(
								'span' => array(
									'class' => array(),
								),
								'i' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					),
					'<i class="fa fa-pencil">',
					'</i>'
				);
			}
		}
	}
}

if ( ! function_exists( 'bgtfw_get_edit_link' ) ) {

	/**
	 * Generates edit link buttons with specific URL.
	 *
	 * @param string $url URL to direct to.
	 * @param string $text Text to display for link title attributes.
	 * @param string $before HTML before link.
	 * @param string $after HTML after link.
	 *
	 * @return string $link HTML to display edit link button.
	 */
	function bgtfw_get_edit_link( $url = null, $text = 'Click to edit.', $before = '<span class="bgtfw-edit-link">', $after = '</span>' ) {
		if ( ! $url ) {
			return;
		}

		$link = wp_kses_post( $before );

		$link .= sprintf(
			'<a href="%1$s" aria-label="%2$s" title="%2$s" class="bgtfw-edit-link-button">' .
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">' .
					'<path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"></path>' .
				'</svg>' .
			'</a>',
			esc_url( $url ),
			esc_html( $text )
		);

		$link .= wp_kses_post( $after );

		return apply_filters( 'bgtfw_get_edit_link', $link, $url, $text, $before, $after );
	}
}

if ( ! function_exists( 'bgtfw_edit_link' ) ) {

	/**
	 * Generates edit link buttons with specific URL.
	 *
	 * @param string $url URL to direct to.
	 */
	function bgtfw_edit_link( $url ) {
		$link = bgtfw_get_edit_link( $url );
		echo apply_filters( 'bgtfw_edit_link', $link );
	}
}

/**
 * Output the view cart button.
 *
 * @subpackage	Cart
 */
function woocommerce_widget_shopping_cart_button_view_cart() {
	?>
		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn button-primary wc-forward"><?php _e( 'View Cart', 'woocommerce' ); ?></a>
	<?php
}

/**
 * Output the proceed to checkout button.
 *
 * @subpackage	Cart
 */
function woocommerce_widget_shopping_cart_proceed_to_checkout() {
	?>
		<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn button-primary checkout wc-forward"><?php _e( 'Checkout', 'woocommerce' ); ?></a>
	<?php
}

/**
 * Provides a template tag to check for template including a sidebar layout or not.
 *
 * @since 2.0.0
 */
function is_not_bgtfw_sidebar_layout() {
	if ( is_home() ) {
		// Default homepage
		$layout = get_theme_mod( 'bgtfw_blog_blog_page_sidebar', get_theme_mod( 'bgtfw_layout_blog', 'no-sidebar' ) );
	} else {
		$layout = get_page_template_slug();

		if ( empty( $layout ) ) {
			$type = 'page' === get_post_type() ? 'page' : 'blog';
			$layout = get_theme_mod( 'bgtfw_layout_' . $type, '' );
		}

		$classes[] = sanitize_html_class( $layout );
	}

	return ( 'no-sidebar' === $layout ) || empty( $layout ) ? true : false;
}

/**
 * Responsible for displaying sidebar/widget areas in a bgtfw theme.
 *
 * @since 2.0.0
 *
 * @param string $sidebar_id The sidebar ID to create an area for.
 * @param bool   $help       Whether or not to display help text inside of widget area.
 *
 * @return void Prints the sidebar markup.
 */
function bgtfw_widget( $sidebar_id, $help = null ) {
	if ( ! empty( $help ) ) {
		$tmp = true;
	}

	global $wp;

	// Link to the widgets section in the customizer.
	$current_page = add_query_arg( $_GET, home_url( $wp->request ) );
	$link = esc_url(
		add_query_arg(
			array(
				'url' => urlencode( $current_page ),
				array(
					'autofocus' => array(
						'control' => 'sidebars_widgets[' . $sidebar_id . ']',
					),
				),
				'return' => $current_page,
			),
			wp_customize_url()
		)
	);

	// Add some padding just for the background color to be visible in certain situations.
	$style = 'padding-top: 15px; padding-bottom: 15px;';
	$sidebar_meta = get_theme_mod( 'sidebar_meta' );

	$color_class = '';
	$classes = array();

	global $boldgrid_theme_framework;
	$configs = $boldgrid_theme_framework->get_configs();

	$meta = new Boldgrid_Framework_Customizer_Widget_Meta( $configs );

	if ( empty( $sidebar_meta[ $sidebar_id ] ) ) {
		$sidebar_meta[ $sidebar_id ] = $meta->get_sidebar_defaults( $sidebar_id );
	}

	$color = empty( $sidebar_meta[ $sidebar_id ]['background_color'] ) ? $meta->get_sidebar_defaults( $sidebar_id, 'background_color' ) : $sidebar_meta[ $sidebar_id ]['background_color'] ;
	$color_class = strtok( $color, ':' );
	if ( strpos( $color_class, 'neutral' ) === false ) {
		$color_class = str_replace( '-', '', $color_class );
	}
	$classes[] = $color_class . '-background-color';
	$classes[] = $color_class . '-text-default';

	$color = empty( $sidebar_meta[ $sidebar_id ]['links_color'] ) ? $meta->get_sidebar_defaults( $sidebar_id, 'links_color' ) : $sidebar_meta[ $sidebar_id ]['links_color'];
	$color_class = strtok( $color, ':' );
	$classes[] = $color_class . '-link-color';

?>
	<aside id="<?php echo sanitize_title( $sidebar_id ); ?>" class="sidebar container-fluid <?php echo esc_attr( implode( ' ', $classes ) ); ?>" role="complementary" style="<?php echo esc_attr( $style ); ?>">
		<?php dynamic_sidebar( $sidebar_id ); ?>
		<?php if ( current_user_can( 'edit_pages' ) && ! is_customize_preview() && true === $tmp ) : ?>
			<?php if ( ! is_active_sidebar( $sidebar_id ) ) : ?>
				<div class="empty-sidebar-message">
					<?php if ( empty( $sidebar_meta[ $sidebar_id ]['title'] ) ) : ?>
						<h2><?php _e( 'Empty Sidebar', 'bgtfw' ); ?></h2>
					<?php endif; ?>
					<p><?php _e( "This sidebar doesn't have any widgets assigned to it yet.", 'bgtfw' ); ?></p>
					<p><a href="<?php echo esc_url( $link ) ?>"><i class="fa fa-plus-square" aria-hidden="true"></i> <?php _e( 'Add widgets here.', 'bgtfw' ) ?></a></p>
				</div>
				<?php elseif ( is_active_sidebar( $sidebar_id ) ) : ?>
					<div class="add-widget-message">
						<p><a href="<?php echo esc_url( $link ) ?>"><i class="fa fa-plus-square" aria-hidden="true"></i> <?php _e( 'Add another widget.', 'bgtfw' ) ?></a></p>
					</div>
			<?php endif; ?>
		<?php endif; ?>
	</aside>
<?php
}

/**
 * Gets the styles for a feature image set as a background image on an element.
 *
 * @since 2.0.0
 *
 * @return string $style The inline styles to apply to an element.
 */
function bgtfw_get_featured_img_bg( $post_id, $theme_mod = false ) {
	global $wp_query;
	$style = '';
	$post_id = ( int ) $post_id;

	if ( has_post_thumbnail( $post_id ) ) {
		$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
		if ( is_home() || is_archive() ) {
			if ( true === $theme_mod && 'show' === get_theme_mod( 'bgtfw_blog_post_header_feat_image_display' ) ) {
				if ( ( 'background' === get_theme_mod( 'bgtfw_blog_post_header_feat_image_position' ) ) || ( int ) get_option( 'page_for_posts', true ) === $post_id ) {

					// Get user defined header color.
					$theme_mod = 'bgtfw_blog_header_background_color';

					if ( ( int ) get_option( 'page_for_posts', true ) === $post_id || is_page( $post_id ) || is_archive( $post_id ) ) {

						// Use background color setting for page as the header background by default.
						$theme_mod = 'boldgrid_background_color';

						// Check ifthe background color is overridden by an overlay color.
						if ( 'image' === get_theme_mod( 'boldgrid_background_type' ) &&
							'' !== get_theme_mod( 'background_image' ) &&
							true === get_theme_mod( 'bgtfw_background_overlay' ) ) {
								$theme_mod = 'bgtfw_background_overlay_color';
						}
					}

					$color = get_theme_mod( $theme_mod );
					$color = explode( ':', $color );
					$color = array_pop( $color );

					// Instantiate the object.
					$color = ariColor::newColor( $color );
					$color = $color->getNew( 'alpha', .7 )->toCSS( 'rgba' );

					$style = 'style="background: linear-gradient(' . $color . ', ' . $color . '), url(' . $img[0] . '); background-size: cover; background-position: center center;"';
				} else {
					$style = 'style="background: url(' . $img[0] . '); background-size: cover; background-position: center center;"';
				}
			}
		} else {
			// Get user defined header color.
			$color = get_theme_mod( 'bgtfw_blog_header_background_color' );
			$color = explode( ':', $color );
			$color = array_pop( $color );

			// Instantiate the object.
			$color = ariColor::newColor( $color );
			$color = $color->getNew( 'alpha', .7 )->toCSS( 'rgba' );

			$style = 'style="background: linear-gradient(' . $color . ', ' . $color . '), url(' . $img[0] . '); background-size: cover; background-position: center center;"';
		}
	}

	return $style;
}

/**
 * Echos the style for a feature image set as a background image on an element.
 *
 * @since 2.0.0
 */
function bgtfw_featured_img_bg( $post_id, $theme_mod = false ) {
	echo bgtfw_get_featured_img_bg( $post_id, $theme_mod );
}

/**
 * Changing excerpt more.
 *
 * @since 2.0.0
 *
 * @param  string $more The string shown within the more link.
 * @return string
 */
function bgtfw_excerpt_more( $more ) {
	global $post;
	return '… <div class="read-more"><a class="' . get_theme_mod( 'bgtfw_blog_post_readmore_type' ) . '" href="' . get_permalink( $post->ID ) . '">' . get_theme_mod( 'bgtfw_blog_post_readmore_text' ) . '</a></div>';
}

add_filter( 'excerpt_more', 'bgtfw_excerpt_more' );
