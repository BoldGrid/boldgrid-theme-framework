import PaletteSelector from '../color/palette-selector';

const $ = jQuery;
const api = wp.customize;

/**
 * Setup all events for previewing background changes.
 *
 * @since 2.0.0
 *
 * @type {Preview}
 */
export class Preview {

	/**
	 * Instatiate the class.
	 *
	 * @since 2.0.0
	 */
	constructor() {
		this.selector = new PaletteSelector();
		this.$body = $( 'body' );
	}

	/**
	 * Initialize this class, binding all events.
	 *
	 * @since 2.0.0
	 *
	 * @return {Preview} Class Instance.
	 */
	init() {
		$( () => this._onLoad() );

		return this;
	}

	/**
	* Set the overlay colors.
	*
	* @since 2.0.0
	*/
	setImage() {
		if ( 'image' === api( 'boldgrid_background_type' )() ) {
			const backgroundImage = api( 'background_image' )();

			let css = '';
			if ( backgroundImage && api( 'bgtfw_background_overlay' )() ) {
				css = this._getOverlayCss( backgroundImage );
			} else if ( backgroundImage ) {
				css = `url("${backgroundImage}")`;
			}

			this.$body.css( 'background-image', css );
		}
	}

	/**
	 * Process to run when the page loads.
	 *
	 * @since 2.0.0
	 */
	_onLoad() {
		this._setupImageChange();
	}

	/**
	 * Get the css needed to display an overlay.
	 *
	 * @since 2.0.0
	 *
	 * @param  {string} backgroundImage Background image property.
	 * @return {string}                 CSS for background image.
	 */
	_getOverlayCss( backgroundImage ) {
		const color = this.selector.getColor(
				api( 'bgtfw_background_overlay_color' )()
			),
			alpha = api( 'bgtfw_background_overlay_alpha' )(),
			brehautColor = parent.net.brehaut.Color( color ),
			rgba = brehautColor.setAlpha( alpha ).toString();

		return `linear-gradient(to left, ${rgba}, ${rgba}), url("${backgroundImage}")`;
	}

	/**
	 * When the background image changes or any related properties, update the image.
	 *
	 * @since 2.0.0
	 */
	_setupImageChange() {
		api(
			'bgtfw_background_overlay_alpha',

			// 'boldgrid_background_type',

			'bgtfw_background_overlay',
			'bgtfw_background_overlay_color',
			'background_image',
			( ...args ) => {
				args.map( control =>
					control.bind( () => this.setImage() )
				);
			}
		);
	}
}
