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
class Boldgrid_Framework_Control_Palette extends WP_Customize_Control {

	/**
	 * The options field type
	 * @var string
	 */
	public $type = 'textarea';

	/**
	 * Enqueue the needed color palette assets
	 */
	public function enqueue() {
		wp_enqueue_script( 'boldgird-theme-helper-color-palette' );
		wp_enqueue_style( 'boldgird-theme-helper-color-palette' );
	}

	/**
	 * Render the set of color palettes.
	 *
	 * @since 1.0
	 */
	public function render_content() {
		$color_palettes = ! empty( $this->choices['palettes'] ) ? $this->choices['palettes'] : array ();
		$has_neutral_color = ! empty( $color_palettes['palettes'][0]['neutral-color'] );
		$color_palatte_columns = $color_palettes['color-palette-size'] + ( (int) $has_neutral_color );
		?>
	<span class="customize-control-title"><?php echo $this->label; ?></span>

	<?php if ( ! empty( $this->description ) ) : ?>
		<span class="description customize-control-description"><?php echo $this->description; ?></span>
	<?php endif; ?>

<h3>Active Palette</h3>
<div class='boldgrid-color-palette-wrapper color-palette-columns-<?php echo $color_palatte_columns;?>'
	 data-color-formats='<?php echo json_encode($color_palettes['palette_formats']); ?>'
	 data-has-neutral='<?php echo esc_attr( $has_neutral_color ); ?>'
	 data-num-colors='<?php echo esc_attr( $color_palatte_columns ); ?>'
	 >
	<?php
	foreach ( $color_palettes['palettes'] as $palette_name => $color_palette ): ?>
	<div data-palette-wrapper='true'>
		<?php
		$palette_id = !empty($color_palette['palette_id']) ? $color_palette['palette_id'] : '';
		if ( $has_neutral_color ) {
			$color_palette['colors'][] = $color_palette['neutral-color'];
		}

		// The following attributes that are not escaped are all booleans.
		 ?>
		<ul class='boldgrid-inactive-palette'
			 data-is-default="<?php echo !empty( $color_palette['default'] ); ?>"
			 data-is-active="<?php echo !empty( $color_palette['is_active'] ); ?>"
			 data-color-palette-format="<?php echo esc_attr( $color_palette['format'] ); ?>"
			 data-copy-on-mod="<?php echo !empty( $color_palette['copy_on_mod'] ); ?>"
			 data-palette-id="<?php echo esc_attr( $palette_id); ?>"
			 <?php echo ! empty( $color_palette['neutral-color'] )
			 	? 'data-neutral-color="' . esc_attr( $color_palette['neutral-color'] ) . '"' : '';?>
		 >
		 	<li class='boldgrid-palette-colors'>
				<?php
				foreach ( $color_palette['colors'] as $key => $color ): ?>
					<span data-color="<?php echo esc_attr( $color ); ?>" style="background: <?php echo esc_attr( $color ); ?>"></span>
				<?php endforeach; ?>
				<div class='boldgrid-duplicate-dashicons'>
					<span class="dashicons dashicons-admin-post boldgrid-copy-palette" title="Save Palette"></span>
					<span class="dashicons dashicons-no boldgrid-remove-palette" title="Remove Palette"></span>
				</div>
	        </li>
		</ul>
	</div>
	<?php endforeach; ?>
		<div class='palette-action-buttons hidden'>

		<button class='button button-primary palette-creator-button palette-generator-button' type='button'>Suggest Palettes</button>

		<input type="text" value="#ffffff" class='pluto-color-control' data-palette="true" />
		<input type="textarea" class='hidden palette-option-field' <?php echo esc_attr($this->link()); ?> val='<?php echo esc_attr($this->value()); ?>'/>

			<div class='generate-palettes-selection-section'>
				<h3>Suggested Palettes</h3>
				<div class='generated-palettes-container'>
				</div>
				<input type="button" class="button cancel-generated-palettes-button" value="Done">
			</div>

			<div class='saved-palettes-divider'>
				<h3>Saved Palettes</h3>
			</div>
		</div>
	</div>
		<?php
	}
}
