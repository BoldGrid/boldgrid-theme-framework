<?php
/**
 * Customizer Control: code.
 *
 * Creates a new custom control.
 * Custom controls accept raw HTML/JS.
 *
 * @package     Kirki
 * @subpackage  Controls
 * @copyright   Copyright (c) 2017, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 * @since       1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds a "code" control, using CodeMirror.
 */
class Kirki_Control_Code extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'kirki-code';

	/**
	 * Used to automatically generate all CSS output.
	 *
	 * @access public
	 * @var array
	 */
	public $output = array();

	/**
	 * Data type
	 *
	 * @access public
	 * @var string
	 */
	public $option_type = 'theme_mod';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {

		wp_enqueue_script( 'kirki-dynamic-control', trailingslashit( Kirki::$url ) . 'assets/js/dynamic-control.js', array( 'jquery', 'customize-base' ), false, true );

		// Make sure $this->choices['language'] is defined.
		$this->choices['language'] = ( ! isset( $this->choices['language'] ) ) ? 'css' : $this->choices['language'];

		wp_enqueue_script( 'kirki-code', trailingslashit( Kirki::$url ) . 'controls/code/code.js', array( 'jquery', 'customize-base', 'kirki-dynamic-control' ), false, true );
		wp_enqueue_style( 'kirki-code-css', trailingslashit( Kirki::$url ) . 'controls/code/code.css', null );

	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}
		$this->json['output']  = $this->output;
		$this->json['value']   = $this->value();
		$this->json['choices'] = $this->choices;
		$this->json['link']    = $this->get_link();
		$this->json['id']      = $this->id;

		$this->json['inputAttrs'] = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}

	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 */
	protected function content_template() {
		global $wp_version;
		// If we're on WP 4.9+, then we don't need to add anything here.
		if ( version_compare( $wp_version, '4.9-beta' ) >= 0 ) {
			return;
		}
		?>
		<label>
			<# if ( data.label ) { #><span class="customize-control-title">{{{ data.label }}}</span><# } #>
			<# if ( data.description ) { #><span class="description customize-control-description">{{{ data.description }}}</span><# } #>
			<div class="codemirror-kirki-wrapper">
				<textarea {{{ data.inputAttrs }}} data-language="{{ data.choices.language }}" class="kirki-codemirror-editor" {{{ data.link }}}>{{{ data.value }}}</textarea>
			</div>
		</label>
		<?php
	}
}
