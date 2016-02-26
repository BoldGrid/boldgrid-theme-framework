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

		public function enqueue() {
			wp_enqueue_script('boldgrid-framework-customizer-background');
			wp_enqueue_style('boldgird-theme-helper-color-palette');
		}
		
		public function render_content() { ?>
		<div class='boldgrid-pattern-wrapper' data-pattern-selected="<?php echo (bool) $this->value(); ?>">
			<div class='boldgrid-pattern-selection-heading'>
				<label>
						<span class="customize-control-title">Background Pattern</span>
				</label>
				<div>
					<a <?php echo (!$this->value()) ? 'disabled="disabled"' : ''; ?>class='button remove-selected-pattern'></a>
				</div>
			</div>
			<div id="<?php echo $this->id ?>" class='pattern-wrapper'>
				<div class='pattern-preview-wrapper'>
				<?php foreach ( $this->choices['patterns'] as $pattern ) { 
					$active_class = '';
					if (  ("url(" . $pattern['uri'] . ")") == $this->value() ) {
						$active_class = 'active-pattern';
					}
					?>
					<div class="patternpreview <?php echo $active_class; ?>" style='background-image:url("<?php echo esc_attr($pattern['uri'])?>")'></div>
				<?php } ?>
				
				</div>
				<input type="text" val='<?php echo esc_attr($this->value()); ?>' class='hidden' <?php echo  esc_attr($this->link()); ?>>
			</div>
		</div>
	<?php
	 }
}
?>