<?php
/**
 * Active callback used with the "required" argument in fields
 *
 * @package     Kirki
 * @category    Core
 * @author      Aristeides Stathopoulos
 * @copyright   Copyright (c) 2016, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 */

if ( ! class_exists( 'Kirki_Active_Callback' ) ) {

	/**
	 * Callback class for use with the "required" argument
	 */
	class Kirki_Active_Callback {

		/**
		 * Figure out whether the current object should be displayed or not.
		 *
		 * @param  WP_Customize_Setting $object The current field.
		 * @return boolean
		 */
		public static function evaluate( $object ) {

			// Get all fields.
			$fields = Kirki::$fields;

			// Make sure the current object matches a registered field.
			if ( ! isset( $object->setting->id ) || ! isset( $fields[ $object->setting->id ] ) ) {
				return true;
			}

			$current_object = $fields[ $object->setting->id ];

			if ( isset( $current_object['required'] ) && is_array( $current_object['required'] ) ) {

				foreach ( $current_object['required'] as $requirement ) {
					if ( ! isset( $requirement['operator'] ) || ! isset( $requirement['value'] ) || ! isset( $requirement['setting'] ) ) {
						return true;
					}

					if ( isset( $current_object['option_name'] ) && '' !== $current_object['option_name'] ) {
						if ( false === strpos( $requirement['setting'], '[' ) ) {
							$requirement['setting'] = $current_object['option_name'] . '[' . $requirement['setting'] . ']';
						}
					}

					$current_setting = $object->manager->get_setting( $requirement['setting'] );

					/**
					 * If the setting required does not exist, then show the control.
					 * This ensures that even if we enter the wrong settings,
					 * the field will not mysteriously disappear.
					 */
					if ( ! is_object( $current_setting ) ) {
						$show = true;
					}

					/**
					 * If one of the conditions is false,
					 * then we don't need to further proceed.
					 * ALL conditions must be met in order to show the control,
					 * so we'll return early and terminate the loop.
					 */
					if ( isset( $show ) && ! $show ) {
						return false;
					}

					/**
					 * Depending on the 'operator' argument we use,
					 * we'll need to perform the appropriate comparison
					 * and figure out if the control will be shown or not.
					 */
					$show = self::compare(
						$requirement['value'],
						$current_setting->value(),
						$requirement['operator']
					);
				}
			}

			return ( isset( $show ) && ( false === $show ) ) ? false : true;

		}

		/**
		 * Compares the 2 values given the condition
		 *
		 * @param mixed  $value1   The 1st value in the comparison.
		 * @param mixed  $value2   The 2nd value in the comparison.
		 * @param string $operator The operator we'll use for the comparison.
		 * @return boolean whether The comparison has succeded (true) or failed (false).
		 */
		public static function compare( $value1, $value2, $operator ) {
			switch ( $operator ) {
				case '===':
					$show = ( $value1 === $value2 ) ? true : false;
					break;
				case '==':
				case '=':
				case 'equals':
				case 'equal':
					$show = ( $value1 == $value2 ) ? true : false;
					break;
				case '!==':
					$show = ( $value1 !== $value2 ) ? true : false;
					break;
				case '!=':
				case 'not equal':
					$show = ( $value1 != $value2 ) ? true : false;
					break;
				case '>=':
				case 'greater or equal':
				case 'equal or greater':
					$show = ( $value1 >= $value2 ) ? true : false;
					break;
				case '<=':
				case 'smaller or equal':
				case 'equal or smaller':
					$show = ( $value1 <= $value2 ) ? true : false;
					break;
				case '>':
				case 'greater':
					$show = ( $value1 > $value2 ) ? true : false;
					break;
				case '<':
				case 'smaller':
					$show = ( $value1 < $value2 ) ? true : false;
					break;
				case 'contains':
				case 'in':
					if ( is_array( $value1 ) && ! is_array( $value2 ) ) {
						$array  = $value1;
						$string = $value2;
					} elseif ( is_array( $value2 ) && ! is_array( $value1 ) ) {
						$array  = $value2;
						$string = $value1;
					}
					if ( isset( $array ) && isset( $string ) ) {
						if ( ! in_array( $string, $array ) ) {
							$show = false;
						}
					} else {
						if ( false === strrpos( $value1, $value2 ) && false === strpos( $value2, $value1 ) ) {
							$show = false;
						}
					}
					break;
				default:
					$show = ( $value1 == $value2 ) ? true : false;

			}

			if ( isset( $show ) ) {
				return $show;
			}

			return true;
		}
	}
}
