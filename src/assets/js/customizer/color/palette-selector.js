export class PaletteSelector {

	/**
	 * Get the color value for the palette selector control.
	 *
	 * @since 2.0.0
	 *
	 * @param  {string} themeMod Theme mod value.
	 * @return {string}       HEX, RGB, RGBA value.
	 */
	getColor( themeMod ) {
		let colorNumber = this.getColorNumber( themeMod );
		return `var(--${colorNumber});`;
	}

	/**
	 * Get the color number for the palette selector control.
	 *
	 * @since 2.0.0
	 *
	 * @param  {string} themeMod Theme mod value.
	 * @return {string} Color number.
	 */
	getColorNumber( themeMod ) {
		return themeMod.split( ':' ).shift();
	}
}

export default PaletteSelector;
