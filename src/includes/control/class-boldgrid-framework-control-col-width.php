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
	 * Configs.
	 *
	 * @since SINCEVERSION
	 *
	 * @var array
	 */
	public $configs;

	/**
	 * Generic.
	 *
	 * Generic controls class instance.
	 *
	 * @since SINCEVERSION
	 *
	 * @var Boldgrid_Framework_Customizer_Generic
	 */
	public $generic;

	/**
	 * WP Customize.
	 *
	 * WP_Customize class instance.
	 *
	 * @since SINCEVERSION
	 *
	 * @var WP_Customize
	 */
	public $wp_customize;

	/**
	 * Constructor.
	 *
	 * @since SINCEVERSION
	 *
	 * @param array        $configs BGTFW Configs Array.
	 * @param WP_Customize $wp_customize WP_Customize object.
	 */
	public function __construct( $configs, $wp_customize ) {
		$this->configs = $configs;
		$this->generic = new Boldgrid_Framework_Customizer_Generic( $this->configs );
		$this->wp_customize = $wp_customize;
		parent::__construct(
			$wp_customize,
				'bgtfw_header_layout_custom_col_width',
				array(
					'label'    => __( 'Header Column Widths', 'bgtfw' ),
					'section'  => 'bgtfw_header_layout_advanced',
					'settings' => 'bgtfw_header_layout_custom_col_width',
				)
		);
	}

	/**
	 * Get Devices Markup
	 *
	 * @since SINCEVERSION
	 */
	public function get_devices_markup() {
		$svg_dir        = $this->configs['framework']['asset_dir'] . 'img/devices/';
		$devices        = array( 'large', 'desktop', 'tablet', 'phone' );
		$devices_markup = '<ul>';
		foreach ( $devices as $device ) {
			$device_svg = file_get_contents( $svg_dir . $device . '.svg' );
			$checked    = 'large' === $device ? 'checked' : '';

			$devices_markup .= '<li><input id="col-width-devices-' . $device . '" name="col-width-devices" value="' . $device . '" type="radio" ' . $checked . '></input>';
			$devices_markup .= '<label class="devices" title="' . ucfirst( $device ) . '" for="col-width-devices-' . $device . '" data-device="' . ( 'phone' === $device ? 'mobile' : $device ) . '">';
			$devices_markup .= $device_svg;
			$devices_markup .= '</label></li>';
		}
		$devices_markup .= '</ul>';

		return $devices_markup;
	}

	/**
	 * Slider Device Group.
	 *
	 * Returns markup for a slider group for each display size.
	 *
	 * @since SINCEVERSION
	 *
	 * @param string $device The device size.
	 * @param array  $current_layout The current layout.
	 *
	 * @return string Markup for this section.
	 */
	public static function slider_device_group( $device, $current_layout ) {
		$sliders_markup = '<div id="bgtfw_header_layout_custom_col_width-slider-' . $device . '" class="col-width-slider-device-group">';
		$row_number     = 0;
		foreach ( $current_layout as $index => $row ) {
			$full_width = false;
			if ( ! array_key_exists( 'items', $row ) ) {
				continue;
			}
			$items_in_row      = count( $row['items'] );
			$default_col_width = floor( 12 / $items_in_row );

			if ( 'tablet' === $device || 'phone' === $device ) {
				$full_width = true;
			}

			$sliders_markup .= '<div id="bgtfw_header_layout_custom_col_width-slider-' . $row_number . '-' . $device . '" class="col-width-slider" data-row="' . $row_number . '" data-items=\'[';
			foreach ( $row['items'] as $index => $item ) {
				$sliders_markup .= '{"uid": "' . $item['uid'] . '", "key":"' . $item['key'] . '", "width":"' . $default_col_width . '", "device":"' . $device . '"}';
				$sliders_markup .= ( count( $row['items'] ) - 1 ) !== (int) $index ? ', ' : ']\'';
			}
			$sliders_markup .= '></div>';

			$sliders_markup .= '<div class="full-width-wrapper"><input id="bgtfw_header_layout_custom_col_width-slider-'
				. $row_number . '-' . $device . '-full" type="checkbox" class="col-width-full-width" data-row="' . $row_number
				. '" data-device="' . $device . '" value="1" ' . checked( $full_width, true, false ) . '>';
			$sliders_markup .= '<label class="full_width_label">' . esc_html__( 'Full width items for this device size', 'bgtfw' ) . '</label></div><hr />';

			$row_number     += 1;
		}
		$sliders_markup .= '</div>';

		return $sliders_markup;
	}

	/**
	 * Get Sliders Markup.
	 *
	 * @since SINCEVERSION
	 */
	public function get_sliders_markup() {
		$current_layout = get_theme_mod( 'bgtfw_header_layout_custom' );

		$sliders_markup = '<div id="' . esc_attr( $this->id ) . '-sliders-wrapper" class="sliders-wrapper">';
		$devices        = array( 'large', 'desktop', 'tablet', 'phone' );

		foreach ( $devices as $device ) {
			$sliders_markup .= self::slider_device_group( $device, $current_layout );
		}

		$sliders_markup .= '</div>';

		return $sliders_markup;
	}

	/**
	 * Render the pattern control in customizer.
	 *
	 * @since 1.0
	 */
	public function render_content() {
		$devices_markup = $this->get_devices_markup();
		$sliders_markup = $this->get_sliders_markup();
	?>
		<div id="<?php echo esc_attr( $this->id ); ?>-control-wrapper" class="boldgrid-col-width-wrapper">
			<div class='boldgrid-col-width-heading'>
				<label>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				</label>
			</div>
			<div id="<?php echo esc_attr( $this->id ); ?>-devices-wrapper" class="devices-wrapper">
				<?php echo $devices_markup; ?>
			</div>
			<div id="<?php echo esc_attr( $this->id ); ?>-sliders-wrapper" class="sliders-wrapper">
				<?php echo $sliders_markup; ?>
			</div>
			<input type="text" value='<?php echo wp_json_encode( $this->value() ); ?>' class='hidden' <?php echo esc_attr( $this->link() ); ?>>
		</div>
	<?php
	}

	/**
	 * Get Updated Markup
	 *
	 * @since SINCEVERSION
	 *
	 * @param array $layout Layout to use for generating markup.
	 */
	public static function get_updated_markup( $layout ) {
		$devices = array( 'large', 'desktop', 'tablet', 'phone' );

		$sliders_markup = '';

		foreach ( $devices as $device ) {
			$sliders_markup .= self::slider_device_group( $device, $layout );
		}

		return $sliders_markup;
	}
}
