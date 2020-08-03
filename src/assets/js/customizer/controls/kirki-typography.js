export default {
	renderFontSelector: function() {
		var $value;

		// Call parent method.
		wp.customize.controlConstructor['kirki-typography']
			.__super__
			.renderFontSelector
			.apply( this, arguments );

		$value = wp.customize( this.id )();

		$value['font-family'] = $value['font-family'].replace( /"/g, '' );
		wp.customize( this.id ).set( $value );

		// Selecting the instance will add the needed attributes, don't ask why.
		$( this.selector + ' .font-family select' ).selectWoo();
		$( this.selector + ' .font-family select' ).val( $value['font-family'] ).trigger('change');
	}
};
