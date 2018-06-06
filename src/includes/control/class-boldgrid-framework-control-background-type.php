<?php
/**
 * Color Palette Customizer Control
 *
 * @link       http://www.boldgrid.com
 * @since      1.0.0
 *
 * @package    Boldgrid_Theme_Helper
 * @subpackage Boldgrid_Theme_Helper/admin
 */

/**
 * Color Palette Customizer Control
 *
 * @package Boldgrid_Theme_Helper
 * @subpackage Boldgrid_Theme_Helper/admin
 * @author BoldGrid.com <pdt@boldgrid.com>
 */
class Boldgrid_Framework_Control_Background_Type extends WP_Customize_Control {

	/**
	 * Scripts to enqueue in customizer.
	 *
	 * @since 1.0
	 */
	public function enqueue() {
		wp_enqueue_script( 'boldgrid-framework-customizer-background' );
		wp_enqueue_style( 'boldgird-theme-helper-color-palette' );
	}

	/**
	 * Render the background type control.
	 *
	 * @since 1.0
	 */
	public function render_content() {
		?>
		<div class='background-type-controls'>
			<div id="<?php echo esc_attr( $this->id ); ?>">
				<input type="radio"
					id="radio1" name="radio" <?php echo checked( $this->value(), 'image' ); ?> value='image'
					<?php $this->link();?>><label for="radio1">Image <span
					class="dashicons dashicons-format-image"></span></label>

				<input type="radio"
					id="radio2" name="radio" value='pattern' <?php echo checked( $this->value(), 'pattern' ); ?>
					<?php $this->link();?>><label for="radio2">Pattern & Color <span
					class="dashicons dashicons-art"></span></label>
		</div>
		<?php
	}
}
?>
