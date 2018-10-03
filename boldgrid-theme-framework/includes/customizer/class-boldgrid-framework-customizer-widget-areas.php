<?php
/**
 * Class: Boldgrid_Framework_Customizer_Widget_Areas
 *
 * This is used to generate the dynamic sidebar mark for header/footer areas.
 *
 * @since      2.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class BoldGrid_Framework_Customizer
 *
 * Responsible for some framework customizer controls.
 *
 * @since 1.0.0
 */
class Boldgrid_Framework_Customizer_Widget_Areas {

	/**
	 * Generate a row of widgets to add to theme template.
	 *
	 * This is used to generate the markup for "dynamic" widget rows.  The prefix should
	 * match the theme_mod responsible.
	 *
	 * @since 2.0.0
	 *
	 * @param string $prefix  prefix of theme_mod "boldgrid_{$prefix}_widgets".
	 * @param int    $columns Number of widget columns to generate.
	 */
	public function widget_row( $prefix, $columns = null ) {
		if ( is_null( $columns ) ) {
			$columns = get_theme_mod( "boldgrid_{$prefix}_widgets" );
		}

		if ( 1 <= $columns && 4 >= $columns ) {
			$container = ( false !== get_theme_mod( "{$prefix}_widgets_container" ) ) ? get_theme_mod( "{$prefix}_widgets_container" ) : '' ;
			?>
				<div id="<?php echo esc_attr( $prefix ); ?>-widget-area" class="bgtfw-widget-row <?php echo esc_attr( $container ); ?>">
					<?php
						for ( $i = 1; $i <= $columns; $i++ ) {
							bgtfw_widget( "{$prefix}-{$i}", true );
						}
					?>
				</div><!-- <?php echo esc_html( $prefix ); ?>-widget-area ends -->
			<?php
		}
	}

	/**
	 * Header Widget Columns.
	 *
	 * This will add the header widget section to a BoldGrid
	 * theme.  This accepts $columns, which should be a number
	 * of columns to include.  Accepted values should be 1
	 * through 4, or leave empty for default behavior.
	 *
	 * @since 2.0.0
	 */
	public function header_html() {
		$this->widget_row( 'header' );
	}

	/**
	 * Footer Widget Columns.
	 *
	 * This will add the footer widget section to a BoldGrid
	 * theme.  This accepts $columns, which should be a number
	 * of columns to include.  Accepted values should be 1
	 * through 4, or leave empty for default behavior.
	 *
	 * @since 2.0.0
	 */
	public function footer_html() {
		$this->widget_row( 'footer' );
	}
}
