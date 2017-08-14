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
	global $boldgrid_theme_framework;
	$configs = $boldgrid_theme_framework->get_configs();

	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	$nav_classes = $configs['template']['post_navigation']['paging_nav_classes'];

	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h2 class="sr-only"><?php _e( 'Posts navigation', 'bgtfw' ); ?></h2>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="<?php echo $nav_classes['next'] ?>"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'bgtfw' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="<?php echo $nav_classes['previous'] ?>"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'bgtfw' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
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
	global $boldgrid_theme_framework;
	$configs = $boldgrid_theme_framework->get_configs();

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
	$format = ! empty( $configs['template']['archives']['posted-on']['format'] ) ? $configs['template']['archives']['posted-on']['format'] : 'date';

	if ( 'timeago' === $format ) {
		$posted_on = sprintf(
			_x( 'Posted %s ago', '%s = human-readable time difference', 'bgtfw' ),
			human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) )
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
		$categories_list = get_the_category_list( __( ', ', 'bgtfw' ) );
		if ( $categories_list && boldgrid_categorized_blog() ) {
			printf( '<span class="cat-links">' . __( 'Posted in %1$s', 'bgtfw' ) . '</span>', $categories_list );
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'bgtfw' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'bgtfw' ) . '</span>', $tags_list );
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'bgtfw' ), __( '1 Comment', 'bgtfw' ), __( '% Comments', 'bgtfw' ) );
		echo '</span>';
	}

	bgtfw_edit_post_link();
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( __( 'Category: %s', 'bgtfw' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'Tag: %s', 'bgtfw' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', 'bgtfw' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', 'bgtfw' ), get_the_date( _x( 'Y', 'yearly archives date format', 'bgtfw' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', 'bgtfw' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'bgtfw' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', 'bgtfw' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'bgtfw' ) ) );
	} elseif ( is_tax( 'post_format', 'post-format-aside' ) ) {
		$title = _x( 'Asides', 'post format archive title', 'bgtfw' );
	} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
		$title = _x( 'Galleries', 'post format archive title', 'bgtfw' );
	} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
		$title = _x( 'Images', 'post format archive title', 'bgtfw' );
	} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
		$title = _x( 'Videos', 'post format archive title', 'bgtfw' );
	} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
		$title = _x( 'Quotes', 'post format archive title', 'bgtfw' );
	} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
		$title = _x( 'Links', 'post format archive title', 'bgtfw' );
	} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
		$title = _x( 'Statuses', 'post format archive title', 'bgtfw' );
	} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
		$title = _x( 'Audio', 'post format archive title', 'bgtfw' );
	} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
		$title = _x( 'Chats', 'post format archive title', 'bgtfw' );
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', 'bgtfw' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'bgtfw' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'bgtfw' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;
	}
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
