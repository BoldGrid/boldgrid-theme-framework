<?php
/**
 * The class responsible for filtering Ninja Forms output and adding appropriate Bootstrap Classes.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework/Ninja_Forms
 * @link       https://boldgrid.com
 *
 * Special thanks to Boston Dell-Vandenberg for his work on quickly adding
 * Bootstrap styles to Ninja Forms forms.
 *
 * @link http://www.pomelodesign.com
 */

/**
 * The class responsible for filtering Ninja Forms output and adding appropriate Bootstrap Classes.
 *
 * Special thanks to Boston Dell-Vandenberg for his work on quickly adding
 * Bootstrap styles to Ninja Forms forms.
 *
 * @link http://www.pomelodesign.com
 * @since      1.0.0
 */
class BoldGrid_Framework_Ninja_Forms {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     1.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Modifies form wrap classes
	 *
	 * @param string $wrap_class Bootstrap class to add to the form wrap.
	 * @param string $form_id Form ID to apply class to.
	 * @since  1.0.0
	 */
	public function forms_form_wrap_class( $wrap_class, $form_id ) {
		$wrap_class = apply_filters( 'bootstrap_ninja_forms_form_wrap_class', '-bootstrap' );
		return $wrap_class;
	}

	/**
	 * Modifies form element classes
	 *
	 * @param string $form_class Bootstrap class to apply to form.
	 * @param string $form_id Form ID to apply class to.
	 * @since  1.0.0
	 */
	public function forms_form_class( $form_class, $form_id ) {
		$form_class = apply_filters( 'bootstrap_ninja_forms_form_class', 'ninja-forms-bootstrap' );
		return $form_class;
	}

	/**
	 * Modifies form label classes
	 *
	 * @param string $label_class Bootstrap class to add to labels.
	 * @param string $field_id Field ID to apply class to.
	 * @since  1.0.1
	 */
	public function forms_label_class( $label_class, $field_id ) {
		$label_class .= ' control-label';
		return $label_class;
	}

	/**
	 * Modifies field wrap classes
	 *
	 * @param string $field_wrap_class Bootstrap class to add to for field wraps.
	 * @param string $field_id Field ID to apply class to.
	 * @since 1.0.0
	 */
	public function forms_field_wrap_class( $field_wrap_class, $field_id ) {

		$settings = $this->get_field_settings( $field_id );

		$field_wrap_class = str_replace( 'field-wrap', 'field-wrap form-group', $field_wrap_class );
		$field_wrap_class = str_replace( 'ninja-forms-error', 'ninja-forms-error has-error', $field_wrap_class );

		return $field_wrap_class;
	}

	/**
	 * Modifies form field classes.
	 *
	 * @param array  $data Form fields to modify.
	 * @param string $field_id Field ID to modify.
	 * @since 1.0.0
	 */
	public function forms_field( $data, $field_id ) {

		$settings = $this->get_field_settings( $field_id );

		if ( null === $settings || empty( $settings['type'] ) ) {
			return $data;
		}

		if ( empty( $data['class'] ) ) {
			$data['class'] = '';
		}

		if ( '_text' === $settings['type'] ||
			'_textarea' === $settings['type'] ||
			'_profile_pass' === $settings['type'] ||
			'_spam' === $settings['type'] ||
			'_number' === $settings['type'] ||
			'_country' === $settings['type'] ||
			'_tax' === $settings['type'] ||
			'_calc' === $settings['type'] ) {
			$data['class'] .= ' form-control';
		}

		if ( '_desc' === $settings['type'] ) {
			$data['class'] .= ' form-group';
		}

		if ( '_list' === $settings['type'] ) {
			if ( 'checkbox' !== $settings['data']['list_type'] && 'radio' !== $settings['data']['list_type'] ) {
				$data['class'] .= ' form-control';
			}
		}

		if ( '_submit' === $settings['type'] ) {
			$data['class'] .= ' button-primary';
		}

		return $data;
	}

	/**
	 * Set class for field descriptions
	 *
	 * @param string $class Bootstrap class to add to form responses.
	 * @param string $field_id Field ID to apply class to.
	 * @since 1.1.0
	 */
	public function field_description_class( $class, $field_id ) {
		$class .= ' help-block';
		return $class;
	}

	/**
	 * Set class for field error message
	 *
	 * @param string $class Bootstrap class to add to form responses.
	 * @param string $field_id Field ID to apply class to.
	 * @since 1.1.0
	 */
	public function field_error_message_class( $class, $field_id ) {
		$class .= ' help-block';
		return $class;
	}

	/**
	 * Set class for required items message
	 *
	 * @param string $class Bootstrap class to add to form responses.
	 * @param string $form_id Form ID to apply class to.
	 * @since 1.1.0
	 */
	public function form_required_items_class( $class, $form_id ) {
		$class .= ' alert alert-warning';
		return $class;
	}

	/**
	 * Set class for response message
	 *
	 * @since 1.1.0
	 * @param string $class Bootstrap class to add to form responses.
	 * @param string $form_id Form ID to apply class to.
	 */
	public function form_response_message_class( $class, $form_id ) {
		$class .= ' alert';
		if ( strpos( $class, 'ninja-forms-error-msg' ) !== false ) {
			$class .= ' alert-danger';
		} elseif ( strpos( $class, 'ninja-forms-success-msg' ) !== false ) {
			$class .= ' alert-success';
		} else {
			$class .= ' alert-warning';
		}

		return $class;
	}

	/**
	 * Gets field settings for specified field ID
	 *
	 * @since 1.0.0
	 *
	 * @param string $field_id Field ID to get settings for.
	 */
	private function get_field_settings( $field_id ) {

		global $ninja_forms_loading;
		global $ninja_forms_processing;

		if ( is_object( $ninja_forms_processing ) ) {
			$field_row = $ninja_forms_processing->get_field_settings( $field_id );
		} else if ( is_object( $ninja_forms_loading ) ) {
			$field_row = $ninja_forms_loading->get_field_settings( $field_id );
		} else {
			$field_row = null;
		}

		return $field_row;
	}
}
