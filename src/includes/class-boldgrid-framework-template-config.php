<?php
/**
 * Class: Boldgrid_Framework_Template_Config
 *
 * This is used to set template based configuration options.
 *
 * This pulls configuration, directories and version information from the framework configs.
 *
 * @since      1.1.1
 * @package    BoldGrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: BoldGrid_Framework_Template_Config
 *
 * This is used to set template based configuration options.
 *
 * This pulls configuration, directories and version information from the framework configs.
 *
 * @since      1.1.1
 */
class BoldGrid_Framework_Template_Config {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.1.1
	 * @access    protected
	 * @var       array     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param     array $configs       The BoldGrid Theme Framework configurations.
	 * @since     1.1.1
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Add style configs for pagination.
	 *
	 * If the style is defined as buttons for pagination, override the classes for paging and posts
	 * nav classes to add button-primary or whatever the configs for that post are.
	 *
	 * @since 1.5
	 *
	 * @return array Configuration.
	 */
	public function pagination_style( $configs ) {
		$post_navigation = $configs['template']['post_navigation'];

		if ( 'buttons' === $post_navigation['style'] ) {
			$pageing_nav_classes = $post_navigation['style_configs']['buttons']['paging_nav_classes'];
			$post_navigation['paging_nav_classes']['next'] .= ' ' . $pageing_nav_classes;
			$post_navigation['paging_nav_classes']['previous'] .= ' ' . $pageing_nav_classes;

			$post_nav_classes = $post_navigation['style_configs']['buttons']['post_nav_classes'];
			$post_navigation['post_nav_classes']['next'] .= ' ' . $post_nav_classes;
			$post_navigation['post_nav_classes']['previous'] .= ' ' . $post_nav_classes;
		}

		$configs['template']['post_navigation'] = $post_navigation;

		return $configs;
	}

	/**
	 * Adds the sidebar templates for pages and posts to the
	 * page/post attributes dropdowns in the WordPress editor.
	 *
	 * @since 2.0
	 *
	 * @param array $templates Array of available templates to choose from.
	 *
	 * @return array $templates The modified $templates array.
	 */
	public function templates( $templates ) {
		$templates['no-sidebar'] = 'No Sidebar';
		$templates['right-sidebar'] = 'Right Sidebar';
		$templates['left-sidebar'] = 'Left Sidebar';

		return $templates;
	}
}
