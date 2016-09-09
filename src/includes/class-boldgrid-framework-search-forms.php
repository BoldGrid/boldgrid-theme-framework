<?php
/**
 * The class responsible for the default 404 template across all BoldGrid themes.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework/Search_Forms
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: BoldGrid_Framework_Search_Forms.
 *
 * The class responsible for the default 404 template across all BoldGrid themes.
 *
 * @since      1.0.0
 */
class BoldGrid_Framework_Search_Forms {

	/**
	 * The plugins configs
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $configs    An array of the plugins configurations
	 */
	protected $configs;

	/**
	 * Pass in the configs
	 *
	 * @since    1.0.0
	 * @param array $configs BoldGrid Theme Framework configuration options.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * BoldGrid Search Form Template
	 *
	 * This is the template for the 404 file that is used by BoldGrid themes.
	 * You can override this by hooking into boldgrid_search_form.
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_search_template() {
	?>

		<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search" class="<?php echo esc_attr( $this->bootstrap_searchform_class( debug_backtrace() ) ); ?>">
			<div class="form-group">
				<input type="text" class="form-control" name="s" value="<?php echo esc_attr( get_search_query( ) ); ?>" id="s" placeholder="<?php esc_attr_e( 'Search &hellip;', 'bgtfw' ); ?>" />
			</div>
			<button type="submit" class="button-primary"><span class="fa fa-search"></span><span>&nbsp;Search</span></button>
		</form>

	<?php }

	/**
	 * This is the bootstrap styling added to the search forms.
	 *
	 * If the search form is used in the header, then add the navbar-form navbar-right classes.
	 * Otherwise, display with the form-inline class.
	 *
	 * @since 1.0.0
	 * @param array $bt File to perform backtrace on.
	 */
	public function bootstrap_searchform_class( $bt = array() ) {
		$caller = basename( $bt[1]['file'], '.php' );

		switch ( $caller ) {
			case 'header' :
				return 'navbar-form navbar-right';
			default :
				return 'form-inline';
		}
	}
}
