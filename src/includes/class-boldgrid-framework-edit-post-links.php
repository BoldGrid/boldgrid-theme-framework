<?php
/**
 * Class: Boldgrid_Framework_Edit_Post_Links
 *
 * This class contains methods that are used for edit post links.
 *
 * @since      1.5.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Edit_Post_Links
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Responsible for the edit post link functionality in BoldGrid themes.
 *
 * @since 1.5.0
 */
class Boldgrid_Framework_Edit_Post_Links {

	/**
	 * The theme's configs.
	 *
	 * @access private
	 * @var    string  $configs The BoldGrid Theme Framework configurations.
	 * @since  1.5.0
	 */
	private $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.5.0
	 *
	 * @param string $configs The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Get formatted link.
	 *
	 * @since 1.5.0
	 *
	 * @return string $link Modified markup for edit_post_link().
	 */
	public function get_link( $link, $id, $text ) {
		$link = sprintf(
			'<a href="%1$s" aria-label="%2$s" title="%2$s" class="bgtfw-edit-link-button">' .
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">' .
					'<path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"></path>' .
				'</svg>' .
			'</a>',
			get_edit_post_link( $id ),
			$text
		);

		return $link;
	}
}
