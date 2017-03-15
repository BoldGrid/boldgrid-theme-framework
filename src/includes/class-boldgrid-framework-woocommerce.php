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

	/**
	 * Set the color of the Sale! indicator.
	 *
	 * @param  [type] $text     [description]
	 * @param  [type] $post     [description]
	 * @param  [type] $_product [description]
	 *
	 * @return String
	 */
	public function woocommerce_custom_sale_text( $text, $post, $_product ) {
		return '<span class="onsale color2-background-color">Sale!</span>';
	}

	/**
	 * Adds select2 styles to match our theme.
	 *
	 * wooCommerce adds select to for their dropdowns, which creates a better
	 * user experience overall.  The styles conflict with the native styles of
	 * bootstrap, so we add our bootstrap select2 style conditionally if wooCommerce
	 * class is present, and we are on the checkout page.  This needs to be enqueued
	 * after the initial select2 styles, so we require that as a dependancy.
	 *
	 * @since 1.4.1
	 * @return null
	 */
	public function select2_style() {
		if ( class_exists( 'woocommerce' ) ) {
			if ( is_checkout() ) {
				$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
				wp_enqueue_style(
					'select2-bootstrap-css',
					$this->configs['framework']['css_dir'] . 'select2-bootstrap/select2-bootstrap' . $suffix . '.css',
					array( 'select2' ),
					'1.4.6'
				);
			}
		}
	}

	/**
	 * Adding .form-control for input elements in wooCommerce.
	 *
	 * The input elements used throughout wooCommerce should inherit
	 * the set bootstrap styles we use throughout our themes.
	 *
	 * @param  [type] $args  [description]
	 * @param  [type] $key   [description]
	 * @param  [type] $value [description]
	 *
	 * @return [type]        [description]
	 */
	public function wc_form_field_args( $args, $key, $value = null ) {
		/**
		 * Look for the various types of items wooCommerce uses, and tap into the
		 * filter to conditoinally apply our required classes to match bootstrap's
		 * expected structure.
		 */
		switch ( $args['type'] ) {
			/**
			 * Targets all select input type elements, except the country and
			 * state select input types.
			 */
			case 'select' :
				$args['class'][] = 'form-group'; // Add a class to the field's html element wrapper - woocommerce input types (fields) are often wrapped within a <p></p> tag
				$args['input_class'] = array( 'form-control', 'input-lg' ); // Add a class to the form input itself
				$args['label_class'] = array( 'control-label' );
				$args['custom_attributes'] = array( 'data-plugin' => 'select2', 'data-allow-clear' => 'true', 'aria-hidden' => 'true',  ); // Add custom data attributes to the form input itself
				break;

			/**
			 * By default WooCommerce will populate a select with the country
			 * names - $args defined for this specific input type targets only
			 * the country select element
			 */
			case 'country' :
				$args['class'][] = 'form-group single-country';
				$args['input_class'] = array('form-control', 'input-lg'); // add class to the form input itself
				$args['label_class'] = array('control-label');
				$args['custom_attributes'] = array( 'data-plugin' => 'select2', 'data-allow-clear' => 'true', 'aria-hidden' => 'true',  );
				break;

			/**
			 * By default WooCommerce will populate a select with state names -
			 * $args defined for this specific input type targets only the country
			 * select element.
			 */
			case 'state' :
				$args['class'][] = 'form-group'; // Add class to the field's html element wrapper
				$args['input_class'] = array('form-control', 'input-lg'); // add class to the form input itself
				$args['label_class'] = array('control-label');
				$args['custom_attributes'] = array( 'data-plugin' => 'select2', 'data-allow-clear' => 'true', 'aria-hidden' => 'true',  );
				break;


			case 'password' :
			case 'text' :
			case 'email' :
			case 'tel' :
			case 'number' :
				$args['class'][] = 'form-group';
				$args['input_class'] = array('form-control', 'input-lg');
				$args['label_class'] = array('control-label');
				break;

			case 'textarea' :
				$args['input_class'] = array('form-control', 'input-lg');
				$args['label_class'] = array('control-label');
				break;

			case 'checkbox' :
				break;

			case 'radio' :
				break;

			default :
				$args['class'][] = 'form-group';
				$args['input_class'] = array('form-control', 'input-lg');
				$args['label_class'] = array('control-label');
				break;
		}

		return $args;
	}
}
