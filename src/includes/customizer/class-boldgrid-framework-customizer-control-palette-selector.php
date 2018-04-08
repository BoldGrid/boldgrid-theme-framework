<?php
/**
 * Adds a color-palette control.
 * This is essentially a radio control, styled as a palette.
 */

if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Adds a color-palette control.
	 * This is essentially a radio control, styled as a palette.
	 */
	class Boldgrid_Framework_Customizer_Control_Palette_Selector extends Kirki_Control_Color_Palette {

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'bgtfw-palette-selector';

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
			?>
			<# if ( ! data.choices ) { return; } #>
			<div class="bgtfw-control-wrapper">
				<span class="customize-control-title">
					{{{ data.label }}}
				</span>
				<# if ( data.description ) { #>
					<span class="description customize-control-description">{{{ data.description }}}</span>
				<# } #>
				<div id="input_{{ data.id }}" class="colors-wrapper <# if ( ! _.isUndefined( data.choices.style ) && 'round' === data.choices.style ) { #>round<# } else { #>square<# } #><# if ( ! _.isUndefined( data.choices['box-shadow'] ) && true === data.choices['box-shadow'] ) { #> box-shadow<# } #><# if ( ! _.isUndefined( data.choices['margin'] ) && true === data.choices['margin'] ) { #> with-margin<# } #>">
					<# for ( key in data.choices['colors'] ) { #>
						<input type="radio" {{{ data.inputAttrs }}} value="{{ key }}:{{ data.choices['colors'][ key ] }}" name="_customize-color-palette-{{ data.id }}" id="{{ data.id }}{{ key }}" {{{ data.link }}}<# if ( data.value == data.choices['colors'][ key ] ) { #> checked<# } #>>
							<label for="{{ data.id }}{{ key }}" style="width: {{ data.choices['size'] }}px; height: {{ data.choices['size'] }}px;">
								<span class="color-palette-color" style='background: {{ data.choices['colors'][ key ] }};'>{{ data.choices['colors'][ key ] }}</span>
							</label>
						</input>
					<# } #>
				</div>
			</div>
			<?php
		}
	}
}
