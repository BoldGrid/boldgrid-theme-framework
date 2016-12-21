<?php
/**
 * Class: Boldgrid_Framework_Customizer_Contact_Blocks
 *
 * This is the class responsible for adding the contact blocks
 * that appear in the footer.
 *
 * @since      1.3.5
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Framework_Customizer_Contact Blocks
 *
 * This is the class responsible for adding the contact blocks
 * functionality to the footer.
 *
 * @since      1.3.5
 */
class Boldgrid_Framework_Customizer_Contact_Blocks {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.3.5
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.3.5
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Adds the Contact Details section
	 *
	 * @since 1.3.5
	 */
	public function add_contact_control( $wp_customize ) {
		Kirki::add_field(
			'boldgrid_contact_details',
			array(
				'type'        => 'repeater',
				'label'       => esc_attr__( 'Contact Details', 'bgtfw' ),
				'section'     => 'boldgrid_footer_panel',
				'priority'    => 10,
				'row_label' => array(
					'field' => 'contact_block',
					'type' => 'field',
					'value' => esc_attr__( 'Contact Block', 'bgtfw' ),
				),
				'settings'    => 'boldgrid_contact_details_setting',
				'default'     => $this->configs['customizer-options']['contact-blocks']['defaults'],
				'fields' => array(
					'contact_block' => array(
						'type'        => 'text',
						'label'       => esc_attr__( 'Text', 'bgtfw' ),
						'description' => esc_attr__( 'Enter the text to display in your contact details', 'bgtfw' ),
						'default'     => '',
					),
				),
			)
		);
	}

	/**
	 * Generates the HTML for the contact_block theme mod.
	 *
	 * @since 1.3.5
	 */
	public function contact_block_html() {
		if ( get_theme_mod( 'boldgrid_enable_footer', true ) ) {
			echo $this->generate_html();
		}
	}

	/**
	 * Generates the HTML for the contact_block theme mod.
	 *
	 * @since 1.3.5
	 *
	 * @return String $html Contains the markup for displaying contact block in footer.
	 */
	public function generate_html() {
		// Theme mod to check.
		$theme_mod = get_theme_mod( 'boldgrid_contact_details_setting', $this->configs['customizer-options']['contact-blocks']['defaults'] );
		// Increment css classes if people need to target an individual section.
		$counter = 1;
		// HTML to print.
		$html = '<div class="bgtfw contact-block">';

		foreach ( $theme_mod as $key => $value ) {
			$value = $value['contact_block'];
			// Check if an email was entered in.
			$email = $this->check_for_email( $value );
			// If we don't have an email check if there's a URL entered.
			$value = $email['is_email'] ? $email['value'] : $this->check_for_url( $value );
			// Generate markup for the contact block.
			$html .= "<span class='contact-block-{$counter}'>{$value}</span>";
			// Increment counter.
			$counter++;
		}

		// Close the div.
		$html .= '</div>';
		// Output our string.
		return trim( $html );
	}

	/**
	 * Checks string for any valid email addresses.
	 *
	 * @since 1.3.5
	 *
	 * @param String $value String to search for email in.
	 *
	 * @return Array $email Contains the markup for displaying contact block in footer and if email was found.
	 */
	public function check_for_email( $value ) {
		// Split string into an array.
		$arr = explode( ' ', $value );

		$email = array(
			'is_email' => false,
			'value' => $value,
		);

		// Check if any of these are emails.
		foreach ( $arr as $word ) {
			if ( is_email( $word ) ) {
				$email['is_email'] = $word;
			}
		}

		// If an email is found create a link for it.
		if ( $email['is_email'] ) {
			$formatted = "<a href='mailto:{$email['is_email']}'>{$email['is_email']}</a>";
			$email['value'] = str_replace( $email['is_email'], $formatted, $email['value'] );
		}

		return $email;
	}

	/**
	 * Checks string for any URLs.
	 *
	 * @since 1.3.5
	 *
	 * @param String $value String to search for email in.
	 *
	 * @return String Contains the markup for displaying contact block in footer.
	 */
	public function check_for_url( $value ) {
		return preg_replace( '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@', '<a href="http$2://$4" rel="nofollow">$1$2$3$4</a>', $value );
	}
}
