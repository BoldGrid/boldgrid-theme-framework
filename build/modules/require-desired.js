module.exports = ( dependency ) => {
	try {
		require.resolve( dependency );
	} catch ( err ) {
		if ( err.code !== 'MODULE_NOT_FOUND' ) throw err;
		return {};
	}
	return require( dependency );
};
