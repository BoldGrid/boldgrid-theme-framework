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
class Boldgrid_Framework_Control_Pattern extends WP_Customize_Control {

	/**
	 * Scripts/styles to enqueue in customizer.
	 *
	 * @since 1.0
	 */
	public function enqueue() {
		wp_enqueue_script( 'boldgrid-framework-customizer-background' );
		wp_enqueue_style( 'boldgird-theme-helper-color-palette' );
	}

	/**
	 * Render the pattern control in customizer.
	 *
	 * @since 1.0
	 */
	public function render_content() {
	?>
		<div id="<?php echo esc_attr( $this->id ); ?>-control-wrapper" class="boldgrid-pattern-wrapper" data-pattern-selected="<?php echo ( bool ) $this->value(); ?>">
			<div class='boldgrid-pattern-selection-heading'>
				<label>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				</label>
				<div>
					<a id="<?php echo esc_attr( $this->id ); ?>-remove-pattern" <?php echo ( ! $this->value() ) ? 'disabled="disabled"' : ''; ?>class='button remove-selected-pattern'></a>
				</div>
			</div>
			<div id="<?php echo esc_attr( $this->id ); ?>" class="pattern-wrapper">
				<div class="pattern-preview-wrapper">
				<?php foreach ( $this->choices as $pattern => $name ) { ?>
					<div class="patternpreview" data-background="<?php echo esc_attr( $pattern ); ?>"></div>
				<?php } ?>
				</div>
				<input type="text" val='<?php echo esc_attr( $this->value() ); ?>' class='hidden' <?php echo esc_attr( $this->link() ); ?>>
			</div>
		</div>
	<?php
	}
}
