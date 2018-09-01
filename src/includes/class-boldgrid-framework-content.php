<?php
/**
 * Class: Boldgrid_Framework_Content
 *
 * @since      2.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Content
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * Boldgrid_Framework_Content Class
 *
 * @since 2.0.0
 */
class Boldgrid_Framework_Content {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       array $configs The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.0.0
	 *
	 * @param array $configs The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Add blank space after each closing tag.
	 *
	 * Let's say you have this code:
	 *  <h3>Header</h3><p>Paragraph</p>
	 * When you strip all tags, you're left with:
	 *  HeaderParagraph
	 * After this method adds spaces and you strip tags, you'll have:
	 *  Header Paragraph
	 *
	 * @since 2.0.0
	 *
	 * @param  string $text The original text.
	 * @return string
	 */
	public static function add_spaces( $text ) {
		/*
		 * We could simply change '>' to '> ', but we could get false positives like
		 * 'text</a>,'
		 * printing as
		 * 'text ,' (with a space before the comma).
		 */
		$text = trim( str_replace( '><', '> <', $text ) );
		return $text;
	}

	/**
	 * Get the excerpt.
	 *
	 * This is a wrapper function to WordPress' the_excerpt. Here, we ensure words in the excerpt
	 * are separated by a space. See self::add_spaces for further info.
	 *
	 * @since 2.0.0
	 */
	public static function the_excerpt() {
		$method = array( 'Boldgrid_Framework_Content', 'add_spaces' );

		// Remove the filter when done so we don't have any unintended consequences elsewhere.
		add_filter( 'the_content', $method );
		the_excerpt();
		remove_filter( 'the_content', $method );
	}

	/**
	 * Filter the except length to 20 words.
	 *
	 * @param int $length Excerpt length.
	 * @return int (Maybe) modified excerpt length.
	 */
	public static function excerpt_length( $length ) {
		return get_theme_mod( 'bgtfw_pages_blog_blog_page_layout_excerpt_length' );
	}
}
