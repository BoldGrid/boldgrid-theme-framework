<?php
/**
 * Class: Boldgrid_Framework_Customizer_Control_Palette_Selector
 *
 * This class is responsible for creating the palette selector
 * controls in the WordPress customizer.
 *
 * @since      2.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */
if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Adds a menu hamburger selection control.
	 *
	 * This is a custom radio control.
	 *
	 * @since 2.0.0
	 */
	class Boldgrid_Framework_Customizer_Control_Menu_Hamburgers extends WP_Customize_Control {

		/**
		 * The control type.
		 *
		 * @var string $type The control type.
		 */
		public $type = 'bgtfw-menu-hamburgers';

		/**
		 * Initials choices for control.
		 *
		 * @var string $initial Initial choices for control.
		 */
		 public $initial = [
			'Boring' => 'hamburger--boring',
			'Collapse' => 'hamburger--collapse',
			'Collapse Reverse' => 'hamburger--collapse-r',
			'Elastic' => 'hamburger--elastic',
			'Elastic Reverse' => 'hamburger--elastic-r',
			'Emphatic' => 'hamburger--emphatic',
			'Emphatic Reverse' => 'hamburger--emphatic-r',
			'Minus' => 'hamburger--minus',
		];

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @since 2.0.0
		 */
		public function to_json() {

			// Call parent to_json() method to get the core defaults like "label", "description", etc.
			parent::to_json();

			// ID.
			$this->json['id'] = $this->id;

			// The setting value.
			$this->json['value'] = $this->value();

			// The control choices.
			$this->json['choices'] = array_merge( $this->initial, $this->choices );

			// The data link.
			$this->json['link'] = $this->get_link();
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
			?>
			<# if ( ! data.choices ) { return; } #>
			<div class="bgtfw-control-wrapper" style="margin: 0 -12px;">
				<span class="customize-control-title">
					{{{ data.label }}}
				</span>
				<# if ( data.description ) { #>
					<span class="description customize-control-description">{{{ data.description }}}</span>
				<# } #>
				<div id="input_{{ data.id }}" class="bgtfw-hamburgers-wrapper">
					<# for ( hamburger in data.choices ) { #>
						<div class="bgtfw-hamburger-col<# if ( data.value == data.choices[ hamburger ] ) { #> hamburger-selected<# } #>">
							<input type="radio" {{{ data.inputAttrs }}} value="{{ data.choices[ hamburger ] }}" name="_customize-bgtfw-hamburgers-{{ data.id }}" id="{{ data.id }}-{{ hamburger }}" {{{ data.link }}}<# if ( data.value == data.choices[ hamburger ] ) { #> checked<# } #>>
								<label for="{{ data.id }}-{{ hamburger }}">
									<div class="tray">
										<div class="name">{{{hamburger}}}</div>
										<div class="hamburger {{ data.choices[ hamburger ] }} bgtfw-hamburger-toggle">
											<div class="hamburger-box">
												<div class="hamburger-inner"></div>
											</div>
										</div>
									</div>
								</label>
							</input>
						</div>
					<# } #>
				</div>
			</div>
			<?php
		}
	}
}
