<?php
/**
 * Class: Boldgrid_Framework_Quick_Start_Guide.
 *
 * This class is used to add the new Quick Start Guide to the Customizer.
 *
 * @since      2.7.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Quick_Start_Guide
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Framework_Quick_Start_Guide.
 *
 * This class is used to add the new Quick Start Guide to the Customizer.
 *
 * @since      2.7.0
 */
class Boldgrid_Framework_Quick_Start_Guide {

	/**
	 * Configs.
	 *
	 * BGTFW Configs Array.
	 *
	 * @since 2.7.0
	 * @access public
	 * @var Array
	 */
	public $configs = array();

	/**
	 * Constructor.
	 *
	 * @since 2.7.0
	 *
	 * @param Array $configs BGTFW Configs Array.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Register Scripts.
	 *
	 * Registers any scripts needed for the Quick Start Guide.
	 *
	 * @since 2.7.0
	 */
	public function register_scripts() {
		wp_register_script(
			'crio-quick-start-guide',
			$this->configs['framework']['js_dir'] . '/customizer/quick-start.js',
			array( 'jquery', 'customize-preview' ),
			true,
			$this->configs['version']
		);
	}

	/**
	 * Localize Scripts.
	 *
	 * Adds necessary data to the customize scripts, such as the nonce needed for ajax calls.
	 *
	 * @since 2.7.0
	 */
	public function localize_scripts() {
		$data = array(
			'nonce'   => wp_create_nonce( 'crio_get_quick_start_markup' ),
			'iconUrl' => get_template_directory_uri() . '/images/crio_logo.svg',
		);

		wp_localize_script(
			'crio-quick-start-guide',
			'crioQuickStartParams',
			$data
		);
	}

	/**
	 * Enqueue Scripts.
	 *
	 * This is the method fired by the 'customizer_preview_init' hook.
	 *
	 * @since 2.7.0
	 */
	public function enqueue_scripts() {
		error_log( 'customizer_preview_init fired' );
		$this->register_scripts();
		$this->localize_scripts();
		wp_enqueue_script( 'crio-quick-start-guide' );
	}

	/**
	 * Quick Start Items.
	 *
	 * Gets an array of quick start items to display.
	 *
	 * @since 2.7.0
	 *
	 * @param string $page Page to get items for.
	 *
	 * @return array An array of items to display.
	 */
	public function quick_start_items( $page ) {
		if ( isset( $this->configs['quick-start-items'] ) && isset( $this->configs['quick-start-items'][ $page ] ) ) {
			return $this->configs['quick-start-items'][ $page ];
		} elseif ( isset( $this->configs['quick-start-items']['main'] ) ) {
			return $this->configs['quick-start-items']['main'];
		} else {
			return array();
		}
	}

	/**
	 * Get full markup.
	 *
	 * Generates the full markup for the quick start guide.
	 *
	 * @since 2.7.0
	 *
	 * @param string $page_name Page to get markup for.
	 *
	 * @return string Markup.
	 */
	public function get_full_markup( $page_name ) {
		$page    = $this->quick_start_items( $page_name );
		$nav     = $page['nav'];
		$items   = $page['items'];
		$markup  = '<div class="content-nav">';
		$markup .= $this->get_breadcrumbs( $nav );
		$markup .= '</div>';

		foreach ( $items as $index => $item ) {
			$child_arrow_markup = '';

			if ( isset( $item['child'] ) && isset( $item['focus'] ) ) {
				$child_arrow_markup .= '<span class="quick-start-arrow dashicons dashicons-arrow-right-alt2" data-child="' . $item['child'] . '" data-focus="' . $item['focus'] . '"></span>';
			} elseif ( isset( $item['child'] ) ) {
				$child_arrow_markup .= '<span class="quick-start-arrow dashicons dashicons-arrow-right-alt2" data-child="' . $item['child'] . '"></span>';
			}

			$markup .= '<div class="content-item" >
				<div class="content-item-header">'
					. ( 1 < count( $items ) ? '<span class="step-number">' . $index + 1 . '</span>' : '' )
					. ( isset( $item['title'] ) ? '<h3>' . $item['title'] . '</h3>' : '' )
					. $child_arrow_markup .
				'</div>
				<div class="content-item-content">'
					. ( isset( $item['descr'] ) ? '<p class="content-item-descr">' . $item['descr'] . '</p>' : '' ) .
				'</div>
			</div>';
		}

		return $markup;
	}

	/**
	 * Get Breadcrumbs.
	 *
	 * Gets a breadcrumb nav for the content.
	 *
	 * @since 2.7.0
	 *
	 * @param array $nav Current Page nav.
	 */
	public function get_breadcrumbs( $nav ) {
		$nav_markup = '';
		foreach ( $nav as $nav_item ) {
			if ( 'main' === $nav_item ) {
				$nav_markup .= '<span class="breadcrumb-nav dashicons dashicons-admin-home" data-child="home"></span>';
			} else {
				$nav_label   = preg_replace( '/[-_]/', ' ', $nav_item );
				$nav_markup .= '<span> > </span><span class="breadcrumb-nav" data-child="' . $nav_item . '">' . strtoupper( $nav_label ) . '</span>';
			}
		}

		return $nav_markup;
	}

	/**
	 * Quick Start Markup.
	 *
	 * Returns the markup used for the quick start guide content.
	 * This is a callback for the 'wp_ajax_crio_get_quick_start_markup' action.
	 *
	 * @since 2.7.0
	 */
	public function quick_start_markup() {
		$verified = false;
		if ( isset( $_POST ) && isset( $_POST['nonce'] ) ) {
			$verified = wp_verify_nonce(
				$_POST['nonce'],
				'crio_get_quick_start_markup'
			);
		}

		if ( ! $verified ) {
			return false;
		}

		$return = array(
			'markup' => $this->get_full_markup( isset( $_POST['page'] ) ? $_POST['page'] : 'main' ),
		);

		wp_send_json( $return );
	}
}
