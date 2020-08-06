export default {
	renderFontSelector: function() {

		// Call parent method.
		wp.customize.controlConstructor['kirki-typography']
			.__super__
			.renderFontSelector
			.apply( this, arguments );

		// Selecting the instance will add the needed attributes, don't ask why.
		$( this.selector + ' .font-family select' ).selectWoo();
		$( this.selector + ' .font-family select' ).select2();
	}
};
