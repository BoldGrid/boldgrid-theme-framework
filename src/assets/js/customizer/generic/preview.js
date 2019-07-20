import { Preview as PreviewUtility } from '../preview';

export class Preview {
	constructor() {
		this.preview = new PreviewUtility();
	}

	/**
	 * Loop through all controls that are generic controls, and bind the change event.
	 *
	 * @since 2.0.0
	 */
	bindEvents() {
		if ( parent.wp.customize.control ) {
			parent.wp.customize.control.each( wpControl => {
				if (
					wpControl.params.choices &&
					'boldgrid_controls' === wpControl.params.choices.name
				) {
					this.bindControl( wpControl );
				}
			} );
		}
	}

	/**
	 * Bind a single WordPress controls change event.
	 *
	 * @since 2.0.0
	 *
	 * @param  {object} wpControl WordPress control instance.
	 */
	bindControl( wpControl ) {
		wp.customize( wpControl.id, value => {
			value.bind( setting => {

				// If value is not truthy, we are reverting to defaults.
				if ( setting ) {
					this.preview.updateDynamicStyles(
						wpControl.id + '-bgcontrol-inline-css',
						setting.css
					);
				}
			} );
		} );
	}
}
