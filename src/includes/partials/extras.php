<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package BoldGrid
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function boldgrid_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'boldgrid_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function boldgrid_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'boldgrid_body_classes' );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function boldgrid_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'boldgrid_setup_author' );

if ( ! function_exists( 'esc_color' ) ) {
	function esc_color( $color ) {
		$output = '';

		// Check for hex.
		if ( strpos( $color, '#') !== false ) {
			$ouput = esc_hex_color( $color );
		
		// Check for rgba.
		} elseif ( strpos( $color, 'rgba') !== false ) {
			$ouput = esc_rgba_color( $color );
		
		// Check for rgb
		} elseif ( strpos( $color, 'rgb') !== false ) {
			$ouput = esc_rgb_color( $color );
		}

		return $output;
	}
}
if ( ! function_exists( 'esc_hex_color' ) ) {
	function esc_hex_color( $color ) {
		if ( '' === $color ) {
			return '';
		}
	
		// 3 or 6 hex digits, or the empty string.
		if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
			return $color;
		}
	}
}
if ( ! function_exists( 'esc_rgba_color' ) ) {
	function esc_rgba_color( $color ) {
		if ( empty( $color ) || is_array( $color ) )
			return '';

		// If string does not start with 'rgba', then try to escape as hex color.
		if ( false === strpos( $color, 'rgba' ) ) {
			return esc_hex_color( $color );
		}

		// String is rgba color so we need to further escape it.
		$color = str_replace( ' ', '', $color );
		$valid = '/(?:\b|-)([0-9]{1,2}[0]?|100)\b/';
		$pattern = '/[^' . preg_quote( $valid, '/' ) . ']/';
		sscanf(
			$color,
			'rgba(%d,%d,%d,%f)', $red, $green, $blue,$alpha
		);
		$red = preg_replace( $pattern, '', $red );
		$green = preg_replace( $pattern, '', $green );
		$blue = preg_replace( $pattern, '', $blue );
		return "rgba($red,$green,$blue,$alpha)";
	}
}
if ( ! function_exists( 'esc_rgb_color' ) ) {
	function esc_rgb_color( $color ) {
		if ( empty( $color ) || is_array( $color ) )
			return '';

		// If string does not start with 'rgba', then try to escape as hex color.
		if ( false === strpos( $color, 'rgb' ) ) {
			return esc_hex_color( $color );
		}

		// String is rgb color so we need to further escape it.
		$color = str_replace( ' ', '', $color );
		$valid = '/(?:\b|-)([0-9]{1,2}[0]?|100)\b/';
		$pattern = '/[^' . preg_quote( $valid, '/' ) . ']/';
		sscanf(
			$color,
			'rgba(%d,%d,%d)',
			preg_replace( $pattern, '', $red ),
			preg_replace( $pattern, '', $green ),
			preg_replace( $pattern, '', $blue )
		);
		return "rgb($red,$green,$blue)";
	}
}
