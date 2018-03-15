<?php
/**
 * Class: BoldGrid_Framework_Starter_Content
 *
 * This is used for the starter content functionality in the BoldGrid Theme Framework.
 *
 * @since      2.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
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
	 * @since     2.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     2.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	public function add_post_meta( $content, $config ) {
		foreach( $config['posts'] as $post => $configs ) {
			if ( isset( $configs['meta_input'] ) ) {
				$content['posts'][ $post ]['meta_input'] = $configs['meta_input'];
			}
		}

		return $content;
	}

	public function add_theme_support() {
		if ( ! empty( $this->configs['starter-content'] ) ) {
			add_theme_support( 'starter-content', $this->configs['starter-content'] );
		}
	}
}
