<?php
/**
 * Class: BoldGrid_Framework_Woocommerce
 *
 * This is where all wooCommerce specific functionality is manipulated, outside
 * of custom templates used by parent themes.
 *
 * @since      1.4.1
 * @package    Boldgrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: BoldGrid_Framework_Woocommerce
 *
 * This is where all wooCommerce specific functionality is manipulated, outside
 * of custom templates used by parent themes.
 *
 * @since      1.4.1
 */
class BoldGrid_Framework_Woocommerce {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.4.1
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     1.4.1
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Filter add_to_cart_url.
	 *
	 * This is responsible for filtering the add to cart buttons  used throughout
	 * wooCommerce and placing our button classes on them.
	 *
	 * @global $product wooCommerce global product info.
	 *
	 * @param  string $link Markup for the link to place on wooCommerce pages.
	 *
	 * @return string $link Markup to use for add to cart buttons in wooCommerce.
	 */
	public function buttons( $link ) {
		global $product;
		$link = sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $quantity ) ? $quantity : 1 ),
			esc_attr( $product->id ),
			esc_attr( $product->get_sku() ),
			esc_attr( isset( $class ) ? $class : 'btn button-primary' ),
			esc_html( $product->add_to_cart_text() )
		);

		return $link;
	}
}
