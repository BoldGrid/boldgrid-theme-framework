/* esversion: 6 */
const api = wp.customize;

export default function() {
	api.bind( 'ready', function() {
		const update = () => {
			let activeSections = api.control( 'bgtfw_header_layout' )
				.getConnectedItems()
				.filter( items => items.includes( 'sidebar' ) )
				.map( control => control.replace( 'bgtfw_sidebar_', 'sidebar-widgets-' ) ),
				sections = _.filter( window._wpCustomizeSettings.sections, ( section, id ) => id.includes( 'sidebar-widgets' ) );

			sections.map( section => api.section( section.id ).active.set( activeSections.includes( section.id ) ) );
		};
		api.previewer.bind( 'ready', () => update() );
		api.previewer.bind( 'bgtfw-widget-section-update', () => update() );
	} );
}
