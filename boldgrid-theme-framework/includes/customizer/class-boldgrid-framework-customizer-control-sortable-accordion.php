<?php
/**
 * Class: Boldgrid_Framework_Customizer_Control_Sortable_Accordion
 *
 * This class is responsible for creating the sortable accordion
 * controls in the WordPress customizer.
 *
 * @since      2.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */
if ( class_exists( 'WP_Customize_Control' ) ) {

	class Boldgrid_Framework_Customizer_Control_Sortable_Accordion extends WP_Customize_Control {

		/**
		 * The type of control being rendered
		 */
		public $type = 'bgtfw-sortable-accordion';

		/**
		 * Repeater items that can be added.
		 */
		public $items;

		/**
		 * Location repeater control is for ( header, footer ).
		 */
		public $location;

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

			// The data link.
			$this->json['link'] = $this->get_link();

			// Addable Item Types.
			$this->json['items'] = $this->items;

			// Location control is for.
			$this->json['location'] = $this->location;
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
			<div class="{{ data.type }}">
				<# if ( data.label ) { #>
					<span class="customize-control-title">{{{ data.label }}}</span>
				<# } #>
				<# if ( data.description ) { #>
					<span class="description customize-control-description">{{{ data.description }}}</span>
				<# } #>
				<input type="hidden" id="input-{{ data.id }}" name="{{ data.id }}" value="{{ data.value }}" class="customize-control-drag-and-drop" {{{ data.link }}} />
				<div id="sortable-{{ data.id }}">
					<# _.each( data.value, function( repeaters, sortable ) { #>
						<div id="sortable-{{ sortable }}-wrapper" class="sortable-wrapper">
							<span class="sortable-title"><span class="title title-empty"><em><?php esc_html_e( 'Empty Section', 'bgtfw' ) ?></em></span><span class="dashicons dashicons-trash"></span></span>
							<div class="sortable-accordion-content">
								<div class="sortable-section-controls">
									<div class="bgtfw-container-control">
										<# var selected = repeaters.container === 'container' ? 'selected' : ''; #>
										<div class="bgtfw-sortable-control container {{ selected }}" data-container="container">
											<span class="bgtfw-icon icon-layout-container"></span>
											<span><?php esc_html_e( 'Container', 'bgtfw' ); ?></span>
										</div>
										<# var selected = repeaters.container !== 'container' ? 'selected' : ''; #>
										<div class="bgtfw-sortable-control full-screen {{ selected }}" data-container="full-width">
											<span class="bgtfw-icon icon-layout-full-screen"></span>
											<span><?php esc_html_e( 'Full Width', 'bgtfw' ); ?></span>
										</div>
									</div>
								</div>
								<ul id="sortable-{{ data.id }}-{{ sortable }}" class="connected-sortable">
								<# _.each( repeaters.items, function( repeater, i ) { #>
									<li class="repeater" data-key="{{ repeater.key }}" data-type="{{ repeater.type }}" <# if ( ! _.isUndefined( repeater.align ) ) { #>data-align="{{ repeater.align }}"<# } #>>
										<div class="repeater-input">
											<div class="repeater-handle">
												<div class="sortable-title">
													<span class="repeater-title"><i class="{{ data.items[ repeater.key ].icon }}"></i>{{{ data.items[ repeater.key ].title }}}</span><span class="dashicons dashicons-trash"></span>
												</div>
											</div>
											<div class="repeater-accordion-content-wrapper">
												<div class="repeater-accordion-content"></div>
											</div>
										</div>
									</li>
								<# } ); #>
								</ul>
								<div class="sortable-actions"></div>
							</div>
						</div>
					<# } ); #>
				</div>
				<div id="sortable-{{ data.id }}-add-section">
					<a class="button-secondary" href="#"><i class="fa fa-plus" aria-hidden="true"></i><?php esc_html_e( 'Add Section', 'bgtfw' ); ?></a>
				</div>
			</div>
			<?php
		}
	}
}
