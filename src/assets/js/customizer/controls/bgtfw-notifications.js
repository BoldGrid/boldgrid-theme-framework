
const api = wp.customize;

export default function() {

	api.bind( 'ready', function() {
		api.previewer.bind( 'ready', function() {
			const notices = [];

			api.section.each( section => {
				if ( ! _.isUndefined( section.params.notice ) ) {
					notices.push( { type: 'section', data: section } );
				}
			} );

			api.panel.each( panel => {
				if ( ! _.isUndefined( panel.params.notice ) ) {
					notices.push( { type: 'panel', data: panel } );
				}
			} );

			_.each( notices, notice => {
				api[ notice.type ]( notice.data.id ).notifications.add( `bgtfw-notice-${ notice.type }-${ notice.data.id }`, new api.Notification( `bgtfw-notice-${ notice.type }-${ notice.data.id }`, notice.data.params.notice ) );
			} );
		} );
	} );
}
