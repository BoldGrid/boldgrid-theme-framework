<?php
/**
 * Responsive Font Size Control
 *
 * @link       http://www.boldgrid.com
 * @since      2.11.0
 *
 * @package    Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework/control
 */

/**
 * Responsive Font Size Control
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework/control
 * @author BoldGrid.com <pdt@boldgrid.com>
 */
class Boldgrid_Framework_Control_Responsive_Font_Size extends WP_Customize_Control {
	/**
	 * Configs.
	 *
	 * @since 2.11.0
	 *
	 * @var array
	 */
	public $configs;

	/**
	 * Generic.
	 *
	 * Generic controls class instance.
	 *
	 * @since 2.11.0
	 *
	 * @var Boldgrid_Framework_Customizer_Generic
	 */
	public $generic;

	/**
	 * Control ID.
	 *
	 * ID of this control.
	 *
	 * @since 2.11.0
	 *
	 * @var string
	 */
	public $control_id;

	/**
	 * WP Customize.
	 *
	 * WP_Customize class instance.
	 *
	 * @since 2.11.0
	 *
	 * @var WP_Customize
	 */
	public $wp_customize;

	/**
	 * Constructor.
	 *
	 * @since 2.11.0
	 *
	 * @param array        $configs      BGTFW Configs Array.
	 * @param WP_Customize $wp_customize WP_Customize object.
	 * @param string       $control_id   Control ID.
	 * @param array        $params       Control arguments.
	 */
	public function __construct( $configs, $wp_customize, $control_id, $params ) {
		$this->control_id   = $control_id;
		$this->configs      = $configs;
		$this->generic      = new Boldgrid_Framework_Customizer_Generic( $this->configs );
		$this->wp_customize = $wp_customize;
		parent::__construct(
			$wp_customize,
			$control_id,
			$params
		);
	}

	/**
	 * Get Devices Markup
	 *
	 * @global WP_Filesystem $wp_filesystem
	 *
	 * @return string Devices Markup.
	 *
	 * @since 2.11.0
	 */
	public function get_devices_markup() {
		global $wp_filesystem;
		$svg_dir        = $this->configs['framework']['asset_dir'] . 'img/devices/';
		$devices        = array( 'large', 'desktop', 'tablet', 'phone' );
		$devices_markup = '<ul>';
		foreach ( $devices as $device ) {
			$device_svg = $wp_filesystem->get_contents( $svg_dir . $device . '.svg' );
			$checked    = 'large' === $device ? 'checked' : '';

			$devices_markup .= '<li><input id=' . esc_attr( $this->id ) . '-devices-' . $device . '" name="' . esc_attr( $this->id ) . '-devices" value="' . $device . '" type="radio" ' . $checked . '></input>';
			$devices_markup .= '<label class="devices" title="' . ucfirst( $device ) . '" for="' . esc_attr( $this->id ) . '-devices-' . $device . '" data-device="' . ( 'phone' === $device ? 'mobile' : $device ) . '">';
			$devices_markup .= $device_svg;
			$devices_markup .= '</label></li>';
		}
		$devices_markup .= '</ul>';

		return $devices_markup;
	}

	/**
	 * Get Input Fields Markup.
	 *
	 * @since 2.11.0
	 *
	 * @return string Input Fields Markup.
	 */
	public function get_input_fields() {
		$current_size = json_decode( get_theme_mod( $this->id ), true );
		$current_size = $current_size ? $current_size : array();

		$font_size_group = '<div id="' . esc_attr( $this->id ) . '-font-size-wrapper" class="font-size-wrapper">';
		$devices         = array( 'large', 'desktop', 'tablet', 'phone' );

		foreach ( $devices as $device ) {
			$current_device_size = isset( $current_size[ $device ] ) ? $current_size[ $device ] : '';
			$font_size_group    .= '<p class="font-size-input ' . $device . '">';
			$font_size_group    .= '<input id="' . esc_attr( $this->id ) . '-font-size-' . $device . '" type="text" ';
			$font_size_group    .= 'name="' . esc_attr( $this->id ) . '-font-size-' . $device . '" value="' . $current_device_size . '" class="font-size-input" ';
			$font_size_group    .= 'data-device="' . $device . '">';
			$font_size_group    .= '</p>';
		}

		$font_size_group .= '</div>';

		return $font_size_group;
	}

	/**
	 * Render the pattern control in customizer.
	 *
	 * @since 2.11.0
	 */
	public function render_content() {
		$devices_markup = $this->get_devices_markup();
		$input_markup   = $this->get_input_fields();
	?>
		<div id="<?php echo esc_attr( $this->id ); ?>-control-wrapper" class="boldgrid-responsive-font-size-wrapper">
			<h5><?php echo esc_html( $this->label ); ?></h5>
			<div>
				<div id="<?php echo esc_attr( $this->id ); ?>-input-wrapper" class="input-wrapper">
					<?php echo $input_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div id="<?php echo esc_attr( $this->id ); ?>-devices-wrapper" class="devices-wrapper">
					<?php echo $devices_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
		<input class='<?php echo esc_attr( $this->id ); ?>-hidden-value' type="hidden" <?php echo esc_attr( $this->link() ); ?> >
	<?php
	}
}
