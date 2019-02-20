const api = wp.customize;

export default () => {
	api.bind( 'ready', () => {
		api.previewer.bind( 'ready', () => {
			const types = [ 'section', 'panel' ];
			types.map( type => {
				api[ type ].each( item => {
					if ( ! _.isUndefined( item.params.notice ) ) {
						api[ type ]( item.id ).notifications.add( `bgtfw-notice-${ type }-${ item.id }`, new api.Notification( `bgtfw-notice-${ type }-${ item.id }`, item.params.notice ) );
					}
				} );
			} );
		} );
	} );
};
