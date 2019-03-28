const api = wp.customize;
export default () => {
	api.bind( 'ready', () => {
		api.previewer.bind( 'edit-post-link', editPostLink => window.location = editPostLink );
	} );
};
