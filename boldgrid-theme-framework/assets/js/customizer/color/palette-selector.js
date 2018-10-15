export class PaletteSelector {

	/**
	 * Get the color value for the palette selector control.
	 *
	 * @since 2.0.0
	 *
	 * @param  {string}  themeMod Theme mod value.
	 * @param  {Boolean} variable Whether to return color variable or true color value.
	 *
	 * @return {string}           CSS Color Variable, HEX, RGB, RGBA or value.
	 */
	getColor( themeMod, variable = false ) {
		return variable ? this.getVariableValue( themeMod ) : this.getColorVariable( themeMod );
	}

	/**
	 * Get the CSS variable for the palette selector control.
	 *
	 * @since 2.0.1
	 *
	 * @param  {string}  themeMod Theme mod value.
	 *
	 * @return {string}           CSS Color Variable.
	 */
	getColorVariable( themeMod ) {
		let colorNumber = this.getColorNumber( themeMod );
		return `var(--${colorNumber});`;
	}

	/**
	 * Get the color value for the palette selector control.
	 *
	 * @since 2.0.1
	 *
	 * @see https://jsperf.com/bgtfw-color-token-parse
	 *
	 * @param  {string}  themeMod Theme mod value.
	 *
	 * @return {string}           CSS Color Variable.
	 */
	getVariableValue( themeMod ) {
		return themeMod.substring( themeMod.indexOf( ':' ) + 1 );
	}

	/**
	 * Get the color number for the palette selector control.
	 *
	 * @since 2.0.0
	 *
	 * @see https://jsperf.com/bgtfw-color-token-parse
	 *
	 * @param  {string} themeMod Theme mod value.
	 *
	 * @return {string} Color number.
	 */
	getColorNumber( themeMod ) {
		return themeMod.substring( 0, themeMod.indexOf( ':' ) );
	}
}

export default PaletteSelector;
