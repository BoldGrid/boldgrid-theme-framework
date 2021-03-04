<?php
/**
 * Column Width Control
 *
 * @link       http://www.boldgrid.com
 * @since      1.0.0
 *
 * @package    Boldgrid_Theme_Helper
 * @subpackage Boldgrid_Theme_Helper/admin
 */

/**
 * Column Width Control
 *
 * @package Boldgrid_Theme_Helper
 * @subpackage Boldgrid_Theme_Helper/admin
 * @author BoldGrid.com <pdt@boldgrid.com>
 */
class Boldgrid_Framework_Control_Col_Width extends WP_Customize_Control {
	/**
	 * Render the pattern control in customizer.
	 *
	 * @since 1.0
	 */
	public function render_content() {
	?>
		<div id="<?php echo esc_attr( $this->id ); ?>-control-wrapper" class="boldgrid-col-width-wrapper">
			<div class='boldgrid-col-width-heading'>
				<label>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				</label>
			</div>
			<div id="<?php echo esc_attr( $this->id ); ?>-devices-wrapper" class="devices-wrapper"></div>
			<div id="<?php echo esc_attr( $this->id ); ?>-sliders-wrapper" class="sliders-wrapper"></div>
			<input type="text" val='<?php echo esc_attr( $this->value() ); ?>' class='hidden' <?php echo esc_attr( $this->link() ); ?>>
		</div>
	<?php
	}
}
